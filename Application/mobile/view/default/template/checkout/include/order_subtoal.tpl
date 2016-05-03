<ul class="tab-chagne change-list">
	<li class="t-title"><?php echo $text_order_summary;?></li>

		<?php foreach($total_data as $total){ ?>
        <?php if($total['title']){ ?>
		<li class="clearfix"><span class="price"><?php echo $currency_code;?><?php echo $total['text'];?></span><?php echo $total['title'];?>: </li>
		<?php } ?>
		<?php } ?>



        <!--
	<?php if(isset($total_data) && is_array($total_data) && count($total_data)>0) { ?>
	<?php $end =end($total_data);?>
	<li >
		<?php echo $end['title'];?>: <span class="price" style="font-size: 1.5em;"><?php echo $currency_code;?><?php echo $end['text'];?></span>
	</li>
	<?php } ?>
        -->
</ul>