<?php echo $head;?>

<body>
 <div class="bgcolor">
<div class="grey-bg on-cancel"></div>
        <div class="all-categories-page">
            <a class="icon-arrow-left"></a>
            <div class="all-login">
                <a href="<?php echo $login_url;?>"><?php echo $text_login;?></a>
                <span>|</span>
                <a href="<?php echo $join_free_url;?>"><?php echo $text_join_free;?></a>
            </div>
            <?php echo $language;?>
			<?php echo $currency;?>
            <ul class="all-list">
                <li><a href="<?php echo $all_categorys_url;?>"><?php echo $text_all_categories;?></a></li>
                <li>
                   <a class="to-language"> <?php echo $text_language;?> <span><?php echo $current_lang_info['name'];?></span></a>
                </li>
                <li>
                    <a class="to-currency"> <?php echo $text_currency;?><span><?php echo $currency_code;?></span> </a>
                </li>
                <li><a href="about-us.html"><?php echo $text_about;?></a></li>
            </ul>
        </div>
        <!-- header ------------------------------------------>
        <header>
            <div class="menu">
                <a class="icon-search"  ></a>
				<?php if($menu_active=='category'){ ?>
                <a class="icon-list active " ></a>
				<?php }else{ ?>
				<a class="icon-list " ></a>
				<?php } ?>
				<?php if($menu_active=='account'){ ?>
                <a class=" icon-user active "   href="<?php echo $account_link;?>"></a>
				<?php }else{ ?>
				 <a class="icon-user"   href="<?php echo $account_link;?>"></a>
				 <?php } ?>
				<?php if($menu_active=='cart'){ ?>
                <a class="icon-shopping-cart active"   href="<?php echo $cart_link;?>" >
				<?php }else{ ?>
				<a class="icon-shopping-cart"   href="<?php echo $cart_link;?>" >
				<?php } ?>
				<span id='cart_total'><?php echo $cart_totals_num;?></span>
				</a>
            </div>
            <a href="/" class="logo"><img src="/mobile/view/images/public/logo.png"  width="150"  /></a>
        </header>

        <!-- query-input ------------------------------------------->
        <section class="query-page" style="display: none">
            <div class="input-bg clearfix">
                <form action="" id="queryform" onsubmit="return common.search_input();" method="post">
                <div class="query-input left"  >
                    <span class="icon-search" ></span>

                         <input type="search"   id="search"  class="query search-input" placeholder="<?php echo $text_search_myled;?>"  value="<?php echo $search;?>" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" />

                </div>
                <div  class="right  query-btn-css" >
                    <input type="button" value="<?php echo $text_cancel;?>" class="serch-btn cancel grey-btn"/>
                </div>
                <div  class="right query-btn-css" style="margin-right: 3px;">
                    <input type="submit" value="Search" class="serch-btn orange-bg"/>
                </div>
                </form>
            </div>
            <ul class="query-list query-data" style="display: none">

            </ul>
            <div class="local-data">
                <h4><?php echo $text_recent_searches;?></h4>
                <ul class="query-list">

                </ul>
                <div style="text-align: center"><a class="min-btn grey-bg-btn clearbtn" style="padding:0.5em 5em;margin-top: 1em;"><?php echo $text_clear;?></a></div>
            </div>
        </section>
        <!-- query-input end------------------------------------------->

