<div class="payment-box">
    <ul class="title-tab">
    <?php  if($payment_methods){ ?>
        <?php foreach($payment_methods as $payment_method){ ?>
            <?php if($pp_express && $payment_method['code'] == 'pp_express' ) { ?>
                <li class="active"> 
                    <input name="payment_code" type="radio" value="<?php echo $payment_method['code'];?>" <?php if($payment_method['code']==$this->session->data['payment']['code']){?>checked="checked"<?php } ?>/>
                           <span class="img"><img src="css/images/payment/<?php echo  strtolower($payment_method['code']);?>.png" alt=""/></span>
                           <span class="tit"><?php echo $payment_method['title'];?></span>
                </li>
            <?php } else if($payment_method['code'] != 'pp_express' ) { ?>
                
                <li <?php if($payment_method['code']==$this->session->data['payment']['code'] ||(empty($this->session->data['payment']['code']) && $payment_method['code']='pp_standard') ){?>class="active"<?php } ?>>
                    <input name="payment_code" type="radio" value="<?php echo $payment_method['code'];?>" <?php if($payment_method['code']==$this->session->data['payment']['code']){?>checked="checked"<?php } ?>/>
                    <span class="img"><img src="css/images/payment/<?php echo  strtolower($payment_method['code']);?>.png" alt=""/></span>
                    <span class="tit"><?php echo $payment_method['title'];?></span>
                </li>
            <?php } ?>
        <?php } ?> 
    <?php } ?> 
    </ul>
    <ul class="content-tab">
        <?php  if($payment_methods){ ?>
        <?php foreach($payment_methods as $payment_method){ ?>
            <?php if($pp_express && $payment_method['code'] == 'pp_express' ) { ?>
                <li><?php echo $payment_method['desc'];?></li>
            <?php } else if($payment_method['code'] != 'pp_express' ) { ?>
                <li <?php if($payment_method['code']==$this->session->data['payment']['code'] ||(empty($this->session->data['payment']['code']) && $payment_method['code']=='pp_standard') ){?>style="display: list-item;"<?php }else { ?>style="display: none"<?php } ?>>

                    <?php if($payment_method['code'] == 'globebill_qiwi') { ?>
                       <div><?php echo $payment_method['desc'];?></div>
                        <div class="payforinfo-li">
                            <span><?php echo $qiwi_username; ?> ：</span><input name="qiwiUsername">
                        </div>
                    <?php } else if($payment_method['code'] == 'globebill_giropay') { ?>
                        <div><?php echo $payment_method['desc'];?></div>
                        <div class="payforinfo-li">
                            <span><?php echo $giropay_username; ?> ：</span><input name="payAccountnumber">
                           <span><?php echo $giropay_bankcode; ?> ：</span><input name="payBankcode">
                        </div>
                    <?php } else if($payment_method['code'] == 'globebill_credit') { ?>
                        <div class="payforinfo-li">
                            <?php include_once(DIR_TEMPLATE.'/default/template/checkout/include/billing_address_from.tpl');?>
                        </div>
                   <?php } else if($payment_method['code'] == 'pp_onestep') { ?>
                        <?php if($logged){ ?>
                            <?php if(!$is_binding_onestep) { ?>
                            <div class="payforinfo-li payforcont">
                                <div><input type="checkbox" value="1" name="is_paypal_onestep" checked="checked" title="<?php echo $text_paypal_onestep_install_tips; ?>"><b class="red"><?php echo $text_paypal_one_step_title; ?></b></div>
                                <div><?php echo $payment_method['desc'];?></div>
                                <div><?php echo $text_paypal_one_step; ?></div>
                            </div>
                            <?php } else { ?>
                            <div class="payforinfo-li payforcont">
                                <div class="red blue-a"><b><?php echo $text_paypal_one_step_unbing; ?></b></div>
                                <div><?php echo $payment_method['desc'];?></div>
                                <div><?php echo $text_paypal_one_step; ?></div>
                            </div>
                            <?php } ?>
                         <?php } else { ?>
                            <div><?php echo $payment_method['desc'];?></div>
                         <?php } ?>
                    <?php } else { ?>
                        <div><?php echo $payment_method['desc'];?></div>
                    <?php } ?>
                </li>
            <?php } ?>
         <?php } ?> 
        <?php } ?> 

    </ul>
</div>


<script>
 $("#payment_list .checkout_con input:radio").bind("click",function(){
       $(this).parents(".checkout_con").addClass("bg_ed").siblings().removeClass("bg_ed");
       $(".payforinfo").hide();
       $(this).parents(".checkout_con").find(".payforinfo").show();
 });
 $(".payment-box .title-tab  li").bind("click",function(){
     var index = $(this).index();
     $(this).addClass("active").siblings().removeClass("active");
     $(".content-tab").children(" li").hide();
     $(".content-tab").children(" li").eq(index).show();
     $(" input:radio",this).attr('checked',true);
     var payment_code = $("input[name=payment_code]",this).val();
     if (payment_code) {
        change_payment(payment_code);
     }
 });
     function change_payment(payment_code) {
        $.ajax({
            url: '/index.php?route=checkout/checkout/changePayment',
            type: 'post',
            data: 'payment_code=' + payment_code,
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 1) {
                    //alert(json['message']);
                    //window.location.href="'"+json['redirect']+"'"
                    window.location.href = "/index.php?route=checkout/checkout";
                }
                else {
                    //$('#shipping_list').html(json['shipping_content']);
                    //$('#order_subtoal').html(json['subtol_coutent']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }
</script>
