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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
	  	<div class="heading">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $repair; ?>" class="button"><?php echo $button_repair; ?></a>
		<a href="javascript:void(0)" class="button" id='add_subcat'><?php echo $button_insert; ?></a>
		
		<a onclick="$('#SelectCatForm').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
	  </div>
  <div class="content">
	
	  <div id='anchor-content'>
	  
	  
	  	
			<div class="zTreeDemoBackground left">
			  <ul class="ztree" id="treeDemo" style="-moz-user-select: none;">
			  </ul>
			</div>
	  </div>
	  <form action="index.php?route=catalog/category/delete&token=<?php echo $token ?>" method="post" id='SelectCatForm'>
	  		<input type="hidden" value="" name="click_id" id='click_id_value'>
	  </form>
	  <div class="catlog_edit_info" style="margin-left:30px;">
	  	<iframe src="index.php?route=catalog/category/update&token=<?php echo $token ?>&category_id=" frameborder="0" id='cat_info'></iframe>
	  </div>
    </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
		var setting = {
			check: {
				enable: true,
				chkStyle: "checkbox",
				chkboxType: { "Y": "s", "N": "s" }
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
					beforeClick: zTreeBeforeClick,
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
	
	function zTreeBeforeClick(event, treeId, treeNode){
		var cat_id = treeId.id;
		$('#click_id_value').attr('value',cat_id);
		
		$('#cat_info').attr('src','index.php?route=catalog/category/update&token=<?php echo $token ?>&category_id='+cat_id)
	}
		$(document).ready(function(){
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		});
	
	$('#add_subcat').click(function(){
		var cat_id =$('#click_id_value').attr('value');
		$('#cat_info').attr('src','index.php?route=catalog/category/insert&token=<?php echo $token ?>&p_id='+cat_id)
	})
	
	
	function onCheck(e,treeId,treeNode){
		var treeObj=$.fn.zTree.getZTreeObj("treeDemo"),
		nodes=treeObj.getCheckedNodes(true),
		v="";
		var id_str ='';
		for(var i=0;i<nodes.length;i++){
			v+=nodes[i].name + ",";
			//alert(nodes[i].id); //获取选中节点的值
			id_str += nodes[i].id;
			if(i<nodes.length-1){
			id_str += ',';
			}
		}
		$('#click_id_value').attr('value',id_str);
		
	  }
	</script>
