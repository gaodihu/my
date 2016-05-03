<?php

/*
 * 得到销售订单脚本
 *
 *
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('/home/www/new_myled.com/script/conf.php');
//require_once("E:/www/code/branches/charles0907/script/conf.php");
ini_set('memory_limit', '502M');
set_time_limit(0);

//define("GOOGLE_FEED_PATH","E:/www/code/branches/charles0907/feed/");
define("GOOGLE_FEED_PATH","/home/www/new_myled.com/feed/");


function get_goods_info($county_name, $start, $limit) {
    $host = 'https://www.myled.com';
    switch ($county_name) {
        case 'shareasale':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'US';
            $currency_code = 'USD';
            break;
        case 'facebook_en':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'US';
            $currency_code = 'USD';
            break;
        case 'facebook_de':
            $host = 'https://de.myled.com';
            $lang_id = 4;
            $store_id = 52;
            $dest_country_id = 'DE';
            $currency_code = 'EUR';
            break;
        case 'facebook_fr':
            $host = 'https://fr.myled.com';
            $lang_id = 5;
            $store_id = 54;
            $dest_country_id = 'FR';
            $currency_code = 'EUR';
            break;

        case 'au':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'AU';
            $currency_code = 'AUD';
            break;
        case 'ca':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'CA';
            $currency_code = 'CAD';
            break;

        case 'ca_fr':
            $host = 'https://fr.myled.com';
            $lang_id = 5;
            $store_id = 54;
            $dest_country_id = 'CA';
            $currency_code = 'CAD';
            break;

        case 'de':
            $host = 'https://de.myled.com';
            $lang_id = 4;
            $store_id = 52;
            $dest_country_id = 'DE';
            $currency_code = 'EUR';
            break;
        case 'dk':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'DK';
            $currency_code = 'DKK';
            break;
        case 'es':
            $host = 'https://es.myled.com';
            $lang_id = 6;
            $store_id = 53;
            $dest_country_id = 'ES';
            $currency_code = 'EUR';
            break;
        case 'fr':
            $host = 'https://fr.myled.com';
            $lang_id = 5;
            $store_id = 54;
            $dest_country_id = 'FR';
            $currency_code = 'EUR';
            break;
        case 'it':
            $host = 'https://it.myled.com';
            $lang_id = 7;
            $store_id = 55;
            $dest_country_id = 'IT';
            $currency_code = 'EUR';
            break;
        case 'nl':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'NL';
            $currency_code = 'EUR';
            break;
        case 'pt':
            $host = 'https://pt.myled.com';
            $lang_id = 8;
            $store_id = 56;
            $dest_country_id = 'PT';
            $currency_code = 'BRL';
            break;
        case 'uk':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'GB';
            $currency_code = 'GBP';
            break;
        case 'at':
            $host = 'https://de.myled.com';
            $lang_id = 4;
            $store_id = 52;
            $dest_country_id = 'AT';
            $currency_code = 'EUR';
            break;
        case 'che':
            $host = 'https://de.myled.com';
            $lang_id = 4;
            $store_id = 52;
            $dest_country_id = 'CHE';
            $currency_code = 'CHF';
            break;
        case 'mx':
            $host = 'https://es.myled.com';
            $lang_id = 6;
            $store_id = 53;
            $dest_country_id = 'MX';
            $currency_code = 'MXN';
            break;
        case 'in':
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'IN';
            $currency_code = 'INR';
            break;
        case 'be':
            $host = 'https://fr.myled.com';
            $lang_id = 5;
            $store_id = 54;
            $dest_country_id = 'BE';
            $currency_code = 'EUR';
            break;
        default:
            $host = 'https://www.myled.com';
            $lang_id = 1;
            $store_id = 0;
            $dest_country_id = 'US';
            $currency_code = 'USD';
            break;
    }

    $sql = "select p.product_id,p.model,pd.name,p.url_path,p.stock_status_id,
        p.image,p.price as base_price,pd.description,pd.features,
        p.weight,p.length,p.width,p.height,concat('ENMLED',p.model) AS mpn,'MyLED' as brand,'Home & Garden > Lighting > Light Bulbs > LED Light Bulbs' AS google_product_category
        from oc_product as p
        left join oc_product_description as pd  on p.product_id=pd.product_id
        WHERE p.stock_status_id = 7 and  p.quantity > 0 AND   pd.language_id=" . $lang_id . " limit $start,$limit";
    $query = mysql_query($sql);
    $res = array();
    $order_data = get_seller_number(7, $county_name, $dest_country_id, $store_id);
    while ($row = mysql_fetch_assoc($query)) {
        $sql_currency = "select value from oc_currency where code='" . $currency_code . "'";
        $query_curr = mysql_query($sql_currency);
        $curre_row = mysql_fetch_assoc($query_curr);
        $currency_rate = $curre_row['value'];

        //$row['description'] = str_replace(array("\r\n", "\r", "\n"), "", html_entity_decode($row['description']));

        $row['description'] = str_replace(array("\r\n", "\r", "\n"), "", html_entity_decode($row['features']));

        if( $county_name == 'shareasale'){
            $shipping_cost =  false;
        }
        else{
            $shipping_cost = getMinShippingFee($dest_country_id,$row['weight'],$row['length'],$row['width'],$row['height'],$lang_id);
        }
        
        if($shipping_cost === false){
            $row['shipping_cost'] = '::';
        }else{
            $row['shipping_cost'] = $dest_country_id .":" .$shipping_cost['method'].":". number_format((float) ($shipping_cost['price'] * $currency_rate), 2) . $currency_code;
        }
        $row["float_weight"] = floor($row['weight']);
        $row['weight'] = floor($row['weight']) . "g";
        $row['link'] = $host . '/' . $row['url_path'] . ".html?currency=" . $currency_code;
        $ext = substr($row['image'], strrpos($row['image'], '.'));
        $row['big_image'] = $host . "/image/cache/" . substr($row['image'], 0, strrpos($row['image'], '.')) . "-455x455" . $ext;
        $row['small_image'] = $host . "/image/cache/" . substr($row['image'], 0, strrpos($row['image'], '.')) . "-170x170" . $ext;
        if($county_name == 'facebook_en' || $county_name == 'facebook_de' || $county_name == 'facebook_fr' ) {
            $row['base_price'] = round((float)($row['base_price'] * $currency_rate), 2);
        }else{
            $row['base_price'] = number_format((float)($row['base_price'] * $currency_rate), 2);
        }
        $row['base_price_curreny'] = $row['base_price'] . $currency_code;
        if ($county_name == 'uk') {
            //剔除plug type 是EU Standard(530)和US Standard(535)的商品
            $query_eu_id = mysql_query("select option_id from oc_new_attribute_option_value where language_id=1 and option_value='EU Standard' order by option_id asc limit 1 ");
            $row_eu_id = mysql_fetch_assoc($query_eu_id);
            $eu_option_id = $row_eu_id['option_id'];
            $eu_products = get_option_products($eu_option_id);
            $query_us_id = mysql_query("select option_id from oc_new_attribute_option_value where language_id=1 and option_value='US Standard' order by option_id asc limit 1 ");
            $row_us_id = mysql_fetch_assoc($query_us_id);
            $us_option_id = $row_us_id['option_id'];
            $us_products = get_option_products($us_option_id);
            if (in_array($row['product_id'], $eu_products) || in_array($row['product_id'], $us_products)) {
                $row['stock_status_id'] = 5;
            }
        }
        //得到特价
        $time = date("Y-m-d 23:59:59", time());
        $sql_special = "select price from  oc_product_special where product_id =" . $row['product_id'] . " and date_start <= now() and date_end>='" . $time . "' and customer_group_id=0 order by priority asc limit 1 ";
        $query_special = mysql_query($sql_special);
        $row_special = mysql_fetch_assoc($query_special);
        if ($row_special['price']) {
            if($county_name == 'facebook_en' || $county_name == 'facebook_de' || $county_name == 'facebook_fr') {
                $row['sale_price'] = round((float) ($row_special['price'] * $currency_rate), 2);
                $row['sale_price_curreny'] = round((float) ($row_special['price'] * $currency_rate), 2) . $currency_code;
            }else{
                $row['sale_price'] = number_format((float) ($row_special['price'] * $currency_rate), 2);
                $row['sale_price_curreny'] = number_format((float) ($row_special['price'] * $currency_rate), 2) . $currency_code;
            }

        } else {
            $row['sale_price'] = $row['base_price'];
            $row['sale_price_curreny'] = $row['base_price_curreny'];
        }
        if($row['sale_price'] < $row['base_price'] ){
            $row['discount_off'] = ceil(($row['base_price'] - $row['sale_price'])/$row['base_price'] * 100);
            $row['discount_off'] = sprintf("%2d",$row['discount_off']);

        }else{
            $row['discount_off'] = "";
        }
        //商品light和material属性
        $light_attr_id = 161;
        $material_attr_id = 165;
        $query_light_option = mysql_query("select attr_option_value_id from oc_product_attribute where product_id=" . $row['product_id'] . " and attribute_id=161 limit 1");
        $res_light_option = mysql_fetch_assoc($query_light_option);
        if ($res_light_option && $res_light_option['attr_option_value_id']) {
            $query_light_option_value = mysql_query("select option_value from oc_attribute_option_value where option_id=" . (int) $res_light_option['attr_option_value_id'] . " and language_id=" . $lang_id . " limit 1");
            $res_light_option_value = mysql_fetch_assoc($query_light_option_value);
            $row['light'] = $res_light_option_value['option_value'];
        } else {
            $row['light'] = '';
        }
        $query_material_option = mysql_query("select attr_option_value_id from oc_product_attribute where product_id=" . $row['product_id'] . " and attribute_id=165 limit 1");
        $res_material_option = mysql_fetch_assoc($query_material_option);
        if ($res_material_option && $res_material_option['attr_option_value_id']) {
            $query_material_option_value = mysql_query("select option_value from oc_attribute_option_value where option_id=" . (int) $res_material_option['attr_option_value_id'] . " and language_id=" . $lang_id . " limit 1");
            $res_material_option_value = mysql_fetch_assoc($query_material_option_value);
            $row['material'] = $res_material_option_value['option_value'];
        } else {
            $row['material'] = '';
        }
        //商品分类
        $query_catagory = mysql_query("select max(category_id) as category_id  from oc_product_to_category where product_id=" . $row['product_id']);
        $res_catagory = mysql_fetch_assoc($query_catagory);
        $category_id = $res_catagory['category_id'];
        $query_path = mysql_query("SELECT path from oc_category where category_id='" . $category_id . "'");
        $res_path = mysql_fetch_assoc($query_path);
        $path = $res_path['path'];
        $cat_arr = explode("/", $path);
        $catagory_arr = array();
        foreach ($cat_arr as $cat_id) {
            if ($cat_id > 0) {
                //$query_cat_name =mysql_query("SELECT name from oc_category_description where category_id='".$cat_id."' and language_id=".$lang_id);
                $query_cat_name = mysql_query("SELECT name from oc_category_description where category_id='" . $cat_id . "' and language_id=1");
                $row_cat = mysql_fetch_assoc($query_cat_name);
                $catagory_arr[] = $row_cat['name'];
            }
        }
        $row['catagory'] = implode(">", $catagory_arr);
        $row['parent_catagory'] = $catagory_arr[0];
        $row['catagory_level2'] = $catagory_arr[1];
        $row['adwords_labels'] = end($catagory_arr);
        $row['seller_num'] = isset($order_data[$row['product_id']]) ? $order_data[$row['product_id']] : 0;
        $res[] = $row;
    }
    return $res;
}

function get_total_goods() {
    $total_query = mysql_query("select count(*) as total from oc_product where stock_status_id = 7 and  quantity > 0");
    $res_total = mysql_fetch_assoc($total_query);
    return $res_total['total'];
}

function get_google_feed_csv($county_name) {
    //数据量太多，分批次处理，每次3000行
    $fp = creat_feed_csv($county_name);
    $count = get_total_goods();
    $num = ceil($count / 3000);
    for ($i = 1; $i <= $num; $i++) {
        $start = 3000 * ($i - 1);
        $product_info = get_goods_info($county_name, $start, 3000);
        $write_sting = '';
        foreach ($product_info as $product) {
            if(empty($product['catagory'])){
                continue;
            }
            $new_arr = array();
            if ($product) {
                if ($county_name == 'us') {
                    $new_arr[0] = $product['product_id'];
                } else {
                    $new_arr[0] = $product['product_id'] . "-" . $county_name;
                }
                if(strlen($product['name'])>150){
                    $product['name'] = substr($product['name'],0,147) . "...";
                }
                if ($county_name == 'us') {
                    $new_arr[1] = $product['name'] ;
                } else {
                    $new_arr[1] = $product['name'];
                }
                $new_arr[2] = $product['base_price_curreny'];
                $new_arr[3] = $product['sale_price_curreny'];
                $new_arr[4] = $product['big_image'];
                $new_arr[5] = $product['link'];
                if($product['description']) {
                    $new_arr[6] = $product['description'];
                }else{
                    $new_arr[6] = $product['name'];
                }
                $new_arr[8] = $product['mpn'];
                $new_arr[9] = $product['brand'];
                $new_arr[10] = $product['catagory'];
                $new_arr[11] = $product['google_product_category'];
                $new_arr[13] = $product['weight'];
                $new_arr[14] = $product['adwords_labels'];
                $new_arr[15] = $product['light'];
                $new_arr[16] = $product['material'];
                $shipping_cost =  $product['shipping_cost'];
                if ($county_name == 'au') {
                    $new_arr[17] = $shipping_cost;
                    $new_arr[18] = $product['product_id'];
                    $new_arr[19] = $product['seller_num'];
                } else {
                    $new_arr[17] = $shipping_cost;
                    $new_arr[18] = $product['product_id'];
                    $new_arr[19] = $product['seller_num'];
                }
                //$new_arr[20]=$product['name'];
                if ($county_name == 'au' || $county_name == 'ca' || $county_name == 'nl' || $county_name == 'uk' || $county_name == 'us' || $county_name == 'in') {
                    $new_arr[7] = 'New';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'in stock';
                    } else {
                        $availability = 'out of stock';
                    }
                    $new_arr[12] = $availability;
                }
                if ($county_name == 'de' || $county_name == 'at' || $county_name == 'che') {
                    $new_arr[7] = 'Neu';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'Auf Lager';
                    } else {
                        $availability = 'Nicht auf Lager';
                    }
                    $new_arr[12] = $availability;
                }
                if ($county_name == 'dk') {
                    $new_arr[7] = 'New';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'på lager';
                    } else {
                        $availability = 'ikke på lager';
                    }
                    $new_arr[12] = $availability;
                }
                if ($county_name == 'es' || $county_name == 'mx') {
                    $new_arr[7] = 'nuevo';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'en stock';
                    } else {
                        $availability = 'agotado';
                    }
                    $new_arr[12] = $availability;
                }
                if ($county_name == 'fr' || $county_name == 'be' || $county_name=='ca_fr') {
                    $new_arr[7] = 'neuf';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'en stock';
                    } else {
                        $availability = 'non disponible';
                    }
                    $new_arr[12] = $availability;
                }
                if ($county_name == 'it') {
                    $new_arr[7] = 'nuovo';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'disponibile';
                    } else {
                        $availability = 'non disponibile';
                    }
                    $new_arr[12] = $availability;
                }

                if ($county_name == 'pt') {
                    $new_arr[7] = 'novo';
                    if ($product['stock_status_id'] == 7) {
                        $availability = 'em estoque';
                    } else {
                        $availability = 'esgotado';
                    }
                    $new_arr[12] = $availability;
                }

                $new_arr[count($new_arr)] = $product['discount_off'];
                ksort($new_arr);

                $write_sting .=implode("\t", $new_arr);
                $write_sting .="\n";
                //fputcsv($fp, $new_arr,"\t"," ");
            }
        }
        fwrite($fp, $write_sting);
    }
    fclose($fp);
}

function creat_feed_csv($county_name) {
    $file = GOOGLE_FEED_PATH.'prd_google_feed.csv';
    switch ($county_name) {
        case 'au':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_au.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'ca':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_ca.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;

        case 'ca_fr':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_ca_fr.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('identifiant', 'titre', 'prix', 'prix soldé', 'lien image', 'lien', 'description', 'état', 'référence fabricant', 'marque', 'catégorie', 'catégorie de produits Google', 'disponibilité', 'poids du colis', 'adwords étiquettes', 'couleur', 'matière', 'livraison(pays livraison:service livraison :frais livraison)', 'identifiant groupe', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;

        case 'de':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_de.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('ID', 'Titel', 'Preis', 'Sonderangebotspreis', 'Bildlink', 'Link', 'Beschreibung', 'Zustand', 'MPN', '    Marke', 'Produkttyp', 'google_product_category', 'Verfügbarkeit', 'versandgewicht', 'Adwords labels', 'Farbe', 'material', 'Versand(land:service:preis)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'dk':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_dk.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'es':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_es.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'título', 'precio', 'precio de oferta', 'enlace_imagen', 'enlace', 'descripción', 'estado', 'MPN', 'marca', 'categoría', 'categoría en google product', 'disponibilidad', 'peso de embarque', 'adwords etiquetas', 'color', 'material', 'envío(país envío:servicio envío:precio)', 'identificador de grupo', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'fr':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_fr.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('identifiant', 'titre', 'prix', 'prix soldé', 'lien image', 'lien', 'description', 'état', 'référence fabricant', 'marque', 'catégorie', 'catégorie de produits Google', 'disponibilité', 'poids du colis', 'adwords étiquettes', 'couleur', 'matière', 'livraison(pays livraison:service livraison :frais livraison)', 'identifiant groupe', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'it':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_it.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'titolo', 'prezzo', 'prezzo scontato', 'link_immagine', 'link', 'descrizione', 'condizione', 'MPN', 'marca', 'categoria', 'categoria prodotto google', 'disponibilità', 'peso spedizione', 'adwords etichette', 'colore', 'materiale', 'spedizione（paese:servizio:prezzo)', 'identificatore di gruppo', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'pt':
            $file =GOOGLE_FEED_PATH.'prd_google_feed_pt.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('código', 'título', 'preço', 'preço de venda', 'link da imagem', 'link', 'descrição', 'estado', 'MPN', 'marca', 'tipo de produto', 'categoria google do produto', 'disponibilidade', 'peso com embalagem', 'adwords etiquetas', 'cor', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'nl':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_nl.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'uk':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_uk.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'at':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_at.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('ID', 'Titel', 'Preis', 'Sonderangebotspreis', 'Bildlink', 'Link', 'Beschreibung', 'Zustand', 'MPN', '    Marke', 'Produkttyp', 'google_product_category', 'Verfügbarkeit', 'versandgewicht', 'Adwords labels', 'Farbe', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'che':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_che.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('ID', 'Titel', 'Preis', 'Sonderangebotspreis', 'Bildlink', 'Link', 'Beschreibung', 'Zustand', 'MPN', '    Marke', 'Produkttyp', 'google_product_category', 'Verfügbarkeit', 'versandgewicht', 'Adwords labels', 'Farbe', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'mx':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_mx.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'título', 'precio', 'precio de oferta', 'enlace_imagen', 'enlace', 'descripción', 'estado', 'MPN', 'marca', 'categoría', 'categoría en google product', 'disponibilidad', 'peso de embarque', 'adwords etiquetas', 'color', 'material', 'shipping(country:service:price)', 'identificador de grupo', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'in':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_in.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
        case 'be':
            $file = GOOGLE_FEED_PATH.'prd_google_feed_be.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('identifiant', 'titre', 'prix', 'prix soldé', 'lien image', 'lien', 'description', 'état', 'référence fabricant', 'marque', 'catégorie', 'catégorie de produits Google', 'disponibilité', 'poids du colis', 'adwords étiquettes', 'couleur', 'matière', 'shipping(country:service:price)', 'identifiant groupe', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;

        default:
            $file = GOOGLE_FEED_PATH.'prd_google_feed.csv';
            if (file_exists($file)) {
                unlink($file);
            }
            $fp = fopen($file, 'a+');
            chmod($file, 0777);
            $txt_title = implode("\t", array('id', 'title', 'price', 'sale_price', 'Image_link', 'link', 'description', 'condition', 'mpn', 'brand', 'product_type', 'google_product_category', 'availability', 'shipping_weight', 'adwords_labels', 'color', 'material', 'shipping(country:service:price)', 'item group id', 'custom_label_0','custom_label_1'));
            $txt_title .="\n";
            fwrite($fp, $txt_title);
            break;
    }
    return $fp;
}

function get_sharesales_feed($name) {
    $file = GOOGLE_FEED_PATH.'shareasale_feed.csv';
    $fp = fopen($file, 'a+');
    chmod($file, 0777);
    fputcsv($fp, array('SKU', 'name', 'URL', 'Price', 'RetailPrice', 'FullImage', 'ThumbnailImage', 'Commission Category', 'SubCategory', 'description', 'SeachTerms', 'Stock'));
    $count = get_total_goods();
    $num = ceil($count / 3000);
    for ($i = 1; $i <= $num; $i++) {
        $start = 3000 * ($i - 1);
        $product_info = get_goods_info($name, $start, 3000);
        foreach ($product_info as $product) {
            $new_arr = array();
            if ($product) {
                $new_arr[] = $product['model'];
                $new_arr[] = $product['name'];
                $new_arr[] = $product['link'];
                $new_arr[] = $product['base_price'];
                $new_arr[] = $product['sale_price'];
                $new_arr[] = $product['big_image'];
                $new_arr[] = $product['small_image'];
                $new_arr[] = $product['parent_catagory'];
                $new_arr[] = $product['adwords_labels'];
                $new_arr[] = $product['description'];
                $new_arr[] = $product['adwords_labels'];
                if ($product['stock_status_id'] == 7) {
                    $availability = 'in stock';
                } else {
                    $availability = 'out of stock';
                }
                $new_arr[] = $availability;
                fputcsv($fp, $new_arr);
            }
        }
    }
    fclose($fp);
}



function get_facebook_feed($name) {

   
    if($name == 'facebook_en'){
        $lang_id = 1;
        $file = GOOGLE_FEED_PATH.'dpa_fb_feed_en.csv';
    }
    if($name == 'facebook_de'){
        $lang_id = 4;
        $file = GOOGLE_FEED_PATH.'dpa_fb_feed_de.csv';
    }
    if($name == 'facebook_fr'){
        $lang_id = 5;
        $file = GOOGLE_FEED_PATH.'dpa_fb_feed_fr.csv';
    }

    if(file_exists($file)){
        unlink($file);
    }
    $fp = fopen($file, 'a+');
    chmod($file, 0777);
    fputcsv($fp, array("id","condition","description","google_product_category","image_link","link","price","title","brand","availability","product_type","applink_ios_app_name","applink_ios_url","applink_iphone_url","applink_iphone_app_name","applink_ipad_url","applink_ipad_app_name","applink_android_url","applink_android_package","applink_android_app_name"),"\t");
    $count = get_total_goods($name);
    $num = ceil($count / 3000);
    for ($i = 1; $i <= $num; $i++) {
        $start = 3000 * ($i - 1);
        $product_info = get_goods_info($name, $start, 3000);
        foreach ($product_info as $product) {
            $new_arr = array();
            if ($product) {
                if($product['model'] == '1810930046'){
                    print_r($product);
                }
                $str_line = "";


                $str_line .= $product['model']."\t";
                $str_line .= "new"."\t";
                $str_line .= str_replace("\t","",getProductAttributes($product['product_id'],$lang_id).$product['description'])."\t";;
                $str_line .= $product['catagory_level2']."\t";
                $str_line .= $product['big_image']."\t";;
                $str_line .= $product['link']. "?utm_source=facebook&utm_medium=ads&utm_campaign=DPA" ."\t";;
                $str_line .= $product['sale_price_curreny']."\t";;
                $str_line .= str_replace("\t","",$product['name'])."\t";;

                $str_line .= 'MyLED'."\t";;
                $str_line .= 'IN STOCK'."\t";

                $str_line .= ($product['catagory_level2'] ? $product['catagory_level2'] : $product['parent_catagory'])."\t";

                $str_line .= ''."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\t";;
                $str_line .= ""."\n";


                fwrite($fp,$str_line);
                //fputcsv($fp, $str_line,"\t");
            }
        }

    }
    fclose($fp);
}


function getProductAttributes($product_id,$lang_id=1) {

    $product_attribute_data = array();
    $product_attribute_query = mysql_query("SELECT pa.attribute_id, ad.name, aov.option_value as text FROM oc_new_product_attribute pa LEFT JOIN oc_new_attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN oc_new_attribute_description ad ON (pa.attribute_id = ad.attribute_id) left join oc_new_attribute_option_value as aov on pa.attr_option_value_id=aov.option_id WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '{$lang_id}' AND aov.language_id = '{$lang_id}' ORDER BY a.sort_order, ad.name");
    $text = "";
    while ($product_attribute = mysql_fetch_assoc($product_attribute_query)) {
        $product_attribute_data[] = array(
            'attribute_id' => $product_attribute['attribute_id'],
            'name'         => $product_attribute['name'],
            'text'         => $product_attribute['text']
        );
        $text .= $product_attribute['name'].":".$product_attribute['text']."<br/>";
    }
    //return $product_attribute_data;
    return $text;
}




function get_seller_number($day = 7, $county_name, $dest_country_id, $store_id) {
    $where = " where 1";
    if (in_array($county_name, array('US', 'CA', 'UK', 'AU', 'DE', 'FR', 'ES', 'IT', 'PT'))) {
        $where.=" AND shipping_country_code='" . $dest_country_id . "' ";
    }
    else {
        $where.=" AND store_id='" . $store_id . "' ";
    }
    $data = array();
    $strat_time = date('Y-m-d H:i:s', time() - 7 * 24 * 3600);
    $strat_time = '2014-11-10 16:00:00';
    $query_time_order = mysql_query("select order_id from oc_order where date_added>='" . $strat_time . "' and order_status_id in (2,5)");
    while ($row_order = mysql_fetch_assoc($query_time_order)) {
        $query_qty = mysql_query("select product_id,quantity from oc_order_product where order_id ='" . $row_order['order_id'] . "' ");
        while ($row_qty = mysql_fetch_assoc($query_qty)) {
            if (isset($data[$row_qty['product_id']])) {
                $data[$row_qty['product_id']] +=(int) $row_qty['quantity'];
            } else {
                $data[$row_qty['product_id']] = (int) $row_qty['quantity'];
            }
        }
    }
    return $data;
}

function get_option_products($option_id) {
    $data = array();
    $query = mysql_query("select product_id from oc_new_product_attribute where attr_option_value_id=" . (int) $option_id);
    while ($row = mysql_fetch_assoc($query)) {
        $data[] = $row['product_id'];
    }
    return $data;
}

function getMinShippingFee($country_code,$weight, $length, $height, $width,$lang_id) {
    if(strtolower($country_code) == 'ca_fr'){
        $country_code = 'CA';
    }
    $volume = $length * $height * $width;
    $total_weigth = ceil($weight);
    $method_data = array();
    $mini_price = false;
    $mini_method = "";
    $airmail = getMethodByCode('airmail', $country_code, $total_weigth,$length, $height, $width);
    if ($airmail) {
        if ($mini_price === false) {
            $mini_price = $airmail['price'];
            $mini_method = $airmail["delivery_method"];
        } else if ($mini_price !== false && $mini_price > $airmail['price']) {
            $mini_price = $airmail['price'];
            $mini_method = $airmail["delivery_method"];
        }
    }

    $standard = getMethodByCode('standard', $country_code, $total_weigth,$length, $height, $width);
    if ($standard) {
        if ($mini_price === false) {
            $mini_price = $standard['price'];
            $mini_method = $standard["delivery_method"];
        } else if ($mini_price !== false && $mini_price > $standard['price']) {
            $mini_price = $standard['price'];
            $mini_method = $standard["delivery_method"];
        }
    }

    $product_cal_weigth = $total_weigth;
    if (ceil($volume / 5) > $total_weigth) {
        $product_cal_weigth = ceil($volume / 5);
    }
    $expedited = getMethodByCode('expedited', $country_code, $product_cal_weigth,$length, $height, $width);
    if ($expedited) {
        if ($mini_price === false) {
            $mini_price = $expedited['price'];
            $mini_method = $expedited["delivery_method"];
        } else if ($mini_price !== false && $mini_price > $expedited['price']) {
            $mini_price = $expedited['price'];
            $mini_method = $expedited["delivery_method"];
        }
    }
    $product_cal_weigth = $total_weigth;
    if (ceil($volume / 8) > $total_weigth) {
        $product_cal_weigth = ceil($volume / 8);
    }
    $ems = getMethodByCode('ems', $country_code, $product_cal_weigth,$length, $height, $width);
    if ($ems) {
        if ($mini_price === false) {
            $mini_price = $ems['price'];
            $mini_method = $ems["delivery_method"];
        } else if ($mini_price !== false && $mini_price > $ems['price']) {
            $mini_price = $ems['price'];
            $mini_method = $ems["delivery_method"];
        }
    }
    $mini_method = getLangName($mini_method,$lang_id);
    return array('method'=>$mini_method,'price' => $mini_price);
}

function getMethodByCode($method_code, $country_code, $total_weigth,$length, $height, $width) {


    if (empty($method_code) || empty($country_code)) {
        return false;
    }
    if (!volumeLimit($method_code, $length, $height, $width, $total_weigth)) {
        return false;
    }
    $query = mysql_query("SELECT * FROM oc_shipping_matrixrate WHERE dest_country_id = '" . $country_code . "' AND delivery_method='" . $method_code . "'  AND  condition_from_value <=  '" . $total_weigth . "' AND  condition_to_value >= '" . $total_weigth . "' limit 1");
    if ($query) {
        $row = mysql_fetch_assoc($query);
        $status = true;
        return $row;
    } else {
        $status = false;
        return false;
    }
}

function volumeLimit($method_code, $length, $height, $width, $weight) {
    $method_code = strtolower($method_code);

    switch ($method_code) {
        case 'airmail':
        case 'standard': {
                $_volume = array($length, $height, $width);
                sort($_volume, SORT_NUMERIC);
                $_h = $_volume[0];
                $_w = $_volume[1];
                $_l = $_volume[2];
                if ($_l > 58 || $_h > 58 || $_w > 58) {
                    return false;
                }
                if ($_l + $_h + $_w > 88) {
                    return false;
                }
            }
            break;
        case 'ems' : {
                $_volume = array($length, $height, $width);
                sort($_volume, SORT_NUMERIC);
                $_h = $_volume[0];
                $_w = $_volume[1];
                $_l = $_volume[2];
                if ($_l > 148 || $_h > 148 || $_w > 148) {
                    return false;
                }
                if ($_l + 2 * $_h + 2 * $_w > 298) {
                    return false;
                }
            }
            break;
        case 'expedited': {
                $_volume = array($length, $height, $width);
                sort($_volume, SORT_NUMERIC);
                $_h = $_volume[0];
                $_w = $_volume[1];
                $_l = $_volume[2];
                $weight = $weight;
                if ($weight < 30000) {
                    if ($_l > 118 || $_w > 80 || $_h > 80) {
                        return false;
                    }
                } else {
                    if ($_l > 158 || $_w > 118 || $_h > 100) {
                        return false;
                    }
                }
            }
            break;
    }
    return true;
}

function getLangName($method,$lang_id){
    $lang = 'en';
    if($lang_id == 1){
        $lang = 'en';
    }
    if($lang_id == 4){
        $lang = 'de';
    }
    if($lang_id == 5){
        $lang = 'fr';
    }

    if($lang_id == 6){
        $lang = 'es';
    }
    if($lang_id == 7){
        $lang = 'it';
    }

    static $lang_arr = array(
        'airmail' => array(
            'en' => 'Super Saver Shipping (10-20 Working Days)',
            'de' => 'Sparversand (10-20 Arbeitstage)',
            'fr' => 'Livraison Super-éco (10-20 Jours Ouvrés)',
            'es' => 'Envío de Súper Ahorro (10-20 Días Laborales)',
            'it' => 'Spedizione Super Risparmio(10-20 Giorni Lavorativi)',
        ),
        'standard' => array(
            'en' => 'Standard Shipping (7-9 Working Days)',
            'de' => 'Standard Versand (7-9 Arbeitstage)',
            'fr' => 'Livraison Standard (7-9 Jours Ouvrés)',
            'es' => 'Envío Estándar (7-9 Días Laborales)',
            'it' => 'Spedizione Standard(7-9 Giorni Lavorativi)',
        ),
        'ems' => array(
            'en' => 'EMS Shipping (9-15 Working Days)',
            'de' => 'EMS Versand (9-15 Arbeitstage)',
            'fr' => 'Livraison EMS (9-15 Jours Ouvrés)',
            'es' => 'Envío EMS (9-15 Días Laborales)',
            'it' => 'Spedizione EMS(9-15 Giorni Lavorativi)',
        ),
        'expedited' => array(
            'en' => 'Expedited Shipping (3-6 Working Days',
            'de' => 'Beschleunigter Versand (3-6 Arbeitstage)',
            'fr' => 'Livraison Express (3-6 Jours Ouvrés)',
            'es' => 'Envío Acelerado (3-6 Días Laborales)',
            'it' => 'Spedizione Rapida(3-6 Giorni Lavorativi)',
        ),
    );
    return $lang_arr[$method][$lang];


}


get_google_feed_csv('au');
get_google_feed_csv('ca');
get_google_feed_csv('de');
get_google_feed_csv('es');
get_google_feed_csv('fr');
get_google_feed_csv('it');
get_google_feed_csv('pt');
get_google_feed_csv('nl');
get_google_feed_csv('uk');
get_google_feed_csv('dk');
get_google_feed_csv('us');
get_sharesales_feed('shareasale');
get_google_feed_csv('at');
get_google_feed_csv('che');
get_google_feed_csv('mx');
get_google_feed_csv('in');
get_google_feed_csv('be');

get_google_feed_csv('ca_fr');


get_facebook_feed("facebook_en");
get_facebook_feed("facebook_de");
get_facebook_feed("facebook_fr");
?>

