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
		<?php if ($success) { ?>
			<div class="success"><?php echo $success; ?><img src="<?php  echo STATIC_SERVER; ?>css/images/close.png" alt="" class="close" /></div>
		<?php } ?>
		<section class="mt_20">
         	<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id='newsletter_form'>

				<input class="subsc" name="redirect" type="hidden" value="<?php echo $redirect_url;?>"/><input class="subem" name="newsletter_email" type="text" value="<?php echo $newsletter;?>" />
				<input class="subsc" name="sub" type="submit" value="Subscribe"/>
				<input class="subsc" name="unsubscribe" type="button" value="Unsubscribe" id='Unsubscribe'/>
			</form>
          
          
          <p class="msub_gray"><?php echo $text_newsletter_frist;?></p>
      </section>
		

        <?php echo $right_bottom;?>
	</section>	
</section>

 <script>
 var unsubscribe_url ='<?php echo $unsub_action;?>'
 	$('#Unsubscribe').click(function(){
 		$("#newsletter_form").attr('action',unsubscribe_url);
 		$("#newsletter_form").submit();
	})
 </script>
<?php echo $footer; ?>