<?php echo $header; ?>


<div class="grey-bg" style="display: none"></div>




<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>" class="title-length" itemprop="url"><meta itemprop="title" content="<?php echo $breadcrumb['text'];?>"><?php echo $breadcrumb['text']; ?>  <?php echo $breadcrumb['separator']; ?></a>
		<?php
		}
		else{
		?>
		<meta itemprop="title" content="<?php echo $breadcrumb['text'];?>"><?php echo $breadcrumb['text']; ?>
		<?php
		}
		?>
		</span>
		<?php if($breadcrumb['child']){
		?>
			<p><span class="xia_sj"></span></p>
			<div class="showdata-list xiasj-list" style="display: none">
                    <ul>
                        <?php foreach($breadcrumb['child'] as $child){ ?>
                        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                           <a  href="<?php echo $child['href'];?>" title="<?php echo $child['name'];?>"><?php echo $child['name'];?></a></li>
                        <?php } ?>
                    </ul>
                </div>
		<?php
		}
		?>

	</li>
	<?php
	}
	?>

	</ul>
	</div>
	<div class="clear"></div>
</nav>

<!-- Product start -->
<div itemscope itemtype="http://data-vocabulary.org/Product">
<section class="wrap product product-extra-none" style="margin-top: 20px;">
<?php if($success){ ?>
<div class="message"><?php echo $success;?></div>
<?php } ?>



<div id="mainContainer">


<div class="box ">
    <div class="clearfix">
        <div class="left-pro">
            <div class="t1">
                <?php if(count($images)>5) { ?>
                <img src="<?php echo STATIC_SERVER; ?>css/images/product/gotop.gif" id="gotop" />
                <?php } ?>
                <div id="showArea">
                    <?php $i =0; ?>
                    <?php foreach($images as $image){
                                ?>
                    <?php if($i==0){ ?>

                        <?php
                                }
                                else{
                                ?>

                        <?php
                                }
                                ?>

                        <a title="<?php echo $i ?>"  rel="zoom1" rev="<?php echo $image['popup'];?>">
                            <img width="59" height="59" alt="" src="<?php echo $image['thumb'];?>">
                        </a>


                    <?php
                                $i++;
                                }
                     ?>

                </div>
                <?php if(count($images)>5) { ?>
                <img src="<?php echo STATIC_SERVER; ?>css/images/product/gobottom.gif" id="gobottom"   />
                <?php } ?>
            </div>
            <div class="t2">
                <a  id="zoom1" class="MagicZoom MagicThumb" href="<?php echo $popup;?>"><img src="<?php echo $popup;?>"  title="<?php echo $heading_title;?>" id="main_img" class="main_img" style="width:400px; height:400px;" /></a>
                <div class="p-relative">
                    <?php if($discount_rate){ ?>
                    <p class="offIcon"><span class="font20"><?php echo $discount_rate;?></span></p>
                    <?php }else if($is_product_hot_label) { ?>
                    <p class="offIcon hotIcon"></p>
                    <?php } ?>
                </div>


                <div class="social-share">
                    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                        <a class="addthis_button_facebook at300b" title="Facebook" href="javascript:void(0)"><span class=" at300bs at15nc at15t_facebook"><span class="at_a11y">Share on facebook</span></span></a>
                        <a class="addthis_button_twitter at300b" title="Tweet" href="javascript:void(0)"><span class=" at300bs at15nc at15t_twitter"><span class="at_a11y">Share on twitter</span></span></a>

                        <a class="addthis_button_pinterest_share at300b" target="_blank" title="Pinterest" href="javascript:void(0)"><span class=" at300bs at15nc at15t_pinterest_share"><span class="at_a11y">Share on pinterest_share</span></span></a>

                        <a class="addthis_counter addthis_bubble_style" href="javascript:void(0)" style="display: inline-block;"></a>
                        <div class="atclear"></div></div>

                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56dfe575c8f7c161"></script>


                    <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
                </div>

           </div>



        </div>

    </div>

</div>


<script src="<?php echo STATIC_SERVER; ?>js/mzp-packed/lrtk.js"></script>
		<!-- Product image end-->
		<!-- Product info start -->
		<div class="product-info">
			<h1 class="product-name" itemprop="name"><?php echo $heading_title;?></h1>
			<?php if($action_desc_info){ ?>
			<p><a href="<?php echo $action_desc_info['link'];?>" title="<?php echo $action_desc_info['text'];?>" class="bule_text"><?php echo $action_desc_info['text'];?></a></p>
			<?php } ?>
			<div class="product_sku"><span class="gray left" itemprop="identifier">SKU:<?php echo $sku;?></span>
			
			<meta itemprop="category" content="<?php echo $top_catalog['name'].'>'.$level_2_catalog['name'];?>"/>
			<span class="gray" itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
			<meta itemprop="rating" content="<?php echo $rating;?>"/>
			<span class="star star-s<?php echo $rating;?>"></span>(<span itemprop="count"><?php echo $reviews;?></span>)</span><a href="<?php echo $review_write;?>" class='write-review'><?php echo $text_add_reviews;?></a>


			</div>


			<div class="newproduct-info" itemprop="offerDetails" itemscope itemtype="http://data-vocabulary.org/Offer">
				<meta itemprop="currency" content="<?php echo $currency_code;?>"/>
				<meta itemprop="condition" content="new"/>
				<?php if($format_special){ ?>
				<meta itemprop="price" content="<?php echo $currency_special;?>"/>
				<?php }else{ ?>
				<meta itemprop="price" content="<?php echo $currency_price;?>"/>
				<?php } ?>
				
				<meta itemprop="availability" content="<?php echo $stock;?>"/>
				
                <div class="boerderline mt_20">

                    <?php if(isset($exclusive_price_info)){ ?><div><?php echo $text_exclusive_price;?></div><?php } ?>
                        <div class="currency">


                                <div class="select_box">
                                    <span   class="s_tap"><?php echo $currency_code;?> <i></i></span>
                                    <ul class="s_list">

                                        <?php foreach($currencies as $curreny){ ?>
                                        <li  onclick="submitCurrancy('<?php echo $curreny['code'];?>')"><?php echo $curreny['code'];?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <form id="currency_change_form_pro" action="/index.php?route=module/currency"
                                      method="post" enctype="multipart/form-data">

                                    <input type="hidden" name="currency_code" value="" id='pro_currency_code'>
                                    <input type="hidden" name="redirect" value="<?php echo $redirect;?>">
                                </form>
                            </span>


                            <?php if($format_special){ ?>

                            <span class="price">

                                <b id="price"><?php echo $format_special;?> </b>

                            </span>

                            <?php
                            }else{
					        ?>

                            <span class="price"><b id="price"><?php echo $format_price;?> </b> </span>

                            <?php } ?>

                        </div>



                    <?php if($format_special){ ?>
                            <div class="price-container">
                                <span class="list-price"><?php echo $currency_code;?><?php echo $format_price;?></span>
                                <span class="save-price">
								<?php if($this->session->data['language']=='DE'){?>
								 <?php echo $saved;?> <?php echo $text_save;?>
								<?php }else{ ?>
								<?php echo $text_save;?> <?php echo $saved;?> 
								<?php } ?>
								</span>
                            </div>
                        <?php	}	?>

                    <?php if($format_special){ ?>
                    <div class="countTime_time"><p class="countTime_t red"><?php echo $limited_time;?></p>

                        <div id="counter"></div>
                        <div class="time_desc">
                            <div><?php echo $text_day;?></div>
                            <div><?php echo $text_hour;?></div>
                            <div><?php echo $text_min;?></div>
                            <div><?php echo $text_sec;?></div>
                        </div>
                    </div>
                    <?php	}	?>
                </div>

                <div class="boerderline linenone">
                  <?php if(($product_info['quantity'])> 0 && $product_info['stock_status_id']==7){ ?>
                    <p><?php echo $stock;?></p>

                    <?php if($warn_product_stock){?>
                        <p class="green"><?php echo $current_stock;?> <span><?php echo $product_info['quantity']; ?></span></p>
                    <?php } ?>
                    <?php } else { ?>
                     <p class="red"><?php echo $out_of_stock;?></p>
                    <?php } ?>

        


                </div>


                <div class="product-operation">
                    <!--弹出窗口 start-->
                    <div class="cart-pop add-cart"  style="display: none;">
                        <a class="del"></a>
                        <div class="text-c">
                            <h4><img src="<?php echo STATIC_SERVER; ?>css/images/public/yes.gif" width="45" height="40"><span id='add_qty_number'></span> <?php echo $text_product_added;?></h4>
                           
                            <div class="mt_10 clearfix">
                                <a   onclick="window.location.href='index.php?route=checkout/cart'" class="btn-primary  mb_10" style="margin-right: 20px;"><?php echo $text_view_cart;?></a>
                                <a  id="continue_shopping" class="btn-default  mb_10 "><?php echo $text_continue_shopping;?></a>
                            </div>
                        </div>
                    </div>

                    <!--弹出窗口 end-->
                <?php if($attr_filter){ ?>

				<div  class="boerderline">
				<?php foreach($attr_filter as $attr){ ?>
					<div class="change-box" attr_id="<?php echo $attr['attr_id'];?>">
						<span class="span-label" <?php if (strlen($attr['attr_name']) < 14) { echo "style='line-height:24px;'"; } ?> ><?php echo $attr['attr_name'];?>:</span>
                        <div class="right-info-block">
						<?php foreach($attr['attr_option_info'] as $option_info){ ?>
						 <?php if(isset($product_attr_filter[$attr['attr_id']]['option_id'])&&$product_attr_filter[$attr['attr_id']]['option_id']==$option_info['value_id']){ ?>		
						<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr pro_attr_selected'><i></i><?php echo $option_info['option_value'];?></span>
						<?php }else{ ?>
							<?php if($option_info['able']){ ?>
							<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr'><a href="<?php echo $option_info['href'];?>"><?php echo $option_info['option_value'];?></a></span>
							<?php }else{ ?>
								<span option_id='<?php echo $option_info['value_id'];?>' class='pro_select_attr disabled'><a href="<?php echo $option_info['href'];?>"><?php echo $option_info['option_value'];?></a></span>
                        <?php } ?>
						<?php } ?>
						<?php } ?>
                        </div>
                    </div>
				<?php } ?>
				</div>
                <?php } ?>


					<form name="add_cart" action="" method="post" autocomplete="off" class="inlineblock" style="width:100%;">
						<div class="product-quantity infor_pro" >
                                <div class="product-alert min-alert-bg"  style="display:none" id="product-tips"></div>
                            
                            <?php if($is_battery) { ?>
                                <div class="countinput clearfix mt_10" >
                                    <div class="clear"></div>
                                    <label class="title">Ship To Country:</label>
                                    <select name="ship_to_country" id="ship_to" class="left list-select" >
                                        <option value="" >Please select</option>
                                        <?php foreach($countries as $country){ ?>
                                            <?php if($ship_to_country_code==$country['iso_code_2']){ ?>
                                                <option value="<?php echo $country['iso_code_2'];?>" selected="selected"><?php echo $country['name'];?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $country['iso_code_2'];?>"><?php echo $country['name'];?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                                <div class="countinput clearfix mt_10" >

                                    <div class="clear"></div>
                                    <label class="title"><?php echo $text_quantity;?></label>

                                    <input id="quantity-dec" type="button"  class="btndown edtbtn"  value="-"/>
                                    <input name="quantity" class="mid-input" onKeyUp="this.value=this.value.replace(/\D/g,'')" type="text" maxlength="3" value="1" id='pro_qty'>
                                    <input id="quantity-inc"  type="button"  class="btnup edtbtn"  value="+"/>

									<!--
                                    <span class="subtotal" style="float: left">
                                        <?php echo $text_subtotal;?>
                                        <span class="red bold font13" id='subtoal'>
                                        <?php if($format_special){
                                            echo $format_special;
                                        }
                                        else{
                                            echo $format_price;
                                        }
                                        ?>
                                        </span>
                                    </span>
                                    -->
                                </div>

						</div>

                                              <?php if($spring_arrival){ ?>
                                                <div class="spring-arrival"><?php echo $spring_arrival; ?></div>
                                              <?php } ?> 
                                            
                                <div class="probtn p-relative">
                                    <?php if(($product_info['quantity'])> 0 && $product_info['stock_status_id']==7){ ?>
                                                                <a class="add-to-cart common-btn-orange aprobtn" href="javascript:addToCart('<?php echo $product_id;?>');" onclick="GaAddToCart('<?php echo $product_id;?>')"  ><i></i><?php echo $text_add_to_cart;?></a>
                                    <?php }else{ ?>
                                    <a class="add-to-cart common-btn-orange aprobtn" href="javascript:void(0);" style=" background-image:none; border:none;background-color:#333333"><i></i><?php echo $text_add_to_cart;?></a>
                                    <?php } ?>
                                    <a class="common-btn-gray add-to-wish" href="javascript:addToWishList('<?php echo $product_id;?>');" onclick="ga('send', 'event', 'Wishlist', '<?php echo $heading_title;?>', '<?php echo $sku;?>')">
                                    <?php if($is_wishlist){ ?>
                                    <i class="active"></i>
                                    <?php }else{ ?>
                                    <i></i>
                                    <?php } ?>

                                    </a>




                                    <div class="cart-pop wish-list"  style="display: none">
                                        <a class="del"></a>
                                        <div>
                                            <h4></h4>
                                        </div>
                                    </div>
                                </div>
						<input id="sub_do" type="hidden" value="0">
						<input name="product_id" type="hidden" value="<?php echo $product_id;?>" id='product_id'>

					</form>

				</div>


                                
				<?php if($discounts){
				?>
				<?php if(!$special_price||($special_price&& $special_price > $discount_low_price)){ ?>
				<div class="product-more">
					<p class="bold title"><?php echo $text_buy_more;?></p>
					<table>
						<tr>
							<th><?php echo $text_quantity;?></th>
							<?php foreach($discounts['qty'] as $qty){
							?>
							<th><?php echo $qty['quantity'];?></th>
							<?php
							}
							?>
							<th>100 +</th>
						<tr>
						<tr>
							<td><?php echo $text_price;?></td>
							<?php foreach($discounts['price'] as $price){
							?>
							<td><?php echo $price['price'];?></td>
							<?php
							}
							?>
							<td class="green"><a href="Mailto:<?php echo CS_EMAIL; ?>" class="chat-alert"><?php echo $text_contact_customer_service;?></a></td>
						</tr>
					</table>
				</div>
				<?php } ?>
				<?php
				}
				?>

			</div>
		</div>
		<!-- Product info end -->
	</div>
	<div class="wrap clearfix" style="margin-top:10px">
		<div class="col-extra">
			<section class="leftpro border">
			<div class="leftprotit bold"><?php echo $text_customer_buy;?></div>
			<ul>
				<div id="personal-also-bought" ></div>
				<?php foreach($customers_also_bought as $bought){
				?>
				<li><div class="img"><a href="<?php echo $bought['href'];?>"><img src="<?php echo $bought['image'];?>" alt="<?php echo $bought['name'];?>"/></a></div>
					<div class="t"><a href="<?php echo $bought['href'];?>"><?php echo $bought['name'];?></a></div>
					<?php if($bought['special']){ ?>
					<div class="howmuch"><span class="xj"><?php echo $bought['special'];?> </span><span class="yj"><?php echo $bought['price'];?></span></div>
					<?php }else{ ?>
					<div class="howmuch"><span class="xj"><?php echo $bought['price'];?></span></div>
					<?php } ?>

				</li>
				<?php
				}
				?>
				
			</ul>
		</section>

		  
      
		</div>
		<div class="col-main">
			<div class="product-detail">

				<div class="detail-container">
					<ul class="tabs-list" id="tabs-list">
						<li class="Description active"><a href="javascript:document.getElementById('Description').scrollIntoView()"><?php echo $tab_description;?></a></li>
						<li class="Description"><a href="javascript:document.getElementById('brochures').scrollIntoView()"><?php echo $text_info_guides;?></a></li>
						<li class="FAQs"><a href="javascript:document.getElementById('FAQs').scrollIntoView()"><?php echo $tab_faqs;?></a></li>
						<li class="Reviews"><a href="javascript:document.getElementById('Reviews').scrollIntoView()"><?php echo $tab_review;?></a></li>
						<!--<li class="Infomation"><a href="javascript:document.getElementById('Infomation').scrollIntoView()"><?php echo $tab_shipping_infomation;?></a></li>-->
					</ul>
					<div class="tabs-content">
						<section id="Description">
						 <div class='pro_desc_block'><?php echo $text_specifications;?> <a href="javascript:void(0)" class=" tabs-tit"></a></div>
							<ul class="ul-table-list">
                                                                <?php $_count = 1; ?>
								<?php foreach($attribute_groups as $attributes){?>
                                                                    
								<li><span class="span-info-1"><?php echo $attributes['name'];?>:</span><?php  $_k = strtolower($attributes['text']); if(!isset($custom_tag[$_k]) || $_count>5){ ?><?php echo $attributes['text'];?><?php } else { ?><a target="_blank" href="<?php echo $custom_tag[$_k]['link']; ?>"><?php echo $attributes['text'];?></a><?php  unset($custom_tag[$_k]);$_count++; } ?></li>
								<?php } ?>
							</ul>
							<div class="top-line" itemprop="description"><?php echo $description;?></div>
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
                                                        <?php if($is_show_led_advantage) { ?>
                                                            <div class="top-line"><img src="/image/led_advantage_<?php echo $lang_code; ?>.jpg" /></div>
                                                        <?php } ?>
                            <div class="top-line"><?php echo $text_custom_tax;?></div>

						</section>
						<section id="brochures">
							<ul class="tabs-list">
								<li class="FAQs active"><a href="javascript:void(0)" class=" tabs-tit"><?php echo $text_info_guides;?></a></li>
							</ul>
							<ul class="FAQs-box guides-info" >
							<?php if($all_brochures){ ?>
							<?php foreach($all_brochures as $brochures){ ?>
								<li>
                                <div><a href="<?php echo $brochures['href'];?>" target="_blank" class="title"><img src="<?php echo STATIC_SERVER; ?>css/images/public/guides.jpg"  style="vertical-align: top" /> <?php echo $text_spectrum;?></a></div>
                                <div>
                                    <a href="<?php echo $brochures['href'];?>" target="_blank" ><img src="<?php  echo STATIC_SERVER; ?>css/images/public/check.jpg"  style="margin-left: 25px;border:1px solid #ccc" /></a>
                                </div>
								</li>
							<?php } ?>
							<?php }else{ ?>
                               <?php echo $text_no_documents;?>
                             <?php } ?>
							</ul>
						</section>
						<section id="FAQs">
							<ul class="tabs-list">
								<li class="FAQs active"><a href="javascript:void(0)" class=" tabs-tit"><?php echo $tab_faqs;?></a></li>
							</ul>
							<div class="FAQs-box">
							<?php if($faq_info){
								 foreach($faq_info as $faq){
							?>
							<p class="q">Q: <?php echo $faq['faq_text'];?></p>
							<?php if($faq['reply_text']){ ?>
								<p>A: <?php echo $faq['reply_text'];?></p>
							<?php } ?>

							<?php
							}
							?>
							<p><!--<a href="#" class="blue underline"><?php echo $text_more_questions;?></a>-->
								<a rel="nofollow" class="button faq_write" href="<?php echo $write_new_faq;?>" style="float:right"><?php echo $text_ask_questions;?></a>
							</p>
							<?php
							} else{
							?>
							<p><?php echo $text_empty_questions;?><a rel="nofollow" class="button faq_write" href="<?php echo $write_new_faq;?>" style=" margin-left:30px;"><?php echo $text_ask_questions;?></a></p>
							<?php } ?>



							</div>
						</section>
						<section id="Reviews">
							<ul class="tabs-list">
								<li class="Reviews active"><a href="javascript:void(0)" class="tabs-tit"><?php echo $tab_review;?></a></li>
							</ul>
							<div class="rating-container border clearfix">
                                                            <div class="rating-level"><span class="rating"><b><?php echo $reviews;?></b></span><a href="<?php echo $reviews_list_link;?>">
								<span class="star">
									<b class="stars">
										<span class="lv0"></span>
									</b>
								</span>
								<span class="total-reviews" style="text-align: left;margin-left: 12px">(<?php echo $text_reviews;?>)</span></a></div>
								<ul id="rating-list">
									<?php foreach($reviews_rating_info as $reviews_rating){ ?>
									<li data-percentage="<?php echo $reviews_rating['rating_percent'];?>">
									<strong><?php echo $reviews_rating['rating'];?> star </strong>
									<b class="obrate">
									<?php if($reviews_rating['rating_percent']){ ?>
										<i style="width:<?php echo $reviews_rating['rating_percent'];?>%"> </i>
									 <?php }else{ ?>
										<i> </i>
									<?php } ?>
									</b>
									<span class="num"><?php echo $reviews_rating['rating_total'];?>(<em class="percent"><?php echo $reviews_rating['rating_percent'];?></em>%)</span>
									</li>
									<?php } ?>
								</ul>
								<div class="evaluate">
                                    <a class="common-btn-orange write-review"  href="<?php echo $review_write;?>" >
                                        <?php echo $text_write;?>
                                    </a>
                                    <span><?php echo $text_can_write;?></span>
                                </div>
							</div>
						</section>

						<section class="an_content_review border" id='comment_list'>
							<?php include_once(DIR_TEMPLATE.'/default/template/product/review.tpl');?>
						</section>
					</div>
					<div>

						<section class="flexslider Historypro border">
                            <div class="picScroll-left">
                                    <div class="hd protit">
                                        <p class="black18"><?php echo $text_recent_history;?></p>
                                    </div>
                                <div class="bd">
                                    <ul >
                                        <?php foreach($history as $history_pro){
                                        ?>
                                        <li class="tempWrap"><div class="img"><a href="<?php echo $history_pro['href'];?>"><img src="<?php echo $history_pro['image'];?>" alt="<?php echo $history_pro['name'];?>"></a></div>
                                                <div class="t"><a href="<?php echo $history_pro['href'];?>"><?php echo $history_pro['name'];?></a></div>
                                                <?php if($history_pro['format_special']){ ?>
                                                <div class="howmuch"><span class="xj"><?php echo $history_pro['format_special'];?></span><span class="yj"><?php echo $history_pro['format_price'];?></span></div>
                                                <?php }
                                                else{ ?>
                                                <div class="howmuch"><span class="xj"><?php echo $history_pro['format_price'];?></span></div>
                                                <?php } ?>

                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
						</section>
					</div>

				</div>
			</div>
		</div>
		<div class="clear"></div>
		<?php foreach($pro_list_foot_banner as $pro_banner){ ?>
			<div class="probot"><a href="<?php echo $pro_banner['link'];?>" title="<?php echo $pro_banner['title'];?>"><img src="<?php echo $pro_banner['image'];?>" alt="<?php echo $pro_banner['title'];?>"/></a></div>
		<?php } ?>

	</div>
</section>
<!-- Product end -->
</div>

<div class="fix-layout">
	<div class="gb-operation-area" id="_returnTop_layout_inner">
		<a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="Top" class="gb-operation-icon"></i>
		<span class="gb-operation-text">Top</span>
		</a>

	</div>

</div>
<div class="clear"></div>
<script type="text/javascript">

    <?php if(isset($left_time_js)){
        ?>
        $(document).ready(function() {
            $('#counter').countdown({
                image: '/css/images/digits.png',
                startTime: '<?php echo $left_time_js;?>'
            });
        });
    <?php
    }
    ?>

</script>
<script type="text/javascript">

    function  submitCurrancy(code){
        $('#pro_currency_code').attr('value',code);
        $('#currency_change_form_pro').submit();

    }

//购物车弹出层
  function show_pop(price,add_qty,total){
      $(".add-cart").show();
	  $('#add_qty_number').html(add_qty);
      $("span[rel=cart-price-total]").text(price);
      $("b[rel=cart-total]").text(total);
  }
  function wish_pop(error,message){

	  if(error=='0'){
	  	$('.add-to-wish>i').addClass('active');
	  }
     	  if(error=='2'){
	  	$('.add-to-wish>i').removeClass('active');
      	  }


  }
  $(".del").click(function(){
        $(".cart-pop").hide();
  })
  $("#continue_shopping").click(function(){
       $(".cart-pop").hide();
  });

// 币种类型
    $("#currency-symbol").mouseover(function(){
        $(".price-list").show();
    })
    $("#currency-symbol").mouseout(function(){
        $(".price-list").hide();
    })
    $(".price-list").mouseout(function(){
        $(this).hide();
    })
//分类下拉
$(".xia_sj").parents("li").mouseover(function(){
        $(".xiasj-list").show();
})
$(".xia_sj").parents("li").mouseout(function(){
    $(".xiasj-list").hide();
})
$(".xiasj-list").mouseout(function(){
    $(this).hide();
})
//写评论,faq
$('.write-review,.faq_write').click(function(){
	var redirect=$(this).attr('href');
	if(!is_login()){
		$('#login_tc').show();
		$('input[name=\'redirect\']').val(redirect);
		return false;
	}
})

//评论翻页
$('.review_page a').live('click',function(){
	var href =$(this).attr('href');
	if(href){
		var linkRegx = /.*page=(\d+)/;
		var group = href.match(linkRegx);
		var page =group[1];
		$.ajax({
			url: 'index.php?route=product/product/review',
			type: 'get',
			data: 'product_id=<?php echo $product_id;?>&page='+page,
			dataType: 'text',
			success: function(str) {
				var load ="<div style='text-align:center;height:200px;line-height:200px;'><img src='/css/images/loader_32x32.gif'></div>";
				$('.an_content_review').html(load);
				$('.an_content_review').html(str);
				location.hash = 'comment_list';

			}
		});
		return false;
	}
	else{
		return false;
	}

});
//评论支持
$('.review-condition').live('click',function(){
	//是否登录
	if(is_login()==0){
		$('#login_tc').show();
		return false;
	}
	var condition =$(this).attr('condition');
	var review_id = $(this).attr('rel');
	var id=condition+'-'+review_id;
	var  num =parseInt($(this).find('#'+id).html())+1;
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

$('#ship_to').live('change',function(){
	var country_code =$(this).val();

        if(country_code == ''){
            return '';
        }
	$.ajax({
		url: 'index.php?route=product/product/canShip',
		type: 'post',
		data: 'country_code=' + country_code + "&product_id=<?php echo $product_info['product_id'];?>" ,
		dataType: 'json',
		success: function(json) {
			if(json['flag']==1){
                            $('#product-tips').hide();
                            $('.add-to-cart').removeClass("disabled-cart");
                           
			}
			else if(json['flag'] == 0){
                            $('.add-to-cart').addClass("disabled-cart");
                            if(json['msg']){
                                $('#product-tips').html(json['msg']);
                                $('#product-tips').show();
                            }
			}

		}
	});
})


</script>



<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74239275-1', 'auto');
  ga('require', 'displayfeatures');
  <?php  if($this->session->data['customer_id']){ ?>
  ga('set', '&uid', "<?php echo $this->session->data['customer_id'];?>"); 
  <?php  }  ?>
ga('require', 'ec');

ga('ec:addProduct', {
  'id': "<?php echo $product_info['product_id'];?>",
  'name': "<?php echo $product_info['name'];?>",
  'category': "<?php echo $top_catalog['en_name'];?>",
  'brand': "<?php echo $top_catalog['en_name'];?>",
  'variant': "<?php echo $top_catalog['en_name'];?>"
});

ga('ec:setAction', 'detail');
ga('send', 'pageview');       // Send product details view with the initial pageview.

// Called when a product is added to a shopping cart.
function GaAddToCart(pid) {
  var qty =$('#pro_qty').val();
  ga('ec:addProduct', {
    'id': pid,
    'name': "<?php echo $product_info['name'];?>",
    'category':  "<?php echo $top_catalog['en_name'];?>",
    'brand': "<?php echo $top_catalog['en_name'];?>",
    'variant':"<?php echo $top_catalog['en_name'];?>",
    'price': "<?php echo $special_price?$special_price:$product_info['price'];?>",
    'quantity': qty
  });
  ga('ec:setAction', 'add');
  ga('send', 'event', 'product', 'click', '<?php echo $product_info['model'];?>',{'nonInteraction': 1});     // Send data using an event.
}
</script>



<?php echo $footer; ?>