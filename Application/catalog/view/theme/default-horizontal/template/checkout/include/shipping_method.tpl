<p class="tit"><?php echo $text_checkout_shipping_method;?>

 <?php if(count($shipping_methods)>1) {?>
<span class="mininfo yellow">
<?php echo $text_split_order_help; ?>
</span>
<?php } ?>
 </p>

                <div class="border">
                    <div class="checkout_box">
                        <div>
                            
                      

<?php if(isset($shipping_methods) && is_array($shipping_methods) && count($shipping_methods)>0) { ?>
<?php $_package_num = 1; ?>

<?php foreach($shipping_methods as $_pk => $_order_package){ ?>
            
            <section class="checkout checkout_prolist">
                <?php if(count($shipping_methods)>1) { ?><p class="tit"><?php echo "package #" . $_package_num ;?></p><?php } ?>
                <div class="border">
                    <ul class="tt clearfix">
                        <li class="text-l text-in15"><?php echo $column_name;?></li>
                        <li><?php echo $column_price;?></li>
                        <li><?php echo $column_quantity;?></li>
                        <li><?php echo $column_total;?></li>
                    </ul>
                    <?php foreach($_order_package['package'] as $product){ ?>
					
                    <ul class="clearfix">
                        <li class="textoverflow textleft15"><a href="<?php echo $this->url->link('product/product', 'product_id=' . $product['product_id']);?>" title="<?php echo $product['name'];?>"><?php echo $product['name'];?></a></li>
                        <li><?php echo $product['currency_price_text'];?></li>
                        <li><?php echo $product['quantity'];?></li>
                        <li><?php echo $product['currency_total_text'];?></li>
                    </ul>
                    <?php } ?>


                </div>
            </section>
        <?php if(count($_order_package['methods']) > 0 && is_array($_order_package['methods']) ) { ?>
        <?php foreach($_order_package['methods'] as $shipping_method){ ?>
	<div class="checkout_con bg_ed clearfix">
        <label>
		<span class="checkout_con_left">
			<?php if($default_shipping_methods[$_pk]['delivery_method'] == $shipping_method['delivery_method']){ ?>
                        <input dom="shipping"  pk= "<?php echo $_pk;?>" name="shipping[<?php echo $_pk;?>]" type="radio" checked="checked" value="<?php echo $shipping_method['delivery_method']; ?>" />
			<?php }else{ ?>
			<input dom="shipping"  pk= "<?php echo $_pk;?>" name="shipping[<?php echo $_pk;?>]" type="radio" value="<?php echo $shipping_method['delivery_method']; ?>" />
			<?php } ?>
			<span class="bold"><?php echo $shipping_method['shipping_method'];?></span>
		</span>
		
        </label>

		<div class="checkout_con_right">
		<span class="bold"><?php echo $shipping_method['format_price'];?></span>
		</div>
		  <?php if($shipping_method['delivery_method']=='expedited'){ ?>
			<?php if($is_remote){ ?>
			</br><?php echo $text_remote_free; ?></p>
			<?php } ?>
		<?php } ?>
	</div>
	
		
        <?php } ?>
        <?php } else { ?>
        <div class="checkout_con bg_ed clearfix"><?php echo $can_not_ship_to;?></div>
        <?php } ?>
        <?php $_package_num ++ ;?>
<?php } ?>

<?php if($battery_can_split == 1){?>
    <?php if($customer_split_package ) { ?>
    <div class="m-t10 m-l10 lh24">
        <input type="checkbox"   name="customer_split_package"  id="customer_split_package" value="0" />
        <span ><?php echo $text_battery_merge_order_tips; ?></span>
    </div>
    <?php } else {?>
      <div class="m-t10 m-l10 lh24">
        <input type="checkbox" name="customer_split_package" id="customer_split_package" value="1" />
        <span ><?php echo $text_battery_split_order_tips; ?></span>
      </div>
    <?php } ?>
<?php } ?>

<?php } ?>
  </div>
                    </div>
                </div>