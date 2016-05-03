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
      <div class="buttons">
	  <a href="<?php echo $import; ?>" class="button">Import</a>
	  <a href="<?php echo $down_templete; ?>" class="button">Download Templete</a>
	  <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			 <td class="left"><?php if ($sort == 'pd.review_id') { ?>
                <a href="<?php echo $sort_review_id; ?>" class="<?php echo strtolower($order); ?>">review_id</a>
                <?php } else { ?>
                <a href="<?php echo $sort_review_id; ?>">review_id</a>
                <?php } ?></td>
			<td class="left"> store_id </td>
              <td class="left">SKU</td>
              <td class="left"><?php if ($sort == 'r.author') { ?>
                <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.rating') { ?>
                <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>

              <td class="left"><?php if ($sort == 'r.is_publish') { ?>
                <a href="<?php echo $sort_is_publish; ?>" class="<?php echo strtolower($order); ?>">publish</a>
                <?php } else { ?>
                <a href="<?php echo $sort_is_publish; ?>">publish</a>
                <?php } ?></td>

              <td class="left"><?php if ($sort == 'r.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
		  	<tr class="filter">
              <td></td>
			  <td class="left"><input type="text" name="filter_id" value="<?php echo $filter['filter_id']; ?>" /></td>
			  <td class="left"><input type="text" name="filter_store_id" value="<?php echo $filter['filter_store_id']; ?>" /></td>
              <td left><input type="text" name="filter_sku" value="<?php echo $filter['filter_sku']; ?>" /></td>	
			  <td class="left"><input type="text" name="filter_author" value="<?php echo $filter['filter_author']; ?>" /></td>
			  <td class="left"><input type="text" name="filter_rating" value="<?php echo $filter['filter_rating']; ?>" /></td>
              <td align="left">

                <select name="filter_status">
                  <option value="">All</option>
                  <option value="1" <?php if($filter['filter_status'] == 1) { echo 'selected="selected"';} ?>>Enabled</option>
                  <option value="0" <?php if(isset($filter['filter_status']) && $filter['filter_status'] == 0) { echo 'selected="selected"';} ?>>Disabled</option>
                </select>
                </td>
              <td align="left">
                <select name="filter_is_publish">
                  <option value="">All</option>
                  <option value="1" <?php if($filter['filter_is_publish'] == 1) { echo 'selected="selected"';} ?>>Enabled</option>
                  <option value="0" <?php if(isset($filter['filter_is_publish']) && $filter['filter_is_publish'] == 0) { echo 'selected="selected"';} ?>>Disabled</option>
                </select>
                </td>
              <td></td>
              <td align="right"><a  href="<?php echo $current_url;?>" class="button">unset filter</a><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($reviews) { ?>
            <?php foreach ($reviews as $review) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($review['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
                <?php } ?></td>
			  <td class="left"><?php echo $review['review_id']; ?></td>
			  <td class="left"><?php echo $review['store_code']; ?>(<?php echo $review['store_id'];?>)</td>
              <td class="left"><?php echo $review['sku']; ?></td>
              <td class="left"><?php echo $review['author']; ?></td>
              <td class="left"><?php echo $review['rating']; ?></td>
              <td class="left"><?php echo $review['status']; ?></td>
              <td class="left"><?php echo $review['is_publish']; ?></td>
              <td class="left"><?php echo $review['date_added']; ?></td>
              <td class="right"><?php foreach ($review['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=catalog/review&token=<?php echo $token; ?>';
	
	var filter_id = $('input[name=\'filter_id\']').attr('value');
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	var filter_store_id = $('input[name=\'filter_store_id\']').attr('value');
	
	if (filter_store_id) {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	}
	var filter_sku = $('input[name=\'filter_sku\']').attr('value');
	
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
	var filter_author = $('input[name=\'filter_author\']').attr('value');
	
	if (filter_author) {
		url += '&filter_author=' + encodeURIComponent(filter_author);
	}
	
	var filter_rating = $('input[name=\'filter_rating\']').attr('value');
	
	if (filter_rating) {
		url += '&filter_rating=' + encodeURIComponent(filter_rating);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_is_publish = $('select[name=\'filter_is_publish\']').attr('value');

  if (filter_is_publish) {
    url += '&filter_is_publish=' + encodeURIComponent(filter_is_publish);
  }

	location = url;
}
//--></script>
<?php echo $footer; ?>