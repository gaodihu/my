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
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*(The unique identification code, do not suggest changes)</span> <?php echo $entry_banner_code; ?></td>
            <td><input type="text" name="banner_code" value="<?php echo $banner_code; ?>" size="100" />
              <?php if ($error_banner_code) { ?>
              <span class="error"><?php echo $error_banner_code; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td>banner width</td>
            <td><input type="text" name="banner_width" value="<?php echo $banner_width; ?>" size="100" />
              <?php if ($error_banner_width) { ?>
              <span class="error"><?php echo $error_banner_width;?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td>banner height</td>
            <td><input type="text" name="banner_height" value="<?php echo $banner_height; ?>" size="100" />
              <?php if ($error_banner_height) { ?>
              <span class="error"><?php echo $error_banner_hright;?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
        
		<div id="tab-general" style="display: block;">
          <div class="htabs" id="languages">
		  				<?php foreach ($languages as $language) { ?>
                        <a  style="display: inline;" lang_id ='<?php echo $language['language_id'];?>'><img title="<?php echo $language['name'];?>" src="view/image/flags/<?php echo $language['image']; ?>"><?php echo $language['name'];?></a>
						<?php
						}
						?>
                      </div>	
			<?php foreach ($languages as $language) {
				$language_id =$language['language_id'];
				
			 ?>
			
           <div id="language<?php echo $language['language_id'];?>" style="display:none" class="banner_info">
              <table id="images" class="list">
          <thead>
            <tr>
			  <td class="left">ID</td>
			  <?php if($banner_code=='catagory_top_banner'){ ?>
			  <td class="left">catagory</td>
			  <?php } ?>
              <td class="left"><?php echo $entry_title; ?></td>
              <td class="left"><?php echo $entry_link; ?></td>
			  <td class="left">sort order</td>
              <td class="left"><?php echo $entry_image; ?></td>
              <td>start time</td>
              <td>end time</td>
              <td>status</td>
			  <td>action</td>
            </tr>
          </thead>
		  <?php  $image_row=0;?>
          <?php if(isset($banner_images[$language_id])){
		  
		  	foreach ($banner_images[$language_id] as $banner_image) { 
			
		  ?>
          <tbody id="image-row<?php echo $image_row; ?>">
            <tr>
				<td class="left">
                <?php echo $banner_image['id']; ?>
               </td>
			  <?php if($banner_code=='catagory_top_banner'){ ?>
			  <td class="left">
					<select name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][catagory_id]" >
						<?php if($all_catagory){ ?>
						<?php foreach($all_catagory as $cat){ ?>
						<option value="<?php echo $cat['category_id'];?>" <?php if($banner_image['category_id']==$cat['category_id']){?> selected='selected' <?php } ?> ><?php echo $cat['name'];?></option>
							<?php if($cat['child']){ ?>
								<?php foreach($cat['child'] as $c_cat){ ?>
								<option value="<?php echo $c_cat['category_id'];?>" <?php if($banner_image['category_id']==$c_cat['category_id']){?> selected='selected' <?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $c_cat['name'];?></option>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php } ?>
					</select>
			  </td>
			  <?php } ?>
              <td class="left">
                <input type="text" name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][title]" value="<?php echo $banner_image['title']; ?>" />
               </td>
              <td class="left"><input type="text" name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" /></td>
	      <td class="left"><input type="text" name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][sort]" value="<?php echo $banner_image['sort']; ?>" /></td>
              <td class="left"><div class="image"><img src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb<?php echo $language_id;?><?php echo $image_row; ?>" />
                  <input type="hidden" name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="image<?php echo $language_id;?><?php echo $image_row; ?>"  />
                  <br />
                  <a onclick="image_upload('image<?php echo $language_id;?><?php echo $image_row; ?>', 'thumb<?php echo $language_id;?><?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
              
                  <td class="left"><input type='text' name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][start_time]" value="<?php echo $banner_image['start_time'] ?>"  class="datetime "></td>
              <td class="left"><input type='text' name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][end_time]" value="<?php echo $banner_image['end_time'] ?>" class="datetime "></td>
              
              <td class="left"><select  name="bannner_info[<?php echo $language_id;?>][<?php echo $image_row; ?>][status]" ><option value="1" <?php if($banner_image['status'] == 1){ ?>selected="selected"<?php } ?>>enabled</option><option  value="0"  <?php if($banner_image['status'] == 0) { ?>selected="selected"<?php } ?>>disabled</option></select></td>
              
                  <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
		  <?php $image_row++;?>
          <?php }} ?>
		  
          <tfoot>
            <tr>
              <td colspan="8"></td>
              <td class="left"><a onclick="addImage(<?php echo $language_id;?>);" class="button"><?php echo $button_add_banner; ?></a></td>
            </tr>
          </tfoot>
        </table>
          </div>
           <?php } ?>

       </div>
      </form>
    </div>
  </div>
</div>
<?php $image_row_col =isset($banner_images[1])?count($banner_images[1]):0;?>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row_col; ?>;

function addImage(lang_id) {
    html  = '<tbody id="image-row'+image_row+'">';
	html += '<tr>';
	html += '<td class="left">';
	html += '</td>';	
 <?php if($banner_code=='catagory_top_banner'){ ?>
	html += '<td class="left">';
	html += '<select name="bannner_info[' + lang_id + ']['+image_row+'][catagory_id]" >';
	<?php if($all_catagory){ ?>
	<?php foreach($all_catagory as $cat){ ?>
		html +='<option value="<?php echo $cat["category_id"];?>"><?php echo $cat["name"];?></option>';
		<?php if($cat['child']){ ?>
			<?php foreach($cat['child'] as $c_cat){ ?>
			html +='<option value="<?php echo $c_cat["category_id"];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $c_cat["name"];?></option>';
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<?php } ?>
html +='</select>';
	html += '</td>';	
 <?php } ?>
    html += '<td class="left">';
	html += '<input type="text" name="bannner_info[' + lang_id + ']['+image_row+'][title]" value="" /><br />';
   
	html += '</td>';	
	html += '<td class="left"><input type="text" name="bannner_info[' + lang_id + ']['+image_row+'][link]" value="" /></td>';
	html += '<td class="left"><input type="text" name="bannner_info[' + lang_id + ']['+image_row+'][sort]" value="" /></td>';	
	html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb'+lang_id + image_row + '" /><input type="hidden" name="bannner_info[' + +lang_id + ']['+image_row+'][image]" value="" id="image' +lang_id+ image_row + '" /><br /><a onclick="image_upload(\'image' +lang_id+ image_row + '\', \'thumb'+lang_id + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' +lang_id+ image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' +lang_id+ image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	
	
        html += '<td class="left"><input type="text" name="bannner_info[' + lang_id + ']['+image_row+'][start_time]" value="" class="datetime " ></td>';
        html += '<td class="left"><input type="text" name="bannner_info[' + lang_id + ']['+image_row+'][end_time]" value="" class="datetime" ></td>';
        
        html += '<td class="left"><select name="bannner_info[' + lang_id + ']['+image_row+'][status]"><option value="1">enabled</option><option value="0">disabled</option></select></td>';
        
        html += '<td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '</tr>';
	html += '</tbody>'; 
	
	$('#language'+lang_id+'>#images tfoot').before(html);
	
	image_row++;
        
        $('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
    });
}
//--></script>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

<script type="text/javascript">
$("#languages a").click(function(){
	var lang_id =$(this).attr('lang_id');
	$("#languages a").removeClass('selected');
	$(this).addClass('selected');
	$(this).siblings().removeClass('selected').end().addClass('selected');
	$('.banner_info').hide();
	$('#language'+lang_id).show();
});
$(document).ready(function(){
  $("#tab-general").find('.banner_info:first').show();
  
  $('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss',
	showSecond: true,
	changeMonth: true,
	changeYear: true
    });
});


</script>


<?php echo $footer; ?>