<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php	
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>

<section class="box wrap clearfix">
	<?php echo $menu;?>
	<section class="boxRight">
		<?php echo $right_top;?>
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>

		<section class="mt_20 paypalstep">
                    <?php if($onestep){ ?>
                    <ul>
                        <?php foreach($onestep as $row) { ?>
                        <li><input type='button' value="<?php echo $text_turn_off; ?>" class="btn-primary greenbg" dom="turn-off" bid="<?php echo $row['id'];?>"/></li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <ul>
                       <li><input type='button' value="<?php echo $text_turn_on; ?>" class="btn-primary orangebg" dom="turn-on"/></li>
                    </ul>
                    <?php } ?>
                    <?php echo $onestep_desc; ?>
                    
                </section>
		
       
        <?php echo $right_bottom;?>
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