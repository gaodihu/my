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
      <h1> 商品图片批量上传</h1>
      <div class="buttons"><a href="<?php echo $back;?>" class="button">返回</a></div>
    </div>
    <div class="content">
	 <div style="color:red; margin-bottom:10px;"><span>*</span>暂只支持ZIP压缩文件,请把商品文件夹压缩为ZIP压缩文件</div>	
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <input type="file"  name="uplaod_file"/>
		<input type="submit" value="提交" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>