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
<section class="box wrap clearfix">
	<aside class="boxLeft left">
		<section class="border">
			<div class="helph">Help Center</div>
            <div class="Account_nav">
            	<ul>
					<?php foreach($help_center_info as $hlep_center){ ?>
					<?php if($hlep_center['information_id']==$information_id){ ?>
					<li><a href="<?php echo $hlep_center['href'];?>" class="active"><?php echo $hlep_center['title'];?></a></li>
					<?php }else{ ?>
					<li><a href="<?php echo $hlep_center['href'];?>"><?php echo $hlep_center['title'];?></a></li>
					<?php } ?>
					
					<?php } ?>
                	
                   
                </ul>
            </div>
		</section>
	</aside>
	<section class="boxRight">
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
        <section class="aboutus">
         <?php echo $description;?>
        </section>
	</section>	
</section>
<?php echo $footer; ?>