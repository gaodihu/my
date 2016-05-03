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
      <h1>批量删除banner信息</h1>

    </div>
    <div class="content">
      <div>上传表格只需要banner_id一列数据即可</div>
      <form action="<?php echo $upload; ?>" method="post" enctype="multipart/form-data">
        <p style='color:red'>批量删除banner信息</p>
        <input type="file"  name="uplaod_add_file"/>
		    <input type="submit" value="批量删除banner信息" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>