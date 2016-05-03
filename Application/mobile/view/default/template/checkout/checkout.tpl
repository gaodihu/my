<?php echo $header;?>
<script type="text/javascript" src="/mobile/view/js/checkout.js"></script>

<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>

<form action="<?php echo $action;?>" method="post" enctype="add_address_from" id='order_form'>
        <div class="tab-box" style="padding-top: 1.5em;">
            <ul class="tab-chagne change-list a_block">
                <li class="t-title"><?php echo $text_checkout_shipping_address;?></li>
				<?php if($default_address){ ?>
				<input name="address" type="hidden" value="<?php echo $default_address['address_id']?$default_address['address_id']:9999;?>">
                <li><a href="<?php echo $address_url;?>"><i class="icon-caret-right"></i>
                    <?php echo $default_address['firstname']." ".$default_address['lastname'];?> <br>
                    <span class="grey"><?php echo $default_address['address_1']. " " . $default_address['address_2'] . " ".$default_address['city']." ".$default_address['zone']." ".$default_address['country']."(".$default_address['postcode'].")"." ".$default_address['phone'];?></span>
                </a></li>
				<?php }else{ ?>
				<li><a href="<?php echo $address_url;?>"><i class="icon-caret-right"></i> <?php echo $text_choose_address;?></a></li>
				<?php } ?>
            </ul>
        </div>

		<?php echo $shipping_method; ?>
        

        <div class="tab-box" id='order_subtoal'>
			<?php //include_once('mobile/view/default/template/checkout/include/order_subtoal.tpl');
			 echo $getTotal;	//echo $order_total;
			?>
        </div>
		

        <div class="tab-box" id="payment_list">
		<?php echo $payment_method;?>
           
        </div>


      
        <div style="padding: 1em;text-align: center" class="checkout-btn">
            <a class="button orange-bg " id='order_confirm'><?php echo $text_checkout_pay_order;?></a>
        </div>
</form>

    <script type="text/javascript">

		var error_select_address ="<?php echo $error_select_address;?>";
		var error_select_shipping ="<?php echo $error_select_shipping;?>";
		var error_select_payment ="<?php echo $error_select_payment;?>";
		var error_select_payment_credit_billing_adress = "<?php echo $error_select_payment_credit_billing_adress;?>";
        $('#order_confirm').click(function() {
            var address = $('#order_form').find("input[name='address']").val();
            var shipping = $('#order_form').find("input[dom='shipping']:checked").val();
            var paymethod = $('#order_form').find("input[name='payment_code']:checked").val();
            var message = '';

            if (!address) {
                message += error_select_address;
            }
            if (!shipping) {
                message += error_select_shipping;
            }
            if (!paymethod) {
                message += error_select_payment;
            }
            if(paymethod == 'globebill_credit'){
                var billing_address = $('#add_billing_address_from input[name=have_billing_address]').val();
                if(billing_address == '0' || billing_address == ''){
                    message += error_select_payment_credit_billing_adress;
                }
            }
            if (message) {

                alert(message);
                $(".payforinfo-li").parents(".more-box").show();
            } else {
                if(common.setBtnLoad($("#order_confirm"),$("#order_confirm"))){
                    return false
                }
                $('#order_form').submit();
            }
        })
    </script>
<!-- Google Code for Checkout Conversion Page -->
<script type=""text/javascript"">
/* <![CDATA[ */
var google_conversion_id = 979549056;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "LauaCOCr-AcQgPeK0wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/979549056/?label=LauaCOCr-AcQgPeK0wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
 <?php echo $footer;?>