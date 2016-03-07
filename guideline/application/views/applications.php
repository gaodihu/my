
        <div class="faq-article appli">
                <ul class="g-nav">
                    <li class="green-title">Categories</li>
                    <?php foreach($all_app_info as $app_info){ ?>
                    <li><?php if($app_info['child']){ ?><span class="imgs"></span><?php } ?><a href="applications/c/<?php echo $app_info['url_path'];?>.html" class="g-tit"><?php echo $app_info['catagory_name'];?></a>
                        <?php if($app_info['child']){ ?>
                        <div class="appli-link">
                            <?php foreach($app_info['child'] as $child){ ?>
                            <span><a href="applications/c/<?php echo $child['url_path'];?>.html" class="blue"><?php echo $child['catagory_name'];?></a></span>
                            <?php } ?>
                        </div>
                        
                        <?php } ?>
                    </li>
                    <?php } ?>
                  
                    </ul>
                    <div class="g-content applications-list">
                    <ul  class="img-list">
                    <?php foreach($all_app_info as $app_info){ ?>
                        <li>
                            <a href="applications/c/<?php echo $app_info['url_path'];?>.html"><img src="<?php echo $app_info['image'];?>" width='470' height='220'/>
                            <div class="title-bg"></div>
							<div class="img-info"><?php echo $app_info['catagory_description'];?></div>
                            <div class="title"><?php echo $app_info['catagory_name'];?></div>
                            </a>
                        </li>
                     <?php } ?>
                    </ul>
                </div>
                </div>
            </div>
    </div>