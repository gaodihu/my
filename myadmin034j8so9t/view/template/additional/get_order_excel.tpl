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
      <h1><img src="view/image/review.png" alt="" /> 下载订单表格</h1>
    </div>
    <div class="content">
	<div style=" font-size:16px; color:red; padding-bottom:30px;">**以下下载的都是当天的订单表格</div>
    <div style=" font-size:16px; color:red; padding-bottom:30px; padding-top:20px;">今日0点订单表格</div> 
	<p><a href="<?php echo $action_sales_order_0;?>">下载0点sales_order订单表格</a></p>
	<p><a href="<?php echo $action_purchase_order_0;?>">下载0点 purchase_order订单表格</a></p>
	<p><a href="<?php echo $action_detail_sales_0;?>">下载0点detail_sales_order订单表格</a></p>
	
	<div style=" font-size:16px; color:red; padding-bottom:30px;">今日11点订单表格</div> 
	<p><a href="<?php echo $action_sales_order_11;?>">下载11点sales_order订单表格</a></p>
	<p><a href="<?php echo $action_purchase_order_11;?>">下载11点 purchase_order订单表格</a></p>
	<p><a href="<?php echo $action_detail_sales_11;?>">下载11点detail_sales_order订单表格</a></p>				
        
		
    </div>
  </div>
</div>
<?php echo $footer; ?>