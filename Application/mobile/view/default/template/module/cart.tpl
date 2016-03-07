<?php if(!$is_ajax) { ?>
<a class="cart-title" href="<?php echo $cart; ?>" rel="nofollow">
    <i class="cart-icon"></i>
    <?php echo $text_my_cart;?> ( <span class="cart-number red" id='cart-total'><?php echo $cart_totals_num;?></span> )
    <i class="arrow"></i>

    <p class="much"><?php echo $text_subtal;?>: <span id='cart-price-total'><?php echo $cart_totals_price;?></span></p>
</a>
<ul class="sub-cart" style="display:none" id="cart-list">
<?php } ?>
    <?php if(!$products){  ?>
    <li class="empty-cart">
        <div class="cart_icon"></div>
        <?php if($guest){
			echo $text_empty;
		}else{
			echo $text_empty_customer;
		}
		?>
    </li>
    <?php }else{ ?>

    <li class="minicart">
        <dl>
            <!--<dt>Recently added <em class="added">1</em> items</dt>-->
            <?php foreach ($products as $product) {

								?>
            <dd class="last">

                <a class="pic" href="<?php echo $product['href'];?>"><img src="<?php echo $product['thumb'];?>"
                                                                          height="60" width="60"></a>
                <a class="title" href="<?php echo $product['href'];?>" title="myled"><?php echo $product['name'];?></a>
									  <span class="price">
                                          <a class="delete"
                                             href="javascript:deleteCart('<?php echo $product['product_id'];?>');"></a>
                                          <?php echo $product['price'];?><b class="quantity">
                                              x<?php echo $product['quantity'];?></b>
                                      </span>

            </dd>
            <?php
									}
								  ?>
        </dl>
        <p><a href="<?php echo $cart; ?>" class="view-cart"><?php echo $text_cart;?></a></p>
    </li>
    <?php } ?>
    
<?php if(!$is_ajax) { ?>
</ul>
<?php } ?>
