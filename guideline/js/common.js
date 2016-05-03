

$(function(){
	//APPLICATIONS  二级菜单
			 $(".nav li").hover(function(){
				$(this).find(".nav-apl").show();
			 },function(){
				$(this).find(".nav-apl").hide();
				$(this).find(".nav-apl").siblings(".nav-apl-link").hide();
			 });
			  $(".nav li .nav-apl a").hover(function(){
				$(this).parent(".nav-apl").siblings(".nav-apl-link").find(".nav-box").hide();
				$(this).parent(".nav-apl").siblings(".nav-apl-link").show();
				$(this).parent(".nav-apl").siblings(".nav-apl-link").find(".nav-box").eq($(this).index()).show();
			 });
		 
    // 移动图标
    $(".img-list li").hover(function(){
        $(this).addClass("active");
    },function(){
        $(this).removeClass("active");
    })

    // 点击列表
    $(".appli li .imgs").on("click",function(){
        $(this).parent("li").toggleClass("active");
        $(this).parent("li").find(".appli-link").toggle();
    })

    $(".tabli").hover(function(){
        $(this).addClass("active-tab");
        $(".content-tab").show();
    },function(){
        $(this).removeClass("active-tab");
        $(".content-tab").hide();
    })
   
 });



