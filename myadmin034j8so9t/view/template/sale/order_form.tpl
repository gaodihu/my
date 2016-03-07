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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="vtabs" class="vtabs"><a href="#tab-customer"><?php echo $tab_customer; ?></a><!--<a href="#tab-payment"><?php echo $tab_payment; ?></a>--><a href="#tab-shipping"><?php echo $tab_shipping; ?></a><a href="#tab-product"><?php echo $tab_product; ?></a><a href="#tab-total"><?php echo $tab_total; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-customer" class="vtabs-content">
          <table class="form">
            <tr>
              <td class="left"><?php echo $entry_store; ?></td>
              <td class="left"><select name="store_id">
                  <option value="0"><?php echo $text_default; ?></option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $store_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_customer; ?></td>
              <td><input type="text" name="customer" value="<?php echo $customer; ?>" />
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                <input type="hidden" name="customer_group_id" value="<?php echo $customer_group_id; ?>" /></td>
            </tr>
            <tr>
              <td class="left"><?php echo $entry_customer_group; ?></td>
              <td class="left"><select id="customer_group_id" <?php echo ($customer_id ? 'disabled="disabled"' : ''); ?>>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <!--
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            -->
            <tr>
              <td><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="customer_email" value="<?php echo $email; ?>" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
            <!--
            <tr>
              <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_fax; ?></td>
              <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
            </tr>
            -->
          </table>
        </div>
          <!--
        <div id="tab-payment" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_address; ?></td>
              <td><select name="payment_address">
                  <option value="0" selected="selected"><?php echo $text_none; ?></option>
                  <?php foreach ($addresses as $address) { ?>
                  <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" />
                <?php if ($error_payment_firstname) { ?>
                <span class="error"><?php echo $error_payment_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" />
                <?php if ($error_payment_lastname) { ?>
                <span class="error"><?php echo $error_payment_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_company; ?></td>
              <td><input type="text" name="payment_company" value="<?php echo $payment_company; ?>" /></td>
            </tr>
            <tr id="company-id-display">
              <td><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></td>
              <td><input type="text" name="payment_company_id" value="<?php echo $payment_company_id; ?>" /></td>
            </tr>
            <tr id="tax-id-display">
              <td><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></td>
              <td><input type="text" name="payment_tax_id" value="<?php echo $payment_tax_id; ?>" />
                <?php if ($error_payment_tax_id) { ?>
                <span class="error"><?php echo $error_payment_tax_id; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
              <td><input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" />
                <?php if ($error_payment_address_1) { ?>
                <span class="error"><?php echo $error_payment_address_1; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_2; ?></td>
              <td><input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_city; ?></td>
              <td><input type="text" name="payment_city" value="<?php echo $payment_city; ?>" />
                <?php if ($error_payment_city) { ?>
                <span class="error"><?php echo $error_payment_city; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
              <td><input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" />
                <?php if ($error_payment_postcode) { ?>
                <span class="error"><?php echo $error_payment_postcode; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_country; ?></td>
              <td><select name="payment_country_id">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $payment_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_payment_country) { ?>
                <span class="error"><?php echo $error_payment_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
              <td><select name="payment_zone_id">
                </select>
                <?php if ($error_payment_zone) { ?>
                <span class="error"><?php echo $error_payment_zone; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
          -->
        <div id="tab-shipping" class="vtabs-content">
          <table class="form">
            <tr>
              <td><?php echo $entry_address; ?></td>
              <td><select name="shipping_address">
                  <option value="0" selected="selected"><?php echo $text_none; ?></option>
                  <?php foreach ($addresses as $address) { ?>
                  <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
              <td><input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" />
                <?php if ($error_shipping_firstname) { ?>
                <span class="error"><?php echo $error_shipping_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
              <td><input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" />
                <?php if ($error_shipping_lastname) { ?>
                <span class="error"><?php echo $error_shipping_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_company; ?></td>
              <td><input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
              <td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" />
                <?php if ($error_shipping_address_1) { ?>
                <span class="error"><?php echo $error_shipping_address_1; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_2; ?></td>
              <td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_city; ?></td>
              <td><input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" /></td>
            </tr>
            <tr>
              <td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
              <td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" />
                <?php if ($error_shipping_postcode) { ?>
                <span class="error"><?php echo $error_shipping_postcode; ?></span>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><span id="shipping-phone-required" class="required">*</span> phone</td>
              <td><input type="text" name="shipping_phone" value="<?php echo $shipping_phone; ?>" />
                <?php if ($error_shipping_phone) { ?>
                <span class="error"><?php echo $error_shipping_phone; ?></span>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><span class="required">*</span> <?php echo $entry_country; ?></td>
              <td><select name="shipping_country_id">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $shipping_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_shipping_country) { ?>
                <span class="error"><?php echo $error_shipping_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
              <td><select name="shipping_zone_id">
                </select>
                <?php if ($error_shipping_zone) { ?>
                <span class="error"><?php echo $error_shipping_zone; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab-product" class="vtabs-content">
          <table class="list">
            <thead>
              <tr>
                <td></td>
                <td class="left"><?php echo $column_product; ?></td>
                <td class="left"><?php echo $column_model; ?></td>
                <td class="right"><?php echo $column_quantity; ?></td>
                <td class="right"><?php echo $column_price; ?></td>
                <td class="right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
			<?php $product_row = 0; ?>
			<?php $option_row = 0; ?>
			<?php $download_row = 0; ?>
			<tbody id="product">
				<?php include_once(DIR_TEMPLATE.'/sale/include/order_product.tpl');?>	 
            </tbody>
          </table>
       <!--    <table class="list">
            <thead>
              <tr>
                <td colspan="2" class="left"><?php echo $text_product; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $entry_product; ?></td>
                <td class="left"><input type="text" name="product_sku" value="" />
                  </td>
              </tr>
              <tr id="option"></tr>
              <tr>
                <td class="left"><?php echo $entry_quantity; ?></td>
                <td class="left"><input type="text" name="quantity" value="1" id='add_pro_qty'/></td>
              </tr>             
            </tbody>
            <tfoot>
              <tr>
                <td class="left">&nbsp;</td>
                <td class="left">
          				<input type="hidden" name="order_id" value="<?php echo $order_id;?>" id='add_for_order'/>
          				<a id="button-product" class="button"><?php echo $button_add_product; ?></a></td>
              </tr>
            </tfoot>
          </table> -->
        </div>
        <div id="tab-total" class="vtabs-content">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_product; ?></td>
                <td class="left"><?php echo $column_model; ?></td>
                <td class="right"><?php echo $column_quantity; ?></td>
                <td class="right"><?php echo $column_price; ?></td>
                <td class="right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
		 <tbody id="total">
          <?php include_once(DIR_TEMPLATE.'/sale/include/order_product_total_update.tpl');?>
		   <input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>" />
           <input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>" />
		  <input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>" />
          <input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>" />
          </tbody>
          </table>
        
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item['category'] != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item['category'] + '</li>');
				
				currentCategory = item['category'];
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'customer\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						category: item['customer_group'],
						label: item['name'],
						value: item['customer_id'],
						customer_group_id: item['customer_group_id'],
						firstname: item['firstname'],
						lastname: item['lastname'],
						email: item['email'],
						telephone: item['telephone'],
						fax: item['fax'],
						address: item['address']
					}
				}));
			}
		});
	}, 
	select: function(event, ui) { 
		$('input[name=\'customer\']').attr('value', ui.item['label']);
		$('input[name=\'customer_id\']').attr('value', ui.item['value']);
		$('input[name=\'firstname\']').attr('value', ui.item['firstname']);
		$('input[name=\'lastname\']').attr('value', ui.item['lastname']);
		$('input[name=\'email\']').attr('value', ui.item['email']);
		$('input[name=\'telephone\']').attr('value', ui.item['telephone']);
		$('input[name=\'fax\']').attr('value', ui.item['fax']);
			
		html = '<option value="0"><?php echo $text_none; ?></option>'; 
			
		for (i in  ui.item['address']) {
			html += '<option value="' + ui.item['address'][i]['address_id'] + '">' + ui.item['address'][i]['firstname'] + ' ' + ui.item['address'][i]['lastname'] + ', ' + ui.item['address'][i]['address_1'] + ', ' + ui.item['address'][i]['city'] + ', ' + ui.item['address'][i]['country'] + '</option>';
		}
		
		$('select[name=\'shipping_address\']').html(html);
		$('select[name=\'payment_address\']').html(html);
		
		$('select[id=\'customer_group_id\']').attr('disabled', false);
		$('select[id=\'customer_group_id\']').attr('value', ui.item['customer_group_id']);
		$('select[id=\'customer_group_id\']').trigger('change');
		$('select[id=\'customer_group_id\']').attr('disabled', true); 
					 	
		return false; 
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('select[id=\'customer_group_id\']').live('change', function() {
	$('input[name=\'customer_group_id\']').attr('value', this.value);
	
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('#company-id-display').show();
		} else {
			$('#company-id-display').hide();
		}
		
		if (customer_group[this.value]['company_id_required'] == '1') {
			$('#company-id-required').show();
		} else {
			$('#company-id-required').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('#tax-id-display').show();
		} else {
			$('#tax-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_required'] == '1') {
			$('#tax-id-required').show();
		} else {
			$('#tax-id-required').hide();
		}	
	}
});

$('select[id=\'customer_group_id\']').trigger('change');

var payment_zone_id = '<?php echo $payment_zone_id; ?>';

$('select[name=\'payment_country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json != '' && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == payment_zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'payment_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'payment_country_id\']').trigger('change');

$('select[name=\'payment_address\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			if (json != '') {	
				$('input[name=\'payment_firstname\']').attr('value', json['firstname']);
				$('input[name=\'payment_lastname\']').attr('value', json['lastname']);
				$('input[name=\'payment_company\']').attr('value', json['company']);
				$('input[name=\'payment_company_id\']').attr('value', json['company_id']);
				$('input[name=\'payment_tax_id\']').attr('value', json['tax_id']);
				$('input[name=\'payment_address_1\']').attr('value', json['address_1']);
				$('input[name=\'payment_address_2\']').attr('value', json['address_2']);
				$('input[name=\'payment_city\']').attr('value', json['city']);
				$('input[name=\'payment_postcode\']').attr('value', json['postcode']);
				$('select[name=\'payment_country_id\']').attr('value', json['country_id']);
				
				payment_zone_id = json['zone_id'];
				
				$('select[name=\'payment_country_id\']').trigger('change');
			}
		}
	});	
});

var shipping_zone_id = '<?php echo $shipping_zone_id; ?>';

$('select[name=\'shipping_country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json != '' && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == shipping_zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'shipping_zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'shipping_country_id\']').trigger('change');

$('select[name=\'shipping_address\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			if (json != '') {	
				$('input[name=\'shipping_firstname\']').attr('value', json['firstname']);
				$('input[name=\'shipping_lastname\']').attr('value', json['lastname']);
				$('input[name=\'shipping_company\']').attr('value', json['company']);
				$('input[name=\'shipping_address_1\']').attr('value', json['address_1']);
				$('input[name=\'shipping_address_2\']').attr('value', json['address_2']);
				$('input[name=\'shipping_city\']').attr('value', json['city']);
				$('input[name=\'shipping_postcode\']').attr('value', json['postcode']);
				$('select[name=\'shipping_country_id\']').attr('value', json['country_id']);
				
				shipping_zone_id = json['zone_id'];
				
				$('select[name=\'shipping_country_id\']').trigger('change');
			}
		}
	});	
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id,
						model: item.model,
						option: item.option,
						price: item.price
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'product\']').attr('value', ui.item['label']);
		$('input[name=\'product_id\']').attr('value', ui.item['value']);
		
		if (ui.item['option'] != '') {
			html = '';

			for (i = 0; i < ui.item['option'].length; i++) {
				option = ui.item['option'][i];
				
				if (option['type'] == 'select') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
				
					html += option['name'] + '<br />';
					html += '<select name="option[' + option['product_option_id'] + ']">';
					html += '<option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['option_value'].length; j++) {
						option_value = option['option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '</select>';
					html += '</div>';
					html += '<br />';
				}
				
				if (option['type'] == 'radio') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
				
					html += option['name'] + '<br />';
					html += '<select name="option[' + option['product_option_id'] + ']">';
					html += '<option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['option_value'].length; j++) {
						option_value = option['option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '</select>';
					html += '</div>';
					html += '<br />';
				}
					
				if (option['type'] == 'checkbox') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					
					for (j = 0; j < option['option_value'].length; j++) {
						option_value = option['option_value'][j];
						
						html += '<input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" id="option-value-' + option_value['product_option_value_id'] + '" />';
						html += '<label for="option-value-' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</label>';
						html += '<br />';
					}
					
					html += '</div>';
					html += '<br />';
				}
			
				if (option['type'] == 'image') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
				
					html += option['name'] + '<br />';
					html += '<select name="option[' + option['product_option_id'] + ']">';
					html += '<option value=""><?php echo $text_select; ?></option>';
				
					for (j = 0; j < option['option_value'].length; j++) {
						option_value = option['option_value'][j];
						
						html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
						
						if (option_value['price']) {
							html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
						}
						
						html += '</option>';
					}
						
					html += '</select>';
					html += '</div>';
					html += '<br />';
				}
						
				if (option['type'] == 'text') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
					html += '</div>';
					html += '<br />';
				}
				
				if (option['type'] == 'textarea') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<textarea name="option[' + option['product_option_id'] + ']" cols="40" rows="5">' + option['option_value'] + '</textarea>';
					html += '</div>';
					html += '<br />';
				}
				
				if (option['type'] == 'file') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<a id="button-option-' + option['product_option_id'] + '" class="button"><?php echo $button_upload; ?></a>';
					html += '<input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
					html += '</div>';
					html += '<br />';
				}
				
				if (option['type'] == 'date') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="date" />';
					html += '</div>';
					html += '<br />';
				}
				
				if (option['type'] == 'datetime') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="datetime" />';
					html += '</div>';
					html += '<br />';						
				}
				
				if (option['type'] == 'time') {
					html += '<div id="option-' + option['product_option_id'] + '">';
					
					if (option['required']) {
						html += '<span class="required">*</span> ';
					}
					
					html += option['name'] + '<br />';
					html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="time" />';
					html += '</div>';
					html += '<br />';						
				}
			}
			
			$('#option').html('<td class="left"><?php echo $entry_option; ?></td><td class="left">' + html + '</td>');

			for (i = 0; i < ui.item.option.length; i++) {
				option = ui.item.option[i];
				
				if (option['type'] == 'file') {		
					new AjaxUpload('#button-option-' + option['product_option_id'], {
						action: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
						name: 'file',
						autoSubmit: true,
						responseType: 'json',
						data: option,
						onSubmit: function(file, extension) {
							$('#button-option-' + (this._settings.data['product_option_id'] + '-' + this._settings.data['product_option_id'])).after('<img src="view/image/loading.gif" class="loading" />');
						},
						onComplete: function(file, json) {

							$('.error').remove();
							
							if (json['success']) {
								alert(json['success']);
								
								$('input[name=\'option[' + this._settings.data['product_option_id'] + ']\']').attr('value', json['file']);
							}
							
							if (json.error) {
								$('#option-' + this._settings.data['product_option_id']).after('<span class="error">' + json['error'] + '</span>');
							}
							
							$('.loading').remove();	
						}
					});
				}
			}
			
			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
			$('.datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'h:m'
			});
			$('.time').timepicker({timeFormat: 'h:m'});				
		} else {
			$('#option td').remove();
		}
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});	
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'payment\']').bind('change', function() {
	if (this.value) {
		$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
	} else {
		$('input[name=\'payment_method\']').attr('value', '');
	}
	
	$('input[name=\'payment_code\']').attr('value', this.value);
});

$('select[name=\'shipping\']').bind('change', function() {
	if (this.value) {
		$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\'] option:selected').text());
	} else {
		$('input[name=\'shipping_method\']').attr('value', '');
	}
	
	$('input[name=\'shipping_code\']').attr('value', this.value);
});
//--></script> 
<script type="text/javascript">
$('#button-product').click(function(){
	var order_id = $('#add_for_order').val();
	var product_sku =$(':input[name=product_sku]').val();
	var qty =$('#add_pro_qty').val();
        var store_id = $(':input[name=store_id]').val();
	$.ajax({
		url: 'index.php?route=sale/order/addOrderProduct&token=<?php echo $token;?>&',
		type: 'post',
		data: 'product_sku=' + product_sku + '&quantity=' + qty+'&order_id='+order_id+"&store_id="+store_id,
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				$('#product').html(json['product_coutent']);
				$('#total').html(json['total_coutent']);
			}
			else{
				alert(json['message']);
			}
		}
	});
})
</script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<?php echo $footer; ?>