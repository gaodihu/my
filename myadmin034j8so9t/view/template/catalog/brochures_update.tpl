<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1>[<?php echo $sku;?>]编辑商品质检报告</h1>
      <div class="buttons">
	  <a href="javascript:$('#form').submit()" class="button">Save</a>
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a>
	  </div>
    </div>
    <div class="content">
     <form  action="<?php echo $update;?>" method="post" enctype="multipart/form-data" id='form'>
        <table class="list" id='zj_table'>
		  	<tr class="filter">
              <td>商品的质检报告</td>
			  <td>上传时间</td>
			  <td>修改时间</td>
              <td align="right"><a onclick="javascript:add_tr();" class="button">ADD</a></td>
            </tr>
			<?php if($all_broches){ ?>
			<?php foreach($all_broches as $broches){ ?>
				<tr>
					<td><input type="file" name="old_broches[<?php echo $broches['id'];?>]" value=''/><a href="<?php echo $web_url.'pdf/brochures/'.$broches['brochures_path'];?>"><?php echo $broches['brochures_path'];?></a></td>
					<td><?php echo $broches['add_time'];?></td>
					<td><?php echo $broches['update_time'];?></td>
					<td><a href="<?php echo $broches['delete'];?>" class="button">Delete</a></td>
				</tr>
			<?php } ?>
			<?php }else{ ?>
				<tr>
					<td><input type="file" name="new_broches[]" /></td>
					<td></td>
					<td></td>
					<td><a onclick="delete_tr();" class="button">Delete</a></td>
				</tr>
			<?php } ?>
        </table>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function add_tr(){
	var html ='<tr>'+
					'<td><input type="file" name="new_broches[]" /></td>'+
					'<td></td>'+
					'<td></td>'+
					'<td><a onclick="delete_tr();" class="button">Delete</a></td>'+
				'</tr>';
	$("#zj_table tr:last").after(html);
}
function delete_tr(){
	$("#zj_table tr:last").remove();
}
function filter() {
	url = 'index.php?route=catalog/brochures/update&token=<?php echo $token; ?>';
	
	var filter_sku = $('input[name=\'filter_sku\']').attr('value');
	
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
	
	

	location = url;
}
//--></script>
<?php echo $footer; ?>