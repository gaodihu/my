<?php echo $header; ?>

<div class="head-title">
    <a class="icon-angle-left left-btn"></a><?php echo $heading_title; ?>
</div>
<section class="form">
<?php if ($error_warning) { ?>
<div class="warning"></div>
<?php } ?>

    <div class="formbox clearfix" >
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">


            <ul class="form-list">
                <li class="form-tit"><?php echo $text_your_email; ?><br/><?php echo $text_email; ?></li>
                <li>
                <label><?php echo $entry_email; ?></label>
                <input type="text" name="email" value="" />
                </li>
            </ul>



              <div style="padding: 1em;text-align: center" class="checkout-btn">
                  <input type="submit" value="SUBMIT" class="button orange-bg send" />
              </div>
          </form>
   </div>
</section>

<?php echo $footer; ?>