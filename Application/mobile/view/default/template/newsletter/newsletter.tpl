<?php echo $header; ?>
<section class="wrap text-c content-box  msg-side">
  <?php if(isset($error_email)){ ?>
  <div class="newsletter ">
    <div class="newsletter_r">
    
      <p><?php echo $error_email;?></p>
    </div>
  </div>
  <?php }else{ ?>
  <div class="newsletter ">
    <div class="newsletter_l"><img src="/catalog/view/theme/default/images/icon_envelope48x35.png" width="48" height="35"></div>
    <div class="newsletter_r">
      <h2><?php echo $heading_title?></h2>
      <p><?php echo $suceess;?></p>
    </div>
  </div>
  <?php } ?>
</section>
<div class="clear"></div>
<?php echo $footer; ?>