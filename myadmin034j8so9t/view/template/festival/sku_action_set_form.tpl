<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1>设置商品页面活动</h1>
      <div class="buttons">
	  <a onclick="$('#prize_set_form').submit()" class="button">Save</a><a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	 <div>
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id='prize_set_form'>
        	<table class="form">
          <tr>
            <td><span class="required">*</span> all sku(多个使用逗号分隔)</td>
            <td>
				<textarea cols='60' rows='7' name='all_sku'><?php echo $sku_set_action_info['all_sku']; ?></textarea>
              <?php if ($error_sku) { ?>
              <span class="error"><?php echo $error_sku; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*</span>start time  </td>
            <td>
				<input type='text' name="start_time" value="<?php echo $sku_set_action_info['start_time']; ?>" style='width:440px;'>(example：2015-05-04 00:00:00)<br>
              <?php if ($error_start_time) { ?>
              <span class="error"><?php echo $error_start_time; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*</span> end time </td>
            <td>
				<input type='text' name="end_time" value="<?php echo $sku_set_action_info['end_time']; ?>" style='width:440px;'>(example：2015-05-14 23:00:00)<br>
              <?php if ($error_end_time) { ?>
              <span class="error"><?php echo $error_end_time; ?></span>
              <?php } ?></td>
          </tr>
		  <?php foreach($languages as $lang){ ?>
		   <?php if($lang['code']!='PT'){ ?>
		   <tr>
            <td><?php echo $lang['code'];?></td>
            <td>
				text:<input type='text' name="text[<?php echo $lang['language_id'];?>]" value="<?php echo $sku_set_desc_info[$lang['language_id']]['text'];?>" style='width:440px;'><br><br>
				link:<input type='text' name="link[<?php echo $lang['language_id'];?>]" value="<?php echo $sku_set_desc_info[$lang['language_id']]['link'];?>"  style='width:440px;'><br>
			</td>
          </tr>
		  <?php } ?>
		  <?php } ?>
        </table>
      </form>
	 </div>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>