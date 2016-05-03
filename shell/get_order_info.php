<?php
include_once('/home/www/new_myled.com/script/conf.php');

mysql_select_db("new_myled",$db_connect_magento);
mysql_query("SET NAMES UTF8");
function get_order_info(){
    $sql ="select o.date_added,o.order_number,op.model as sku,op.quantity,p.product_code,p.supplier_code,'' as sale_pro_attr,op.name,
    CONCAT('https://www.myled.com/',p.url_path,'.html') as pro_url,p.image as images,
    op.price as base_price ,o.base_shipping_amount,o.base_discount_amount,o.base_grand_total,o.currency_code,op.currency_price as price ,o.shipping_amount,o.discount_amount ,o.grand_total,
    o.shipping_method,o.shipping_country_code,CONCAT(o.shipping_firstname,' ',o.shipping_lastname) as shipp_name,o.shipping_company as company,o.shipping_address_1 as address_1,o.shipping_address_2 as address_2,o.shipping_city as city,o.shipping_zone as region,o.shipping_postcode as postcode,o.shipping_phone as telephone,o.order_tax_id as vat_id
    from oc_order as o
    left join oc_order_product as op  on o.order_id =op.order_id
    left join oc_product as p on op.product_id=p.product_id
    left join oc_order_status as os on os.order_status_id=o.order_status_id
    where o.send_forecast_status =1 and os.name='Processing' and os.language_id=1 and  o.is_parent = 0";
    $query =mysql_query($sql);
    $res =array();
    while($row =mysql_fetch_assoc($query)){
        $new_row =array();
         $ext =substr($row['images'], strrpos($row['images'], '.'));
         $row['images'] ="https://www.myled.com/image/cache/".substr($row['images'],0,strrpos($row['images'], '.'))."-455x455".$ext;
        foreach($row as $key=>$value){
            if($key=='sale_pro_attr'){
                $value =implode('<br>',get_sale_pro_attr($row['sku']));
            }
            $value =html_entity_decode($value,ENT_QUOTES ,'UTF-8');
            $new_row[$key] =$value;
        }
        $res[] =$new_row;
    }
    return array_unique_fb($res);

}

    function get_detail_sales_csv($order_info){
        $time =date('Y-m-d-H-i',time());
        $export_time =date('Y-m-d',time());
        $file ='/data/logs/test_order_export/detail_sales_order'.$time.'.csv';
        //$file ='d://test_detail_sale_order.csv';
        $fp = fopen($file, 'w+');
        chmod($file, 0777);
        fputcsv($fp,array('订单创建时间','订单号','sku','数量','供应商商品代码','供应商代码','商品规格属性','商品名称','商品链接','商品图片','商品单价','运费','折扣','订单总金额','成交币种','商品单价/成交币种','运费/成交币种','折扣/成交币种','订单总金额/成交币种','运送方式','国家','收件人姓名','收件人公司名','收件人地址1'    ,'收件人地址2','收件人城市','收件人州','收件人邮编','收件人电话','收件人VAT','订单导出日期'));
        $diff_replace =array(
            'Æ'=>'AE',
            'æ'=>'ae',
            'Á'=>'A',
            'á'=>'a',
            'À'=>'A',
            'à'=>'a',
            'Ă'=>'A',
            'ă'=>'a',
            'Ắ'=>'A',
            'ắ'=>'a',
            'Ằ'=>'A',
            'ằ'=>'a',
            'Ẵ'=>'A',
            'ẵ'=>'a',
            'Ẳ'=>'A',
            'ẳ'=>'a',
            'Â'=>'A',
            'â'=>'a',
            'Ấ'=>'A',
            'ấ'=>'a',
            'Ầ'=>'A',
            'ầ'=>'a',
            'Ẫ'=>'A',
            'ẫ'=>'a',
            'Ẩ'=>'A',
            'ẩ'=>'a',
            'Ǎ'=>'A',
            'ǎ'=>'a',
            'Å'=>'A',
            'å'=>'a',
            'Ǻ'=>'A',
            'ǻ'=>'a',
            'Ä'=>'A',
            'ä'=>'a',
            'Ǟ'=>'A',
            'ǟ'=>'a',
            'Ã'=>'A',
            'ã'=>'a',
            'Ȧ'=>'A',
            'ȧ'=>'a',
            'Ǡ'=>'A',
            'ǡ'=>'a',
            'Ą'=>'A',
            'ą'=>'a',
            'Ā'=>'A',
            'ā'=>'a',
            'Ả'=>'A',
            'ả'=>'a',
            'Ȁ'=>'A',
            'ȁ'=>'a',
            'Ȃ'=>'A',
            'ȃ'=>'a',
            'Ạ'=>'A',
            'ạ'=>'a',
            'Ặ'=>'A',
            'ặ'=>'a',
            'Ậ'=>'A',
            'ậ'=>'a',
            'Ḁ'=>'A',
            'ḁ'=>'a',
            'Ⱥ'=>'A',
            'ⱥ'=>'a',
            'ᶏ'=>'a',
            'Ḃ'=>'B',
            'ḃ'=>'b',
            'Ḅ'=>'B',
            'ḅ'=>'b',
            'Ḇ'=>'B',
            'ḇ'=>'b',
            'Ƀ'=>'B',
            'ƀ'=>'b',
            'ᵬ'=>'b',
            'ᶀ'=>'b',
            'Ɓ'=>'B',
            'ɓ'=>'b',
            'Ƃ'=>'B',
            'ƃ'=>'b',
            'ß'=>'ss',
            'Ć'=>'C',
            'ć'=>'c',
            'Ĉ'=>'C',
            'ĉ'=>'c',
            'Č'=>'C',
            'č'=>'c',
            'Ċ'=>'C',
            'ċ'=>'c',
            'Ç'=>'C',
            'ç'=>'c',
            'Ḉ'=>'C',
            'ḉ'=>'c',
            'Ȼ'=>'C',
            'ȼ'=>'c',
            'Ƈ'=>'C',
            'ƈ'=>'c',
            'ɕ'=>'c',
            'Ď'=>'D',
            'ď'=>'d',
            'Ḋ'=>'D',
            'ḋ'=>'d',
            'Ḑ'=>'D',
            'ḑ'=>'d',
            'Ḍ'=>'D',
            'ḍ'=>'d',
            'Ḓ'=>'D',
            'ḓ'=>'d',
            'Ḏ'=>'D',
            'ḏ'=>'d',
            'Đ'=>'D',
            'đ'=>'d',
            'ᵭ'=>'d',
            'ᶁ'=>'d',
            'Ɖ'=>'D',
            'ɖ'=>'d',
            'Ɗ'=>'D',
            'ɗ'=>'d',
            'ᶑ'=>'d',
            'Ƌ'=>'D',
            'ƌ'=>'d',
            'ȡ'=>'d',
            'É'=>'E',
            'é'=>'e',
            'È'=>'E',
            'è'=>'e',
            'Ĕ'=>'E',
            'ĕ'=>'e',
            'Ê'=>'E',
            'ê'=>'e',
            'Ế'=>'E',
            'ế'=>'e',
            'Ề'=>'E',
            'ề'=>'e',
            'Ễ'=>'E',
            'ễ'=>'e',
            'Ể'=>'E',
            'ể'=>'e',
            'Ě'=>'E',
            'ě'=>'e',
            'Ë'=>'E',
            'ë'=>'e',
            'Ẽ'=>'E',
            'ẽ'=>'e',
            'Ė'=>'E',
            'ė'=>'e',
            'Ȩ'=>'E',
            'ȩ'=>'e',
            'Ḝ'=>'E',
            'ḝ'=>'e',
            'Ę'=>'E',
            'ę'=>'e',
            'Ē'=>'E',
            'ē'=>'e',
            'Ḗ'=>'E',
            'ḗ'=>'e',
            'Ḕ'=>'E',
            'ḕ'=>'e',
            'Ẻ'=>'E',
            'ẻ'=>'e',
            'Ȅ'=>'E',
            'ȅ'=>'e',
            'Ȇ'=>'E',
            'ȇ'=>'e',
            'Ẹ'=>'E',
            'ẹ'=>'e',
            'Ệ'=>'E',
            'ệ'=>'e',
            'Ḙ'=>'E',
            'ḙ'=>'e',
            'Ḛ'=>'E',
            'ḛ'=>'e',
            'Ɇ'=>'E',
            'ɇ'=>'e',
            'ᶒ'=>'e',
            'Ḟ'=>'F',
            'ḟ'=>'f',
            'ᵮ'=>'f',
            'ᶂ'=>'f',
            'Ƒ'=>'F',
            'ƒ'=>'f',
            'Ǵ'=>'G',
            'ǵ'=>'g',
            'Ğ'=>'G',
            'ğ'=>'g',
            'Ĝ'=>'G',
            'ĝ'=>'g',
            'Ǧ'=>'G',
            'ǧ'=>'g',
            'Ġ'=>'G',
            'ġ'=>'g',
            'Ģ'=>'G',
            'ģ'=>'g',
            'Ḡ'=>'G',
            'ḡ'=>'g',
            'Ǥ'=>'G',
            'ǥ'=>'g',
            'ᶃ'=>'g',
            'Ɠ'=>'G',
            'ɠ'=>'g',
            'Ĥ'=>'H',
            'ĥ'=>'h',
            'Ȟ'=>'H',
            'ȟ'=>'h',
            'Ḧ'=>'H',
            'ḧ'=>'h',
            'Ḣ'=>'H',
            'ḣ'=>'h',
            'Ḩ'=>'H',
            'ḩ'=>'h',
            'Ḥ'=>'H',
            'ḥ'=>'h',
            'Ḫ'=>'H',
            'ḫ'=>'h',
            'Ħ'=>'H',
            'ħ'=>'h',
            'Ⱨ'=>'H',
            'ⱨ'=>'h',
            'Í'=>'I',
            'í'=>'i',
            'Ì'=>'I',
            'ì'=>'i',
            'Ĭ'=>'I',
            'ĭ'=>'i',
            'Î'=>'I',
            'î'=>'i',
            'Ǐ'=>'I',
            'ǐ'=>'i',
            'Ï'=>'I',
            'ï'=>'i',
            'Ḯ'=>'I',
            'ḯ'=>'i',
            'Ĩ'=>'I',
            'ĩ'=>'i',
            'İ'=>'I',
            'i'=>'i',
            'Į'=>'I',
            'į'=>'i',
            'Ī'=>'I',
            'ī'=>'i',
            'Ỉ'=>'I',
            'ỉ'=>'i',
            'Ȉ'=>'I',
            'ȉ'=>'i',
            'Ȋ'=>'I',
            'ȋ'=>'i',
            'Ị'=>'I',
            'ị'=>'i',
            'Ḭ'=>'I',
            'ḭ'=>'i',
            'I'=>'I',
            'ı'=>'i',
            'Ɨ'=>'I',
            'ɨ'=>'i',
            'ᵻ'=>'i',
            'ᶖ'=>'i',
            'Ĵ'=>'J',
            'ĵ'=>'j',
            'ǰ'=>'j',
            'ȷ'=>'j',
            'Ɉ'=>'J',
            'ɉ'=>'j',
            'ʝ'=>'j',
            'ɟ'=>'j',
            'ʄ'=>'j',
            'Ḱ'=>'K',
            'ḱ'=>'k',
            'Ǩ'=>'K',
            'ǩ'=>'k',
            'Ķ'=>'K',
            'ķ'=>'k',
            'Ḳ'=>'K',
            'ḳ'=>'k',
            'Ḵ'=>'K',
            'ḵ'=>'k',
            'ᶄ'=>'k',
            'Ƙ'=>'K',
            'ƙ'=>'k',
            'Ⱪ'=>'K',
            'ⱪ'=>'k',
            'Ĺ'=>'L',
            'ĺ'=>'l',
            'Ľ'=>'L',
            'ľ'=>'l',
            'Ļ'=>'L',
            'ļ'=>'l',
            'Ḷ'=>'L',
            'ḷ'=>'l',
            'Ḹ'=>'L',
            'ḹ'=>'l',
            'Ḽ'=>'L',
            'ḽ'=>'l',
            'Ḻ'=>'L',
            'ḻ'=>'l',
            'Ł'=>'L',
            'ł'=>'l',
            'Ŀ'=>'L',
            'ŀ'=>'l',
            'Ƚ'=>'L',
            'ƚ'=>'l',
            'Ⱡ'=>'L',
            'ⱡ'=>'l',
            'Ɫ'=>'L',
            'ɫ'=>'l',
            'ɬ'=>'l',
            'ᶅ'=>'l',
            'ɭ'=>'l',
            'ȴ'=>'l',
            'Ḿ'=>'M',
            'ḿ'=>'m',
            'Ṁ'=>'M',
            'ṁ'=>'m',
            'Ṃ'=>'M',
            'ṃ'=>'m',
            'ᵯ'=>'m',
            'ᶆ'=>'m',
            'ɱ'=>'m',
            'Ń'=>'N',
            'ń'=>'n',
            'Ǹ'=>'N',
            'ǹ'=>'n',
            'Ň'=>'N',
            'ň'=>'n',
            'Ñ'=>'N',
            'ñ'=>'n',
            'Ṅ'=>'N',
            'ṅ'=>'n',
            'Ņ'=>'N',
            'ņ'=>'n',
            'Ṇ'=>'N',
            'ṇ'=>'n',
            'Ṋ'=>'N',
            'ṋ'=>'n',
            'Ṉ'=>'N',
            'ṉ'=>'n',
            'ᵰ'=>'n',
            'Ɲ'=>'N',
            'ɲ'=>'n',
            'Ƞ'=>'N',
            'ƞ'=>'n',
            'ᶇ'=>'n',
            'ɳ'=>'n',
            'ȵ'=>'n',
            'Ó'=>'O',
            'ó'=>'o',
            'Ò'=>'O',
            'ò'=>'o',
            'Ŏ'=>'O',
            'ŏ'=>'o',
            'Ô'=>'O',
            'ô'=>'o',
            'Ố'=>'O',
            'ố'=>'o',
            'Ồ'=>'O',
            'ồ'=>'o',
            'Ỗ'=>'O',
            'ỗ'=>'o',
            'Ổ'=>'O',
            'ổ'=>'o',
            'Ǒ'=>'O',
            'ǒ'=>'o',
            'Ö'=>'O',
            'ö'=>'o',
            'Ȫ'=>'O',
            'ȫ'=>'o',
            'Ő'=>'O',
            'ő'=>'o',
            'Õ'=>'O',
            'õ'=>'o',
            'Ṍ'=>'O',
            'ṍ'=>'o',
            'Ṏ'=>'O',
            'ṏ'=>'o',
            'Ȭ'=>'O',
            'ȭ'=>'o',
            'Ȯ'=>'O',
            'ȯ'=>'o',
            'Ȱ'=>'O',
            'ȱ'=>'o',
            'Ø'=>'O',
            'ø'=>'o',
            'Ǿ'=>'O',
            'ǿ'=>'o',
            'Ǫ'=>'O',
            'ǫ'=>'o',
            'Ǭ'=>'O',
            'ǭ'=>'o',
            'Ō'=>'O',
            'ō'=>'o',
            'Ṓ'=>'O',
            'ṓ'=>'o',
            'Ṑ'=>'O',
            'ṑ'=>'o',
            'Ỏ'=>'O',
            'ỏ'=>'o',
            'Ȍ'=>'O',
            'ȍ'=>'o',
            'Ȏ'=>'O',
            'ȏ'=>'o',
            'Ơ'=>'O',
            'ơ'=>'o',
            'Ớ'=>'O',
            'ớ'=>'o',
            'Ờ'=>'O',
            'ờ'=>'o',
            'Ỡ'=>'O',
            'ỡ'=>'o',
            'Ở'=>'O',
            'ở'=>'o',
            'Ợ'=>'O',
            'ợ'=>'o',
            'Ọ'=>'O',
            'ọ'=>'o',
            'Ộ'=>'O',
            'ộ'=>'o',
            'Ɵ'=>'O',
            'ɵ'=>'o',
            'Ṕ'=>'P',
            'ṕ'=>'p',
            'Ṗ'=>'P',
            'ṗ'=>'p',
            'Ᵽ'=>'P',
            'ᵽ'=>'p',
            'ᵱ'=>'p',
            'ᶈ'=>'p',
            'Ƥ'=>'P',
            'ƥ'=>'p',
            'ʠ'=>'q',
            'Ɋ'=>'Q',
            'ɋ'=>'q',
            'Ŕ'=>'R',
            'ŕ'=>'r',
            'Ř'=>'R',
            'ř'=>'r',
            'Ṙ'=>'R',
            'ṙ'=>'r',
            'Ŗ'=>'R',
            'ŗ'=>'r',
            'Ȑ'=>'R',
            'ȑ'=>'r',
            'Ȓ'=>'R',
            'ȓ'=>'r',
            'Ṛ'=>'R',
            'ṛ'=>'r',
            'Ṝ'=>'R',
            'ṝ'=>'r',
            'Ṟ'=>'R',
            'ṟ'=>'r',
            'Ɍ'=>'R',
            'ɍ'=>'r',
            'ᵲ'=>'r',
            'ᶉ'=>'r',
            'ɼ'=>'r',
            'Ɽ'=>'R',
            'ɽ'=>'r',
            'ɾ'=>'r',
            'ᵳ'=>'r',
            'Ś'=>'S',
            'ś'=>'s',
            'Ṥ'=>'S',
            'ṥ'=>'s',
            'Ŝ'=>'S',
            'ŝ'=>'s',
            'Š'=>'S',
            'š'=>'s',
            'Ṧ'=>'S',
            'ṧ'=>'s',
            'Ş'=>'S',
            'ş'=>'s',
            'Ṣ'=>'S',
            'ṣ'=>'s',
            'Ṩ'=>'S',
            'ṩ'=>'s',
            'Ș'=>'S',
            'ș'=>'s',
            'ᵴ'=>'s',
            'ᶊ'=>'s',
            'ʂ'=>'s',
            'ȿ'=>'s',
            'Ť'=>'T',
            'ť'=>'t',
            'Ṫ'=>'T',
            'ṫ'=>'t',
            'Ţ'=>'T',
            'ţ'=>'t',
            'Ṭ'=>'T',
            'ṭ'=>'t',
            'Ț'=>'T',
            'ț'=>'t',
            'Ṱ'=>'T',
            'ṱ'=>'t',
            'Ṯ'=>'T',
            'ṯ'=>'t',
            'Ŧ'=>'T',
            'ŧ'=>'t',
            'Ⱦ'=>'T',
            'ⱦ'=>'t',
            'ᵵ'=>'t',
            'ƫ'=>'t',
            'Ƭ'=>'T',
            'ƭ'=>'t',
            'Ʈ'=>'T',
            'ʈ'=>'t',
            'ȶ'=>'t',
            'Ú'=>'U',
            'ú'=>'u',
            'Ù'=>'U',
            'ù'=>'u',
            'Ŭ'=>'U',
            'ŭ'=>'u',
            'Û'=>'U',
            'û'=>'u',
            'Ǔ'=>'U',
            'ǔ'=>'u',
            'Ů'=>'U',
            'ů'=>'u',
            'Ü'=>'U',
            'ü'=>'u',
            'Ǘ'=>'U',
            'ǘ'=>'u',
            'Ǜ'=>'U',
            'ǜ'=>'u',
            'Ǚ'=>'U',
            'ǚ'=>'u',
            'Ǖ'=>'U',
            'ǖ'=>'u',
            'Ű'=>'U',
            'ű'=>'u',
            'Ũ'=>'U',
            'ũ'=>'u',
            'Ṹ'=>'U',
            'ṹ'=>'u',
            'Ų'=>'U',
            'ų'=>'u',
            'Ū'=>'U',
            'ū'=>'u',
            'Ṻ'=>'U',
            'ṻ'=>'u',
            'Ủ'=>'U',
            'ủ'=>'u',
            'Ȕ'=>'U',
            'ȕ'=>'u',
            'Ȗ'=>'U',
            'ȗ'=>'u',
            'Ư'=>'U',
            'ư'=>'u',
            'Ứ'=>'U',
            'ứ'=>'u',
            'Ừ'=>'U',
            'ừ'=>'u',
            'Ữ'=>'U',
            'ữ'=>'u',
            'Ử'=>'U',
            'ử'=>'u',
            'Ự'=>'U',
            'ự'=>'u',
            'Ụ'=>'U',
            'ụ'=>'u',
            'Ṳ'=>'U',
            'ṳ'=>'u',
            'Ṷ'=>'U',
            'ṷ'=>'u',
            'Ṵ'=>'U',
            'ṵ'=>'u',
            'Ʉ'=>'U',
            'ʉ'=>'u',
            'ᵾ'=>'u',
            'ᶙ'=>'u',
            'Ṽ'=>'V',
            'ṽ'=>'v',
            'Ṿ'=>'V',
            'ṿ'=>'v',
            'ᶌ'=>'v',
            'Ʋ'=>'V',
            'ʋ'=>'v',
            'ⱴ'=>'v',
            'Ẃ'=>'W',
            'ẃ'=>'w',
            'Ẁ'=>'W',
            'ẁ'=>'w',
            'Ŵ'=>'W',
            'ŵ'=>'w',
            'Ẅ'=>'W',
            'ẅ'=>'w',
            'Ẇ'=>'W',
            'ẇ'=>'w',
            'Ẉ'=>'W',
            'ẉ'=>'w',
            'Ẍ'=>'X',
            'ẍ'=>'x',
            'Ẋ'=>'X',
            'ẋ'=>'x',
            'ᶍ'=>'x',
            'Ý'=>'Y',
            'ý'=>'y',
            'Ỳ'=>'Y',
            'ỳ'=>'y',
            'Ŷ'=>'Y',
            'ŷ'=>'y',
            'Ÿ'=>'Y',
            'ÿ'=>'y',
            'Ỹ'=>'Y',
            'ỹ'=>'y',
            'Ẏ'=>'Y',
            'ẏ'=>'y',
            'Ȳ'=>'Y',
            'ȳ'=>'y',
            'Ỷ'=>'Y',
            'ỷ'=>'y',
            'Ỵ'=>'Y',
            'ỵ'=>'y',
            'ʏ'=>'y',
            'Ɏ'=>'Y',
            'ɏ'=>'y',
            'Ƴ'=>'Y',
            'ƴ'=>'y',
            'Ź'=>'Z',
            'ź'=>'z',
            'Ẑ'=>'Z',
            'ẑ'=>'z',
            'Ž'=>'Z',
            'ž'=>'z',
            'Ż'=>'Z',
            'ż'=>'z',
            'Ẓ'=>'Z',
            'ẓ'=>'z',
            'Ẕ'=>'Z',
            'ẕ'=>'z',
            'Ƶ'=>'Z',
            'ƶ'=>'z',
            'ᵶ'=>'z',
            'ᶎ'=>'z',
            'Ȥ'=>'Z',
            'ȥ'=>'z',
            'ʐ'=>'z',
            'ʑ'=>'z',
            'ɀ'=>'z',
            'Ⱬ'=>'Z',
            'ⱬ'=>'z'
        );
        foreach($order_info as $order){
            if($order){
                if($order['shipping_method']=='Expedited Shipping (3-6 Working Days)'){
                    if($order['shipping_country_code']=='DE'){
                        $diff_replace['Ä']='AE';
                        $diff_replace['Ö']='OE';
                        $diff_replace['Ü']='UE';
                        $diff_replace['ä']='ae';
                        $diff_replace['ö']='oe';
                        $diff_replace['ü']='ue';
                    }
                    foreach($diff_replace as $key=>$item){
                        if(strstr($order['shipp_name'],$key)){
                            $order['shipp_name'] = str_replace($key,$item,$order['shipp_name']);
                        }
                        if(strstr($order['company'],$key)){
                            $order['company'] = str_replace($key,$item,$order['company']);
                        }
                        if(strstr($order['address_1'],$key)){
                            $order['address_1'] = str_replace($key,$item,$order['address_1']);
                        }
                        if(strstr($order['address_2'],$key)){
                            $order['address_2'] = str_replace($key,$item,$order['address_2']);
                        }
                        if(strstr($order['city'],$key)){
                            $order['city'] = str_replace($key,$item,$order['city']);
                        }
                        if(strstr($order['region'],$key)){
                            $order['region'] = str_replace($key,$item,$order['region']);
                        }
                    }
                }
                $order['address_1'] = str_replace("\r\n"," ",$order['address_1']);
                $order['address_2'] = str_replace("\r\n"," ",$order['address_2']);
                $order['order_number'] = '="'.$order['order_number'].'"';
                $order[] =$export_time;
                fputcsv($fp,$order);
            }
        }
        fclose($fp);
        return $file;
    }
    function get_sale_csv($order_info){
        //$order_info =get_order_info();
        $time =date('Y-m-d-H-i',time());
        $export_time =date('Y-m-d',time());
        $file ='/data/logs/test_order_export/sales_order'.$time.'.csv';
        //$file ='d://test_sale_order.csv';
        $fp = fopen($file, 'w+');
        chmod($file, 0777);
        fputcsv($fp,array('订单创建时间','订单号','sku','数量','供应商商品代码','供应商代码','商品规格属性','商品名称','商品链接','商品图片','商品单价','运费','折扣','订单总金额','运送方式','国家','订单导出日期'));
        foreach($order_info as $order){
            if($order){
                $order[] =$export_time;
                unset($order['currency_code']);
                unset($order['price']);
                unset($order['shipping_amount']);
                unset($order['discount_amount']);
                unset($order['grand_total']);
                unset($order['shipp_name']);
                unset($order['company']);
                unset($order['address_1']);
                unset($order['address_2']);
                unset($order['city']);
                unset($order['region']);
                unset($order['postcode']);
                unset($order['telephone']);
                unset($order['vat_id']);
                fputcsv($fp,$order);
            }
        }
        fclose($fp);
        return $file;
    }
    function get_pur_order_info($order_info){
        //$order_info =get_order_info();
        $tmpArray = array();
        foreach ($order_info as $row) {
            $key = $row['sku'];
            if (array_key_exists($key, $tmpArray)) {
                $tmpArray[$key]['qty'] = $tmpArray[$key]['qty']+ intval($row['quantity']);
            } else {
                $tmpArray[$key]['date'] = $row['date_added'];
                $tmpArray[$key]['sku'] = $row['sku'];
                $tmpArray[$key]['qty'] = intval($row['quantity']);
                $tmpArray[$key]['product_code'] = $row['product_code'];
                $tmpArray[$key]['supplier_code'] = $row['supplier_code'];
                $tmpArray[$key]['sale_pro_attr'] = $row['sale_pro_attr'];
                $tmpArray[$key]['pname'] = $row['name'];
                $tmpArray[$key]['purl'] = $row['pro_url'];
                $tmpArray[$key]['shipping_method'] = $row['shipping_method'];
            }
        }
        return $tmpArray;
    }
    function get_pur_order_csv($order_info){
        $pur_order_info =get_pur_order_info($order_info);
        $time =date('Y-m-d-H-i',time());
        $export_time =date('Y-m-d',time());
        $file ='/data/logs/test_order_export/purchase_order'.$time.'.csv';
        //$file ='d://test_purchase_order.csv';
        $fp = fopen($file, 'w+');
        chmod($file, 0777);
            fputcsv($fp,array('订单创建时间','sku','数量','供应商商品代码','供应商代码','商品规格属性','商品名称','商品链接','运送方式','订单导出日期'));
        foreach($pur_order_info as $order){
            if($order){
                $order[] =$export_time;
                fputcsv($fp,$order);
            }
        }
        fclose($fp);
        return $file;
    }
    function array_unique_fb($array2D)
    {
        foreach ($array2D as $v)
        {
            $v = join("||",$v);
            $temp[] = $v;
        }
        $temp = array_unique($temp);
        foreach ($temp as $k => $v)
        {
            $array=explode("||",$v);
            $temp2[$k]["date_added"] =$array[0];
            $temp2[$k]["order_number"] =$array[1];
            $temp2[$k]["sku"] =$array[2];
            $temp2[$k]["quantity"] =$array[3];
            $temp2[$k]["product_code"] =$array[4];
            $temp2[$k]["supplier_code"] =$array[5];
            $temp2[$k]["sale_pro_attr"] =$array[6];
            $temp2[$k]["name"] =$array[7];
            $temp2[$k]["pro_url"] =$array[8];
            $temp2[$k]["images"] =$array[9];
            $temp2[$k]["base_price"] =$array[10];
            $temp2[$k]["base_shipping_amount"] =$array[11];
            $temp2[$k]["base_discount_amount"] =$array[12];
            $temp2[$k]["base_grand_total"] =$array[13];
            $temp2[$k]["currency_code"] =$array[14];
            $temp2[$k]["price"] =$array[15];
            $temp2[$k]["shipping_amount"] =$array[16];
            $temp2[$k]["discount_amount"] =$array[17];
            $temp2[$k]["grand_total"] =$array[18];
            $temp2[$k]["shipping_method"] =$array[19];
            $temp2[$k]["shipping_country_code"] =$array[20];
            $temp2[$k]["shipp_name"] =$array[21];
            $temp2[$k]["company"] =$array[22];
            $temp2[$k]["address_1"] =$array[23];
            $temp2[$k]["address_2"] =$array[24];
            $temp2[$k]["city"] =$array[25];
            $temp2[$k]["region"] =$array[26];
            $temp2[$k]["postcode"] = '="'.$array[27].'"';
            $temp2[$k]["telephone"] = '="'.$array[28].'"';
            $temp2[$k]["vat_id"] = '="'.$array[29].'"';
        }
        return $temp2;
    }
    function get_sale_pro_attr($sku){
        $have_attr_name =array('外观颜色','发光颜色','孔径尺寸','直径','是否可调光','输出电流','输入电流','输入电压','输出电压','水晶体尺寸');
        $data =array();
        $product_id =get_product_id($sku);
        $query = mysql_query("select attr_id,attr_name from oc_sale_product_attr where product_id=".$product_id);
        while($row =mysql_fetch_assoc($query)){
            $query_attr_value =mysql_query("select naov.option_value from  oc_new_product_attribute as npa left join oc_new_attribute_option_value as naov on npa.attr_option_value_id =naov.option_id where npa.product_id =".$product_id." and npa.attribute_id=".$row['attr_id']." and naov.language_id=99 limit 1");
            $res =mysql_fetch_assoc($query_attr_value);
            $attr_value =$res['option_value'];
            if(in_array($row['attr_name'],$have_attr_name)){
                $data[] =$row['attr_name'].":".$attr_value;
            }
            else{
                $data[] =$attr_value;
            }

        }
        return $data;
    }

    function get_product_id($sku){
        $query =mysql_query("select product_id from oc_product where model='".$sku."'");
        $row =mysql_fetch_assoc($query);
        if($row){
            return $row['product_id'];
        }
        else{
            return false;
        }
    }
    function update_status($order_info){
        //$order_info =get_order_info();
        foreach($order_info as $item){
            $sql = " UPDATE oc_order SET oc_order.send_forecast_status=2  where oc_order.order_number ='".$item['order_number']."'";
            $query =mysql_query($sql);
        }
        
    }
    function update_parent_status($order_info){
        //修改父订单
        foreach($order_info as $item){
            $order_number = $item['order_number'];
            $sql = "select order_id,is_parent,parent_id from oc_order where order_number = '{$order_number}'";
            $query = mysql_query($sql);
            $row = mysql_fetch_assoc($query);
            if($row && $row['is_parent'] == 0 && $row['parent_id'] > 0){
                $parent_id = $row['parent_id'];
                $sql_parent = "select order_id,send_forecast_status,order_status_id from oc_order where is_parent = 0 and  parent_id = '{$parent_id}'";
                $rs_parent = mysql_query($sql_parent);
                $is_all_send_forecast = 1;
                while($row_parent = mysql_fetch_assoc($rs_parent)){
                    $send_forecast_status = $row_parent['send_forecast_status'];
                    if($send_forecast_status != 2){
                        $is_all_send_forecast = 0;
                    }
                }
                if($is_all_send_forecast){
                    $sql_update_parent = " UPDATE oc_order SET oc_order.send_forecast_status=2  where order_id ='".$parent_id."'";
                    mysql_query($sql_update_parent);
                }
            }
            
        }
    }

    function send_email($sender,$attachment,$title){
        require_once("/home/www/new_myled.com/system/lib/PHPMailer/class.phpmailer.php");
        //require_once("D://XAMPP/htdocs/www.myled.com/lib/PHPMailer/class.phpmailer.php");
        $mail = new PHPMailer(); //建立邮件发送类
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->SMTPAuth   = false;
        //$mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.myled.com"; // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = "cgdd@myled.com"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "quickcggd8"; // 邮局密码
        $mail->Port=25;
        $mail->From = "cgdd@myled.com"; //邮件发送者email地址
        $mail->FromName = "myled";
        $mail->CharSet = "UTF-8";
        foreach($sender as $send_to){
            $mail->AddAddress($send_to, "subject");
        }
        $mail->AddAttachment($attachment); // 添加附件
        //$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title; //邮件标题
        $mail->Body = "myled销售订单邮件"; //邮件内容
        if(!$mail->Send())
        {
            $message =$title.'邮件错误信息:'.$mail->ErrorInfo."<br>";
        }
        else {
            $message =  $title."邮件发送成功!<br />";
        }
        return $message;
    }
    $order_info =get_order_info();

    $detail_csv =get_detail_sales_csv($order_info);
    $order_csv =get_sale_csv($order_info);
    $pur_csv =get_pur_order_csv($order_info);
    $send_to_detail =array('jeremyyin2012@gmail.com','csmyled@gmail.com');
    $send_to_order =array('jeremyyin2012@gmail.com','jeremy@myled.com','myledpo@gmail.com','julie@myled.com','icy@myled.com','coulson@myled.com','sharon@myled.com','susie@myled.com');
    $send_to_pur = array('jeremyyin2012@gmail.com','jeremy@myled.com','myledpo@gmail.com','julie@myled.com','icy@myled.com','coulson@myled.com','sharon@myled.com','susie@myled.com');
    $mes1 =send_email($send_to_detail,$detail_csv,'detail_sales_order---订单邮件');
    $mes2 =send_email($send_to_order,$order_csv,'sales_order---订单邮件');
    $mes3 =send_email($send_to_pur,$pur_csv,'pruchase_order---订单邮件');
    $time =date('Y-m-d-H',time());
    file_put_contents('/data/logs/test_order_export/emial_send_info'.$time.'.txt',$mes1.$mes2.$mes3);
    update_status($order_info);
    update_parent_status($order_info);

?>