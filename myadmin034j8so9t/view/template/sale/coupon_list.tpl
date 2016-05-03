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
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="document.getElementById('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="left"><?php if ($sort == 'c.coupon_id') { ?>
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">coupon id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>">coupon id</a>
               <?php } ?></td>
              <td class="left"><?php if ($sort == 'cd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.code') { ?>
                <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'c.discount') { ?>
                <a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.date_start') { ?>
                <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.date_end') { ?>
                <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
		  <tr class="filter">
              <td></td>
			  <td><input type="text" name="filter_id" value="<?php echo $filter['filter_id']; ?>" /></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter['filter_name']; ?>" /></td>
              <td><input type="text" name="filter_code" value="<?php echo $filter['filter_code']; ?>" /></td>
              <td></td>
              <td align="left">Form<input type="text" name="filter_start_from" value="<?php echo $filter['filter_start_from']; ?>" size="12" class="date" />
				To<input type="text" name="filter_start_to" value="<?php echo $filter['filter_start_to']; ?>" size="12" class="date" /></td>
			 <td align="left">Form<input type="text" name="filter_end_from" value="<?php echo $filter['filter_end_from']; ?>" size="12" class="date" />
				To<input type="text" name="filter_end_to" value="<?php echo $filter['filter_end_to']; ?>" size="12" class="date" /></td>
              <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($coupons) { ?>
            <?php foreach ($coupons as $coupon) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($coupon['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" />
                <?php } ?></td>
			  <td class="left"><?php echo $coupon['coupon_id']; ?></td>
              <td class="left"><?php echo $coupon['name']; ?></td>
              <td class="left"><?php echo $coupon['code']; ?></td>
              <td class="right"><?php echo $coupon['discount']; ?></td>
              <td class="left"><?php echo $coupon['date_start']; ?></td>
              <td class="left"><?php echo $coupon['date_end']; ?></td>
              <td class="left"><?php echo $coupon['status']; ?></td>
              <td class="right"><?php foreach ($coupon['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=sale/coupon&token=<?php echo $token; ?>';
	
	var filter_id = $('input[name=\'filter_id\']').attr('value');
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_code = $('input[name=\'filter_code\']').attr('value');
	
	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}
	var filter_start_from = $('input[name=\'filter_start_from\']').attr('value');
	
	if (filter_start_from) {
		url += '&filter_start_from=' + encodeURIComponent(filter_start_from);
	}
	
	var filter_start_to = $('select[name=\'filter_start_to\']').attr('value');
	
	if (filter_start_to) {
		url += '&filter_start_to=' + encodeURIComponent(filter_start_to);
	}	

	var filter_end_from = $('input[name=\'filter_end_from\']').attr('value');

	if (filter_end_from) {
		url += '&filter_end_from=' + encodeURIComponent(filter_end_from);
	}
	var filter_end_to = $('input[name=\'filter_end_to\']').attr('value');

	if (filter_end_to) {
		url += '&filter_end_to=' + encodeURIComponent(filter_end_to);
	}		

				
	location = url;
}
</script>  
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
<?php echo $footer; ?>