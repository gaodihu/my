<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/review.png" alt="" />设置活动规则</h1>
      <div class="buttons">
	  <a onclick="$('#prize_set_form').submit()" class="button">Save</a><a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	 <div>
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id='prize_set_form'>
        	<table class="form">
				<tr><td>活动奖项</td><td>中奖概率(分子数值，分母是所有概率之和)</td><td>中奖人数</td><td>奖品名称</td><td><input type="button" name='add' value='Add' onclick="add_clo()"/></td></tr>
				<?php if($prize_set_info){ ?>
				<?php foreach($prize_set_info as $prize_set){ ?>
				<tr>
					<td><input type="text" name="prize_id[]" value="<?php echo $prize_set['prize_id'];?>"/></td>
					<td><input type="text" name="prize_chance[]" value="<?php echo $prize_set['prize_chance'];?>"/></td>
					<td><input type="text" name="prize_num[]" value="<?php echo $prize_set['prize_num'];?>"/></td>
					<td><input type="text" name="prize_name[]" value="<?php echo $prize_set['prize_name'];?>"/></td>
					<td><input type="button" name="delete" value="Delete" onclick="del_clo()"/></td>
				</tr>
				<?php } ?>
				<?php }else{ ?>
				<tr>
					<td><input type="text" name='prize_id[]'/></td>
					<td><input type="text" name='prize_chance[]'/></td>
					<td><input type="text" name='prize_num[]'/></td>
					<td><input type="text" name='prize_name[]'/></td>
					<td><input type="button" name="delete" value="Delete" onclick="del_clo()"/></td>
				</tr>
				<?php } ?>
			</table>
      </form>
	 </div>
    </div>
	
  </div>
</div> 
<script>
function add_clo(){
	var html='<tr>'+
					'<td><input type="text" name="prize_id[]"/></td>'+
					'<td><input type="text" name="prize_chance[]"/></td>'+
					'<td><input type="text" name="prize_num[]"/></td>'+
					'<td><input type="text" name="prize_name[]"/></td>'+
					'<td><input type="button" name="delete" value="Delete" onclick="del_clo()"/></td>'+
				'</tr>';
	$('#prize_set_form>table tr:last').after(html);
	
}

function del_clo(){
	$('#prize_set_form>table tr:last').remove();
}
</script>
<?php echo $footer; ?>