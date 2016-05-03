<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> 订单销售数据</h1>
    </div>
    <div class="content">
      
      <table class="list">
        <thead>
          <tr>
            <td class="left">时间段</td>
            <td class="left">销售金额</td>
			<td class="left">订单商品总价格</td>
			<td class="left">订单商品总原价</td>
            <td class="right">总运费</td>
			<td class="right">订单毛利率</td>
			<td class="right">产品毛利率</td>
          </tr>
        </thead>
		<tbody>
          <?php if ($today_sale) { ?>
          <tr>
            <td class="left">今天</td>
            <td class="left">$<?php echo $today_sale['total']; ?></td>
			<td class="right">$<?php echo $today_sale['product_total']; ?></td>
			<td class="right">$<?php echo $today_sub_original?></td>
            <td class="right">$<?php echo $today_sale['total_shipping_cost']; ?></td>
			<td class="right"><?php echo $today_maoli; ?>%</td>
			<td class="right"><?php echo $today_product_maoli; ?>%</td>
          </tr>
          <?php } ?>
		   <?php if ($yestoday_sale) { ?>
          <tr>
            <td class="left">昨天</td>
            <td class="left">$<?php echo $yestoday_sale['total']; ?></td>
			<td class="right">$<?php echo $yestoday_sale['product_total']; ?></td>
			<td class="right">$<?php echo $yestoday_sub_original?></td>
            <td class="right">$<?php echo $yestoday_sale['total_shipping_cost']; ?></td>
			<td class="right"><?php echo $yestoday_maoli; ?>%</td>
			<td class="right"><?php echo $yestoday_product_maoli; ?>%</td>
          </tr>
          <?php } ?>
		 <?php if ($weeky_sale) { ?>
          <tr>
            <td class="left">过去7天</td>
            <td class="left">$<?php echo $weeky_sale['total']; ?></td>
			<td class="right">$<?php echo $weeky_sale['product_total']; ?></td>
			<td class="right">$<?php echo $weeky_sub_original?></td>
            <td class="right">$<?php echo $weeky_sale['total_shipping_cost']; ?></td>
			<td class="right"><?php echo $weeky_maoli; ?>%</td>
			<td class="right"><?php echo $weeky_product_maoli; ?>%</td>
          </tr>
          <?php } ?>
		  <?php if ($month_sale) { ?>
          <tr>
            <td class="left">月度数据</td>
            <td class="left">$<?php echo $month_sale['total']; ?></td>
			<td class="right">$<?php echo $month_sale['product_total']; ?></td>
			<td class="right">$<?php echo $month_sub_original; ?></td>
            <td class="right">$<?php echo $month_sale['total_shipping_cost']; ?></td>
			<td class="right"><?php echo $month_maoli; ?>%</td>
			<td class="right"><?php echo $month_product_maoli; ?>%</td>
          </tr>
          <?php } ?>
		  <?php if ($last_month_sale) { ?>
          <tr>
            <td class="left">上月月度数据</td>
            <td class="left">$<?php echo $last_month_sale['total']; ?></td>
			<td class="right">$<?php echo $last_month_sale['product_total']; ?></td>
			<td class="right">$<?php echo $last_month_sub_original; ?></td>
            <td class="right">$<?php echo $last_month_sale['total_shipping_cost']; ?></td>
			<td class="right"><?php echo $last_month_maoli; ?>%</td>
			<td class="right"><?php echo $last_month_product_maoli; ?>%</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
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