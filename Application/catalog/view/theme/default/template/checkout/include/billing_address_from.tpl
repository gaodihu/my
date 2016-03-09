<h3><?php echo $confirm_billing_address; ?></h3>
<div id="ship_info" class="ship_info" <?php if(!$have_billing_address){ ?>style="display:none"<?php } ?>>
    <div class="ship_info_btn">   
            <div class="ship_ie_fix1"><a href="javascript:void(0)" id="edit_billing_address" class="blue"><?php echo $edit_billing_address; ?></a>
            </div>
    </div>
    <div class="ship_info_scroll">
            <div id="payment_box">
                   <?php echo $address_info['firstname'] . ' ' . $address_info['lastname'] .' '. $address_info['address_1']. " " . $address_info['address_2'] . " ".$address_info['city']." ".$address_info['zone']." ".$address_info['country']."(".$address_info['postcode'].")"." ".$address_info['phone'];?>						 
            </div>
    </div>
    <div class="limit-tips">*<i><?php echo $text_credit_limit_tips; ?></i></div>
</div>
<div class="form selectform form billinglist" id="add_billing_address_from" <?php if($have_billing_address){ ?>style="display:none"<?php } ?>>
        
        <ul>
            <li class="text"><span class="left"><b>*</b> <?php echo $entry_firstname;?>  </span><input name="firstname" type="text" value="<?php echo $address_info['firstname'];?>" verify="user"/></li>
            <li class="text"><span class="left"><b>*</b> <?php echo $entry_lastname;?> </span><input name="lastname" type="text" value="<?php echo $address_info['lastname'];?>" verify="user"/></li>

            <?php if(!$logged) { ?>
            <li class="text"><span class="left"><b>*</b> <?php echo $entry_email;?>  </span><input name="email" type="text" value="<?php echo $address_info['email'];?>" verify="email"/></li>
            <?php } ?>

            <li class="text"><span class="left"><b>*</b> <?php echo $entry_address_1;?></span><input name="address_1" type="text" value="<?php echo $address_info['address_1'];?>" verify="address"/></li>
            <li class="text"><span class="left"> <?php echo $entry_address_2;?></span><input name="address_2" type="text" value="<?php echo $address_info['address_2'];?>" verify="address"/></li>
            <li class="text"><span class="left"><b>*</b>  <?php echo $entry_city;?></span><input name="city" type="text" value="<?php echo $address_info['city'];?>" verify="city"/></li>
            <li class="text"><span class="left"><b>*</b> <?php echo $entry_postcode;?></span><input name="postcode" type="text" value="<?php echo $address_info['postcode'];?>" verify="notnull"/></li>
            <li class="text"><span class="left"><b>*</b>  <?php echo $entry_country;?></span>
                <select name="country_id" class="large-field"  verify="notnull">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $address_info['country_id']) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>

            </li>
            <li class="text"><span class="left"><b>*</b> <?php echo $entry_zone;?></span>
                <input name="zone" type="text"  verify="notnull" value="<?php echo $address_info['zone']; ?>" <?php if($zones){ ?> style="display:none" <?php } ?>/>
                <select name="zone_id"  verify="notnull" class="large-field" <?php if(!$zones) { ?> style="display:none" <?php } ?>>

                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($zones as $zone) { ?>
                    
                    <?php if ($zone['zone_id'] == $address_info['zone_id']) { ?>
                    <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>

                </select>
            </li>




            <li class="text"><span class="left"><b>*</b> <?php echo $entry_phone;?></span><input name="phone" type="text"  value="<?php echo $address_info['phone'];?>" verify="notnull"/></li>
        </ul>
        <div class="form_under">
            
            <input type="hidden" value="<?php if($have_billing_address){ echo $address_info['address_id'];} ?>" name="address_id" /> 

            <input type="button" class="common-btn-orange send" value="Save" id='update_billing_address_button'/>
             <?php if($have_billing_address){ ?>
            <input type="button" class="common-btn-gray" value="Cancel" id='cancel_billing_address' />
            <?php } ?>
            
            <input type="hidden" value="<?php if($have_billing_address){ ?>1<?php }else{ ?>0<?php } ?>" name="have_billing_address" /> 
            
        </div>


        
    </div>


<script>
    /******************************************************/
   $('#add_billing_address_from select[name=\'country_id\']').live('change', function() {
        if (this.value == '')
            return;
        $.ajax({
            url: '/index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
				if($(".wait").length>0){
					$(".wait").show();
				}else{
					 $('#add_billing_address_from select[name=\'country_id\']').after('<span class="wait"><img src="<?php  echo STATIC_SERVER; ?>css/images/loader_16x16.gif" alt="" /></span>');
				}
               
            },
            complete: function() {
                $('.wait').hide();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }
                html = '';
                if (json['zone'] != '') {
					html = '<option value="">Please Select...</option>';
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }

                    $('#add_billing_address_from select[name=\'zone_id\']').html(html);
                    $('#add_billing_address_from input[name=\'zone\']').hide();
                    $('#add_billing_address_from select[name=\'zone_id\']').show();
                } else {
                    $('#add_billing_address_from input[name=\'zone\']').val('');
                    $('#add_billing_address_from input[name=\'zone\']').show();
                    $('#add_billing_address_from select[name=\'zone_id\']').hide();
                }



            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#update_billing_address_button').live('click', function() {
        
        //form 验证
        //提交，最终验证。
       
        $("#add_billing_address_from input,#add_billing_address_from select").trigger('blur');
        var numError = $('#add_billing_address_from .redborder').length;

        if(numError){
            return false;
        }
        
        $.ajax({
            url: '/index.php?route=checkout/address/saveBillingAddress',
            type: 'post',
            data: $('#add_billing_address_from input,#add_billing_address_from select').serialize(),
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 0) {
                    $('#add_billing_address_from').hide();
                    var billing_address_html = 
                    json.billing_address.firstname + ' '
                    + json.billing_address.lastname + ' '
                    + json.billing_address.address_1 + ' ' 
                    + json.billing_address.address_2 + " " 
                    + json.billing_address.city + " "
                    + json.billing_address.zone + " "
                    + json.billing_address.country + "("
                    + json.billing_address.postcode + ")"
                    + json.billing_address.phone ;
                    console.log(billing_address_html);
                    $('#payment_box').html(billing_address_html);
                    $("#ship_info").show();
                    $('#add_billing_address_from input[name=address_id]').val(json.billing_address.address_id);
                    $('#add_billing_address_from input[name=have_billing_address]').val('1');
                    
                } else {
                    $('#add_billing_address_from select[name=\'country_id\']').trigger('change');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#edit_billing_address').live('click',function(){
        $('#add_billing_address_from').show();
    });
    $('#cancel_billing_address').live('click',function(){
            $('#add_billing_address_from').hide();
    });
</script>
