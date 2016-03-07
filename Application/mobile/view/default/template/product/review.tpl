<?php if ($reviews_list) { ?>
	<div class="Recent_tit"><?php echo $text_recent_reviews;?></div>
	<div class="reviewList">
		<ul>
		<?php foreach($reviews_list as $review){ ?>
		<li>
			<div class="review_name_time">
				<div class="star star-s<?php echo $review['rating'];?>"></div>
				<div class="d_review_pro"><span class="gray"><?php echo $text_by;?></span><?php echo $review['author']; ?></div>
				
				<div class="d_review_time"><?php echo $review['date_added']; ?></div>
			</div>
			<div class="review_main">
				<div class="m_review_text"><span class="yh_q">“</span><?php echo $review['text']; ?><span class="yh_h">”</span></div>
	
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
			<div class="fb-like gray"><a href="#"><img src="catalog/view/theme/default/images/fb.jpg" alt=""/><?php echo $text_review_share;?></a></div>
		</li>
		<?php } ?>
										
		
										
		</ul>
	</div>
	<section class="pro_sort propage review_page">
		<!--<div class="links"><span class="ml_10 font13 bold">1/1</span></div>-->
		<?php echo $pagination;?>
	</section>
<?php } else { ?>	
	<div class="Recent_tit"><?php echo $text_no_reviews;?></div>						
<?php } ?>												