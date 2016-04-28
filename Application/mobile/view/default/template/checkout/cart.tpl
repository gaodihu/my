<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?> (<span id='shopping_cart_qty'><?php echo $count_qty;?></span>)</div>
<section class="clearfix" >
		<?php if($products && is_array($products)>0) { ?>
			<div id="cart_content">
			<?php include_once(DIR_TEMPLATE . '/default/template/checkout/include/cart_product.tpl');?>
			</div>
			 
		 <div class="spacing"></div>
                    <div style="padding: 1em;text-align: center" class="checkout-btn">
                        <a  class="button orange-bg w70  setdisabled " id="payment" onclick="golink('<?php echo $this->url->link('payment/pp_express/start'); ?>','payment')"  >
                                        PayPal
                        </a>
                    </div>
                 
		<div style="padding: 1em;text-align: center" class="checkout-btn">
			<a class="button orange-bg w70 setdisabled"  id="checkout" onclick="golink('<?php echo $checkout;?>','checkout')" ><?php echo $text_checkout;?></a>
		</div>
		<?php if(!$logged) { ?>
				<div style="padding: 1em;text-align: center" class="checkout-btn">
					<a class="button orange-bg w70 setdisabled"  id="checkout_guest" onclick="golink('<?php echo $checkout_guest;?>','checkout_guest')"><?php echo $text_guest_checkout;?></a>
		</div>
		 
		 <?php } ?>
		<?php }else{ ?>
			<div class="cart-info cart-empty">
				<i class="icon-shopping-cart cartimg"></i>
				<div><?php echo $text_empty;?></div>
			</div>
			<div class="spacing"></div>
			<div style="padding: 1em;text-align: center" class="checkout-btn">
				<a class="button orange-bg" href="/"><?php echo $text_shipping;?></a>
			</div>
<?php } ?>
</section>

</div>

<script>
    function golink(href,id){
        if(common.setBtnLoad($('#'+id),$('#'+id))){
            return false;
        }
        window.location.href= href;

    }
    $(".coupon a").live('click', function(){
		$(".coupon-alert").toggle();
    })
	$('#apply_coupon').live('click', function(){
		var coupon = $(this).prev('input').val();
        Cart.validateCoupon(coupon, CartEvent.fresh_cart);
   })
    $('#Cancel_coupon').live('click', function(){
		var coupon = $(this).prev('input').val();
        Cart.cancelCoupon(coupon, CartEvent.fresh_cart);
   })
</script>
<?php echo $footer; ?>

