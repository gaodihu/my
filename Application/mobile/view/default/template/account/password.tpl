<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>
<?php if($success){ ?>
<div class="msg-info" style="color:red"><?php echo $success;?></div>
<?php } ?>
<div class="spacing"></div>
<form action="<?php echo $action;?>" method="post" id='change_pw_form'>
<section>
	<div class="login-form form2">
		<div><i class="icon-unlock"></i>
		<input type="password" name="old_password" class="" placeholder="<?php echo $entry_old_password;?>" verify="notnull" value="<?php echo $old_password;?>"/>
		<div class="error"><?php echo $error_old_password;?></div>
		</div>
		<div><i class="icon-lock"></i>
		<input type="password" name="new_password" class=""  placeholder="<?php echo $entry_password;?>" verify="pw1" value="<?php echo $new_password;?>"/>
		<div class="error"><?php echo $error_password;?></div>
		</div>
		<div><i class="icon-lock"></i>
		<input type="password" name="confirm" class="" placeholder="<?php echo $entry_confirm;?>" verify="pw2" value="<?php echo $confirm;?>"/>
		<div class="error"><?php echo $error_confirm;?></div>
		</div>
	</div>
</section>
    <div class="login-box">
        <a class="orange-bg button send"><?php echo $button_submit;?></a><br/>
    </div>
</form>


<script type="text/javascript" >
   function formSubmit(){
		$("#change_pw_form").submit();
   }
</script>
<?php echo $footer; ?>