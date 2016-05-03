<?php

/*
*得到销售订单脚本
*
*
*/

include_once('/home/www/new_myled.com/script/conf.php'); 

//include_once('D://XAMPP/htdocs/new_myled/script/conf2.php');
function get_goods_info(){
    $host ='https://www.myled.com';
    /*
    $category_str =array(207);
    $in_cat =array();
    foreach($category_str as $cat_id){
        $subcat =getSunCat($cat_id);
        $in_cat =array_merge($in_cat,$subcat); 
    }
    $in_category_str =implode(',',$in_cat);
    */
    $category_id=207;
    $lang_id =1;
    $sku_data =get_us_sku();
    $catalog_data =get_catagory_sku($category_id);
    foreach($sku_data as $p_id=>$qty){
        if(!in_array($p_id,$catalog_data)){
            $catalog_data[] =$p_id;
        }
    }
    $res =array();
    foreach($catalog_data as $product_id){
        $sql ="select distinct p.product_id,p.length,p.width,p.height,p.weight,pd.meta_keyword,p.model as sku,pd.name,p.url_path,
        p.image,p.price as base_price
        from oc_product as p
        left join oc_product_description as pd  on p.product_id=pd.product_id

        WHERE p.stock_status_id=7 and pd.language_id=".$lang_id." and p.product_id =".$product_id." limit 1";
        $query =mysql_query($sql);
        $row=mysql_fetch_assoc($query);
        $row['image'] =$host."/image/".$row['image'];
        $check =is_amazon_500_and_white($row['image']);
         if($check){
            $row['link'] =$host.'/'.$row['url_path'].".html";
            //商品分类
            $query_catagory =mysql_query("select max(category_id) as category_id  from oc_product_to_category where product_id=".$row['product_id']);
            $res_catagory =mysql_fetch_assoc($query_catagory);
            $category_id =$res_catagory['category_id'];
            $query_path =mysql_query("SELECT path from oc_category where category_id='".$category_id."'");
            $res_path =mysql_fetch_assoc($query_path);
            $path =$res_path['path'];
            $cat_arr =explode("/",$path);
            $catagory_arr =array();
            foreach($cat_arr as $cat_id){
                if($cat_id>0){
                    $query_cat_name =mysql_query("SELECT name from oc_category_description where category_id='".$cat_id."' and language_id=".$lang_id);
                    $row_cat =mysql_fetch_assoc($query_cat_name);
                    $catagory_arr[]=$row_cat['name'];
                }
            }
            $row['catagory'] ="Home>".implode(">",$catagory_arr);
            //得到商品运费
            if($row['weight']<=2000){
                $sql_2 = "select price from oc_shipping_matrixrate where dest_country_id ='US' and delivery_type='Super Saver Shipping (10-20 Working Days)' and condition_from_value<='$row[weight]' and condition_to_value>='$row[weight]' ";
                $query_2 =mysql_query($sql_2);
                $to_row =mysql_fetch_row($query_2);

                if(!empty($to_row)){
                    $row['shipping_cost'] =number_format($to_row[0],2) ;
                }
            }
            elseif($row['weight']>2000){
                $sql_3 = "select price from oc_shipping_matrixrate where dest_country_id ='US' and delivery_type='Expedited Shipping (3-6 Working Days)' and condition_from_value<='$row[weight]' and condition_to_value>='$row[weight]' ";
                $query_3 =mysql_query($sql_3);
                $to_row_3 =mysql_fetch_row($query_3);
                if(!empty($to_row_3)){
                    $row['shipping_cost'] =number_format($to_row_3[0],2);
                }
            }
            //得到商品的前3个参数
            $query_can=mysql_query("select  naov.option_value from oc_new_product_attribute as npa left join oc_new_attribute_option_value as naov on npa.attr_option_value_id=naov.option_id  where naov.language_id=1 and  npa.product_id =".$product_id." limit 3");
            $i=1;
            while($row_canshu =mysql_fetch_assoc($query_can)){
                $row['canshu_'.$i] =$row_canshu['option_value'];
               
                $i++;
            }
            $row['length'] =number_format($row['length'],2) ;
            $row['width'] =number_format($row['width'],2) ;
            $row['height'] =number_format($row['height'],2) ;
            $row['weight'] =number_format($row['weight'],2) ;
            $res[] =$row ;
        }
    }
    return $res;
}

function get_feed_csv(){
    $order_info =get_goods_info();
    $file ='/home/www/new_myled.com/shopping_feed/amazon_feed.csv';
    //$file ='D://XAMPP/htdocs/new_myled/script/amazon_feed.csv';
    if(file_exists($file)){
            unlink($file);
    }
    $fp = fopen($file, 'w+');
    chmod($file, 0777);
    fputcsv($fp,array('Category','Title','Link','SKU','Price','Image','Description','Shipping Cost','Bullet point1','Bullet point2','Bullet point3','Length','Width','Height','Weight','Keywords1'));
    foreach($order_info as $order){
        $new_arr =array();
        if($order){
            $new_arr[]=$order['catagory'];
            $new_arr[]=$order['name'];
            $new_arr[]=$order['link'];
            $new_arr[]=$order['sku'];
            $new_arr[]=$order['base_price'];
            $new_arr[]=$order['image'];
            $new_arr[]=$order['name'];
            $new_arr[]=$order['shipping_cost'];
            $new_arr[]=$order['canshu_1'];
            $new_arr[]=$order['canshu_2'];
            $new_arr[]=$order['canshu_3'];
            $new_arr[]=$order['length'];
            $new_arr[]=$order['width'];
            $new_arr[]=$order['height'];
            $new_arr[]=$order['weight'];
            $new_arr[]=$order['meta_keyword'];
            fputcsv($fp,$new_arr);
        }
    }
    fclose($fp);
}

function getSunCat($cat_id){
    $query_1 =mysql_query("select path from oc_category where category_id=".$cat_id ." limit 1");
    $res_1 =mysql_fetch_assoc($query_1);
    $path =$res_1['path'];
    $query_2 =mysql_query("select category_id from oc_category where path like '".$path."%'");
    $res =array();
    while($res_2 =mysql_fetch_assoc($query_2)){
        $res[] =$res_2['category_id'];
    }
    return $res;
}

function is_amazon_500_and_white($img_file){
   
    $image_size   =   getimagesize($img_file); 
    $width =  $image_size[0]-1;
    $height =   $image_size[1]-1;
    if( $width < 500 || $height < 500){
        return false;
    }
    else{
         return true;
    }
    /*
    $points = array(
        array(0,0),
        array($width,0),
        array(0,$height),
        array($width,$height),
    );
    $i=imagecreatefromjpeg($img_file);
    $is_white = true;
    foreach($points as $item){
        $p1 = imagecolorat($i,$item[0],$item[1]);
        $r = ($p1 >> 16) & 0xFF;
        $g = ($p1 >> 8) & 0xFF;
        $b = $p1 & 0xFF;
        if($r != 255 ||  $g != 255 ||  $b != 255){
            $is_white = false;
        }
    }
    return $is_white; 
    */
}    


//得到美国购买的超过50的sku
function get_us_sku(){
    $sql ="select o.order_id,op.product_id,op.quantity from oc_order as o left join oc_order_product as op on o.order_id =op.order_id where o.shipping_country_code='US' ";
    $query=mysql_query($sql);
    $data =array();
    while($row =mysql_fetch_assoc($query)){
        if(isset($data[$row['product_id']])){
            $data[$row['product_id']] +=intval($row['quantity']);
        }
        else{
            $data[$row['product_id']] =intval($row['quantity']);
        }
        
    }
    foreach($data as $key=>$item){
        if($item<50){
            unset($data[$key]);
        }
    }
    return $data;
}

//得到分类下的所有商品
function get_catagory_sku($catagory_id){
    $data =array();
    $sql ="select product_id from  oc_product_to_category where category_id=".$catagory_id;
    $query= mysql_query($sql);
    while($row =mysql_fetch_assoc($query)){
        $data[] =$row['product_id'];
    }
    //得到置顶商品

$sku_array =array(1020721728,1020722108,1020722110,1020722112,1020722114,1020722116,1020722118,1020722120,1020722122,1020722124,1020722126,1020722128,1020722130,1020722132,1020722134,1020722136,1020722138,1020722140,1020722142,1020722144,1020722146,1020722156,1020722158,1020722164,1020722166,1020722170,1020722172,1020722176,1020722178,1020731017,1020731019,1020731025,1020731027,1020731031,1020731033,1020731035,1020731037,1020731043,1020731044,1020731051,1020731052,1020821727,1020821730,1020831018,1020831020,1020831022,1020831024,1020831026,1020831028,1020831032,1020831034,1020831036,1020831038,1020922109,1020922111,1020922171,1020922173,1020922193,1100922056,1020922129,1020922131,1000721658,1000721692,1000721694,1000721702,1000721704,1000721712,1000721714,1000721716,1000722198,1000722206,1000921659,1000921693,1000921695,1000921713,1000921715,1000922199,1000922207,1020721678,1020721724,1020721726,1020722160,1020722162,1020722180,1020722182,1020722184,1020722186,1020722190,1020722192,1020722194,1020722196,1020722200,1020722204,1020724771,1020731047,1020731049,1020831046,1020831048,1020831050,1020921681,1020922113,1020922115,1020922117,1020922119,1020922121,1020922123,1020922125,1020922127,1020922133,1020922135,1020922137,1020922139,1020922145,1020922147,1020922157,1020922159,1020922161,1020922163,1020922168,1020922175,1020922197,1020922201,1020922205,1020924772,1100922044,1100922045,1100922055,1100922057,1100922058,1110322049,1110922053,1120022050,1120922047,1120922048,1170922052,1170926629,1170926630,1500924826,1000026588,1000721658,1000721660,1000721662,1000721664,1000721666,1000721668,1000721688,1000721690,1000721700,1000724779,1000921659,1000921661,1000921663,1000921665,1000921667,1000921669,1000921689,1000921691,1000921697,1000921699,1000921701,1000921715,1020721652,1020721654,1020721656,1020721670,1020721672,1020721674,1020721676,1020721678,1020721680,1020721682,1020721684,1020724797,1020724799,1020726596,1020726598,1020726600,1020726602,1020726604,1020726606,1020726608,1020726612,1020726616,1020921653,1020921655,1020921657,1020921671,1020921673,1020921675,1020921677,1020921679,1020921681,1020922169,1020922177,1020922187,1020922189,1020922191,1020922195,1020924798,1020924800,1020926597,1020926599,1020926601,1020926603,1020926605,1020926607,1020926609,1020926613,1020926617,1100922062,1100922064,1100922065,1100922068,1100922069,1100922074,1100922076,1110324794,1110922054,1120024792,1120024793,1120921606,1120921607,1120921608,1120921610,1120921611,1120921613,1120921615,1120924796,1130024790,1130124791,1130324795,1130724784,1130922084,1130924778,1130924783,1130924788,1130924789,1140021627,1140121626,1140721619,1140921616,1140921618,1140921620,1140921622,1140921624,1140921628,1150221594,1150221597,1150221602,1150221605,1150721593,1150721596,1150721601,1150721604,1150722087,1150722091,1150921595,1150921598,1150921603,1150922088,1150922090,1150922092,1170922059,1170922060,1170922061,1170922066,1170922067,1170922070,1170922071,1170922078,1170922079,1180024816,1180224815,1180224817,1180722093,1180722097,1180722101,1180922094,1180922096,1180922103,1180924810,1180924811,1180924813,1180924814,1180924818,1000126589,1000226590,1000326591,1000721696,1000721698,1000721706,1000721718,1000724781,1000724803,1000724805,1000724807,1000727595,1000727624,1000727641,1000727647,1000727649,1000727651,1000727653,1000727655,1000921705,1000921707,1000921719,1000924780,1000924782,1000924804,1000924806,1000924808,1000927596,1000927625,1000927642,1000927648,1000927650,1000927652,1000927654,1000927656,1010721708,1010721710,1010921709,1010921711,1020026593,1020126594,1020226595,1020721686,1020724801,1020724819,1020724821,1020724823,1020726679,1020727586,1020727588,1020727590,1020727599,1020727601,1020727603,1020727605,1020727607,1020727609,1020727611,1020727613,1020727615,1020727617,1020727629,1020727631,1020727633,1020727635,1020921687,1020922179,1020924802,1020924820,1020924822,1020924824,1020926680,1020927600,1020927602,1020927612,1020927614,1020927630,1020927634,1100922063,1100922072,1100922073,1100922075,1100922077,1110927594,1120921612,1120921614,1120927623,1130921578,1130921579,1130921580,1130921581,1130921582,1130921584,1130922082,1130922083,1130924777,1130924785,1130924786,1130924787,1140221625,1140721617,1140921621,1150722089,1150921592,1150921599,1150921600,1160924774,1160924775,1160924776,1160924809,1170927621,1170927627,1180022104,1180126592,1180222105,1180722095,1180722099,1180722106,1180922098,1180922100,1180922102,1180922107,1180924812,1221221629,1221221630,1221221631,1221221632,1221221633,1221221634,1221221635,1221221636,1221221637,1221221638,1500024827,1500024837,1500024842,1500124840,1500224829,1500224839,1500324828,1500324838,1500624833,1500724825,1500724835,1500924826,1500924831,1500924834,1500924836,2400922208,1000723142,1000723144,1000723146,1000723148,1000723150,1000723152,1000723154,1000723156,1000723158,1000723160,1000723162,1000723164,1000723166,1000723168,1000723170,1000723172,1000723174,1000723176,1000723178,1000723180,1000723182,1000723184,1000723186,1000723188,1000723190,1000723192,1000723194,1000723196,1000723198,1000723200,1000723202,1000723204,1000723206,1000723208,1000723210,1000723212,1000723214,1000723216,1000723218,1000723220,1000723222,1000723224,1000723226,1000723228,1000725021,1000725022,1000725023,1000725024,1000725029,1000725030,1000725031,1000725035,1000725036,1000725037,1000725038,1000725043,1000725044,1000725045,1000725049,1000725050,1000725051,1000725055,1000725056,1000725069,1000725070,1000725071,1000725075,1000725076,1000725079,1000725081,1000725083,1000725085,1000725087,1000725089,1000725091,1000725093,1000725095,1000725097,1000725099,1000725101,1000725103,1000725105,1000725107,1000725109,1000725111,1000725119,1000725123,1000725131,1000923143,1000923147,1000923149,1000923151,1000923153,1000923155,1000923159,1000923161,1000923163,1000923165,1000923167,1000923171,1000923173,1000923175,1000923177,1000923181,1000923183,1000923185,1000923187,1000923189,1000923191,1000923193,1000923195,1000923197,1000923199,1000923201,1000923203,1000923205,1000923207,1000923209,1000923211,1000923213,1000923215,1000923217,1000923219,1000923221,1000923223,1000923225,1000923227,1000923229,1000925027,1000925034,1000925041,1000925048,1000925052,1000925053,1000925054,1000925057,1000925058,1000925080,1000925086,1000925090,1000925092,1000925098,1000925102,1000925108,1000925114,1000925120,1000925126,1000925132,1020726677,1020727597,1020727639,1020727643,1020727645,1020727657,1020727659,1020821723,1020821725,1020827567,1020827568,1020827569,1020827570,1020827571,1020827572,1020827573,1020827574,1020827575,1020827576,1020827577,1020921685,1020926678,1020927587,1020927589,1020927616,1020927618,1020927636,1020927640,1020927644,1020927646,1020927660,1030728112,1030728114,1030728116,1030728118,1030728120,1030728122,1030928113,1030928115,1030928121,1030928123,1100927619,1100927620,1110927622,1110927626,1120927308,1120927309,1120927592,1120927593,1130021589,1130121591,1130221590,1130921585,1130921586,1130921587,1130922080,1130922081,1130922085,1130922086,1131321588,1160227578,1160227579,1160227580,1160227581,1160227582,1160227583,1160227584,1160227585,1160921649,1221221640,1221221641,1520026686,1520126687,1520226688,1520326689,1520626690,1520726685,1520726691,1520926692);
foreach($sku_array as $item){
    $query_id =mysql_query("select product_id from oc_product where model='".$item."'");
    $row_id =mysql_fetch_assoc($query_id);
    if($row_id){
        $data[] =$row_id['product_id'];
    }
}
    return $data;
}
    
get_feed_csv();

?>

