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
      <h1> 商品属性值批量处理(新增或者更改)</h1>
      <div class="buttons"><a href="<?php echo $download;?>" class="button">模板下载</a></div>
    </div>
    <div class="content">
   	  <div style="color:red; margin-bottom:10px;font-size:20px;"><span>*</span>程序会自动判断增加或者修改商品属性</div>
      <form action="<?php echo $upload_add; ?>" method="post" enctype="multipart/form-data">
        <p style='color:red'>编辑商品属性值</p>
        <input type="file"  name="uplaod_file"/>
		    <input type="submit" value="编辑商品属性值" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>