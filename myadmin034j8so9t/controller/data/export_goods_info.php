<?php 
class ControllerDataExportGoodsInfo extends Controller {
	private $error = array();
    //private $file_path = "E:/www/code/branches/charles0728/script/product_attr/";
    private $file_path =  DIR_DATA ."/product_attr/";
    
	public function index() {
		
		$this->document->setTitle("导出商品信息");
        $this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => "导出商品信息",
			'href'      => $this->url->link('data/export_goods_info', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);


        $this->data['token'] = $this->session->data['token'];

        $this->data['export_goods_info'] = $this->url->link('data/export_goods_info/exportGoods', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['export_attr_info'] = $this->url->link('data/export_goods_info/exportAttr', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['export_guiji_info'] = $this->url->link('data/export_goods_info/exportGuiji', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['export_desc_info'] = $this->url->link('data/export_goods_info/exportDesc', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	    $this->template = 'data/export_goods_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    public function exportGoods(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $file =$this->file_path.'goods_info.csv';
        $fp =fopen($file,"a");
        $title =array('category_name','catagory_2_name','product_id','sku','name','battery_type','description','price','weight','length','width','height','product_code','supplier_code','image','keywords','stock_status_id','quantity','date_added','url','gallery','store');
        fputcsv($fp,$title);
        $sql ="select count(*) as total from oc_product";
        $query =$this->db->query($sql);
        $count =$query->row['total'];
        $ceil =1000;
        $time =ceil($count/$ceil);
        for($i=1;$i<=$time;$i++){
            $start =($i-1)*$ceil;
            $limit =$ceil;
            $pro_info =$this->get_pro_info($start,$limit);
            foreach($pro_info as $arr){
                $arr['description'] =str_replace("\r\n","",htmlspecialchars_decode($arr['description']));
                $category_name =$this->get_pro_category($arr['product_id']);
                $catagory_2_name =$this->get_pro_level_2_catagory($arr['product_id']);
                $gallery =$this->get_pro_gallery($arr['product_id']);
                array_push($arr,$gallery);
                array_unshift($arr,$category_name,$catagory_2_name);
                $store_str =$this->get_pro_store($arr['product_id']);
                array_push($arr,$store_str);
                /*
                $_tmp_str = "";
                foreach($arr as $item){
                    $_tmp_str .= '"' . addcslashes($item) .'",';
                }
                $_tmp_str = substr($_tmp_str,0,-1);
                $_tmp_str  .= "\n";
                fwrite($fp, $_tmp_str);
                 * 
                 */
                fputcsv($fp,$arr,',','"');
            }
        }
        fclose($fp);
        header("Content-type:application/csv;charset=utf-8");
        header("content-Disposition:filename=goods_info.csv ");
        $str =file_get_contents($file);
        unlink($file);
        echo "\xEF\xBB\xBF";
        echo $str;
        exit;
    }
    public function exportAttr(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        $file =$this->file_path.'goods_attr.csv';
        $fp =fopen($file,"a");
        $attr_array = $this->get_attr_info();
        $title =array();
        $attr_2_key =array();
        foreach($attr_array as $key=>$att_value){
            $title[] =$att_value['name'];
            if($att_value['attribute_id']){
                $attr_2_key[$att_value['attribute_id']] =$key;
            }
        }
        fputcsv($fp,$title);
        $pro_info =$this->get_product_attr();
        foreach($pro_info as $product_id=>$row_sku){
            $input_csv =array();
            foreach($attr_array as $att_value){
                $input_csv[] ='';
            }
            $sku =$this->get_product_sku($product_id);
            $catagory_2_name =$this->get_pro_level_2_catagory($product_id);
            $stock_status =$this->get_product_stock($product_id);
            $input_csv[0] =$sku;
            $input_csv[1] =$stock_status;
            $input_csv[2] =$row_sku['attribute_group_code'];
            $input_csv[3] =$catagory_2_name;
            foreach($row_sku['attribute'] as $sku_attr){
                $input_csv[$attr_2_key[$sku_attr['attribute_id']]] =$sku_attr['option_value'];
            }
            /*
            $_tmp_str = "";
            foreach($input_csv as $item){
                $_tmp_str .= ',"' . addslashes($item) .'"';
            }
            $_tmp_str = substr($_tmp_str,1);
            $_tmp_str  .= "\n";
            fwrite($fp, $_tmp_str);
             * 
             */
            fputcsv($fp,$input_csv,",",'"');
        }
        
        fclose($fp);
        header("Content-type:application/csv;charset=utf-8");
        header("content-Disposition:filename=goods_attr.csv");
        
        $str =file_get_contents($file);
        unlink($file);
        echo "\xEF\xBB\xBF";
        echo $str;
        exit;
    }
    
    public function exportGuiji(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式 
        $data =$this->getGuijiData();
        $objPHPExcel = new PHPExcel();
        $objActSheet=$objPHPExcel->getActiveSheet();
        $objActSheet->setTitle("商品归集");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->setCellValue("A1", 'group_id');
        $objActSheet->setCellValue("B1", 'sku');
        $objActSheet->setCellValue("C1", 'supplier_code');
        $objActSheet->setCellValue("D1", '属性名');
        $objActSheet->setCellValue("E1", '属性值');
        $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','I','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH');
        $i=2;
        foreach($data as $model=>$item){
            foreach($item as $key=>$value){
                 $objActSheet->setCellValue($tmp_array[$key].$i, $value);
            }
           $i++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品归集.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    public function exportDesc(){
        ini_set('memory_limit','502M');
        set_time_limit(0);
        require_once(DIR_SYSTEM."lib/PHPExcel/PHPExcel.php");
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel/Writer/Excel5.php');     // 用于其他低版本xls
        require_once (DIR_SYSTEM.'lib/PHPExcel/PHPExcel//Writer/Excel2007.php'); // 用于 excel-2007 格式
        $data =$this->getDescData();
        $objPHPExcel = new PHPExcel();
        $objActSheet=$objPHPExcel->getActiveSheet();
        $objActSheet->setTitle("商品描述");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet->setCellValue("A1", 'sku');
        $objActSheet->setCellValue("B1", 'packaging_list');
        $objActSheet->setCellValue("C1", 'read_more');
        $objActSheet->setCellValue("D1", 'application_image');
        $objActSheet->setCellValue("E1", 'size_image');
        $objActSheet->setCellValue("F1", 'features');
        $objActSheet->setCellValue("G1", 'installation_method');
        $objActSheet->setCellValue("H1", 'video');
        $objActSheet->setCellValue("I1", 'notes');
        $i=2;
        foreach($data as $item){
            $objActSheet->setCellValue("A".$i, $item['model']);
            $objActSheet->setCellValue("B".$i, str_replace("\r\n","",htmlspecialchars_decode($item['packaging_list'])));
            $objActSheet->setCellValue("C".$i, str_replace("\r\n","",htmlspecialchars_decode($item['read_more'])));
            $objActSheet->setCellValue("D".$i, str_replace("\r\n","",htmlspecialchars_decode($item['application_image'])));
            $objActSheet->setCellValue("E".$i, str_replace("\r\n","",htmlspecialchars_decode($item['size_image'])));
            $objActSheet->setCellValue("F".$i, str_replace("\r\n","",htmlspecialchars_decode($item['features'])));
            $objActSheet->setCellValue("G".$i, str_replace("\r\n","",htmlspecialchars_decode($item['installation_method'])));
            $objActSheet->setCellValue("H".$i, str_replace("\r\n","",htmlspecialchars_decode($item['video'])));
            $objActSheet->setCellValue("I".$i, str_replace("\r\n","",htmlspecialchars_decode($item['notes'])));
            $i++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=商品描述.xlsx" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    /*
    **
    **  下面的函数是辅助函数
    **
    **
    */  
    //得到商品信息
    public function get_pro_info($start,$limit){
        $sql ="select p.product_id,p.model,pd.name,p.battery_type,pd.description,p.price,p.weight, p.length,p.width,p.height,
        p .product_code,p.supplier_code,p.image,
        pd.meta_keyword as keywords, p.stock_status_id,
        p.quantity,p.date_added,CONCAT('http://www.myled.com/',p.url_path) as url
        from oc_product as p
        left join oc_product_description as pd on p.product_id =pd.product_id and pd.language_id=1
        order by p.product_id desc limit ".$start.",".$limit."
        ";
        $query =$this->db->query($sql);
        return $query->rows;
    }

    //得到商品的所有语言站点
    public function get_pro_store($product_id){
        $store_array =array('0'=>'en','52'=>'de','53'=>'es','54'=>'fr','55'=>'it','56'=>'pt','57'=>'mobile');
        $sql ="select store_id from oc_product_to_store where product_id=".(int)$product_id;
        $query =$this->db->query($sql);
        $store_in_str =array();
        foreach($query->rows as $row){
            $store_in_str[] =$store_array[$row['store_id']];
        }
        return implode(',',$store_in_str);
    }
    //得到商品的分类
    public function get_pro_category($product_id){
        $query =$this->db->query("select category_id from oc_product_to_category where product_id=".$product_id." order by category_id asc limit 1");
        $row =$query->row;
        $category_id =$row['category_id'];
        if($category_id){
            $query_parent =$this->db->query("select parent_id from oc_category where category_id=".$category_id);
            $row_parent =$query_parent->row;
            if($row_parent['parent_id']){
                $category_id=$row_parent['parent_id'];
            }
            $query_name =$this->db->query("select name from oc_category_description where category_id=".$category_id." and language_id=1");
            $res =$query_name->row;
            if($res){
                return $res['name'];
            }else{
                return NUll;
            }
        }else{
            return NUll;
        }
    }
    //得到商品的附图
    public function get_pro_gallery($product_id){
        $query =$this->db->query("select image from oc_product_image where product_id=".$product_id." limit 1");
        $row =$query->row;
        if($row['image']){
        return $row['image'];

        }else{
            return null;
        }
    }
    
    //得到商品的属性
    public function get_product_attr(){
        $data =array();
        $query =$this->db->query("select npa.product_id,ag.attribute_group_code,npa.attribute_id,naov.option_value 
        from oc_new_product_attribute as npa 
        left join oc_product_attribute_group as pag on npa.product_id=pag.product_id
        left join oc_attribute_group as ag on pag.attribute_group_id=ag.attribute_group_id
        left join  oc_new_attribute_option_value as naov on npa.attr_option_value_id=naov.option_id and naov.language_id=1
        order by npa.product_id ASC ");
        foreach($query->rows as $attr_value){
            if(!isset($data[$attr_value['product_id']]['attribute_group_code'])){
                $data[$attr_value['product_id']]['attribute_group_code'] =$attr_value['attribute_group_code'];
            }
            $data[$attr_value['product_id']]['attribute'][]=array(
                'attribute_id'  =>$attr_value['attribute_id'],
                'option_value'  =>$attr_value['option_value']
            );
        }
        return $data;
    }
    //得到所有属性名
    public function get_attr_info(){
        $query =$this->db->query("select attribute_id,name from oc_new_attribute_description where language_id=1");
        $data =array();
        $data[] =array('name'=>'sku');
        $data[] =array('name'=>'stock_status');
        $data[] =array('name'=>'attrbute_set');
        $data[] =array('name'=>'catagory_level_2');
        foreach($query->rows as $value){
            $data[] =$value;
        }
        return $data;
    }
    public function get_product_sku($product_id){
        $query =$this->db->query("select model from oc_product where product_id='".$product_id."' limit 1");
        $row =$query->row;
        if($row){
            return $row['model'];
        }
        else{
            return false;
        }
    }
    public function get_product_stock($product_id){
        $query =$this->db->query("select stock_status_id from oc_product where product_id='".$product_id."' limit 1");
        $row =$query->row;
        if($row){
            return $row['stock_status_id'];
        }
        else{
            return false;
        }
    }

    public function get_pro_level_2_catagory($product_id){
        $sql ="select cd.name from  
        oc_product_to_category as p2c left join oc_category as c on p2c.category_id =c.category_id
        left join oc_category_description as cd on  p2c.category_id =cd.category_id
        where p2c.product_id =".$product_id." and c.level=2 and cd.language_id=1";
        $query =$this->db->query($sql);
        $row =$query->row;
        if($row){
            return $row['name'];
        }
        else{
            return 'NULL';
        }
    }
    public function getGuijiData(){
        $data =array();
        $sql ="select  pa.group_id,p.model, p.supplier_code,nad.name,naov.option_value from oc_product_attr_filter as pa
                left join oc_product as p on pa.product_id =p.product_id
                left join oc_new_attribute_description as nad on pa.attr_id=nad.attribute_id and nad.language_id=1
                left join oc_new_attribute_option_value as naov on pa.value_id=naov.option_id and naov.language_id=1";
        $query=$this->db->query($sql);
        foreach($query->rows as $row){
            if(isset($data[$row['model']])){
                $data[$row['model']][] =$row['name'];
                $data[$row['model']][] =$row['option_value'];
            }
            else{
                $data[$row['model']][] =$row['group_id'];
                $data[$row['model']][] =$row['model'];
                $data[$row['model']][] =$row['supplier_code'];
                $data[$row['model']][] =$row['name'];
                $data[$row['model']][] =$row['option_value'];
            }
        }
        return $data;
    }
    public function getDescData(){
        $data =array();
        $sql ="select p.model, pd.packaging_list,pd.read_more, pd.application_image,pd.size_image,pd.features,pd.installation_method,pd.video,
        pd.notes from oc_product_description as pd
        left join oc_product as p on p.product_id=pd.product_id
        where pd.language_id=1 ORDER  BY p.product_id ASC ";
        $query =$this->db->query($sql);
        foreach($query->rows as $row){
            if($row['packaging_list']||$row['read_more']||$row['application_image']||$row['size_image']||$row['features']||$row['installation_method']||$row['video']||$row['notes']){
                $data[] =$row;
            }
        }
        return $data ;

    }
    //得到对应excel的列数
    public function get_excel_col($num){
        $tmp_array =array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        if($num<=25){
            $col_str =$tmp_array[$num];
        }
        else{
            $n =floor(($num)/25);
            $left =($num)%25;
            if($left==0){
                $col_str =$tmp_array[$n-1].'Z';
            }
            else{
                $col_str =$tmp_array[$n-1].$tmp_array[$left-1];
            }
        }
        return $col_str;
    }
   
}
?>