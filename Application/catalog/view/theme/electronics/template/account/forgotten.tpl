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

<section class="wrap" style="height:500px; margin-top:15px;" >
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
    <h2><?php echo $heading_title; ?></h2>
    <div class="formbox clearfix" >
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

            <h3><?php echo $text_your_email; ?></h3>
            <p><?php echo $text_email; ?></p>
            <div><?php echo $entry_email; ?><input type="text" name="email" value="" /></div>
            <div class="buttons" style="margin-top: 20px;">
              <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
              <div class="left" style="padding-left:150px;">
                <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
              </div>
            </div>
          </form>
   </div>
</section>

<?php echo $footer; ?>