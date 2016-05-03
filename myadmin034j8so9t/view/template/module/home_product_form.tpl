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
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> Product Id</td>
            <td><input type="text" name="product_id" value="<?php echo isset($home_product_info['product_id'])?$home_product_info['product_id']:''; ?>" size="100" />
              <?php if(isset($error_product_id)) { ?>
              <span class="error"><?php echo $error_sku; ?></span>
              <?php } ?>
			  <?php if(isset($error_exit)) { ?>
              <span class="error"><?php echo $error_exit; ?></span>
              <?php } ?>
			  
			  <?php if(isset($error_not_special)) { ?>
              <span class="error"><?php echo $error_not_special; ?></span>
              <?php } ?>	
			  </td>
          </tr>
		  <tr>
            <td><span class="required">*</span>type</td>
            <td>
				<select name="type">
					<option value="-1">select product type</option>
					<?php foreach($product_types as $key => $pro_type){
						if(isset($home_product_info['type'])&&$home_product_info['type']== $key){
					?>
							<option value="<?php echo $key;?>" selected="selected"><?php echo $pro_type;?></option>
					<?php
						}
						else{
					?>
					
							<option value="<?php echo $key;?>"><?php echo $pro_type;?></option>
					<?php
						}
					}
					?>
				</select>
				
				<?php if(isset($error_type)) { ?>
              <span class="error"><?php echo $error_type; ?></span>
              <?php } ?>
            </td>
          </tr>
		  <tr>
            <td>start time</td>
            <td><input type="text" name="start_time"  class="datetime" value="<?php echo isset($home_product_info['start_time'])?$home_product_info['start_time']:''; ?>" size="100" />
            </td>
          </tr>
		  <tr>
            <td>end time</td>
            <td><input type="text" name="end_time" class="datetime" value="<?php echo isset($home_product_info['end_time'])?$home_product_info['end_time']:''; ?>" size="100" />
             </td>
          </tr>
          <tr>
            <td>sort order</td>
            <td><input type="text" name="sort_order" value="<?php echo isset($home_product_info['sort_order'])?$home_product_info['sort_order']:''; ?>" size="100" />
              </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
});
$('.time').timepicker({timeFormat: 'hh:mm:ss'});
//--></script> 
<?php echo $footer; ?>