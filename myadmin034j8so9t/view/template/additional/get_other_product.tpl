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
      <h1><img src="view/image/review.png" alt="" /> 得到新品</h1>
      <div class="buttons"><a onclick="$('form').submit();" class="button">删除</a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'np.new_pro_id') { ?>
                <a href="<?php echo $sort_new_pro_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                <?php } else { ?>
                <a href="<?php echo $sort_new_pro_id; ?>">ID</a>
                <?php } ?></td>
			         <td class="left">language</td>
              <td class="left"> user_name</td>
              <td class="left">
                email
               </td>
              <td class="left ">product_name</td>
              <td class="left">color</td>
              <td class="left">img</td>
              <td class="left">base_price</td>
			  <td class="left">currency_price</td>
              <td class="left">url_link</td>
              <td class="left">shipment</td>
              <td class="left">status</td>
              <td class="left">email_send</td>
		      <td class="left"><?php if ($sort == 'np.created_at') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">add time</a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>">add time</a>
                <?php } ?></td>
              <td class="right">操作</td>
            </tr>
          </thead>
          <tbody>
		  <tr class="filter">
              <td></td>
              <td></td>
              <td left><input type="text" name="filter_language_code" value="<?php echo $filter['filter_language_code']; ?>" style="text-align: right;width:60px;"/></td>	
              <td></td>
              <td left><input type="text" name="filter_email" value="<?php echo $filter['filter_email']; ?>" /></td>  
			  <td></td>
			  <td></td>
			  <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td align="left"><input type="text" name="filter_status" value="<?php echo $filter['filter_status']; ?>" size="8"/></td>
              <td align="left"><input type="text" name="filter_email_send" value="<?php echo $filter['filter_email_send']; ?>" style="text-align: right;width:40px;" /></td>
			  
              <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($other_products) { ?>
            <?php foreach ($other_products as $other_product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($other_product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $other_product['new_pro_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $other_product['new_pro_id']; ?>" />
                <?php } ?></td>
			   
              <td class="left"><?php echo $other_product['new_pro_id']; ?></td>
			        <td class="left"><?php echo $other_product['language_code']; ?></td>
              <td class="left"><?php echo $other_product['user_name']; ?></td>
              <td class="left">*****</td>
              <td class="right"><?php echo $other_product['product_name']; ?></td>
              <td class="left"><?php echo $other_product['product_color']; ?></td>
              <td class="left"><a target="_blank" href="<?php echo $other_product['big_image']; ?>"><img src="<?php echo $other_product['product_img']; ?>"></a></td>
              <td class="left"><?php echo $other_product['base_price']; ?></td>
			  <td class="left"><?php echo $other_product['price']; ?></td>
              <td class="left"><?php echo $other_product['url_link']; ?></td>
              <td class="left"><?php echo $other_product['shipment']; ?></td>
              <td class="left"><?php echo $other_product['status']; ?></td>
              <td class="left"><?php echo $other_product['email_send']; ?></td>
              <td class="left"><?php echo $other_product['created_at']; ?></td>
              <td class="right"><?php foreach ($other_product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10">无记录</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=promotion/get_other_product&token=<?php echo $token; ?>';
	
	var filter_language_code = $('input[name=\'filter_language_code\']').attr('value');
	
	if (filter_language_code) {
		url += '&filter_language_code=' + encodeURIComponent(filter_language_code);
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
  var filter_status = $('input[name=\'filter_status\']').attr('value');
  
  if (filter_status) {
    url += '&filter_email=' + encodeURIComponent(filter_status);
  }
	var filter_email_send = $('input[name=\'filter_email_send\']').attr('value');
	
	if (filter_email_send) {
		url += '&filter_author=' + encodeURIComponent(filter_email_send);
	}

	location = url;
}
//--></script>
<?php echo $footer; ?>