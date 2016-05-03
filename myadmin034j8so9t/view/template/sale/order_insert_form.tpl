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

    </div>
    <div class="content">
      <!--<div id="vtabs" class="vtabs"><a href="#tab-customer"></a></div>-->
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		  <div style="font-size:16px;margin-bottom: 20px;"><b><?php echo $tab_customer; ?></b></div>
		  <div id="tab-customer" class="">
          <table class="form">
            <tr>
              <td class="left"><?php echo $entry_store; ?></td>
              <td class="left"><select name="store_id">
                  <option value="0"><?php echo $text_default; ?></option>
                  <?php foreach ($stores as $store) { ?>
                  <?php if ($store['store_id'] == $store_id) { ?>
                  <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['url']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $store['store_id']; ?>"><?php echo $store['url']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
                <td class="left">选择币种</td>
                <td class="left">
					<select name="currency_code" id='select_currency_code'>
						<?php foreach($currencies as $currency){ ?>
						<option value="<?php echo $currency['code'];?>"><?php echo $currency['code'];?></option>
						<?php } ?>
					</select>
                 </td>
              </tr>

              <tr>
                  <td><?php echo $entry_email; ?></td>
                  <td><input type="text" name="customer_email" value="<?php echo $email; ?>" />
                      <?php if ($error_email) { ?>
                      <span class="error"><?php echo $error_email; ?></span>
                      <?php } ?></td>
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

          </table>
        </div>
		  <div style="font-size:16px;margin-bottom: 20px;"><b><?php echo $tab_shipping; ?></b></div>

        <div id="tab-shipping" class="">
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
              <td>Tax Id:</td>
              <td><input type="text" name="order_tax_id" value="<?php echo $order_tax_id; ?>" /></td>
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
          <div style="font-size:16px;margin-bottom: 20px;"><b><?php echo $tab_product; ?></b></div>
        <div id="tab-product" class="">
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
			
			<tbody id="product">
				<?php include_once(DIR_TEMPLATE.'/sale/include/order_product.tpl');?>	 
            </tbody>
          </table>
          <table class="list">
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
			  <tr>
                <td class="left">商品价格</td>
                <td class="left"><input type="text" name="price" value="0" id='add_pro_price'/></td>
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
          </table>
        </div>
          <div style="font-size:16px;margin-bottom: 20px;"><b><?php echo $tab_total; ?></b></div>
        <div id="tab-total" class="">
			<div id='package'></div>
			<div id='pro-total'></div>
        </div>
      </form>
        <div style="margin-top: 20px;">
        <div class="buttons">
            <a id="order-save"  class="button" style="padding: 10px 30px 10px 30px;"><?php echo $button_save; ?></a>
            <a href="<?php echo $cancel; ?>" class="button" style="margin-left:20px "><?php echo $button_cancel; ?></a></div>
        </div>
        </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'customer_email\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						category: item['customer_group'],
						name:item['name'],
						label: item['email'],
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
		$('input[name=\'customer\']').attr('value', ui.item['name']);
		$('input[name=\'customer_id\']').attr('value', ui.item['value']);
		$('input[name=\'shipping_firstname\']').attr('value', ui.item['firstname']);
		$('input[name=\'shipping_lastname\']').attr('value', ui.item['lastname']);
		$('input[name=\'customer_email\']').attr('value', ui.item['email']);
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

$('input[name=\'affiliate\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['affiliate_id'],
					}
				}));
			}
		});
	}, 
	select: function(event, ui) { 
		$('input[name=\'affiliate\']').attr('value', ui.item['label']);
		$('input[name=\'affiliate_id\']').attr('value', ui.item['value']);
			
		return false; 
	},
	focus: function(event, ui) {
      	return false;
   	}
});

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
            tab_total();
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
				$('input[name=\'order_tax_id\']').attr('value', json['tax_id']);
				$('input[name=\'shipping_address_1\']').attr('value', json['address_1']);
				$('input[name=\'shipping_address_2\']').attr('value', json['address_2']);
				$('input[name=\'shipping_city\']').attr('value', json['city']);
				$('input[name=\'shipping_postcode\']').attr('value', json['postcode']);
                $('input[name=\'shipping_phone\']').attr('value', json['phone']);
				$('select[name=\'shipping_country_id\']').attr('value', json['country_id']);
				
				shipping_zone_id = json['zone_id'];
				
				$('select[name=\'shipping_country_id\']').trigger('change');

                tab_total();
			}
		}
	});	
});
//--></script> 

<script type="text/javascript"><!--
$('select[name=\'payment\']').bind('click', function() {
	if (this.value) {
		$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
	} else {
		$('input[name=\'payment_method\']').attr('value', '');
	}
	
	$('input[name=\'payment_code\']').attr('value', this.value);
});
function tab_total() {

	var arr  =
     {
         "firstname" : $('input[name=\'shipping_firstname\']').val(),
         "lastname" : $('input[name=\'shipping_lastname\']').val(),
		 "company" : $('input[name=\'shipping_company\']').val(),
		 "tax_id" : $('input[name=\'order_tax_id\']').val(),
		 "address_1" : $('input[name=\'shipping_address_1\']').val(),
		 "address_2" : $('input[name=\'shipping_address_2\']').val(),
		 "postcode" : $('input[name=\'shipping_postcode\']').val(),
		 "city" : $('input[name=\'shipping_city\']').val(),
		 "zone_id" : $('select[name=\'shipping_zone_id\']').val(),
		 "phone":$('input[name=\'shipping_phone\']').val(),
		 "country_id" : $('select[name=\'shipping_country_id\']').val(),
     }
     
		$.ajax({
		url: 'index.php?route=sale/customer/shipping_method&token=<?php echo $token; ?>',
		type: 'post',
        data: arr,
		dataType: 'json',
		success: function(json) {
	
				$('#package').html(json['content']);
				$('.shipping_method').on('change', shippingMethod);
		}
	});	
	
	$.ajax({
		url: 'index.php?route=sale/order/AddOrderTotals&token=<?php echo $token; ?>',
		type: 'post',
        data: '',
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				$('#pro-total').html(json['total_coutent']);
			}
			else{
				alert(json['message']);
			}
		}
	});	
	
}


function shippingMethod(){
	
	var cost=$(this).find("option:selected").attr('cost');
        var method = $(this).find("option:selected").text();
	var method_code =$(this).val();
	var pk=$(this).attr('pk');
	$.ajax({
		url: 'index.php?route=sale/order/addShippingTotal&token=<?php echo $token; ?>&cost='+cost+'&method='+method+'&pk='+pk,
		dataType: 'json',
		success: function(json) {
			if(json['message']){
				alert(json['message']);
			}
			else{
				$('#pro-total').html(json['total_coutent']);
			}
		}
	});	
}
    $('#order-save').click(function(){

         var messgae ='';

        if(!$('input[name=\'customer_email\']').val()){
            messgae +='请邮件地址\n';
        }
        if($('input[name=\'shipping_country_id\']').val() ==''){
            messgae +='请选择国家\n';
        }


         if(!$('input[name=\'shipping_postcode\']').val()){
            messgae +='请输入地址邮编\n';
         }
         if(!$('input[name=\'shipping_city\']').val()){
            messgae +='请输入城市\n';
         }
         if(!$('input[name=\'shipping_address_1\']').val()){
            messgae +='请输入地址\n';
         }
        if($('input[name^="order_product"]').size()==0){
            messgae +='请添加产品\n';
        }
         $('select[dom="shipping"]').each(function(){
             if($(this).val() ==""){
                 messgae +='请选择运费方式\n';
             }
         });
         if(messgae){
            alert(messgae);
            return false;
         }

        $('#form').submit();
    });

//--></script> 
<script type="text/javascript">
$('#button-product').click(function(){
	var order_id = $('#add_for_order').val();
	var product_sku =$(':input[name=product_sku]').val();
	var qty =$('#add_pro_qty').val();
	var price =$('#add_pro_price').val();
    var store_id = $(':input[name=store_id]').val();
	var curreny_code = $('#select_currency_code').val();
	var country_id =$("select[name='shipping_country_id']").val();
	$.ajax({
		url: 'index.php?route=sale/order/canShip&token=<?php echo $token;?>',
		type: 'post',
		data: 'product_sku=' + product_sku + '&country_id=' + country_id,
		dataType: 'json',
		success: function(json) {
			if(json['flag']==0){
				alert(json['msg']);
			}
			else{
					$.ajax({
						url: 'index.php?route=sale/order/addOrderProduct&token=<?php echo $token;?>',
						type: 'post',
						data: 'product_sku=' + product_sku + '&quantity=' + qty+'&price='+price+'&order_id='+order_id+"&store_id="+store_id+"&curreny_code="+curreny_code,
						dataType: 'json',
						success: function(json) {
							if(json['error']==0){
								$('#product').html(json['product_coutent']);
								$('#total').html(json['total_coutent']);
							}
							else{
								alert(json['message']);
							}
                            tab_total();
						}
					});
			}
		}
	});

})

function add_product_delete(add_product_id){
	$.ajax({
		url: 'index.php?route=sale/order/delOrderProduct&token=<?php echo $token;?>&',
		type: 'get',
		data: 'add_product_id=' + add_product_id,
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				$('#product').html(json['product_coutent']);
				$('#total').html(json['total_coutent']);
                tab_total();
			}
			else{
				alert(json['message']);
			}
		}
	});
}
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