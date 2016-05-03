<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php	
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class='wrap'>
	<h1><?php echo $heading_title; ?></h1>
  <div class="message"> <?php echo $suceess_message;?></div>
  <div class="continue">
    <div ><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
</section>
<?php echo $footer; ?>