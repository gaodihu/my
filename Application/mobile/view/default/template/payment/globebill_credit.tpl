<?php echo $head;?>
<body>
    <style>
        .pay-info{overflow:hidden;border:1px solid #ccc;margin:10px auto; }
        .pay-info li{width:307px;float:left;border-right:1px solid #ccc;padding:10px;height:50px; }
        .pay-info li:nth-child(3){border-right:0; }
        .total-info{width:970px;border:1px dashed #ccc; height:35px;line-height:35px;background:#f1f1f1;padding:0 10px;}
        .total-info span{float:right;font-size:20px;}
        .total-info b{color:red;}
        .checkout_header{
            margin-bottom:0;
        }
        
    </style>    
    <!--
    <header class="checkout_header">
        <div class="width990 clearfix">
            <div class="logo"><a href='/' title='logo'><img src="catalog/view/theme/default/images/logo.png" alt=""/></a></div>
            <div class="checkout_menu">
                <ul>
                    <li class="active"><a href="javascript:void(0)"><?php echo $text_checkout_confirm_order;?></a></li>
                    <li class="active"><a href="javascript:void(0)"><?php echo $text_checkout_pay_order;?></a></li>
                    <li><a href="javascript:void(0)"><?php echo $text_checkout_success;?></a></li>
                </ul>
            </div>
        </div>
    </header-->
<?php 
$email = $order['email'];
$email_arr = explode('@',$email);
$new_email_pre =    $email_arr[0]; 
$new_email_pre = str_replace('.','',$new_email_pre);
$new_email = $new_email_pre.'@' .$email_arr[1];
?>
<iframe width="100%" height="500px"  marginwidth="0" marginheight="0" frameborder="0"style="background: #fff;margin:0;padding: 0;"  name="pay"></iframe>
<div style="display:none">
    <form  target="pay" action="<?php echo $transport_url; ?>" method="post" id="payment_from">
    <input type="hidden" name="merNo" value="<?php echo $merchant_no; ?>" />
    <input type="hidden" name="gatewayNo" value="<?php echo $payment_gateway; ?>" />
    <input type="hidden" name="orderNo" value="<?php echo $order['order_number']; ?>" />
    <input type="hidden" name="orderCurrency" value="<?php echo $order['currency_code']; ?>" />
    <input type="hidden" name="orderAmount" value="<?php echo $order['grand_total']; ?>" />
    <input type="hidden" name="signInfo" value="<?php echo $signkey_code; ?>" />
    <input type="hidden" name="returnUrl" value="<?php echo $return_url; ?>" />
    <input type="hidden" name="firstName" value="<?php echo htmlspecialchars($order['payment_firstname']); ?>" />
    <input type="hidden" name="lastName" value="<?php echo htmlspecialchars($order['payment_lastname']); ?>" />
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($new_email); ?>" />
    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($order['payment_phone']); ?>" />
    <input type="hidden" name="paymentMethod" value="<?php echo htmlspecialchars($payment_code); ?>" />
    <input type="hidden" name="country" value="<?php echo htmlspecialchars($order['payment_iso_code_2']); ?>" />
    <input type="hidden" name="city" value="<?php echo htmlspecialchars($order['payment_city']); ?>" />
    <input type="hidden" name="address" value="<?php echo htmlspecialchars($order['payment_address_1']); ?>" />
    <input type="hidden" name="zip" value="<?php echo htmlspecialchars($order['payment_postcode']); ?>" />
    <input type="hidden" name="isMobile" value="1" />
    <div class="buttons" style="display:none" >
        <div class="right">
          <input type="submit" value="<?php echo $button_confirm; ?>" class="button" id="pay_button"/>
        </div>
      </div>
    </form>
    <script>
           document.getElementById('pay_button').click();
    </script>
</div>


</body>
</html>

