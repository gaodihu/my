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

  
  <div class="res_message">
        <?php if(isset($text_paypal_onestep_install) && $text_paypal_onestep_install){ ?>
        <p><?php echo $text_paypal_onestep_install; ?></p>
        <?php } ?>


        <?php if(isset($text_paypal_onestep_pay_tips) && $text_paypal_onestep_pay_tips){ ?>
        <p><?php echo $text_paypal_onestep_pay_tips; ?></p>
        <?php } ?>
      
      <?php echo $text_message; ?></div>
  <div class="continue">
    <div ><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
</section>


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