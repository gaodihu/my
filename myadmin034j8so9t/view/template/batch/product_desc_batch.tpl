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
      <h1> 商品描述板块批量修改</h1>
      <div class="buttons"><a href="<?php echo $download;?>" class="button">模板下载</a></div>
    </div>
    <div class="content">
		<div>1.描述内图片上传路径为/image/product_desc/,请把文件夹放入该路径下面</div>
      <form action="<?php echo $upload; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="file"  name="uplaod_file"/>
		<input type="submit" value="提交" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>