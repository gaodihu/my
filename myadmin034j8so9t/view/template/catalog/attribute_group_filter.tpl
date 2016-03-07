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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
	
          <tr>
            <td>Attribute Group</td>
            <td><?php echo $attributte_group['attribute_group_code']; ?></td>
          </tr>

            <tr>
                <td>Attribute Code</td>
                <td><?php echo $attributte['attribute_code']; ?></td>
            </tr>

          <tr>
            <td>Attribute type</td>

            <td>

                   <?php if($attributte['value_type'] == 'numerical') { echo '数值';} ?>
                   <?php if($attributte['value_type'] == 'text') { echo '文本';} ?>
                   <?php if($attributte['value_type'] == 'radio') { echo '单选';} ?>
                   <?php if($attributte['value_type'] == 'option') { echo '多选';} ?>

          </tr>

            <tr>
                <td>属性名称</td>
                <td>

                    <table>
                        <tr>
                            <?php
						 foreach ($languages as $language) { ?>
                            <td width="130"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></td>
                            <?php } ?>
                        </tr>
                        <tr >

                            <?php foreach ($languages as $language) { ?>
                            <td width="130">  <span><?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>
                            </span>
                            </td>
                            <?php } ?>
                        </tr>
                    </table>

                </td>


            </tr>
          


	<tr>
            <td>status in catalog narrow</td>
            <td>
			<?php if($group_attribute['status']){ ?>
			<input  type="radio" name="status"  value="1"  checked="checked"/>YES
			<input  type="radio" name="status"  value="0" />NO
			<?php } else{ ?>
			<input  type="radio" name="status"  value="1"  />YES
			<input  type="radio" name="status"  value="0"  checked="checked"/>NO
			<?php } ?>
			
			</td>
          </tr>
          
         <tr>
            <td>过滤条件:</td>
            <td>
                <select name="filter_type" id="filter_type" >



                    <?php if($attributte['value_type'] == 'radio' || $attributte['value_type'] == 'option') { ?>
                    <option value="1" <?php if($group_attribute['filter_type'] == 1){ echo "selected=selected";} ?>>直接使用属性值</option>
                    <?php } ?>

                    <?php if( $attributte['value_type'] == 'numerical') { ?>
                    <option value="2" <?php if($group_attribute['filter_type'] == 2){ echo "selected=selected";} ?>>自定（包含定义区间）</option>
                    <?php } ?>
                </select>	
	    </td>
          </tr>
          
          
          <tr id="filter_type_1"  style="display:none">
              <td>直接使用属性值:</td>
            <td>
              <table class="dynamic-grid" cellspacing="0" cellpadding="0">
                <tbody>
                  <tr id="attribute-options-table">
				  <?php foreach ($languages as $language) { ?>
                    <th><?php echo $language['name']; ?><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></th>
				  <?php
				  }
				  ?>

                  </tr>
                  	
				  	<?php 
					if(!empty($attribute_option)){
					foreach($attribute_option as $option_id => $attribute_data){
					?>
					<tr class="option-row">

                        <?php foreach ($languages as $language) { ?>

                        <td><input name="attribute_option[value][<?php echo $attribute_data[$language['language_id']]['option_id'];?>][<?php echo $language['language_id'];?>]" value="<?php echo $attribute_data[$language['language_id']]['option_value'];?>"  type="text"></td>

                        <?php } ?>



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
        
                  
          <tr id="filter_type_2" style="display:none">
              <td>自定（包含定义区间）:</td>
            <td>

                <?php if($attributte['value_type'] == 'numerical'){ ?>


                <div>数字区间（前开后闭[)） <a href="javascript:void(0)" dom="add_range">添加区间</a></div>
                <table  id="numerical_range" class="form" style="width:500px;">
                    <tr>
                        <td >区间</td>
                        <td >开始</td>
                        <td >结束</td>
                        <td >操作</td>
                    </tr>

                    <?php if(count($range_numerical_list) <=0){ ?>
                    <tr dom="range">
                        <td><input type="text" name="numerical_range_sort[]" value="" size="5"></td>
                        <td><input type="text" name="numerical_range_start[]" value="" size="5"></td>
                        <td><input type="text" name="numerical_range_end[]" value="" size="5"></td>
                        <td><a href="javascript:void(0)" dom="del_range">删除</a></td>
                    </tr>
                    <?php } else { ?>
                    <?php foreach($range_numerical_list as $item){ ?>
                    <tr dom="range">
                        <td><input type="text" name="numerical_range_sort[]" value="<?php echo $item['sort_order']; ?>" size="5"></td>
                        <td><input type="text" name="numerical_range_start[]" value="<?php echo $item['start']; ?>" size="5"></td>
                        <td><input type="text" name="numerical_range_end[]" value="<?php echo $item['end']; ?>" size="5"></td>
                        <td><a href="javascript:void(0)" dom="del_range">删除</a></td>
                    </tr>
                    <?php } ?>

                    <?php } ?>
                </table>



      </form>
    </div>



                <?php } ?>

                <?php if($attributte['value_type'] == 'option' || $attributte['value_type'] == 'radio'){ ?>

              <table class="dynamic-grid" cellspacing="0" cellpadding="0">
                <tbody>



                  <tr id="attribute-options-table">
				  <?php foreach ($languages as $language) { ?>
                    <th><?php echo $language['name']; ?><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></th>
				  <?php
				  }
				  ?>
                    <th>Position</th>
					<th>is show front</th>
                    <th> <button id="add_new_option_button" title="Add Option" type="button" row_num='in_row'>Add Option</button></th>
                  </tr>
                  	
				  	<?php 
					if(!empty($attribute_option)){
					foreach($attribute_option as $option_id =>  $attribute_data){
					?>
					<tr class="option-row">
                        <?php foreach ($languages as $language) { ?>

                        <td><input name="attribute_option[value][<?php echo $attribute_data[$language['language_id']]['option_id'];?>][<?php echo $language['language_id'];?>]" value="<?php echo $attribute_data[$language['language_id']]['option_value'];?>"  type="text"></td>

                        <?php } ?>
					<td><input  type="text" name="attribute_option[order][<?php echo $attribute_data[0]['option_id'];?>]" value="<?php echo $attribute_data[0]['sort_order'];?>"></td>
					<td><input  type="text" name="attribute_option[show][<?php echo $attribute_data[0]['option_id'];?>]" value="<?php echo $attribute_data[0]['is_show_front'];?>"></td>
                    	<td >
                      		<a href="index.php?route=catalog/attribute/delOption&attr_id=<?php echo $attribute_id;?>&option_id=<?php echo $attribute_data[0]['option_id'];?>"  title="Delete" type="button" >Delete</a></td>
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
              </table>

            <?php } ?>

            </td>
          </tr>
        
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
function change_filter_type(){
    var selected = $('#filter_type').val();
    if(selected == 1){
        $('#filter_type_1').show();
        $('#filter_type_2').hide();
    }else if(selected == 2){
        $('#filter_type_1').hide();
        $('#filter_type_2').show();
    }else{
        $('#filter_type_1').hide();
        $('#filter_type_2').hide();  
    }
}
$('#filter_type').change(change_filter_type);
change_filter_type();

    
$('#add_new_option_button').click(function(){
	var row_num = $(this).attr('row_num');
	var next_row =row_num+1;
	var html = '<tr class="option-row">';
	<?php foreach ($languages as $language) { ?>
	html +=	'<td><input type="text" value="" name="attribute_option[value]['+next_row+'][<?php echo $language['language_id']?>]"></td>';
	<?php } ?>
														
	html +=	'<td><input type="text" name="attribute_option[order]['+next_row+']" value=""></td><td><input type="text" name="attribute_option[show]['+next_row+']" value=""></td><td><button class="delete_option_button" title="Delete" type="button">Delete</button></td></tr>';
	
	$('#attribute-options-table').after(html);
	$(this).attr('row_num',next_row);
});

$('.delete_option_button').live('click',function(){
	$(this).parents('.option-row').remove();
});


function add_numerical_range(){
    var range = jQuery('tr[dom=range]').eq(0).clone();
    jQuery('#numerical_range').append(
            range
    );
    jQuery('a[dom=del_range]').click(function(){
        $(this).parent().parent().remove();
    });
    return false;
}
jQuery('a[dom=add_range]').click(add_numerical_range);
jQuery('a[dom=del_range]').click(function(){
    $(this).parent().parent().remove();
});

</script>