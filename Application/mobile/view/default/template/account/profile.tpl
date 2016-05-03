<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>
<?php if($success){ ?>
<div class="msg-info success"><?php echo $success;?></div>
<?php } ?>
<form action="<?php echo $action;?>" method="post" >
<div class="form">
	<ul  class="form-list">
		<li>
			<label><?php echo $entry_email;?>:<i>*</i></label>
			<input type="text" value="<?php echo $customer['email'];?>" disabled='1'/>
			
		</li>
<!-- 		<li>
			<label><?php echo $entry_nickname;?>:</label>
			<input type="text" value="<?php echo $customer['nickname'];?>"  name='nickname'/>
			
		</li> -->
		<li>
			<label><?php echo $entry_frist_name;?>:<i>*</i></label>
			<input type="text" value="<?php echo $customer['firstname'];?>" verify="user" name='firstname'/>
			
		</li>
		<li>
			<label><?php echo $entry_last_name;?>  <i>*</i></label>
			<input type="text" value="<?php echo $customer['lastname'];?>" verify="user" verify="user" name='lastname'/>
		</li>
		<li>
			<label><?php echo $entry_telphone;?>: <i>*</i></label>
			<input type="text" value="<?php echo $customer['telephone'];?>" verify="phone" name='telephone'/>
		</li>
		<li>
			<label><?php echo $entry_gender;?>: </label>
			<select  verify="notnull" name='gender'>
				 <option value="0"> <?php echo $text_select;?> </option>
				 <?php if($customer['gender']==1){ ?>
                 <option value="1" selected="selected"><?php echo $text_male;?></option>
                 <option value="2"><?php echo $text_female;?></option>
				 <?php }elseif($customer['gender']==2){ ?>
				 <option value="1" ><?php echo $text_male;?></option>
                 <option value="2" selected="selected"><?php echo $text_female;?></option>
				<?php }else{ ?>
				 <option value="1" ><?php echo $text_male;?></option>
                 <option value="2" ><?php echo $text_female;?></option>
				<?php } ?>
			</select>
		</li>
	</ul>
	 <div style="padding: 1em;text-align: center" class="checkout-btn">
            <input class="button orange-bg send" value="<?php echo $button_submit;?>" type="submit">
        </div>
	
</div>
</form>
<?php echo $footer; ?>