
              <div class="faq-article appli">
                <ul class="g-nav">
                    <li class="green-title">Categories</li>
                    <?php foreach($all_app_info as $app_info){ ?>
                        <?php if($parent_catagory_info['catagory_id']==$app_info['catagory_id']){ ?>
                        <li class='active'><?php if($app_info['child']){ ?><span class="imgs"></span><?php } ?></span><a href="applications/c/<?php echo $app_info['url_path'];?>.html" class="g-tit "><?php echo  $app_info['catagory_name'];?></a>
                            <div class="appli-link" style='display:block'>
                        <?php }else { ?>
                        <li><?php if($app_info['child']){ ?><span class="imgs"></span><?php } ?></span><a href="applications/c/<?php echo $app_info['url_path'];?>.html" class="g-tit"><?php echo  $app_info['catagory_name'];?></a>
                            <div class="appli-link">
                         <?php } ?>
                                <?php if($app_info['child']){ ?>
                                    <?php foreach($app_info['child'] as $child){ ?>
                                        <?php if($catagory_info['catagory_id']==$child['catagory_id']){ ?>
                                        <span><a href="javascript:void(0)" class="blue"><?php echo $child['catagory_name'];?></a></span>
                                        <?php } else{ ?>
                                        <span><a href="applications/c/<?php echo $child['url_path'];?>.html"><?php echo $child['catagory_name'];?></a></span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="applications-article-info left">
                    <div class="article_info">
					 	<div class="effect_image">
                            <img src="<?php echo $article_info['effect_image'];?>"/>
                        </div>
                        <div class="info">
                            <h1 class="mb_20 text-c"><?php echo $article_info['title'];?></h1>
                           <?php echo $article_info['content'];?>
                        </div>
                       
                    </div>

                </div>
				<div class="app_product"> 
                    <h1 class="title-green" style='font-size:18px;'><span>Products used in this application</span></h1>
                    <ul  class="product-list">
                        <?php foreach($user_products as $product){ ?>
                        <li>
                            <a href="https://www.myled.com/<?php echo $product['url_path'];?>.html">
							<img src="<?php echo $product['image'];?>" width='50' height='50'/>
							<div class="pro_detail">
								<div class="name"><?php echo $product['name'];?></div>
								<div class="price">
									
									<?php if($product['special']){?>
                                    <span style=" font-size:16px;color:red">$<?php echo $product['special'];?></span>
									<span style="text-decoration:line-through; color:red">$<?php echo $product['price'];?></span>
									<?php }else{ ?>
                                    <span style=" font-size:16px;color:red">$<?php echo $product['price'];?></span>
                                    <?php } ?>
									
								</div>
							</div>
                            <div style='clear:both'></div>
							</a>
                        </li>
                        <?php } ?>
						
                    </ul>
				</div>
            </div>
    </div>
<script type="text/javascript">

    $(function(){
        $(".appli .g-tit").click(function(){
                $(this).toggleClass("active");
                $(this).parent("li").find(".appli-link").toggle();
        })
    });

</script>