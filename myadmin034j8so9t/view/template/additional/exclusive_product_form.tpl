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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/review.png" alt="" /><?php echo $action_text;?></h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">	
		<table class="form">
			<tr>
				<td>product id：</td>
				<td>
				<input type="text"  name="product_id" value="<?php echo isset($exclusive_product_info['product_id'])?$exclusive_product_info['product_id']:'';?>" />	
			</tr>
			<tr>
				<td>from url(渠道的url,请填写ID，多个用逗号隔开)：</td>
				<td><input type="text"  name="from_url" value="<?php echo isset($exclusive_product_info['from_url'])?$exclusive_product_info['from_url']:'';?>"  /></td>
			</tr>
			<tr>
				<td>price：</td>
				<td><input type="text"  name="price" value="<?php echo isset($exclusive_product_info['price'])?$exclusive_product_info['price']:'';?>" /></td>
			</tr>
			<tr>
				<td>limit number:</td>
				<td><input type="text"  name="limit_number" value="<?php echo  isset($exclusive_product_info['limit_number'])?$exclusive_product_info['limit_number']:'';?>" /></td>
			</tr>
			<tr>
				<td>start_time：</td>
				<td><input type="text"  name="start_time" value="<?php echo isset($exclusive_product_info['start_time'])?$exclusive_product_info['start_time']:'' ;?>"  class="datetime"/></td>
			</tr>
			<tr>
				<td>end_time：</td>
				<td><input type="text"  name="end_time" value="<?php echo isset($exclusive_product_info['end_time'])?$exclusive_product_info['end_time']:'' ;?>"  class="datetime"/></td>
			</tr>
			<tr>
				<td><input type="submit" value="确定" /></td>
				<td><input type="reset" value="取消" /></td>
			</tr>
			
        </table>
	</form>
    </div>
	
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>  
<script type="text/javascript"><!--
 $('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
    });
</script>

<?php echo $footer; ?>