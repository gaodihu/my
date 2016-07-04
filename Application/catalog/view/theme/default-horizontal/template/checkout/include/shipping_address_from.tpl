<!--弹窗-->
<script type="text/javascript" src="/mobile/view/js/checkout.js"></script>
<div class="blkbg" style="display:none;"></div>
<section class="tanchuan checkout_tc">
    <?php if($logged) { ?><div class="checkout_con enter_new closebtn"><a href="javascript:void(0)" class="btn"></a></div><?php } ?>
    <div class="tanchuang_box">
        <?php if(isset($address_info)){ ?>
        <form enctype="multipart/form-data" method="post" id="add_address_from">
            <div class="form selectform">
                <ul>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_firstname;?>  </span><input name="firstname" type="text" value="<?php echo $address_info['firstname'];?>" verify="user"/></li>
                    <li class="text"><span class="left"><b>*</b><?php echo $entry_lastname;?> </span><input name="lastname" type="text" value="<?php echo $address_info['lastname'];?>" verify="user"/></li>

                    <?php if(!$logged) { ?>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_email;?>  </span><input name="email" type="text" value="<?php echo $guest_email;?>" verify="email"/></li>
                    <?php } ?>
                    <li class="text"><span class="left"><?php echo $entry_company;?></span><input name="company" type="text"  value="<?php echo $address_info['company'];?>" verify="company"/></li>
					<li class="text"><span class="left"><?php echo $entry_tax_id;?></span><input name="tax_id" type="text"  value="<?php echo $address_info['tax_id'];?>" /></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_address_1;?></span><input name="address_1" type="text" value="<?php echo $address_info['address_1'];?>" verify="address"/></li>
                    <li class="text"><span class="left">  <?php echo $entry_address_2;?></span><input name="address_2" type="text" value="<?php echo $address_info['address_2'];?>" verify="address"/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_city;?></span><input name="city" type="text" value="<?php echo $address_info['city'];?>" verify="city"/></li>

                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_postcode;?></span><input name="postcode" type="text" value="<?php echo $address_info['postcode'];?>" verify="postcode"/></li>




                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_country;?></span>
                        <select name="country_id" class="large-field" verify="notnull" >
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
                    <li class="text"><span class="left add-must-tag"><b>*</b> <?php echo $entry_zone;?></span>

                        <select name="zone_id"  verify="notnull"  class="large-field entry_zone" <?php if(!$zones) { ?> style="display:none" <?php } ?>>

                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($zones as $zone) { ?>
                        <?php if ($zone['zone_id'] == $address_info['zone_id']) { ?>
                        <option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
                        <?php } ?>
                        <?php } ?>

                        </select>
                        <input name="zone" verify="notnull"  type="text"  class="entry_zone" value="<?php echo $address_info['zone']; ?>" <?php if($zones){ ?> style="display:none" <?php } ?>/>
                    </li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_phone;?></span><input name="phone" type="text"  value="<?php echo $address_info['phone'];?>" verify="number"/></li>


                </ul>
                <div class="form_under">
                    <?php if($logged){  ?>
                    <p><input name="default" type="checkbox" <?php if($is_default==1){ ?>checked="checked"<?php } ?>/><?php echo $entry_set_default;?></p>
                    <?php } ?>
                    <input type="hidden" value="<?php echo $address_info['address_id'];?>" name="address_id" />
                    <input type="hidden" name="type" value=""  id='address_type'/>
                    <input type="hidden" name="from" value=""  id='address_from'/>
                    <input type="button" class="common-btn-orange send" value="Save" id='update_address_button'/>
                    <?php if($logged) { ?>
                    <input type="button" class="common-btn-gray" value="Cancel" id='address-form-cancel'/>
                    <?php } ?>
                </div>
            </div>
        </form>
        <?php }else{ ?>
        <form enctype="multipart/form-data" method="post" id="add_address_from">
            <div class="form selectform">
                <ul>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_firstname;?>  </span><input name="firstname" type="text" value="" verify="user"/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_lastname;?>  </span><input name="lastname" type="text" value="" verify="user"/></li>
                    <?php if(!$logged) { ?>
                        <li class="text"><span class="left"><b>*</b> <?php echo $entry_email;?>  </span><input name="email" type="text" value="" verify="email"/></li>
                    <?php } ?>
					<li class="text"><span class="left"><?php echo $entry_company;?></span><input name="company" type="text"  value="" verify="company"/></li>
					<li class="text"><span class="left"><?php echo $entry_tax_id;?></span><input name="tax_id" type="text"  value=""/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_address_1;?></span><input name="address_1" type="text" value="" verify="address"/></li>
                    <li class="text"><span class="left"> <?php echo $entry_address_2;?></span><input name="address_2" type="text" value="" verify="address"/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_city;?> </span><input name="city" type="text" value="" verify="city"/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_postcode;?> </span><input name="postcode" type="text" value="" verify="postcode"/></li>
                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_country;?> </span>
                        <select name="country_id"  class="large-field" verify="notnull">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

                            <?php } ?>
                        </select>
                    </li>
                    <li class="text"><span class="left">* <?php echo $entry_zone;?> </span>

                        <select name="zone_id" class="large-field entry_zone" style="display:none"  verify="notnull">
                            <option value=""><?php echo $text_select; ?></option>
                        </select>
                        <input name="zone" verify="notnull"  type="text" value="" class="entry_zone" />
                    </li>




                    <li class="text"><span class="left"><b>*</b> <?php echo $entry_phone;?></span><input name="phone" type="text" verify="number"/></li>

                </ul>
                <div class="form_under">
                    <?php if($logged) { ?>
                    <p><input name="default" type="checkbox" checked/><?php echo $entry_set_default;?></p>
                    <?php } ?>
                    <input type="hidden" name="type" value="" id='address_type'/>
                    <input type="hidden" name="from" value=""  id='address_from'/>
                    <input type="button" class="common-btn-orange send" value="Save" id='save_address_button'/>
                     <?php if($logged) { ?>
                    <input type="button" class="common-btn-gray" value="Cancel" id='address-form-cancel'/>
                    <?php } ?>
                </div>
            </div>
        </form>
        <?php } ?>
    </div>
</section>