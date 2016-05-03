<?php echo $header; ?>

<div class="head-title">
    <a class="icon-angle-left left-btn"></a><?php echo $category_info['name'];?> (<?php echo $total_product;?>)
    <?php if($_COOKIE['view_list']=='list'){ ?>
    <a class="icon-align-left right-btn"></a>
    <?php }else{ ?>
    <a class="icon-th-large right-btn"></a>
    <?php } ?>
</div>
<!-- pop-filter -->

<div class="pop-filter close" id="filter">

    <div class="cont">
        <div class="pop-tit"><a class="close a-btn"><i class="icon-remove"></i></a><?php echo $text_filter;?></div>

		<?php if($AttrbuteGroup){ ?>
        <div class="filter-box">
            <ul class="categories-list filter-list first-list" style="border-top:0 ">
                <?php foreach($AttrbuteGroup as $attr_group){ ?>
                <li>
                    <a class="list-change"><i class="icon-caret-right"></i><?php echo $attr_group['name'];?></a>
                    <ul class="second-list">
                        <?php foreach($attr_group['option'] as $option){ ?>
                        <li><a href="<?php echo $option['href'];?>"><label><?php echo $option['option_value'];?></label></a>
                        </li>
                        <?php } ?>

                    </ul>
                </li>
                <?php } ?>
                <li>
                    <a class="list-change"><i class="icon-caret-right"></i><?php echo $text_price;?></a>
                    <ul class="second-list">
                        <?php foreach($price_range_list as $option){ ?>
                        <li><a href="<?php echo $option['href'];?>"><label><?php echo $option['option_value'];?></label></a>
                        </li>
                        <?php } ?>

                    </ul>
                </li>
            </ul>

        </div>
		<?php } ?>
        <!-- <div style="padding: 1em;text-align: center" class="checkout-btn">
            <a class="button orange-bg">APPLY</a>
        </div> -->
    </div>

</div>
<!-- product -->
<section class="product">

            <span class="product-change ">
                <a class="sortby"><?php echo $text_sort_by;?><span class="by-info">( <span
                                id="by-info"><?php echo $current_sort_info['text'];?>  <i class="f16 <?php if(strtolower($order)=='desc'){ ?> icon-double-angle-down <?php }else{ ?>  icon-double-angle-up <?php } ?>"></i> </span> )</span></a>
                <ul class="sortby-list" style="display: none">
                    <?php foreach($sorts as $list_sort){ ?>
                    <?php if($sort ==$list_sort['code']){ ?>
                    <li class="active">
					<a href="<?php echo $list_sort['href'];?>"><?php echo $list_sort['text'];?>
					 <i class=" <?php if(strtolower($order)=='desc'){ ?>  icon-double-angle-up <?php }else{ ?> icon-double-angle-down <?php } ?>"></i>
					</a>
                    </li>
                    <?php }else{ ?>
                    <li><a href="<?php echo $list_sort['href'];?>"><?php echo $list_sort['text'];?>
					 <i class=" <?php if(strtolower($order)=='desc'){ ?>  icon-double-angle-up <?php }else{ ?> icon-double-angle-down <?php } ?>"></i>
					</a></li>
                    <?php } ?>
                    <?php } ?>

                </ul>





					<?php if($select_option){ ?>
					<span class="filter"><i class="icon-filter icon-filter-select"></i></span>
					<?php }elseif($AttrbuteGroup&&!$select_option){ ?>
					<span class="filter"><i class="icon-filter"></i></span>
					<?php } ?>
            </span>
            <div>
                <!------------- show-check -------------->
                <?php if($select_option){ ?>
                <div id="show-check "  class="clearfix  borderline m-b10 p_b0">
                    <?php foreach($select_option as $selected){ ?>
                    <a class="selected_attr" rel="<?php echo $selected['option_id'];?>"
                       title="<?php echo $selected['option_value'];?>"
                       href="<?php echo $selected['href'];?>"><?php echo $selected['option_value'];?><i class="icon-remove"></i></a>
                    <?php } ?>

                </div>
                <?php } ?>




            </div>
    <?php if($_COOKIE['view_list']=='list'){ ?>
    <ul class="con-box clearfix list-css">
        <?php }else{ ?>
        <ul class="con-box clearfix ">
            <?php } ?>

            <?php foreach($products as $pro){ ?>
            <li>
                <a href="<?php echo $pro['href'];?>">
                    <div class="product-img">
                        <img src="<?php echo $pro['thumb'];?>"/>
                    </div>
                    <div class="product-title">
                        <?php echo $pro['name'];?>
                    </div>
                    <div class="product-cost">
                        <?php if($pro['special']){ ?>
                        <b><?php echo $pro['special'];?></b>
                        <del><?php echo $pro['price'];?></del>
                        <?php }else{ ?>
                        <b><?php echo $pro['price'];?></b>

                        <?php } ?>
                    </div>
                    <div class="stars">
                        <span class="star star-s<?php echo $pro['rating'];?>"></span>
                    </div>
                    
                    <div class="addcart">
                        <?php if(!$pro['is_battery']){ ?>
                        <span class="min-btn orange-bg"
                              onclick="addToCart('<?php echo $pro['product_id'];?>');"><?php echo $button_cart;?></span>
                        <?php } else { ?>
                         <span class="min-btn orange-bg"
                              onclick="javascript:location.href='<?php echo $pro['href']; ?>';"><?php echo $button_cart;?></span>
                        <?php } ?>
                    </div>
                    
                </a>
            </li>
            <?php } ?>
        </ul>


        <div id="lodding" class="lodding" style="display: none"><span></span></div>
</section>
</div>
<?php if($fanye_show){ ?>
<script type="text/javascript">

    $.pagescroll.ajax_fn = function () {
        $.ajax({
            type: "get",
            url: '<?php echo htmlspecialchars_decode($json_list_url);?>',
            data: 'page=' + ($.pagescroll.index + 1),
            dataType: "json",
            success: function (data) {
                if(data['data'] == null){
                    $.pagescroll.loadedEnd("LOADE END");
                    return;
                }

                var tempArr = [], HTML;
                $.pagescroll.index = $.pagescroll.index + 1;
                if ($.pagescroll.index == 0) {
                    $.pagescroll.setHtml("");
                    return false;
                }


                // 有数据
                if (data['error'] == 0) {
                    $.each(data['data'], function (index, content) {
                        if (content.special) {
                            var price_html = "<b>" + content.special + "</b>" +
                                    "<del>" + content.price + "</del>";
                        } else {
                            var price_html = "<b>" + content.price + "</b>";
                        }
                        var inHtml = "<li>" +
                                "<a href='" + content.href
                                + "'>" +
                                "<div class='product-img'>" +
                                "<img src='" + content.thumb + "' />" +
                                "</div>" +
                                "<div class='product-title'>" +
                                content.name +
                                "</div>" +
                                "<div class='product-cost'>" +
                                price_html +
                                "</div>" +
                                "<div class='stars'>" +
                                "<span class='star star-s" + content.rating + "'></span>" +
                                "</div>" +
                                "<div class='addcart'>" +
                                "<span class='min-btn orange-bg' onclick='addToCart(" + content.product_id + ");'>" + content.add_cart + "</span>" +
                                "</div>" +
                                "</a>" +
                                "</li>";
                        tempArr.push(inHtml);

                    });
                    HTML = tempArr.join('');
                    //$(".product .con-box").html(HTML);
                    $.pagescroll.setHtml(HTML);
                    // 阻止冒泡
                    $(".addcart").click(function (event) {
                        event.stopPropagation();
                        return false;
                    });
                } else {
                    $.pagescroll.setHtml(data['message']);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //console.log(errorThrown);
            }
        });

    }

    /*  page lodding  */
    $.pagescroll.init(".product .con-box");
    $.scrollbtn.init('<?php echo $cart_url;?>');
</script>
<?php } ?>
<?php echo $footer; ?>
