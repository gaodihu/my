<?php

class ControllerCommonHeader extends Controller
{
    protected function index()
    {
        $this->language->load('common/header');
        $this->data['text_home'] = $this->language->get('text_home');
        $this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
        $this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'));
        $this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getNickName(), $this->url->link('account/logout', '', 'SSL'));
        $this->data['text_account'] = $this->language->get('text_account');
        $this->data['text_checkout'] = $this->language->get('text_checkout');
        $this->data['text_phone_service'] = $this->language->get('text_phone_service');
        $this->data['text_ticket_service'] = $this->language->get('text_ticket_service');
        $this->data['text_order_search'] = $this->language->get('text_order_search');
        $this->data['text_new_product_post'] = $this->language->get('text_new_product_post');
        $this->data['text_live_chat'] = $this->language->get('text_live_chat');
        $this->data['text_faq'] = $this->language->get('text_faq');
        $this->data['text_contact_us'] = $this->language->get('text_contact_us');
        $this->data['text_exclusive_offers'] = $this->language->get('text_exclusive_offers');
        $this->data['text_enter_search'] = $this->language->get('text_enter_search');
        $this->data['text_enter_emial_address'] = $this->language->get('text_enter_emial_address');
        $this->data['text_led_categories'] = $this->language->get('text_led_categories');
        $this->data['text_my_order'] = $this->language->get('text_my_order');

        $this->data['home'] = $this->url->link('common/home');
        $this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $this->data['logged'] = $this->customer->isLogged();
        $this->data['account'] = $this->url->link('account/account', '', 'SSL');
        $this->data['shopping_cart'] = $this->url->link('checkout/cart');
        $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
        $this->data['new_product_post'] = $this->url->link('service/productPost', '', 'SSL');

        $this->data['text_visit_mobile_version'] = $this->language->get('text_visit_mobile_version');

        // Daniel's robot detector
        $status = true;

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $robots = explode("\n", trim($this->config->get('config_robots')));

            foreach ($robots as $robot) {
                if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
                    $status = false;

                    break;
                }
            }
        }
        //currency_code
        if (isset($this->request->get['currency'])) {
            $this->load->model('localisation/currency');
            $results = $this->model_localisation_currency->getCurrencies();
            $currency_array = array();
            foreach ($results as $res) {
                $currency_array[] = $res['code'];
            }
            if (in_array($this->request->get['currency'], $currency_array)) {
                $this->currency->set($this->request->get['currency']);
            }

        }



        // Search
        if (isset($this->request->get['search'])) {
            $this->data['search'] = $this->request->get['search'];
        } else {
            $this->data['search'] = '';
        }

        // Catalog Menu
        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $this->load->model('catalog/product');



        $this->data['categories'] = array();

        //$this->data['categories'] = $this->cache->get('header.categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
        if (!$this->data['categories']) {
            $categories = $this->model_catalog_category->getCategories(0);
            foreach ($categories as $category) {
                if ($category['top']) {
                    // Level 2
                    $children_data = array();

                    $product_total = $this->model_catalog_product->getTotalProducts(array('filter_category_id' => $category['category_id']));
                    if ($product_total == 0) {
                        continue;
                    }

                    $children = $this->model_catalog_category->getCategories($category['category_id']);

                    foreach ($children as $child) {
                        $data = array(
                            'filter_category_id' => $child['category_id'],
                            'filter_sub_category' => true
                        );

                        //$product_total = $this->model_catalog_product->getTotalProducts($data);
                        $url = '';
                        if ($child['url_path']) {
                            $url = $child['url_path'];
                        } else {
                            $url = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
                        }
                        $children_data[] = array(
                            //子类不需要显示商品数量
                            //'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                            'name' => $child['name'],
                            'href' => $url
                        );
                    }
                    // Level 1
                    $url = '';
                    if ($category['url_path']) {
                        $url = $category['url_path'];
                    } else {
                        $url = $this->url->link('product/category', 'path=' . $category['category_id']);
                    }
                    $this->data['categories']['top']['c-' . $category['category_id']] = array(
                        'name' => $category['name'],
                        //'children' => $children_data,
                        'column' => $category['column'] ? $category['column'] : 1,
                        'href' => $url,

                    );
                    // Level 2
                    if ($category['bg_image']) {
                        //使用原图
                        //$bg_img =$this->model_tool_image->resize($category['bg_image'], CATALOG_BG_IMG_WIDTH, CATALOG_BG_IMG_HEIGHT);
                        $bg_img = IMG_SERVER . $category['bg_image'];
                    } else {
                        $bg_img = false;
                    }
                    if ($children_data) {
                        $this->data['categories']['child']['c-' . $category['category_id']] = array(
                            'children' => $children_data,
                            //分类背景图片
                            'bg_image' => $bg_img,
                            'action_description' => htmlspecialchars_decode(trim($category['action_description']))
                        );
                    }
                }
            }
        }
        $this->cache->set('header.categories.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $this->data['categories']);

        //首页直接显示下级分类，其他页面获得焦点时获得
        $cur_uri = $_SERVER['REQUEST_URI'];
        $cur_uri_arr = explode('?', $cur_uri);
        $cur_uri = $cur_uri_arr[0];
        if ($cur_uri == '/' || ($cur_uri == '/index.php' && empty($_GET['route'])) || (isset($_GET['route']) && $_GET['route'] == 'common/home')) {
            $this->data['show_cat'] = 1;
        } else {
            $this->data['show_cat'] = 0;
        }


        $this->data['search_action'] = $this->url->link('product/search', '', 'SSL');
        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }
        $this->data['search_keyword'] = $search;
        //首页menu
        $this->data['menus'] = array();
        $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

        $this->data['menus'][] = array(
            'text' => $this->language->get('text_menu_new_arrivals'),
            'link' => $this->config->getDomain(). 'new_arrivals.html',
            'is_active' => 0
        );
        $this->data['menus'][] = array(
            'text' => $this->language->get('text_menu_top_sellers'),
            'link' => $this->config->getDomain(). 'top-sellers.html',
            'is_active' => 0
        );
        $this->data['menus'][] = array(
            'text' => $this->language->get('text_menu_deals'),
            'link' => $this->config->getDomain().'deals.html',
            'is_active' => 0
        );
        $this->data['menus'][] = array(
            'text' => $this->language->get('text_menu_clearance'),
            'link' => $this->config->getDomain(). 'clearance.html',
            'is_active' => 0
        );
        $this->data['text_guideline'] = $this->language->get('text_menu_guidelien');
        $this->children = array(
            'common/head',
            'module/language',
            'module/currency',
            'module/cart'
        );
        if (isset($this->session->data['customer_id'])) {
            $this->data['is_login'] = 1;
        } else {
            $this->data['is_login'] = 0;
        }
        $this->data['my_ticket'] = $this->url->link('account/account');
        $this->data['order_search'] = $this->url->link('service/orderSearch');
        $this->data['new_product_post'] = $this->url->link('service/productPost');
        $this->data['contactus'] = $this->config->getDomain(). 'contact-us.html';


        $_lang_code = $this->session->data['language'];
        $_lang_code = strtolower($_lang_code);
        $this->data['lang_code'] = $_lang_code;


        //国庆节活动
        $china_10_1_time = array(
            'start' => strtotime('2015-09-29 11:00:00'),
            'end' => strtotime('2015-10-04 11:00:00'),
        );
        if ($china_10_1_time['start'] <= time() && $china_10_1_time['end'] >= time()) {
            $china_10_1 = true;
            $china_10_1_msg = $this->language->get('text_china_10_1');
            $this->data['china_10_1'] = $china_10_1;
            $this->data['china_10_1_msg'] = $china_10_1_msg;

        }


        // 复活节砸蛋活动banner
        $show_time_start = array(
            'start' => strtotime("2015-04-01 09:00:00"),
            'end' => strtotime("2015-04-06 15:00:00"),
        );
        if (time() >= $show_time_start['start'] && time() < $show_time_start['end']) {
            $this->data['show_esater_banner'] = true;
            $this->data['esater_url'] = $this->url->link('activity/prize', '', 'SSL');
        }


        //Contact Us
        $this->data['text_contact_email'] = $this->language->get('text_contact_email');
        $this->data['text_contact_livechat'] = $this->language->get('text_contact_livechat');
        $this->data['text_contact_more'] = $this->language->get('text_contact_more');;

        $this->data['cookie_domain'] = COOKIE_DOMAIN;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {
            $this->template = 'default/template/common/header.tpl';
        }
        $this->render();
    }
}

?>
