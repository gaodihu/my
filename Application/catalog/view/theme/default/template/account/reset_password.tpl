<?php echo $header; ?>

<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){ ?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } else{ ?>
		<?php echo $breadcrumb['text']; ?>
		<?php	} ?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php } ?>
	</ul>
	</div>
	<div class="clear"></div>
</nav>
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