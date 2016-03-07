$(document).ready(function(){

    $(window).scroll(function() {

        var scroll = $(window).scrollTop();
        $(".tabs-tit").each(function(index){
            if(scroll + 50 >= $(".tabs-tit").eq(index).offset().top){
                $("#tabs-list li").eq(index).addClass("active").siblings("li").removeClass("active");
            }
        })

        if(scroll > 1058){
           $("#tabs-list").addClass("fixed_tab");
        }
        if(scroll <= 1058){

            $("#tabs-list").removeClass("fixed_tab");
        }

    });

    // 没图片默认图片
    $("img").error(function () {
        $(this).addClass("nobg");
        $(this).attr("onerror","this.src='/css/images/default.gif'");
    });
    // 关闭提示框
    $(".closebtn").on("click",function(){
        $(this).parents(".jq-pop").hide();
        $(".grey-bg").remove();
    })

    $(".close").on("click",function(){
        $(".grey-bg").hide();
    })

    // 关闭提示框
    $("a[dom=closebtn]").on("click",function(){
        $(this).parents(".jq-pop").hide();
        $(".grey-bg").remove();
    })

    $(".select_box").hover(function(){
        $(".s_tap").addClass("on");
    },function(){
        $(".s_tap").removeClass("on");
    })

	$("#indexnav").mouseover(function(){
		$(".navbar").show();
	});

	$(".nav,.thirdnavbox").mouseleave(function(){
		$(".thirdnav").hide();
        $(this).find(".third-nav-img").attr("src", $(this).find(".third-nav-img").attr("_src"));
		$(".navbar a").removeClass("active");
		$(".navbar").removeClass("shadownone");
	});
	
	$("#infornav").hover(function(){
		$(".navbar").show();	
	},function(){
		$(this).find(".first_link a").removeClass("active");
		$(".navbar").hide();
		$(".thirdnav").hide();	
		$(".navbar a").removeClass("active");
		$(".navbar").removeClass("shadownone");
	})
	
	$(".navbar a").hover(function(){
		$(".first_link a").addClass("active");
                var dom = $(this).attr('dom');
               
		var Index = $(this).parent("li").index();
		$(".navbar a").removeClass("active");
		$(this).addClass("active");
		$(".navbar").addClass("shadownone");
		$(".thirdnav").show();
                $(".thirdnav>section").hide();
                if($('.thirdnav>section[dom=' + dom + ']')){
                    $('.thirdnav>section[dom=' + dom + ']').show();
                }
	});

	//
	$(".prolist li").hover(function(){
		$(this).find(".img_Text").animate({"bottom":"0"},"fast");	
	},function(){
		$(this).find(".img_Text").animate({"bottom":"-25px"},"fast");	
	})
	
	//
	$(".return-top").click(function(){
		$(window).scrollTop(0);
	})
	
	$(".helpicon").live('hover',function(){
		$(this).next("div").toggle();	
	});
	
	$(".tanchuan ul.tabs-list li").on("click",function(){
		var Index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".tanchuang_box .form").eq(Index).show().siblings(".form").hide();
	})

	$(".clubtit a").click(function(event){
		event.preventDefault();
		$(".xia_sj_gray").toggleClass("active");
		$(".acc_details_table").toggle();
	});
	
	$("#cop").live('click',function(){
		$(".coupon").toggle();	
	});	

    $(".Recent_History .tabs-list li").click(function(){
		var Index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(this).parent("ul").parent(".Recent_History").children(".flexslider").show().eq(Index).css("visibility","visible").siblings(".flexslider").hide().css("visibility","hidden");
		$(this).parent("ul").parent(".Recent_History").children(".account_table").children("section").show().eq(Index).css("visibility","visible").siblings("section").hide().css("visibility","hidden");
	})
  
	$(".giftbox .giftit .close").click(function(){
		$(this).parent().parent(".giftbox").hide();
	})

    $('.menu_slide').hover(function() {
		$(this).find('.menu_body').show();
	}, function() {
		$(this).find('.menu_body').hide();
	});

	$("#quantity-dec").click(function(){
		var VV = $(this).next("input").val();
        if(VV<=1){
            return;
        }
		VV--;
        $(this).next("input").val(VV);
		var product_id =$('#product_id').val();
		if(VV>=0){
			$.ajax({
				url: 'index.php?route=product/product/get_subtotal',
				type: 'post',
				data: 'product_id=' + product_id + '&quantity=' + VV,
				dataType: 'json',
				success: function(json) {
                                        if(json['error']){
                                            
                                        }else{
                                             $('#product-tips').hide();
                                             $('#quantity-inc').attr('disabled',false);
                                        }
					$('#subtoal').html(json['subtotal']);
				}
			});
		}
	})

	$("#quantity-inc").click(function(){
		var VV = $('#pro_qty').val();
		VV++;
		$('#pro_qty').val(VV);
		var product_id =$('#product_id').val();
		if(VV>=0){
			$.ajax({
				url: 'index.php?route=product/product/get_subtotal',
				type: 'post',
				data: 'product_id=' + product_id + '&quantity=' + VV,
				dataType: 'json',
				success: function(json) {
                                        if(json['error'] == 1){
                                            $(':input[name=quantity]').val(json['qty']);
                                            $('#product-tips').html(json['msg']);
                                            $('#product-tips').show();
                                            $('#quantity-inc').attr('disabled',true);
                                        }else{
                                             $('#product-tips').hide();
                                             $('#quantity-inc').attr('disabled',false);
                                        }
					$('#subtoal').html(json['subtotal']);
				}
			});
		}
	});
	
	$("#pro_qty").blur(function(){
		var VV = $(this).val();
		if(VV<0){
			$(this).val(0);
		}
		var product_id =$('#product_id').val();
			//计算价格
		if(VV>=0){
			
			$.ajax({
				url: 'index.php?route=product/product/get_subtotal',
				type: 'post',
				data: 'product_id=' + product_id + '&quantity=' + VV,
				dataType: 'json',
				success: function(json) {
				      if(json['error'] == 1){
                                            $(':input[name=quantity]').val(json['qty']);
                                            $('#product-tips').html(json['msg']);
                                            $('#product-tips').show();
                                            $('#quantity-inc').attr('disabled',true);
                                        }else{
                                             $('#product-tips').hide();
                                             $('#quantity-inc').attr('disabled',false);
                                        }
					$('#subtoal').html(json['subtotal']);
				}
			});
		}
		
	})

	if($(".cart-number").text()>0){
		$(".empty-cart").hide()
	}else if($(".cart-number").text()<0){
		$(".minicart").hide()	
	}


    // 购物车代码
    $("#cart").hover(function(){
            $(".cart-title").addClass("hovered");
            $(".sub-cart").show();
            //refreshCart(false);
            //console.log(1);
    },function(){
        $(".cart-title").removeClass("hovered");
        $(".sub-cart").hide();

    });

	$(".currency li").hover(function(){
		$(this).children(".money").show();
	},function(){
		$(this).children(".money").hide();
	})
	
	$(".Account").hover(function(){
		$(".Account>a").addClass("hovered")
		$(this).children(".Account-panel").show();
	},function(){
		$(".Account>a").removeClass("hovered")
		$(this).children(".Account-panel").hide();
	})


        
    //search----------------------------------------------------------------------------------
       var $inp = $('#search'); //所有的input元素
        $inp.keypress(function (e) { //这里给function一个事件参数命名为e，叫event也行，随意的，e就是IE窗口发生的事件。
            var key = e.which; //e.which是按键的值
            if (key == 13) {
                url = '';

                var search = $('input[name=\'search\']').attr('value');
                search = search.replace(/[\`\~!\@#\$%\^&*()_+=|\\\{\}\[\];:"'<,>.?\/]/gm,' ');
                search = search.replace(/^\s+|\s+$/gm,' ');
                
                if (search.length>=2) {
                    //search = search.replace(/\s+|\s+/gm,'+');
                    var url_search = '';
                    var search_keyword = '';
                    var search_words = search.split(' ');
                    for(var i=0;i<search_words.length; i++){
                        word = search_words[i];
                        if(word !='' && word!= ' '){
                            search_keyword +=  encodeURIComponent(word) + '+';
                            
                        }
                    }
                    
                    search_keyword = search_keyword.replace(/\++/g,'+');
                    search_keyword = search_keyword.substr(0,search_keyword.length-1);
                    
                    url = '/s/' + search_keyword + '.html' ;
                    window.location.href = url;
                }
            }
        });

        $('#search_button').click(function () {
            url = '/index.php?route=product/search';

            var search = $('input[name=\'search\']').attr('value');
            search = search.replace(/[\`\~!\@#\$%\^&*()_+=|\\\{\}\[\];:"'<,>.?\/]/g,' ');
            search = search.replace(/^\s+|\s+$/gm,' ');
            
            
            if (search.length>=2) {
                //search = search.replace(/\s+|\s+/gm,'+');
                var url_search = '';
                var search_keyword = '';
                var search_words = search.split(' ');
                for(var i=0;i<search_words.length; i++){
                    word = search_words[i];
                    if(word !='' && word!= ' '){
                        search_keyword +=  encodeURIComponent(word) + '+';
    
                    }
                }
                
                search_keyword = search_keyword.replace(/\++/g,'+');
                search_keyword = search_keyword.substr(0,search_keyword.length-1);
                
                url = '/s/' +  search_keyword + '.html' ;
                window.location.href = url;
            }
        })
        /*autocomplete函数
        1)获取txtKey中用户输入的值(用户每输入一个字符，都会获取一次)
        2)将获取的值和array集合中的元素进行比较，找出匹配的元素，并且显示出来
        3)会将用户选择的项添加到txtKey中。
        */
        $('#search').autocomplete('/index.php?route=product/search/suggest',{
            minChars: 1,        //至少输入的字符数，default：1；
            max: 10,            //下拉项目的个数，default：10
            scrollHeight: 300,    // 下拉框的高度， Default: 180 
            scroll: true,        //当结果集大于默认高度时，是否使用滚动条，Default: true
            multiple: false,    //是否允许输入多个值. Default: false
            selectFirst:false
    }).result(function (event, data) {
            //result函数：对用户选择的结果进行操作。data参数表示用户选择的项
            //result当回车或单击一个按钮时能返回一个值
            //第一个函数even当参数使用，不起作用
            //第二个函数data就是当前选中text中的选中值

            $('#search').html(data); 
            $('#search_button').click();
        });

});

// 设置按钮加载loadding
function setBtnLoad(that,text){
    if(that.hasClass("disabled_btn")){
        return true
    }else{
        that.addClass("disabled_btn");
        if(text){
            text.text("processing...");
        }
        return false
    }


}


//加入购物车
function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;
	//商品页面加入购物车
        var ship_to = '';
        if($('#ship_to').size()){
            var ship_to = $('#ship_to').val();
            if(ship_to == ''){
                $('#ship_to').focus();
                return false;
            }
            if($('.add-to-cart').size()){
                if($('.add-to-cart').hasClass('disabled-cart')){
                    return false;
                }
            }
        }
        if($('#pro_qty').size()){
            if($('#pro_qty').val() <= 0){
                $('#pro_qty').focus();
                return false;
	       }
           quantity =   $('#pro_qty').val();
            
        }
        if($('#product-tips').size()){
            $('#product-tips').hide();
        }

    // 列表添加购物车提示
    if($("#animation_"+product_id)){
        animation(product_id);
    }

    // 列表添加购物车提示
    if($("#animation_list_"+product_id)){
        animation("list_"+product_id);
    }
	$.ajax({
	url: 'index.php?route=checkout/cart/add',
	type: 'post',
	data: 'product_id=' + product_id + '&quantity=' + quantity + '&ship_to='+ship_to,
	dataType: 'json',
	success: function(json) {
            if(!json['error']){
                $('#cart-total').html(json['total_num']);
                $('#cart-price-total').html(json['total_price']);
                // 详情购物车提示
                if(typeof(show_pop) != "undefined" ){
                    show_pop(json['total_price'],json['add_qty'],json['total_num']);
                }
				//deals 页面
				
				if($('#deals_add_cart').size()>0){
					show_pop_deal(product_id,json['total_price'],json['add_qty'],json['total_num']);	
				}


                refreshCart(false);
		    }
		else{
                    if(json['error']){
                        $('#product-tips').html(json['error']);
                        $('#product-tips').show();
                    }
		
		}
		
		
	}
	});
}

// 删除购物车
function deleteCart(key) {
	key = typeof(key) != 'undefined' ? key : '';
	if(key!=''){
		$.ajax({
			url: "index.php?route=module/cart&remove="+key,
			type: 'get',
			dataType: 'text',
			success: function(text) {
				$('#cart').html(text);
                $(".cart-title").addClass("hovered");
                $(".sub-cart").show();
			}
		});	
	
	}
	
}

// 添加收藏
function addToWishList(product_id) {
	if(!is_login()){
		$('#login_tc').show();
        $('.grey-bg').show();
	}
	else{
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				if(json['error']==0){
					if (json['message']) {
                        if(typeof(active_like) != "undefined"){

                            active_like(product_id,json['error'],json['message']);
                        }

						//$('#notification').html(json['message'] + '<img src="css/images/close.png" alt="" class="close" />');
						//$('#notification').show();
                        if(typeof(wish_pop) != "undefined"){
                            wish_pop(json['error'],json['message']);
                        }

					}
		
				}
				else{
                    if(typeof(active_like) != "undefined"){

                        active_like(product_id,json['error'],json['message']);
                    }
                    if(typeof(wish_pop) != "undefined"){
                        wish_pop(json['error'],json['message']);
                    }

					
					//$('.cart_like').removeClass("redimg");
				}
			}
		});
	}
}

// 添加遮罩层
function showMask(){
    $(document).ready(function(){
        if($("#mask").length <= 0){
            $("body").prepend("<div id='mask' class='grey-bg' style='text-align: center;'><span><img  style='margin-top: 20%' src='css/images/loader_32x32.gif' width='32' height='32'  original='css/images/loader_32x32.gif'></span></div>");
        }

    });
}

// 隐藏遮罩层
function hideMask(){
    if($("#mask").length > 0){
        $("#mask").remove("");
    }
}

// 添加比较
function addToCompare(product_id){
	$.ajax({
	url: 'index.php?route=product/compare/add',
	type: 'post',
	data: 'product_id=' + product_id,
	dataType: 'json',
	success: function(json) {
	$('.success, .warning, .attention, .information').remove();
	if (json['success']) {
	$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="css/images/close.png" alt="" class="close" /></div>');
	$('.success').fadeIn('slow');
	$('#compare-total').html(json['total']);
	$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
	}
	});
}

/*登录注册JS判断 */
function checkMail(str){
    var reg = /^(.)*@[\s\S]+((\.\w+)+)$/;
    return reg.test(str);
}

/*关闭窗口 */
function closePop(){
    $(".blkbg,.tanchuan,.grey-bg ").hide();

}


/*判断用户是否登录 */
function is_login(){

	var login =$('#if_login').val();
	if(login==1){
		return true;
	}
	else{
		return false;
	}
}

/* 刷新购物车  */
function  refreshCart(show){
    $.ajax({
        url: 'index.php?route=module/cart&ajax=1',
        type: 'post',
        dataType: 'json',
        success: function(data) {
            $("#cart-total").text(data.qty);
            $("#cart-price-total").text(data.total);
            $("#cart-list").html(data.detail);
            if(show){
                $(".cart-title").addClass("hovered");
                $(".sub-cart").show();
            }
        }
    });
}


/* 购物车动画效果     <div class="animation"></div> */
function animation(id){
    var box = $("#animation_"+id);
    var imgUrl = $("#animation_img_"+id).attr("src");
    if(imgUrl == "" || typeof(imgUrl) == "undefined"){
        return;
    }
    box.html("<img src="+ imgUrl +"  class='animation' />");
    $("#animation_"+id).children(".animation").show();
    var obj = $("#animation_"+id).children(".animation");

    obj.css({
        "position": "absolute",
        "z-index": "500",
        "left": box.offset().left,
        "top": box.offset().top
    });
    obj.animate({
            "left": ($("#cart").offset().left
                - $("#cart").width())+"px",
            "top": ($(document).scrollTop()+30)+"px",
            "width": "80px",
            "height": "80px"
        },
        500,
        function() {

            obj.animate({
                "left": $("#cart").offset().left+"px",
                "top": $("#cart").offset().top+"px",
                "width": "50px",
                "height": "50px"
            },500).fadeTo(0, 0.2).hide(0);
        });

}




