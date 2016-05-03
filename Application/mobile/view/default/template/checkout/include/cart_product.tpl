
 <ul class="cart-list">
        <?php foreach($products as $product){ ?>
        <li class="clearfix" id="li_<?php echo $product['product_id']; ?>">
            <div class="img"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" width="207"></a></div>
            <div class="p-info">
                <p class="p-tit"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></p>
                <p><b><span class="price" rel="1.29" ><?php echo $this->currency->onlyFormat($product['price']);?></span></b><p>
                <p class="clearfix">
                    <span class="quantity">
                        <a class="minus" dom="<?php echo $product['product_id']; ?>" onclick="CartEvent.minusQuantity('<?php echo $product['product_id'];?>');"><i class="icon-minus"></i></a>
                        <input type="text" value="<?php echo $product['quantity'];?>" id="num_<?php echo $product['product_id'];?>" class="num" onchange="CartEvent.totalQuantity('<?php echo $product['product_id'];?>');">
                        <a class="plus" dom="<?php echo $product['product_id']; ?>" onclick="CartEvent.plusQuantity('<?php echo $product['product_id'];?>');"><i class=" icon-plus"></i></a>
                    </span>
					<?php if($product['is_wishlist']){ ?>
                    <a class="p-heart red-color"><i class="icon-heart" onclick="addToWishList('<?php echo $product['product_id']; ?>')"></i></a>
					<?php }else{ ?>
					<a class="p-heart"><i class="icon-heart" onclick="addToWishList('<?php echo $product['product_id']; ?>')"></i></a>
					<?php } ?>
                    <a class="del" onclick="CartEvent.delCart(<?php echo $product['product_id']; ?>)" id="del1"><i class="icon-remove-circle"></i></a>
                <p>
            </div>
        </li>
        <?php } ?>
    </ul>
    
    
    <?php foreach($totals as $total){ ?>
             
             <div class="total"><?php echo $total['title'];?>: <b> <?php echo $currency_code;?>  <span class="total-text"  id='cart_total' ><?php echo $total['text'];?></span></b></div>
             
    <?php } ?>

<div class="text-btn-box">
    <div class="coupon m-b10 text-r"><a class="blue"><?php echo $text_have_coupon;?></a></div>
    <div class="coupon-alert">
            <i class="triangle"></i>
            <?php if(isset($coupon_code)){ ?>
            <input type="text" value="<?php echo $coupon_code;?>" class="text"><input type="button" value="Cancel" class="btn orange-bg" id='Cancel_coupon'>
            <?php }else{ ?>
            <input type="text" value="" class="text"><input type="button" value="<?php echo $text_apply; ?>" class="btn orange-bg" id='apply_coupon'>
            <?php } ?>

    </div>
</div>








