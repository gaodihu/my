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
      <h1> 商品批量上传</h1>
      
    </div>
    <div class="content">
	 <div style="color:red; margin-bottom:10px;"><span>*</span>上传商品数据前请先上传要修改的图片文件夹，位置及命名规则如下：/upload/product/年月日-product-update-photo/ 如：20141010-product-update-photo</div>
   	 <div style="color:red; margin-bottom:10px;"><span>*</span>上传商品数据前确保字段名称保持一致</div>
	 <div style="color:red; margin-bottom:10px;"><span>*</span>单表数据不要超过1W条数据</div>
      <form action="<?php echo $upload; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="file"  name="uplaod_file"/>
		<input type="submit" value="提交" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>