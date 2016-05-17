function getId(e) {return document.getElementById(e);}
document.getElementsByClassName = function(cl) {
    var retnode = [];
    var myclass = new RegExp('\\b'+cl+'\\b');
    var elem = this.getElementsByTagName('*');
    for (var i = 0; i < elem.length; i++) {
        var classes = elem[i].className;
        if (myclass.test(classes)) retnode.push(elem[i]);
    }
    return retnode;
}
var MyMar;
var speed = 1; //速度，越大越慢
var spec = 1; //每次滚动的间距, 越大滚动越快
var ipath = 'css/images/product/'; //图片路径
var thumbs = document.getElementsByClassName('thumb_img');
for (var i=0; i<thumbs.length; i++) {
    thumbs[i].onmouseover = function () {getId('main_img').src=this.rel; getId('main_img').link=this.link;};

}
/*
getId('gotop').onmouseover = function() {this.src = ipath + 'gotop2.gif'; MyMar=setInterval(gotop,speed);}
getId('gotop').onmouseout = function() {this.src = ipath + 'gotop.gif'; clearInterval(MyMar);}
getId('gobottom').onmouseover = function() {this.src = ipath + 'gobottom2.gif'; MyMar=setInterval(gobottom,speed);}
getId('gobottom').onmouseout = function() {this.src = ipath + 'gobottom.gif'; clearInterval(MyMar);}
*/
function gotop() {getId('showArea').scrollTop-=spec;}
function gobottom() {getId('showArea').scrollTop+=spec;}
$("#showArea a img").click(function(){
    $("#showArea a img").removeClass("on");
    $(this).addClass("on");

})


$("#showArea a img").eq(0).addClass("on");
$("#zoom1").click(function(event) {
    return false;//阻止链接跳转
});

