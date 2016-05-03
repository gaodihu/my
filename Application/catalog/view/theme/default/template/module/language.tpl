<?php 
$current_lang_code = $_SESSION['language'];
foreach ($languages as $language) {
	if($current_lang_code==$language['code']){
?>
		<span class="bold"><a href="<?php echo $language['base_url'];?>" title="<?php echo $language['name']; ?>"><?php echo $language['name']; ?></a></span>
<?php
	}
	else{
?>		
		<a href="<?php echo $language['base_url'];?>" title="<?php echo $language['name']; ?>"><?php echo $language['name']; ?></a>
<?php
	}
 } ?>
