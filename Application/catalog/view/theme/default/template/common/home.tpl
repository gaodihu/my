
<?php echo $header; ?>

    <div class="wrap clearfix index home">
    <div class="top-banner clearfix">

        <div class="flexslider banner left">
            <div id="slideBox" class="slideBox ">
                <div class="hd">
                    <ul><li class="on">1</li> <li class="">2</li></ul>
                </div>
                <div class="bd">
                   <ul>

                       <?php foreach($home_flash_banner_info as $banner_info){
                            ?>
                       <li><a href="<?php echo $banner_info['link'];?>" ><img src="<?php echo STATIC_SERVER; ?>css/images/grey.gif"  _src="<?php echo $banner_info['image'];?>" width="750" height="250" alt="<?php echo $banner_info['title'];?>" /></a></li>

                       <?php
                          }
                          ?>

                   </ul>
                </div>

            </div>
          <!--ul class="slides">
          <?php foreach($home_flash_banner_info as $banner_info){
          ?>
          <li><a href="<?php echo $banner_info['link'];?>" ><img src="<?php echo $banner_info['image'];?>" width="750" height="250" alt="<?php echo $banner_info['title'];?>"/></a></li>
          <?php
          }
          ?>
          </ul-->
        </div>

        <div class="gg_right">
            <?php foreach($home_flash_right_banner_info as $banner_right_info){
            ?>
            <div class="gg_img gg_img2"><a href="<?php echo $banner_right_info['link'];?>" ><img src="<?php echo $banner_right_info['image'];?>"  width="213" height="125" alt="<?php echo $banner_right_info['title'];?>"/></a></div>
            <?php } ?>
        </div>
        <div class="clear"></div>
        <?php echo $special; ?>
        <script type="text/javascript">

        </script>
    </div>

        <div ><?php echo $bestseller; ?></div>
        <?php echo $latest; ?>
        <?php //echo $hotcatalog; ?>

    </div>

<?php echo $footer; ?>
