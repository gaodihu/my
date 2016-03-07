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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
	  		<!-- <a href="<?php echo $join; ?>"   class="button">客户订单归并</a> -->
          <a onclick="$('#form').attr('action', '<?php echo $update_send; ?>'); $('#form').attr('target', '_self'); $('#form').submit();"  class="button">update send status</a>
           <a href="<?php echo $shippment; ?>"   class="button">upload shippment</a>
          <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
          <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
                <td></td>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="left">is send </td>
			  <td class="left">store</td>
              <td class="right"><?php if ($filter['sort'] == 'o.order_id') { ?>
                <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                <?php } ?></td>
			  <td class="left"><?php echo $column_order_number; ?>
			   </td>
              <td class="left"><?php if ($filter['sort'] == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_customer; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                <?php } ?></td>
				
			  
              <td class="left"><?php if ($filter['sort'] == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left">Payment Method</td>
	      <td class="right">Base Total</td>
              <td class="right"><?php if ($filter['sort'] == 'o.total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_total; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($filter['sort'] == 'o.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <!--
              <td class="left"><?php if ($filter['sort'] == 'o.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($filter['order']); ?>"><?php echo $column_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                <?php } ?></td>
              -->
              <td class="left"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
                        
			  <td align="left">
			  	<select name="filter_send_forecast_status" style='width:80px;'>
					<option value=></option>
					<?php if($filter['filter_send_forecast_status']==='0'){ ?>
					<option value="0"  selected="selected"  >Not Send</option>
					<?php }else{ ?>
					<option value="0" >Not Send</option>
					<?php } ?>
					<?php if($filter['filter_send_forecast_status']==1){ ?>
					<option value="1" selected="selected">Send</option>
					<?php } else{ ?>
					<option value="1" >Send</option>
					<?php } ?>
					<?php if($filter['filter_send_forecast_status']==2){ ?>
					<option value="2" selected="selected">Send&Purchased </option>
					<?php }else{ ?>
					<option value="2">Send&Purchased </option>
					<?php } ?>
				</select>
			  </td>
                          
			  <td><select name="filter_store_id" style="width:100px;">
					<option value="-99"></option>
					<?php foreach($store_array as $store_id =>$store){ ?>
					<?php if($filter['filter_store_id']==$store_id){ ?>
					<option value="<?php echo $store_id;?>"  selected="selected"  ><?php echo $store;?></option>
					<?php }else{ ?>
					<option value="<?php echo $store_id;?>"  ><?php echo $store;?></option>
					<?php } ?>
					<?php } ?>
					
				</select></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter['filter_order_id']; ?>" size="4" style="text-align: right;" /></td>
	      <td><input type="text" name="filter_order_number" value="<?php echo $filter['filter_order_number']; ?>" /></td>
              <td>
                  <span>name:<input type="text" name="filter_customer" value="<?php echo $filter['filter_customer']; ?>" /></span>
                  <br/>
                  <span>email:<input type="text" name="filter_email" value="<?php echo $filter['filter_email']; ?>"  /></span>
              
	      </td>
			 
              <td><select name="filter_order_status_id" style="width: 70px;">
                  <option value="*"></option>
                  <?php if ($filter['filter_order_status_id'] == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($filter['order_statuses'] as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter['filter_order_status_id']) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
                <td>
                    <select name="filter_payment_code">
                        <option value=""></option>
                        <?php foreach($filter_payment_code as $key => $item) { ?>
                        <option value="<?php echo $key; ?>" <?php if($current_filter_payment_code == $key) { ?>selected="selected"<?php } ?>><?php echo $key; ?></option>
                        <?php } ?>
                    </select>
                </td>
              <td align="right">Form<input type="text" name="filter_total_from" value="<?php echo $filter['filter_total_from']; ?>" size="4" style="text-align: right;" /><br />To<input type="text" name="filter_total_to" value="<?php echo $filter['filter_total_to']; ?>" size="4" style="text-align: right;" /></td>
			  <td></td>
              <td><input type="text" name="filter_date_added" value="<?php echo $filter['filter_date_added']; ?>" size="12" class="date" /></td>
              <!--<td><input type="text" name="filter_date_modified" value="<?php echo $filter['filter_date_modified']; ?>" size="12" class="date" /></td>-->
              <td align="left"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
               <td><?php if( is_array($order['children']) && count($order['children'])>0){ ?><span dom='box-show' class="box-show" style="font-size:15px;cursor: pointer;">+</span><?php } ?></td>
              <td style="text-align: center;">
                 <?php //if( is_array($order['children']) && count($order['children'])>0){ }else{ ?> 
                  
                  <?php if ($order['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" <?php if( is_array($order['children']) && count($order['children'])>0) { ?>dom="<?php echo $order['order_id']; ?>"<?php } ?> />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" <?php if( is_array($order['children']) && count($order['children'])>0) { ?>dom="<?php echo $order['order_id']; ?>"<?php } ?> />
                <?php } ?>
              <?php //} ?>
              </td>
			  <td class="left"><?php /*if( is_array($order['children']) && count($order['children'])>0){ ?><?php }else{ */ ?><?php echo $order['send_status']; ?><?php /* } */ ?></td>
			  <td class="left"><?php echo $order['store_from']; ?></td>
              <td class="right"><?php echo $order['order_id']; ?></td>
			  <td class="left"><?php echo $order['order_number']; ?></td>
              <td class="left"><?php echo $order['customer']; ?></td>
			 
			 
              <td class="left"><?php if( is_array($order['children']) && count($order['children'])>0){ ?><?php }else { ?><?php echo $order['status']; ?><?php } ?></td>
              <td class="left"><?php echo $order['payment_code']; ?></td>
	      <td class="right"><?php echo $order['base_total']; ?></td>
              <td class="right"><?php echo $order['currency_code']; ?><?php echo $order['total']; ?></td>
              <td class="left"><?php echo $order['date_added']; ?></td>
             <!-- <td class="left"><?php echo $order['date_modified']; ?></td>-->
              <td class="right" style="word-break: keep-all;white-space:nowrap;"><?php foreach ($order['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            
                <?php if($order['children'] && is_array($order['children'])){ ?>
                  <tr style="display:none">
                  <td colspan='13'>
                  <table width="100%"  cellpadding="0" cellspacing="0" border="0">
                    <?php foreach($order['children'] as $_c) { ?>
                    
                    <tr>
                        <td></td>
                        <td style="text-align: center;"><?php if ($order['selected']) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $_c['order_id']; ?>" checked="checked" dom="p-<?php echo $order['order_id']; ?>" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $_c['order_id']; ?>"   parent="<?php echo $order['order_id']; ?>"/>
                          <?php } ?></td>
                                    <td class="left"><?php echo $_c['send_status']; ?></td>
                                    <td class="left"><?php echo $_c['store_from']; ?></td>
                        <td class="right"><?php echo $_c['order_id']; ?></td>
			  <td class="left"><?php echo $_c['order_number']; ?></td>
                        <td class="left"><?php echo $_c['customer']; ?></td>
                                  

                        <td class="left"><?php echo $_c['status']; ?></td>
                        <td class="left"><?php echo $_c['payment_code']; ?></td>
                        <td class="right"><?php echo $_c['base_total']; ?></td>
                        <td class="right"><?php echo $_c['currency_code']; ?><?php echo $_c['total']; ?></td>
                        <td class="left"><?php echo $_c['date_added']; ?></td>
                       <!-- <td class="left"><?php echo $order['date_modified']; ?></td>-->
                        <td class="right" style="word-break: keep-all;white-space:nowrap;"><?php foreach ($_c['action'] as $action) { ?>
                          [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                          <?php } ?></td>
                      </tr>
                    <?php } ?>
                  </table>
                  </td>
                  </tr>
                <?php } ?>
            
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="13"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script>
    $("span[dom='box-show']").click(function(){
         $(this).parents("tr").next("tr").toggle();
         if($(this).text() == "+"){
             $(this).text("-");   
         }else{
             $(this).text("+");   
         }

     }) 
</script>    
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
	
	var filter_send_forecast_status = $('select[name=\'filter_send_forecast_status\']').attr('value');
	
	if (filter_send_forecast_status) {
		url += '&filter_send_forecast_status=' + encodeURIComponent(filter_send_forecast_status);
	}
	var filter_store_id = $('select[name=\'filter_store_id\']').attr('value');
	
	if (filter_store_id) {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}
	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	
	var filter_order_number = $('input[name=\'filter_order_number\']').attr('value');
	
	if (filter_order_number) {
		url += '&filter_order_number=' + encodeURIComponent(filter_order_number);
	}
	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	var filter_total_from = $('input[name=\'filter_total_from\']').attr('value');

	if (filter_total_from) {
		url += '&filter_total_from=' + encodeURIComponent(filter_total_from);
	}
	var filter_total_to = $('input[name=\'filter_total_to\']').attr('value');

	if (filter_total_to) {
		url += '&filter_total_to=' + encodeURIComponent(filter_total_to);
	}		
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
	
        var filter_payment_code = $(':input[name=\'filter_payment_code\']').val();
	
	if (filter_payment_code) {
		url += '&filter_payment_code=' + encodeURIComponent(filter_payment_code);
	}
        
	location = url;
}
//--></script>  
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$(":input[dom]").click(function(){
    var dom = $(this).attr('dom');
    if(dom){
        if($(this).attr("checked") == 'checked'){
            $(":input[parent='" + dom+ "']").attr('checked','checked');
        }else{
            $(":input[parent='" + dom+ "']").attr('checked',false);
        }
        
    }
});
//--></script> 
<?php echo $footer; ?>