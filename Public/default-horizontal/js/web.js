require.config({
    baseUrl:'js/',
    paths:{
        "jQuery":"jquery/jquery",
        "SuperSlide":"jquery/jquery.SuperSlide.2.1.1",
        "common":"common",
        "lazyload":"jquery/jquery.lazyload",
        "autocomplete":"jquery/jquery.autocomplete",
        "validform":"jquery/validform",
        "address":"Address",
        "flexslider":"jquery/jquery.flexslider",
        "mzppacked":"mzp-packed/mzp-packed",
        "lrtk":"mzp-packed/lrtk",
        "countdown":"jquery/jquery.countdown"
    }
});

require(['jQuery','SuperSlide'],function(jQuery,SuperSlide){
    $(".slideBox").slide({mainCell:".bd ul",autoPlay:true,trigger:"click",switchLoad:"_src",delayTime:1000 });

});

require(['jQuery','lazyload'],function(jQuery,lazyload){

});

require(['jQuery','validform'],function(jQuery,validform){

});

require(['jQuery','address'],function(jQuery,validform){

});



require(['jQuery','flexslider'],function(jQuery,validform){

});
