<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td>开始时间
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="20" class="datetime"/></td>
          <td>结束时间
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="20" class="datetime"/></td>
          <td style="text-align: right;"><a onclick="filter();" class="button">Filter</a></td>
		  <td style="text-align: right;"><a href="<?php echo $export;?>" class="button">导出表格</a></td>
        </tr>
      </table>
	  
	  <table class="list">
        <thead>
          <tr>
            <td class="left">product_id</td>
            <td class="left">sku</td>
            <td class="right">en_sales<br>(www销售总量)</td>
            <td class="right">en_order_num<br>(www订单频次)</td>
			<td class="right">de_sales</td>
			<td class="right">de_order_num</td>
			<td class="right">es_sales</td>
			<td class="right">es_order_num</td>
			<td class="right">fr_sales</td>
			<td class="right">fr_order_num</td>
			<td class="right">it_sales</td>
			<td class="right">it_order_num</td>
			<td class="right">pt_sales</td>
			<td class="right">pt_order_num</td>

          </tr>
        </thead>
		<tbody>
          <?php if ($product_sale_info) { ?>
       	  <?php foreach($product_sale_info as $info){ ?>
          <tr>
            <td class="left"><?php echo $info['product_id'];?></td>
            <td class="left"><?php echo $info['model'];?></td>
            <td class="right"><?php echo isset($info['0']['order_product_count'])?$info['0']['order_product_count']:0;?></td>
            <td class="right"><?php echo isset($info['0']['order_count'])?$info['0']['order_count']:0;?></td>
			<td class="right"><?php echo isset($info['52']['order_product_count'])?$info['52']['order_product_count']:0;?></td>
			<td class="right"><?php echo isset($info['52']['order_count'])?$info['52']['order_count']:0;?></td>
			<td class="right"><?php echo isset($info['53']['order_product_count'])?$info['53']['order_product_count']:0;?></td>
			<td class="right"><?php echo isset($info['53']['order_count'])?$info['53']['order_count']:0;?></td>
			<td class="right"><?php echo isset($info['54']['order_product_count'])?$info['54']['order_product_count']:0;?></td>
			<td class="right"><?php echo isset($info['54']['order_count'])?$info['54']['order_count']:0;?></td>
			<td class="right"><?php echo isset($info['55']['order_product_count'])?$info['55']['order_product_count']:0;?></td>
			<td class="right"><?php echo isset($info['55']['order_count'])?$info['55']['order_count']:0;?></td>
			<td class="right"><?php echo isset($info['56']['order_product_count'])?$info['56']['order_product_count']:0;?></td>
			<td class="right"><?php echo isset($info['56']['order_count'])?$info['56']['order_count']:0;?></td>
          </tr>
		  <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="14"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
        
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/product_sale_data&token=<?php echo $token; ?>';
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	location = url;
}
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
  $('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
    });
});
//--></script> 
<?php echo $footer; ?>