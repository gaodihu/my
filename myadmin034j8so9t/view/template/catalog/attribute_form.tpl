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

  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		
		  <tr>
            <td><span class="required">*</span><?php echo $entry_attribute_code; ?></td>
            <td><input type="text" name="attribute_code" value="<?php echo $attribute_code; ?>"  /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td>
			  	<table>
					<tr>
						<?php
						 foreach ($languages as $language) { ?>
						<td><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></td>
						<?php } ?>
					</tr>
					<tr>
						<?php foreach ($languages as $language) { ?>
						<td> <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" /></td>
						<?php } ?>
					</tr>
				</table>
			  </td>
          </tr>

          <tr>
            <td>属性值类型</td>
            <td>
              <select name="value_type" >

                <option value="numerical" <?php if($value_type == 'numerical') { echo 'selected="selected"';} ?>>数值</option>
                <option value="text"   <?php if($value_type == 'text') { echo 'selected="selected"';} ?>>文本</option>
                <option value="radio"  <?php if($value_type == 'radio') { echo 'selected="selected"';} ?>>单选</option>
                <option value="option" <?php if($value_type == 'option') { echo 'selected="selected"';} ?>>多选</option>
              </select>
              
          </tr>


          <tr>
            <td>属性单位</td>
            <td><input type="text" name="unit"  value="<?php echo $unit; ?>" /></td>

          </tr>

          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>"  /></td>
          </tr>

          <tr dom="value_dom" <?php if($value_type != 'radio' && $value_type != 'option') { ?>style="display: none;"<?php } ?>>
            <td><?php echo $entry_action_attribute_value;?></td>
            <td><table class="dynamic-grid" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr id="attribute-options-table">
				  <?php foreach ($languages as $language) { ?>
                    <th><?php echo $language['name']; ?><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></th>
				  <?php
				  }
				  ?>
                    <th>sort</th>

                    <th> <button id="add_new_option_button" title="Add Option" type="button" row_num='in_row'>Add Option</button></th>
                  </tr>
                  	
				  	<?php 
					if(!empty($attribute_option)){
					foreach($attribute_option as $option_id => $attribute_data){
					?>
					<tr class="option-row">

						<?php foreach ($languages as $language) { ?>

								<td><input name="attribute_option[value][<?php echo $attribute_data[$language['language_id']]['option_id'];?>][<?php echo $language['language_id'];?>]" value="<?php echo $attribute_data[$language['language_id']]['option_value'];?>"  type="text"></td>

						<?php } ?>

							

					<td><input  type="text" name="attribute_option[order][<?php echo $option_id;?>]" value="<?php echo $attribute_data[$language['language_id']]['sort_order'];?>"></td>

                    	<td >
                      		<a href="index.php?route=catalog/attribute/delOption&attr_id=<?php echo $attribute_id;?>&option_id=<?php echo $option_id;?>"  title="Delete" type="button" >Delete</a></td>
                  		</tr>
					<?php
					}
					}else{
					?>
					<tr class="option-row">
							
                  		</tr>
					<?php
					}
                    ?>
					
                </tbody>
              </table></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">

$('select[name=value_type]').change(function(){
    var value = $(this).val();
  if(value == 'radio' || value=="option"){
      $("tr[dom=value_dom]").show();
  }else{
     if($("input[name^=attribute_option]").size()){
       alert("请先删除属性在在进行类型修改");
     }else {
       $("tr[dom=value_dom]").hide();
     }
  }
});


$('#add_new_option_button').click(function(){
	var row_num = $(this).attr('row_num');
	var next_row =row_num+1;
	var html = '<tr class="option-row">';
	<?php foreach ($languages as $language) { ?>
	html +=	'<td><input type="text" value="" name="attribute_option[value]['+next_row+'][<?php echo $language['language_id']?>]"></td>';
	<?php } ?>
														
	html +=	'<td><input type="text" name="attribute_option[order]['+next_row+']" value=""></td><td><button class="delete_option_button" title="Delete" type="button">Delete</button></td></tr>';
	
	$('#attribute-options-table').after(html);
	$(this).attr('row_num',next_row);
});

$('.delete_option_button').live('click',function(){
	$(this).parents('.option-row').remove();
});
</script>