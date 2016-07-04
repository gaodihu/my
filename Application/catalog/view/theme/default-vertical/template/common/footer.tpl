<footer class="footer">
	<div class="wrap">
		<section class="footer_on clearfix">
			<div class="on_left left">
				<div class="bold l"><a href="<?php echo $tag_href;?>">Product Tags</a>:</div><div class="link">
				<?php foreach($product_tags as $value){ ?>
				<a href="<?php echo $value['href'];?>"><?php echo $value['text'];?></a>|
				<?php } ?>
				</div>
			</div>
		</section>
		<section class="footer_under clearfix">
			<div class="under_left">
				<?php foreach($informations as $key=>$values){ ?>
				<ul>
					<li><p class="bold black"><?php echo $values['name'];?></p></li>
					<?php foreach($values['information'] as $value){ ?>
					<li><a href="<?php echo $value['href'];?>"><?php echo $value['title'];?></a></li>
					<?php } ?>


				</ul>
				<?php } ?>
			</div>
			<div class="under_right left ">
                <div class="green_bg clearfix">
                    <div class="font15 yellow bold"><?php echo $text_subscribe_to;?></div>
                    <p><?php echo $text_get_updates;?></p>
                    <div class="emailadd clearfix m_b10">
                    <form action="index.php?route=newsletter/newsletter" method="post" enctype="multipart/form-data" onsubmit="return checkNewletter('newsletter_foot')">
                        <div class="emailadd_search">
                            <div class="emailaddInput input_text left"><input name="newsletter_email" type="text" placeholder="<?php echo $text_enter_emial_address;?>" id="newsletter_foot"/></div>
                            <div class="new_letter_msg"></div>
                            <div class="emailaddbtn input_btn right"><input name="submit" type="submit" value="submit"/></div>
                        </div>
                    </form>
                    </div>
                </div>
                
			</div>
		</section>
	</div>
</footer>
<section class="suport">
    <img src="<?php echo STATIC_SERVER; ?>css/images/foot/footer_shipping_payment.jpg" alt="">

    <div class="clear"></div>
	<div><?php echo $text_copyright;?></div>


</section>

<?php include_once(DIR_TEMPLATE.'/default-vertical/template/common/login_form.tpl');?>

<script type="text/javascript">

function checkNewletter(obj_id){
	var email= $("#"+obj_id).val();
	var msg='';
	if(email==''){
		msg='This is a required field.';
		$('.emailadd_search > .new_letter_msg').html(msg);
		$('.emailadd_search > .new_letter_msg').show();
	}
	else if(!checkMail(email)){

		msg='please input a right email address !';
	}
	if(msg){
		$('.emailadd_search > .new_letter_msg').html(msg);
		$('.emailadd_search > .new_letter_msg').show();
		return false;
	}
	else{
		return true;
	}

}
</script>
</body>
</html>