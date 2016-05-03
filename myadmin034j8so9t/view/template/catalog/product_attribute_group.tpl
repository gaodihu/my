<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      
    </div>
    <div class="content">
	<form action="<?php echo $insert ;?>" method="post">
	<table>
		<tr ><td ><?php echo $entry_select_attr_group;?></td></tr>
		<tr >
			<td>
			<?php foreach($attribute_groups as $attr_group){?>
				<input  type="checkbox" name="pro_attr_group[]"  value="<?php echo $attr_group['attribute_group_id']?> "/><?php echo $attr_group['attribute_group_code']?>
			<?php } ?>
			</td>
		</tr>
		<tr>
			<td>
			<input type="submit" value="submit" />
			</td>
		</tr>
	</table>
	</form>

    </div>
  </div>
</div>
<?php echo $footer; ?>