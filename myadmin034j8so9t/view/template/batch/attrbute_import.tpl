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
      <h1> 编辑属性(新增或者更改)</h1>
      <div class="buttons"><a href="<?php echo $download_add;?>" class="button">新增模板下载</a><a href="<?php echo $download_update;?>" class="button">更改模板下载</a></div>
    </div>
    <div class="content">
   	  <div style="color:red; margin-bottom:10px;font-size:20px;"><span>*</span>请正确选择对应的操作</div>
      <form action="<?php echo $upload_add; ?>" method="post" enctype="multipart/form-data">
        <p style='color:red'>增加属性值</p>
        <input type="file"  name="uplaod_add_file"/>
		    <input type="submit" value="增加属性值" />
        
      </form>
      
      <form action="<?php echo $upload_update; ?>" method="post" enctype="multipart/form-data" style='padding-top:70px;'>
         <p style='color:red'>更改属性值</p>
        <input type="file"  name="uplaod_update_file"/>
        <input type="submit" value="编辑属性值" />
        
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>