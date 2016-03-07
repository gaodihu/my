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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		  <tr>
            <td>attribute code:</td>
            <td>
			 <?php echo $attribute_code;?>
			</td>
          </tr>	
		  <tr>
            <td>attribute value:</td>
            <td>
			 <?php echo $attribute_value;?>
			</td>
          </tr>		
          <tr>
            <td><span class="required">*</span><?php echo $entry_attribute_group; ?></td>
            <td>
			 <?php foreach ($option_to_attribute_group__info as $option_group) { ?>
			 	<?php if($option_group['status']){ ?>
				<input type="checkbox" value="<?php echo $option_group['value_id']; ?>" name="option_group_id[]"  checked="checked"/><?php echo $option_group['attribute_group_code']; ?>
				<?php }else{ ?>
				<input type="checkbox" value="<?php echo $option_group['value_id']; ?>" name="option_group_id[]" /><?php echo $option_group['attribute_group_code']; ?>
				<?php } ?>
				 <?php } ?>
			</td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
