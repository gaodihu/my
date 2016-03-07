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
      <h1><img src="view/image/review.png" alt="" />上传渠道商品</h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">	
		<table class="form">
			<tr>
				<td>上传表单：</td>
				<td><input type="file"  name="uplaod_file" value="" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="确定" /></td>
				<td><input type="reset" value="取消" /></td>
			</tr>
			
        </table>
	</form>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>