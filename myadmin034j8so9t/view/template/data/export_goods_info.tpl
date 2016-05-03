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
      <h1>导出商品信息</h1>

    </div>
    <div class="content">
      <div style="margin-bottom: 50px"><div class="buttons"><a href="<?php echo $export_goods_info;?>" class="button">导出商品信息</a></div></div>
        <div style="margin-bottom: 50px"><div class="buttons"><a href="<?php echo $export_attr_info;?>" class="button">导出属性信息</a></div></div>
        <div style="margin-bottom: 50px"><div class="buttons"><a href="<?php echo $export_guiji_info;?>" class="button">导出归集信息</a></div></div>
        <div style="margin-bottom: 50px"><div class="buttons"><a href="<?php echo $export_desc_info;?>" class="button">导出描述信息</a></div></div>
    </div>
  </div>
</div>
<?php echo $footer;?>