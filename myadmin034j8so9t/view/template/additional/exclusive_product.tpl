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
      <h1><img src="view/image/review.png" alt="" />渠道商品</h1>
	  <div class="buttons"><a href="<?php echo $upload;?>" class="button">批量上传</a><a href="<?php echo $download;?>" class="button">下载模板</a><a href="<?php echo $add_url;?>" class="button">增加</a>
	  	<a onclick="$('form').submit();" class="button">删除</a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="center"><?php if ($sort == 'pep_id') { ?>
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>">ID</a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'product_id') { ?>
                <a href="<?php echo $sort_product_id; ?>" class="<?php echo strtolower($order); ?>">product_id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_product_id; ?>">product_id</a>
                <?php } ?></td>
		
              <td class="center">Price</td>
             
              <td class="center">渠道URL</td>
			  <td class="center"><?php if ($sort == 'limit_number') { ?>
                <a href="<?php echo $sort_limit; ?>" class="<?php echo strtolower($order); ?>">限购数量</a>
                <?php } else { ?>
                <a href="<?php echo $sort_limit; ?>">限购数量</a>
                <?php } ?></td>
			  <td class="center"><?php if ($sort == 'start_time') { ?>
                <a href="<?php echo $sort_start_time; ?>" class="<?php echo strtolower($order); ?>">开始时间</a>
                <?php } else { ?>
                <a href="<?php echo $sort_start_time; ?>">开始时间</a>
                <?php } ?></td>
			  <td class="center"><?php if ($sort == 'end_time') { ?>
                <a href="<?php echo $sort_end_time; ?>" class="<?php echo strtolower($order); ?>">结束时间</a>
                <?php } else { ?>
                <a href="<?php echo $sort_end_time; ?>">结束时间</a>
                <?php } ?></td>
			  <td class="center">操作</td>
            </tr>
          </thead>
		  
          <tbody>
		  <tr class="filter">
              <td></td>
              <td></td>
              <td class="center"><input type="text" name="filter_product_id" value="<?php echo $filter['filter_product_id']; ?>" style="text-align: right;width:60px;"/></td>	
              <td></td>

              <td class="left"><input type="text" name="filter_from_url" value="<?php echo $filter['filter_from_url']; ?>" />(填写渠道url前的ID)</td>  
			  <td></td>
			  <td class="center"><input type="text" name="filter_start_time" value="<?php echo $filter['filter_start_time']; ?>" class="datetime "/></td>
			  <td class="center"><input type="text" name="filter_end_time" value="<?php echo $filter['filter_end_time']; ?>" class="datetime "/></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($exclusive_prices) { ?>
            <?php foreach ($exclusive_prices as $exclusive_price) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($exclusive_price['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $exclusive_price['id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $exclusive_price['id']; ?>" />
                <?php } ?></td>
			  <td class="center"><?php echo $exclusive_price['id']; ?></td>
              <td class="center"><?php echo $exclusive_price['product_id']; ?></td>
			  <td class="center"><?php echo $exclusive_price['price']; ?></td>
			  <td class="left"><?php echo $exclusive_price['from_url']; ?></td>
			  <td class="left"><?php echo $exclusive_price['limit_number']; ?></td>
			  <td class="center"><?php echo $exclusive_price['start_time']; ?></td>
			  <td class="center"><?php echo $exclusive_price['end_time']; ?></td>
			      
              <td class="center"><?php foreach ($exclusive_price['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8">无记录</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
 $('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
    });
function filter() {
	url = 'index.php?route=additional/exclusive_price&token=<?php echo $token; ?>';
	
	var filter_product_id = $('input[name=\'filter_product_id\']').attr('value');
	
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}
	
	var filter_from_url = $('input[name=\'filter_from_url\']').attr('value');
	
	if (filter_from_url) {
		url += '&filter_from_url=' + encodeURIComponent(filter_from_url);
	}
  var filter_start_time = $('input[name=\'filter_start_time\']').attr('value');
  
  if (filter_start_time) {
    url += '&filter_start_time=' + encodeURIComponent(filter_start_time);
  }
	var filter_end_time = $('input[name=\'filter_end_time\']').attr('value');
	
	if (filter_end_time) {
		url += '&filter_end_time=' + encodeURIComponent(filter_end_time);
	}

	location = url;
}
//--></script>
<?php echo $footer; ?>