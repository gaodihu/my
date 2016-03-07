<form enctype="multipart/form-data" method="post" action="<?php echo $action;?>" id='currency_change_form'>
<input type="hidden" value="" name="currency_code" id='currency_code'>
<input type="hidden" value="<?php echo $redirect; ?>" name="redirect">
</form>
<div  id="currency" class="order-page" style="display: none">
		<h4><i class="icon-remove"></i><?php echo $text_currency;?></h4>
	<ul class="secondary-list"  >
	<?php foreach ($currencies as $currency) {
		if ($currency['code'] == $currency_code){ ?>
		<li value="<?php echo $currency['code'];?>" class="green-color"><?php echo $currency['code'];?></li>
		<?php } else{ ?>
		<li value="<?php echo $currency['code'];?>"><?php echo $currency['code'];?></li>
		<?php  } ?>
    <?php  } ?>
	</ul>
	</div>

 <script>
 $('#currency>.secondary-list>li').click(function(){
	 var code =$(this).attr('value');
	$('#currency_code').attr('value',code);
	$('#currency_change_form').submit();
 })
</script>

			 
