<?php echo $header; ?>
<style>
    .form-list .input{width:60%;border:1px solid #e6e6e6;height:25px;padding-left:5px;}
    .text-info-error{line-height:24px;height:24px;}
</style>
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
        <?php if($can_write_review){ ?>
        
	<form action="<?php echo $action;?>" method="post" enctype="multipart/form-data" id='review_from'>
        <section class="left-form">
            <div class="review-cont">
				
			       <div class="top-side">
                        <label><b>*</b><?php echo $text_input_order;?></label>
                        <div><input type="text" value="<?php echo $order_number;?>" class="input-default"   name="order_number" datatype="s5-16" errormsg="Please enter your order number"/></div>
                        <?php if(!$error_order_number){ ?>
                        <div><?php echo $text_review_order_number_note;?></div>
                        <?php }  ?>
						<?php if($error_order_number){ ?>
						<div class="red-info  left-side"><?php echo $error_order_number;?></div>
						<?php } ?>
						
                    </div>
				
                                
                                
                     
                    <div class="top-side">
                        <label><b>*</b> <?php echo $text_rating;?></label>
                        <div class="star star-s<?php echo $rating;?>  cursor-star" >
                            <span rel="star-s1" rating='1'></span><span rel="star-s2" rating='2'></span><span rel="star-s3" rating='3'></span><span rel="star-s4"rating='4'></span><span rel="star-s5" rating='5'></span>
                        </div>
                        <span class="star-text"></span>
						<input type="hidden" value="<?php echo $rating;?>" name="rating"  id='rating'/>
						<?php if($error_rating){ ?>
						<div class="red-info  left-side"><?php echo $error_rating;?></div>
						<?php } ?>
						
                    </div>
                    <div class="top-side">
                        <label><b>*</b> <?php echo $text_review_title;?></label>
                        <div><input type="text" value="<?php echo $title;?>" class="input-default"   name="title" datatype="s1" errormsg="Please enter your review title"/></div>
                        
						<?php if($error_title){ ?>
						<div class="red-info  left-side"><?php echo $error_title;?></div>
						<?php } ?>
                    </div>
                    <div class="top-side">
                        <label><b>*</b> <?php echo $text_review_content;?></label>
                        
                        <div <?php if($error_content){ ?>style="color:#cc0000"<?php } ?>><?php echo $review_content_note;?></div>
                        <div><textarea class="input-default" name="content" rows='15' datatype="s20"><?php echo $content;?></textarea></div>
                        
                    </div>

                    <div class="top-side form-list">
                        <label> <?php echo $text_uplaod_image;?>:</label>
                        <div>
                            <input type="file" name='fileImg[]'  class="input" verify="notnull"/> <a class="addcss" >+</a>
                            <div class="addbox"></div>
                            <div class="text-info-error red"></div>
                        </div>
                    </div>

                <div class="top-side">
                    <label> <?php echo $text_nickname;?></label>
                    <input type="text" value="<?php echo $nickname;?>" class="input-default"  name="nickname"/>
                </div>
            </div>





            <div class="submit-form">

                <!--<div>
                    <label><b>*</b> Your Location:</label>
                    <input type="text" value="" class="input-default" placeholder="Ex. San Jose, CA?"  />
                    <div class="red-info  left-side">Fields marked with * are required.</div>
                </div>-->
                <div class="submit-btn">
					<input type="submit" value="<?php echo $text_submit;?>" class="btn-primary btn_submit"/>
					<input type="reset" value="<?php echo $text_cancel;?>" class="btn-default bun_reset"/>
					<!--
                    <a class="btn-primary btn_submit" href="javascript:void(0);"> </a>
                    <a class="btn-default bun_reset" href="javascript:void(0);"> </a>
					-->
					
                </div>
            </div>
        </section>
	</form>
        <?php }else{ ?>
             <section class="left-form">
                <div class="review-cont">
                    <div class="top-side"><?php echo $error_max_write_review_limit; ?></div>
                </div>
             </section>
            
       <?php } ?>
        <aside class="right-product">
            <div class="tit-info"><span class="left-img"></span><?php echo $product_information;?></div>
            <div class="pro-info ">
                <div class="clearfix">
                    <div class="price">
                        <p><a href="<?php echo $product_info['url'];?>"><?php echo $product_info['name'];?></a></p>
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
                    <a href="<?php echo $product_info['url'];?>"><img src="<?php echo $product_info['thumb_image'];?>"></a>
                </div>
                <h4><?php echo $text_average_rating;?> <span class="star star-s<?php echo $product_info['rating'];?>" ></span></h4>
                <div class="bule">(<a href="<?php echo $review_list_link; ?>"> <?php echo $product_info['reviews'];?><?php echo $text_reviews;?></a> )</div>
            </div>
            <div class="dash-info">
                <?php echo $text_review_posting_guidelines;?>

            </div>
         </aside>
    </div>
</section>
<script type="text/javascript">
    $(".addcss").on("click",function(){
        if($(".add-input").index() == 3){
            $(".text-info-error").text("Add five only!");
            return ;
        }else{
            $(".text-info-error").text("");
            $(".addbox").append("<div style='margin-top:10px;' class='add-input'><input type='file' name='fileImg[]' class='input'/> <a class='delete-input addcss' >-</a></div>");
        }
    })
    $(document).on('click', '.delete-input', function() {
        $(".text-info-error").text("");
        $(this).parent("div").remove();
    });
    // 评论五角星选择
    $(document).ready(function() {
        var star_class = "",text = "";
        $('.cursor-star span').click(function(){
             $(this).parent().removeClass().addClass("star  cursor-star "+$(this).attr("rel")) ;
             star_class = "star  cursor-star "+$(this).attr("rel") ;
             text = $(this).attr("rel");
			 $('#rating').val($(this).attr("rating"));
             startext(text);
        });
        $('.cursor-star span').hover(function(){
            star_class = $(this).parent().attr("class");
            $(this).parent().removeClass().addClass("star  cursor-star "+$(this).attr("rel")) ;
            startext($(this).attr("rel"));
        },function(){
            $(this).parent().removeClass().addClass(star_class);
            startext(text);
        });
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
</script>

<?php echo $footer; ?>