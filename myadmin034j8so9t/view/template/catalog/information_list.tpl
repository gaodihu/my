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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			     <td class="left"><?php if ($sort == 'i.information_id') { ?>
                <a href="<?php echo $sort_information_id; ?>" class="<?php echo strtolower($order); ?>">id </a>
                <?php } else { ?>
                <a href="<?php echo $sort_information_id; ?>">id</a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'id.title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                <?php } ?></td>
			  <td class="left"><?php if ($sort == 'i.information_group_id') { ?>
                <a href="<?php echo $sort_information_group_id; ?>" class="<?php echo strtolower($order); ?>">所属分类</a>
                <?php } else { ?>
                <a href="<?php echo $sort_information_group_id; ?>">所属分类</a>
                <?php } ?></td>	
              <td class="right"><?php if ($sort == 'i.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
		   <tr class="filter">
              <td></td>
			  <td class="left"><input type="text" name="filter_id" value="<?php echo $filter['filter_id']; ?>" /></td>
              <td left><input type="text" name="filter_name" value="<?php echo $filter['filter_name']; ?>" /></td>	
              <td class="left"><input type="text" name="filter_information_group" value="<?php echo $filter['filter_information_group']; ?>" /></td>
			  <td></td>
              <td align="right"><a  href="<?php echo $current_url;?>" class="button">unset filter</a><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($informations) { ?>
            <?php foreach ($informations as $information) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($information['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" />
                <?php } ?></td>
			  <td class="left"><?php echo $information['information_id']; ?></td>		
              <td class="left"><?php echo $information['title']; ?></td>
			  <td class="left"><?php echo $information['information_group_code']; ?>(<?php echo $information['information_group_id'];?>)</td>
              <td class="right"><?php echo $information['sort_order']; ?></td>
              <td class="right"><?php foreach ($information['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=catalog/information&token=<?php echo $token; ?>';
	
	var filter_id = $('input[name=\'filter_id\']').attr('value');
	
	if (filter_id) {
		url += '&filter_id=' + encodeURIComponent(filter_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_information_group = $('input[name=\'filter_information_group\']').attr('value');
	
	if (filter_information_group) {
		url += '&filter_information_group=' + encodeURIComponent(filter_information_group);
	}
	
	
	location = url;
}
//--></script>
<?php echo $footer; ?>