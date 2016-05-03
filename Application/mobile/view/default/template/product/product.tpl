<?php echo $header; ?>
<div class="head-title">
        <a class="icon-angle-left left-btn"></a><?php echo $text_product_details;?>
 </div>
 <?php if($success){ ?>
 <div class="msg-info" style="color:red"><?php echo $success;?></div>
 <?php } ?>
   <!-- product -->
		<div class="product-bg">
        <div class="product-img">
            <div id="slider" class="swipe">
                <div class="swipe-wrap">
					<?php foreach($images as $image){ ?>
                    <figure>
                        <div class="wrap">
                            <img src="<?php echo $image;?>"  width="600" />
                        </div>
                    </figure>
					<?php } ?>
                    
                </div>
            </div>
            <nav>
                <div id="position" class="position">
				 <?php foreach($images as $key=>$image){ ?>
					<?php if($key==0){ ?>
                    <i class="on"></i>
					<?php }else{ ?>
					<i class=""></i>
					<?php } ?>
				<?php } ?>
                </div>
            </nav>
        </div>
		</div>
        <div class="product-info">
            <h4><?php echo $product_info['name'];?></h4>
            <p class="sku">SKU:<?php echo $product_info['model'];?></p>
            <p>
				<?php if($product_info['special']){ ?>
				<b><?php echo $format_special;?></b>
				<del><?php echo $format_price;?></del>
				<?php }else{ ?>
				<b><?php echo $format_price;?></b>  <span class="more-price m-l20 blue" onclick="changeImg()"><?php echo $text_more_price;?><i class="icon-plus-sign" id="plus-sign"></i></span>
				<?php } ?>
			</p>
			<?php if($discounts){
				?>
				<?php if(!$special_price||($special_price&& $special_price > $discount_low_price)){ ?>
				<div class="product-more" style="display: none">
					<p class="bold title"><?php echo $text_buy_more;?></p>
					<table border="0" cellpadding="0" cellspacing="0" >
						<tr>
							<th><?php echo $text_quantity;?></th>
							<?php foreach($discounts['qty'] as $qty){
							?>
							<th><?php echo $qty['quantity'];?></th>
							<?php
							}
							?>
							
						</tr>
						<tr>
							<td><?php echo $text_price;?></td>
							<?php foreach($discounts['price'] as $price){
							?>
							<td><?php echo $price['price'];?></td>
							<?php
							}
							?>
							
						</tr>
					</table>
				</div>
				<?php } ?>
				<?php
				}
				?>
			<?php if($is_wishlist){ ?>
            <a class="p-heart red-bg" onclick="addToWishList('<?php echo $product_info['product_id'];?>')"><i class=" icon-heart-empty"></i></a>
			<?php }else{ ?>
			<a class="p-heart" onclick="addToWishList('<?php echo $product_info['product_id'];?>')"><i class=" icon-heart-empty"></i></a>
			<?php } ?>
			<?php if($attr_filter){ ?>
				<div  class="boerderline">
				<?php foreach($attr_filter as $attr){ ?>
					<div class="change-box" attr_id="<?php echo $attr['attr_id'];?>">
						<span class="span-label" <?php if (strlen($attr['attr_name']) < 14) { echo "style='line-height:24px;'"; } ?> ><?php echo $attr['attr_name'];?>:</span>
                        <div class="right-info-block">
						<?php foreach($attr['attr_option_info'] as $option_info){ ?>
						 <?php if(isset($product_attr_filter[$attr['attr_id']]['option_id'])&&$product_attr_filter[$attr['attr_id']]['option_id']==$option_info['value_id']){ ?>		
						<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr pro_attr_selected'><?php echo $option_info['option_value'];?></span>
						<?php }else{ ?>
							<?php if($option_info['able']){ ?>
							<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr'><a href="<?php echo $option_info['href'];?>"><?php echo $option_info['option_value'];?></a></span>
							<?php }else{ ?>
								<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr disabled'><a href="<?php echo $option_info['href'];?>"><?php echo $option_info['option_value'];?></a></span>
                        <?php } ?>
						<?php } ?>
						<?php } ?>
                        </div>
					</div>
				<?php } ?>
					
				</div>
                <?php } ?>
			
			<div class="product-operation">
			<form name="add_cart" action="" method="post" autocomplete="off" class="inlineblock" style="width:100%;">
				<div class="product-quantity infor_pro" >
					<div class="product-alert min-alert-bg"  style="display:none" id="product-tips"></div>
					
					<?php if($is_battery) { ?>
					<div class="countinput clearfix mt_20" >
						<div class="clear"></div>
						<label class="title"><?php echo $text_ship_to_country;?>:</label>
						<select name="ship_to_country" id="ship_to" class="left list-select" >
							<option value="" ><?php echo $text_please_select;?></option>
							<?php foreach($countries as $country){ ?>
								<?php if($ship_to_country_code==$country['iso_code_2']){ ?>
									<option value="<?php echo $country['iso_code_2'];?>" selected="selected"><?php echo $country['name'];?></option>
								<?php }else{ ?>
									<option value="<?php echo $country['iso_code_2'];?>"><?php echo $country['name'];?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
			<?php } ?>

            <div class="fixed-btn clearfix m-t20">
                <?php if($product_info['stock_status_id']==7){ ?>
                <a class="red-bg-btn red-bg" href="javascript:addToCart('<?php echo $product_info['product_id'];?>');" ><?php echo $button_cart;?></a>
                <?php }else{ ?>
                <a class="grey-bg-btn" href="javascript:;" ><?php echo $button_cart;?></a>
                <?php } ?>
                <a class="orange-bg"  href="javascript:addToCart('<?php echo $product_info['product_id'];?>',1,2);" ><i class="icon-shopping-cart" ></i> <?php echo $button_buy_now;?></a>
            </div>
        </div>



    </div>
        </div>
            <section class="tab-box a-block">

                <ul class="tab-chagne">
                    <li><a href="<?php echo $desc_info_more;?>"><i class="icon-caret-right"></i><?php echo $text_description;?></a></li>
                    <li><a  href="<?php echo $review_info_more;?>"><i class="icon-caret-right"></i><?php echo $text_reviews;?>(<?php echo $review_total;?>)</a></li>
                    <li><a  href="<?php echo $faq_info_more;?>"><i class="icon-caret-right"></i><?php echo $text_faq;?></a></li>

                </ul>
            </section>

            <!-- product -->
            <section class="product" style="padding-bottom: 6em;padding-top: 0">
                <div class="title"><?php echo $text_best_sellers;?></div>
                <ul class="con-box clearfix">
                    <?php foreach($best_sellers as $best){ ?>
                    <li>
                        <a href="<?php echo $best['href'];?>">
                            <div class="product-img">
                                <img src="<?php echo $best['image'];?>" />
                            </div>
                            <div class="product-title">
                                <?php echo $best['name'];?>
                            </div>
                            <div class="product-cost">
                                <?php if($best['special']){ ?>
                                <b><?php echo $best['special'];?></b>
                                <del><?php echo $best['price'];?></del>
                                <?php }else{ ?>
                                <b><?php echo $best['price'];?></b>
                                <?php } ?>
                            </div>
                        </a>
                    </li>
                    <?php } ?>

                </ul>
            </section>
<script type="text/javascript" src="mobile/view/js/pagescroll.js"></script>
<script type="text/javascript" >
		
	  common.sliding_event('slider');
	$('#ship_to').live('change',function(){
	var country_code =$(this).val();
        $('.add-to-cart').removeClass("disabled-cart");
        if(country_code == ''){
            return '';
        }
	$.ajax({
		url: 'index.php?route=product/product/canShip',
		type: 'post',
		data: 'country_code=' + country_code + "&product_id=<?php echo $product_info['product_id'];?>" ,
		dataType: 'json',
		success: function(json) {
			if(json['flag']==1){
                            $('#product-tips').hide();
                           
			}
			else if(json['flag'] == 0){
                            $('.add-to-cart').addClass("disabled-cart");
                            if(json['msg']){
                                $('#product-tips').html(json['msg']);
                                $('#product-tips').show();
                            }
			}

		}
	});
})
    function changeImg(){
        $('.product-more').toggle();
        if($("#plus-sign").hasClass("icon-plus-sign")){
            $("#plus-sign").attr("class","icon-minus-sign");
        }else{
            $("#plus-sign").attr("class","icon-plus-sign");
        }
    }

      $.scrollbtn.init('index.php?route=checkout/cart');
</script>
<?php echo $footer; ?>