
        <div class="faq-article">
                <ul class="g-nav">
                    <li class="green-title">Categories</li>
                    <?php foreach($faq_catagorys as $faq_cat){ ?>
                       <?php if($faq_cat['catagory_id']==$catagory_id){ ?>
                            <li class='active'><?php if($faq_cat['child']){ ?><span class="imgs"></span><?php } ?><a href="faq/c/<?php echo $faq_cat['url_path'];?>.html" class="g-tit blue"><?php echo $faq_cat['catagory_name'];?></a></li>
                       <?php }else{ ?>
                            <li><?php if($faq_cat['child']){ ?><span class="imgs"></span><?php } ?><a href="faq/c/<?php echo $faq_cat['url_path'];?>.html" class="g-tit"><?php echo $faq_cat['catagory_name'];?></a></li>
                       <?php } ?>
                    
                    <?php } ?>
                    
                </ul>
                <div class="g-content">
                    <h4 class="title-green"><span class="green">FAQ</span></h4>
                    <div class="faq-list">
                            <div><?php echo  $faq_info['title'];?></div>
                            <?php echo  $faq_info['content'];?>
                    </div>
                    <h4 class="title-green" style="margin-top: 20px;"><span>Related FAQ</span></h4>
                    <ul class="faq-list">
                        <?php foreach($related_info as $info) { ?>
                        <li>
                            <div><a href="faq/<?php echo $info['url_path'];?>.html"><?php echo  $info['title'];?></a></div>
                            <?php if(strlen($info['content'])>200){
                                echo sub($info['content'],200);
                              }else{ 
                                  echo $info['content'];
                               }?>
                        </li>
                        <?php } ?>
                       

                    </ul>
                    <?php if(isset($page_links)){ ?>
                    <div class="propage"><?php echo $page_links;?></div>
                    <?php } ?>
                </div>
            </div>
    </div>
    <div class="clear"></div>
 <script type="text/javascript">

    $(function(){
        $(".tabli").hover(function(){
            $(this).addClass("active-tab");
            $(".content-tab").show();
        },function(){
            $(this).removeClass("active-tab");
            $(".content-tab").hide();
        })
    });

</script>