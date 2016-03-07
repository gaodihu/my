<form enctype="multipart/form-data" method="post" action="<?php echo $action;?>" id='language_change_form' style='display:none;'>
	<input type='hidden' value='' name='language_code' id='lang_code'>
<input type="hidden" value="<?php echo $redirect; ?>" name="redirect">
  </form>
<div  id="language" class="order-page" style="display: none">
	<h4><i class="icon-remove"></i><?php echo $text_language;?></h4>
	<ul class="secondary-list a_block">
<?php 
foreach ($languages as $language) {
	if($language_code==$language['code']){ ?>
		<li class="green-color" code ="<?php echo $language['code'];?>"><?php echo $language['name']; ?></li>
	<?php }else{ ?>
	<li code ="<?php echo $language['code'];?>"><a href="<?php echo $language['base_url'];?>" title="<?php echo $language['name']; ?>"><?php echo $language['name']; ?></a></li>
	<?php } ?>
<?php } ?>
	</ul>
 </div>