<?php echo $header; ?>
<div class="head-title">
    <a class="icon-angle-left left-btn"></a><?php echo $heading_title;?>(<?php echo $res_count;?>)
	<?php if($products){ ?>
		<?php if($_COOKIE['view_list']=='list'){ ?>
		<a class="icon-align-left right-btn"></a>
		<?php }else{ ?>
		<a class="icon-th-large right-btn"></a>
		<?php } ?>
	<?php } ?>
</div>
<section class="product">

    <?php if($products){ ?>
            <span class="product-change ">
                <a class="sortby"><?php echo $text_sort_by;?><span class="by-info"><?php if($current_sort_info['text']) { ?>( <span id="by-info"><?php echo $current_sort_info['text'];?></span> )<?php } ?></span></a>
                <ul class="sortby-list" style="display: none">
                    <?php foreach($sorts as $list_sort){ ?>
                    <?php if($sort ==$list_sort['code']){ ?>
                    <li class="active"><a href="<?php echo $list_sort['href'];?>"><?php echo $list_sort['text'];?></a> </li>
                    <?php }else{ ?>
                    <li ><a href="<?php echo $list_sort['href'];?>"><?php echo $list_sort['text'];?></a> </li>
                    <?php } ?>
                    <?php } ?>

                </ul>

            </span>
    <?php if($_COOKIE['view_list']=='list'){ ?>
        <ul class="con-box clearfix list-css">
     <?php }else{ ?>
        <ul class="con-box clearfix ">
     <?php } ?>
        <?php foreach($products as $pro){ ?>
        <li>
            <a href="<?php echo $pro['href'];?>">
                <div class="product-img">
                    <img src="<?php echo $pro['thumb'];?>" />
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
                    <span class="min-btn orange-bg" onclick="addToCart('<?php echo $pro['product_id'];?>');"><?php echo $button_cart;?></span>
                </div>
            </a>
        </li>
        <?php } ?>

    </ul>


    <div id="lodding"  class="lodding" style="display: none"><span><i class="icon-spinner"></i></span></div>
    <?php }else{ ?>
    <section>
        <div class="page404">


                    <ul>
                        <li class="font20 m-b20"><span><?php echo $no_res;?></span></span></li>
                        <li> <?php echo $text_suggestions;?> </li>
                        <li><?php echo $text_suggestions_01;?></li>
                        <li><?php echo $text_suggestions_02;?></li>
                        <li><?php echo $text_suggestions_03;?></li>
                    </ul>

            </div>
    </section>
    <?php } ?>
</section>
<?php if($fanye_show){ ?>
<script type="text/javascript" >
    $.pagescroll.ajax_fn=function(){
        $.ajax({
            type: "get",
            url: '<?php echo htmlspecialchars_decode($json_list_url);?>',
            data: 'page=' + ($.pagescroll.index+1),
            dataType: "json",
            success: function (data) {
                var tempArr = [],HTML;
                $.pagescroll.index = $.pagescroll.index +1;
                if($.pagescroll.index == 0){
                    $.pagescroll.setHtml("");
                    return false;
                }
                if(data['error']==0){
                    $.each( data['data'], function(index, content)
                    {
                        if(content.special){
                            var price_html ="<b>"+content.special+"</b>"+
                                    "<del>"+content.price+"</del>";
                        }else{
                            var price_html  = "<b>"+content.price+"</b>";
                        }
                     var inHtml =
                        '<li>'+
                    '<a href="'+content.href+'">'+
                    '<div class="product-img">'+
                    '<img src="'+content.thumb+'" />'+
                    '</div>'+
                    '<div class="product-title">'+
                        content.name+
                      '</div>'+
                    '<div class="product-cost">'+
                        price_html+
					'</div>'+
                    '<div class="stars">'+
                    '<span class="star star-s'+content.rating+'"></span>'+
                    '</div>'+
                    '<div class="addcart">'+
                    '<span class="min-btn orange-bg" onclick="addToCart("'+content.product_id+'");">'+content.add_cart+'</span>'+
                    '</div>'+
                    '</a>'+
                    '</li>';
                        tempArr.push(inHtml);

                    });
                    HTML = tempArr.join('');
                    //$(".product .con-box").html(HTML);
                    $.pagescroll.setHtml(HTML);
                }else{
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