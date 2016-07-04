<form action="<?php echo $transport_url; ?>" method="post" id="payment_from">
<input type="hidden" name="merNo" value="<?php echo $merchant_no; ?>" />
<input type="hidden" name="gatewayNo" value="<?php echo $payment_gateway; ?>" />
<input type="hidden" name="orderNo" value="<?php echo $order['order_number']; ?>" />
<input type="hidden" name="orderCurrency" value="<?php echo $order['currency_code']; ?>" />
<input type="hidden" name="orderAmount" value="<?php echo $order['grand_total']; ?>" />
<input type="hidden" name="signInfo" value="<?php echo $signkey_code; ?>" />
<input type="hidden" name="returnUrl" value="<?php echo $return_url; ?>" />
<input type="hidden" name="firstName" value="<?php echo $order['shipping_firstname']; ?>" />
<input type="hidden" name="lastName" value="<?php echo $order['shipping_lastname']; ?>" />
<input type="hidden" name="email" value="<?php echo $order['email']; ?>" />
<input type="hidden" name="phone" value="<?php echo $order['shipping_phone']; ?>" />
<input type="hidden" name="paymentMethod" value="<?php echo $payment_code; ?>" />
<input type="hidden" name="country" value="<?php echo $order['shipping_iso_code_2']; ?>" />
<input type="hidden" name="city" value="<?php echo $order['shipping_city']; ?>" />
<input type="hidden" name="address" value="<?php echo $order['shipping_address_1']; ?>" />
<input type="hidden" name="zip" value="<?php echo $order['shipping_postcode']; ?>" />
<div class="buttons" style="display: none">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" id="pay_button"/>
    </div>
  </div>
</form>
<script>
       document.getElementById('pay_button').click();
</script>
