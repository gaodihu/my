<?php
class ModelModuleHomeProduct extends Model {
    /*
    * $type 首页类型。1，special.2 best seller 3. new arrives
    *$data  条件数组
    *$come_soon  是否是未来数据
    */

	public function getHomeProduct($type,$data,$come_soon=false){
		$this->load->model('catalog/product');
		$home_product_info =array();
        $time_where ='';
        if(isset($data['start_time'])){
            if(!$come_soon){
                $time_where .=" and start_time<='".$data['start_time']."' and end_time>'".$data['start_time']."'";
            }
            else{
                $time_where .=" and start_time>='".$data['start_time']."' and end_time>'".$data['start_time']."'";
            }
        }
        else{
            $time_where .=" and start_time<=NOW()";    
        }

        if(isset($data['end_time'])){
            $time_where .=" and end_time<='".$data['end_time']."'";
        }
        else{
            $time_where .=" and end_time>=NOW()";
        }
		$sql = "select product_id,start_time,end_time from ".DB_PREFIX."home_products where type=".$type .$time_where." order by ".$data['sort'].' '.$data['order']." limit ".$data['start'].",".$data['limit'];
		$query = $this->db->query($sql);
		foreach($query->rows as $key=>$item){
			$product_info=$this->model_catalog_product->getProduct($item['product_id']);
            if($product_info['special']){
                $save_rate =round((($product_info['price']-$product_info['special'])/$product_info['price'])*100,2);
            }
            else{
                $save_rate =false;
            }
            
			$home_product_info[]=array(
				'product_id'	=>$item['product_id'],
				'start_time' =>$item['start_time'],
				'end_time'   =>$item['end_time'],
				'name'   =>$product_info['name'],
				'sku'   =>$product_info['model'],
				'price'   =>$product_info['price'],
				'special'   =>$product_info['special'],
				'image'   =>$product_info['image'],
				'rating'   =>$product_info['rating'],
				'reviews' =>$product_info['reviews'],
				'tax_class_id'   =>$product_info['tax_class_id'],
                'save_rate'          =>$save_rate
			);
		}
		return $home_product_info;
	}
}
?>