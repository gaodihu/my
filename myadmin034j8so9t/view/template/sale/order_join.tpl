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
      <h1> 匿名订单归并</h1>
      <div class="buttons"><a href="<?php echo $back;?>" class="button">返回</a></div>
    </div>
    <div class="content">
   	  
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        订单号：<input type="text"  name="order_number" value="<?php echo $order_number;?>"/><br /><br />
		emial：<input type="text"  name="email"  value="<?php echo $email;?>"/><br /><br />
		积分：<input type="text"  name="points"/>(不送积分这无需填写)<br /><br />
		<input type="submit" value="提交" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>