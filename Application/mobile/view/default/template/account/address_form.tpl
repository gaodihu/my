<?php echo $header; ?>
<script>
    // 鍔犺浇閬僵灞�
    common.lodShow();
    function onSubmitFn(){
        if(common.setBtnLoad($(".send"),false,$(".send"))){
            return false
        }
    }

    function fn_change_country(id) {
        common.lodShow();
        $('input[name=zone]').removeClass("redborder");
        $('input[name=zone_id]').removeClass("redborder");
        var id_val = $("#"+id).val();
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + id_val,
            dataType: 'json',
            beforeSend: function() {
                $(".change_box_2").show();
                $(".change_box_1").hide();
            },
            complete: function() {
                $(".change_box_2").hide();
                $(".change_box_1").show();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }
                var html = '';

                if (json['zone'] != '') {
                    html = '<option value="">Please Select...</option>';
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';
                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }

                    $('#address_from select[name=zone_id]').html(html);
                    $('#address_from select[name=zone_id]').show();
                    $('#address_from input[name=zone]').hide();

                } else {
                    $('#address_from input[name=zone]').show();
                    $('#address_from select[name=zone_id]').hide();
                    $('#address_from input[name=zone]').val("");

                }
                $(".change_box_2").hide();
                $(".change_box_1").show();
                common.lodHide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                common.lodHide();
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    common.lodHide();
</script>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $text_address;?></div>
<?php if($address_info){ ?>
<form action="<?php echo $action;?>" method="post" id="address_from" onsubmit="return onSubmitFn()">
 <div class="form">
            <ul  class="form-list">
                <li>
                    <label><?php echo $entry_firstname;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $address_info['firstname'];?>"  verify="user" name='firstname'/>
					<div class='error'><?php echo $error_firstname;?></div>
                </li>
                <li>
                    <label><?php echo $entry_lastname;?> : <i>*</i></label>
                    <input type="text" value="<?php echo $address_info['lastname'];?>" verify="user" name='lastname'/>
					<div class='error'><?php echo $error_lastname;?></div>
                </li>
				<?php if(!$logged) { ?>
				  <li>
                    <label><?php echo $entry_email;?> :  <i>*</i></label>
                    <input type="text" value="<?php echo $address_info['email'];?>" verify="email" name='email'/>
					<div class='error'><?php echo $error_email;?></div>
                </li>
				<?php }?>
                <li>
                    <label><?php echo $entry_address_1;?>: <i>*</i></label>
                    <input type="text" value="<?php echo $address_info['address_1'];?>" verify="address" name='address_1'/>
					<div class='error'><?php echo $error_address_1;?></div>
                </li>
                <li>
                    <label><?php echo $entry_address_2;?>: </label>
                    <input type="text" value="<?php echo $address_info['address_2'];?>" verify="address" name='address_2'/>
					<div class='error'><?php echo $error_address_2;?></div>
                </li>
                <li>
                    <label><?php echo $entry_city;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $address_info['city'];?>" verify="city" name='city'/>
					<div class='error'><?php echo $error_city;?></div>
                </li>
                <li>
                    <label><?php echo $entry_country;?>:  <i>*</i></label>
                    <select  verify="notnull" name='country_id' id="country_id_1" onchange="fn_change_country('country_id_1')">
                        <option value=""><?php echo $text_select;?></option>
						<?php foreach($countries as $country){ ?>
						<?php if ($country['country_id'] == $address_info['country_id']) { ?>
						<option value="<?php echo $country['country_id'];?>" selected="selected"><?php echo $country['name'];?></option>
						<?php }else{ ?>
                        <option value="<?php echo $country['country_id'];?>"><?php echo $country['name'];?></option>
						<?php } ?>
						<?php } ?>
                    </select>
					<div class='error'><?php echo $error_country;?></div>
                </li>
                <li>
                    <label><?php echo $entry_zone;?>:<i>*</i></label>

                    <select  verify="notnull"   name='zone_id' <?php if(!$zones){ ?> style="display:none" <?php } ?>>
                        <option value=""><?php echo $text_select;?></option>
						<?php foreach ($zones as $zone) { ?>
						<?php if ($zone['zone_id'] == $address_info['zone_id']) { ?>
						<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
                     </select>

					 <input name="zone" verify="notnull"  type="text"  class="entry_zone" value="<?php echo $address_info['zone']; ?>" <?php if($zones){ ?> style="display:none" <?php } ?>/>
					 <div class='error'><?php echo $error_zone;?></div>
                </li>
                <li>
                    <label><?php echo $entry_postcode;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $address_info['postcode'];?>" verify="postcode" name='postcode'//>
					<div class='error'><?php echo $error_postcode;?></div>
                </li>
                <li>
                    <label><?php echo $entry_phone;?>:<i></i></label>
                    <input type="text" value="<?php echo $address_info['phone'];?>" verify="number" name='phone'/>
                </li>
            </ul>
        </div>
        <div class="spacing"></div>
		<input type='hidden' value="<?php echo $address_info['address_id'];?>" name="address_id">
        <div style="padding: 1em;text-align: center" class="checkout-btn">
            <input class="button orange-bg send" value="<?php echo $button_submit;?>" type="submit">
        </div>
</form>
<?php }else{ ?>
<form action="<?php echo $action;?>" method="post"  id='address_from' onsubmit="return onSubmitFn()">
<div class="form">
            <ul  class="form-list">
                <li>
                    <label><?php echo $entry_firstname;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $firstname;?>"  verify="user" name='firstname'/>
					<div class='error'><?php echo $error_firstname;?></div>
                </li>
                <li>
                    <label><?php echo $entry_lastname;?> : <i>*</i></label>
                    <input type="text" value="<?php echo $lastname;?>" verify="user" name='lastname'/>
					<div class='error'><?php echo $error_lastname;?></div>
                </li>
				<?php if(!$logged) { ?>
				  <li>
                    <label><?php echo $entry_email;?> :  <i>*</i></label>
                    <input type="text" value="<?php echo $email;?>" verify="email" name='email'/>
					<div class='error'><?php echo $error_email;?></div>
                </li>
				<?php }?>
                <li>
                    <label><?php echo $entry_address_1;?>: <i>*</i></label>
                    <input type="text" value="<?php echo $address_1;?>" verify="address" name='address_1'/>
					<div class='error'><?php echo $error_address_1;?></div>
                </li>
                <li>
                    <label><?php echo $entry_address_2;?>: </label>
                    <input type="text" value="<?php echo $address_2;?>" verify="address" name='address_2'/>
					<div class='error'><?php echo $error_address_2;?></div>
                </li>
                <li>
                    <label><?php echo $entry_city;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $city;?>" verify="city" name='city'/>
					<div class='error'><?php echo $error_city;?></div>
                </li>
                <li>
                    <label><?php echo $entry_country;?>:<i>*</i></label>
                    <select  verify="notnull" name='country_id' id="country_id_2" onchange="fn_change_country('country_id_2')">
                        <option value=""><?php echo $text_select;?></option>
						<?php foreach($countries as $country){ ?>
                                                <option value="<?php echo $country['country_id'];?>"  <?php if($country['iso_code_2'] == $default_country_code) {?>selected="selected"<?php } ?>><?php echo $country['name'];?></option>
						<?php } ?>
                    </select>
					<div class='error'><?php echo $error_country;?></div>
                </li>
                <li>

                    <label><?php echo $entry_zone;?>:<i>*</i></label>
                    <div class="change_box_1" >
                        <select  verify="notnull"  name='zone_id'>
                            <option value=""><?php echo $text_select;?></option>
                            <?php foreach ($zones as $zone) { ?>
                            <?php if ($zone['zone_id'] == $address_info['zone_id']) { ?>
                            <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                         </select>
                         <input name="zone" verify="notnull"  type="text" value="" class="entry_zone"  style="display: none"/>
                         <div class='error'><?php echo $error_zone;?></div>
                     </div>
                    <div class="change_box_2 " style="display: none">
                            Lodding...
                    </div>
                </li>
                 <li>
                    <label><?php echo $entry_postcode;?>:<i>*</i></label>
                    <input type="text" value="<?php echo $postcode;?>" verify="postcode" name='postcode'/>
					<div class='error'><?php echo $error_postcode;?></div>
                </li>
                <li>
                    <label><?php echo $entry_phone;?>:<i></i></label>
                    <input type="text" value="<?php echo $phone;?>" verify="number" name='phone'/>
                </li>
            </ul>
        </div>
        <div class="spacing"></div>
        <div style="padding: 1em;text-align: center" class="checkout-btn">
            <input class="button orange-bg send" value="<?php echo $button_submit;?>" type="submit">
        </div>
</form>
<?php } ?>

<?php echo $footer; ?>
