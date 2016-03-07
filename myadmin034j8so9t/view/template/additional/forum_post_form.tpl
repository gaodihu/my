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
      <h1><img src="view/image/review.png" alt="" />编辑用户上传信息</h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	<?php if($forum_user_post_info['email_send']==0){ ?>
	 <div class="left" style="float:left; width:40%">
		 <table class="form">
			<tr>
				<td> Ga link id:</td>
				<td><?php echo $forum_user_post_info['fourm_ga_id']; ?></td>
			</tr>
			<tr>
				<td> User id:</td>
				<td><?php echo $forum_user_post_info['user_id']; ?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?php echo $user_info['email']; ?></td>
			</tr>
			<tr>
				<td> post link:</td>
				<td><?php echo $forum_user_post_info['forum_link']; ?></td>
			</tr>
			<tr>
				<td> post text:</td>
				<td><?php echo $forum_user_post_info['forum_content']; ?></td>
			</tr>
			
			<tr>
				<td> email send:</td>
				<td><?php echo $forum_user_post_info['email_send']; ?></td>
			</tr>
			
        </table>
	 </div>
	 <div style="float:right; width:40%">
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        	<table class="form">
				<tr><td>Status:</td></tr>
				<tr><td>
				<?php foreach($status as $key=>$item){ ?>
				<input type="radio" name="status" value="<?php echo $key;?>"  <?php if($forum_user_post_info['status']==$key){ ?>checked="checked" <?php } ?> /><?php echo $item;?>
				<?php } ?>
				</td></tr>
				<tr><td>Ga click nums</td></tr>
				<tr><td><input type="text" value="<?php echo $forum_user_post_info['ga_click'];?>" name="ga_click" /></td></tr>
				<tr><td>Ga click nums screenshot</td></tr>
				<?php if($forum_user_post_info['ga_click_screenshot']){ ?>
				<tr><td><img src="/image/<?php echo $forum_user_post_info['ga_click_screenshot'];?> " /></td></tr>
				<?php }else{ ?>
				<tr><td><input type="file"  name="ga_click_screenshot" /></td></tr>
				<?php } ?>
				
				<tr><td>Make money</td></tr>
				<tr><td><input type="text" value="<?php echo $forum_user_post_info['forum_money'];?>" name="money" /></td></tr>
				<tr><td>points</td></tr>
				<tr><td><input type="text" value="<?php echo $forum_user_post_info['forum_get_points'];?>" name="points" /></td></tr>
				<tr><td><input type="submit" value="Submit"/></td></tr>
			</table>
      </form>
	 </div>
     <div class="clear"></div>
	 <?php }else{ ?>
		<table class="form">
			<tr>
				<td> Language Code:</td>
				<td><?php echo $forum_user_post_info['language_code']; ?></td>
			</tr>
			<tr>
				<td> User Name:</td>
				<td><?php echo $forum_user_post_info['user_name']; ?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?php echo $forum_user_post_info['email']; ?></td>
			</tr>
			<tr>
				<td> Product Name:</td>
				<td><?php echo $forum_user_post_info['product_name']; ?></td>
			</tr>
			<tr>
				<td> Product Color:</td>
				<td><?php echo $forum_user_post_info['product_color']; ?></td>
			</tr>
			<tr>
				<td> Product Image:</td>
				<td><img src='<?php echo $pro_img; ?>' /></td>
			</tr>
			<tr>
				<td> Price:</td>
				<td><?php echo $forum_user_post_info['price']; ?></td>
			</tr>
			<tr>
				<td> Currency Code:</td>
				<td><?php echo $forum_user_post_info['currency_code']; ?></td>
			</tr>
			<tr>
				<td> Url Link:</td>
				<td><?php echo $forum_user_post_info['url_link']; ?></td>
			</tr>
			<tr>
				<td> Shipment:</td>
				<td><?php echo $forum_user_post_info['shipment']; ?></td>
			</tr>
			<tr>
				<td> Product Description:</td>
				<td><?php echo $forum_user_post_info['product_description']; ?></td>
			</tr>
			<tr>
				<td> Comment:</td>
				<td><?php echo $forum_user_post_info['comment']; ?></td>
			</tr>
			<tr>
				<td> Status:</td>
				<td><?php echo $forum_user_post_info['status']; ?></td>
			</tr>
     		<tr>
				<td> Replay Content:</td>
				<td><?php echo $forum_user_post_info['replay_content']; ?></td>
			</tr>
        </table>
	 <?php }?>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>