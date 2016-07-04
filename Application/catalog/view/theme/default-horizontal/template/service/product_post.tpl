<?php echo $header; ?>
<script type="text/javascript" src="js/jquery/validform_active.js"></script>
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
<section class="box wrap clearfix" >
	<aside class="boxLeft left category-left">
	
		<section class="leftpro border">
			<div class="leftprotit bold"><?php echo $text_hot;?></div>
			<ul>
				<?php foreach($special_lists as $special_list){ ?>
				<li><a href="<?php echo $special_list['link'];?>"><img src="<?php echo $special_list['image'];?>" alt="<?php echo $special_list['title'];?>"/></a>
				</li>
				<?php } ?>
			</ul>
		</section>
		

		<section class="gg_right">
			<?php foreach($side_banner as $banner){ ?>
			<figure class="gg_img"><a href="<?php echo $banner['link'];?>"><img src="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>"/></a></figure>
			<?php } ?>
		</section>
	</aside>
	<section class="boxRight category-right">
            <div class="review-tit "><?php echo $heading_title;?></div>
            <div class="review-info"><?php echo $text_desc;?></div>

                <div style="width: 700px;">
                    <!--------------------------left-form------------------------------>
					<section>
						<div class='success'>
						<?php echo $success;?>
						<?php foreach($error as $item){
							echo $item."</br>";
						}
						?>
						</div>
					</section>
				<form class="form" action="<?php echo $action;?>" enctype="multipart/form-data" method="post" id='new_pro_uplaod'>
                    <div class="review-cont add-box">
                        <div class="input-box">
                            <label><b>*</b><?php echo $enter_user_name;?></label>
                            <div><input type="text" class="input-default" value='<?php echo $user_name;?>' name='user_name' verify="notnull"/></div>
                        </div>
                        <div class="input-box">
                            <label><b>*</b> <?php echo $enter_user_email;?></label>
                            <div><input type="text" class="input-default" value='<?php echo $user_email;?>' name='user_email' verify="email"/></div>
                        </div>
						<?php if($product_name&&$count=count($product_name)){ ?>
						<?php for($i=0;$i<$count;$i++){ ?>
                        <div class="input-box">
                            <label><b>*</b><?php echo $enter_product_name;?></label>
                            <div><input type="text" value='<?php echo $product_name[$i];?>' name='product_name[]' class="input-default product_name" verify="notnull"/></div>
                        </div>

						<div class="input-box">
                            <label><?php echo $enter_color;?></label>
                            <div><input type="text" value='<?php echo $product_color[$i];?>' name='product_color[]' class="input-default" /></div>
                        </div>
                        <div class="input-box">
                            <label><b>*</b> <?php echo $enter_product_image;?></label>
                        </div>

                        <div class="border setimgborder" style="position:relative">

                            <div class="btn-line">

							 </div>
							 <input type='file' verify="img-notnull" value='' name='product_image[]' style="cursor: pointer;height: 35px; position: absolute; top: 10px;">
                            <div class="red-info"><?php echo $text_file_type_limit;?></div>

                        </div>
						<div class="input-box">
                            <label><?php echo $enter_expected_price;?></label>
							
                            <div>
							<select name="currency[]" style="height:35px; line-height:35px; width:60px;">
								<?php foreach($currencies as $currency){ ?>
								<?php if($product_currency[$i]==$currency['code']){ ?>
								<option value="<?php echo $currency['code'];?>" selected="selected"><?php echo $currency['code'];?></option>
								<?php }else{ ?>
								<option value="<?php echo $currency['code'];?>"><?php echo $currency['code'];?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<input type="text" value='<?php echo $product_price[$i];?>' name='product_price[]' class="input-default" style="width:595px;"/>
                        </div>
						<div class="input-box">
                            <label><?php echo $enter_product_description;?></label>
                            <div><textarea class="input-default" style="height: 100px;" name='product_description[]'><?php echo $product_description[$i];?></textarea></div>
                        </div>
						<div class="input-box">
                            <label><b>*</b> <?php echo $enter_url;?></label>
                            <div><input type="text" value='<?php echo $product_link[$i];?>' name='product_link[]' verify="notnull" class="input-default" /></div>
                        </div>
                        <div class="input-box">
                            <label><b>*</b> <?php echo $enter_anticipant_shipment;?></label>
                            <div>
                                <select class="input-default" name="shipment[]"  verify="notnull">
                                    <?php foreach($shipment_method as $item){ ?>
                                    <?php if($shipment[$i]==$item){ ?>
                                    <option value="<?php echo $item;?>" selected='true'><?php echo $item;?></option>
                                    <?php }else{ ?>
                                    <option value="<?php echo $item;?>"><?php echo $item;?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="input-box">
                            <label><?php echo $enter_additional_comment;?></label>
                            <div><textarea class="input-default" style="height: 100px;" name='comment[]'><?php echo $comment[$i];?></textarea></div>
                        </div>
						<?php } ?>
						<?php }else{ ?>
						
						
						
						
						
						<div class="input-box">
                            <label><b>*</b><?php echo $enter_product_name;?></label>
                            <div><input type="text" value='' name='product_name[]' class="input-default product_name"  verify="notnull"/></div>

                        </div>
						<div class="input-box">
                            <label><?php echo $enter_color;?></label>
                            <div><input type="text" value='' name='product_color[]' class="input-default" /></div>
                        </div>
	
                        <div class="input-box">
                            <label><b>*</b> <?php echo $enter_product_image;?></label>
                        </div>

                        <div class="border  setimgborder" style="position:relative">
                            
                            <div class="btn-line">
							
							 </div>
							 <input type='file' value=''  verify="img-notnull" name='product_image[]' style="cursor: pointer;height: 35px; position: absolute; top: 10px;">
                            <div class="red-info"><?php echo $text_file_type_limit;?></div>

                        </div>
						<div class="input-box">
                            <label><?php echo $enter_expected_price;?></label>
                            <div>
							<select name="currency[]" style="height:35px; line-height:35px; width:60px;">
								<?php foreach($currencies as $currency){ ?>
								<option value="<?php echo $currency['code'];?>"><?php echo $currency['code'];?></option>
								<?php } ?>
							</select>
							<input type="text" value='' name='product_price[]' class="input-default"  style="width:595px;"/></div>
                        </div>
						<div class="input-box">
                            <label><?php echo $enter_product_description;?></label>
                            <div><textarea class="input-default" style="height: 100px;" name='product_description[]'></textarea></div>
                        </div>
						<div class="input-box">
                            <label><b>*</b> <?php echo $enter_url;?></label>
                            <div><input type="text" value='' name='product_link[]'  verify="notnull" class="input-default" /></div>
                        </div>
                        <div class="input-box">
                            <label><b>*</b> <?php echo $enter_anticipant_shipment;?></label>
                            <div>
								<select class="input-default" name="shipment[]"  verify="notnull">
									<?php foreach($shipment_method as $item){ ?>
									
									<option value="<?php echo $item;?>"><?php echo $item;?></option>
									
								<?php } ?>
								</select>
							</div>
                        </div>
                        <div class="input-box">
                            <label><?php echo $enter_additional_comment;?></label>
                            <div><textarea class="input-default" style="height: 100px;" name='comment[]'></textarea></div>
                        </div>
						<?php } ?>

                    </div>
                    <div id="addbox" style="margin-top:20px;">

                    </div>
                        <div class="btn-box" style="margin: 60px 0 40px 0;">
                            <a class="btn-primary bule-bg" href="javascript:;" id="addbtn"><?php echo $text_add_new;?></a>
                            <a class="btn-primary send" href="javascript:;" id='button_product'><?php echo $text_submit;?></a>
                        </div>

				</form>

                    



        </div> <!--------------------------left-form end------------------------------>


    </section>
</section >
	
		

</section>
<script type="text/javascript">
        //提交，最终验证。
        $('.send').click(function(){
            $(".form input,.form select").trigger('blur');
            var numError = $('.form .onError').length;

            if(numError){
                return false;
            }
            $("#new_pro_uplaod").submit();
        });


</script>
<script type="text/javascript">
	var product_name ='<?php echo $enter_product_name;?>';
	var product_color ='<?php echo $enter_color;?>';
	var product_image ='<?php echo $enter_product_image;?>';
	var product_expected_price ='<?php echo $enter_expected_price;?>';
	var product_description ='<?php echo $enter_product_description;?>';
	var product_link ='<?php echo $enter_url;?>';
	var product_shipment ='<?php echo $enter_anticipant_shipment;?>';
	var product_comment ='<?php echo $enter_additional_comment;?>';
	var file_type_limit ='<?php echo $text_file_type_limit;?>';
	var text_uplaod_image ='<?php echo $text_uplaod_image;?>';
	var text_delete ='<?php echo $text_delete;?>';
    $("#addbtn").click(function(){
		var html='';
		html ='<div class="review-cont add-box" style="margin-top: 20px;">'+
							'<div class="input-box">'+
								'<label><b>*</b>'+product_name+'</label>'+
								'<div><input type="text" value="" class="input-default product_name" name="product_name[]" verify="user"/></div>'+
							'</div>'+
							'<div class="input-box">'+
								'<label>'+product_color+'</label>'+
								'<div><input type="text" value="" class="input-default" name="product_color[]"/></div>'+
							'</div>'+

							'<div class="input-box">'+
								'<label><b>*</b> '+product_image+'</label>'+
							'</div>'+
							'<div class="border setimgborder" style="position:relative">'+
                        '<div class="btn-line"></div>'+
						' <input type="file" verify="img-notnull" value="" name="product_image[]" style="cursor: pointer;height: 35px; position: absolute; top: 10px;">'+
                        '<div class="red-info">'+file_type_limit+'</div>'+

							'</div>'+
							'<div  class="input-box">'+
								'<label>'+product_expected_price+'</label>'+
								'<div><select name="currency[]" style="height:35px; line-height:35px; width:60px;">';
					<?php foreach($currencies as $curreny){ ?>	
					
						<?php foreach($currencies as $currency){ ?>
						var cureny_code ="<?php echo $currency['code'];?>"
						html+=	'<option value="'+cureny_code+'">'+cureny_code+'</option>';
					<?php } ?>
					<?php } ?>				
						html +='</select><input type="text" value="" class="input-default" name="product_price[]" style="width:595px;"/></div>'+
							'</div>'+
							'<div  class="input-box">'+
								'<label>'+product_description+'</label>'+
								'<div><textarea class="input-default" style="height: 100px;" name="product_description[]"></textarea></div>'+
							'</div>'+
							' <div  class="input-box">'+
								'<label><b>*</b> '+product_link+'</label>'+
								'<div><input type="text" value="" class="input-default" name="product_link[]" verify="notnull"/></div>'+
							'</div>'+
							'<div class="input-box">'+
                            '<label><b>*</b> '+product_shipment+'</label>'+
                            '<div><select class="input-default" name="shipment[]" verify="notnull">';
		<?php foreach($shipment_method as $item){ ?>
			var ship_item ='<?php echo $item;?>';
			html +='<option value="<?php echo $item;?>">'+ship_item+'</option>';
		<?php } ?>
		html += '</select>'+
							'</div>'+
                        '</div>'+
                        '<div class="input-box">'+
                            '<label>'+product_comment+'</label>'+
                            '<div><textarea class="input-default" style="height: 100px;" name="comment[]"></textarea></div>'+
                        '</div>'+
						'<div class="btn-box">'+
								'<a class="btn-default delete-btn">'+text_delete+'</a>'+
								'</div>'+
							'</div>';
        $("#addbox").append( html);

    });


    $(document).on('click', '.delete-btn', function() {
        $(this).parents(".add-box").remove();
    });



</script>
<?php echo $footer; ?>

