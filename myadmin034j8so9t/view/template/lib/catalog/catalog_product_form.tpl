
<table class="list">
            <thead>
              <tr>
			  	<td class="left"><input type="checkbox" name="checkAll"  class="checkbox" title="Select All" id='checkAll'></td> 
                <td class="left">
                <a href="javascript:sort_order('p.product_id','<?php echo $order;?>')">ID</a>
               </td>
                <td class="left">名称</td>
				<td class="left">SKU</td>
				<td class="left">价格</td>
				<td class="left"><a href="javascript:sort_order('pc.position','<?php echo $order;?>')">排序</a></td>
				<td class="left">action</td>
              </tr>
            </thead>
			<thead>
              <tr>
			  	<td class="left"></td> 
                <td class="left"></td>
                <td class="left"><input type="text" value="<?php echo $filter_name;?>" name='filter_name'/></td>
				<td class="left"><input type="text" value="<?php echo $filter_sku;?>" name='filter_sku'/></td>
				<td class="left"></td>
				<td class="left"></td>
				<td class="left"><input type="button" class="button filter"  value="filter" /></td>
              </tr>
            </thead>
            <tbody>
			<?php 
			if(isset($pro_info)){
			foreach($pro_info as $v){
			?>
			 <tr>
			  	<td class="left"><input type="checkbox" name="pro_check[]" value="<?php echo $v['product_id'];?>" class="checkbox" pid="<?php echo $v['product_id'];?>"></td>
                <td class="left"><?php echo $v['product_id'];?></td>
                <td class="left"><?php echo $v['name'];?></td>
				<td class="left"><?php echo $v['model'];?></td>
				<td class="left"><?php echo $v['price'];?></td>
				<td class="left"><input type="text" class="input-text " name="position[]" value="<?php echo $v['position'];?>" id="p_<?php echo $v['product_id'];?>"> 
				</td>
              </tr>
			<?php
			}
				} 
			?>
             
            </tbody>
          </table>
<div class="pagination"><?php echo $pagination; ?></div>
<script type="text/javascript">
$(document).ready(function(){
	$('#checkAll').attr('checked','checked');
	$('input[name=\'pro_check[]\']').attr('checked','checked');
});
$('#checkAll').click(function(){
var checked = $(this).attr('checked');
if(checked=='checked'){
	$('input[name=\'pro_check[]\']').attr('checked','checked');
	$('input[name=\'position[]\']').removeAttr("disabled");
}
else{
	$('input[name=\'pro_check[]\']').removeAttr("checked");
	$('input[name=\'position[]\']').attr("disabled",'disabled');
	
}
})
$('input[name=\'pro_check[]\']').click(function(){
	var checked = $(this).attr('checked');
	var id =$(this).attr('pid');
	var id_p = "p_"+id;
	if(checked=='checked'){
		$('#'+id_p).removeAttr("disabled");
	}
	else{
		$('#'+id_p).attr("disabled",'disabled');
	}
	
})
$('.pagination a').bind('click',function(){
   	var page = $(this).html();
	if(page =='&gt;|'){
		var page =<?php echo $totalPage;?>;
	}
	if(page =='&gt;'){
		var page =<?php echo $current_page+1;?>;
	}
	if(page =='|&lt;'){
		var page =1;
	}
	if(page =='&lt;'){
		var page =<?php echo $current_page-1;?>;
	}
	var sort_name ='<?php echo $sort_name;?>';
	var sort_order ='<?php echo $sort_order;?>';
	var filter_sku ='<?php echo $filter_sku;?>';
	var filter_name ='<?php echo $filter_name;?>';
	
	$.ajax({
		url: 'index.php?route=catalog/category/AjaxGetCatPro&token=<?php echo $token;?>&page=' + page+'&category_id='+category_id+"&sort_name="+sort_name+'&sort_order='+sort_order+'&filter_sku='+filter_sku+'&filter_name='+filter_name,
		dataType: 'text',
		success: function(data) {
			$('#tab-pro').html(data);
		}
	});
	
});
function sort_order(sort_name,order){
	var sort_name =sort_name;
	var sort_order =order;
	var page ="<?php echo $current_page;?>";
	var filter_sku ='<?php echo $filter_sku;?>';
	var filter_name ='<?php echo $filter_name;?>';
	$.ajax({
		url: 'index.php?route=catalog/category/AjaxGetCatPro&token=<?php echo $token;?>&page=' + page+'&category_id='+category_id+"&sort_name="+sort_name+'&sort_order='+sort_order+'&filter_sku='+filter_sku+'&filter_name='+filter_name,
		dataType: 'text',
		success: function(data) {
			$('#tab-pro').html(data);
		}
	});
}
$('.filter').live('click',function(){
	var filter_sku =$('input[name=\'filter_sku\']').attr('value');
	var filter_name =$('input[name=\'filter_name\']').attr('value');
	$.ajax({
		url: 'index.php?route=catalog/category/AjaxGetCatPro&token=<?php echo $token;?>&category_id='+category_id+'&filter_sku='+filter_sku+'&filter_name='+filter_name,
		dataType: 'text',
		success: function(data) {
			$('#tab-pro').html(data);
		}
	});
})
</script>