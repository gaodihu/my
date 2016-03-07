<?php echo $header; ?>
<?php if ($success) { ?>

<?php } ?>
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

<section class="box wrap clearfix">
	<?php echo $menu;?>
	<section class="boxRight">
		<?php echo $right_top;?>
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
        <div class="success"><?php echo $success; ?></div>
		<?php if($products){ ?>
        <section class="prolist prolist_info">
			<ul class="clearfix">
				<?php foreach($products as $product){ ?>
				<li>
					<!--<p class="offIcon"><span class="font20">50</span>%<br/>OFF</p>-->
					<div class="procon">
                        <div id="animation_<?php echo $product['product_id'];?>"></div>

						<div class="img"><a href="<?php echo $product['href'];?>" title="<?php echo $product['name'];?>"><img id="animation_img_<?php echo $product['product_id'];?>" src="<?php echo $product['thumb'];?>" width="207" height="160" alt="<?php echo $product['name'];?>"/></a><div class="img_Text"><a class="addre1" href="javascript:addToCart('<?php echo $product['product_id'];?> ')"></a><a class="addre2"  href="<?php echo $product['remove'];?>"></a></div></div>
						<p class="tt"><a href="<?php echo $product['href'];?>" title="<?php echo $product['name'];?>"><?php echo $product['name'];?></a></p>
						<?php if($product['special']){ ?>
						<p class="howmuch"><span class="xj"><?php echo $product['special'];?></span><span class="yj"><?php echo $product['price'];?></span></p>
						<?php } else{ ?>
						<p class="howmuch"><span class="xj"><?php echo $product['price'];?></span></p>
						<?php } ?>
						<?php if($product['as_low_as_price']){ ?>
						<p class="green"><?php echo $text_as_low_as;?> <?php echo $product['as_low_as_price'];?></p>
						<?php } ?>
						<p class="gray"><span class="star star-s<?php echo $product['rating'];?>"></span>(<?php echo $product['reviews'];?>)</p>
					</div>
				</li>
				<?php } ?>
			</ul>
		</section>
		
			<?php echo $pagination; ?>
	    
	  	<?php } else{ ?>
		<section class="account_table">
			<?php echo $text_empty;?>
		</section>	
		<?php } ?>
	   	
        <?php echo $right_bottom;?>
	</section>	
</section>
<?php echo $footer; ?>

