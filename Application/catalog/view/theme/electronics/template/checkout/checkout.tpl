<?php echo $head;?>

<body>
    <header class="checkout_header">
        <div class="width990 clearfix">
            <div class="logo"><a href='/' title='logo'><img src="<?php  echo STATIC_SERVER; ?>css/images/logo.png" alt=""/></a></div>
            <div class="checkout_menu">

                <ul>
                    <li ><a href="index.php?route=checkout/cart"><?php echo  $text_cart ;?></a></li>

                    <li class="active"><a href="javascript:void(0)"><?php echo   $text_checkout_confirm_order;?></a></li>
                    <li><a href="javascript:void(0)"><?php echo $text_checkout_pay_order;?></a></li>
                    <li><a href="javascript:void(0)"><?php echo $text_checkout_success;?></a></li>
                </ul>
            </div>
        </div>
    </header>
    <form action="<?php echo $action;?>" method="post" enctype="add_address_from" id='order_form'>
        <div class="width990">
            <div class="protit"><p class="black18"><?php echo $text_order_detalls;?></p></div>

            <section class="checkout">
                <p class="tit"><?php echo $text_checkout_shipping_address;?>                
					<span style="font-size: 12px;font-weight: normal; margin-left: 5px; color: #666">
					<?php echo $text_address_waring;?>
					</span>
			  </p>
                <div class="border">

                    <div class="checkout_box">
                        <div id="address_list">
                            <?php echo $address;?>
                        </div>

                        <?php if($logged){ ?>
                        <div class="checkout_con enter_new"><a href="javascript:void(0)" class="btn"><span>+</span><?php echo $text_add_address;?></a></div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <section class="checkout"  id="shipping_list">
               <?php echo $shipping_method;?>
            </section>

            <section class="checkout">
                <p class="tit"><?php echo $text_checkout_payment_method;?></p>
                <div id="payment_list"><?php echo $payment_method;?></div>
            </section>


            <div class="subtoal" id='order_subtoal'>
                <?php include_once(DIR_TEMPLATE.'/default/template/checkout/include/order_subtoal.tpl');?>
            </div>
            <div class="mycartbt mycartbt_under">
                <div class="cart-btn"><span class="carbtn setdisabled "><a href="javascript:void(0)" id='order_confirm' onclick="_gaq.push(['_trackEvent', 'shopping cart','Place your order' ])"><span class="btnjt"></span><span class="processing"><?php echo $text_place_your_order;?></span></a></span></div>
            </div>
        </div>
    </form>
   
    <div id='address_form'>
        <?php include_once(DIR_TEMPLATE.'/default/template/checkout/include/shipping_address_from.tpl');?>
    </div>
    <div class='grey-bg' style='text-align:center;display:none'><img src='<?php echo STATIC_SERVER; ?>css/images/lod2.gif' width='60' height='60' style='margin-top:25%'></div>
    <script>
          $("body").ajaxStart(function(){
              $(".grey-bg").show();
         }).ajaxStop(function(){
	      $(".grey-bg").hide();
         });
    </script>


    <script type="text/javascript">
        var error_point = '<?php echo $error_points;?>';
        var error_than_points = '<?php echo $error_than_points;?>';
                var config_point_reword = <?php echo $config_point_reword; ?> ;
                var total_points = <?php echo $totalpoints; ?> ;
                var error_select_address = "<?php echo $error_select_address;?>";
        var error_select_shipping = "<?php echo $error_select_shipping;?>";
        var error_select_payment = "<?php echo $error_select_payment;?>";
        var error_select_payment_credit_billing_adress = "<?php echo $error_select_payment_credit_billing_adress;?>";
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                url: 'index.php?route=checkout/checkout/total',
                type: 'post',
                data: '',
                dataType: 'json',
                success: function(json) {
                    $('#order_subtoal').html(json['subtol_coutent']);
                }
            });

        })
        $('#order_confirm').click(function() {

            var address = $('#order_form').find("input[name='address']:checked");
            var shipping = $('#order_form').find("input[dom='shipping']:checked").val();
            var paymethod = $('#order_form').find("input[name='payment_code']:checked").val();
            var message = '';

            if (!address.size()>0) {
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
            } else {
                if(setBtnLoad($('.setdisabled'),$(".processing"))){
                    return false;
                }
                $('#order_form').submit();
            }
        })
    </script>


 <?php echo $footer;?>
