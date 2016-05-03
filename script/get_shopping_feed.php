<?php

/*
*得到销售订单脚本
*
*
*/
    ini_set('memory_limit','502M');
    set_time_limit(0);
    include_once('conf.php');
    function get_goods_info($stroe_id){
        $host ='http://www.myled.com';
        $category_str ="119,122,206,165,174";
        switch($stroe_id){
            case 0:
                $host = 'https://www.myled.com';
                $lang_id=1;
                break;
            case 52:
                $host = 'https://de.myled.com';
                $lang_id=4;
                break;
            case 53:
                $host = 'https://es.myled.com';
                $lang_id=6;
                break;
            case 54:
                $host = 'https://fr.myled.com';
                $lang_id=5;
                $category_str ="119,207,208,209,165,174";
                break;
            case 16:
                $host = 'https://fr.myled.com';
                $lang_id=5;
                $category_str ="";
                break;
            case 55:
                $host = 'https://it.myled.com';
                $lang_id=7;
                break;
            case 56:
                $host = 'https://pt.myled.com';
                $lang_id=8;
                break;
            case 44:
                $host = 'https://de.myled.com';
                $lang_id=4;
                $category_str ="";
                break;

            case 46:
                $host = 'https://www.myled.com';
                $lang_id=1;
                $category_str ="";
                break;
            case 66:
                $host = 'https://www.myled.com';
                $lang_id=1;
                $category_str ="";
                break;
            case 18:
                $host = 'https://www.myled.com';
                $lang_id=1;
                $category_str ="";
                break;

            case 71:
                $host = 'https://www.myled.com';
                $lang_id=1;
                $category_str ="";
                break;

            default:
                $host = 'https://www.myled.com';
                $lang_id=1;
                 break;
        }
        $curr_fh ='$';
        $currency_code ='USD';
        if($stroe_id==52||$stroe_id==53||$stroe_id==54||$stroe_id==55||$stroe_id==56||$stroe_id==44||$stroe_id==16){
            $curr_fh = '€';
            $currency_code ='EUR';
        }
        //AU
        if($stroe_id==22){
            $currency_code ='AUD';
        }
        $sql ="select p.product_id,p.model as sku,pd.name,p.url_path,p.stock_status_id,
        p.image,p.price as base_price,pd.description,pd.meta_description,pd.meta_keyword,
        p.weight,concat('ENMLED',p.model) AS mpn,p.is_hot
        from oc_product as p
        left join oc_product_description as pd  on p.product_id=pd.product_id
        ";
        
        if($category_str){
            $sql .="left join oc_product_to_category as p2c on p.product_id=p2c.product_id WHERE p2c.category_id in (".$category_str.") and p.stock_status_id=7 and pd.language_id=".$lang_id;
        }
        elseif($stroe_id!=66){
            $sql.="WHERE  p.stock_status_id=7 and pd.language_id=".$lang_id;
        }else{
            $sql.="WHERE  pd.language_id=".$lang_id;
        }
        $query =mysql_query($sql);
        $res =array();
        while($row=mysql_fetch_assoc($query)){
            $sql_currency ="select value from oc_currency where code='".$currency_code."'";
            $query_curr = mysql_query($sql_currency);
            $curre_row =mysql_fetch_assoc($query_curr);
            $currency_rate =$curre_row['value'];
            $row['description'] =str_replace(array("\r\n", "\r", "\n"), "", html_entity_decode($row['description']));
            $row['meta_description'] =html_entity_decode($row['meta_description']);
            $row['meta_keyword'] =html_entity_decode($row['meta_keyword']);
            //计算运费
            if($stroe_id !=44){
                $dest_country_id='US';
            }
            elseif($stroe_id ==44){
                $dest_country_id='DE';
            }
            if($row['weight']<=2000){
                $sql_2 = "select price from oc_shipping_matrixrate where dest_country_id ='".$dest_country_id."' and delivery_type='Super Saver Shipping (10-20 Working Days)' and condition_from_value<='$row[weight]' and condition_to_value>='$row[weight]' ";
                $query_2 =mysql_query($sql_2);
                $to_row =mysql_fetch_row($query_2);

                if(!empty($to_row)){
                    $row['shipping_cost'] =$curr_fh.number_format($to_row[0]*$currency_rate,2) ;
                }
                $row['shipping_method'] ='Super Saver Shipping (10-20 Working Days)';
                $row['shipping_day'] ='20' ;
            }
            elseif($row['weight']>2000){
                $sql_3 = "select price from oc_shipping_matrixrate where dest_country_id ='".$dest_country_id."' and delivery_type='Expedited Shipping (3-6 Working Days)' and condition_from_value<='$row[weight]' and condition_to_value>='$row[weight]' ";
                $query_3 =mysql_query($sql_3);
                $to_row_3 =mysql_fetch_row($query_3);
                if(!empty($to_row_3)){
                    $row['shipping_cost'] =$curr_fh.number_format($to_row_3[0]*$currency_rate,2) ;
                }
                $row['shipping_method'] ='Expedited Shipping (3-6 Working Days)';
                $row['shipping_day'] ='5-7' ;
            }
            if(!isset($row['shipping_cost'])){
                $row['shipping_cost']='';
            }
            $row['link'] =$host.'/'.$row['url_path'].".html";
            $ext =substr($row['image'], strrpos($row['image'], '.'));
            $row['image'] =str_replace(' ','%20',$row['image']);
            $row['big_image'] =$host."/image/cache/".substr($row['image'],0,strrpos($row['image'], '.'))."-455x455".$ext;
            $row['small_image'] =$host."/image/cache/".substr($row['image'],0,strrpos($row['image'], '.'))."-170x170".$ext;
            if($stroe_id==0){
                $row['base_price'] =$curr_fh.$row['base_price'];
            }
            elseif($stroe_id==99){
                $row['commission_price'] =$curr_fh.($row['base_price']*0.14);
                $row['base_price'] =$curr_fh.$row['base_price'];

            }
            else{
                $row['base_price'] =$curr_fh.($row['base_price']*$currency_rate);
            }
            //得到特价
            $time =date("Y-m-d H:i:s",time());
            $sql_special ="select price from  oc_product_special where product_id =".$row['product_id']." and date_start <='".$time."' and date_end>='".$time."' and customer_group_id=0 order by priority asc limit 1 ";
            $query_special =mysql_query($sql_special);
            $row_special =mysql_fetch_assoc($query_special );
            if($row_special['price']){
                 $row['current_price'] =$curr_fh.number_format($row_special['price']*$currency_rate,2);
                 $row['special_price'] = $row_special['price'];
            }
            else{
                $row['current_price'] = $row['base_price'];
                $row['special_price'] = 0;
            }
            
            $row['price'] =(float)str_replace($curr_fh,'',$row['base_price']);
            //得到最低的阶梯价
            $time =date("Y-m-d H:i:s",time());
            $sql_trie="select min(price) as price from oc_product_discount where product_id =".$row['product_id']." and date_start <='".$time."' and date_end>='".$time."' and customer_group_id=0";
            $query_trie =mysql_query($sql_trie);
            $row_trie =mysql_fetch_assoc($query_trie);
            if($row_trie['price']){
                $row['extra_price_field'] =$curr_fh.number_format($row_trie['price']*$currency_rate,2);
            }
            else{
                $row['extra_price_field']='';
            }
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
            $row['catagory_name'] =$catagory_arr;
            $row['catagory_id'] =$cat_arr;
            $row['catagory'] =implode(">",$catagory_arr);

            $res[] =$row;
        }
        return $res;
    }


    function get_feed_csv($stroe_id=0){
        $order_info =get_goods_info($stroe_id);
        $file ='/home/www/new_myled.com/shopping_feed/shopping_feed.csv';
        //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed.csv';
        switch($stroe_id){
            case 0:
                $file ='/home/www/new_myled.com/shopping_feed/shopping_feed.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed.csv';
                break;
            case 52:
                $file ='/home/www/new_myled.com/shopping_feed/shopping_feed_de.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed_de.csv';
                break;
            case 54:
                $file ='/home/www/new_myled.com/shopping_feed/shopping_feed_fr.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed_fr.csv';
                break;
            case 16:
                $file ='/home/www/new_myled.com/shopping_feed/tradedoubler.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed_fr.csv';
                break;
            case 99:
                $file ='/home/www/new_myled.com/shopping_feed/shareasale_feed.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shareasale_feed.csv';
                break;
            case 22:
                $file ='/home/www/new_myled.com/shopping_feed/shopping_feed_au.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/shopping_feed_au.csv';
                break;
            case 33:
                $file ='/home/www/new_myled.com/shopping_feed/CSV_datafeed_webgain.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/CSV_datafeed_webgain.csv';
                break;

            case 71:
                $file ='/home/www/new_myled.com/shopping_feed/CSV_datafeed_sas.csv';
                //$file ='E:/www/code/branches/charles0817/shopping_feed/CSV_datafeed_sas.csv';
                break;

            case 44:
                $file ='/home/www/new_myled.com/shopping_feed/adcell_feed.csv';
                //$file ='E:/www/code/branches/charles0817/shopping_feed/adcell_feed.csv';
                break;
            case 46:
                $file ='/home/www/new_myled.com/shopping_feed/cj_feed.csv';
                //$file ='D:\XAMPP\htdocs\new_myled/shopping_feed/cj_feed.csv';
                break;
            case 66:
                $file ='/home/www/new_myled.com/shopping_feed/em_feed.csv';
                //$file ='D:\tinker20150107/shopping_feed/em_feed.csv';
                break;
            case 18:
                $file ='/home/www/new_myled.com/shopping_feed/data_feed.csv';
                //$file ='D:\tinker0609/shopping_feed/data_feed.csv';
                break;
        }

        //$file ='d://feed.csv';
        if(file_exists($file)){
                unlink($file);
        }
        $fp = fopen($file, 'w+');
        chmod($file, 0777);
        switch($stroe_id){
            case 0:
                fputcsv($fp,array('Unique Merchant SKU','Product Name ','Product URL','Image URL','Original Price','Current Price','Shipping Rate','Stock Availability','Condition','MPN','Coupon Code','Coupon Code Description'));
                break;
            case 52:
                fputcsv($fp,array('Hersteller','Produktname','Eindeutige Händler-Artikelnummer','Produkt-URL','Preis','MPN','ProduktBild-UR','Versandgebühr','Verfügbarkeit','Coupon-Code','Beschreibung Coupon-Code','Produkttyp'));
                break;
            case 54:
                fputcsv($fp,array('MPN ','Marque','Référence Interne','Nom du produit  ','URL du produit ','URL image','Prix Origine','Prix actuel','frais de port','Disponibilité','Nom de la catégorie'));
                break;
            case 16:
                fputcsv($fp,array('MPN ','Marque','Référence Interne','Nom du produit  ','URL du produit ','URL image','Prix Origine','Prix actuel','frais de port','Disponibilité','Nom de la catégorie','description'));
                break;
            case 99:
                fputcsv($fp,array('SKU','name','URL','Price','RetailPrice','FullImage','ThumbnailImage','Commission','Category','SubCategory','Description','SearchTerms','Status','MerchantID','ShortDescription'));
                break;
            case 22:
                fputcsv($fp,array('Unique Merchant SKU','Product Name ','Product URL','Image URL','Current Price','Stock Availability','Brand / Manufacturer','MPN/ISBN','Condition','Shipping ','Original Price','Product Description ','Category'));
                break;
            case 33:
                fputcsv($fp,array('product_name','deeplink','merchant_category','price','product_id','description','image_URL','delivery_time','delivery_cost','extra_price_field','thumbnail_image_URL','availability'));
                break;

            case 71:
                //fputcsv($fp,array('product_name','deeplink','merchant_category','price','product_id','description','image_URL','delivery_time','delivery_cost','extra_price_field','thumbnail_image_URL','availability'));
                fputcsv($fp,array('SKU','Name','URL to product','Price','Retail Price','URL to image','URL to thumbnail image','Commission','Category','SubCategory','Description','SearchTerms','Status','Your MerchantID','Custom 1','Custom 2','Custom 3','Custom 4','Custom 5','Manufacturer','PartNumber','MerchantCategory','MerchantSubcategory','ShortDescription','ISBN','UPC','CrossSell','MerchantGroup','MerchantSubgroup','CompatibleWith','CompareTo','QuantityDiscount','Bestseller','AddToCartURL','ReviewsRSSURL','Option1','Option2','Option3','Option4','Option5','customCommissions','customCommissionIsFlatRate','customCommissionNewCustomerMultiplier','mobileURL','mobileImage','mobileThumbnail','ReservedForFutureUse','ReservedForFutureUse','ReservedForFutureUse','ReservedForFutureUse'));
                break;

            case 44:
                //fputcsv($fp,array('Deep link','Product title','Product description','Price (net)','Currency','Manufacturer','Product picture URL','Product category','Delivery charges general','Shipping method ','Delivery period/ availability'));
                fputcsv($fp,array('product_name','deeplink','merchant_category','price','product_id','description','image_URL','delivery_time','delivery_cost','extra_price_field','thumbnail_image_URL','availability'));
                break;
            case 46:
                fputcsv($fp,array('&CID=4430338'));
                fputcsv($fp,array('&SUBID=164323'));
                fputcsv($fp,array('&PROCESSTYPE=OVERWRITE'));
                fputcsv($fp,array('&AID=12031344'));
                fputcsv($fp,array('&PARAMETERS=NAME|KEYWORDS|DESCRIPTION|SKU|BUYURL|AVAILABLE|IMAGEURL|PRICE|CURRENCY|ADVERTISERCATEGORY|CONDITION'));		
                fputcsv($fp,array('NAME','KEYWORDS','DESCRIPTION','SKU','BUYURL','AVAILABLE','IMAGEURL','PRICE','CURRENCY','ADVERTISER CATEGORY','CONDITION'));
                break;
            case 66:
                fputcsv($fp,array('item','link','title','image','category','available','price','c_special_price','c_item_id','c_title_de','c_title_fr','c_title_es','c_title_it','c_title_pt','c_path','c_price_aud','c_price_brl','c_price_cad','c_price_chf','c_price_clp','c_price_cny','c_price_dkk','c_price_eur','c_price_gbp','c_price_hkd','c_price_ils','c_price_inr','c_price_jpy','c_price_mxn','c_price_nok','c_price_rub','c_price_sek','c_special_price_aud','c_special_price_brl','c_special_price_cad','c_special_price_chf','c_special_price_clp','c_special_price_cny','c_special_price_dkk','c_special_price_eur','c_special_price_gbp','c_special_price_hkd','c_special_price_ils','c_special_price_inr','c_special_price_jpy','c_special_price_mxn','c_special_price_nok','c_special_price_rub','c_special_price_sek'));
                break;
             case 18:
                fputcsv($fp,array('SKU','Name','URL to product','Price','Retail Price','URL to image','URL to thumbnail image','Commission','Category','SubCategory','Description','SearchTerms','Status','Your MerchantID','Custom 1','Custom 2','Custom 3','Custom 4','Custom 5','Manufacturer','PartNumber','MerchantCategory','MerchantSubcategory','ShortDescription','ISBN','UPC','CrossSell','MerchantGroup','MerchantSubgroup','CompatibleWith','CompareTo','QuantityDiscount','Bestseller','AddToCartURL','ReviewsRSSURL','Option1','Option2','Option3','Option4','Option5','customCommissions','customCommissionIsFlatRate','customCommissionNewCustomerMultiplier','mobileURL','mobileImage','mobileThumbnail','ReservedForFutureUse','ReservedForFutureUse','ReservedForFutureUse','ReservedForFutureUse'));
                break;
        }
        if($store_id =66){
            //得到各币种汇率
            $aud_huilv =get_huilv('AUD');
            $brl_huilv =get_huilv('BRL');
            $cad_huilv =get_huilv('CAD');
            $chf_huilv =get_huilv('CHF');
            $clp_huilv =get_huilv('CLP');
            $cny_huilv =get_huilv('CNY');
            $dkk_huilv =get_huilv('DKK');
            $eur_huilv =get_huilv('EUR');
            $gbp_huilv =get_huilv('GBP');
            $hkd_huilv =get_huilv('HKD');
            $ils_huilv =get_huilv('ILS');
            $inr_huilv =get_huilv('INR');
            $jpy_huilv =get_huilv('JPY');
            $mxn_huilv =get_huilv('MXN');
            $nok_huilv =get_huilv('NOK');
            $rub_huilv =get_huilv('RUB');
            $sek_huilv =get_huilv('SEK');
        }
        foreach($order_info as $order){
            $new_arr =array();
            if($order){
                if($stroe_id==0){
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['current_price'];
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]='yes';
                    $new_arr[]='New';
                    $new_arr[]=$order['mpn'];
                    $new_arr[]='bulbshopping';
                    $new_arr[]='Get 10% bulb category discount,Valid till July';
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==52){
                    $new_arr[]="Myled";
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['mpn'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]='in stock';
                    $new_arr[]='cell2530';
                    $new_arr[]='€25 Rabatt auf alle Bestellungen mit einem Warenkorb über €200';
                    $new_arr[]=$order['catagory'];
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==54){
                    $new_arr[]=$order['mpn'];
                    $new_arr[]='MyLED';
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['current_price'];
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]='in stock';
                    $new_arr[]=$order['catagory'];
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==16){
                    $new_arr[]=$order['mpn'];
                    $new_arr[]='MyLED';
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['current_price'];
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]='in stock';
                    $new_arr[]=$order['catagory'];
                    $new_arr[]=substr(mb_convert_encoding($order['description'],'UTF-8'),0,2989);
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==99){
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];;
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['current_price'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]=$order['commission_price'];
                    $new_arr[]=11;
                    $new_arr[]=98;
                    $new_arr[]=$order['description'];
                    $new_arr[]=$order['meta_keyword'];
                    $new_arr[]='In Stock';
                    $new_arr[]='50601';
                    $new_arr[]=$order['meta_description'];
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==22){
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['current_price'];
                    $new_arr[]='In Stock';
                    $new_arr[]='MyLED';
                    $new_arr[]=$order['mpn'];
                    $new_arr[]='New';
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['description'];
                    $new_arr[]=$order['catagory'];
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==33){
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['catagory'];
                    //$new_arr[]='Electronic Games > Consoles';
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['description'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]='3-6 Working Days';
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]=$order['extra_price_field'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]='In Stock';
                    fputcsv($fp,$new_arr);
                }

                if($stroe_id==71){

                    $rank = mt_rand(0,count($order_info));
                    $CrossSell =$order_info[$rank]['sku'];

                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]='';
                    $new_arr[]= isset($order['catagory_id'][1])?$order['catagory_id'][1]:"";
                    $new_arr[]= isset($order['catagory_id'][2])?$order['catagory_id'][2]:"";
                    $new_arr[]=$order['description'];
                    $new_arr[]="";
                    $new_arr[]='In Stock';
                    $new_arr[]='50601';
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]= isset($order['catagory_name'][0])?$order['catagory_name'][0]:"";
                    $new_arr[]= isset($order['catagory_name'][1])?$order['catagory_name'][1]:"";
                    $new_arr[]=$order['name'];
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]= $CrossSell;
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";
                    $new_arr[]="";

                    fputcsv($fp,$new_arr);
                }

                if($stroe_id==44){
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['catagory'];
                    $new_arr[]=$order['base_price'];
                    $new_arr[]=$order['product_id'];
                    $new_arr[]=mb_convert_encoding($order['description'],'UTF-8');
                    $new_arr[]=$order['big_image'];
                    $new_arr[]="3-6 Working Days";
                    $new_arr[]=$order['shipping_cost'];
                    $new_arr[]="";
                    $new_arr[]=$order['small_image'];
                    $new_arr[]="In Stock";
                    //$new_arr[]="€";
                    //$new_arr[]='MyLED';
                    //$new_arr[]=$order['shipping_method'];

                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==46){
                    $new_arr[]=$order['name'];
                    $new_arr[]=strip_tags(mb_convert_encoding($order['meta_keyword'],'UTF-8'));
                    $new_arr[]=strip_tags(mb_convert_encoding($order['description'],'UTF-8'));
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['link'];
                    $new_arr[]='Yes';
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=str_replace('$','',$order['current_price']);
                    $new_arr[]="USD";
                    $new_arr[]=$order['catagory'];
                    $new_arr[]='New';
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==66){
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['catagory'];
                    if($order['stock_status_id']==7){
                        $new_arr[]='TRUE';
                    }else{
                        $new_arr[]='FALSE';
                    }
                    $new_arr[]=$order['price'];
                    if($order['special_price']){
                        $new_arr[]=number_format($order['special_price'],2);
                    }else{
                        $new_arr[]=0;
                    }
                    $new_arr[]=$order['product_id'];
                    $new_arr[]=get_product_name($order['product_id'],4);
                    $new_arr[]=get_product_name($order['product_id'],5);
                    $new_arr[]=get_product_name($order['product_id'],6);
                    $new_arr[]=get_product_name($order['product_id'],7);
                    $new_arr[]=get_product_name($order['product_id'],8);
                    $new_arr[]=$order['url_path'].".html";
                    $new_arr[]=number_format(($order['price']*$aud_huilv),2);
                    $new_arr[]=number_format(($order['price']*$brl_huilv),2);
                    $new_arr[]=number_format(($order['price']*$cad_huilv),2);
                    $new_arr[]=number_format(($order['price']*$chf_huilv),2);
                    $new_arr[]=number_format(($order['price']*$clp_huilv),2);
                    $new_arr[]=number_format(($order['price']*$cny_huilv),2);
                    $new_arr[]=number_format(($order['price']*$dkk_huilv),2);
                    $new_arr[]=number_format(($order['price']*$eur_huilv),2,',','.');
                    $new_arr[]=number_format(($order['price']*$gbp_huilv),2);
                    $new_arr[]=number_format(($order['price']*$hkd_huilv),2);
                    $new_arr[]=number_format(($order['price']*$ils_huilv),2);
                    $new_arr[]=number_format(($order['price']*$inr_huilv),2);
                    $new_arr[]=number_format(($order['price']*$jpy_huilv),2);
                    $new_arr[]=number_format(($order['price']*$mxn_huilv),2);
                    $new_arr[]=number_format(($order['price']*$nok_huilv),2);
                    $new_arr[]=number_format(($order['price']*$rub_huilv),2);
                    $new_arr[]=number_format(($order['price']*$sek_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$aud_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$brl_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$cad_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$chf_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$clp_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$cny_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$dkk_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$eur_huilv),2,',','.');
                    $new_arr[]=number_format(($order['special_price']*$gbp_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$hkd_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$ils_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$inr_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$jpy_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$mxn_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$nok_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$rub_huilv),2);
                    $new_arr[]=number_format(($order['special_price']*$sek_huilv),2);
                    fputcsv($fp,$new_arr);
                }
                if($stroe_id==18){
                    $new_arr[]=$order['sku'];
                    $new_arr[]=$order['name'];
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['price'];
                     if($order['special_price']){
                        $new_arr[]=number_format($order['special_price'],2);
                    }else{
                        $new_arr[]=$order['price'];
                    }
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]=0;
                    $new_arr[]=$order['catagory_id'][1];
                    $new_arr[]=$order['catagory_id'][2];
                    $new_arr[]=strip_tags(mb_convert_encoding($order['description'],'UTF-8'));
                    $new_arr[]=$order['meta_keyword'];
                    $new_arr[]='instock';
                    $new_arr[]='50601';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]=$order['catagory_name'][0];
                    $new_arr[]=$order['catagory_name'][1];
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]=0;
                    $new_arr[]=get_cross_sku($order['catagory_id'],$order['product_id']);
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]=$order['is_hot'];
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]=$order['link'];
                    $new_arr[]=$order['big_image'];
                    $new_arr[]=$order['small_image'];
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    $new_arr[]='';
                    fputcsv($fp,$new_arr);
                }
            }
        }
        fclose($fp);
    }

    
   function get_huilv($currency_code){
        $sql ="select value from oc_currency where code='".$currency_code."'";
        $query= mysql_query($sql);
        $row =mysql_fetch_assoc($query);
        $currency_rate =$row['value'];
        return $currency_rate;
   }

   function get_product_name($product_id,$lang_id){
        $sql ="select name from oc_product_description where product_id='".$product_id."' and language_id=".$lang_id;
        $query = mysql_query($sql);
        $row =mysql_fetch_assoc($query);
        return $row['name'];
   }

   function get_cross_sku($category,$product_id){
        if($category[2]){
            $cat_id =$category[2];
        }else{
            $cat_id =$category[1];
        }
        $sql ="select p.model from 
        oc_product_to_category as c 
        left join oc_product_to_store as s on c.product_id=s.product_id and c.category_id='".$cat_id."' 
        left join oc_product as p on c.product_id=p.product_id
        where c.product_id !=".$cat_id." and  s.store_id=0 order by sales_num DESC limit 1";
        $query = mysql_query($sql);
        $row =mysql_fetch_assoc($query);
        return $row['model'];
   }


    
    //shoping-feed_en
    get_feed_csv($stroe_id=0);
    //shoping-feed_de
    get_feed_csv($stroe_id=52);
    //shoping-feed_fr
    get_feed_csv($stroe_id=54);
    //sales_feed
    get_feed_csv($stroe_id=99);
    //shoping-feed_au
    get_feed_csv($stroe_id=22);
   // webgain feed
    get_feed_csv($stroe_id=33);


    //adcell
    get_feed_csv($stroe_id=44);



    //cj feed
    get_feed_csv($stroe_id=46);
    
    //all_goods_fr_feed
    get_feed_csv($stroe_id=16);
    //em 推荐系统feed
    get_feed_csv($stroe_id=66);
    // 商品feed luyi
    get_feed_csv($stroe_id=18);

    // SAS feed en
    get_feed_csv($stroe_id=71);
    
?>

