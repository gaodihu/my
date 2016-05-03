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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="left">attribute code</td>
              <td class="left">attribute value</td>
              <td class="left">attribute group</td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
		   <tr class="filter">
              <td></td>
			  <td></td>
			  <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td></td>
             
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($option_attribute_groups) { ?>
            <?php foreach ($option_attribute_groups as $attribute) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($attribute['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $attribute['option_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $attribute['option_id']; ?>" />
                <?php } ?></td>
			  <td class="left"><?php echo $attribute['attribute_code']; ?></td>
              <td class="left"><?php echo $attribute['name']; ?></td>
              <td class="left">
			  	<?php foreach($attribute['to_group'] as $to_group){ ?>
				<?php if($to_group['status']){ ?>
				<input type="checkbox" name="option_to_group[]" value="<?php echo $to_group['attribute_group_id'];?>" checked="checked"/><?php echo $to_group['attribute_group_code'];?>
				<?php }else{ ?>
				<input type="checkbox" name="option_to_group[]" value="<?php echo $to_group['attribute_group_id'];?>" /><?php echo $to_group['attribute_group_code'];?>
				<?php } ?>
				<?php } ?>
			  
			  </td>
              <td class="right"><?php foreach ($attribute['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=catalog/option_attribute_group&token=<?php echo $token; ?>';
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	

	location = url;
}
//--></script>
<?php echo $footer; ?>