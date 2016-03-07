<?php echo $header; ?>
<nav class="sidernav">
    <div class="wrap">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li>
		<span>
		<?php if($breadcrumb['href']){ ?>
            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php	} else{ ?>
            <?php echo $breadcrumb['text']; ?>
            <?php	} ?>
		</span>
                <?php echo $breadcrumb['separator']; ?>
            </li>
            <?php } ?>

        </ul>
    </div>
    <div class="clear"></div>
</nav>

<section class="wrap product">
    <div class="wrap clearfix">
        <div class="col-main">
            <div class="product-detail">
                <div class="detail-container">
                    <?php if($deals){ ?>
                    <?php $count =count($deals);foreach($deals as $key=>$product){ ?>
                    <?php if($key==0){ ?>
                    <div class="deals">
                        <?php } elseif($key==($count-1)){ ?>
                        <div class="deals mt_20 mb_20">
                            <?php } else{ ?>
                            <div class="deals mt_20">
                                <?php } ?>

                                <h2><?php echo $product['name'];?></h2>
                                <section class="deals_con">
                                    <div class="deals_l">
                                        <div class="deals_dis">

                                            <div class="deals_jj"><img
                                                        src="css/images/deals_01.png" width="40"
                                                        height="60"></div>
                                            <div class="deals_rig" style="position: relative">

                              <span class="deals_price">
                                  <?php echo $product['format_special'];?></span>
                              <span class="deals_buy">
                                     <a href="javascript:addToCart('<?php echo $product['product_id'];?>')"
                                        id='deals_add_cart'
                                        onclick="ga('send', 'event', 'add to cart', '<?php echo $product['name'];?>', '<?php echo $product['sku'];?>')"><?php echo $text_buy;?></a>

                              </span>
                                            </div>
                                            <div class="cart-pop add-cart_<?php echo $product['product_id'];?>"
                                                 style="display: none; margin-top: -90px">
                                                <a class="del"></a>

                                                <div>
                                                    <h4><img src="css/images/public/yes.gif"
                                                             width="45" height="40"><span id='add_qty_number'></span>
                                                        <?php echo $text_product_added;?></h4>

                                                    
                                                    <div><input type="button" value="<?php echo $text_view_cart;?>"
                                                                onclick="window.location.href='/index.php?route=checkout/cart'"
                                                                class="btn-primary" style="margin-right: 20px"/>

                                                        <input type="button" value="<?php echo $text_continue_shopping;?>"
                                                                class="delbtn btn-default"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="price_deals">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo $text_list_price;?></th>
                                                    <th><?php echo $text_discount;?></th>
                                                    <th><?php echo $text_save;?></th>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $product['format_price'];?></td>
                                                    <td><?php echo $product['discount_rate'];?>%</td>
                                                    <td><?php echo $product['save'];?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="countTime_time ml_20">
                                            <?php if(isset($product['left_time_js'])){  ?>
                                            <p class="countTime_t red"><?php echo $text_special_left;?> :</p>

                                            <div id="counter<?php echo $product['product_id'];?>"></div>
                                            <div class="time_desc">
                                                <div><?php echo $text_day;?></div>
                                                <div><?php echo $text_hour;?></div>
                                                <div><?php echo $text_min;?></div>
                                                <div><?php echo $text_sec;?></div>
                                            </div>

                                            <script type="text/javascript">

                                                    var product_id =<?php echo $product['product_id'];?>;
                                                    var left_time_id = 'counter' + product_id;
                                                    $('#' + left_time_id).countdown({
                                                        image: 'css/images/digits.png',
                                                        startTime: "<?php echo $product['left_time_js'];?>"
                                                    });


                                            </script>
                                            <?php }  ?>
                                            <div class="clear"></div>

                                            <p class="proleft"><?php echo $product['quantity'];?> <?php echo $text_product_left;?>
												<?php if($product['text_limit_buy']){ ?>
                                                <span>( <?php echo $product['text_limit_buy'];?> )</span>
												<?php } ?>
											</p>
                                        </div>
                                        <p class="dealsp"><?php echo $product['description'];?>
                                            <a href="<?php echo $product['href'];?>"><?php echo $text_view_details;?></a></p>
                                    </div>
                                    <div class="deals_r"><a href="<?php echo $product['href'];?>"><img
                                                    src="<?php echo $product['image'];?>" width="320" height="320"
                                                    border="0"></a></div>
                                    <div class="clear"></div>
                                </section>
                            </div>
                            <?php } ?>

                            <?php echo $pagination;?>
                        </div>
                        <?php }else{ ?>
                        <div style="font-size:20px; text-align:center; height:100px; margin-top:60px; color:#ef0101"><?php echo $text_empty;?></div>

                        <div class="clear"></div>
                        <div>
                            <div class="protit"><p class="black18"><?php echo $text_recommendations;?></p></div>
                            <section class="Historypro flexslider  border">
                                <div class="picScroll-left">
                                    <div class="hd">
                                        <a class="next"> >  </a>
                                        <ul><li class="">1</li><li class="">2</li><li class="">3</li><li class="on">4</li></ul>
                                        <a class="prev"> <</a>
                                        <span class="pageState"><span>4</span>/4</span>
                                    </div>
                                <div class="bd">
                                    <ul >
                                        <?php foreach($recommendations as $history_pro){
                                    ?>
                                        <li>
                                            <div class="img"><a href="<?php echo $history_pro['href'];?>"><img
                                                            src="<?php echo $history_pro['image'];?>"
                                                            alt="<?php echo $history_pro['name'];?>"></a></div>
                                            <div class="t"><a
                                                        href="<?php echo $history_pro['href'];?>"><?php echo $history_pro['name'];?></a>
                                            </div>
                                            <?php if($history_pro['format_special']){ ?>
                                            <div class="howmuch"><span
                                                        class="xj"><?php echo $history_pro['format_special'];?></span><span
                                                        class="yj"><?php echo $history_pro['format_price'];?></span></div>
                                            <?php }
                                            else{ ?>
                                            <div class="howmuch"><span
                                                        class="xj"><?php echo $history_pro['format_price'];?></span></div>
                                            <?php } ?>

                                        </li>
                                        <?php
                                    }
                                    ?>
                                    </ul>
                                </div>
                            </section>

                            <script type="text/javascript">
                                require(['jQuery','SuperSlide'],function(jQuery,SuperSlide){
                                    $(".picScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:4});
                                });
                            </script>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-extra">
                    <section class="leftpro border clearfix">
                        <div class="leftprotit bold"><?php echo $text_top_seller;?></div>
                        <ul>
                            <?php foreach($top_selles as $top_sell){ ?>
                            <li>
                                <div class="img"><a href="<?php echo $top_sell['href'];?>"><img
                                                src="<?php echo $top_sell['thumb'];?>"
                                                alt="<?php echo $top_sell['name'];?>"/></a></div>
                                <div class="t"><a
                                            href="<?php echo $top_sell['href'];?>"><?php echo $top_sell['name'];?></a>
                                </div>
                                <?php if($top_sell['special']){ ?>
                                <div class="howmuch"><span class="xj"><?php echo $top_sell['special'];?></span><span
                                            class="yj"><?php echo $top_sell['price'];?></span></div>
                                <?php }else{ ?>
                                <div class="howmuch"><span class="xj"><?php echo $top_sell['price'];?></span></div>
                                <?php } ?>

                            </li>
                            <?php } ?>
                        </ul>
                    </section>
                    <div class="clear"></div>
                    <section class="leftpro border clearfix">
                        <div class="leftprotit bold"><?php echo $text_hot;?></div>
                        <ul>
                            <?php foreach($special_lists as $special){ ?>
                            <li><a href="<?php echo $special['link'];?>"><img src="<?php echo $special['image'];?>"
                                                                              alt="<?php echo $special['title'];?>"/></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </section>
                </div>
                <div class="clear"></div>
                <?php foreach($pro_list_foot_banner as $banner){ ?>
                <div class="probot"><a href="<?php echo $banner['link'];?>"><img src="<?php echo $banner['image'];?>"
                                                                                 alt="<?php echo $banner['title'];?>"/></a>
                </div>
                <?php } ?>

            </div>

        </div>
    </div>
</section>


<div class="fix-layout">
    <div class="gb-operation-area" id="_returnTop_layout_inner">
        <a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="Top"
                                                                                           class="gb-operation-icon"></i>
            <span class="gb-operation-text">Top</span>
        </a>
    </div>
</div>

<script type="text/javascript">
    function show_pop_deal(product_id, price, add_qty, total) {
        $(".add-cart_" + product_id).show();
        $('#add_qty_number').html(add_qty);
        $("span[rel=cart-price-total]").text(price);
        $("b[rel=cart-total]").text(total);
    }
    $(".del,.delbtn").click(function () {
        $(".cart-pop").hide();
    })

</script>

<?php echo $footer; ?>