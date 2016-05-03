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
      <h1>导出sku销售数量信息</h1>

    </div>
    <div class="content">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <p>(时间格式为2015-06-24)</p>
        起始时间：<input type="text" value="" name="start_time"><br>
        结束时间：<input type="text" value="" name="end_time"><br>
        <input type="submit" value="submit">
      </form>
    </div>
  </div>
</div>
<?php echo $footer;?>