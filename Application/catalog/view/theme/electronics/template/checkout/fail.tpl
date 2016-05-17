<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php	
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class='wrap'  style="margin-top: 20px">
	<h1><?php echo $heading_title; ?></h1>
  <div class="res_message"><?php echo $text_message; ?>
      <div><?php echo $text_error_tips; ?></div>
  </div>

  <div class="continue">
    <div ><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
</section>
<?php  
		$this->load->model('catalog/product');
		$orderItems = '';
		$ebay_item= '';
		$webgain_item= array();
		foreach($order_product_info as $product){
			$catagory_info =$this->model_catalog_product->getCategoryInfo($product['product_id']);

			//$orderItems .= "ga('ecommerce:addItem', {'id': '".$order_info['order_number']."','name': '".$product['name']."','sku': '".$product['model']."','category': '".$catagory_info['name']."','price': '".$product['price']."','quantity': '".(int)$product['quantity']."'});\n";
			$orderItems .= "ga('ec:addProduct', {
				'id': '".$product['model']."',
				'name': '".$product['name']."',
				'category': '".$catagory_info['name']."',
				'brand': '',
				'variant':'',
				'price': '".$this->currency->convert($product['price'],'USD',$order_info['currency_code'])."',
				'quantity': '".(int)$product['quantity']."'
			});\n";
			$ebay_item .="_roi.push(['_addItem', '".$product['model']."', '".$product['name']."', '".$catagory_info['category_id']."', '".$catagory_info['name']."', '".$product['price']."', '".(int)$product['quantity']."' ]);"; 
			$webgain_item[]= "16213::".$product['price']."::".$product['name']."::".$product['model']."::".$order_info['coupon_code'];
		}
		$webgain_item_string =implode('|',$webgain_item);
?>

<script>
/* ga 增强性代码*/
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74239275-1', 'auto');
  ga('require', 'displayfeatures');
  <?php  if($this->session->data['customer_id']){ ?>
  ga('set', '&uid', "<?php echo $this->session->data['customer_id'];?>"); 
  <?php  }  ?>
var dimensionValue ="<?php echo $order_info['coupon_code']; ?>";
ga('set', 'dimension1', dimensionValue);
ga('require', 'ec');


function checkout() {
  <?php echo $orderItems; ?>
}
// In the case of checkout actions, an additional actionFieldObject can
// specify a checkout step and option.
ga('ec:setAction','checkout', {
    'step': 1,            // A value of 1 indicates this action is first checkout step.
    'option': "<?php echo $order_info['payment_method'];?>"      // Used to specify additional info about a checkout stage, e.g. payment method.
});
ga('ec:setAction','checkout', {'step': 2});

// Called when user has completed shipping options.

  ga('ec:setAction', 'checkout_option', {
    'step': 2,
    'option': "<?php echo $order_info['shipping_method'];?>"
  });

ga('set', '&cu', "<?php echo $order_info['currency_code'];?>");              // Set tracker currency to Euros
<?php echo $orderItems; ?>
ga('ec:setAction', 'purchase', {
  id: "<?php echo $order_info['order_number']; ?>",
  affiliation: "<?php echo $order_info['payment_method']; ?>",
  revenue: "<?php echo $order_info['grand_total']; ?>",
  tax: '',
  shipping:"<?php echo $order_info['shipping_amount']; ?>",
  coupon: "<?php echo $order_info['coupon_code']; ?>"
});

ga('send', 'pageview');
</script>


<?php echo $footer; ?>