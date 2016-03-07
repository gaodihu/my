

  <div class="faq-article appli">
                <ul class="g-nav">
                    <li class="green-title">Categories</li>
                    <?php foreach($all_app_info as $app_info){ ?>
                    <?php if($catagory_info['catagory_id']==$app_info['catagory_id']){ ?>
                    <li class='active'><?php if($app_info['child']){ ?><span class="imgs"></span><?php } ?><a href="javascript:void(0)" class="g-tit blue"><?php echo  $app_info['catagory_name'];?></a>
                        <div class="appli-link" style='display:block'>
                    <?php }else { ?>
                    <li><?php if($app_info['child']){ ?><span class="imgs"></span><?php } ?><a href="applications/c/<?php echo $app_info['url_path'];?>.html" class="g-tit"><?php echo  $app_info['catagory_name'];?></a>
                        <div class="appli-link">
                     <?php } ?>
                            <?php if($app_info['child']){ ?>
                            <?php foreach($app_info['child'] as $child){ ?>
                            <span><a href="applications/c/<?php echo $child['url_path'];?>.html" ><?php echo $child['catagory_name'];?></a></span>
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


                    <ul  class="img-list">
                        <?php if($child_catagory_info){ ?>
                        <?php foreach($child_catagory_info as $child_catagory){ ?>
                        <li>
                            <a href="applications/c/<?php echo $child_catagory['url_path'];?>.html"><img src="<?php echo $child_catagory['image'];?>" width='470' height='220'/>
							<div class="title-bg"></div>
                            <div class="img-info"><?php echo $child_catagory['catagory_description'];?></div>
                            <div class="title"><?php echo $child_catagory['catagory_name'];?></div>
                            
                            </a>
                        </li>
                        <?php } ?>
                        <?php   } ?>
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