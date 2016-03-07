<?php echo $header; ?>
<div class="head-title tab-title">
    <a class="icon-angle-left left-btn"></a>
    <a class="tit-tab <?php if($show =='description'){ echo 'active';} ?>" rel="description" style="margin-left: 4em;"><?php echo $text_description;?></a>
    <a class="tit-tab <?php if($show =='review'){ echo 'active';} ?>"  rel="reviews"><?php echo $text_reviews;?></a>
    <a class="tit-tab <?php if($show =='faq'){ echo 'active';} ?>"  rel="faqs"><?php echo $text_faq;?></a>

</div>
<?php if($show !='description'){ ?>
<div class="details-content" id="description"  style='display:none'>
<?php }else{ ?>
<div class="details-content" id="description"  >
<?php } ?>
    <ul class="ul-table-list">
        <div class='pro_desc_block'><?php echo $text_specifications;?></div>
        <?php foreach($attribute_groups as $attributes){?>
            <li><span class="span-info-1"><?php echo $attributes['name'];?>:</span><?php echo $attributes['text'];?></li>
        <?php } ?>
    </ul>
    <div class="desc_block_info" ><?php echo $description;?></div>
    <section id="Description">
        <?php if($packaging_list){ ?>
        <div class='pro_desc_block'><?php echo $text_packaging_list;?></div>
        <div class='desc_block_info'><?php echo $packaging_list;?></div>
        <?php } ?>
        <?php if($read_more){ ?>
        <div class='pro_desc_block'><?php echo $text_read_more;?></div>
        <div class='desc_block_info'><?php echo $read_more;?></div>
        <?php } ?>
        <?php if($application_image){ ?>
        <div class='pro_desc_block'><?php echo $text_application_image;?></div>
        <div class='desc_block_info'><?php echo $application_image;?></div>
        <?php } ?>
        <?php if($size_image){ ?>
        <div class='pro_desc_block'><?php echo $text_size_image;?></div>
        <div class='desc_block_info'><?php echo $size_image;?></div>
        <?php } ?>
        <?php if($features){ ?>
        <div class='pro_desc_block'><?php echo $text_features;?></div>
        <div class='desc_block_info'><?php echo $features;?></div>
        <?php } ?>
        <?php if($installation_method){ ?>
        <div class='pro_desc_block'><?php echo $text_installation_method;?></div>
        <div class='desc_block_info'><?php echo $installation_method;?></div>
        <?php } ?>
        <?php if($video){ ?>
        <div class='pro_desc_block'><?php echo $text_video;?></div>
        <div class='desc_block_info'><?php echo $video;?></div>
        <?php } ?>
        <?php if($notes){ ?>
        <div class='pro_desc_block'><?php echo $text_notes;?></div>
        <div class='desc_block_info'><?php echo $notes;?></div>
        <?php } ?>
    </section>
</div>
<?php if($show !='faq'){ ?>
<div class="details-content"  id="faqs" style="display: none">
<?php }else{ ?>
<div class="details-content"  id="faqs">
<?php } ?>
    <?php if($faq_info){
    foreach($faq_info as $faq){ ?>
    <p class="q">Q: <?php echo $faq['faq_text'];?></p>
    <?php if($faq['reply_text']){ ?>
    <p>A: <?php echo $faq['reply_text'];?></p>
    <?php } ?>

    <?php } ?>
    <?php }else{ ?>
    <p class='review-tit'><?php echo $text_empty_questions;?></p>
    <?php } ?>
</div>
<?php if($show !='review'){ ?>
<div class="details-content"  id="reviews" style="display: none">
<?php }else{ ?>
<div class="details-content"  id="reviews">
<?php } ?>
    <div class="review-tit">( <?php echo $review_total;?> <?php echo $text_reviews;?> )</div>
    <section class="m-b20">
        <a class="long-btn orange-bg reviews-btn" ><?php echo $write_review;?></a>
    </section>
    <ul class="reviews-list">
        <?php foreach($reviews_list as $review){ ?>
        <li>
            <div class="appraise">
                <a class="good"><i class="icon-thumbs-up review_like" condition='support' review_id ='<?php echo $review['review_id'];?>'></i>(<span id='support-<?php echo $review['review_id'];?>'><?php echo $review['support'];?></span>)</a>
                <a class="bad"><i class="icon-thumbs-down review_like" condition='against' review_id ='<?php echo $review['review_id'];?>'></i>(<span id='against-<?php echo $review['review_id'];?>'><?php echo $review['against'];?></span>)</a>
            </div>
            <div class="clearfix">
                <!--<img src="images/public/user_default.png" width="88" />-->
                <div class="re-info">
                    <?php echo $review['title'];?>
                    <div class="stat">
                        <?php
                        $rating =$review['rating'];
                        for($i=1;$i<=$rating;$i++){ ?>
                        <i class="icon-star"></i>
                        <?php } ?>
                    </div>
                    <div class="date">Post on  <?php echo $review['author'];?> <?php echo $review['date_added'];?> </div>
                </div>
            </div>
            <div class="re-cont"><?php echo $review['text'];?></div>
        </li>
        <?php } ?>



    </ul>





</div>


<!-- review-alert -->

<?php if($have_error){ ?>
<div class="grey-bg" style="display: block" ></div>
<section class="pop-filter pop-review" >
<?php }else{ ?>
<div class="grey-bg" style="display: none" ></div>
<section class="pop-filter pop-review" style="display: none;">
<?php } ?>
    <div class="cont" style="">
        <div class="pop-tit" ><a class="close a-btn"><i class="icon-remove"></i></a><?php echo $write_review;?></div>
        <div class="form">
            <!-- form -->
			<form action='<?php echo $review_write;?>' method='post'>
            <ul class="form-list" >
                <li class="clearfix">
                    <label class="blod float-left"><i>*</i> <?php echo $text_rating;?></label>
                    <div class="form-star">
                        <div class="star star-s0 cursor-star" >
                            <span rel="star-s1" rating='1'></span><span rel="star-s2" rating='2'></span><span rel="star-s3" rating='3'></span><span rel="star-s4" rating='4'></span><span rel="star-s5" rating='5'></span>
                        </div>
						<input type='hidden' value="<?php echo $rating;?>" name="rating" id='rating'>
                        <span class="star-text"></span>
						<div style='clear:both'></div>
                    </div>
					<div class="form-info red" ><?php echo $error_rating;?></div>
                </li>
                <li>
                    <label class="blod"><i>*</i><?php echo $text_review_title;?></label>
                    <input type="text" value="<?php echo $title;?>" name='title'/>
                    <div class="form-info"><?php echo $review_title_note;?></div>
					<div class="form-info red"><?php echo $error_title;?></div>
                </li>
                <li>
                    <label class="blod" ><i>*</i><?php echo $text_review_content;?></label>
                    <textarea  class="default-input " style="width:98.5%;height: 5em;" name='content'><?php echo $content;?></textarea>
                    <div class="form-info"><?php echo $text_review_content_note;?></div>
					<div class="form-info red"><?php echo $error_content;?></div>
                </li>
                <li>
                    <label><i>*</i> <?php echo $text_nickname;?></label>
                    <input type="text" value="<?php echo $nick_name;?>"  placeholder="Ex. Jim the Runner?" name='nickname'/>
                </li>
                <!-- <li>
                    <label><i>*</i> Your Location</label>
                    <input type="text" value="" placeholder="Ex. San Jose, CA?"/>
                </li> -->
            </ul>

            <div >
                <input type='submit' class="long-btn orange-bg"  value="<?php echo $text_submit;?>">
            </div>
			</form>
        </div>
    </div>
</section>
<div class="fixed-btn">
    	<?php if($product_info['stock_status_id']==7){ ?>
		<a class="red-bg-btn red-bg" href="javascript:addToCart('<?php echo $product_info['product_id'];?>');" ><?php echo $button_cart;?></a>
		<?php }else{ ?>
		<a class="grey-bg-btn" href="javascript:;" ><?php echo $button_cart;?></a>
		<?php } ?>
    <a class="orange-bg"  href="javascript:addToCart('<?php echo $product_info['product_id'];?>',1,2);"><i class="icon-shopping-cart" ></i> <?php echo $button_buy_now;?></a>
</div>
</div>
<script type="text/javascript" >
	$.pagescroll.init(".product .con-box");
	$(window).unbind('scroll');//取消滚动绑定
  // 详情页面标题切换
	$(".tit-tab").click(function(){
		$(this).siblings(".tit-tab").removeClass("active");
		$(this).addClass("active");
		$(".details-content").hide();
		$("#"+$(this).attr("rel")).show();
		
		if($(this).attr("rel") == "description"){
			$(window).unbind('scroll');//取消滚动绑定
		}else if($(this).attr("rel") == "reviews"){
			bindPagescroll();
			$.pagescroll.ajax_fn = ajax_fn_reviews;
		}else if($(this).attr("rel") == "faqs"){
			bindPagescroll();
			$.pagescroll.ajax_fn = ajax_fn_faqs;
		}
	})
	
	/*  绑定滚动  */
	function  bindPagescroll(){
		$(window).bind('scroll',function(){$.pagescroll.show()});
	}
	
	/*  page ajax reviews  */
	function ajax_fn_reviews(){
		<?php if($review_fanye){ ?>
		$.ajax({
			type: "get",
			url: '<?php echo htmlspecialchars_decode($reviews_list_ajax);?>',
				data: 'page=' + ($.pagescroll.index+1),
			dataType: "json",
			success: function (data) {
				var tempArr = [],HTML;
				$.pagescroll.index = $.pagescroll.index +1;
				if($.pagescroll.index == 0){
					$.pagescroll.setHtml("");
					return false;
				}
					if(data['error']==0){
				$.each( data['data'], function(index, content)
				{
					var rating =content.rating;
					var rating_html ='';
					for(var i=1;i<=rating;i++){
						rating_html +='<i class="icon-star"></i>';
					}
					var inHtml =  '<li>'+
							'<div class="appraise">'+
								'<a class="good"><i class="icon-thumbs-up review_like" condition="support" review_id ="'+content.review_id+'"></i>(<span id="support-'+content.review_id+'">'+content.support+'</span>)</a>'+
								'<a class="bad"><i class="icon-thumbs-down review_like " condition="against" review_id ="'+content.review_id+'"></i>(<span id="against-'+content.review_id+'">'+content.against+'</span>)</a>'+
							'</div>'+
							'<div class="clearfix">'+
								'<div class="re-info">'+
									content.title
									+'<div class="stat">'+
										rating_html
									+'</div>'+
									'<div class="date">Post on  '+content.author+ content.date_added +'</div>'+
								'</div>'+
							'</div>'+
							'<div class="re-cont">'+content.text+'</div>'+
						'</li>';
					tempArr.push(inHtml);
				});
					 HTML = tempArr.join('');
				    $.pagescroll.setHtml(HTML);
					}else{
						$.pagescroll.setHtml(data['message']);
					}
			   
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				//console.log(errorThrown);
			}
		});
		$.pagescroll.init("#reviews .reviews-list");
		<?php } ?>
	}
	
	/*  page ajax faqs  */
	function ajax_fn_faqs(){
		<?php if($faq_fanye){ ?>
		$.ajax({
			type: "get",
			url: '<?php echo htmlspecialchars_decode($faq_list_ajax);?>',
			data: 'page=' + ($.pagescroll.index+1),
			dataType: "json",
			success: function (data) {
				var tempArr = [],HTML;
				$.pagescroll.index = $.pagescroll.index +1;
				if($.pagescroll.index == 0){
					$.pagescroll.setHtml("");
					return false;
				}
					if(data['error']==0){
				$.each( data['data'], function(index, content)
				{
					var rating =content.rating;
					var rating_html ='';
					for(var i=1;i<=rating;i++){
						rating_html +='<i class="icon-star"></i>';
					}
					var inHtml =  '<li>'+
							'<div class="appraise">'+
								'<a class="good"><i class="icon-thumbs-up review_like" condition="support" review_id ="'+content.review_id+'"></i>(<span id="support-'+content.review_id+'">'+content.support+'</span>)</a>'+
								'<a class="bad"><i class="icon-thumbs-down review_like " condition="against" review_id ="'+content.review_id+'"></i>(<span id="against-'+content.review_id+'">'+content.against+'</span>)</a>'+
							'</div>'+
							'<div class="clearfix">'+
								'<div class="re-info">'+
									content.title
									+'<div class="stat">'+
										rating_html
									+'</div>'+
									'<div class="date">Post on  '+content.author+ content.date_added +'</div>'+
								'</div>'+
							'</div>'+
							'<div class="re-cont">'+content.text+'</div>'+
						'</li>';
					tempArr.push(inHtml);
				});
					 HTML = tempArr.join('');
				    $.pagescroll.setHtml(HTML);
					}else{
						$.pagescroll.setHtml(data['message']);
					}
			   
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				//console.log(errorThrown);
			}
		});
		$.pagescroll.init("#reviews .reviews-list");
		<?php } ?>
	}
			 
    // 评论五角星选择
    $(document).ready(function() {
        var star_class = "",text = "";
        $(".cursor-star span").click(function(){
            $(this).parent().removeClass().addClass("star  cursor-star "+$(this).attr("rel")) ;
            star_class = "star  cursor-star "+$(this).attr("rel") ;
            text = $(this).attr("rel");
			rating =$(this).attr("rating");
			$('#rating').attr('value',rating);
            startext(text);
        });
        $(".reviews-btn").click(function(){
            $(".pop-review").show();
            $(".grey-bg").show();
            $(".fixed-btn").hide();
        })
    });

    function startext(type){
        var text = $(".star-text")
        if(type == "star-s1"){
            text.text("( Poor )");
        }else if(type == "star-s2"){
            text.text("( Fair )");
        }else if(type == "star-s3"){
            text.text("( Average )");
        }else if(type == "star-s4"){
            text.text("( Good )");
        }else if(type == "star-s5"){
            text.text("( Excellent )");
        }else if(type == ""){
            text.text("");
        }
    }

    $(window).scroll(function() {
        var scroll = $(window).scrollTop(),wl = $(".tab-title").width();
        var head_height= $("header").height() ;
        // console.log(head_height);
        if(scroll > head_height){
            $(".tab-title").addClass("tab-title-fixed").css({"width":wl});
            //console.log(2);
        }

        if(scroll < head_height){
            if($(".tab-title").hasClass("tab-title-fixed")){
                $(".tab-title").removeClass("tab-title-fixed");
                //console.log(1);
            }
        }
    });


    $(".fixed-btn").css({"width":$(".fixed-btn").width(),"position": "fixed","bottom": 0,"z-index": 999});
    
/*  page lodding  */

//评论支持
$('.review_like').live('click',function(){
	//是否登录
	var is_login ="<?php echo $is_login;?>"
	if(!is_login){
		window.location.href="index.php?route=account/login";
	}
	var condition =$(this).attr('condition');
	var review_id = $(this).attr('review_id');
	var id=condition+'-'+review_id;
	var num =parseInt($('#'+id).html())+1;
	$.ajax({
		url: 'index.php?route=product/product/supportreview',
		type: 'get',
		data: 'review_id='+review_id+"&num="+num+'&condition='+condition,
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				$('#'+id).html(json['content']);
			}
		},
		error: function (xhr, type, exception) {
		      //获取ajax的错误信息
             alert(xhr.responseText, "Failed");
         }
	});
})
</script>
<?php echo $footer; ?>