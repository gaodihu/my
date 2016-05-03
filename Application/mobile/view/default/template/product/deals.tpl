<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>
        <!-- product -->
        <section class="product">
            <div class="title">
               <?php echo $text_special_left;?>
            </div>
            <ul class="con-box clearfix special_list">
			<?php foreach($special_lists as $pro){ ?>
                <li>
                       <a href="<?php echo $pro['href'];?>">
                           <div class="product-img">
                               <img src="<?php echo $pro['image'];?>" data-original="<?php echo $pro['image'];?>" class="test-lazyload"/>
                           </div>
                            <div class="product-title">
                               <?php echo $pro['name'];?>
                            </div>
                           <div class="deal-time-big" time="<?php echo $pro['left_time'];?>" state="false"><i class="icon-time"></i> <span class="time_box"><b><?php echo $pro['left_time_days'];?></b> <?php echo $pro['text_days'];?> <b><?php echo $pro['left_time_hours'];?></b>:<b><?php echo $pro['left_time_min'];?></b>:<b><?php echo $pro['left_time_sec'];?></b></span></div>
                           <div class="product-cost">
							<?php if($pro['special']){ ?>
                               <b><?php echo $pro['format_special'];?></b>
                               <del><?php echo $pro['format_price'];?></del>
							 <?php }else{ ?>
							  <b><?php echo $pro['format_price'];?></b>
                            
							 <?php } ?>
                           </div>
                       </a>
                </li>
			<?php } ?>
            
            </ul>
			<div id="lodding" class="lodding" style="display: none"><span><i class="icon-spinner"></i></span></div>
        </section>
<?php if($show_ajax_page){ ?>
   <script type="text/javascript" >
        timeInit();
         function timeInit(){
               $(".deal-time-big").each(function(){
                   if($(this).attr("state") == "false"){
                       common.timer($(this).find(".time_box"),$(this).attr("time"),"day");
                       //状态激活
                       $(this).attr("state","true");
                   }
               });
         }

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
                        var inHtml =  "<li>"+
                                "<a href='"+content.href
    								+"'>"+
                                "<div class='product-img'>"+
                                "<img src='"+content.image+"' />"+
                                "</div>"+
                                "<div class='product-title'>"+
                                content.name+
                                "</div>"+
                                "<div class='deal-time-big' time='"+content.left_time+"' state='false'><i class='icon-time'></i> <span class='time_box'><b>"+content.left_time_days+"</b> days <b>"+content.left_time_hours+"</b>:<b>"+content.left_time_min+"</b>:<b>"+content.left_time_sec+"</b></span></div>"+
                                "<div class='product-cost'>"+
    								price_html+
                                "</div>"+
                                "</a>"+
                                "</li>";
                        tempArr.push(inHtml);

                    });
    					    HTML = tempArr.join('');
    				        //$(".product .con-box").html(HTML);
                            $.pagescroll.setHtml(HTML);
                            timeInit();
                            // 阻止冒泡
                            $(".addcart").click(function(event){
                                event.stopPropagation();
                                return false;
                            });
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
       $.pagescroll.init(".special_list");
    </script>
<?php } ?>
<?php echo $footer; ?>