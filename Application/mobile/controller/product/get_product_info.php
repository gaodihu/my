<?php  
class ControllerProductGetProductInfo extends Controller {
    public function index(){
       
        $sku =isset($this->request->get['sku'])?trim($this->request->get['sku']):'';
        if($sku){
            $str ="<html>
            <style type='text/css'> 
        body{width:980px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px;}
        a{text-decoration:none; margin:0; padding:0;}
        a:hover{text-decoration:underline;} 
        div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,input,th,td,span,p{margin:0; padding:0;}
        img{border:0;}
        dl,ul,ol{list-style:none;}
        li{list-style-type:none;}
        .clear{clear:both; height:0px;}
        .products{}
        .products ul{display:block;}
        .products ul li{width:230px; height:340px; background-color:#FFF; float:left; margin-left:10px; margin-top:10px; border:1px dashed #fba81c;}
        .products .first{margin-left:0px;}
        .products li img{padding:3px 8px;}
        .products li .name{color:#565656; height:36px; line-height:18px; text-align:center; overflow:hidden; display:block; padding:0 8px;}
        .products li .price{font-size:24px; color:#9c0000; text-align:center; height:24px; line-height:24px; margin:8px auto;}
        .products li .buy{width:119px; height:36px; margin:5px auto; background:url(images/get.jpg) no-repeat; display:block; text-align:center; color:#FFF; font-size:14px; line-height:30px; text-decoration:none;}
        </style>
            <body>
            <div class='products'>
                <ul>";
            $this->load->model('catalog/product');
            $this->load->model('tool/image');
            $sku_array =explode(',',$sku);
            if ($this->customer->isLogged()) {
                $customer_group_id = $this->customer->getCustomerGroupId();
            } else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }	
            foreach($sku_array as $model){
                $sql_product_id ="select product_id from ".DB_PREFIX."product where model='".$model."'";
                $query =$this->db->query($sql_product_id);
                $product=$query->row;
                $query2 = $this->db->query("SELECT p.product_id,p.url_path,p.price, pd.name AS name, p.image, ps.price as special_price,ps.date_start as special_from,ps.date_end as special_to
                FROM " . DB_PREFIX . "product p 
                LEFT JOIN " . DB_PREFIX . "product_special ps ON (ps.product_id = p.product_id) and ps.customer_group_id=0 
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
                WHERE p.product_id = '" . (int)$product['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
                $product_info =$query2->row;
                if($product_info['image']){
                    $image =$this->model_tool_image->resize($product_info['image'],453,453);
                }
                else{
                    $image =false;
                }
                if($product_info['special_price']){
                    $special_price =$this->currency->format($product_info['special_price'],'USD');
                }
                else{
                    $special_price =false;
                }
                $product_info['price'] =$this->currency->format($product_info['price'],'USD');
                $product_info['url_path'] =$product_info['url_path'].".html";
                $str .="
                   <li> 
                   <a href='".$product_info['url_path']."'><img src='".$image."'  width='230' height='220'></a> 
				   <a class='name' href='".$product_info['url_path']."'>".$product_info['name']."</a>
				  <p class='price'>".$special_price."_____".$product_info['price']."</p>
				   <p>special from:".$product_info['special_from'].";special to:".
                                    $product_info['special_to']."</p></li>";
                
            }
            $str .= " </ul></div></body></html>";
            echo $str;
        }
        else{
            echo "请输入sku";
        }
    }
     
}




?>
