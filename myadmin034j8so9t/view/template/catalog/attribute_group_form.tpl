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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span>attribute group code:</td>
            <td><input type="text" name="attribute_group_code" value="<?php echo isset($attribute_group_code) ? $attribute_group_code : ''; ?>" /></td>
          </tr>

          
        </table>
        <div>属性列表：<a id="add_new_attribute_button" class="button" row_num="row_num1">添加属性</a></div>

        <table class="form" id="group_attribute_list">
            
          <tr>
            <td>属性code</td>
              <td>filter_type</td>
            <td>排序</td>
              <td>状态</td>
            <td>操作</td>
          </tr>
            <?php if(count($attribute_list)) { ?>
            <?php foreach($attribute_list as $item) { ?>
          <tr>
            <td><?php echo $item['attribute_code']; ?></td>

              <td><?php if($item['filter_type'] == 1)
             { echo "直接使用属性值"; }
             else if($item['filter_type'] == 2)
             { echo "自定（包含定义区间）";} ?></td>


              <td><input type="text" name="attribute_sort[<?php echo  $item['attribute_id'];?>]" value="<?php echo $item['sort_order']; ?>" /></td>



              <td><select name="attribute_status[<?php echo  $item['attribute_id'];?>]"><option value="1" <?php if($item['status']==1){ echo "selected=selected";} ?>>开启</option><option value="0" <?php if($item['status']!=1){ echo "selected=selected";} ?>>关闭</option></select></td>


            <td><a href="<?php echo $this->url->link('catalog/attribute_group/filter', 'token=' . $this->session->data['token'] . '&attribute_group_id='.$this->request->get['attribute_group_id'].'&attribute_id='. $item['attribute_id'], 'SSL');?>">编辑</a> | <a href="<?php echo $this->url->link('catalog/attribute_group/deleteAttrGroupAttribute', 'token=' . $this->session->data['token'] . '&attribute_group_id='.$this->request->get['attribute_group_id'].'&attribute_id='. $item['attribute_id'], 'SSL');?>">删除</a></td>
          </tr>
          <?php } ?>
            <?php } ?>
          
          
        </table>

        
        
        <div>价格区间（前开后闭[)） <a href="javascript:void(0)" dom="add_range">添加区间</a></div>
        <table  id="price_range" class="form" style="width:500px;">
           <tr>
            <td >区间</td>
            <td >开始</td>
            <td >结束</td>
            <td >操作</td>
          </tr>
           
          <?php if(count($range_price_list) <=0){ ?>
          <tr dom="range">
            <td><input type="text" name="price_range_sort[]" value="" size="5"></td>
            <td><input type="text" name="price_range_start[]" value="" size="5"></td>
            <td><input type="text" name="price_range_end[]" value="" size="5"></td>
            <td><a href="javascript:void(0)" dom="del_range">删除</a></td>
          </tr>
          <?php } else { ?>
          <?php foreach($range_price_list as $item){ ?>
           <tr dom="range">
               <td><input type="text" name="price_range_sort[]" value="<?php echo $item['sort_order']; ?>" size="5"></td>
            <td><input type="text" name="price_range_start[]" value="<?php echo $item['start']; ?>" size="5"></td>
            <td><input type="text" name="price_range_end[]" value="<?php echo $item['end']; ?>" size="5"></td>
            <td><a href="javascript:void(0)" dom="del_range">删除</a></td>
          </tr>
          <?php } ?>
          
          <?php } ?>
        </table>
        
        
        
      </form>
    </div>
  </div>
</div>
<script>
    function add_price_range(){
        var range = jQuery('tr[dom=range]').eq(0).clone(); 
        jQuery('#price_range').append(
                    range
                );
        jQuery('a[dom=del_range]').click(function(){
            $(this).parent().parent().remove();
        });
       return false;
    }
    jQuery('a[dom=add_range]').click(add_price_range);
    jQuery('a[dom=del_range]').click(function(){
            $(this).parent().parent().remove();
    });

    var all_attribute_option = "<?php echo $all_attribute_option; ?>";


    $('#add_new_attribute_button').click(function(){
        var row_num = $(this).attr('row_num');
        var next_row =row_num+1;

        var html = '<tr class="option-row">';

        html +=	'<td><select name="group_attribure[' + row_num + '][attribute_id]">' + all_attribute_option + '</select></td>';


        html +=	'<td><select name="group_attribure[' + row_num + '][filter_type]" dom="group_attribure-' + row_num + '-filter_type"></select></td>';


        html +=	'<td><input type="text" name="group_attribure[' + row_num + '][sort]" value="16"></td>';

        html +=	'<td><select name="group_attribure[' + row_num + '][status]"><option value="1"  selected="selected">开启</option><option value="0">关闭</option></select></td>';

        html +=	'<td><button class="delete_option_button" title="Delete" type="button">Delete</button></td></tr>';

        $('#group_attribute_list tbody').append(html);
        $(this).attr('row_num',next_row);

        $('select[name^=group_attribure]').change(function() {
            var v =  $(this).val();
            var value_type = $(this).find("option:selected").attr('value_type');
            $('select[dom=group_attribure-'+row_num+'-filter_type] option').remove();
            if(value_type == 'numerical'){
                $('select[dom=group_attribure-'+row_num+'-filter_type]').append('<option value="2">自定（包含定义区间）</option>');
            }else{
                $('select[dom=group_attribure-'+row_num+'-filter_type]').append('<option value="1" selected="selected">直接使用属性值</option>');
            }



        })
    });

    $('.delete_option_button').live('click',function(){
        $(this).parents('.option-row').remove();
    });

    $('select[name^=group_attribure]').change(function() {
        var v =  $(this).val();
        console.log($(this).find("option:selected").attr('value_type'));

    })




</script>

<?php echo $footer; ?>