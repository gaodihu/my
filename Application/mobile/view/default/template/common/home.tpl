
<?php echo $header; ?>

    <!-- banner ----------------------------------------------------->
        <section class="banner">
            <div id="slider" >
                <div class="swipe-wrap">
				<?php foreach($home_flash_banner_info as $banner){ ?>
                    <figure>
                        <div class="wrap">
                            <a href="<?php echo $banner['link'];?>" title="<?php echo $banner['title'];?>"><img src="<?php echo $banner['image'];?>"  class="lazyload" data-original="<?php echo $banner['image'];?>" width="750"/></a>
                        </div>
                    </figure>
				<?php }  ?>

                </div>
            </div>
            <nav>
                <div id="position" class="position">
				<?php foreach($home_flash_banner_info as $key=>$banner){ ?>
					<?php if($key==0){ ?>
                    <i class="on"></i>
					<?php }else{ ?>
					<i class=""></i>
					<?php } ?>
			    <?php } ?>

                </div>
            </nav>
        </section>


        <!-- banner -->
        <section class="product a-block" style="padding-top: 0.5em;padding-bottom: 0em;border-bottom: 0.3em solid #eee;">
            <div class="title"><a href="<?php echo $all_categorys_url;?>"><i class="icon-angle-right radius-border  float-right" style="margin-right: 1em;"></i><?php echo $text_all_categories;?></a></div>
        </section>
        <section class="nav clearfix">
                <a href="new_arrivals.html" class="bg-green"><span><i class="icon-eye-open"></i><br/><?php echo $text_menu_new_arrivals;?> </span></a>
                <a href="top-sellers.html" class="bg-red"><span> <i class="icon-thumbs-up"></i><br/><?php echo $text_menu_top_sellers;?></span></a>
                <a href="deals.html" class="bg-orange "><span> <i class="icon-time"></i><br/><?php echo $text_menu_deals;?> </span></a>
                <a href="clearance.html" class="bg-bule "><span> <i class="icon-tag"></i><br/><?php echo $text_menu_clearance;?></span></a>
        </section>

        <!-- product -->
       <?php echo $special;?>

        <!-- product -->
        <?php echo $bestseller;?>
<?php echo $footer; ?>
