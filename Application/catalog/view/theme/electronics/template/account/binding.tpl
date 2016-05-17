<?php echo $header; ?>

<nav class="sidernav">
    <div class="wrap">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li>
		<span>
		<?php if($breadcrumb['href']){ ?>
            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } else{ ?>
            <?php echo $breadcrumb['text']; ?>
            <?php	} ?>
		</span>
                <?php echo $breadcrumb['separator']; ?>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="clear"></div>
</nav>
<?php if($login_error_msg) { ?>
<section class="wrap" style=" margin-top:15px;">
    <div class="warning2" style="margin-bottom: 0"><?php echo $login_error_msg; ?></div>
</section>
<?php } ?>
<section class="wrap" style="height:500px;width: 500px;margin: 15px auto 0 auto; ">
    <div class="clearfix">
    <?php if($third_customers){ ?>
        <?php foreach($third_customers as $cusotmer ) { ?>
            <?php if($cusotmer['third_from'] =='paypal'){ ?>
                <div   id="paypalAuth" style="display:inline;float:left;margin-right: 5px;"></div>
            <?php } ?>
            <?php if($cusotmer['third_from'] =='facebook'){ ?>
                <div class="loginWithFb" style="margin-right: 5px;"><a id="fbAuth" href="javascript:void(0);" class="fl"><b>f</b><?php echo $text_sign_facebook;?></a></div>
            <?php } ?>
            <?php if($cusotmer['third_from'] =='google'){ ?>
                <div id="googleAuth" class="loginWithGg" ><b><img src="<?php echo STATIC_SERVER; ?>css/images/public/google.png" width="32" height="32"></b>Log in with Google</button></div>
            <?php } ?>

        <?php } ?>
    <?php } ?>
    </div>

    <?php if($local_customer){ ?>
    <div id="sign_l">
        <h2><?php echo $text_sign_in;?></h2>
        <form action="<?php echo $login_url;?>" method="post" enctype="multipart/form-data">
            <div class="form" style="max-height: 180px;">
                <ul>

                    <li class="text"><span class="left"><?php echo $entry_email;?></span><input name="email" type="text" value="<?php echo $local_customer['email'];  ?>"/></li>
                    <li class="text" ><span class="left"><?php echo $entry_password;?></span><input name="password" type="password"/>

                        <span class="info"><?php if($error_warning_login){ echo $error_warning_login; } ?></span>

                    </li>
                </ul>

                <div class="form_under">
                    <!--<p><input name="checkbox" type="checkbox" checked/>Keep me signed in</p>-->
                    <input type="submit" class="common-btn-orange" value="<?php echo $text_sign_in;?>"/><span class="blue forgot" style="position: relative;top: 20px;"><a href="<?php echo $forgotten;?>" ><?php echo $text_forgot_password;?></a></span>
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



        </div>
    </div>
    <?php } ?>




</section>
<div class="clear"></div>
<script type="text/javascript">
    $('#register_email').blur(function(){

        var value =$(this).val();
        message ='';
        if(!checkMail(value)){
            message+='Warning:<?php echo $error_email;?>';
            $(this).next('.info').html(message);
        }else{
            $(this).next('.info').html("");
            var message_obj =$('#register_email').next('.info');
            User.checkemail(value,message_obj);
        }

    })
</script>

<?php echo $footer; ?>