<?php

class ControllerCommonSeoUrl extends Controller {

    public function index() {
        // Add rewrite to url class

        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }

        //重置host，request_uri
        $old_host = $_SERVER['HTTP_HOST'];
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $temp = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $_SERVER['REQUEST_URI'] = str_replace($this->config->getDomain(),"",$temp);
            $_SERVER['HTTP_HOST']   = str_replace("https://","",$this->config->getDomain());
        } else {
            $temp = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            $_SERVER['REQUEST_URI'] = str_replace($this->config->getDomain(),"/",$temp);
            $_SERVER['HTTP_HOST']   = str_replace("http://","",$this->config->getDomain());
        }



        if($this->request->get['_route_']) {
            $domain = $this->config->getDomain();
            if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
                $temp = 'https://' . $old_host . "/" .$this->request->get['_route_'];
                $this->request->get['_route_'] = str_replace($domain, "", $temp);
            } else {
                $temp = 'http://' . $old_host . "/" . $this->request->get['_route_'];
            }

            $this->request->get['_route_'] = str_replace($domain, "", $temp);
            if($this->request->get['_route_'] == ""){
                $this->request->get['_route_'] = "/";
            }
            if($this->request->get['_route_'] == "index.php"){
                $this->request->get['_route_'] = "";
            }
        }



        // Decode URL
        if (isset($this->request->get['_route_']) && $this->request->get['_route_'] ) {

            //产品页url不全
            //产品页URL以/P****形式的url均能301到正确地址，P id作为唯一识别产品地址的参数
            if (preg_match('/p(\d+)(.*)/', $this->request->get['_route_'], $data)) {

                $url_pid = $data[1];
                $url_pid = intval($url_pid);
                $this->load->model('catalog/product');
                $url_product = $this->model_catalog_product->getProductUrl($url_pid);
                $url_param = '';
                if (isset($_GET) && count($_GET) > 0) {
                    foreach ($_GET as $key => $val) {
                        if ($key != '_route_') {
                            $url_param .= $key . '=' . $val . "&";
                        }
                    }
                }
                $url_param = substr($url_param, 0, -1);
                if ($url_product) {
                    if ($url_product != $this->request->get['_route_']) {
                        if ($url_param) {
                            $url_product = $url_product . '?' . $url_param;
                        }
                        //$this->redirect($url_product,301);
                    }
                }
                $this->request->get['route'] = 'product/product';
                $this->request->get['product_id'] = $url_pid;

            } else if ($this->request->get['_route_'] == "/") {
                $this->request->get['route'] = 'common/home';

            } else {
                $parts = explode('/', $this->request->get['_route_']);

              
                $last = $parts[count($parts) - 1];

                //分类页面分页
                $cat_page = 0;

                if (preg_match('/^(\d+)\.html/', $last)) {
                    $url = $this->request->get['_route_'];
                    $len = strlen($last);
                    $url = substr($url, 0, strlen($url) - $len - 1);
                    $url = $url . '.html';

                    $cat_page = str_replace('.html', '', $last);
                    $cat_page = intval($cat_page);
                } else {
                    $url = $this->request->get['_route_'];
                }
                if (substr($url, 1, 1) == '/') {
                    $url = substr($url, 1);
                }

                //搜索

                $this->load->model('catalog/category');

                $category = $this->model_catalog_category->getCategoryByUrl($url);

                if ($category) {
                    $path = $category['path'];
                    $path = substr($path, 2);

                    $path = str_replace('/', '_', $path);
                    //echo $path;
                    $this->request->get['path'] = $path;
                    $this->request->get['route'] = 'product/category';
                    if ($cat_page) {
                        $this->request->get['page'] = $cat_page;
                    }
                } else {
                    $is_cat = 0;
                    foreach ($parts as $part) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

                        if ($query->num_rows) {
                            $url = explode('=', $query->row['query']);

                            if ($url[0] == 'product_id') {
                                $this->request->get['product_id'] = $url[1];
                            }

                            if ($url[0] == 'category_id') {
                                if (!isset($this->request->get['path'])) {
                                    $this->request->get['path'] = $url[1];
                                } else {
                                    $this->request->get['path'] .= '_' . $url[1];
                                }
                                $is_cat = 1;
                            }

                            if ($url[0] == 'manufacturer_id') {
                                $this->request->get['manufacturer_id'] = $url[1];
                            }

                            if ($url[0] == 'information_id') {
                                $this->request->get['information_id'] = $url[1];
                            }
                            if ($url[0] == 'route') {
                                $this->request->get['route'] = $url[1];
                            }
                        } else {

                            $this->request->get['route'] = 'error/not_found';

                        }
                    }
                }



            }
        }


        if (isset($this->request->get['product_id'])) {
            $this->request->get['route'] = 'product/product';
        } elseif (isset($this->request->get['path'])) {
            $this->request->get['route'] = 'product/category';

        } elseif (isset($this->request->get['manufacturer_id'])) {
            $this->request->get['route'] = 'product/manufacturer/info';
        } elseif (isset($this->request->get['information_id'])) {
            $this->request->get['route'] = 'information/information';
        }

        if (isset($this->request->get['route'])) {
            return $this->forward($this->request->get['route']);
        }
    }

    public function rewrite($link) {

        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_info['query'], $data);
        if (empty($data)) {
            $url .= '/';
        }

        foreach ($data as $key => $value) {

            if (isset($data['route'])) {
                //产品url重写规则
                if ($data['route'] == 'product/product' && $key == 'product_id') {
                    $this->load->model('catalog/product');
                    $product_url = $this->model_catalog_product->getProductUrl($value);
                    if ($product_url !== false) {
                        $url .= '/' . $product_url;
                        unset($data[$key]);
                    }
                }
                //首页
                if ($data['route'] == 'common/home' || $data['route'] == '') {
                    $url .= '/';
                    //unset($data[$key]);
                }
                //分类页
                if ($data['route'] == 'product/category' && $key == 'path') {
                    $categories = explode('_', $value);
                    $lenght = count($categories) - 1;
                    $category_id = $categories[$lenght];
                    $this->load->model('catalog/category');
                    $is_rewrite = 0;
                    $cat_url = $this->model_catalog_category->getCategoryUrl($category_id);
                    if ($cat_url) {
                        $url .= '/' . $cat_url;
                        unset($data[$key]);
                        $is_rewrite = 1;
                    }

                    if ($is_rewrite) {
                        if (isset($data['page'])) {
                            if (substr($url, -1, 5) == '.html') {
                                $url = substr($url, 0, -5);
                            }
                            if ($data['page'] == '{page}') {
                                $url .= '/' . $data['page'] . '.html';
                            } else {
                                $page = intval($data['page']);
                                if ($page > 1) {
                                    $url .= '/' . $data['page'] . '.html';
                                } else {
                                    $url .= '.html';
                                }
                            }
                            unset($data['page']);
                        }
                    }
                    unset($data[$key]);
                }


                //文章
                if ((($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");

                    if ($query->num_rows) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
                }
                
                //搜索
                if ($data['route'] == 'product/search' ) {
                    $search = $data['search'];
                    
                    //$search = preg_replace('/[^\d\w]/', '-', $search);
                    //$search = preg_replace('/(\-+)/','-',$search);
                    $is_rewrite = 0;
                    $search_url = $search;
                    if ($search_url) {
                        //if(substr($search_url,-1,1) == '-'){
                        //    $search_url = substr($search_url,0,-1);
                       // }
                        //$search_url = strtolower($search_url);
                        //$search_url = urlencode($search_url);
                        //$url .= '/s/' . $search_url .'.html';
                        
                        $search_keyword = '';
                        $search_url = preg_replace('/[\`\~\!\@#\$%\^&*()_+=|\\\{\}\[\];:\"\'<,>\.?\/]/',' ',$search_url);
                        $search_keyword_arr = explode(' ',$search_url);
                        foreach($search_keyword_arr as $item){
                            if($item!='' &&$item!=' '){
                                $search_keyword .= urlencode($item).'+';
                            }
                        }
                        $search_keyword = substr($search_keyword,0,-1);
                         
                        $data['search'] = $search_keyword;
                        unset($data['search']);
                        $url .= '/s/' . $search_keyword .'.html';
                        $is_rewrite = 1;
                    }
                    if ($is_rewrite) {
                        if (isset($data['page'])) {
                            if (substr($url, -1, 5) != '.html') {
                                $url = substr($url, 0, -5);
                            }
                            if ($data['page'] == '{page}') {
                                $url .= '/' . $data['page'] . '.html';
                            } else {
                                $page = intval($data['page']);
                                if ($page > 1) {
                                    $url .= '/' . $data['page'] . '.html';
                                } else {
                                    $url .= '.html';
                                }
                            }
                            unset($data['page']);
                        }
                    }
                    unset($data[$key]);
                }
                
                //tags
                if ($data['route'] == 'product/popular' ) {
                    $url .= '/hot.html';
                    unset($data[$key]);
                }
                 if ($data['route'] == 'product/popular/tag' ) {
                    if(isset($data['tag'])){
                        $tags = strtolower($data['tag']);
                        $url .= '/hot/'.$tags.'.html';
                        if (isset($data['page'])) {
                            if (substr($url, -1, 5) != '.html') {
                                $url = substr($url, 0, -5);
                            }
                            if ($data['page'] == '{page}') {
                                $url .= '/' . $data['page'] . '.html';
                            } else {
                                $page = intval($data['page']);
                                if ($page > 1) {
                                    $url .= '/' . $data['page'] . '.html';
                                } else {
                                    $url .= '.html';
                                }
                            }
                            unset($data['page']);
                        }
                        unset($data['tag']);
                    }
                    
                    unset($data[$key]);
                }

                //产品评论
                if ($data['route'] == 'product/reviews' ) {
                    $url .= '/reviews.html';
                    unset($data[$key]);
                }
                if ($data['route'] == 'product/reviews/product' ) {
                    if(isset($data['sku'])){
                        $url .= '/reviews/'.$data['sku'].".html";
                        unset($data['sku']);
                    }
                    
                    unset($data[$key]);
                }
                if ($data['route'] == 'product/reviews/info' ) {
                    if(isset($data['sku'])&&isset($data['review_id'])){
                        $url .= '/reviews/'.$data['sku'].'/'.(int)$data['review_id'].".html";
                        unset($data['sku']);
                        unset($data['review_id']);
                    }
                    
                    unset($data[$key]);
                }
                
            }
        }
        if ($url) {
           
            unset($data['route']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . $key . '=' . $value;
                }

                if ($query) {
                    $query = '?' . trim($query, '&');
                }
            }
       
            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        } else {
            return $link;
        }
    }

}

?>