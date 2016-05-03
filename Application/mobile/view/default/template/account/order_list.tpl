<?php echo $header; ?>
<!-- pop-price -->
<div class="pop-filter pop-price" style="display: none" id="filter-order">
	<div class="cont">
		<div class="pop-tit">
			<a class="close a-btn order-filter-close"><i class="icon-remove"></i></a>
			<?php echo $text_filter;?>
		</div>
		<form action="<?php echo $action;?>" method="post" id='order_form'>
		<div class="filter-number">
			
			<input type="text" class="input_price min" name='order_number' placeholder="Order Number" style="width:21em;"  value="<?php echo $order_number;?>">
			<a class="radius-btn green-btn" style="font-size:2em;margin-left: 10px;"><i class="icon-ok"></i></a>
			<div class="spacing"></div>
			
			<input type="date" class="input_price min" name='date_from' placeholder="Start"  value="<?php echo $date_from;?>">
			<i class="icon-minus"></i>
			<input type="date" class="input_price max" name='date_to' placeholder="End"  value="<?php echo $date_to;?>">
			<a class="radius-btn green-btn" style="font-size:2em;margin-left: 10px;"><i class="icon-ok"></i></a>
		</div>
		</form>
	</div>
</div>
   <div class="head-title">
		<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?> <a class="order-filter"><i class="icon-filter"></i></a>
	</div>

	<div class="spacing"></div>
		<?php if($orders){ ?>
        <ul  class="account-list">
		<?php foreach($orders as $order){ ?>
            <li>
                <a href="<?php echo $order['href'];?>">
                    <i class="icon-caret-right"></i>
                    <div ><?php echo $text_order_id;?>:<span  class="order-no"><?php echo $order['order_number'];?></span></div>
                    <div><?php echo $text_total;?>:<span class="orange"><?php echo $order['total'];?></span></div>
                    <div><span class="date"><?php echo $order['date_added'];?></span><?php echo $text_status;?>:<span class="grey"><?php echo $order['status'];?></span></div>
                </a>
            </li>
		<?php } ?>
        </ul>
		<?php }else{ ?>
		<div class="msg-info"><?php echo $text_empty;?></div>
		<?php } ?>
		<div id="lodding"  class="lodding" style="display: none"><span><i class="icon-spinner"></i></span></div>
     </div>
<script>
$(".radius-btn").click(function(){
	$('#order_form').submit();
});
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
							var inHtml=
							'<li>'+
								'<a href="'+content.href+'">'+
									'<i class="icon-caret-right"></i>'+
									'<div ><?php echo $text_order_id;?>:<span  class="order-no">'+content.order_number+'</span></div>'+
									'<div><?php echo $text_total;?>:<span class="orange">'+content.total+'</span></div>'+
									'<div><span class="date">'+content.date_added+'</span><?php echo $text_status;?>:<span class="grey">'+content.status+'</span></div>'+
								'</a>'+
							'</li>';
							tempArr.push(inHtml);
		
						});
						HTML = tempArr.join('');
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
       $.pagescroll.init(".account-list");
    </script> 
<?php } ?>
<?php echo $footer; ?>

