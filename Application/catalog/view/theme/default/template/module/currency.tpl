<ul class="currency">
	<form enctype="multipart/form-data" method="post" action="/index.php?route=module/currency" id='currency_change_form'>
                <li><a href="javascript:void(0);"><strong><?php echo $currency_code;?></strong><span class="<?php echo strtolower($currency_code);?>"></span><i></i></a>
				  <div class="money" style="display: none;">
					<dl>
					  <?php foreach ($currencies as $currency) {
							if ($currency['code'] == $currency_code){
					?>
								<dd><a  onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code'];?>'); $('#currency_change_form').submit();" class="select"><span class="<?php echo strtolower($currency['code']);?>"></span><?php echo $currency['code'];?></a></dd>
					 <?php
							}
							else{
					?>
								<dd><a onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code'];?>'); $('#currency_change_form').submit();"><span class="<?php echo strtolower($currency['code']);?>"></span><?php echo $currency['code'];?></a></dd>
					<?php
							}
					  }
					  ?>
					  <input type="hidden" value="" name="currency_code">
					<input type="hidden" value="<?php echo $redirect; ?>" name="redirect">
					</dl>
				  </div> 
				</li>
	</form>
</ul>



			 
