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
      <h1><img src="view/image/customer.png" alt="" /> Customer Points</h1>
      <div class="buttons"><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button">Delete</a></div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <!--
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              -->
			  <td class="left"><?php if ($sort == 'c.customer_id') { ?>
                <a href="<?php echo $sort_customer_id; ?>" class="<?php echo strtolower($order); ?>">Customer Id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer_id; ?>">Customer Id</a>
                <?php } ?></td>
              <td class="left">Email</td>
              <td class="left"><?php if ($sort == 'order_number') { ?>
                <a href="<?php echo $sort_order_number; ?>" class="<?php echo strtolower($order); ?>">Order Number</a>
                <?php } else { ?>
                <a href="<?php echo $sort_order_number; ?>">Order Number</a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>">Status</a>
                <?php } ?></td>
              <td class="left">Accumulated points</td>
              <td class="left">Spent points</td>

             <td class="right">action</td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <!--<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>-->
              <td><input type="text" name="filter_customer_id" value="<?php echo $filter_customer_id; ?>" /></td>
              <td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" /></td>
              <td><input type="text" name="filter_order_number" value="<?php echo $filter_order_number; ?>" /></td>
              <td><select name="filter_status">
                  
                  <?php if ($filter_status===0) { ?>
				  <option value="-99">*</option>
                  <option value="0" selected="selected">Pending</option>
				  <option value="1">Available</option>
                  <?php } elseif($filter_status===1) { ?>
				  <option value="-99">*</option>
                  <option value="0" >Pending</option>
				  <option value="1" selected="selected">Available</option>
				  <?php } else{ ?>
				  <option value="-99" selected="selected">*</option>
                  <option value="0" >Pending</option>
				  <option value="1" >Available</option>
                  <?php } ?>
                  
                </select></td>
              <td></td>
              <td></td>
              <td align="right"><a onclick="filter();" class="button">Filter</a></td>
            </tr>
            <?php if ($customer_points) { ?>
            <?php foreach ($customer_points as $customer_point) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($customer_point['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_point['customer_reward_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_point['customer_reward_id']; ?>" />
                <?php } ?></td>
              <!--<td class="left"><?php echo $customer['name']; ?></td>-->
			  <td class="left"><?php echo $customer_point['customer_id']; ?></td>
              <td class="left">***<?php //echo $customer_point['email']; ?></td>
              <td class="left"><?php echo $customer_point['order_number']; ?></td>
              <td class="left"><?php echo $customer_point['status_name']; ?></td>
              <td class="left"><?php echo $customer_point['points']; ?></td>
              <td class="left"><?php echo $customer_point['points_spent']; ?></td>
			   <td class="left"></td>
              <!--<td class="right"><?php foreach ($customer['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>-->
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=sale/customer_point&token=<?php echo $token; ?>';
	
	var filter_customer_id = $('input[name=\'filter_customer_id\']').attr('value');
	
	if (filter_customer_id) {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	var filter_order_number = $('input[name=\'filter_order_number\']').attr('value');
	
	if (filter_order_number) {
		url += '&filter_order_number=' + encodeURIComponent(filter_order_number);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	
	
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?> 