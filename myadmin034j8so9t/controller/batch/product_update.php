<?php 
class ControllerBatchProductUpdate extends Controller {
	private $error = array();
    private $path = DIR_DATA;
    //private $path = 'E:/www/code/branches/charles0601/'; 
	public function index() {
		
		$this->document->setTitle($this->language->get('商品批量修改')); 
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "商品批量修改",
			'href'      => $this->url->link('batch/product_update', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['download'] = $this->url->link('batch/product_update/download', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upload'] = $this->url->link('batch/product_update/upload', 'token=' . $this->session->data['token'], 'SSL');	
        $this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	    $this->template = 'batch/product_update.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function upload(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $this->document->setTitle($this->language->get('商品批量修改')); 
        $accpet_file = array('xls','xlsx');
		$file_name = $_FILES['uplaod_file']['name'];
		$file_type = substr($file_name,strrpos($file_name,'.')+1);
		if(!in_array($file_type,$accpet_file )){
			$this->error['warning'] = "暂时只支持excel格式文件，请重新上传!";
            $this->redirect($this->url->link('batch/product_update', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{

            
            require_once($this->path."system/lib/PHPExcel/PHPExcel.php");
            require_once ($this->path."system/lib/PHPExcel/PHPExcel/Writer/Excel5.php");     // 用于其他低版本xls
            require_once ($this->path."system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php"); // 用于 excel-2007 格式 
			$file_path =$this->path."upload/product/";
            
            
            /*
            require_once($this->path."system/lib/PHPExcel/PHPExcel.php");
            require_once ($this->path.'system/lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
            require_once ($this->path.'system/lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
            $file_path =$this->path."upload/product/";
            */
			$file_name = date('Y-m-d_H_i_s',time())."_product_update.".$file_type;
			if($_FILES['uplaod_file']['tmp_name']){
				if(!is_dir($file_path)){
					mkdir($file_path,0777,1);
				}
				if(!move_uploaded_file($_FILES["uplaod_file"]["tmp_name"],$file_path.$file_name)){
					echo "上传失败，请重新上传!";
				}
				else{
                    
		            $file_content =$this->getexcelcontent($file_path.$file_name);
                    $update_fileds_arr =array_flip($file_content[1]);

                    if(isset($update_fileds_arr['gallery'])||isset($update_fileds_arr['image'])){
                        $this->do_product_photo($file_path);
                    }
                    $i=1;

                    foreach($file_content as $key=>$info){
                        if($key>1){
                            header("Content-type: text/html; charset=utf-8");
                            $query_product_exist =$this->db->query("SELECT product_id FROM ".DB_PREFIX."product where model ='".$info[0]."'");
                            if($query_product_exist->num_rows){
                                $product_id =$query_product_exist->row['product_id'];
                                
                                 if(isset($update_fileds_arr['attribute_set'])){
                                    $int_key_0 =$update_fileds_arr['attribute_set'];
                                     //商品属性
                                    $attribute_group_query =$this->db->query("SELECT attribute_group_id FROM ".DB_PREFIX."attribute_group where attribute_group_code='".trim($info[$int_key_0])."' ");
                                    if($attribute_group_query->row['attribute_group_id']){
                                        $attribute_group_id = $attribute_group_query->row['attribute_group_id'];
                                        //更新product_attribute_group
                                        $this->db->query("UPDATE ".DB_PREFIX."product_attribute_group set attribute_group_id='".$attribute_group_id."' where product_id=".$product_id);

                                    }
                                    else{
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行查找属性组失败</p>";
                                     }
                                }
                                //商品分类
                                if(isset($update_fileds_arr['category_ids'])){
                                    $int_key_1 =$update_fileds_arr['category_ids'];
                                    $category_ids =explode(',',$info[$int_key_1]);
                                    array_pop($category_ids);
                                    $this->db->query("DELETE FROM ".DB_PREFIX."product_to_category  where product_id=".$product_id);
                                    if($category_ids){
                                        foreach($category_ids as $category_id){
                                            $res_product_to_category=$this->db->query("INSERT INTO ".DB_PREFIX."product_to_category set product_id='".$product_id."', category_id='".$category_id."',position=1");
                                             if(!$res_product_to_category){
                                                 $this->error[$i] =$i;
                                                 echo "<p style='color:red'>第".$i."行更新product_to_category表失败，category_id='".$category_id."'</p>";
                                            }
                                        }
                                    }else{
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product_to_category表失败，category_id='".$info[$int_key_1]."'</p>";
                                    }
                                }
                                //根据erp需求，当修改以下字段内容是，更新修改日期，作为同步依据
                                if(isset($update_fileds_arr['product_code'])||isset($update_fileds_arr['supplier_code'])||isset($update_fileds_arr['name'])||isset($update_fileds_arr['image'])||isset($update_fileds_arr['price'])||isset($update_fileds_arr['weight'])||isset($update_fileds_arr['package_length'])||isset($update_fileds_arr['package_width'])||isset($update_fileds_arr['package_height'])){
                                    $this->db->query("UPDATE ".DB_PREFIX."product set  date_modified=NOW() where product_id=".$product_id);
                                }
                                //product_code
                                if(isset($update_fileds_arr['product_code'])){
                                    $product_code =str_replace("\r\n","",trim($info[$update_fileds_arr['product_code']]));
                                    $query_product_code_exist =$this->db->query("SELECT product_id FROM ".DB_PREFIX."product where  product_code='".$product_code."' ");
                                     if($query_product_code_exist->num_rows){
                                         $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行product code 已存在</p>";
                                    }
                                    else{
                                        $query_product_code_update =$this->db->query("UPDATE ".DB_PREFIX."product set  product_code='".$product_code."' where product_id=".$product_id);
                                        if(!$query_product_code_update){
                                            $this->error[$i] =$i;
                                            echo "<p style='color:red'>第".$i."行更新product code 失败</p>";
                                        }
                                    }
                                }
                                //package_length
                                if(isset($update_fileds_arr['package_length'])){
                                    $package_length =$info[$update_fileds_arr['package_length']];                                   
                                    $query_product_length_update =$this->db->query("UPDATE ".DB_PREFIX."product set  length='".$package_length."' where product_id=".$product_id);
                                    if(!$query_product_length_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product length 失败</p>";
                                    }
                                }
                                //package_width
                                if(isset($update_fileds_arr['package_width'])){
                                    $package_width =$info[$update_fileds_arr['package_width']];                                   
                                    $query_product_width_update =$this->db->query("UPDATE ".DB_PREFIX."product set  width='".$package_width."' where product_id=".$product_id);
                                    if(!$query_product_width_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product width 失败</p>";
                                    }
                                }
                                //package_height
                                if(isset($update_fileds_arr['package_height'])){
                                    $package_height =$info[$update_fileds_arr['package_height']];                                   
                                    $query_product_height_update =$this->db->query("UPDATE ".DB_PREFIX."product set  height='".$package_height."' where product_id=".$product_id);
                                    if(!$query_product_height_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product height 失败</p>";
                                    }
                                }
                                //supplier_code
                                if(isset($update_fileds_arr['supplier_code'])){
                                    $supplier_code = str_replace("\r\n","",trim($info[$update_fileds_arr['supplier_code']]));                                 
                                    $query_supplier_code_update =$this->db->query("UPDATE ".DB_PREFIX."product set  supplier_code='".$supplier_code."' where product_id=".$product_id);
                                    if(!$query_supplier_code_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product supplier_code 失败</p>";
                                    }
                                }
                                //name
                                if(isset($update_fileds_arr['name'])){
                                    $name =$info[$update_fileds_arr['name']];
                                    //$url_path =str_replace(' ','-',strtolower($name));
                                    //$url_path =str_replace('.','-',$url_path);
                                    //$url_path =str_replace('(','-',$url_path);
                                    //$url_path =str_replace(')','',$url_path);
                                    
                                    $url_path = preg_replace('/[^\d\w\-]/','-',strtolower($name));
                                    $url_path = preg_replace('/(\-+)/','-',$url_path);
                                    $last_url_path = substr($url_path,-1,1);
                                    if($last_url_path == '-'){
                                        $url_path = substr($url_path,0,-1);
                                    }
                                    
                                    $url_path ="p".$product_id."-".$url_path;
                                    
                                    
                                    $this->db->query("UPDATE ".DB_PREFIX."product set url_path='".$url_path."' where product_id=".$product_id);
                                    $query_name_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  name='".$this->db->escape(trim($name))."',title='".$this->db->escape(trim($name))."' where product_id=".$product_id);
                                    if(!$query_name_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product name失败</p>";
                                    }
                                }
                                //meta_description
                                if(isset($update_fileds_arr['meta_description'])){
                                    $meta_description =$info[$update_fileds_arr['meta_description']];                                   
                                    $query_meta_description_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  meta_description='".$this->db->escape(trim($meta_description))."' where product_id=".$product_id);
                                    if(!$query_meta_description_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product meta_description失败</p>";
                                    }
                                }
                                //description
                                if(isset($update_fileds_arr['description'])){
                                    $description =$info[$update_fileds_arr['description']]; 
                                    $description =str_replace(array("\r\n", "\r", "\n"), "<br>", $description);
                                    $query_description_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  description='".$this->db->escape(trim($description))."' where product_id=".$product_id);
                                    if(!$query_description_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product meta_description失败</p>";
                                    }
                                }
                                //meta_keyword
                                if(isset($update_fileds_arr['meta_keyword'])){
                                    $meta_keyword =$info[$update_fileds_arr['meta_keyword']]; 
                                    $query_meta_keyword_update =$this->db->query("UPDATE ".DB_PREFIX."product_description set  meta_keyword='".$this->db->escape(trim($meta_keyword))."' where product_id=".$product_id);
                                    if(!$query_meta_keyword_update){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行更新product meta_keyword失败</p>";
                                    }
                                }
                                //gallery
                                if(isset($update_fileds_arr['gallery'])){
                                    $gallery =$info[$update_fileds_arr['gallery']]; 
                                    $pro_image =explode(';',$gallery);
                                    //得到图片ID
                                    $this->db->query("delete from ".DB_PREFIX."product_image where product_id=".$product_id);
                                    foreach($pro_image as $pro_gallery){
                                        $gallery_path =$this->getPorImagePath(substr($pro_gallery,1));
                                        $sort_order=intval(substr($pro_gallery,-6,2));
                                        $res_product_image=$this->db->query("INSERT INTO ".DB_PREFIX."product_image set product_id='".$product_id."',image='".$gallery_path."',sort_order=".$sort_order);
                                         if(!$res_product_image){
                                            $this->error[$i] =$i;
                                            echo "<p style='color:red'>第".$i."行插入product_image表失败，image='".$gallery_path."'</p>";
                                        }
                                    }
                                }
                                //image
                                if(isset($update_fileds_arr['image'])){
                                    $image =$info[$update_fileds_arr['image']]; 
                                    $image_path =$this->getPorImagePath(substr($image,1));
                                    $update_product_image=$this->db->query("UPDATE ".DB_PREFIX."product set image='".$image_path."' where product_id=".$product_id);
                                    if(!$update_product_image){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product image失败，image='".$image."'</p>";
                                    }
                                }
                                //price
                                if(isset($update_fileds_arr['price'])){
                                    $price =$info[$update_fileds_arr['price']]; 
                                    $update_product_price=$this->db->query("UPDATE ".DB_PREFIX."product set price='".$price."',points='".floor($price)."'  where product_id=".$product_id);
                                    if(!$update_product_price){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product price失败，price='".$price."'</p>";
                                    }
                                    $this->db->query("update ".DB_PREFIX."product_discount set price='".($price*0.97)."' where product_id='".$product_id."' and quantity=2");
                                    $this->db->query("update ".DB_PREFIX."product_discount set price='".($price*0.93)."' where product_id='".$product_id."' and quantity=10");
                                    $this->db->query("update ".DB_PREFIX."product_discount set price='".($price*0.88)."' where product_id='".$product_id."' and quantity=50");
                                }
                                //special_price
                                if(isset($update_fileds_arr['special_price'])){
                                    $special_price =$info[$update_fileds_arr['special_price']]; 
                                    $update_product_special_price=$this->db->query("UPDATE ".DB_PREFIX."product_special set price='".$special_price."' where product_id=".$product_id);
                                    if(!$update_product_special_price){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product special price失败，price='".$special_price."'</p>";
                                    }
                                }
                                //special_from_date
                                if(isset($update_fileds_arr['special_from_date'])){
                                    $special_from_date =$info[$update_fileds_arr['special_from_date']]; 
                                    $update_product_special_from_date=$this->db->query("UPDATE ".DB_PREFIX."product_special set date_start='".$special_from_date."' where product_id=".$product_id);
                                    if(!$update_product_special_from_date){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product special_from_date失败，special_from_date='".$special_from_date."'</p>";
                                    }
                                }
                                //special_to_date
                                if(isset($update_fileds_arr['special_to_date'])){
                                    $special_to_date =$info[$update_fileds_arr['special_to_date']]; 
                                    $update_product_special_to_date=$this->db->query("UPDATE ".DB_PREFIX."product_special set date_end='".$special_to_date."' where product_id=".$product_id);
                                    if(!$update_product_special_to_date){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product special_to_date失败，special_to_date='".$special_to_date."'</p>";
                                    }
                                }
                                //special_to_date
                                if(isset($update_fileds_arr['special_to_date'])){
                                    $special_to_date =$info[$update_fileds_arr['special_to_date']]; 
                                    $update_product_special_to_date=$this->db->query("UPDATE ".DB_PREFIX."product_special set date_end='".$special_to_date."' where product_id=".$product_id);
                                    if(!$update_product_special_to_date){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product special_to_date失败，special_to_date='".$special_to_date."'</p>";
                                    }
                                }
                                //weight
                                if(isset($update_fileds_arr['weight'])){
                                    $weight =$info[$update_fileds_arr['weight']]; 
                                    $update_product_weight=$this->db->query("UPDATE ".DB_PREFIX."product set weight='".$weight."' where product_id=".$product_id);
                                    if(!$update_product_weight){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product weight失败，weight='".$weight."'</p>";
                                    }
                                }

                                //qty
                                if(isset($update_fileds_arr['qty'])){
                                    $qty =$info[$update_fileds_arr['qty']]; 
                                    $update_product_quantity=$this->db->query("UPDATE ".DB_PREFIX."product set quantity='".$qty."' where product_id=".$product_id);
                                    if(!$update_product_quantity){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product quantity失败，quantity='".$qty."'</p>";
                                    }
                                }
                                //is_in_stock
                                if(isset($update_fileds_arr['is_in_stock'])){
                                    $is_in_stock =$info[$update_fileds_arr['is_in_stock']]; 
                                    if($is_in_stock==1){
                                        $stock_status_id =7;
                                    }
                                    else{
                                        $stock_status_id =5;
                                    }
                                    $update_product_stock_status=$this->db->query("UPDATE ".DB_PREFIX."product set stock_status_id='".$stock_status_id."' where product_id=".$product_id);
                                    if(!$update_product_stock_status){
                                        $this->error[$i] =$i;
                                        echo "<p style='color:red'>第".$i."行修改product stock_status失败，stock_status='".$is_in_stock."'</p>";
                                    }
                                }
                                
                                //store                      
                                if(isset($update_fileds_arr['store'])){
                                    $_store      = $info[$update_fileds_arr['store']];
                                    
                                    //en:0;de:52;fr:54;es:53;it:55;pt:56
                                    $_store_ref_arr = array(
                                        'en' => 0,
                                        'de' => 52,
                                        'fr' => 54,
                                        'es' => 53,
                                        'it' => 55,
                                        'pt' => 56,
                                    );
                                    
                                    
                                    $store_query = $this->db->query("SELECT store_id FROM ".DB_PREFIX."store ");
                                    
                                    $_product_store_query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_to_store where product_id='{$product_id}'");
                                    $_product_store_rows  = $_product_store_query->rows;
                                    $_exist_product_store = array();
                                    foreach($_product_store_rows as $_row){
                                        $_exist_product_store[] = $_row['store_id'];
                                    }
                                    $_store = trim($_store);
                                    $_store = strtolower($_store);
                                    if($_store == ''){
                                        foreach($store_query->rows as $store_res){
                                          if(!in_array($store_res['store_id'],$_exist_product_store)){
                                              $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=".$store_res['store_id'].",sales_num=0"); 
                                          }
                                        }
                                        if(!in_array(0,$_exist_product_store)){
                                            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=0,sales_num=0"); 
                                        }
                                    }else{
                                        $_tmp_store_arr = explode(',',$_store);
                                        $_store_arr = array();
                                        foreach($_tmp_store_arr as $_item){
                                            if(isset($_store_ref_arr[$_item])){
                                                $_store_arr[] = $_store_ref_arr[$_item];
                                            }
                                        }
                                        
                                        
                                        foreach($store_query->rows as $store_res){
                                            if(in_array($store_res['store_id'],$_store_arr)){
                                                 if(in_array($store_res['store_id'],$_exist_product_store)){
                                                    
                                                 }else{
                                                     $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=".$store_res['store_id'].",sales_num=0"); 
                                                 }
                                            }
                                        }
                                        if(in_array(0,$_store_arr) && !in_array(0,$_exist_product_store)){
                                            $this->db->query("INSERT INTO ".DB_PREFIX."product_to_store  set product_id='".$product_id."',store_id=0,sales_num=0"); 
                                        }
                                        //去掉已经存在，但是要取消的store
                                        $_diff = array_diff($_exist_product_store,$_store_arr);
                                        if(!empty($_diff)){
                                            foreach($_diff as $_item){
                                                $_item = intval($_item);
                                                $this->db->query("DELETE FROM  ".DB_PREFIX."product_to_store  WHERE product_id='".$product_id."' AND store_id='".$_item."'"); 
                                            }
                                        }
                                    }
                                    
                                }
                                
                                
                                if(isset($update_fileds_arr['battery_type'])){
                                    $battery_type = 0;
                                    $_battery_type = $info[$update_fileds_arr['battery_type']];
                                    $_battery_type = trim($_battery_type);
                                    if($_battery_type == ''){
                                       $battery_type  = 0;
                                    }else{
                                        if(in_array($_battery_type,array(0,1,2,3,4))){
                                            $battery_type = $_battery_type;
                                        }else{
                                            
                                        }
                                    }
                                    if($battery_type === ''){
                                        echo "<p style='color:red'>第".$i."行修改product battery_type，battery_type='".$update_fileds_arr['battery_type']."'</p>";
                                    }else{
                                        $update_product_battery_type = $this->db->query("UPDATE ".DB_PREFIX."product set battery_type='".$battery_type."' where product_id=".$product_id);
                                        //echo "UPDATE ".DB_PREFIX."product set battery_type='".$battery_type."' where product_id=".$product_id;
                                        if(!$update_product_battery_type){
                                            $this->error[$i] =$i;
                                            echo "<p style='color:red'>第".$i."行修改product battery_type，battery_type='".$battery_type."'</p>";
                                        }
                                    }
                                }
                            }
                            else{
                                $this->error[$i] =$i;
                                 echo "<p style='color:red'>第".$i."行SKU不存在</p>";
                            }
                           

                        }
                        
                     $i++;  
                    }
                    $error_count =count($this->error);
                     if($error_count>0){
                        $back_url =  $this->url->link('batch/product_update', 'token=' . $this->session->data['token'], 'SSL');  
                        echo "<p>共上传".$i."条数据，共有".$error_count." 条数据失败.<br><a href='".$back_url ."' >返回</a></p>";
                    }
                    else{
                         $this->session->data['success'] ="共".$i."条数据上传成功";
                         $this->redirect($this->url->link('batch/product_update', 'token=' . $this->session->data['token'], 'SSL'));  
                    }
				}
			}
		}
    }
    public function getexcelcontent($file){			
        $objReader = new PHPExcel_Reader_Excel2007(); 
        if(!$objReader->canRead($file)){
            $objReader = new PHPExcel_Reader_Excel5(); 
        }
        $objPHPExcel = $objReader->load($file);
        $objWorksheet = $objPHPExcel->getActiveSheet();  
        
        $highestRow = $objWorksheet->getHighestRow();   
        $highestColumn = $objWorksheet->getHighestColumn();   
         
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  
         
        $excelData = array();  
        $time_file =array(); 
        for ($row = 1; $row <= $highestRow; ++$row) { 
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $content =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $content = trim($content);
                if($col==0&&!$content){
                    break;
                }
               
                if($content==='special_from_date'||$content==='special_to_date'){
                       $time_file[] = $col;
                }
                if($row>1&&in_array($col,$time_file)){
                       $content=PHPExcel_Shared_Date::ExcelToPHP($content);
                       $content =gmdate("Y-m-d H:i:s", $content);
                 }
                  //富文本转换字符串  
                 if($content instanceof PHPExcel_RichText){    
                    $content = $content->__toString();  
                 }
                $excelData[$row][] = $content;
                
            }  
        }

        return $excelData;  
    }
    
    public function getPorImagePath($image){
        $image_array =explode('_',$image);
        $image_sku =$image_array[0];
        $file_path_1 =substr($image_sku,0,1);
        $file_path_2 =substr($image_sku,1,1);
        $image_path ="product/".$file_path_1."/".$file_path_2."/".$image;
        return $image_path;
    }

    public function do_product_photo($file_path){
        $dir_path =$file_path.date("Ymd",time())."-product-update-photo/";
        $file_arr =scandir($dir_path);
        foreach($file_arr as $file_name){
            if($file_name!='.'&&$file_name!='..'){
                $upload_path =$this->getPorImagePath($file_name);
                $upload_path_dir =substr($upload_path,0,strrpos($upload_path,'/')+1);
                if(!is_dir(DIR_IMAGE.$upload_path_dir)){
                    mkdir(DIR_IMAGE.$upload_path_dir,0777,1);
                }
                copy($dir_path.$file_name,DIR_IMAGE.$upload_path);
            }
        }
        
    }
}
?>