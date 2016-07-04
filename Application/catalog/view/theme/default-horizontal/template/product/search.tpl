<?php echo $header; ?>
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
<section class="box wrap searchcontent clearfix">
	<aside class="boxLeft left">
		<nav class="leftnav">
			<div class="leftnavtit"><p class="bold"><?php echo $text_category;?></p></div>
			<?php if($category_id){ ?>
			<div class="H bold"><a href="<?php echo $any_category_href;?>"><< <?php echo $text_any_category;?></a></div>
				<?php foreach($res_catalog_pro as $catalog_pro){ ?>
					<div class="H bold" style="padding-left:10px;">
					<?php if($category_id == $catalog_pro['category_id']){ ?>
					<a href="<?php echo $catalog_pro['href'];?>" title="<?php echo $catalog_pro['name'];?>" style="color:#68a500">
					<?php }else{ ?>
					<a href="<?php echo $catalog_pro['href'];?>" title="<?php echo $catalog_pro['name'];?>">
					<?php } ?>
					<?php echo $catalog_pro['name'];?><span class="gray">(<?php echo $catalog_pro['count'];?>)</span></a></div>
					<?php if($catalog_pro['child']){ ?>
					<ul>
						<?php foreach($catalog_pro['child'] as $child_cat){ ?>
						<li>
						<?php if($category_id == $child_cat['category_id']){ ?>
						<a href="<?php echo $child_cat['href'];?>" title="<?php echo $child_cat['name'];?>" style="color:#68a500">
						<?php }else{ ?>
						<a href="<?php echo $child_cat['href'];?>" title="<?php echo $child_cat['name'];?>">
						<?php } ?>
						
						<?php echo $child_cat['name'];?><span class="gray">(<?php echo $child_cat['count'];?>)</span></a></li>
						<?php } ?>
					</ul>
					<?php }	?>
					
				<?php } ?>
			<?php }else{ ?>
				<?php foreach($res_catalog_pro as $catalog_pro){ ?>
					<div class="H bold"><a href="<?php echo $catalog_pro['href'];?>" title="<?php echo $catalog_pro['name'];?>"><?php echo $catalog_pro['name'];?><span class="gray">(<?php echo $catalog_pro['count'];?>)</span></a></div>
					<?php if($catalog_pro['child']){ ?>
						
					
					<ul>
						<?php foreach($catalog_pro['child'] as $child_cat){ ?>
						<li><a href="<?php echo $child_cat['href'];?>" title="<?php echo $child_cat['name'];?>"><?php echo $child_cat['name'];?><span class="gray">(<?php echo $child_cat['count'];?>)</span></a></li>
						<?php } ?>
					</ul>
					<?php }	?>
					
				<?php } ?>
		<?php } ?>
			
		</nav>
		<section class="leftpro border">
			<div class="leftprotit bold"><?php echo $top_seller;?></div>
			<ul>
				<?php foreach($top_selles as $top){
				?>
				<li><div class="img"><a href="<?php echo $top['href'];?>"><img src="<?php echo $top['thumb'];?>" alt="<?php echo $top['name'];?>"/></a></div>
					<div class="t"><a href="<?php echo $top['href'];?>"><?php echo $top['name'];?></a></div>
					<?php if($top['special']){
					?>
					<div class="howmuch"><span class="xj"><?php echo $top['special'];?></span><span class="yj"><?php echo $top['price'];?></span></div>
					<?php
					}
					else{
					?>
					<div class="howmuch"><span class="xj"><?php echo $top['price'];?></span></div>
					<?php
					}
					?>
					
				</li>
				<?php
				}
				?>
			</ul>
		</section>
		
		
		<section class="gg_right">
			<?php foreach($side_banner as $banner){ ?>
			<figure class="gg_img"><a href="<?php echo $banner['link'];?>"><img src="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>"/></a></figure>
			<?php } ?>
		</section>
	</aside>
	<section class="boxRight " >
		<div class="protit"><p class="black18"><?php echo $search;?>(<?php echo $res_count;?>)</p></div>
		<?php if($products){ ?>
		<section class="pro_sort" >
                    <?php if($default_sort) { ?>
                    <div class="sort_child <?php if(!$sort) { ?>active<?php } ?>"><a href="<?php echo $default_sort['href']; ?>" <?php if(!$sort) { ?><?php } ?>><?php echo $default_sort['text']; ?></a></div>
	            <?php } ?>
                        <?php foreach($sorts as $sort_list){
				if($sort==$sort_list['code']&&$order=='asc'){ ?>
				<div class="sort_child active"><a href="<?php echo $sort_list['href'];?>" ><?php echo $sort_list['text'];?><span class="c_a up"></span></a></div>
				<?php } elseif($sort==$sort_list['code']&&$order=='desc'){ ?>
					<div class="sort_child active"><a href="<?php echo $sort_list['href'];?>" ><?php echo $sort_list['text'];?><span class="c_a down"></span></a></div>
				<?php }else{ ?>
				<div class="sort_child"><a href="<?php echo $sort_list['href'];?>"><?php echo $sort_list['text'];?><span class="c_a"></span></a></div>
				<?php } ?>
		<?php } ?>
            <div class="sort_child"><a href="javascript:display('grid');" class="on" id='grid_show'><span class="c_a a4 icon-showBlock"></span><?php //echo $text_grid; ?></a></div>
			<div class="sort_child"><a href="javascript:display('list');"  id='list_show'><span class="c_a a3 icon-showList"></span><?php //echo $text_list; ?></a></div>

			<span class="gray" style="margin-left:10px;"><?php echo $text_limit; ?> <select onchange="location = this.value;">
					<?php foreach ($limits as $limits) { ?>
					<?php if ($limits['value'] == $limit) { ?>
					<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
					<?php } ?>
					<?php } ?>
			  </select></span>
		</section>
		<section class="prolist prolist_info" id='display_grid'>
			<ul class="clearfix">
				<?php foreach($products as $pro){
				?>
				<li>
                    <div class="p-relative">
                        <?php if($pro['discount_rate']){
                        ?>
                        <p class="offIcon"><span class="font20"><?php echo $pro['discount_rate'];?></span></p>
                        <?php
                        }else if($pro['is_product_hot_label']){ ?>
						<p class="offIcon hotIcon"></p>
						<?php } ?>

                    </div>
					<div class="procon">
                        <div id="animation_<?php echo $pro['product_id'];?>"></div>
						<div class="img">

                            <a href="<?php echo $pro['href'];?>" ><img src="<?php echo $pro['thumb'];?>" id="animation_img_<?php echo $pro['product_id'];?>" width="207" height="160" alt="<?php echo $pro['name'];?>"/></a><div class="img_Text">
						<div class="addthis_toolbox addthis_default_style addthis_16x16_style">
							<a class="addthis_button_facebook at300b f" title="Facebook" href="#" ><span><span class="at_a11y"></span></span></a>
                            <a class="addthis_button_pinterest_share at300b n"  title="Tweet" href="#"><span><span class="at_a11y"></span></span></a>
							<a class="addthis_button_twitter at300b tqq" title="Pinterest" href="#" ><span><span class="at_a11y"></span></span></a>

							<div class="atclear"></div>
						</div>
						<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=undefined"></script>
						<script type="text/javascript" src="https://s7.addthis.com/static/r07/core145.js"></script>
                                                <?php if($pro['status'] == 1 && $pro['stock_status_id'] == 7){ ?>
						<a href="javascript:addToCart('<?php echo $pro['product_id'];?>');" class="add_btn" onclick="ga('send', 'event', 'add to cart', '<?php echo $pro['name'];?>', '<?php echo $pro['model'];?>')"><?php echo $button_cart; ?></a>
						<?php } else { ?>
                                                <a href="javascript:void(0);" class="add_btn" ><?php echo $pro['stock_status']; ?></a>
                                                <?php } ?>
                                                </div></div>
                        <p class="tt"><a href="<?php echo $pro['href'];?>" ><?php echo $pro['hightlight'];?></p></a>
						<?php if($pro['special']){
						?>
						<p class="howmuch"><span class="xj"><?php echo $pro['special'];?></span><span class="yj"><?php echo $pro['price'];?></span></p>
						
						<?php
						}
						else{
						?>
						<p class="howmuch"><span class="xj"><?php echo $pro['price'];?></span></p>
						<?php
						}
						?>
						<?php if($pro['as_low_as_price']){
						?>
						<p ><?php echo $text_as_low;?><?php echo $pro['as_low_as_price'];?></p>
						
						<?php
						}
						?>
						
						<p class="gray">
                            <a href="<?php echo $pro['href'];?>" ><span class="star star-s<?php echo $pro['rating'];?>"></span>(<?php echo $pro['reviews'];?>)</a>
                        </p>
					</div>
				</li>
				<?php
				}
				?>
			</ul>
		</section>
		
		<section class="prolist prolist_type1" style='display:none' id='display_list'>
			<?php foreach($products as $pro){
			?>
			<dl>
                <div  id="animation_list_<?php echo $pro['product_id'];?>"></div>
                <div class="typeright">
                    <?php if($pro['special']){
						?>
                    <p class="yj"><?php echo $pro['price'];?></p>
                    <p class="xj"><?php echo $pro['special'];?></p>
                    <?php
						}
						else{
						?>
                    <p class="xj"><?php echo $pro['price'];?></p>
                    <?php
						}
						?>
                    <?php if($pro['as_low_as_price']){
						?>
                    <p class="green"><?php echo $text_as_low;?> <?php echo $pro['as_low_as_price'];?></p>
                    <?php
						}
						?>

                    <p class="redbtn"><a href="javascript:addToCart('<?php echo $pro['product_id'];?>');"><?php echo $button_cart; ?></a></p>
                </div>
				<dt>

					<?php if($pro['discount_rate']){
					?>
					<p class="offIcon"><span class="font20"><?php echo $pro['discount_rate'];?></span></p>
					<?php
					}
					?>
				<a href="<?php echo $pro['href'];?>"><img src="<?php echo $pro['thumb'];?>"  id="animation_img_list_<?php echo $pro['product_id'];?>" alt="<?php echo $pro['name'];?>"/></a></dt>
				<dd>
					<div class="t bold"><a href="<?php echo $pro['href'];?>"><?php echo $pro['name'];?></a></div>
					<div class="t gray"><?php echo $text_description;?><?php echo $pro['description'];?></div>
					<div class="t"><a href="<?php echo $pro['href'];?>"><?php echo $text_product_detail;?></a></div>
					<div class="gray"><span class="star star-s<?php echo $pro['rating'];?>"></span>(<?php echo $pro['reviews'];?>)</div>

				</dd>
			</dl>
			<?php } ?>
		</section>
	  
			<?php echo $pagination;?>
	
	  <?php }else{ ?>
		<section>
            <div class="page404" style='padding:0px'>


                    <ul>
                        <li><span><?php echo $text_no_res;?></span></span></li>
                        <li> <?php echo $text_suggestions;?> </li>
                        <li><?php echo $text_suggestions_01;?> </li>
                        <li><?php echo $text_suggestions_02;?></li>
                        <li><?php echo $text_suggestions_03;?></li>
                    </ul>

            </div>
			<div class="Recent_History">
						<div class="protit"><p class="black18">Top Sellers</p></div>
						<section class="flexslider Historypro border">
							<ul class="slides">
								<?php foreach($good_lists as $good_info){
								?>
								<li style='float:left; width:192px;position:relative'><div class="img"><a href="<?php echo $good_info['href'];?>">
									<?php if($good_info['discount_rate']){ ?>
											<p class="offIcon"><span class="font20"><?php echo $good_info['discount_rate'];?></span></p>
									<?php }elseif($good_info['is_new']){ ?>
										<p class="offIcon newIcon"></p>
									<?php } ?>
									<img src="<?php echo $good_info['image'];?>" alt="<?php echo $good_info['name'];?>"></a></div>
										<div class="t"><a href="<?php echo $good_info['href'];?>"><?php echo $good_info['name'];?></a></div>
										<?php if($good_info['format_special']){ ?>
										<div class="howmuch"><span class="xj"><?php echo $good_info['format_special'];?></span><span class="yj"><?php echo $good_info['format_price'];?></span></div>
										<?php }
										else{ ?>
										<div class="howmuch"><span class="xj"><?php echo $good_info['format_price'];?></span></div>
										<?php } ?>

									</li>
								<?php
								}
								?>
							</ul>
						</section>
					</div>
		</section>
	   <?php } ?>
	</section>	
</section>
<div class="fix-layout">
	<div class="gb-operation-area" id="_returnTop_layout_inner">
		<a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="top" class="gb-operation-icon"></i>
		<span class="gb-operation-text">TOP</span>
		</a>
	</div>
</div>

<script type="text/javascript"><!--
function display(view) {
	if(view=='grid'){
		$('#display_list').hide();
		$('#display_grid').show();
		$('#list_show').removeClass('on');
		$('#grid_show').addClass('on');
	}
	else{
		$('#display_grid').hide();
		$('#display_list').show();
		$('#grid_show').removeClass('on');
		$('#list_show').addClass('on');
	}	
}
//--></script>
<?php echo $footer; ?>