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
      <h1><img src="view/image/review.png" alt="" />新品回复</h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button">Cancel</a></div>
    </div>
    <div class="content">
	<?php if($other_product_info['email_send']==0){ ?>
	 <div class="left" style="float:left; width:40%">
		 <table class="form">
			<tr>
				<td> Language Code:</td>
				<td><?php echo $other_product_info['language_code']; ?></td>
			</tr>
			<tr>
				<td> User Name:</td>
				<td><?php echo $other_product_info['user_name']; ?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?php echo $other_product_info['email']; ?></td>
			</tr>
			<tr>
				<td> Product Name:</td>
				<td><?php echo $other_product_info['product_name']; ?></td>
			</tr>
			<tr>
				<td> Product Color:</td>
				<td><?php echo $other_product_info['product_color']; ?></td>
			</tr>
			<tr>
				<td> Product Image:</td>
				<td><img src='<?php echo $pro_img; ?>' /></td>
			</tr>
			<tr>
				<td> Price:</td>
				<td><?php echo $other_product_info['price']; ?></td>
			</tr>
			<tr>
				<td> Currency Code:</td>
				<td><?php echo $other_product_info['currency_code']; ?></td>
			</tr>
			<tr>
				<td> Url Link:</td>
				<td><?php echo $other_product_info['url_link']; ?></td>
			</tr>
			<tr>
				<td> Shipment:</td>
				<td><?php echo $other_product_info['shipment']; ?></td>
			</tr>
			<tr>
				<td> Product Description:</td>
				<td><?php echo $other_product_info['product_description']; ?></td>
			</tr>
			<tr>
				<td> Comment:</td>
				<td><?php echo $other_product_info['comment']; ?></td>
			</tr>
			<tr>
				<td> Status:</td>
				<td><?php echo $other_product_info['status']; ?></td>
			</tr>
     		<tr>
				<td> Replay Content:</td>
				<td><?php echo $other_product_info['replay_content']; ?></td>
			</tr>
        </table>
	 </div>
	 <div style="float:right; width:40%">
	 	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        	<table class="form">
				<tr><td>Status:</td></tr>
				<tr><td>
				<?php if($other_product_info['status']==1){ ?>
				<input type="radio" name="status" value="1"  checked="checked"/>Enable 
				<input type="radio" name="status" value="0" />Disable 
				<?php }else{ ?>
				<input type="radio" name="status" value="1"  />Enable 
				<input type="radio" name="status" value="0" checked="checked"/>Disable 
				<?php } ?>
				</td></tr>
				<tr><td>Repaly content:</td></tr>
				<tr><td><textarea name="reply_content" cols="70" rows="10"></textarea></td></tr>
				<tr><td><input type="submit" value="Submit"/></td></tr>
			</table>
      </form>
	 </div>
     <div class="clear"></div>
	 <?php }else{ ?>
		<table class="form">
			<tr>
				<td> Language Code:</td>
				<td><?php echo $other_product_info['language_code']; ?></td>
			</tr>
			<tr>
				<td> User Name:</td>
				<td><?php echo $other_product_info['user_name']; ?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?php echo $other_product_info['email']; ?></td>
			</tr>
			<tr>
				<td> Product Name:</td>
				<td><?php echo $other_product_info['product_name']; ?></td>
			</tr>
			<tr>
				<td> Product Color:</td>
				<td><?php echo $other_product_info['product_color']; ?></td>
			</tr>
			<tr>
				<td> Product Image:</td>
				<td><img src='<?php echo $pro_img; ?>' /></td>
			</tr>
			<tr>
				<td> Price:</td>
				<td><?php echo $other_product_info['price']; ?></td>
			</tr>
			<tr>
				<td> Currency Code:</td>
				<td><?php echo $other_product_info['currency_code']; ?></td>
			</tr>
			<tr>
				<td> Url Link:</td>
				<td><?php echo $other_product_info['url_link']; ?></td>
			</tr>
			<tr>
				<td> Shipment:</td>
				<td><?php echo $other_product_info['shipment']; ?></td>
			</tr>
			<tr>
				<td> Product Description:</td>
				<td><?php echo $other_product_info['product_description']; ?></td>
			</tr>
			<tr>
				<td> Comment:</td>
				<td><?php echo $other_product_info['comment']; ?></td>
			</tr>
			<tr>
				<td> Status:</td>
				<td><?php echo $other_product_info['status']; ?></td>
			</tr>
     		<tr>
				<td> Replay Content:</td>
				<td><?php echo $other_product_info['replay_content']; ?></td>
			</tr>
        </table>
	 <?php }?>
    </div>
	
  </div>
</div> 
<?php echo $footer; ?>