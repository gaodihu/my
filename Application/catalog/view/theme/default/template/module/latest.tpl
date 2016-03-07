
<div class="protit"><span class="more right"><a href="<?php echo $more_link;?>"><?php echo $more;?> ></a></span>
    <h4 ><?php echo $heading_title;?></h4></div>
<section class="prolist home_pro  min-border">
		<ul class="clearfix slist">
			<?php foreach ($products as $product) { ?>
			<li>
				<div class="p-relative">
				 <?php if($product['save_rate']){ ?>
				<p class="offIcon"><span class="font20"><?php echo $product['save_rate'];?></span></p>
					<?php }else if($product['is_product_hot_label']) { ?>
					<p class="offIcon hotIcon"></p>
					<?php } ?>

            	</div>
				<div class="procon">
					<div class="img"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" width="207" height="160" alt="<?php echo $product['name']; ?>"/></a>
                        <div class="img_Text"><div class="addthis_toolbox addthis_default_style addthis_16x16_style">
                                <a class="addthis_button_facebook  f" title="Facebook" href="#" ><span class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span class="at_a11y"></span></span></a>
                                <a class="addthis_button_twitter  n" title="Tweet" href="#" ><span class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span class="at_a11y"></span></span></a>
                                <a class="addthis_button_pinterest_share  tqq" target="_blank" title="Pinterest" href="#"><span class=" at300bs at15nc at15t_pinterest_share"><span class="at_a11y"></span></span></a>
                                <div class="atclear"></div>
                            </div>

                        </div>

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
