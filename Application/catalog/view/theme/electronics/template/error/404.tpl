<?php echo $header;?>
<div class=" clearfix">
    <div class="page404">
        <ul>
            <!-- <li><b>404</b></li>
            <li><span><?php echo $text_sorry;?></span></li>
            <li><?php echo $text_error;?></li>
            <li><?php echo $continue;?></li>
			 -->
			 <li><span><?php echo $text_sorry;?></span></li>
            <li><?php echo $text_error;?></li>
            <li><?php echo $text_to_proceed;?></li>
			<li><?php echo $text_go_homepage;?></li>
			<li><?php echo $text_go_lastpage;?></li>
			<li><?php echo $text_send_emial;?></li>
        </ul>
    </div>
    <div class="Recent_History" style="width: 1070px; margin: 0 auto;">
        <section class="flexslider Historypro border">
            <ul class="slides">
                <?php foreach($goods_list as $good_info){
								?>
				
                <li style='float:left; width:192px;position:relative'><div class="img"><a href="<?php echo $good_info['href'];?>">
				<?php if($good_info['discount_rate']){ ?>
                        <p class="offIcon"><span class="font20"><?php echo $good_info['discount_rate'];?></span></p>
				<?php } ?>
                             <img src="<?php echo $good_info['image'];?>" alt="<?php echo $good_info['name'];?>">
                            

                        </a></div>
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
				<div class='clear'></div>
            </ul>
        </section>
    </div>
</div>
<?php echo $footer;?>
</body>
</html>