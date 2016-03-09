<html>
<head>
<style>
	.center{width:300px;margin:0 auto; margin-top:10%;}
</style>
</head>
<body>
You will be redirected to the PayPal website in a few seconds.<br/>
<?php if ($testmode) { ?>
<div class="warning"><?php echo $text_testmode; ?></div>
<?php } ?>

<form action="<?php echo $action; ?>" method="post" id="paypal_from">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="upload" value="1" />
  <input type="hidden" name="business" value="<?php echo $business; ?>" />
  <?php $i = 1; ?>
  <?php foreach ($products as $product) { ?>
  <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo htmlentities($product['name']); ?>" />
  <input type="hidden" name="item_number_<?php echo $i; ?>" value="<?php echo htmlentities($product['model']); ?>" />
  <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $product['price']; ?>" />
  <input type="hidden" name="quantity_<?php echo $i; ?>" value="<?php echo $product['quantity']; ?>" />
 
  <?php $i++; ?>
  <?php } ?>
  <?php if ($discount_amount_cart) { ?>
  <input type="hidden" name="discount_amount_cart" value="<?php echo $discount_amount_cart; ?>" />
  <?php } ?>
  <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
  <input type="hidden" name="first_name" value="<?php echo htmlentities($first_name); ?>" />
  <input type="hidden" name="last_name" value="<?php echo htmlentities($last_name); ?>" />
  <input type="hidden" name="address1" value="<?php echo htmlentities($address1); ?>" />
  <input type="hidden" name="address2" value="<?php echo htmlentities($address2); ?>" />
  <input type="hidden" name="city" value="<?php echo htmlentities($city); ?>" />
  <input type="hidden" name="zip" value="<?php echo htmlentities($zip); ?>" />
  <input type="hidden" name="state" value="<?php echo htmlentities($state); ?>" type="hidden"/>
  <input type="hidden" name="country" value="<?php echo htmlentities($country); ?>" />
  <input type="hidden" name="address_override" value="0" />
  <input type="hidden" name="email" value="<?php echo $email; ?>" />
  <input type="hidden" name="invoice" value="<?php echo $custom; ?>" />
  <input type="hidden" name="lc" value="<?php echo $lc; ?>" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="charset" value="utf-8" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="<?php echo $paymentaction; ?>" />
  <input type="hidden" name="custom" value="<?php echo $custom; ?>" />
  <input type="hidden" name="bn" value="opencart" />
  <input id="item_name" name="item_name" value="opencart" type="hidden"/>

  <div class="buttons" >
    <div class="right">
      <input type="submit" value="Click here if you are not redirected within 10 seconds..." class="button" id="paypal_button"/>
    </div>
  </div>
</form>
<script>
    document.getElementById('paypal_button').click();
</script>
<div class="center"><img src="<?php  echo STATIC_SERVER; ?>css/images/lodding.gif"  /></div>
</body>
</html>

