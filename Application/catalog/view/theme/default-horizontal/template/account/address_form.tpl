<div class="blkbg" style="display:none;"></div>
<section class="tanchuan checkout_tc">
	<div class="checkout_con enter_new closebtn"><a href="javascript:void(0)" class="btn"></a></div>
    <div class="tanchuang_box">
        <form enctype="multipart/form-data" method="post" id="add_address_from">
        <div class="form">
        	<ul>
            	<li class="text"><span class="left">* First Name:  </span><input name="firstname" type="text" value=""/></li>
				<li class="text"><span class="left">* Last Name:  </span><input name="lastname" type="text" value=""/></li>
                <li class="text"><span class="left">* Street:</span><input name="street" type="text" value=""/></li>
                <li class="text"><span class="left">* City: </span><input name="city" type="text" value=""/></li>
               
                <li class="text"><span class="left">* Country: </span>
				<select name="country_id" class="large-field">
					  <option value=""><?php echo $text_select; ?></option>
					  <?php foreach ($countries as $country) { ?>
					  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
					 
					  <?php } ?>
        		</select></li>
				 <li class="text"><span class="left">* State/Province/Region: </span>
				 	<select name="zone_id" class="large-field">
						<option value=""><?php echo $text_select; ?></option>
				 	 </select>
				</li>
                <li class="text"><span class="left">* Zip/Postal code: </span><input name="postcode" type="text" value=""/></li>
                <li class="text"><span class="left">Phone number:</span><input name="phone" type="text"/></li>
            </ul>
            <div class="form_under">
            	<p><input name="default" type="checkbox" checked/>Set as default</p>
                <input type="button" class="common-btn-orange" value="Save" id='save_address_button'/><input type="button" class="common-btn-gray" value="Cancel" id='address-form-cancel'/>
            </div>
        </div>
		</form>
    </div>
</section>