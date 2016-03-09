<section class="flexslider banner">
	  <ul class="slides">
	  <?php foreach ($banners as $banner) { ?>
	  <?php if ($banner['link']) { ?>
	  <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" width="772" height="320" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
	  <?php } else { ?>
	  <li><img src="<?php echo $banner['image']; ?>" width="772" height="320" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></li>
	  <?php } ?>
	  <?php } ?>
	    
	  </ul>
	</section>
	<section class="gg_right">
		<figure class="gg_img gg_img2"><a href="#"><img src="<?php  echo STATIC_SERVER; ?>css/images/gg1.jpg" alt=""/></a></figure>
		<figure class="gg_img gg_img2"><a href="#"><img src="<?php  echo STATIC_SERVER; ?>css/images/gg2.jpg" alt=""/></a></figure>
	</section>
	<div class="clear"></div>