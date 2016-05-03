<?php echo $header; ?>

<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $text_login;?><a class="register" href="<?php echo $register_url;?>"><?php echo $text_register;?></a></div>
<form action='<?php echo $login_url;?>' method='post' id='login_form'>
<div class="spacing"></div>
<section>
	<div class="login-form">
		<div><i class="icon-user"></i><input type="text" class="username"  name ='email' placeholder="E-mail or Username" value="<?php echo $email;?>"/></div>
		<div><i class="icon-lock"></i><input type="password" class="password"  name ='password' placeholder="Password" value="<?php echo $password;?>"/></div>
	</div>
	<div class='login_error' style='<?php if ($error_login=="") {echo 'display:none';} ?>' ><?php echo $error_login;?></div>
</section>

<div class="login-box">
	<input type="submit" class="orange-bg button"  style="height: 3em;line-height: 3em;cursor:pointer;" value="<?php echo strtoupper($text_login);?>"/><br/>
	<a class="forgot button" style="margin-top: 50px;" href="<?php echo $forgotten;?>"><?php echo $text_forgot_password;?></a>
</div>
</form>

<?php echo $footer; ?>