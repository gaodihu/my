<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/review.png" alt="" />编辑抽奖活动</h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	<?php if($prize_name_info){ ?>
	 <div>
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        	<table class="form">
				<tr><td>Name</td><td><input type='text' name='name' value="<?php echo $prize_name_info['name'];?>" ></td></tr>
				<tr><td>Start Time</td><td><input type='text' name='start_time' value="<?php echo $prize_name_info['start_time'];?>" ></td></tr>
				<tr><td>End Time</td><td><input type='text' name='end_time' value="<?php echo $prize_name_info['end_time'];?>" ></td></tr>


				<tr><td>类型</td><td><select name="type">
							<?php foreach($type_arr as $key => $item) { ?>
							<option value="<?php echo $key; ?>" <?php if($prize_name_info['type'] == $key) {echo "selected";}?> ><?php echo $item; ?></option>

							<?php } ?>

						</select></td></tr>

				<tr><td><input type="submit" value="Submit"/></td><td></td></tr>
			</table>
      </form>
	 </div>
	 <?php }else{ ?>
		<div>
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        	<table class="form">
				<tr><td>Name</td><td><input type='text' value='' name='name'></td></tr>
				<tr><td>Start Time</td><td><input type='text' name='start_time' value="" ></td></tr>
				<tr><td>End Time</td><td><input type='text' name='end_time' value="" ></td></tr>
				<tr><td>类型</td><td><select name="type">
							<?php foreach($type_arr as $key => $item) { ?>
							<option value="<?php echo $key; ?>"  ><?php echo $item; ?></option>

							<?php } ?>

						</select></td></tr>
			
				<tr><td><input type="submit" value="Submit"/></td><td></td></td></tr>
			</table>
      </form>
	 </div>
	 <?php }?>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>