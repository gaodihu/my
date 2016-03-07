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




<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <p style="text-align: center;"><?php echo $text_store; ?></p>
    <?php foreach ($stores as $store) { ?>
    <?php if ($store['store_id'] == $store_id) { ?>
    <a href="<?php echo $store['url']; ?>"><b><?php echo $store['name']; ?></b></a><br />
    <?php } else { ?>
    <a href="<?php echo $store['url']; ?>"><?php echo $store['name']; ?></a><br />
    <?php } ?>
    <?php } ?>
    <br />
  </div>
</div>
