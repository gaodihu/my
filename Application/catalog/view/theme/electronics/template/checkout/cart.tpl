<?php echo $header; ?>
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<div class="grey-bg " style=" display: none;"></div>
<nav class="sidernav">
    <div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){ ?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } else{ ?>
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

<section class="wrap p-relative" >
<?php if($products){ ?>
    <div class="mycartbt">
		<span class="tit"><?php echo $heading_title; ?></span>
        <div class="cart-btn">
            
            <a href="<?php echo $paypal_express; ?>" dom="paypal" <?php if(!$show_paypal){ ?>style="display:none"<?php } ?>><span class="paypal"></span></a><span class="left" dom="paypal" <?php if(!$show_paypal){ ?>style="display:none"<?php } ?>>-<?php echo $text_or;?>-</span>
            
                               <span class="carbtn"><a href="<?php echo $checkout;?>" class="proceed"><span class="btnjt"></span><?php echo $text_proceed_to_checkout;?></a></span></div>
    </div>
<div class="cart_message <?php if(!$error_info){ ?>message<?php } else{ ?>warning2<?php } ?>" <?php if($error_info){ ?>style="display:block"<?php } ?>>
    
    <?php 
        if($error_info && is_array($error_info) && count($error_info)>0){
            foreach($error_info as $item){
                echo $item."<br/>";
            }
        }
    ?>
</div>

        <div  id='cart_mb'><span><img src="<?php  echo STATIC_SERVER; ?>css/images/loader_32x32.gif" width="32" height="32" /></span></div>
    <div class="mycart_table">

    	<?php include_once(DIR_TEMPLATE.'/default/template/checkout/include/cart_product.tpl');?>
    </div>


		<?php foreach($totals as $total){ ?>
			 <?php if($total['code']=='total'){ ?>
			 	<div class="alignright font20">Cart Total: <span class="red" ><span id='cart_total'><?php echo $total['text'];?></span></span></div>
		<?php } ?>
		<?php } ?>

        <div class="mycartbt mycartbt_under">
        	<a href="<?php echo $continue;?>" class="common-btn-gray"><?php echo $text_continue_shopping;?></a>
                <div class="cart-btn">
                   
                    <a href="<?php echo $paypal_express; ?>" dom="paypal" <?php if(!$show_paypal){ ?>style="display:none"<?php } ?>><span class="paypal"></span></a><span class="left" dom="paypal" <?php if(!$show_paypal){ ?>style="display:none"<?php } ?>>-<?php echo $text_or;?>-</span>
                    
                    <span class="carbtn"><a href="<?php echo $checkout;?>" class="proceed"><span class="btnjt"></span><?php echo $text_proceed_to_checkout;?></a></span></div>
        </div>
    


    <script>

        $("#check_all").change(function(){
            if($(this).is(":checked")){
                $(".a2 input").attr("checked",true);
            }else{
                $(".a2 input").attr("checked",false);
            }

        })
        function active_like(id,error,message){

            if(error=='0'){
                $("#cart_like"+id+" .cart_like").addClass("redimg");
            }
            if(error=='2'){
                $("#cart_like"+id+" .cart_like").removeClass("redimg");
            }
        }



        function showdialog(id){
            $("a[rel=cart_nolike"+id+"]").siblings('div').show();
        }
        function hide_cart_like(id){
            $("a[rel=cart_nolike"+id+"]").siblings('div').hide();
        }

    </script>
	

<?php }else{ ?>

<div class="cart_message <?php if(!$error_info){ ?>message<?php } else{ ?>warning2<?php } ?>" <?php if($error_info){ ?>style="display:block"<?php } ?>>
    
    <?php 
        if($error_info && is_array($error_info) && count($error_info)>0){
            foreach($error_info as $item){
                echo $item."<br/>";
            }
        }
    ?>
</div>

<div style="height:100px; padding-top:50px;  font-size:18px; width: 450px; margin: 0 auto">
    <div class="left"><img src="<?php echo  STATIC_SERVER; ?>css/images/empty_cart_bg.png"/></div>
    <div class="left" style="margin-left: 20px;"><?php echo $text_empty;?>
        <p><?php echo $text_to_buy;?></p>
    </div>
</div>
<div class="clear"></div>
<?php } ?>
</section>

<script type="text/javascript">
var load_time = null;
var down_time = null;
$(".update_cart_del").live('click',function(){

		var VV = $(this).next("input").val();
        var key =$(this).next("input").attr('key');
        if(VV<=1){
            return
        }

		VV--;


        $(this).next("input").val(VV);

		var qty =VV;
		clearInterval(load_time);
         if(null != down_time){
            //停止时钟down_time
            clearInterval(down_time);
         }
           //5妙不操作就跳转
         down_time = setTimeout(function(){
		 	 $('#cart_mb').show();
            Cart.update(key,qty,update_cart);

         },1000);

	});
	$(".update_cart_add").live('click',function(){
		var VV = $(this).prev("input").val();
		VV++;
		$(this).prev("input").val(VV);
		var key =$(this).prev("input").attr('key');
		var qty =VV ;
		clearInterval(load_time);
         if(null != down_time){
            //停止时钟down_time
            clearInterval(down_time);
         }
           //5妙不操作就跳转
         down_time = setTimeout(function(){
		 	 $('#cart_mb').show();
            Cart.update(key,qty,update_cart);
         },1000);

});

$('#pro_quantity').live('change',function(){
	var qty =$(this).val();
	var key= $(this).attr('key');
	clearInterval(load_time);
         if(null != down_time){
            //停止时钟down_time
            clearInterval(down_time);
         }
           //5妙不操作就跳转
         down_time = setTimeout(function(){
		 	 $('#cart_mb').show();
            Cart.update(key,qty,update_cart);
         },1000);
})
function update_cart(json){
        refreshCart(false);
        setTimeout(function(){
            $('#cart_mb').hide();
         },1000);
		$('.mycart_table').html(json['content']);
		$('#cart_total').html(json['subtotal']);
		if(json['ship_cost']){
                       
			if(json['ship_cost']['error']==0){
				var innerHTML ='';
				for( var key in json['ship_cost']['data']){
					innerHTML +="<li><span>"+json['ship_cost']['data'][key]['delivery_type']+"</span>:<span>"+json['ship_cost']['data'][key]['format_price']+"</span></li>";
				}
				$('#ship_cost').html(innerHTML);
                                $('#ship_cost_msg').text('');
			}
			else if(json['ship_cost']['error'] == 1){
				$('#ship_cost').html('');
                                if(json['message']){
                                    $('#ship_cost_msg').text(json['ship_cost']['message']);
                                }else{
                                    $('#ship_cost_msg').text('');
                                }
			}else if(json['ship_cost']['error'] == 2){
                            $('#ship_cost').html('');
                                if(json['message']){
                                    $('#ship_cost_msg').text(json['ship_cost']['message']);
                                }else{
                                    $('#ship_cost_msg').text('');
                            }
                            window.location.reload();
                            return ;
                        }

		}
                /*
                var show_paypal = json['show_paypal'];
                if(!show_paypal){
                    $('[dom=paypal]').hide();
                }else{
                    $('[dom=paypal]').show();
                }
                */
	if(json['error']==0){
            //success
            $('.cart_message').removeClass('warning2');
            $('.cart_message').addClass('message');
            $('.cart_message').html(json['message']);
	    $('.cart_message').show();
	}
	else{
            //warming
            $('.cart_message').removeClass('message');
            $('.cart_message').addClass('warning2');
	    $('.cart_message').html(json['message']);
	    $('.cart_message').show();
	}
}

$('#remove_button').live('click',function(){
	var obj=$('.mycart_table .checked:checked');
	var key='';
	obj.each(function(){
		key+=$(this).attr('key')+',';
	})
	Cart.remove(key,update_cart);

})

//remove product
function remove(key){
	Cart.remove(key,update_cart);
}

$('#apply_coupon').live('click',function(){
	var coupon =$(this).prev('input').val();
	Cart.validateCoupon(coupon,update_cart);
})
$('#Cancel_coupon').live('click',function(){
	var coupon =$(this).prev('input').val();
	Cart.cancelCoupon(coupon,update_cart);
})
$('.proceed').click(function(){

	<?php if(!$this->customer->isLogged()){ ?>

		$('.tanchuang_box .login_form_redirect').val('<?php echo $checkout;?>');
		$('#login_tc ,.grey-bg').show();

		return false;
	<?php } ?>

})
$('#user_ship_to').live('click',function(){
	$('.ship_to').toggle();
})
$('#ship_to').live('change',function(){
        $('#ship_cost_msg').hide();
	var country_code =$(this).val();
        if(country_code == ''){
            return false;
        }
        
	$.ajax({
		url: 'index.php?route=checkout/cart/getShipCost',
		type: 'post',
		data: 'country_code=' + country_code,
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				var innerHTML ='';
				for( var key in json['data']){
					innerHTML +="<li><span>"+json['data'][key]['delivery_type']+"</span>:<span>"+json['data'][key]['format_price']+"</span></li>";
				}
				$('#ship_cost').html(innerHTML);
                                $('#ship_cost_msg').text('');
			}
			else if(json['error'] == 1){
				$('#ship_cost').html('');
                                if(json['message']){
                                    $('#ship_cost_msg').text(json['message']);
                                    $('#ship_cost_msg').show();
                                }else{
                                    $('#ship_cost_msg').text('');
                                }
			}else if(json['error'] == 2){
                            $('#ship_cost').html('');
                                if(json['message']){
                                    $('#ship_cost_msg').text(json['message']);
                                    $('#ship_cost_msg').show();
                                }else{
                                    $('#ship_cost_msg').text('');
                            }
                            window.location.reload();
                        }

		}
	});
})


</script>


<?php echo $footer; ?>

