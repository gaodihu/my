<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/review.png" alt="" /> FAQS Reply</h1>
      <div class="buttons"><!-- <a href="<?php echo $delete; ?>" class="button">delete reply</a> --><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		
		  <tr>
            <td> faqs text</td>
            <td><?php echo $faq_text;?></td>
          </tr>	
          <tr>
            <td><span class="required">*</span> reply text</td>
            <td>
			  <textarea name="reply_text" cols="60" rows="8"><?php echo $reply_text; ?></textarea>
              <?php if ($error_reply_text) { ?>
              <span class="error"><?php echo $error_reply_text; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </form>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>