<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php	
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>

<section class="box wrap clearfix">
	<?php echo $menu;?>
	<section class="boxRight">
		<?php echo $right_top;?>
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
		<?php if ($success) { ?>
			<div class="success"><?php echo $success; ?><img src="<?php  echo STATIC_SERVER; ?>css/images/close.png" alt="" class="close" /></div>
		<?php } ?>
		<section class="mt_20">
		<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
          <table class="profile_table">
        <tbody><tr>
            <th><?php echo $entry_email;?> </th>
            <td><?php echo $customer['email'];?></td>
        </tr>
        <tr>
            <th><?php echo $entry_nickname;?></th>
            <td><?php echo $customer['nickname'];?></td>
        </tr>
        <tr>
            <th> <?php echo $entry_frist_name;?></th>
            <td>
                <input type="text" value="<?php echo $customer['firstname'];?>" name="firstname" maxlength="100"  class="long_txt">
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $entry_last_name;?>
            </th>
            <td>
                <input type="text" value="<?php echo $customer['lastname'];?>" name="lastname" maxlength="100"  class="long_txt valid">
            </td>
        </tr>
		<tr>
            <th>
                <?php echo $entry_telphone;?>
            </th>
            <td>
                <input type="text" value="<?php echo $customer['telephone'];?>" name="telephone" maxlength="100"  class="long_txt valid">
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $entry_gender;?>
            </th>
            <td>
                <select jvalue="0" name="gender" id="Gender" class="long_opt">
                    <option value="0"> Please Select </option>
                    <option value="1" <?php if ($customer['gender'] ==1) { ?> selected="selected" <?php } ?>>Male</option>
                    <option value="2" <?php if ($customer['gender'] ==2) { ?> selected="selected" <?php } ?>>Female</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $entry_birthday;?>
            </th>
            <td> 
				 <input type="text" value="<?php echo $customer['birthday'];?>" name="birthday"  class="datetime">
            </td>
        </tr> 
        <tr>
            <th>
               <?php echo $entry_country;?>
            </th>
            <td>
               <select name="country_id" class="large-field long_txt">
					  <option value=""><?php echo $text_select; ?></option>
					  <?php foreach ($countries as $country) { ?>
					  <?php if ($country['country_id'] == $customer['country_id']) { ?>
					  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
					  <?php } else { ?>
					  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
					  <?php } ?>
					  <?php } ?>
        		</select>
            </td>
        </tr>
        <tr>
            <th valign="top">
                <?php echo $entry_image_upload;?>
            </th>
            <td style="padding-bottom: 10px;">
                <div style="position: relative;">
                    <input readonly="" style="width: 142px; height: 24px; line-height: 24px; border: solid 1px #d5d5d5;
                        border-radius: 3px; z-index: 1" name="path" value="">
                    <input type="button" style="height: 26px; line-height: 26px; cursor: pointer; position:relative;" value="browsing">
                    <input type="file" style="position: absolute; width: 220px; height: 30px; background: #f00; left: 0;
                        z-index: 9999; -moz-opacity: 0; filter: alpha(opacity: 0); opacity: 0; font-size: 13px;" onchange="fileonChange(this)" id="imageupload" name="imageupload">
                </div>
                <label class="hint">
                    <?php echo $entry_image_advised;?></label>
            </td>
        </tr>
        <tr>
            <th valign="top">
                <?php echo $entry_image_preview;?>
            </th>
            <td class="image_preview">
                    <img src="<?php echo $customer['avatar'];?>" width="50" height="50">
            </td>
        </tr>
        <tr>
            <th>
            </th>
            <td>
                <p class="save_box">
                   <input type="submit" value="Save" class="common-btn-orange">
                </p>
            </td>
        </tr>
    </tbody></table>
		</form>
        </section>
        <?php echo $right_bottom;?>
	</section>	
</section>
<script type="text/javascript" language="javascript">
    function fileonChange(sender) {
        var $file = $(sender);
		var val =$file.val();
		if(CheckFile(val)){
			$('input[name=\'path\']').val($file.val());
          	return true;
		} 
		else{
			return false;	
		}    
    }
	
function CheckFile(f)
 {
 	var image_limit ="<?php echo $entry_image_limit;?>";
	//判断图片类型
	if(!/\.(gif|jpg|jpeg|bmp)$/.test(f))
	{
		alert(image_limit);
		return false;
	}
	return true;
}
</script>
<script>
  $('.datetime').datepicker({
	dateFormat: 'yy-mm-dd',
	showSecond: false,
	changeMonth: true,
	changeYear: true
    });
$(function() {
	$('.flexslider').flexslider({
		animation: "slide",
		controlNav: true,
		slideshow: false,
		animationLoop: false,
		itemWidth: 180,
		itemMargin: 0,
		minItems: 5,
		maxItems: 5
	  });
	  
});
</script>
<?php echo $footer; ?>