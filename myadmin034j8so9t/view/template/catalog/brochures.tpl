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
      <h1>商品质检报告</h1>
      <div class="buttons">
	  <a href="<?php echo $import; ?>" class="button">Import</a>
	  <a href="<?php echo $down_templete; ?>" class="button">Download Templete</a>
	  </div>
    </div>
    <div class="content">
     
        <table class="list">
          <tbody>
		  	<tr class="filter">
              <td>输入商品sku查看对应的质检报告文件：</td>
			  <td class="left"><input type="text" name="filter_sku" value="" /></td>
			 
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/brochures/update&token=<?php echo $token; ?>';
	
	var filter_sku = $('input[name=\'filter_sku\']').attr('value');
	
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
	
	

	location = url;
}
//--></script>
<?php echo $footer; ?>