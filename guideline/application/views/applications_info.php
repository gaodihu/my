

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
                <div class="g-content applications-list">
                    <div class="info-banner">
                        <div class="info">
                            <h4><?php echo $catagory_info['catagory_name'];?></h4>
                           <?php echo $catagory_info['catagory_description'];?>
                        </div>
                        <div class="img">
                            <img src="<?php echo $catagory_info['image'];?>" width="516" height="198"/>
                        </div>
                    </div>

                    <h4 class="title-green"><span>Applications in <?php echo $catagory_info['catagory_name'];?>:</span></h4>
                    <ul  class="img-list">
                        <?php foreach($articles_list as $article){ ?>
                        <li>
                            <a href="applications/<?php echo $article['url_path'];?>.html"><img src="<?php echo $article['effect_image'];?>" width='470' height='220'/>
                            <div class="title-bg"></div>
                            <div class="title"><?php echo $catagory_info['catagory_name'];?></div>
                            <div class="img-info">
                                <h4 class="green"><?php echo $article['title'];?></h4>
                                <?php echo $article['meta_description'];?>
                            </div>
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