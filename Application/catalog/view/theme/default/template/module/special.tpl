<?php if($products){ ?>
<section class="clock">
		<span class="black18 left" id="heading_title"><?php echo $heading_title;?></span><span class="left mr_5" id='time_limit'><?php echo $time_limit;?></span><div id="counter" class="indexcounter"></div>

	  <span class="bluebtn" onclick="changeSpecial()" ><a href="javascript:void(0)"><span  id="changeSpecial"><?php echo $coming_soon;?></span><code class="right_sj"></code></a></span>	</section>
<section class="prolist off50">
    <ul class="clearfix slist special_list"  >
        <?php foreach ($products as $product) { ?>

        <li>
            <div class="p-relative">
             <?php if($product['save_rate']){ ?>
            <p class="offIcon"><span class="font20"><?php echo $product['save_rate'];?></span></p>
            <?php }else if($product['is_product_hot_label']) { ?>
                <p class="offIcon hotIcon"></p>
                <?php } ?>
            </div>
            <div class="procon">
                <div class="img"><a href="<?php echo $product['href'];?>"><img src="<?php echo $product['thumb'];?>"
                                                                               width="207" height="160" alt=""/></a>

                    <div class="img_Text">
                        <div class="addthis_toolbox addthis_default_style addthis_16x16_style">
                            <a class="addthis_button_facebook at300b f" title="Facebook" href="#"><span
                                        class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span
                                            class="at_a11y"></span></span></a>
                            <a class="addthis_button_twitter at300b n" title="Tweet" href="#"><span
                                        class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span
                                            class="at_a11y"></span></span></a>
                            <a class="addthis_button_pinterest_share at300b tqq" target="_blank" title="Pinterest"
                               href="#"><span class=" at300bs at15nc at15t_pinterest_share"><span
                                            class="at_a11y"></span></span></a>

                            <div class="atclear"></div>
                        </div>

                    </div>
                </div>
                <a href="<?php echo $product['href'];?>"><p class="tt"><?php echo $product['name'];?></p>
                     <?php if($product['special']) { ?>
                     <p class="howmuch"><span class="xj"><?php echo $product['special'];?></span><span
                                class="yj"><?php echo $product['price'];?></span></p>
                     <?php }else{ ?>
                     <p class="howmuch"><span class="xj"><?php echo $product['price'];?></span></p>
                     <?php } ?>

				  <?php if($product['as_low_as_price']){ ?>
                  <p class="green"><?php echo $lower;?> <?php echo $product['as_low_as_price'];?></p>
				  <?php } ?>
				  
				   </a></div>
        </li>
        <?php
		}
		?>
    </ul>


    <ul class="clearfix slist special_list" style="display: none" >
        <?php foreach($next_products as $next_product){ ?>
            <li>
                <div class="p-relative">
            <?php if($next_product['save_rate']){ ?>
            <p class="offIcon"><span class="font20"><?php echo $next_product['save_rate'];?></span></p>
                <?php }else if($next_product['is_product_hot_label']) { ?>
                <p class="offIcon hotIcon"></p>
                <?php } ?>
                    </div>
            <div class="procon">
                <div class="img"><a href="<?php echo $next_product['href'];?>"><img src="<?php echo $next_product['thumb'];?>"
                                                                               width="207" height="160" alt=""/></a>

                    <div class="img_Text">
                        <div class="addthis_toolbox addthis_default_style addthis_16x16_style">
                            <a class="addthis_button_facebook at300b f" title="Facebook" href="#"><span
                                        class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span
                                            class="at_a11y"></span></span></a>
                            <a class="addthis_button_twitter at300b tqq" title="Tweet" href="#"><span
                                        class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span
                                            class="at_a11y"></span></span></a>
                            <a class="addthis_button_pinterest_share at300b n" target="_blank" title="Pinterest"
                               href="#"><span class=" at300bs at15nc at15t_pinterest_share"><span
                                            class="at_a11y"></span></span></a>

                            <div class="atclear"></div>
                        </div>

                    </div>
                </div>
                <p class="orange"><?php echo $coming_soon;?></p>
               <p class="tt"> <a href="<?php echo $next_product['href'];?>"><?php echo $next_product['name'];?></a></p>



        </li>

        <?php } ?>


    </ul>
	</section>
<script type="text/javascript">
	$('#counter').countdown({
		image: 'css/images/digits.png',
		startTime: '<?php echo $left_time_js;?>'
	});
    function changeSpecial(){
        var text =   $("#changeSpecial").text();
        if(text == "<?php echo $back;?>"){
            $("#changeSpecial").text("<?php echo $coming_soon;?>");
            $("#heading_title").text("<?php echo $heading_title;?>");
			$("#time_limit").text("<?php echo $time_limit;?>");

        }else{
            $("#changeSpecial").text("<?php echo $back;?>");
            $("#heading_title").text("<?php echo $next_todays_deals;?>");
			$("#time_limit").text("<?php echo $time_start_limit;?>");
			
        }

        $(".special_list").toggle();
    }
</script>
<?php } ?>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53462d7277f39c32"></script>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>