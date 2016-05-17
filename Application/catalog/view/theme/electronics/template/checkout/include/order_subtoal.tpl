<div class="alignright checkright">
    <div class="t5">
        <code class="helpicon"></code>
        <div class="help" style="display:none;"><code class="sj"></code><div class="con"><?php echo $text_have_points;?>, <?php echo $config_point_reword;?>points = USD$1</div></div>
        <span class="blue"><input name="checkbox" type="checkbox" id="cop"/><?php echo $text_user_points;?></span>
    </div>
    <?php if($usered_points){ ?>
    <div class="coupon" style="display:block; width:245px;"><input name="points" type="text" value="<?php echo $usered_points;?>" id='use_point'/><a href="javascript:void(0)" class="common-btn-orange" id='apply_point'><?php echo $text_apply;?></a><a href="javascript:void(0)" class="common-btn-orange" id='Cancel_point'><?php echo $text_cancel;?></a></div>
    <?php }else{ ?>
    <div class="coupon" style="display:none"><input name="points" type="text" value="" id='use_point'/><a href="javascript:void(0)" class="common-btn-orange" id='apply_point'><?php echo $text_apply;?></a></div>
    <?php } ?>
	<div class="clear"></div>
    <?php if(isset($total_data) && is_array($total_data) && count($total_data)>0) { ?>
    <?php foreach($total_data as $total){ ?>
    <?php if($total['code']!=='total'){ ?>
    <div class="t6 font14 clearfix"><?php echo $total['title'];?>: <span class="bold"><?php echo $total['text'];?></span></div>
    <?php } ?>
    <?php } ?>
    <?php } ?>


</div>
<div class="clear"></div>
<?php if(isset($total_data) && is_array($total_data) && count($total_data)>0) { ?>
<?php $end =end($total_data);?>
<div class="alignright checkTotal font20"><?php echo $end['title'];?> : <span class="red">
        <span><?php echo $end['text'];?></span>
    </span></div>
<?php } ?>