<section class="product" >
            <div class="title"><?php echo $best_sellers;?></div>
            <ul class="con-box clearfix">
			<?php foreach ($products as $product) { ?>
                <li>
                    <a href="<?php echo $product['href'];?>">
                        <div class="product-img">
                            <img src="<?php echo $product['thumb'];?>" />
                        </div>
                        <div class="product-title">
                           <?php echo $product['name'];?>
                        </div>
                        <div class="product-cost">
							<?php if($product['special']){ ?>
                           
							 <b><?php echo $product['special'];?></b>
                             <del><?php echo $product['price'];?></del>
							<?php }else{ ?>
							<b><?php echo $product['price'];?></b>
							<?php } ?>
                        </div>
                    </a>
                </li>
		 <?php } ?>
            </ul>
 </section>
