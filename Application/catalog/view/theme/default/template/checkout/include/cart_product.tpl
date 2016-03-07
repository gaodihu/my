<table width="100%" cellpadding="0" cellspacing="0" border="0">
        	<tbody><tr>

            	<th><?php echo $column_image; ?></th>
            	<th><?php echo $column_name; ?></th>
            	<th><?php echo $column_price; ?></th>
            	<th><?php echo $column_quantity; ?></th>
                <th><?php echo $column_total; ?></th>
            	<th></th>
            </tr>

            <?php if($no_shipping_product_id_arr) {?>
            <?php $_i = 1;?>
            <?php foreach ($products as $product) { ?>
            <?php if(in_array($product['product_id'],$no_shipping_product_id_arr)) { ?>
            <tr id="a2" class="a2 red-border-side <?php if($_i == 1) { echo " red-border-top";} ?> <?php if($_i == count($no_shipping_product_id_arr)) { echo " red-border-bottom";} ?>" >

                <td>
                    <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                    <?php } ?>
                <td width="38%">
                    <div class="alignleft">
                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br><span class="gray">SKU:<?php echo $product['model']; ?></span><br><?php foreach ($product['option'] as $option) { ?>
                    - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                    <?php } ?>
                    </div>
                </td>
                <td>
                <div class="alignleft">
                    <?php if($product['save_price'] > 0 ) { ?>
                        <div class="t1"><?php echo $product['original_price']; ?></div>
                    <?php } ?>
                    <div class="t2"><?php echo $product['price']; ?></div>
                    <?php if($product['save_price'] > 0 ) { ?>
                        <div class="t3">SAVE  <?php echo  $product['save_price_text'] ; ?>
                            <div class="t3_1"><code></code><?php echo  $product['save_price_percent'] ; ?>% OFF</div>
                        </div>
                    <?php }  ?>

                    </div>
                </td>
                <td>
                <div class="product-quantity-cart">

                    <div class="countinput clearfix input-left" style=" margin-top: 20px; margin-left:20%">

                         <input id="quantity-dec" type="button"  class="edtbtn update_cart_del"  value="-"/>
                        <input name="quantity"  class="mid-input "  onkeyup="this.value=this.value.replace(/\D/g,'')" type="text" maxlength="4" value="<?php echo $product['quantity'];?>" id='pro_quantity' key="<?php echo $product['product_id'];?>">
                        <input id="quantity-inc" type="button"  class="edtbtn update_cart_add"  value="+"/>
                    </div>
                </div></td>
                <td><?php echo $product['total_format'];?></td>
                <td class="p-relative" id="cart_like<?php echo $product['product_id'];?>">
                    <?php if($product['is_wishlist']){ ?>
                    <a href="javascript:addToWishList('<?php echo $product['product_id'];?>');" class="cart_like redimg"></a>
                    <?php }else{ ?>
                    <a href="javascript:addToWishList('<?php echo $product['product_id'];?>');" class="cart_like"></a>
                    <?php } ?>
                    <a href="javascript:showdialog('<?php echo $product['product_id'];?>')" class="cart_nolike" rel="cart_nolike<?php echo $product['product_id'];?>"></a>
                    <div class="cart-pop cart-min" style="display: none">
                        <p><?php echo $text_remove_confirme;?></p>
                        <div><a  class="btn-primary" href="javascript:remove('<?php echo $product['product_id'];?>')" >Yes</a> <input type="button" class="btn-default" onclick="hide_cart_like('<?php echo $product['product_id'];?>')" value="No"></div>
                    </div>
                </td>
            </tr>
            <?php $_i++; ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>



            <?php foreach ($products as $product) { ?>
            <?php if(!in_array($product['product_id'],$no_shipping_product_id_arr)) {?>
            <tr id="a2" class="a2  <?php if(in_array($product['product_id'],$no_shipping_product_id_arr)) {?><?php echo "red-border"; ?><?php } ?>">

            	<td>
                    <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                    <?php } ?>
                <td width="38%">
                    <div class="alignleft">
                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br><span class="gray">SKU:<?php echo $product['model']; ?></span><br><?php foreach ($product['option'] as $option) { ?>
                    - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                    <?php } ?>
                    </div>
                </td>
            	<td>
                <div class="alignleft">
                    <?php if($product['save_price'] > 0 ) { ?>
                        <div class="t1"><?php echo $product['original_price']; ?></div>
                    <?php } ?>
                    <div class="t2"><?php echo $product['price']; ?></div>
                    <?php if($product['save_price'] > 0 ) { ?>
                        <div class="t3">SAVE  <?php echo  $product['save_price_text'] ; ?>
                            <div class="t3_1"><code></code><?php echo  $product['save_price_percent'] ; ?>% OFF</div>
                        </div>
                    <?php }  ?>

                    </div>
                </td>
            	<td>
                <div class="product-quantity-cart">

                    <div class="countinput clearfix input-left" style=" margin-top: 20px; margin-left:20%">

                         <input id="quantity-dec" type="button"  class="edtbtn update_cart_del"  value="-"/>
                        <input name="quantity"  class="mid-input "  onkeyup="this.value=this.value.replace(/\D/g,'')" type="text" maxlength="4" value="<?php echo $product['quantity'];?>" id='pro_quantity' key="<?php echo $product['product_id'];?>">
                        <input id="quantity-inc" type="button"  class="edtbtn update_cart_add"  value="+"/>
                    </div>
                </div></td>
            	<td><?php echo $product['total_format'];?></td>
                <td class="p-relative" id="cart_like<?php echo $product['product_id'];?>">
					<?php if($product['is_wishlist']){ ?>
                    <a href="javascript:addToWishList('<?php echo $product['product_id'];?>');" class="cart_like redimg"></a>
					<?php }else{ ?>
					<a href="javascript:addToWishList('<?php echo $product['product_id'];?>');" class="cart_like"></a>
					<?php } ?>
                    <a href="javascript:showdialog('<?php echo $product['product_id'];?>')" class="cart_nolike" rel="cart_nolike<?php echo $product['product_id'];?>"></a>
                    <div class="cart-pop cart-min" style="display: none">
                        <p><?php echo $text_remove_confirme;?></p>
                        <div><a  class="btn-primary" href="javascript:remove('<?php echo $product['product_id'];?>')" >Yes</a> <input type="button" class="btn-default" onclick="hide_cart_like('<?php echo $product['product_id'];?>')" value="No"></div>
                    </div>
                </td>
            </tr>
            
            <?php } ?>
            <?php } ?>
            
            <tr class="last">

                <td colspan="6">
                	<div class="alignright right">
					 <div class="t5">
                        	<code class="helpicon"></code>
                        	<div class="help" style="display:none;margin-left:-300px; top: -20px;"><code class="sj"></code><div class="con"><?php echo $coupon_not_used_for;?></div></div>
                            <span class="blue"><!--<input name="checkbox" type="checkbox" id="cop">--><?php echo $coupon_used;?></span>
                            
                        </div>
						<?php if(isset($coupon_code)){ ?>
						<div class="coupon" style="display:block; width:245px;"><input name="text" type="text" value="<?php echo $coupon_code;?>"><a href="javascript:void(0)" class="common-btn-orange" id='apply_coupon'><?php echo $text_apply;?></a><a href="javascript:void(0)" class="common-btn-orange" id='Cancel_coupon'><?php echo $text_cancel;?></a></div>
						<?php }else{ ?>
						<div class="coupon"><input name="text" type="text" value=""><a href="javascript:void(0)" class="common-btn-orange" id='apply_coupon'><?php echo $text_apply;?></a></div>
						<?php } ?>
						
						<div class="clear"></div>
					 <?php foreach($totals as $total){ ?>

					  <?php if($total['code']=='total'){ ?>
					  <!--div class="t6"><span class="bold"><?php echo $total['title'];?>:</span><span class="font20 red"><?php echo $currency_code;?> <?php echo $total['text'];?></span></div-->
					 <?php  } else{ ?>
					  <div class="t4" style="margin-top: 5px"><?php echo $total['title'];?>: <span class="red font14"><?php echo $currency_code;?> <?php echo $total['text'];?></span></div>
					 <?php  } ?>
					 <?php }?>


                        
                    </div>
                </td>
            </tr>
        </tbody>
</table>




