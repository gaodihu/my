<?php echo $header; ?>


<?php if($login_error_msg) { ?>
<section class="wrap" style=" margin-top:15px;">
    <div class="warning2" style="margin-bottom: 0"><?php echo $login_error_msg; ?></div>
</section>
<?php } ?>
<section style="width: 900px;margin: 20px auto">
    <div id="sign_l">
      <h2><?php echo $text_sign_in;?></h2>
	  <form action="<?php echo $login_url;?>" method="post" enctype="multipart/form-data">
      	<div class="form" >
        	<ul>

                <li class="text"><span class="left"><?php echo $entry_email;?></span><input name="email" type="text" value="<?php if(isset($third_email)){ echo $third_email; } ?>" verify = "email" placeholder="name@mail.com"/></li>
                <li class="text" ><span class="left"><?php echo $entry_password;?></span><input name="password" type="password" verify = "notnull" placeholder="Enter password" />

					<span class="info"><?php if($error_warning_login){ echo $error_warning_login; } ?></span>

				</li>
            </ul>

            <div class="form_under">
            	<!--<p><input name="checkbox" type="checkbox" checked/>Keep me signed in</p>-->
                <input type="submit" class="common-btn-orange send" value="<?php echo $text_sign_in;?>"/><span class="gray forgot" style="position: relative;top: 20px;"><a href="<?php echo $forgotten;?>" ><?php echo $text_forgot_password;?>?</a></span>
            </div>
            <div class="clear"></div>
			<?php if(isset($text_login_to_get_5)){ ?>
            <div class="login-info" style="top:20px; text-align:center; left:0px;">
				<?php echo $text_login_to_get_5;?><br />
				<?php echo $text_coupon_code;?>
				
			</div>
			<?php } ?>
            <div class="errorinfo"><?php if($error_email_login){ echo $error_email_login;} ?></div>

        </div>
	  </form>
      <div class="clearfix top10" >


		  <div   id="paypalAuth" ></div>
          <div id="googleAuth" class="loginWithGg m_tb5 clear" ><b><img src="<?php echo STATIC_SERVER; ?>css/images/public/google.png" width="32" height="32"></b>Log in with Google</button></div>
                <div class="loginWithFb clear" ><a id="fbAuth" href="javascript:void(0);" class="fl"><b>f</b><?php echo $text_sign_facebook;?></a></div>

        </div>
        </div>
    <div id="sign_r">
      <h2><?php echo $text_register;?></h2>
	  <?php if($error_warning_reg){ ?>
			<div class="re_warn"><?php echo $error_warning_reg;?></div>
	  <?php } ?>
	  <form action="<?php echo $register_url;?>" method="post" enctype="multipart/form-data">
      <div class="form">
        	<ul>

            	<li class="text"><span class="left">* <?php echo $entry_email;?></span><input name="email" type="text" value="<?php echo $email;?>" id='register_email' verify = "email"  placeholder="name@mail.com"/>

					<div class="formtips onError"><?php if($error_email_reg){echo $error_email_reg; } ?></div>


				</li>
                <li class="text"><span class="left">* <?php echo $entry_nickname;?></span><input name="nickname" type="text" value="<?php echo $nickname;?>"  verify = "user"  placeholder="Enter name"/>
					<div class="formtips onError"><?php if($error_nickname){ echo $error_nickname;} ?></div>
				</li>
            	<li class="text"><span class="left">* <?php echo $entry_password;?></span><input name="password" type="password" value="<?php echo $password;?>"  verify = "pw1"  placeholder="Enter password"/>
					<div class="formtips onError"><?php if($error_password){echo $error_password ;}?></div>
				</li>
            	<li class="text"><span class="left">* <?php echo $entry_confirm;?></span><input name="confirm" type="password" value="<?php echo $confirm;?>"  verify = "pw2"  placeholder="Enter password again"/>
					<div class="formtips onError"><?php if($error_confirm){echo $error_confirm;} ?></div>
				</li>

            </ul>
            <div class="form_under">
            	<div><input name="newsletter" type="checkbox"  checked="checked" style="float: none"  /><?php echo $text_newsletter;?></div>
				<div><input name="conditions" type="checkbox"  checked="checked"  /><?php echo $text_agree;?>
					<span class="re_warn"><?php if($error_conditions){echo $error_conditions;}?></span>
                </div>
				<input name="redirect" type="hidden" value="<?php echo isset($redirect)?$redirect:'';?>"/>
                <input type="submit" class="common-btn-orange send" value="<?php echo $text_register_button;?>"/>
            </div>
        </div>

	  </form>

	</div>

</section>
<div class="clear"></div>
<script type="text/javascript" src="js/jquery/validform.js"></script>

<script type="text/javascript">
$('#register_email').blur(function(){
    $(this).next('.formtips').hide();
        var value =$(this).val();
        message ='';
    if(!checkMail(value)){
        message+='Warning:<?php echo $error_email;?>';
        $(this).next('.formtips').html(message);
    }else{
        $(this).next('.formtips').html("");
        var message_obj =$('#register_email').next('.info');
        User.checkemail(value,message_obj);
    }

})
</script>

<?php echo $footer; ?>