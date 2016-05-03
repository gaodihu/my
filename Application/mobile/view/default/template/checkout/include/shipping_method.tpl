
<?php if(count($shipping_methods)>1) {?>
<div class="tab-box">
	<div class="min-alert-bg font20 ">
	<?php echo $text_split_order_help; ?>
	</div>
</div>
	<?php } ?>

<?php if(isset($shipping_methods) && is_array($shipping_methods) && count($shipping_methods)>0) { ?>
<?php $_package_num = 1; ?>

<?php foreach($shipping_methods as $_pk => $_order_package){ ?>

<div class="tab-box" >
	<ul class="tab-chagne change-list">
		<?php if(count($shipping_methods)>1) { ?><li><?php echo "package #" . $_package_num ;?></li><?php } ?>
		<?php foreach($_order_package['package'] as $product){ ?>
		<li >
			<a href="<?php echo $this->url->link('product/product', 'product_id=' . $product['product_id']);?>"><img src="image/<?php echo $product['image'];?>" width="74" /></a>
			<div class="p-info">
				<div class="p-t"><a href="<?php echo $this->url->link('product/product', 'product_id=' . $product['product_id']);?>"><?php echo $product['name'];?></a></div>
				<div><span class="price"><?php echo $this->currency->onlyFormat($product['currency_price']);?></span>Quantity :<?php echo $product['quantity'];?></div>
			</div>

		</li>
		<?php } ?>
	</ul>
</div>
<div class="tab-box" >
	<?php if(count($_order_package['methods']) > 0 && is_array($_order_package['methods']) ) { ?>
	<ul class="tab-chagne change-list tab-active" >
        <li class='shipping-title'><?php echo $text_checkout_shipping_method;?></li>
		 <?php foreach($_order_package['methods'] as $shipping_method){ ?>
			   <?php if($default_shipping_methods[$_pk]['delivery_method'] == $shipping_method['delivery_method']){ ?>
			   
				<li  class="active" ><label  for="<?php echo $shipping_method['delivery_method']; ?>"><input dom="shipping"  pk="<?php echo $_pk;?>" name="shipping[<?php echo $_pk;?>]" type="radio" id="<?php echo $shipping_method['delivery_method']; ?>" checked="checked" value="<?php echo $shipping_method['delivery_method']; ?>" onclick="AddressEvent.shipping('<?php echo $shipping_method['delivery_method']; ?>','<?php echo $_pk;?>')" class='shipping_radio' /><span class="price"><?php echo $shipping_method['format_price'];?></span><!-- <b class="icon-check"></b> --><?php echo $shipping_method['shipping_method'];?></label>
				<?php if($shipping_method['delivery_method']=='expedited'){ ?>
					<?php if($is_remote){ ?>
					</br><span style="font-size:0.6em"><?php echo $text_remote_free; ?></span>
					<?php } ?>
				<?php } ?>
				</li>
				<?php }else{ ?>
				<li ><label  for="<?php echo $shipping_method['delivery_method']; ?>"><input dom="shipping"  pk= "<?php echo $_pk;?>" name="shipping[<?php echo $_pk;?>]" type="radio" id="<?php echo $shipping_method['delivery_method']; ?>" value="<?php echo $shipping_method['delivery_method']; ?>" class='shipping_radio' onclick="AddressEvent.shipping('<?php echo $shipping_method['delivery_method']; ?>','<?php echo $_pk;?>')"   /><span class="price"><?php echo $shipping_method['format_price'];?></span><!-- <b class="icon-check-empty"></b> --><?php echo $shipping_method['shipping_method'];?></label>
				<?php if($shipping_method['delivery_method']=='expedited'){ ?>
					<?php if($is_remote){ ?>
					</br><span style="font-size:0.6em"><?php echo $text_remote_free; ?></span>
					<?php } ?>
				<?php } ?>
				</li>
				<?php } ?>
		 <?php } ?>

	  </ul>

 <?php }else { ?>
        <div class="min-alert-bg font20 clearfix"><?php echo $can_not_ship_to;?></div>
  <?php } ?>
  </div>
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



 
 