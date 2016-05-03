<?php echo $header; ?>

   <div class="head-title">
		<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?> 
	</div>
<section class="box wrap clearfix">

	<section class="boxRight mypoints clearfix">
		
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>

		<section class="mt_20 paypalstep">
                    <?php if($onestep){ ?>
                    <ul>
                        <?php foreach($onestep as $row) { ?>
                        <li><input type='button' value="<?php echo $text_turn_off; ?>" class="min-btn green-btn lh0 m-b10" dom="turn-off" bid="<?php echo $row['id'];?>"/></li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <ul>
                       <li><input type='button' value="<?php echo $text_turn_on; ?>" class="min-btn orange-bg lh0 m-b10" dom="turn-on"/></li>
                    </ul>
                    <?php } ?>
                    <?php echo $onestep_desc; ?>
                    
                </section>
	</section>	
</section>

 <script>

 $('input[dom=turn-off]').click(function(){
     
     if(confirm('<?php echo $text_turn_confirm_tips; ?>')){
         var bid = $(this).attr('bid');
         $.ajax({
		url: '/index.php?route=account/onestep/cancel',
		type: 'post',
		data: 'id='+bid,
		dataType: 'json',
		success: function(json) {
                    window.location = json.redirect;
		}
	});
     }
     
 });
 $('input[dom=turn-on]').click(function(){
     window.location = '/index.php?route=account/onestep/turnon';
 });
 </script>
<?php echo $footer; ?>