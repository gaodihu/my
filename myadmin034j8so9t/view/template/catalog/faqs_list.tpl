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
      <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'f.faq_id') { ?>
                <a href="<?php echo $sort_faq_id; ?>" class="<?php echo strtolower($order); ?>">faq id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_faq_id; ?>">faq_id</a>
                <?php } ?></td>
			  <td class="left"> <?php if ($sort == 'f.store_id') { ?>
                <a href="<?php echo $sort_store_id; ?>" class="<?php echo strtolower($order); ?>">store</a>
                <?php } else { ?>
                <a href="<?php echo $sort_store_id; ?>">store</a>
                <?php } ?> </td>
              <td class="left">
                sku
               </td>
               <td class="left">
                customer_email
               </td>
              <td class="left ">author</td>
              <td class="left"> faq text</td>
            
			<td class="left"><?php if ($sort == 'f.is_pass') { ?>
                <a href="<?php echo $sort_is_pass; ?>" class="<?php echo strtolower($order); ?>">is passed</a>
                <?php } else { ?>
                <a href="<?php echo $sort_is_pass; ?>">is passed</a>
                <?php } ?></td>
			<td class="left"><?php if ($sort == 'f.is_reply') { ?>
                <a href="<?php echo $sort_is_reply; ?>" class="<?php echo strtolower($order); ?>">is reply</a>
                <?php } else { ?>
                <a href="<?php echo $sort_is_reply; ?>">is reply</a>
                <?php } ?></td>
		      <td class="left"><?php if ($sort == 'f.add_time') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">add time</a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>">add time</a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
		  <tr class="filter">
              <td></td>
              <td class="left"><input type="text" name="filter_id" value="<?php echo $filter['filter_id']; ?>" style="width:40px;"/></td>
              <td left><input type="text" name="filter_store_id" value="<?php echo $filter['filter_store_id']; ?>" /></td>	
              <td class="left"><input type="text" name="filter_sku" value="<?php echo $filter['filter_sku']; ?>" /></td>
              <td class="left"><input type="text" name="filter_email" value="<?php echo $filter['filter_email']; ?>" style="width:60px;"/></td>
			        <td class="left"><input type="text" name="filter_author" value="<?php echo $filter['filter_author']; ?>" style="width:60px;"/></td>
			        <td style="width:200px;"></td>
              
              <td align="left"><input type="text" name="filter_pass" value="<?php echo $filter['filter_pass']; ?>" size="8"/></td>
              <td align="left"><input type="text" name="filter_reply" value="<?php echo $filter['filter_reply']; ?>" style="text-align: right;width:20px;" /></td>
			  
              <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($faqs) { ?>
            <?php foreach ($faqs as $faq) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($faq['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $faq['faq_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $faq['faq_id']; ?>" />
                <?php } ?></td>
			   
              <td class="left"><?php echo $faq['faq_id']; ?></td>
			        <td class="left"><?php echo $faq['store_code']; ?>(store_id:<?php echo $faq['store_id']; ?>)</td>
              <td class="left"><?php echo $faq['sku']; ?></td>
              <td class="left">*****</td>
              <td class="right"><?php echo $faq['author']; ?></td>
              <td class="left"><?php echo $faq['faq_text']; ?></td>
			  
			  <td class="left"><?php echo $faq['is_pass']; ?></td>
			  <td class="left"><?php echo $faq['is_reply']; ?></td>
              <td class="left"><?php echo $faq['add_time']; ?></td>
              <td class="right"><?php foreach ($faq['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=catalog/faqs&token=<?php echo $token; ?>';
	
	var filter_id = $('input[name=\'filter_id\']').attr('value');
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	
	var filter_sku = $('input[name=\'filter_sku\']').attr('value');
	
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
  var filter_email = $('input[name=\'filter_email\']').attr('value');
  
  if (filter_email) {
    url += '&filter_email=' + encodeURIComponent(filter_email);
  }
	var filter_author = $('input[name=\'filter_author\']').attr('value');
	
	if (filter_author) {
		url += '&filter_author=' + encodeURIComponent(filter_author);
	}
	
	var filter_store_id = $('input[name=\'filter_store_id\']').attr('value');
	
	if (filter_store_id) {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}
	
	var filter_pass = $('input[name=\'filter_pass\']').attr('value');
	
	if (filter_pass) {
		url += '&filter_pass=' + encodeURIComponent(filter_pass);
	}
	
	var filter_reply = $('input[name=\'filter_reply\']').attr('value');
	
	if (filter_reply) {
		url += '&filter_reply=' + encodeURIComponent(filter_reply);
	}

	location = url;
}
//--></script>
<?php echo $footer; ?>