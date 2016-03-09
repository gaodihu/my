<?php echo $header; ?>
<section class="wrap">
    <?php if(isset($error_email)){ ?>
    <div class="newsletter">
        <div class="newsletter_r">

            <p><?php echo $error_email;?></p>
        </div>
    </div>
    <?php }else{ ?>
    <div class="newsletter mt_20">
        <div class=""></div>
        <div class="">
            <h1><img src="<?php  echo STATIC_SERVER; ?>css/images/public/yes.gif" width="45" height="40" original="<?php  echo STATIC_SERVER; ?>css/images/public/yes.gif" style="vertical-align: middle"/> successfullly  <?php echo $heading_title?></h1>
            <div class="res_message mt_20">
                <p><?php echo $success_text;?></p>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($redirect) { ?>
    <script>
        setTimeout(function(){
            window.location="<?php echo $redirect; ?>";
        },5000)


    </script>
    <?php } ?>
</section>
<div class="clear"></div>
<?php echo $footer; ?>