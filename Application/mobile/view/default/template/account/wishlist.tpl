<?php echo $header; ?>
 <!-- head-title -->
<div class="head-title">
	<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></a>
</div>
  <div class="spacing"></div>
        <!-- product -->
        <section class="product" style="padding-bottom: 5em;">
            <div class="title"><?php echo $heading_title;?></div>
			<?php if($success){ ?>
            <div class="msg-info"><?php echo $success;?></div>
			<?php } ?>
			<?php if($wish_lists){ ?>
            <ul class="con-box clearfix list-css my-wish">
				<?php foreach($wish_lists as $list){ ?>
                <li >
                    <a href="<?php echo $list['href'];?>">
                        <div class="product-img">
                            <img src="<?php echo $list['thumb'];?>" />
                        </div>
                        <div class="product-title">
                            <?php echo $list['name'];?>
                        </div>
                        <div class="product-cost">
							<?php if($list['special']){ ?>
                            <b><?php echo $list['special'];?></b>
                            <del><?php echo $list['price'];?></del>
							<?php }else{ ?>
							<b><?php echo $list['price'];?></b>
							<?php } ?>
                        </div>
                        <div class="stars">
                            <span class="star star-s<?php echo $list['rating'];?>"></span>
                        </div>
                        <div class="addcart">
                            <span class="min-btn grey-bg-btn" href="<?php echo $list['remove'];?>"><?php echo $button_delete;?></span>
                            <span class="min-btn orange-bg none-click" onclick="addToCart('<?php echo $list['product_id'];?>');"><?php echo $button_cart;?></span>
                        </div>
                    </a>
                </li>
				<?php } ?>
            </ul>
			<?php }else{ ?>
			<div class="msg-info"><?php echo $text_empty;?></div>
			<?php } ?>
			<div id="lodding"  class="lodding" style="display: none"><span><i class="icon-spinner"></i></span></div>
        </section>
<script>
		
	// 删除提示框
	$(".product  .addcart .grey-bg-btn").on("click",delalert,event);
function delalert(event){
    var that = $(this);
    var href=that.attr('href');
    $.popConfirm.show("<?php echo $text_remove_confirm;?>");
    $.popConfirm.yesfn=function(){
        //隐藏提示
        $.popConfirm.hide()
        // 购物车删除
        //that.parents("li").remove();
        window.location.href=href;
    }
    return false;
    event.stopPropagation();


}

</script>
<?php if($show_ajax_list){ ?>
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
								var price_html =
								'<b>'+content.special+'</b>'+
								'<del>'+content.price+'</del>';
							}else{
								var price_html =
								'<b>'+content.price+'</b>';
							}
							var inHtml=
									'<li >'+
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
												'<span class="min-btn grey-bg-btn" href="'+content.remove+'"><?php echo $button_delete;?></span> '+
												'<span class="min-btn orange-bg" onclick="addToCart('+content.product_id+');"><?php echo $button_cart;?></span>'+
											'</div>'+
										'</a>'+
								'</li>';



							tempArr.push(inHtml);
		
						});
						HTML = tempArr.join('');
						$.pagescroll.setHtml(HTML);
                        // 阻止冒泡
                        $(".addcart .min-btn").click(function(event){
                            event.stopPropagation();
                            return false;
                        });
                        // 绑定删除事件
                        $(".product  .addcart .grey-bg-btn").on("click",delalert,event);
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
       $.pagescroll.init(".my-wish");
    </script> 
<?php } ?>
<?php echo $footer; ?>