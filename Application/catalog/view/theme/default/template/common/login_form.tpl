<style>
    .alert_login .PPBlue{width:176px!important;}
    .alert_login .LIwPP .PPTM, .LIwPP i {margin-top:0!important;margin-left:0!important;}
    .alert_login .PPTM i{margin-right:5px!important;}
</style>
<div id='notification'></div>
<section class="tanchuan login_tc" id='login_tc'>
	<a class="close" href="javascript:closePop()"></a>
	<ul class="tabs-list">
    	<li class="active"><a href="javascript:;"><?php echo $text_sign_in;?></a></li>
    	<li><a href="javascript:;"><?php echo $text_register_olny;?></a></li>
    </ul>
    <div class="tanchuang_box p-relative m_t20">

        <div class="form span_form_lg"  >
		  <form method="post" enctype="multipart/form-data" id='login_form'>
              <div id="login_error" class="login-error" style="display: none"></div>
              <div class="clearfix">
                    <ul >
                        <li class="text">
                            <span class="left"><?php echo $entry_email;?></span><input name="email" type="text" verify = "email" placeholder="name@mail.com" />

                        </li>
                        <li class="text"><span class="left"><?php echo $entry_password;?></span><input name="password" type="password" verify = "notnull" placeholder="Enter password"></li>
                    </ul>
                    <div class="form_under">
                     <input type="hidden"  value="" name="redirect" class="login_form_redirect">
                        <a class="common-btn-orange send" href="javascript:void(0);" id='login_form_submit'>Login</a><span class="blue forgot" ><a href="<?php echo $forgotten;?>"><?php echo $text_forgot_password;?></a></span>
                    </div>
             </div>

		  </form>
        </div>
    	<div class="form span_form_lg_2" style="display:none;">
		  <form  method="post" enctype="multipart/form-data" id='register_form'>
              <div id="error" class="login-error"></div>
              <ul class="login-list">
				<li >
                    <span class="left">* Nickname</span><input name="nickname" type="text" verify = "user" placeholder="Enter name"/>
                    <div class="formtips onError "></div>
                </li>
            	<li>
                    <span class="left">* <?php echo $entry_email;?></span><input name="email" type="text"  verify = "email" placeholder="name@mail.com" />
                    <div class="formtips onError f_emial_notice"></div>
                </li>

            	<li >
                    <span class="left">* <?php echo $entry_password;?></span><input name="password" type="password" verify = "pw1" placeholder="Enter password"/>
                    <div class="formtips onError "></div>
                </li>
            	<li >
                    <span class="left">* <?php echo $entry_confirm;?> </span><input name="confim_password" type="password" verify = "pw2" placeholder="Enter password again"/>
                    <div class="formtips onError "></div>
                </li>
            </ul>
            <div class="form_under">
            	<p><input name="newsletter" type="checkbox" checked="checked"><?php echo $text_newsletter;?></p>
				<p><input name="condition" type="checkbox" checked="checked"><?php echo $text_agree;?></p>
				 <input type="hidden"  value="" class="login_form_redirect" name="redirect">
                <a  class="common-btn-orange send"  href="javascript:void(0);"  id='register_form_submit'>Register</a>
            </div>
		 </form>
        </div>
        <div class="alert_login clearfix">
                <div   id="paypalAuth" style="display:inline-block;float:left;margin-bottom: 10px;"></div>
                <div class="loginWithFb" style="margin-bottom: 10px;"><a id="fbAuth" href="javascript:void(0);" class="fl"><b>f</b><?php echo $text_sign_facebook;?></a></div>
                <div  id="googleAuth" class="loginWithGg g-signin" style="margin-bottom: 10px;  width: 164px; "><b><img src="css/images/public/google.png" width="32" height="32"></b>Log in with Google</button></div>
                <?php if($_REQUEST['route'] == 'checkout/cart'){ ?>
                <div><a href="<?php echo $guest_checkout_link; ?>"  class="bulebtn" style="float:left;width: 175px;"><!--span class="cc"></span--><?php echo $text_checkout_as_guest; ?></a></div>
                <?php } ?>


</span>
        </div>
    </div>
<div id="fb-root" style="display: none"></div>
</section>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/validform.js"></script>

<script type="text/javascript">
$('#register_form ul li input').blur(function(){
 var  name =$(this).attr('name');
 var value =$(this).val();
 var message ='';
    $('.f_emial_notice').hide();
 if(name=='email'){
  if(value>96 || !checkMail(value)){
  	message+="Warning:<?php echo $error_email;?>";
  }
  if(message==''){
  	$.ajax({
		url: 'index.php?route=account/register/check_email',
		type: 'post',
		data: 'email='+value,
		dataType: 'json',
		success: function(json) {
			if (json['message']!='') {
				message=json['message'];
			}
		}
	});
  }
  $('.f_emial_notice').html(message);
     $('.f_emial_notice').show();
 }

})

//ajax用户注册
$('#register_form_submit').click(function(){
    var that = $(this);
    if($("#register_form").find("input[name=nickname]").val() == "" || $("#register_form").find("input[name=email]").val() == ""|| $("#register_form").find("input[name=password]").val() == ""|| $("#register_form").find("input[name=confim_password]").val() == ""){
        return;
    }
	$.ajax({
		url: 'index.php?route=account/login/register&is_ajax=1',
		type: 'post',
		data: $('#register_form').serialize(),
		dataType: 'json',
		success: function(json) {
		if (json['message']!='') {
			$('#error').html(json['message']);
            $('#error').show();
		}
		else{
            $('#error').hide();
            setBtnLoad(that,that);
			location.href=json['redirect'];
		}
	}
	});
});
//ajax用户登录
$('#login_form_submit').click(function(){
    var that = $(this);
   if($("#login_tc").find("input[name=email]").val() == "" || $("#login_tc").find("input[name=password]").val() == ""){

       return;
   }
	$.ajax({
		url: 'index.php?route=account/login/login&is_ajax=1',
		type: 'post',
		data: $('#login_form').serialize(),
		dataType: 'json',
		success: function(json) {
		if (json['message']!='') {
            $('#login_error').show();
			$('#login_error').html(json['message']);

		}
		else{
            $('#login_error').hide();
            setBtnLoad(that,that);
			location.href=json['redirect'];
		}
	}
	});
})

$("input[name=email],input[name=password]").focus(function(){

        $('#login_error').fadeOut();


})
// facebook login
var to_url = '';
<?php
    if( (isset($_GET['route']) && $_GET['route'] == 'product/product') || (isset($_GET['_route']) && $_GET['_route'] == 'product/product') ){
        echo "to_url = '';";
    } else {
         echo "to_url = '" . $this->url->link('checkout/checkout','','SSL') . "';";
    }
?>
$(function(){
		$('#fbAuth').click(function(){
			FbAuth.facebook.login(to_url);
		});
});
</script>

<script src="https://www.paypalobjects.com/js/external/api.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/paypal.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/fb_login.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/google.js"></script>
