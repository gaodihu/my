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
      <h1>修改商品图片</h1>
      <div class="buttons"><a href="<?php echo $update_img_download;?>" class="button">批量修改模板下载</a></div>
      <div class="buttons"><a href="<?php echo $add_img_download;?>" class="button">批量增加模板下载</a></div>
    </div>

    <div class="content">
		<div style="color:red; margin-bottom:10px;"><span>*</span>上传商品数据前先上传商品图片,图片上传路径 /upload/product/</div>
		<div style="color:red; margin-bottom:10px;"><span>*</span>上传图片文件夹命名：年月日-product-update-photo:</div>
      <div>批量修改商品图片</div>
      <form action="<?php echo $update_img_upload; ?>" method="post" enctype="multipart/form-data">
        <p style='color:red'>批量修改商品图片</p>
        <input type="file"  name="update_file"/>
		    <input type="submit" value="批量修改商品图片" />
      </form>
        </br></br></br></br>
        <div>批量增加商品图片</div>
        <form action="<?php echo $add_img_upload; ?>" method="post" enctype="multipart/form-data">
            <p style='color:red'>批量增加商品图片</p>
            <input type="file"  name="add_file"/>
            <input type="submit" value="批量增加商品图片" />
        </form>
    </div>
  </div>
</div>
<?php echo $footer;?>