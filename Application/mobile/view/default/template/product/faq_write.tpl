<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){ ?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } else{ ?>
		<?php echo $breadcrumb['text']; ?>
		<?php } ?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php } ?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class="wrap">
	<div class="review-tit top-side"><?php echo $heading_title;?></div>
    <div class="clearfloat b-red">
	<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
        <section class="left-form">
                    <!--<div class="top-side">
                        <h4> <?php echo $text_name;?></h4>
                        <div><input type="text" value="<?php echo $name;?>" class="input-default" name="name"/></div>
                    </div>-->
                    <!--<div class="top-side">
                        <h4><?php echo $text_faq_about;?></h4>
                        <div class="top-min">
                            <label><input type="radio" value="1"  name="faq_type" <?php if($faq_type==1){?> checked="checked" <?php } ?>/><?php echo $text_faq_about_product;?></label>
                            <label><input type="radio" value="2" name="faq_type" <?php if($faq_type==2){?> checked="checked" <?php } ?>/><?php echo $text_faq_abou_shippingt;?></label>
                            <label><input type="radio" value="3" name="faq_type" <?php if($faq_type==3){?> checked="checked" <?php } ?> /><?php echo $text_faq_about_customer;?></label>
                        </div>
                    </div>-->
                    <div class="top-side">
                        <h4>*<?php echo $text_ask_question;?></h4>
                        <div><input type="text" value="<?php echo $faq_title;?>" class="input-default"  name="faq_title"/></div>
						<div><?php echo $review_title_note;?></div>
						<?php if($error_title){ ?>
						<div class="red-info  left-side"><?php echo $error_title;?></div>
						<?php } ?>
                    </div>
                    <div class="top-side">
                        <h4> *<?php echo $text_add_additional;?></h4>
                        <div><textarea  class="input-default textheight" name="faq_content"><?php echo $faq_content;?></textarea> </div>
						<div><?php echo $text_review_content_note;?></div>
						<?php if($error_content){ ?>
						<div class="red-info  left-side"><?php echo $error_content;?></div>
						<?php } ?>
                    </div>


            <!--<div class="border padding-side top-side">
                <div class="img-list">
                    <img src="images/reviews_write/up_view.png" />
                    <img src="images/reviews_write/up_view.png" />
                    <img src="images/reviews_write/up_view.png" />
                    <img src="images/reviews_write/up_view.png" />
                    <img src="images/reviews_write/up_view.png" />
                    <img src="images/reviews_write/up_view.png" />
                </div>
                <div class="btn-line"><a class="btn-default photos" href="javascript:;"><i></i>Post Photos</a> <a class="btn-primary upload" href="javascript:;"><i></i>Upload Images</a></div>
                <div class="red-info">Support GIF,BMP,JPG,and PNG. Cannot exceed 3MB per photo. Height & Width: Both between 144 to 2500 pixels.</div>
                <div class="submit-video">
                    <a class="btn-default video" href="javascript:;"><i></i>Post Videos</a>
                    <input type="text" value="" class="input-default" />
                     <span class="red-info left-side">Support Youtube Video URL.</span>
                </div>
            </div>-->

            <div class="submit-form">

                <div class="submit-btn">
                    <input type="submit" value="<?php echo $text_submit;?>" class="btn-primary btn_submit"/>
					<input type="reset" value="<?php echo $text_cancel;?>" class="btn-default bun_reset"/>
                </div>
            </div>
        </section>
	</form>
        <aside class="right-product">
            <div class="tit-info"><span class="left-img"></span><?php echo $product_information;?></div>
            <div class="pro-info ">
                <div class="clearfix">
                    <div class="price">
                        <p><?php echo $product_info['name'];?></p>
						<?php if($product_info['special']){ ?>
							<del class="grey"><?php echo $product_info['price_format'];?></del>
							<div class="red3"><?php echo $product_info['special_format'];?></div>
							
						<?php }else{ ?> 
                        	<div class="red3"><?php echo $product_info['price_format'];?></div>
						<?php } ?>
                        <?php if($product_info['as_low_as_price']){ ?>
						 <div class="green"><?php echo $text_as_low_as;?><?php echo $product_info['as_low_as_price'];?></div>
						<?php } ?>
                    </div>
                    <img src="<?php echo $product_info['thumb_image'];?>">
                </div>
                <h4><?php echo $text_average_rating;?> <span class="star star-s<?php echo $product_info['rating'];?>" ></span></h4>
                <div class="bule">( <?php echo $product_info['reviews'];?><?php echo $text_reviews;?> )</div>
            </div>
            <div class="dash-info">
                <h4>  Tips for writing questions</h4>

                1. Don't include any personal information such as your
                name, email address or other contact info, submis
                sions with personal data may be rejected.</br>
                2. Ask a question directly related to this product, category
                or topic.</br>
                3. If you want to know if a product will be right for you,
                include as much detail as possible (where will you use
                it, how often, concerns you may have ).</br>
                4. To comment on a product without asking a question,
                please use ratings and reviews.</br>

            </div>
         </aside>
    </div>
</section>
<?php echo $footer; ?>