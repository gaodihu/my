
            <div class="faq-article appli">
                <ul class="g-nav">
                    <li class="green-title">Categories</li>
                    <?php foreach($faq_catagorys as $faq_cat){ ?>
                    <li>
                    <?php if($faq_cat['child']){ ?><span class="imgs"></span><?php } ?>
                        <?php if($faq_cat['catagory_id']==$catagory_id){ ?>
                        <a href="faq/c/<?php echo $faq_cat['url_path'];?>.html" class="g-tit fc_active"><?php echo $faq_cat['catagory_name'];?></a>
                        <?php }else{ ?>
                        <a href="faq/c/<?php echo $faq_cat['url_path'];?>.html" class="g-tit"><?php echo $faq_cat['catagory_name'];?></a>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <div class="g-content">
                    <h4 class="title-green"><span class="green">FAQ</span></h4>
                    <ul class="faq-list">
                        <?php foreach($faq_articles as $article){ ?>
                        <li>
                            <div><a href="faq/<?php echo $article['url_path'];?>.html"><?php echo $article['title'];?></a></div>
                            <?php echo sub($article['content'],200);?>
                        </li>
                        <?php } ?>
                       

                    </ul>
                    <?php if($page_links){ ?>
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