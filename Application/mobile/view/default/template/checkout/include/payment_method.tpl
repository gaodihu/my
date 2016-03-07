 <ul class="tab-chagne change-list tab-active">
                <li class="t-title">
                    <?php echo $text_checkout_payment_method;?>
                </li>
				<?php  if($payment_methods){ ?>
				<?php foreach($payment_methods as $payment_method){ ?>
					<?php if($payment_method['code']==$this->session->data['payment']['code'] ||(empty($this->session->data['payment']['code']) && $payment_method['code']='pp_standard') ){?>
					<li  class="active">
					<?php }else{ ?>
				    <li>

					<?php }?>
						<a class="radius-btn more-info"><span class="icon-angle-down"></span></a>
						<!-- <b class="icon-check"></b> -->
                        <label for="<?php echo $payment_method['code'];?>">
						<input  class='shipping_radio' name="payment_code"  onclick="CheckOutEvent.payment('<?php echo $payment_method['code'];?>')" type="radio" id="<?php echo $payment_method['code'];?>" value="<?php echo $payment_method['code'];?>" <?php if($payment_method['code']==$this->session->data['payment']['code']){?>checked="checked"<?php } ?>/>

                        <?php echo $payment_method['title'];?>
						<div class="more-box">
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
									<?php include_once('mobile/view/default/template/checkout/include/billing_address_from.tpl');?>
								</div>
						   <?php } else if($payment_method['code'] == 'pp_onestep') { ?>
                                                               <!--
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
                                                                 -->
                                                                 <div><?php echo $payment_method['desc'];?></div>
                                                                 
							<?php } else { ?>
								<div><?php echo $payment_method['desc'];?></div>
							<?php } ?>
						</div>
                        </label>
					</li>
				<?php } ?>
				<?php } ?>
               
            </ul>