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
            <?php if($breadcrumb['child']){
		?>

                <p><span class="xia_sj"></span></p>
                <div class="showdata-list" style="display: none">
                    <ul>
                        <?php foreach($breadcrumb['child'] as $child){ ?>
                        <li>
                           <a href="<?php echo $child['href'];?>"
                               title="<?php echo $child['name'];?>"><?php echo $child['name'];?></a></li>
                        <?php } ?>
                    </ul>
                </div>

            <?php
		}
		?>
            <?php echo $breadcrumb['separator']; ?>
        </li>
	<?php
	}
	?>

	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class="box wrap clearfix" >

	<section class=" category-right">
		<div>
		<?php foreach($top_banner_info as $top_banner){ ?>
		<?php if($top_banner['link']){ ?>
		<a href="<?php echo $top_banner['link'];?>" title="<?php echo $top_banner['title'];?>"><img src="<?php echo $top_banner['image'];?>" width='960' height='120' alt="<?php echo $top_banner['title'];?>"></a>
		<?php }else{ ?>
		<img src="<?php echo $top_banner['image'];?>" width='960' height='120' alt="<?php echo $top_banner['title'];?>">
		<?php } ?>
		<?php } ?>
		</div>
		<div class="protit" ><h1><?php echo $categories['self']['name'];?></h1></div>
		<?php if($categories['self']['seo_img']){ ?>
			<figure class="probanner"><img src="<?php echo $categories['self']['seo_img'];?>" alt="<?php echo $categories['self']['name'];?>"/></figure>
		<?php } ?>

                <div class="c_change_box">
                <?php if($select_option) { ?>
                     <a href="<?php echo $no_filter_url;?>" class="attr_clear_all">Clear All</a>
                 <?php }  ?>
                    <section id="show-check">


                        <?php foreach($select_option as $selected){
                        ?>
                        <a href="<?php echo $selected['href'];?>"  title="<?php echo $selected['option_value'];?>" rel="<?php echo $selected['option_id'];?>" class="selected_attr"><?php echo $selected['option_value'];?></a>
                        <?php
                        }
                        ?>


                    </section>
                </div>   
		<section>


			<?php if(isset($categories['child']) && $categories['child']){ ?>
			<div class="proLabel ">
				<dl>
					<dt>sub categories: </dt>
					<dd>
						<div class="icheck">
							<ul id="check-ul" class="check-ul ">
								<?php foreach($categories['child'] as $child){ ?>
								<li>


										<a  class="check-category" href="<?php echo $child['href'];?>"><?php echo $child['name'];?></a>


								</li>
								<?php } ?>

							</ul>
						</div>
					</dd>
				</dl>
			</div>
			<?php } ?>


			<?php if($AttrbuteGroup){
			?>
			<?php foreach($AttrbuteGroup as  $attr_key=> $attr_groups){
			?>
			<div class="proLabel <?php if($attr_key >3){ echo 'groups-li-none'; }   ?>">
				<dl>
					<dt><?php echo $attr_groups['name'];?>: </dt>
					<dd>
						<div class="icheck">
						  <ul id="check-ul" class="check-ul">
						  <?php foreach($attr_groups['option'] as $key=> $option){
						  ?>
						  <?php if($key>5){ ?>
						  <li class="check-li-none" >
						  <?php }else{ ?>
						   <li>
						  <?php } ?>
                                                  <label for="input-<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>" title="<?php echo $option['option_value'];?>">
                                   <input type="checkbox" id="input-<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>"  onchange="input_check('<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>','<?php echo $option['href'];?>','<?php echo $option['selected_href']; ?>')">
                                     <span id="span_<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>"  rel="<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>" onclick="input_check('<?php echo $attr_groups['attribute_id'].'-'.$option['option_id']; ?>','<?php echo $option['href'];?>')"><?php echo $option['option_value'];?></span>
                                 </label>

							</li>
						  <?php
						  }
						  ?>
						  </ul>
						  <?php if(count($attr_groups['option'])>6){ ?>
						  <a class="more-btn"><span class="moreinfo"><?php echo $text_more;?></span><i class="m-down-img"></i></a>
						  <?php
						  }
                          ?>
						</div>
					</dd>
				</dl>
			</div>
			<?php
			}
			?>

                        <?php if($price_range_list) { ?>
                        
                        <div class="proLabel <?php if(count($AttrbuteGroup)>4){ echo 'groups-li-none'; }   ?>">
				<dl>
					<dt>Price: </dt>
					<dd>
						<div class="icheck">
						  <ul id="check-ul" class="check-ul">
						  <?php foreach($price_range_list as $key=> $option){
						  ?>
						  <?php if($key>5){ ?>
						  <li class="check-li-none" >
						  <?php }else{ ?>
						   <li>
						  <?php } ?>
                                 <label for="input-<?php echo $option['option_id'];?>" title="<?php echo $option['option_value'];?>">
                                   <input type="checkbox" id="input-<?php echo $option['option_id'];?>"  onchange="input_check('<?php echo $option['option_id'];?>','<?php echo $option['href'];?>','<?php echo isset($selected['href'])?$selected['href']:'';?>')">
                                     <span id="span_<?php echo $option['option_id'];?>"  rel="<?php echo $option['option_id'];?>" onclick="input_check('<?php echo $option['option_id'];?>','<?php echo $option['href'];?>')"><?php echo $option['option_value'];?></span>
                                 </label>

							</li>
						  <?php
						  }
						  ?>
						  </ul>
						  <?php if(count($price_range_list)>6){ ?>
						  <a class="more-btn"><span class="moreinfo"><?php echo $text_more;?></span><i class="m-down-img"></i></a>
						  <?php
						  }
                          ?>
						</div>
					</dd>
				</dl>
			</div>
                        
                        <?php } ?>
                        
                        
                        
                        
                        
			<?php if(count($AttrbuteGroup)>4){ ?>
			<div class="ViewAll" ><span class="btn"><span class="down-img"></span><b><?php echo $text_view_all;?></b></span></div>
			<?php } ?>
			<?php
			}
			?>
		</section>
		<section class="pro_sort clearfix" >
			<?php foreach($sorts as $sort_list){
				if($sort==$sort_list['code']&&$order=='ASC'){ ?>
				<div class="sort_child active"><a href="<?php echo $sort_list['href'];?>" ><?php echo $sort_list['text'];?><span class="c_a up"></span></a></div>
				<?php } elseif($sort==$sort_list['code']&&$order=='DESC'){ ?>
					<div class="sort_child active"><a href="<?php echo $sort_list['href'];?>" ><?php echo $sort_list['text'];?><span class="c_a down"></span></a></div>
				<?php }else{ ?>
				<div class="sort_child"><a href="<?php echo $sort_list['href'];?>"><?php echo $sort_list['text'];?><span class="c_a"></span></a></div>
				<?php } ?>
		<?php } ?>

			<div class="sort_child right">
				<?php if((isset($_COOKIE['view_list'])&&$_COOKIE['view_list'] == 'list')){ ?>
				<a href="javascript:display('list');" class="on" id='list_show'><span class="c_a a3   icon-showList"></span><?php //echo $text_list; ?></a>
				<?php }else{ ?>
				<a href="javascript:display('list');"  id='list_show'><span class="c_a a3  icon-showList"></span><?php //echo $text_list; ?></a>
				<?php } ?>
			</div>

            <div class="sort_child right">
                <?php if(!isset($_COOKIE['view_list'])|| isset($_COOKIE['view_list'])&&$_COOKIE['view_list'] == 'grid'){ ?>
                <a href="javascript:display('grid');" class="on " id='grid_show'><span class="c_a a4   icon-showBlock"></span><?php //echo $text_grid; ?></a>
                <?php }else{ ?>
                <a href="javascript:display('grid');"  class="icon-showBlock"  id='grid_show'><span class="c_a a4  icon-showBlock"></span><?php //echo $text_grid; ?></a>
                <?php } ?>
            </div>


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
		<?php $view_method =isset($_COOKIE['view_list'])?$_COOKIE['view_list']:'grid';?>
		<section class="prolist prolist_info" id='display_grid' <?php if($view_method!='grid'){ ?> style='display:none' <?php } ?> >
			<ul class="clearfix">
				<?php foreach($products as $position=>$pro){
				$cat_info = $this->model_catalog_product->getCategoryInfo($pro['product_id']);
				$cat_info['name'] =$this->model_catalog_category->get_category_en_name($cat_info['category_id']);
				?>
				<li>

                 

					<div class="procon ">
                           <div class="p-relative">
                        <?php if($pro['discount_rate']){ ?>
                        	<p class="offIcon"><span class="font20"><?php echo $pro['discount_rate'];?></span></p>
                        <?php }elseif($pro['is_new']){ ?>
                        	<p class="offIcon newIcon"></p>
                        <?php }else if($pro['is_product_hot_label']){ ?>
							<p class="offIcon hotIcon"></p>
						<?php } ?>
                    </div>

                        <div  id="animation_<?php echo $pro['product_id'];?>"></div>
                        <div class="img"><a href="<?php echo $pro['href'];?>" onclick="onProductClick('<?php echo $pro['product_id'];?>','<?php echo $pro['name'];?>','<?php echo $cat_info['name'];?>','<?php echo $position+1;?>');"><img src="<?php echo $pro['thumb'];?>"
                                                                                   width="207" height="160"
                                                                                   alt="<?php echo $pro['name'];?>"
                                                                                   id="animation_img_<?php echo $pro['product_id'];?>"
                                        /></a>



                        </div>
                        <p class="tt"><a href="<?php echo $pro['href'];?>"  onclick="onProductClick('<?php echo $pro['product_id'];?>','<?php echo $pro['name'];?>','<?php echo $cat_info['name'];?>','<?php echo $position+1;?>');"><?php echo $pro['name'];?></a></p>
                        <?php if($pro['special']){
						?>
                        <p class="howmuch"><span class="xj"><?php echo $pro['special'];?></span><span
                                    class="yj"><?php echo $pro['price'];?></span></p>

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
                        </p>
                        <p class="gray"><span
                                    class="star star-s<?php echo $pro['rating'];?>"></span>(<?php echo $pro['reviews'];?>)</p>

					</div>

				</li>
				<?php
				}
				?>
			</ul>
		</section>

		<section class="prolist prolist_type1" id='display_list' <?php if($view_method!='list'){ ?> style='display:none' <?php } ?> >
			<?php foreach($products as $position=>$pro){
			$cat_info = $this->model_catalog_product->getCategoryInfo($pro['product_id']);
			$cat_info['name'] =$this->model_catalog_category->get_category_en_name($cat_info['category_id']);
			?>
			<dl>
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
                    <p style="height: 24px;line-height: 24px;">
                    <?php if($pro['as_low_as_price']){
						?>
                    <p ><?php echo $text_as_low;?> <?php echo $pro['as_low_as_price'];?></p>
                    <?php
						}
						?>
                    </p>
                    <p class="redbtn">
                        <a href="javascript:addToCart('<?php echo $pro['product_id'];?>');" onclick="ga('send', 'event', 'add to cart', '<?php echo $pro['name'];?>', '<?php echo $pro['model'];?>')"><?php echo $button_cart; ?></a></p>

                </div>
				<dt >
					<?php if($pro['discount_rate']){
					?>
					<p class="offIcon"><span class="font20"><?php echo $pro['discount_rate'];?></span></p>
					<?php
					}
					?>
				<a href="<?php echo $pro['href'];?>" onclick="onProductClick('<?php echo $pro['product_id'];?>','<?php echo $pro['name'];?>','<?php echo $cat_info['name'];?>','<?php echo $position+1;?>');"><img src="<?php echo $pro['thumb'];?>"  id="animation_img_list_<?php echo $pro['product_id'];?>" alt="<?php echo $pro['name'];?>"/></a>

                </dt>
				<dd >

                    <div  id="animation_list_<?php echo $pro['product_id'];?>"></div>
                    <div class="t bold"><a href="<?php echo $pro['href'];?>" onclick="onProductClick('<?php echo $pro['product_id'];?>','<?php echo $pro['name'];?>','<?php echo $cat_info['name'];?>','<?php echo $position+1;?>');"><?php echo $pro['name'];?></a></div>
					<div class="t gray auto_over"><?php echo $text_description;?><?php echo $pro['description'];?><a href="<?php echo $pro['href'];?>" onclick="onProductClick('<?php echo $pro['product_id'];?>','<?php echo $pro['name'];?>','<?php echo $cat_info['name'];?>','<?php echo $position+1;?>');"><?php echo $text_product_detail;?></a></div>
					
					<div class="gray"><span class="star star-s<?php echo $pro['rating'];?>"></span>(<?php echo $pro['reviews'];?>)</div>


				</dd>
			</dl>
			<?php } ?>
		</section>

	  
			<?php echo $pagination;?>
	  
		<?php if($category_info['description']) {  ?>
			<section class="pro_intro"><?php echo htmlspecialchars_decode($category_info['description']);?></section>
		<?php } ?>
	</section >
</section >
<div class="fix-layout">
	<div class="gb-operation-area" id="_returnTop_layout_inner">
		<a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="Top" class="gb-operation-icon"></i>
		<span class="gb-operation-text">Top</span>
		</a>
	</div>
</div>

<script type="text/javascript">
/* ga 增强性代码*/
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74239275-1', 'auto');
  ga('require', 'displayfeatures');
  <?php  if($this->session->data['customer_id']){ ?>
  ga('set', '&uid', "<?php echo $this->session->data['customer_id'];?>"); 
  <?php  }  ?>
//列表展示
ga('require', 'ec');

<?php foreach($products as $position=>$pro){ 
$cat_info = $this->model_catalog_product->getCategoryInfo($pro['product_id']);
$cat_info['name'] =$this->model_catalog_category->get_category_en_name($cat_info['category_id']);
$category_info['name'] =$this->model_catalog_category->get_category_en_name($category_info['category_id']);
if($cat_info['name']==$category_info['name']){
	$cat_name =$cat_info['name'];
}
else{
	$cat_name =$cat_info['name'].'>'.$category_info['name'];
}
	$cat_name = $category_info['name'];
?>
ga('ec:addImpression', {
  'id': "<?php echo $pro['product_id'];?>",                   // Product details are provided in an impressionFieldObject.
  'name': "<?php echo $pro['model'];?>",
  'category': "<?php echo $cat_name;?>",
  'type': 'view',
  'brand': '',
  'variant': '',
  'list': "Home><?php echo $cat_name.'-'.$page;?>",
  'position': '<?php echo $position+1;?>'             // 'position' indicates the product position in the list.
});
<?php } ?>
//用户点击
function onProductClick(pid,name,catrgory,position) {
  ga('ec:addProduct', {
	'id': pid,
	'name':name,
	'category': catrgory,
	'brand': '',
	'variant': '',
	'position':position
  });
 ga('ec:setAction', 'click', {list: "Home><?php echo $cat_name.'-'.$page;?>"});
  // Send click with an event, then send user to product page.
  ga('send', 'event', 'category', 'click', '<?php echo $cat_name;?>', {
	  'hitCallback': function() {
	  },'nonInteraction': 1
  });
}
ga('send', 'pageview');       // Send product details view with the initial pageview.
</script>

<script type="text/javascript"><!--
/* 超过5个隐藏 */
    $(document).ready(function(){
		var text_more ='<?php echo $text_more;?>';
		var text_less ='<?php echo $text_less;?>';
		var text_view_all ='<?php echo $text_view_all;?>';
		var text_hide ='<?php echo $text_hide;?>';
        $(".more-btn").click(function(){
            $(this).find(".m-down-img").toggleClass("m-up-img");
            if($(this).text() == ""+text_more+""){
                $(this).find(".moreinfo").text(""+text_less+"");
                $(this).siblings("ul").children().show();
            }else{
                $(this).find(".moreinfo").text(""+text_more+"");
                $(this).siblings("ul").children(".check-li-none").hide();
            }
        })

$(".ViewAll .btn").on("click",function(){
            if($(".down-img").hasClass("up-img")){
                $(this).find("b").html(""+text_view_all+"");
                /* 超过5个隐藏 */
                $(".groups-li-none").hide();
            }else{
                $(this).find("b").html(""+text_hide+"");
                $(".proLabel").show();
            }
            $(".down-img").toggleClass("up-img");
        })
})



$(".xia_sj").parents("li").mouseover(function(){
        $(".showdata-list").show();
})
$(".xia_sj").parents("li").mouseout(function(){
    $(".showdata-list").hide();
})
$(".showdata-list").mouseout(function(){
    $(this).hide();
})

$("#show-check a").each(function(){
    var a_val = $(this);
    $("#check-ul span").each(function(){
        var span_val = $(this);
        if(a_val.attr("rel") == span_val.attr("rel")){
            span_val.siblings("input").attr("checked",true);
        }
    })
})
function display(view) {
	document.cookie="view_list="+view;
	if(view=='grid'){
		$('#display_list').hide();
		$('#display_grid').show();
		$('#list_show').removeClass('on');
		$('#grid_show').addClass('on');
		document.cookie="view_list="+view;
	}
	else{
		$('#display_grid').hide();
		$('#display_list').show();
		$('#grid_show').removeClass('on');
		$('#list_show').addClass('on');
	}
}


function input_check(id,url,durl){
var check  =  true;
 $("#show-check a").each(function(){

        if($(this).attr("rel") == $("#span_"+id).attr("rel")){
            window.location = durl;
            check  =  false;
        }

 })
    if(check){
    $("#input-"+id).attr("checked",true);
        window.location = url;
    }
}
//--></script>

<?php echo $footer; ?>