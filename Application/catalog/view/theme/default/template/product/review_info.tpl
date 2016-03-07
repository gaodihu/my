<?php echo $header;?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="p-relative" >
		<span >
		<?php if($breadcrumb['href']){
		?>
            <a href="<?php echo $breadcrumb['href']; ?>"  ><?php echo $breadcrumb['text']; ?></a>
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

	<div class="wrap clearfix">
	<?php if($success){ ?>
	<div class="message"><?php echo $success;?></div>
	<?php } ?>
			<!-- col-extra -->
			<div class="col-extra">
				<section class="gg_right">
					<?php foreach($side_banner as $banner){ ?>
					<figure class="gg_img"><a href="<?php echo $banner['link'];?>"><img src="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>"/></a></figure>
					<?php } ?>
				</section>
				<section class="leftpro border clearfix">
					<div class="leftprotit bold"><?php echo $text_popular_products;?></div>
					<ul>
					<?php foreach($max_review_products as $product){ ?>
						<li><div class="img"><a href="<?php echo $product['url_path'];?>"><img src="<?php echo $product['image'];?>" alt="<?php echo $product['name'];?>"/></a></div>
						<div class="t"><a href="<?php echo $product['url_path'];?>"><?php echo $product['name'];?></a></div>
						<div class="howmuch"><?php echo $text_total_reviews;?>:<span class="xj"><?php echo $product['count'];?></span></div>
						</li>
					<?php } ?>
					</ul>
				</section>
				</div>

				<!-- col-main -->
				<div class="col-main">
					<div class="product-detail" style="position:static ">
						<div class="border">
							<div class="rating-container  clearfix">
								<div class="rating-level">
									<span class="rating"><b><?php echo $product_info['rating'];?></b></span><a href="<?php echo $all_pro_review;?>">
									<span class="star">
										<b class="stars">
											<span class="lv0"></span>
										</b>
									</span>
									<span class="total-reviews" >
										<?php if($product_info['reviews']==1){ ?>
                                        <span class="hoverblue">(<?php echo $product_info['reviews'];?> <?php echo $text_review;?>)</span>
										<?php }else{ ?>
										<span  class="hoverblue">(<?php echo $text_see_all;?> <?php echo $product_info['reviews'];?> <?php echo $text_reviews;?>)</span>
										<?php } ?>

									</span></a>

								</div>

									  <ul id="rating-list">
										<?php foreach($reviews_rating_info as $rating){ ?>
									   <li data-percentage="0"> <strong><?php echo $rating['rating'];?>star </strong> <b class="obrate"> <i style="width:<?php echo $rating['rating_percent'];?>%"> </i> </b> <span class="num"><?php echo $rating['rating_total'];?>(<em class="percent"><?php echo $rating['rating_percent'];?></em>%)</span> </li>
									   <?php } ?>
									  </ul>

								<!-- <div class="customer-box">
									Customer’s impression:
									<p><a class="very-usefull" href="">Very usefull</a></p>
								</div> -->

								<div class="evaluate" style="text-align: right">
                                    <div class="clearfix">
									<a class="common-btn-orange write-review right" href="<?php echo $add_review;?>">
                                      <?php echo $text_create_review;?>                             </a>
									</div>
                                    <span ><?php echo $text_share_review;?></span>
                                </div>
							</div>

							<!--review-sku-cont-->
							<div class="review-sku-cont clearfix">
							<!--review-left-->
							<div class="review-left">
                                <div  id="animation_<?php echo $product_info['product_id'];?>"></div>
								<a href="<?php echo $product_info['href'];?>" ><img src="<?php echo $product_info['image'];?>"  id="animation_img_<?php echo $product_info['product_id'];?>"/></a>
								<h4>
									<a href="<?php echo $product_info['href'];?>" ><?php echo $product_info['name'];?></a>
								</h4>
								<?php if($product_info['special']){ ?>
								<del class="yj"><?php echo $product_info['price'];?></del>

								<b class="xj"><?php echo $product_info['special'];?></b>
								<?php }else{ ?>
								<b class="xj"><?php echo $product_info['price'];?></b>
								<?php } ?>
								<div class="probtn p-relative"><a class="add-to-cart common-btn-orange aprobtn" href="javascript:addToCart('<?php echo $product_info['product_id'];?>');"> <i></i> <?php echo $button_cart;?></a></div>
								<!-- <a class="add-wish" href="javascript:addToWishList('<?php echo $product_info['product_id'];?>');">Add to Wish list</a> -->
							</div>

							<!--review-right-->
							<div class="review-right">



									<!--fliter-list-->
									<div class="fliter-list review-post-con">


												<h4>
												<span class="review-on">
													<span>
														<a href="javascript:void(0)" class="left f-good review-condition" rel="<?php echo $review_info['review_id']; ?>" condition='support' style="margin-right: 5px!important;display: inline!important;"><span style="margin-right: 0px" id='support-<?php echo $review_info['review_id'];?>'><?php echo $review_info['support'];?></span></a>
														<a class="left" style="margin: 0;padding: 0;margin-right: 2px;">|</a>
                                                    <a href="javascript:void(0)" class="left f-bad review-condition" rel="<?php echo $review_info['review_id']; ?>" condition='against'><span id='against-<?php echo $review_info['review_id'];?>'><?php echo $review_info['against'];?></span></a>
													</span>
												</span>
												<?php echo $review_info['title'];?>
												</h4>
												<div class="star star-s<?php echo $review_info['rating'];?>"></div>
												<div class="grey-span"><span>By</span>  <?php echo $review_info['author'];?> <span><?php echo $text_on;?></span> <?php echo $review_info['date_added'];?>  </div>
												<!-- <div class="grey-span"><span>Impression:</span> Perfecto;</div> -->
												<?php echo $review_info['text'];?>

											<?php if($review_info['review_image']){ ?>
                                            <div class="spec-scroll">
                                                <a class="prev">◀</a> <a class="next">▶</a>
                                                <div class="items ">
                                                    <ul style='margin-top:0px;'>
                                                    	<?php foreach($review_info['review_image'] as $img){ ?>
                                                        <li><img bimg="/image/<?php echo $img['origin_image'];?>" src="<?php echo $img['thumb_image'];?>" ></li>
                                                        <?php } ?>

                                                    </ul>
                                                </div>
                                                <div style="clear:both;height:10px;line-height:10px;"></div>
                                                <div  class="spec-preview clearfix"> <span class="jqzoom"><img jqimg="image/<?php echo $review_info['review_image'][0]['origin_image'];?>" src="image/<?php echo $review_info['review_image'][0]['origin_image'];?>" /></span> </div>
                                                <div class="spec-close-box"><a>close<i></i></a></div>
                                                <div style="clear:both;height:10px;line-height:10px;"></div>
                                            </div>
                                            <?php } ?>
												
												<h4 class="post-tit"><?php echo $text_post_comment;?></h4>
												<form method='post' action="<?php echo $form_action;?>" id='review_comment_form'>
												<div>
													<textarea  class="review-edit" name='comment'></textarea>
                                                    <script>
                                                        $(function(){
                                                            $(".review-edit").focus(function(){
                                                                if($(".review-edit").val() == "" && !is_login()){
                                                                         $('#login_tc').show();
                                                                }
                                                            })

                                                        })
                                                    </script>
												</div>
												<div><a class="common-btn-orange write-review" id='form_submit'><?php echo $text_submit;?></a></div>
												</form>
												<?php if($reply_info_list){ ?>
												<ul class="comm-list" id='comment'>
													<?php foreach($reply_info_list as $replay){ ?>
													<li>
														<div class="clearfix">
															<?php if($replay['avatar']){ ?>
															<img src="<?php echo $replay['avatar'];?>"  class="img" width='60' height='60'/>
															<?php }else{ ?>
															<img src="css/images/user_tx/user_default.png"  class="img" width='60' height='60'/>
															<?php } ?>
															<div class="info-right">
																<div  class="grey-span"><span><?php echo $text_posted_by;?></span> <?php echo $replay['firstname'];?> <span><?php echo $text_on;?></span> <?php echo $replay['date_added'];?></div>
																<p><?php echo $replay['text'];?></p>
															</div>
														</div>
													</li>
													<?php } ?>

												</ul>
												<?php } ?>


									</div>


								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
<script>
$('#form_submit').click(function(){
	var login =is_login();
	if(!login){
		$('#login_tc').show();
	}else{
		return $('#review_comment_form').submit();
	}
});
</script>
<?php echo $footer; ?>
