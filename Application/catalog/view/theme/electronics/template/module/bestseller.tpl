<div class="protit"><span class=" more right"><a href="<?php echo $more_link;?>"><?php echo $more;?> ></a></span>
    <h4><?php echo $best_sellers;?></h4></div>
<section class="prolist home_pro min-border ">

		<ul class="clearfix">
			<?php foreach ($products as $product) { ?>

			<li>
                <div class="p-relative">
				<?php if($product['special']){ ?>
				      <p class="offIcon"><span class="font20"><?php echo $product['save_rate'];?></span></p>
					<?php }else if($product['is_product_hot_label']) { ?>
					  <p class="offIcon hotIcon"></p>
					<?php } ?>

                 </div>
				<div class="procon">

					<div class="img">
                        <a href="<?php echo $product['href']; ?>">
                            <img src="<?php echo $product['thumb']; ?>" width="207" height="160" alt="<?php echo $product['name']; ?>"/></a>

                    </div>
					<a href="<?php echo $product['href']; ?>"><p class="tt"><?php echo $product['name']; ?></p></a>
					<?php if($product['special']){
					?>
					<p class="howmuch"><span class="xj"><?php echo $product['special']; ?></span><span class="yj"><?php echo $product['price']; ?></span></p>
					<?php
					}
					else{
					?>
					<p class="howmuch"><span class="xj"><?php echo $product['price']; ?></span></p>
					<?php
					}
					?>
					<?php if($product['as_low_as_price']){ ?>
                  	<p class="green"><?php echo $lower;?> <?php echo $product['as_low_as_price'];?></p>
				  	<?php } ?>


				</div>

			</li>
		<?php } ?>
		</ul>
	</section>
