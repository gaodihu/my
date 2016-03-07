// 滚动到底部加载
$.pagescroll = {
    check:true,
    addHTML:"",
    cls:null,
    index:3,
    ajax_fn:function(){

    },
    // 初始化
    init:function(cls) {
        this.cls = cls;
        $(".lodding").show();
        $(window).bind('scroll',function(){$.pagescroll.show()});
    },
    // 加载
    show:function() {

        if($(window).scrollTop()+$(window).height()+200>=$(document).height()){
            $.pagescroll.ajaxRead();
        }
    },
    ajaxRead:function() {

        if($.pagescroll.check){
            $.pagescroll.check = false;
            $('.lodding').show();
            $.pagescroll.ajax_fn();

        }
    },
    // 填充数据
    setHtml:function(data) {
        if(data == 0){
            $.pagescroll.loadedEnd("LOAD END");
            return;
        }
        $(".lodding").hide();
        $(this.cls).append(data);
        $.pagescroll.check = true;
    },
    // 加载结束
    loadedEnd:function(msg){
        $('.lodding').html(msg);
        $('.lodding').addClass("loadend");
    },
    // 加载错误
    error:function(msg){
        $.pagescroll.check = false;
    }
};

$.scrollbtn={
    init:function(){
        $(".bgcolor").append('<a class="top-bg radius-btn clickbg" onclick="$(window).scrollTop(0)" ><i class="icon-arrow-up"></i></a><a class="fix-cart radius-btn clickbg" href="shopping_cart.html"><i class="icon-shopping-cart"></i></a>');

        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if(scroll > 50){
                $(".top-bg, .fix-cart").show();
            }else if(scroll < 50){
                $(".top-bg, .fix-cart").hide();
            }
        });
    }
}

$.popConfirm={
    yesfn:function(){

    },
    show:function(title){
      var html="";

        html="<section class='confirmation'>"+
            "<h4>"+title+"</h4>"+
            "<div class='confirm-btn'>"+
                "<a class='yes orange-bg' onclick='$.popConfirm.yesfn()'>yes</a>"+
                "<a class='no grey-bg-btn' onclick='$.popConfirm.hide()'>No</a>"+
            "</div>"+
        "</section>"

        $("body").append(html);
        $(".grey-bg").show();
    },
    hide:function(){
        $(".confirmation").hide();
        $(".grey-bg").hide();
    }
}