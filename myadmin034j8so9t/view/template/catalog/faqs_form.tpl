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
      <h1><img src="view/image/review.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
			<tr>
            <td> customer_email:</td>
            <td><?php echo $customer_email; ?></td>
      </tr>
      <tr>
            <td><span class="required">*</span> store </td>
            <td>
				<select name="store_id">
					<option value="-1">.......</option>
					<?php if($store_id==0){ ?>
					<option value="0" selected="selected">default(en)</option>
					<?php }else{ ?>
					<option value="0">default(en)</option>
					<?php } ?>
					<?php foreach($stores as $store){ ?>
					<?php if($store['store_id'] == $store_id){ ?>
					<option value="<?php echo $store['store_id'];?>" selected="selected"><?php echo $store['name'];?></option>
					<?php } else{  ?>
					<option value="<?php echo $store['store_id'];?>"><?php echo $store['name'];?></option>
					<?php } ?>
					
					<?php } ?>
				</select>
			</td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_author; ?></td>
            <td><input type="text" name="author" value="<?php echo $author; ?>" />
              <?php if ($error_author) { ?>
              <span class="error"><?php echo $error_author; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_product; ?></td>
            <td><input type="text" name="product_id" value="<?php echo $product_id; ?>" />
              <?php if ($error_product) { ?>
              <span class="error"><?php echo $error_product; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td>title</td>
            <td><input type="text" name="title" value="<?php echo $title; ?>" />
              <?php if ($error_title) { ?>
              <span class="error"><?php echo $error_title; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_text; ?></td>
            <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
              <?php if ($error_text) { ?>
              <span class="error"><?php echo $error_text; ?></span>
              <?php } ?></td>
          </tr>
		  
          <tr>
            <td>is pass</td>
            <td><select name="is_pass">
                <?php if ($is_pass) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>