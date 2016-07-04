<?php if ($reviews_list) { ?>
	<div class="Recent_tit"><?php echo $text_recent_reviews;?></div>
	<div class="reviewList">
		<ul>
		<?php foreach($reviews_list as $review){ ?>
		<li>
			<div class="review_name_time width-type-1">
				<div class="white-space-nowrap"><a href="<?php echo $review['href'];?>"><?php echo $review['title'];?></a></div>
				<div class="star star-s<?php echo $review['rating'];?>"></div>
				<div class="d_review_pro white-space-nowrap"><span class="gray"><?php echo $text_by;?></span><?php echo $review['author']; ?></div>
				
				<div class="d_review_time"><?php echo $review['date_added']; ?></div>
			</div>
			<div class="review_main">
				<div class="m_review_text">
                    <?php echo $review['text']; ?>
                    <div class="review-on"><a class="f-msg border-none" href="<?php echo $review['href'];?>#comment"><?php echo $review['reply_count'];?></a>  </div>
                </div>
					<ul class="review-img-list clearfix">
					<?php foreach($review['image'] as $image){ ?>
					<li><a><img src="<?php echo $image['thumb_image'];?>" width="70" class="review-m-img" bigsrc="<?php echo $image['origin_image'];?>"/></a></li>
					<?php } ?>
                  
					 </ul>
                <div class="showbig-img" >
                    <img src="" width="350"/>
                    <div class="spec-close-box"><a>close<i></i></a></div>
                </div>
			</div>

			<div class="review_like">
				<p> <?php echo $text_review_helpful;?> </p>
				<div class="likeWrapper"> 
					<a href="javascript:void(0)" class="like review-condition" rel="<?php echo $review['review_id']; ?>" condition='support'><span class="litb-icon-praise">(<span id='support-<?php echo $review['review_id'];?>'><?php echo $review['support'];?></span>)</span> </a> 
					<span class="gap">|</span> 
					<a href="javascript:void(0)" class="unlike review-condition" rel="<?php echo $review['review_id']; ?>" condition='against'><span class="litb-icon-opposition">(<span id='against-<?php echo $review['review_id'];?>'><?php echo $review['against'];?></span>)</span> </a> 
			</div>
			<div class="review_tip hide"></div>
			</div>

		</li>
		<?php } ?>
										
		
										
		</ul>
	</div>
	
		<!--<div class="links"><span class="ml_10 font13 bold">1/1</span></div>-->
		<?php echo $pagination;?>
	
	<div class="Recent_tit" style='margin-top:20px;'><?php echo $text_get_reviews;?></div>	
<?php } else { ?>	
	<div class="Recent_tit"><?php echo $text_no_reviews;?></div>						
<?php } ?>
<script>

        $(".review-m-img").on("click",function(){

            var spec_preview_img = 'image/'+$(this).attr("bigsrc");
            $(this).parents(".review_main").find(".showbig-img img").attr("src",spec_preview_img);
            $(this).parents(".review_main").find(".showbig-img").fadeIn();
        })

        $(".showbig-img .spec-close-box a").on("click",function(){
            $(this).parents(".showbig-img").fadeOut();
        })



</script>