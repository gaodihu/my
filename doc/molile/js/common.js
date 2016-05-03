

var common ={
	/* 初始化 */
	init:function(){
		// 默认浏览窗口高度
		var hg = $(window).height();
		$("body,html,.bgcolor").css({"min-height":hg});
        // 本地查询列表数据加载
        this.getlocalList();
        // 默认静态购物车数据4个
        $(".menu").find(".icon-shopping-cart").html("<span>4</span>");
	},
	/* banner滑屏幕 */
	sliding_event:function(id){
		 /* banner */
            var slider =  Swipe(document.getElementById(id), {
                auto: 4000,
                continuous: true,
                callback: function(pos) {
                    var bullets = document.getElementById('position').getElementsByTagName('i');
                    var i = bullets.length;
                    while (i--) {
                        bullets[i].className = ' ';
                    }
                    bullets[pos].className = 'on';

                }
            });

	},
	/* 倒计时  type 如果 day 带日期显示  */
	timer:function(id,time,type){
		var sh =setInterval(function(){
        var day=0,
            hour=0,
            minute=0,
            second=0;//时间默认值
        if(time > 0){
            day = Math.floor(time / (60 * 60 * 24));
            hour = Math.floor(time / (60 * 60)) - (day * 24);
            minute = Math.floor(time / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(time) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        if(type == "day"){
            $("#"+id).html("<b>"+day+" </b>days  <b>  "+hour+"</b> : <b>"+minute+"</b> : <b>"+second +"</b>");
        }else{
            $("#"+id).html("<b>"+day+" </b> : <b>  "+hour+"</b> : <b>"+minute+"</b> : <b>"+second +"</b>");
        }
       // console.log(time);
        if(time<=0){
            clearInterval(sh);
        }
        time--;
		}, 1000);
	},
	
	/* 筛选切换 */
	filter_tab:function(index_id,id){
		$("#"+index_id).hide();
		$("#"+id).show();
		
	},
	
	// 获取查询列表本地数据
	 getlocalList:function(){
		if(window.localStorage.getItem("query-list-data") != null){
			var obj =  JSON.parse(window.localStorage.getItem("query-list-data"));
			$.each( obj.arry, function(index, content)
			{
				$(".local-data .query-list").append("<li><a href='product_list.html'>"+content+"</a></li>")
			})
			//console.log( window.localStorage.getItem("query-list-data"));
		}
	},

	// 提交查询
	 search_input:function(){
		var serch = $(".search-input").val(),lo_data=window.localStorage.getItem("query-list-data");

		if(serch==""){
			return false;
		}
		if(lo_data != null){
			var obj = JSON.parse(lo_data);
			obj.arry.push(serch);
			window.localStorage.setItem("query-list-data",JSON.stringify(obj));
			console.log( window.localStorage.getItem("query-list-data"));
		}else{
			var obj = {"arry":[]};
			obj.arry.push(serch);
			console.log(obj);
			window.localStorage.setItem("query-list-data",JSON.stringify(obj));
			//console.log( window.localStorage.getItem("query-list-data"));
		}
		$(".queryform").submit();
		//window.localStorage.setItem("query-list-data",value);
		// window.localStorage.getItem("query-list-data");
	},

	// 弹出层插件
	alertInfo:function(msg){
		if($(".grey-bg-z").length == 0){
			 $(".bgcolor").append("<div class='grey-bg-z'></div>");
		}
			$(".grey-bg-z").show();
			$(".bgcolor").append("<div class='alertinfo'><span>"+msg+"</span></div>");
			setTimeout(function(){
				$(".grey-bg-z").hide();
				$(".alertinfo").hide();
			},2000)
	},

	// 隐藏主体
	hideBody:function(){
		var hg = $(window).height();
		$("body,html").css({height:hg+"px",overflow:"hidden"});
	},

	// 显示主体
	showBody:function(){
		$("body,html").css({height:"auto",overflow:"auto"});
	}
}

$(document).ready(function(){
    common.init();

    // 查询按钮
       $(".search-input").on("keydown",function() {
           if ($(this).val() == "") {
               $(".local-data").show();
               $(".query-data").hide();
           } else {
               $(".query-data").show();
               $(".local-data").hide();
           }
       })
    // 首页导航查询按钮
	$(".menu .icon-search").click(function(){
        var hg = $(window).height();
        $(".query-page").css({"height":hg});
        $(".query-page").show();
        common.hideBody();
	});
    $(".query-page .cancel").click(function(){
        $(".query-page").hide();
        common.showBody();
    });
    // 筛选关闭
    $(".cont .close").click(function(){
        $(this).parents(".pop-filter").hide();
        $(".grey-bg").hide();
        $(".fixed-btn").show();
        common.showBody();
    })
    // 筛选
    $(".filter").click(function(){
        var hg = $(window).height();
        $(".pop-filter").find(".cont").css({"height":hg});
        $(".pop-filter").addClass("open");
        $("#filter").show();
        $(".grey-bg").show();
        common.hideBody();
    })
    $(".categories-list .price").click(function(){
        $(".pop-price").show();
    })
    $(".categories-list .reviews").click(function(){
        $(".pop-reviews").show();
    })
    $(".pop-left").click(function(){
        $(this).parents(".pop-filter").hide();
    })
    // 详情页面标题切换
    $(".tit-tab").click(function(){
        $(this).siblings(".tit-tab").removeClass("active");
        $(this).addClass("active");
        $(".details-content").hide();
        $("#"+$(this).attr("rel")).show();
    })
    // 个人中心订单筛选
    $(".order-filter").click(function(){
        $("#filter-order").show();
        $(".grey-bg").show();
    })
    // 订单筛选
    $(".order-filter-close").click(function(){
        $("#filter-order").hide();
        $(".grey-bg").hide();
    })
    // 回退
	$(".icon-angle-left").click(function(){
		window.history.go(-1);
	})
    // 产品详细爱心
    $(".product-info .p-heart").click(function(){
            $(this).addClass("red-bg");
    })
    // 购物车爱心
    $(".cart-list .p-heart").click(function(){
            $(this).addClass("red-color");
    })
    // 支付展示详情
    $(".tab-active .more-info").click(function(){
        var img = $(this).find("span");
        if(img.attr("class") == "icon-angle-down"){
            img.attr("class","icon-angle-up");
        }else{
            img.attr("class","icon-angle-down");
        }
            $(this).parents("li").find(".more-box").toggle();
        event.stopPropagation();
    })
    // 添加购物车
    $(".product  .addcart .orange-bg").click(function(event) {
        common.alertInfo("Add Sucess");
        return false;
        event.stopPropagation();
    })
    // 删除购物车
    $(".product  .addcart .del").click(function(event) {
        $(this).parents("li").remove();
        if($(".my-wish li").length == 0){
            $(".msg-info").show();
        }
        return false;
        event.stopPropagation();
    })
    // 支付页列表激活效果
    $(".tab-active li").click(function(){
        var img = $(this).find("b");
            img.parents(".tab-active").find("b").attr("class","icon-check-empty");
            img.attr("class","icon-check");
        if(!$(this).is(".t-title")){
            $(this).addClass("active").siblings("li").removeClass("active");
        }
    })
    // 地址删除
    $(".address-list .address-del").click(function(){
            $(this).parents("li").remove();
    })
    // 列表页点击加载数据
    $(".lodding .active").click(function(){
        var me = $(this);
        me.toggleClass("active");
        me.find("i").addClass("icon-spinner") .removeClass("icon-double-angle-down");
        setTimeout(function(){
            me.find("i").addClass("icon-double-angle-down") .removeClass("icon-spinner");
            innerProduct();
        },1000)
    })
    // 筛选二级分类
    $(".filter-box .filter-list .list-change").click(function(){
        var me = $(this);
        $(".filter-box .filter-list .list-change").removeClass("active");
        $(".filter-box .filter-list .second-list").hide();
        me.addClass("active");
        me.parents("li").find(".second-list").show();
    })
    // 筛选二级分类
    $(".product-change .sortby").click(function(){
         $(".grey-bg").show();
         $(".sortby-list").show();
    })
    // 筛选点击层隐藏
    $(".canel-bg,.on-cancel").click(function(){
        $(this).hide();
        $(".confirmation").hide();
        $(".sortby-list").hide();
    })
    // 筛选点击层隐藏
    $(".product-change .sortby-list li").click(function(){
        $(this).parents(".product-change").find("#by-info").text($(this).find("a").text());
        $(this).addClass("active").siblings("li").removeClass("active");;
        $(".grey-bg").hide();
        $(this).parents(".sortby-list").hide();
    })
    // 过滤二级菜单
    $(".filter-box .second-list li").click(function(){
            $(this).parents(".second-list").find(".icon-ok").remove();
            $(this).append("<span class='icon-ok green-color'></span>");
    })
    //导航更多列表
    $(".icon-list").click(function(){
        var hg = $(window).height();
        $(".all-categories-page").css({"height":hg});
        $(".all-categories-page").addClass("open");
        $(".grey-bg").show();
        $(".product-change").css({"z-index":1});
        common.hideBody();
    })
    //导航模块点击取消
    $(".on-cancel ,.all-categories-page .icon-arrow-left").click(function(){
        $(".all-categories-page").removeClass("open");
        $(".grey-bg").hide();
        $(".product-change").css({"z-index":601});
        common.showBody();
    })

    // 筛选点击
    $(".head-title .icon-align-left ,.icon-th-large").click(function(){
        $(".product .con-box").toggleClass("list-css");
        if($(this).hasClass("icon-align-left")) {
            $(this).removeClass("icon-align-left").addClass("icon-th-large");
        }else{
            $(this).removeClass("icon-th-large").addClass("icon-align-left");
        }
    })

    // 筛选点击
    $(".all-categories-page .all-list li a").click(function(){
        if($(this).hasClass("to-language")){
            $(".all-list").hide();
            $("#currency").hide();
            $("#language").show();
        }
        if($(this).hasClass("to-currency")){
            $(".all-list").hide();
            $("#language").hide();
            $("#currency").show();
        }
    })

    // 首页语言选择
    $("#currency li,#language li").click(function(){
        var type =$(this).parents(".order-page").attr("id");
        if(type == "language"){
            $(".all-list").find(".to-language span").text($(this).text());
        }else{
            $(".all-list").find(".to-currency span").text($(this).text());
        }
        $(this).addClass("green-color").siblings("li").removeClass("green-color");
        $(this).parents(".order-page").hide();
        $(".all-list").show();
    })

    // 菜单导航语言和价格类型关闭返回主要列表
    $(".all-categories-page h4 .icon-remove").click(function(){
        $(this).parents(".order-page").hide();
        $(".all-list").show();

    })
    // 查询框清除本地缓存
    $(".query-page .clearbtn").click(function(){
        window.localStorage.removeItem("query-list-data");
        $(".local-data .query-list").html("");
    })

});


