<?php echo $head;?>
<body>
<header>
    <?php if($china_10_1){ ?>
    <div id="china_10_1" class="national_day"
    <?php if(isset($_COOKIE['china_10_1']) && $_COOKIE['china_10_1'] =='hide' ) { ?>style="display:none"<?php } ?>>

    <div class="wrap relative">
        <a class="red-del" onclick="close101()"></a>
        <?php echo $china_10_1_msg; ?>
    </div>

    </div>
    <script src="/js/jquery/jquery.cookie.js"></script>
    <script>
        function close101() {
            $('#china_10_1').hide();

            jQuery.cookie('china_10_1', 'hide', {domain:'<?php echo $cookie_domain; ?>'});
        }
    </script>

    <?php } ?>


    <?php if(isset($show_esater_banner)){ ?>
    <div style='margin:0 auto;width:1200px;'>
        <a href="<?php echo $esater_url;?>"><img
                    src="css/images/public/easter_banner_<?php echo $lang_code;?>.gif "
                    width='1200' height='100'></a>
    </div>
    <?php } ?>
    <div class="top nav-common">
        <div class="wrap clearfix">
            <div class="topleft">

                <?php echo $language; ?>
                <?php echo $currency; ?>
            </div>
            <?php if(!$logged){
			?>
            <div class="topright"><?php echo $text_welcome;?>
                <?php
			}
			else{
			?>
                <div class="topright"><?php echo $text_logged;?>
                    <?php
			}
			?>
                    <div class="Account top-change-tab">
                        <a class="Account-anchor top-change-name"
                           href="index.php?route=account/account"><?php echo $text_account;?>
                            <i></i></a>
                        <ul class="Account-panel top-change-list" style="display: none;min-width: 109px!important;">
                            <li><a target="_blank" href="/index.php?route=account/order"
                                   rel="nofollow"><?php echo $text_my_order;?></a></li>

                            <li><a target="_blank" href="<?php echo $order_search;?>"
                                   rel="nofollow"><?php echo $text_order_search;?></a></li>

                        </ul>
                    </div>
                    <a href="/contact-us.html"><i></i><?php  echo $text_contact_us;?></a>


                </div>
            </div>
        </div>
        <div class="head-defaul-bg">

            <div class="head_content ">
                <div class="wrap clearfix">
                    <div class="logo"><a href="/" ><img src="css/images/logo.png"/></a></div>

                    <div class="headright clearfix">


                        <div class="newserch">
                            <div class="serch_btn"><input type="button" id="search_button"></div>
                            <input name="search" id="search" class="newserch_input" type="text"
                                   placeholder="<?php echo $text_enter_search;?>"
                                   value="<?php echo $search_keyword;?>"/>

                        </div>

                        <div class="shop-car right">


                            <div class="num left nav-common">
                                <div class="cart dropdown" id='cart'>
                                    <?php echo $cart;?>
                                </div>

                            </div>

                            <div class="clear"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <nav class="nav">
            <div class="wrap clearfix">
                <?php if($show_cat){
			?>
                <div class="navleft" id="indexnav">
                    <?php }else{ ?>
                    <div class="navleft" id="infornav">
                        <?php }?>

                        <div class="first_link">
                            <?php if($show_cat){ ?>
                            <span><?php echo $text_led_categories;?></span>
                        </div>
                        <?php }else{ ?>
                        <a class="list_img"><?php echo $text_led_categories;?></a>
                    </div>
                    <?php } ?>

                    <div class="navbar">
                        <ul>
                            <?php foreach($categories['top'] as $key => $categorie_top){
                            ?>
                            <li class="<?php echo $key; ?>"><a href="<?php echo $categorie_top['href'];?>"
                                                               dom="<?php echo $key; ?>"><?php echo $categorie_top['name'];?></a>
                            </li>
                            <?php
						}
						?>
                        </ul>
                    </div>
                    <div class="thirdnav">
                        <?php if(isset($categories['child']) && is_array($categories['child']) && count($categories['child'])>
                        0){ ?>
                        <?php foreach($categories['child'] as $c_k => $child){ ?>
                        <section class="thirdnavbox  nofigure <?php if(!$child['bg_image']) { ?> nofigure <?php } ?>"
                                 dom='<?php echo $c_k; ?>'>
                            <!--
                            <?php if($child['bg_image']) { ?>
                                    <figure class="img"><img class="third-nav-img"  src="css/images/grey.gif"  _src="<?php echo $child['bg_image'];?>" alt=""/></figure>
                            <?php } ?>
                            -->

                            <article class="text">
                                <ul class="navlink clearfix">
                                    <?php foreach($child['children'] as $child_2){
								?>
                                    <li><a href="<?php echo $child_2['href'];?>"><?php echo $child_2['name'];?></a></li>
                                    <?php
								}
								?>
                                </ul>
                                <?php if($child['action_description']) { ?>
                                <ul class="navlink rightlink">
                                    <?php echo $child['action_description'];?>
                                </ul>
                                <?php } ?>
                            </article>

                        </section>
                        <?php
                             }
                          }
					?>
                    </div>
                </div>
                <div class="menu">
                    <ul>
                        <?php foreach($menus as $key => $menu){ ?>
                        <?php if($menu['is_active']){ ?>
                        <li class="active">
                            <?php }else{ ?>
                        <li>
                            <?php }?>
                            <a href="<?php echo $menu['link'];?>"><span></span><?php echo $menu['text'];?></a>
                        </li>
                        <?php } ?>


                    </ul>
                </div>
            </div>
        </nav>
        <input type="hidden" name="is_login" value="<?php echo $is_login;?>" id='if_login'/>
</header>
