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
    <script src="<?php echo STATIC_SERVER; ?>/js/jquery/jquery.cookie.js"></script>
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
                    src="<?php echo STATIC_SERVER; ?>css/images/public/easter_banner_<?php echo $lang_code;?>.gif "
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
                           href="<?php echo $domain; ?>index.php?route=account/account"><?php echo $text_account;?>
                            <i></i></a>
                        <ul class="Account-panel top-change-list" style="display: none;min-width: 109px!important;">
                            <li><a target="_blank" href="<?php echo $domain; ?>index.php?route=account/order"
                                   rel="nofollow"><?php echo $text_my_order;?></a></li>

                            <li><a target="_blank" href="<?php echo $order_search;?>"
                                   rel="nofollow"><?php echo $text_order_search;?></a></li>

                        </ul>
                    </div>
                    <a href="<?php echo $domain; ?>contact-us.html"><i></i><?php  echo $text_contact_us;?></a>


                </div>
            </div>
        </div>
        <div class="head-defaul-bg">

            <div class="head_content ">
                <div class="wrap clearfix">
                    <div class="logo"><a href="<?php echo $domain; ?>" ><img src="<?php echo STATIC_SERVER; ?>css/images/logo.png"/></a></div>

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

               <div class="menu ">


                        <ul  >
                            <?php foreach($categories['top'] as $key => $categorie_top){
                            ?>
                            <li class="<?php echo $key; ?>" dom="nav-menu" popup="<?php echo $key; ?>">
                            <a href="<?php echo $categorie_top['href'];?>"  ><?php echo $categorie_top['name'];?></a>

                              <?php  if( isset($categories['child'][$key]['children'])  &&  $categories['child'][$key]['children'] ) {  ?>
                               <div class="popup "  dom='<?php echo $key; ?>' style="display: none;z-index: 99;">

                                        <ul class="navlink clearfix">
                                            <?php foreach($categories['child'][$key]['children'] as $child_2){ ?>
                                            <li><a href="<?php echo $child_2['href'];?>"><?php echo $child_2['name'];?></a></li>
                                            <?php  }  ?>
                                        </ul>

                                </div>

                                <?php }  ?>




                            </li>
                            <?php
                        }
                        ?>
                        </ul>
                    </ul>
                </div>


          

                </div>

            </div>
        </nav>
        <input type="hidden" name="is_login" value="<?php echo $is_login;?>" id='if_login'/>
</header>
