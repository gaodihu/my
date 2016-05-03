<?php echo $header; ?>
<div class="head-title">
	<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?>
</div>

<div class="mypoints clearfix">

	<?php echo $text_how_to_get_bonus_point_in_myled;?>
	<table class="mypoints-table" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
		<td><?php echo $column_available_points;?></td>
		<td><?php echo $column_used_points;?></td>
		<td><?php echo $column_pending_points;?> </td>
		<td><?php echo $column_total_points;?></td>
	</tr>
	<tr class="blod">
		<td><?php echo $available_points;?></td>
		<td><?php echo $used_points;?></td>
		<td><?php echo $pending_points;?></td>
		<td class="recolor"><?php echo $total_points;?></td>
	</tr>
	</tbody>
	</table>

</div>
<?php echo $footer; ?>



  
