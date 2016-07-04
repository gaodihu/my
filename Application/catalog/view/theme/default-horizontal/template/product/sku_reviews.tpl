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
					<div class="product-detail"  style="position:static ">
						<div class="border">
							<div class="rating-container  clearfix">
								<div class="rating-level">
									<span class="rating"><b style="margin: 0"><?php echo $product_info['rating'];?></b></span>
									<span class="star">
										<b class="stars" >
											<span class="lv0"></span>
										</b>
									</span>
									<span class="total-reviews" style="text-align: center">(<?php echo $product_info['reviews'];?> Reviews)</span>
								</div>

									  <ul id="rating-list"> 
										<?php foreach($reviews_rating_info as $rating){ ?>
									   <li data-percentage="0"> <strong><?php echo $rating['rating'];?>star </strong> <b class="obrate"> <i style="width:<?php echo $rating['rating_percent'];?>%"> </i> </b> <span class="num"><?php echo $rating['rating_total'];?>(<em class="percent"><?php echo $rating['rating_percent'];?></em>%)</span> </li> 
									   <?php } ?>
									  </ul>
									  
								<!--  <div class="customer-box">
								 									Customer’s impression:
								 									<p><a class="very-usefull" href="">Very usefull</a></p>
								 								</div>  -->
									
								<div class="evaluate" style="text-align: right">
                                    <div class="clearfix">
									<a class="common-btn-orange write-review right" href="<?php echo $add_review;?>">
                                       <?php echo $text_create_review;?>                                </a>
									</div>
                                    <span><?php echo $text_share_review;?></span>
                                </div>
							</div>
						
							<!--review-sku-cont-->
							<div class="review-sku-cont clearfix">
							<!--review-left-->
							<div class="review-left">
                                <div  id="animation_<?php echo $product_info['product_id'];?>"></div>
								<a href="<?php echo $product_info['href'];?>" ><img src="<?php echo $product_info['image'];?>" id="animation_img_<?php echo $product_info['product_id'];?>" /></a> 
								<h4>
									<a href="<?php echo $product_info['href'];?>" ><?php echo $product_info['name'];?></a>
								</h4>
								<?php if($product_info['special']){ ?>
								<del class="yj"><?php echo $product_info['price'];?></del>

								<b class="xj"><?php echo $product_info['special'];?></b>
								<?php }else{ ?>
								<b class="xj"><?php echo $product_info['price'];?></b>
								<?php } ?>
								<div class="probtn p-relative"><a class="add-to-cart common-btn-orange aprobtn" href="javascript:addToCart('<?php echo $product_info['product_id'];?>');"> <i></i> Add to Cart</a></div>	
								<!-- <a class="add-wish" href="javascript:addToWishList('<?php echo $product_info['product_id'];?>');">Add to Wish list</a> -->
							</div>	
							
							<!--review-right-->
							<div class="review-right">
							
									<!--fliter-box-->
									<div class="fliter-box">
										<span class="change-select">
											<span id="select_info"><?php echo $sort_text;?></span>
											<span class="select-option" style="display:none;">
												<?php foreach($sorts as $sort_list){ ?>
												<a href="<?php echo $sort_list['href'];?>"><?php echo $sort_list['text'];?></a>
												<?php } ?>
												
											</span>
											
										</span>
										<a class="change-tab on-list <?php if($view==0){ echo 'active';} ?>" href="<?php echo $all_review;?>"><?php echo $text_all_review;?></a>                 
										<a class="change-tab b-img <?php if($view==1){ echo 'active';} ?>" href="<?php echo $image_only;?>"><?php echo $text_image_only;?></a>
									</div>	
									<script>
										$(".change-select").hover(function(){
											$(".select-option").show();									
										},function(){
											$(".select-option").hide();
										})
										$(".select-option a").on("click",function(event){
											event.stopPropagation();
											$("#select_info").text($(this).text());
											$(".select-option").hide();
										})
									</script>
									
									<!--fliter-list-->
									<ul class="fliter-list">
									<?php foreach($reviews_list as $re_list) { ?>
										<li >
			
												<h4><a href="<?php echo $re_list['detail_href'];?>"><?php echo $re_list['title'];?></a></h4>
												<div class="star star-s<?php echo $re_list['rating'];?>"></div>
												<div class="grey-span"><span>By</span> <?php echo $re_list['author'];?>  <span>on</span> <?php echo $re_list['date_added'];?> </div>
												<p>
													<?php echo $re_list['text'];?>
													<a href="<?php echo $re_list['detail_href'];?>">Read more</a></p>
												<!-- <div class="grey-span"><span>Impression:</span> Perfecto;</div> -->
											<?php if($re_list['review_image']){ ?>
                                            <div class="spec-scroll">
                                                <a class="prev">◀</a> <a class="next">▶</a>
                                                <div class="items ">
                                                    <ul >
                                                    	<?php foreach($re_list['review_image'] as $img){ ?>
                                                        <li><img bimg="/image/<?php echo $img['origin_image'];?>" src="<?php echo $img['thumb_image'];?>" ></li>
                                                        <?php } ?>
                                                       
                                                    </ul>
                                                </div>
                                                <div style="clear:both;height:10px;line-height:10px;"></div>

                                                <div  class="spec-preview clearfix" > <span class="jqzoom"><img jqimg="/image/<?php echo $re_list['review_image'][0]['origin_image'];?>" src="/image/<?php echo $re_list['review_image'][0]['origin_image'];?>"  /></span> </div>
                                                <div class="spec-close-box"><a>close<i></i></a></div>
                                                <div style="clear:both;height:10px;line-height:10px;"></div>
                                            </div>
                                            <?php } ?>


												<div class="review-on">
												<span>
													<a href="javascript:void(0)" class="f-good review-condition" rel="<?php echo $re_list['review_id']; ?>" condition='support'><span id='support-<?php echo $re_list['review_id'];?>'><?php echo $re_list['support'];?></span></a>
													<a href="javascript:void(0)" class="f-bad review-condition" rel="<?php echo $re_list['review_id']; ?>" condition='against'><span id='against-<?php echo $re_list['review_id'];?>'><?php echo $re_list['against'];?></span></a>
							
												</span>
												<a class="f-msg" href="<?php echo $re_list['detail_href'];?>#comment"><?php echo $re_list['reply_count'];?></a>  
											
										</div>
										
										</li>
									<?php } ?>
									
									</ul>


									<!--propage-->
									
										<?php echo $pagination;?>
									
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
<?php echo $footer; ?>
