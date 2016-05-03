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
      <h1> 删除运费数据</h1>
      <div class="buttons"><a href="<?php echo $shipping_matrixrates;?>" class="button">返回</a></div>
    </div>
    <div class="content">
   	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	  	<table>
			<tr><td align="left">country_code:</td><td align="left"><input type="text"  name="country_code" style="width:600px;"/>(填写国家2字码，多个国家用逗号分隔)</td></tr>
			<tr height="10"></tr>
			<tr><td align="left">delivery_type:</td><td align="left"><input type="text"  name="delivery_type" style="width:600px;"/>(填写delivery_type，多个用逗号分隔)</td></tr>
			<tr height="10"></tr>
			<tr><td colspan="2"><input type="submit" value="提交" /></td></tr>
		</table>
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>