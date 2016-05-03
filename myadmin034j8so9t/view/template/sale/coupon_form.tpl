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
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
        <?php if ($coupon_id) { ?>
        <a href="#tab-history"><?php echo $tab_history; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input name="name" value="<?php echo $name; ?>" />
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
              <td><span class="required">*</span> front name (显示在前端网页)</td>
              <td>
			  <?php foreach($languages as $language){ ?>
			  
			  <img src="view/image/flags/<?php echo $language['image'];?>" /><input name="coupon_description[<?php echo $language['language_id'];?>][front_name]" value="<?php echo isset($front_name[$language['language_id']]['front_name'])?$front_name[$language['language_id']]['front_name']:'' ?> " />
			  <?php } ?>
			   
              <?php if ($error_front_name) { ?>
              <span class="error"><?php echo $error_front_name; ?></span>
              <?php } ?>
			</td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>" />
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_type; ?></td>
              <td><select name="type">
                  <?php if ($type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $text_percent; ?></option>
                  <?php } ?>
                  <?php if ($type == 'F') { ?>
                  <option value="F" selected="selected">fixed amount discount for the whole cart （固定的减免金额）</option>
                  <?php } else { ?>
                  <option value="F">fixed amount discount for the whole cart （固定的减免金额）</option>
                  <?php } ?>
				  <?php if ($type == 'PF') { ?>
                  <option value="PF" selected="selected">fixed amount discount(比例减免金额)</option>
                  <?php } else { ?>
                  <option value="PF">fixed amount discount(比例减免金额)</option>
                  <?php } ?>
				  <?php if ($type == 'BGP') { ?>
                  <option value="BGP" selected="selected">Buy x get y(比例减免)</option>
                  <?php } else { ?>
                  <option value="BGP">Buy x get y(比例减免)</option>
                  <?php } ?>
				  <?php if ($type == 'BGF') { ?>
                  <option value="BGF" selected="selected">Buy x get y(固定减免)</option>
                  <?php } else { ?>
                  <option value="BGF">Buy x get y(固定减免)</option>
                  <?php } ?>
				  <?php if ($type == 'BGD') { ?>
                  <option value="BGD" selected="selected">Buy x get y Percentage(比例减免折扣,discount为%比折扣)</option>
                  <?php } else { ?>
                  <option value="BGD">Buy x get y Percentage(比例减免折扣,discount为%比折扣)</option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr><td>购买数量(buy x ):</td>
				<td><input type="text" name="buy_x" value="<?php echo $buy_x; ?>" /></td>
			</tr>
            <tr>
              <td><?php echo $entry_discount; ?></td>
              <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
            </tr>
			<tr>
              <td style="color:red; font-size:20px;" colspan="2">coupon conditions </td>
            </tr>
			<tr>
              <td>合并条件(all or any )</td>
              <td>
			  		<?php if($combine_condition){ ?>
			  		<input type="radio" name="combine_condition" value="1" checked="checked" />
					all(满足所有condition条件)
					<input type="radio" name="combine_condition" value="0" />	
					any(满足条件中的任何一个)
					<?php }else{ ?>
					<input type="radio" name="combine_condition" value="1" />
					all(满足所有condition条件)
					<input type="radio" name="combine_condition" value="0" checked="checked" />	
					any(满足条件中的任何一个)
					<?php } ?>
			  </td>
            </tr>
			
			<tr>
              <td>*选择条件项</td>
              <td>
			  		
			  		<input type="checkbox" name="combine_condition_value[]" value="total" <?php if(in_array('total',$combine_condition_value)){ ?> checked="checked" <?php } ?> />Total Amount
					<input type="checkbox" name="combine_condition_value[]" value="condition_total" <?php if(in_array('condition_total',$combine_condition_value)){ ?> checked="checked" <?php } ?> />符合条件(可以使用coupon商品)总金额
					
					<input type="checkbox" name="combine_condition_value[]" value="total_qty" <?php if(in_array('total_qty',$combine_condition_value)){ ?> checked="checked" <?php } ?>/>Total Quantity
					
					<input type="checkbox" name="combine_condition_value[]" value="condition_total_qty" <?php if(in_array('condition_total_qty',$combine_condition_value)){ ?> checked="checked" <?php } ?>/>Condition Total Quantity(符合条件的商品数目)

					<input type="checkbox" name="combine_condition_value[]" value="row_item_qty" <?php if(in_array('row_item_qty',$combine_condition_value)){ ?> checked="checked" <?php } ?> />Row Item Quantity
					
					<input type="checkbox" name="combine_condition_value[]" value="logged" <?php if(in_array('logged',$combine_condition_value)){ ?>checked="checked" <?php } ?> />Customer Login
					
					<input type="checkbox" name="combine_condition_value[]" value="shipping" <?php if(in_array('shipping',$combine_condition_value)){ ?>checked="checked"<?php } ?>/>Free Shipping
					
					<input type="checkbox" name="combine_condition_value[]" value="products" <?php if(in_array('products',$combine_condition_value)){ ?>checked="checked"<?php } ?>/>Products
					
					<input type="checkbox" name="combine_condition_value[]" value="category" <?php if(in_array('category',$combine_condition_value)){ ?>checked="checked"<?php } ?>/>Category	
					
			  </td>
            </tr>
            <tr>
              <td><?php echo $entry_total; ?></td>
              <td><input type="text" name="total" value="<?php echo $total; ?>" /></td>
            </tr>
			<tr>
              <td>符合条件(可以使用coupon商品)总金额</td>
              <td><input type="text" name="condition_total" value="<?php echo $condition_total; ?>" /></td>
            </tr>
			<tr>
              <td>Total Quantity(购物车总产品数量）</td>
              <td><input type="text" name="total_qty" value="<?php echo $total_qty; ?>" /></td>
            </tr>
				<tr>
              <td>Condition Total Quantity(符合条件的购物车产品数量）</td>
              <td><input type="text" name="condition_total_qty" value="<?php echo $condition_total_qty; ?>" /></td>
            </tr>
			<tr>
              <td>Row Item Quantity(购物车单个产品数量）</td>
              <td><input type="text" name="row_item_qty" value="<?php echo $row_item_qty; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_logged; ?></td>
              <td><?php if ($logged) { ?>
                <input type="radio" name="logged" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="logged" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="logged" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="logged" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping) { ?>
                <input type="radio" name="shipping" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
			<tr>
              <td>sku cindition</td>
              <td>
			  	<select name="sku_condition">
					
					<option value="0">select...</option>
					<?php if($sku_condition==1){?>
					<option value="1" selected="selected">sku is one of</option>
					<?php } else{ ?>
					<option value="1" >sku is one of</option>
					<?php } ?>
					<?php if($sku_condition==2){?>
					<option value="2" selected="selected">sku is not one of</option>
					<?php } else{ ?>
					<option value="2">sku is not one of</option>
					<?php } ?>
					<?php if($sku_condition==3){?>
					<option value="3" selected="selected">sku is</option>
					<?php } else{ ?>
					<option value="3" >sku is</option>
					<?php } ?>
					<?php if($sku_condition==4){?>
					<option value="4" selected="selected">sku is not</option>
					<?php } else{ ?>
					<option value="4">sku is not</option>
					<?php } ?>
				</select> 
			  </td>
            </tr>
            <tr>
              <td><?php echo $entry_product; ?></td>
              <td><input type="text" name="coupon_product" value="<?php echo $coupon_products;?>" />
			  	<?php if ($error_sku_in_catalog) { ?>
                <span class="error"><?php echo $error_sku_in_catalog; ?></span>
                <?php } ?>
			  </td>
            </tr>
			<tr>
              <td>category cindition</td>
              <td>
			  	<select name="category_condition">
					<option value="0">select...</option>
					<?php if($category_condition==1){ ?>
					<option value="1" selected="selected">category is one of  </option>
					
					<?php }else{ ?>
					<option value="1">category is one of  </option>
					<?php } ?>
					<?php if($category_condition==2){ ?>
					<option value="2" selected="selected">category is not one of</option>
					<?php }else{ ?>
					<option value="2">category is not one of</option>
					<?php } ?>
				</select> 
			  </td>
            </tr>
             <tr>
              <td><?php echo $entry_category; ?></td>
              <td><input type="text" name="category" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="coupon-category" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($coupon_category as $coupon_category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="coupon-category<?php echo $coupon_category['category_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_category['name']; ?><img src="view/image/delete.png" alt="" />
                    <input type="hidden" name="coupon_category[]" value="<?php echo $coupon_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>           
            <tr>
              <td><?php echo $entry_date_start; ?></td>
              <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="24" id="date-start" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_date_end; ?></td>
              <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="24" id="date-end" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_total; ?></td>
              <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_uses_customer; ?></td>
              <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php if ($coupon_id) { ?>
        <div id="tab-history">
          <div id="history"></div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#coupon-category' + ui.item.value).remove();
		
		$('#coupon-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="coupon_category[]" value="' + ui.item.value + '" /></div>');

		$('#coupon-category div:odd').attr('class', 'odd');
		$('#coupon-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#coupon-category div img').live('click', function() {
	$(this).parent().remove();
	
	$('#coupon-category div:odd').attr('class', 'odd');
	$('#coupon-category div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('#date-start').datetimepicker({
  dateFormat: 'yy-mm-dd',
  timeFormat: 'hh:mm:ss',
  showSecond: true,
  changeMonth: true,
	changeYear: true
});
$('#date-end').datetimepicker({
  dateFormat: 'yy-mm-dd',
  timeFormat: 'hh:mm:ss',
  showSecond: true,
  changeMonth: true,
  changeYear: true
});
//--></script>
<?php if ($coupon_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/coupon/history&token=<?php echo $token; ?>&coupon_id=<?php echo $coupon_id; ?>');
//--></script>
<?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>