<?php echo $header; ?>
<link type="text/css" href="view/javascript/zTree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet">
<script src="view/javascript/zTree/js/jquery.ztree.core-3.5.js" type="text/javascript"></script>
<script src="view/javascript/zTree/js/jquery.ztree.excheck-3.5.js" type="text/javascript"></script>
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
            <td><span class="required">*</span> <?php echo $entry_group; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="filter_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_group_description[$language['language_id']]) ? $filter_group_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
        <table id="filter" class="list">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $entry_name ?></td>
              <td class="left"><span class="required">*</span> 选择分类</td>
              <td class="left"><span class="required">*</span> 选择属性</td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $filter_row = 0; ?>
          <?php foreach ($filters as $filter) { ?>
          <tbody id="filter-row<?php echo $filter_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="filter[<?php echo $filter_row; ?>][filter_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter['filter_description'][$language['language_id']]) ? $filter['filter_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_filter[$filter_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_filter[$filter_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
              <td class="right">
			  	<div class="zTreeDemoBackground"><ul class="ztree" id="treeDemo<?php echo $filter_row;?>" style="-moz-user-select: none;"></ul>
				<?php foreach($filter['filter_category'] as $filter_category){
				?>
				<input type='hidden' value="<?php echo $filter_category['category_id'];?>" name='filter[<?php echo $filter_row;?>][filter_category][]'>
				<?php
				}
				?>
				</div>
			  </td>
              <td class="right">
			  		<?php if($all_attribute){
			  				foreach($all_attribute as $attr){
								if($filter['filter_attribute']&& in_array($attr['attribute_id'],$filter['filter_attribute'])){
					?>
					<input type="checkbox"  value="<?php echo $attr['attribute_id']?>" name="filter[<?php echo $filter_row; ?>][filter_attr][]" checked="checked"><?php echo $attr['name']?>
					<?php	
								}
								else{
					?>
					<input type="checkbox"  value="<?php echo $attr['attribute_id']?>" name="filter[<?php echo $filter_row; ?>][filter_attr][]"><?php echo $attr['name']?>
					<?php			
								}
							}
						}
					?>
			  </td>
              <td class="right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $filter_row++; ?>
          <?php } ?>
          <tfoot>
            
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addFilter();" class="button"><?php echo $button_add_filter; ?></a>
			  	<input type="hidden" name="" value="" id='filter_row'/>
			  </td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var filter_row = <?php echo $filter_row; ?>;

function addFilter() {
	html  = '<tbody id="filter-row' + filter_row + '">';
	html += '  <tr>';	
    html += '    <td class="left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '    <input type="text" name="filter[' + filter_row + '][filter_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="right"><div class="zTreeDemoBackground"><ul class="ztree" id="treeDemo'+filter_row+'" style="-moz-user-select: none;"></ul></div></td>';
	html += '    <td class="left">';
	  <?php if($all_attribute){
			  	foreach($all_attribute as $attr){
			  ?>
	html +=	'<input type="checkbox"  value="<?php echo $attr['attribute_id']?>" name="filter[' + filter_row + '][filter_attr][]"><?php echo $attr['name']?>';
			  <?php
				}
			  }
			  ?>
	html +=	'</td>';			
	html += '    <td class="right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" size="1" /></td>';
	html += '     <td class="left"><a onclick="$(\'#filter-row' + filter_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';	
    html += '</tbody>';
	
	$('#filter tfoot').before(html);
	var ID= 'treeDemo'+filter_row;
	$('#filter_row').val(filter_row);
	$.fn.zTree.init($("#"+ID), setting, zNodes);
	
	filter_row++;
}
//--></script>

<script type="text/javascript">
		var setting = {
			check: {
				enable: true,
				chkStyle: "checkbox",
				chkboxType: { "Y": "", "N": "" }
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
					onCheck:onCheck
			}
		}; 
	var zNodes;
	var catTree =<?php echo $catTree;?>;
	var znodeArr =new Array();
	for(var i=0;i<catTree.length;i++){
		znodeArr.push(eval("("+catTree[i]+")"));
	}
	zNodes = znodeArr;
	
		$(document).ready(function(){
			
			//设置默认选中的树
			<?php 
			$filter_row=0;
			
			foreach($filters as $filter_to){
			?>
			$.fn.zTree.init($("#treeDemo<?php echo $filter_row;?>"), setting, zNodes);
			
			<?php 
			
				if($filter_to['filter_category']){
				
					foreach($filter_to['filter_category'] as $filter_cat){
			?>
				var treeObj=$.fn.zTree.getZTreeObj("treeDemo<?php echo $filter_row;?>");
				treeObj.selectNode(treeObj.getNodeByParam("id",<?php echo $filter_cat['category_id'];?> , null),true,true);
				treeObj.checkNode(treeObj.getNodeByParam("id", <?php echo $filter_cat['category_id'];?>, null), true, true);
			<?php	
					
					}
				
				}
			$filter_row++;
			} 
			
			?>
			filter_row++;
		});

	function onCheck(e,treeId,treeNode){
		var filter_row =treeId.substring (treeId.length-1);
		if(treeNode.checked==true){
			var content ="<input type='hidden' value='"+treeNode.id+"' name='filter[" + filter_row + "][filter_category][]'>";
			$('#'+treeId).after(content);
		}
		else if(treeNode.checked==false){
			var obj =$("input[name='filter[" + filter_row + "][filter_category][]'][value='"+treeNode.id+"']");
		    obj.remove();
		}
		
	  }
	</script>
<?php echo $footer; ?>