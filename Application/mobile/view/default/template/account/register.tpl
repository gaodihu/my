<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a>Create a New Account</div>
<form action="<?php echo $action;?>" method="post" id="form2" AUTOCOMPLETE="off">
<div class="spacing"></div>
		<section>
			<div class="login-form form2">
				<div><i class="icon-envelope" style="font-size:2.2em;top:0.6em;"></i>
				<input type="text" name="email" class="" placeholder="<?php echo $entry_email;?>" value="<?php echo $email;?>" verify="email"/>
				<div class='waring'><?php echo $error_email;?></div>
				<div class='wating_emial waring'></div>
				</div>
				<div><i class="icon-user" style="font-size:2.7em;"></i><input type="text" name="nickname" class=""  placeholder="<?php echo $entry_name;?>" value="<?php echo $name;?>" verify="user"/>
				<div class='waring'><?php echo $error_name;?></div>
				</div>
				<div><i class="icon-lock"></i><input type="password" name="password" class="" placeholder="<?php echo $entry_password;?>" value="<?php echo $password;?>" verify="pw1"/>
				<div class='waring'><?php echo $error_password;?></div>
				</div>
				<div><i class="icon-lock"></i><input type="password" name="confirm" class=""  placeholder="<?php echo $entry_confirm;?>" value="<?php echo $confirm;?>" verify="pw2"/>
				<div class='waring'><?php echo $error_confirm;?></div>
				</div>
			</div>
		</section>

       <div class="login-box">
			<input type="button" class="orange-bg button send" value='<?php echo $text_sign_in;?>'><br/>
	   </div>
	</div>
</form>
<script type="text/javascript" src="mobile/view/js/validform.js"></script>



<script>
function formSubmit(){

    $("#form2").submit();
}
$("input[name='email']").blur(function(){
	var email =$(this).val();
	if(email){
	$.ajax({
            type: "post",
            url: '/index.php?route=account/register/check_email',
            data: 'email=' +email,
            dataType: "json",
            success: function (data) {
                if(data['message'] == ""){
                    return;
                }
                common.alertInfo(data['message'],"error");
				//$(".wating_emial").html();
			}
		});
	}
})
</script>
<?php echo $footer; ?>