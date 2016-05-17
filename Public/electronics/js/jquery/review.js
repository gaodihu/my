$(function(){
    $(".spec-scroll .items ul li").hover(function(){
        var spec_preview_img = $(this).parents(".spec-scroll").find(".spec-preview .jqzoom img");
        spec_preview_img.attr("src",$(this).find("img").attr("bimg"));
        spec_preview_img.attr("jqimg",$(this).find("img").attr("bimg"));

    },function(){


    })

    $(".spec-scroll .items ul li").click(function(){
        $(this).parents(".spec-scroll").find(".spec-preview").fadeIn();
        $(this).parents(".spec-scroll").find(".spec-close-box").fadeIn();
    })

    $(".spec-close-box").click(function(){
        $(this).parents(".spec-scroll").find(".spec-preview").fadeOut();
        $(this).parents(".spec-scroll").find(".spec-close-box").fadeOut();
    })

    $(".spec-scroll").each(function(){
        imgReview($(this));
    });

    $(".spec-preview").click(function(){
       if($(".wrap").width()>=1200){
           $(this).find("img").animate({"width":"720px"});
           $(this).next(".spec-close-box").animate({"width":"720px"});
       }else{
           $(this).find("img").animate({"width":"570px"});
           $(this).next(".spec-close-box").animate({"width":"570px"});
       }

    });

    $(document).click(function(e){

        var e=e?e:window.event;
        var tar = e.srcElement||e.target;
        if(tar.className!="spec-close-box"){
            if($(".spec-close-box").width() >= 570){
                $(".spec-preview img").animate({"width":"352px"});
                $(".spec-close-box").animate({"width":"352px"});
            }
        }
    });


})



//图片预览小图移动效果,页面加载时触发

function imgReview(obj){
	var tempLength = 0; //临时变量,当前移动的长度
	var viewNum = 5; //设置每次显示图片的个数量
	var moveNum = 2; //每次移动的数量
	var moveTime = 300; //移动速度,毫秒
	var scrollDiv = obj.find(".items ul"); //进行移动动画的容器
	var scrollItems = obj.find(".items ul li"); //移动容器里的集合
	var moveLength = scrollItems.eq(0).width() * moveNum; //计算每次移动的长度
	var countLength = (scrollItems.length - viewNum) * scrollItems.eq(0).width(); //计算总长度,总个数*单个长度

	//下一张
    obj.find(".next").on("click",function(){
		if(tempLength < countLength){
			if((countLength - tempLength) > moveLength){
				scrollDiv.animate({left:"-=" + moveLength + "px"}, moveTime);
				tempLength += moveLength;
			}else{
				scrollDiv.animate({left:"-=" + (countLength - tempLength) + "px"}, moveTime);
				tempLength += (countLength - tempLength);
			}
		}
	});
	//上一张
    obj.find(".prev").on("click",function(){
		if(tempLength > 0){
			if(tempLength > moveLength){
				scrollDiv.animate({left: "+=" + moveLength + "px"}, moveTime);
				tempLength -= moveLength;
			}else{
				scrollDiv.animate({left: "+=" + tempLength + "px"}, moveTime);
				tempLength = 0;
			}
		}
	});
}
$('.review-condition').live('click',function(){
	//是否登录
	if(is_login()==0){
		$('#login_tc').show();
		return false;
	}
	var condition =$(this).attr('condition');
	var review_id = $(this).attr('rel');
	var id=condition+'-'+review_id;
	var  num =parseInt($(this).find('#'+id).html())+1;
	$.ajax({
		url: 'index.php?route=product/product/supportreview',
		type: 'get',
		data: 'review_id='+review_id+"&num="+num+'&condition='+condition,
		dataType: 'json',
		success: function(json) {
			if(json['error']==0){
				$('#'+id).html(json['content']);
			}
		},
		error: function (xhr, type, exception) {
		      //获取ajax的错误信息
             alert(xhr.responseText, "Failed");
         }
	});
})
