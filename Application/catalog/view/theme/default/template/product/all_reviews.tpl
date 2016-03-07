<?php echo $header; ?>

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
					<div class="leftprotit bold">Popular Products</div>
					<ul>
					<?php foreach($max_review_products as $product){ ?>
						<li><div class="img"><a href="<?php echo $product['url_path'];?>"><img src="<?php echo $product['image'];?>" alt="<?php echo $product['name'];?>"/></a></div>
						<div class="t"><a href="<?php echo $product['url_path'];?>"><?php echo $product['name'];?></a></div>
						<div class="howmuch">Total Reviews:<span class="xj"><?php echo $product['count'];?></span></div>
						</li>
					<?php } ?>
					</ul>
				</section>
				</div>
				
				<!-- col-main -->
				<div class="col-main">
					<div class="product-detail">
						<div class="border">
							<div class="leftprotit bold"><?php echo $text_review_category;?></div>
							<div>
								<ul class="led-light-list clearfix">
								<?php foreach($catalog_reviews as $catalog_review){ ?>
									<?php if($category_id ==$catalog_review['catalog_id']){ ?>
									<li class="active"><a href="<?php echo $catalog_review['href'];?>"><?php echo $catalog_review['catalog_name'];?> (<?php echo $catalog_review['num'];?>)</a></li>
									<?php }else{ ?>
									<li><a href="<?php echo $catalog_review['href'];?>"><?php echo $catalog_review['catalog_name'];?> (<?php echo $catalog_review['num'];?>)</a></li>
									<?php } ?>
								<?php } ?>
									
								</ul>
							
							</div>
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
							<ul class="fliter-list ">
							<?php foreach($reviews_list as $re_list) { ?>
								<li >
									<div class="clearfix">
									<a href="<?php echo $re_list['href'];?>" class="img"><img src="<?php echo $re_list['image'];?>" alt="<?php echo $re_list['href'];?>"/></a>
									<div class="f-right-info">
										<h4><a href="<?php echo $re_list['detail_href'];?>"><?php echo $re_list['title'];?></a></h4>
										<div class="star star-s<?php echo $re_list['rating'];?>"></div>
										<div class="grey-span"><span>By</span> <?php echo $re_list['author'];?>  <span>on</span> <?php echo $re_list['date_added'];?> </div>
										<p>
											<?php echo $re_list['text'];?>
											<a href="<?php echo $re_list['detail_href'];?>"><?php echo $text_read_more;?></a></p>

										<?php if($re_list['review_image']){ ?>
										<div class="spec-scroll" >
                                            <a class="prev">◀</a> <a class="next">▶</a>
                                            <div class="items " >
                                                <ul style='margin-top:0px;"'>
                                                	<?php foreach($re_list['review_image'] as $img){ ?>
                                                    <li><img bimg="/image/<?php echo $img['origin_image'];?>" src="<?php echo $img['thumb_image'];?>"></li>

                                                    <?php } ?>
                                                   
                                                </ul>
                                            </div>
                                            <div style="clear:both;height:10px;line-height:10px;"></div>
                                            <div  class="spec-preview clearfix"> <span class="jqzoom"  ><img jqimg="images/b1.jpg" src="images/s1.jpg"  /></span> </div>
                                            <div class="spec-close-box"><a><?php echo $text_close;?><i></i></a></div>
                                            <div style="clear:both;height:10px;line-height:10px;"></div>
                                            </div>
										<?php } ?>


										<!-- <div class="grey-span"><span>Impression:</span> Perfecto;</div> -->








										<div class="review-on">
											<span>
												<a href="javascript:void(0)" class="f-good review-condition" rel="<?php echo $re_list['review_id']; ?>" condition='support'><span id='support-<?php echo $re_list['review_id'];?>'><?php echo $re_list['support'];?></span></a>
												<a href="javascript:void(0)" class="f-bad review-condition" rel="<?php echo $re_list['review_id']; ?>" condition='against'><span id='against-<?php echo $re_list['review_id'];?>'><?php echo $re_list['against'];?></span></a>
						
											</span>
											<a class="f-msg" href="<?php echo $re_list['detail_href'];?>#comment"><?php echo $re_list['reply_count'];?></a>  
											
										</div>
									</div>
									</div>
								</li>
						   <?php } ?>
								



							</ul>
							
							
								<?php echo $pagination;?>
							
						</div>
						
					</div>
				</div>
		</div>

<?php echo $footer; ?>
