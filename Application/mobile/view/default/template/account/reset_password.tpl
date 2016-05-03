<?php echo $header; ?>

<div class="head-title">
    <a class="icon-angle-left left-btn"></a><?php echo $heading_title; ?>
</div>
<section class="wrap" style="height:500px; margin-top:15px;">
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
    <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="content">
      <table class="formbox">
        <tr>
          <td><?php echo $entry_new_password; ?></td>
          <td><input type="password" name="new_password" value="" /></td>
        </tr>
		<tr>
          <td><?php echo $entry_confim_new_password; ?></td>
          <td><input type="password" name="confim_new_password" value="" />
				 <input type='hidden' name='email' value="<?php echo $email;?>">	
		  </td>
        </tr>
		<tr>
          <td></td>
          <td><input type="submit"  value="submit" /></td>
        </tr>
      </table>
    </div>
    </div>
  </form>
</section>
<?php echo $footer; ?>