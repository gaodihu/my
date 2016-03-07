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
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'product_id') { ?>
                <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>">product id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_product_id; ?>">product id</a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'type') { ?>
                <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>">type</a>
                <?php } else { ?>
                <a href="<?php echo $sort_type; ?>">type</a>
                <?php } ?></td>
				<td class="left">sku
                </td>
				<td class="left">name
                </td>
              <td class="left"><?php if ($sort == 'start_time') { ?>
                <a href="<?php echo $sort_start_time; ?>" class="<?php echo strtolower($order); ?>">start time</a>
                <?php } else { ?>
                <a href="<?php echo $sort_start_time; ?>">start time</a>
                <?php } ?></td>
				<td class="left"><?php if ($sort == 'end_time') { ?>
                <a href="<?php echo $sort_end_time; ?>" class="<?php echo strtolower($order); ?>">end time</a>
                <?php } else { ?>
                <a href="<?php echo $sort_end_time; ?>">end time</a>
                <?php } ?></td>
				<td class="left"><?php if ($sort == 'sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>">sort order</a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>">sort order</a>
                <?php } ?></td>
				<td class="left">action </td>
            </tr>
          </thead>
          <tbody>
		  <tr class="filter">
              <td></td>
			  <td><input type="text" value="" name="filter_product_id"></td>
              <td><input type="text" value="" name="filter_type" class="ui-autocomplete-input" autocomplete="off"></td>
              <td><input type="text" value="" name="filter_model"></td>
			  <td><input type="text" value="" name="filter_name" class="ui-autocomplete-input" autocomplete="off"></td>
              <td align="left">From<input type="text" size="8" value="" name="filter_start_time_from" class="datetime">
			  		To<input type="text" size="8" value="" name="filter_start_time_to" class="datetime">
			  </td>
              <td align="left">From<input type="text" style="text-align: right;" value="" name="filter_end_time_from" class="datetime">
			  To<input type="text" style="text-align: right;" value="" name="filter_end_time_to" class="datetime">
			  </td>
			  <td></td>
              <td align="right"><a class="button" onclick="filter();">Filter</a></td>
            </tr>
            <?php if ($home_products) { ?>
            <?php foreach ($home_products as $pro) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($pro['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $pro['rec_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $pro['rec_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $pro['product_id'];?></td>
			  <td class="left"><?php echo $pro['type']; ?>/(<?php echo $pro['type_name']; ?>)</td>
			  <td class="left"><?php echo $pro['model']; ?></td>
			  <td class="left"><?php echo $pro['name']; ?></td>
			  <td class="left"><?php echo $pro['start_time']; ?></td>
			  <td class="left"><?php echo $pro['end_time']; ?></td>
              <td class="left"><?php echo $pro['sort_order']; ?></td>
              <td class="right"><?php foreach ($pro['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8">this is no results!</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=module/home_product&token=<?php echo $token; ?>';
	
	var filter_product_id = $('input[name=\'filter_product_id\']').attr('value');
	
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_type = $('input[name=\'filter_type\']').attr('value');
	
	if (filter_type) {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}
	
	var filter_start_time_from = $('input[name=\'filter_start_time_from\']').attr('value');
	
	if (filter_start_time_from) {
		url += '&filter_start_time_from=' + encodeURIComponent(filter_start_time_from);
	}
	
	var filter_start_time_to = $('input[name=\'filter_start_time_to\']').attr('value');
	
	if (filter_start_time_to) {
		url += '&filter_start_time_to=' + encodeURIComponent(filter_start_time_to);
	}
	
	var filter_end_time_from = $('input[name=\'filter_end_time_from\']').attr('value');
	
	if (filter_end_time_from) {
		url += '&filter_end_time_from=' + encodeURIComponent(filter_end_time_from);
	}		
	var filter_end_time_to = $('input[name=\'filter_end_time_to\']').attr('value');
	
	if (filter_end_time_to) {
		url += '&filter_end_time_to=' + encodeURIComponent(filter_end_time_to);
	}	

	location = url;
}
//--></script>
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
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>