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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span>information group name</td>
            <td>
			<?php foreach($languages as $language){ ?>
			<img src="view/image/flags/<?php echo $language['image'];?>" /><input type="text" name="information_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($information_group_description[$language['language_id']]) ? $information_group_description[$language['language_id']]['name'] : ''; ?>" />
			<?php } ?>
			</td>
          </tr>
		  <tr>
            <td><span class="required">*</span>information group code</td>
            <td><input type="text" name="information_group_code" value="<?php echo $information_group_code; ?>"/></td>
          </tr>
		  <tr>
            <td>status</td>
            <td>
				<select name="status">
					<option value="1">Enable</option>
					<option value="0">Disable</option>
				</select>
			</td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>