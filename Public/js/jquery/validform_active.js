// 表单验证
$(function(){
    //文本框失去焦点后
	$(document).on('blur', ".form input,.form select", function() {

        var verify = $(this).attr("verify");
        var $parent = $(this).parent();
        var okMsg = '<div class="formtipsa onSuccess">Correct.</div>';
        $(this).next(".formtipsa").remove();
        //不为空
        if(verify == "notnull"){
            if( this.value=="" || this.value==" "){
                var errorMsg = 'Not Null.';
                errorBorder($(this),errorMsg);
            }else{
                //$parent.append(okMsg);
                 successBorder($(this));
            }
        }


		
		 // 图片
        if(verify == "img-notnull"){
			
             if( this.value=="" || this.value==" "){
                errorImg($(this));
            }else{
                 successImg($(this));
            }
        }

        //地址
        if(verify == "address"){
            if(this.name == 'address_2' && this.value == ''){
                //address_2 可以为空
            } else{
                if( this.value.length <3 || this.value.length>128 ||  this.value=="" ){
                    var errorMsg = 'The length of the input 3 ~ 128.';
                    errorBorder($(this),errorMsg);
                }else{
                    successBorder($(this));
                }
            }
        }
        //城市
        if(verify == "city"){
            if( this.value.length <2 || this.value.length>128 ||  this.value=="" ){
                var errorMsg = 'The length of the input 2 ~ 128.';
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }
        //邮箱
        if( verify == "email"){
            if( this.value=="" || (!checkMail(this.value)) ){
                var errorMsg = 'Please enter a correct E-mail address';
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }
        //数字
        if( verify == "number"){
            if( this.value=="" || ( this.value!="" &&  !/["^\d]/g.test(this.value) ) ){
                var errorMsg = "Only Numbers and isn't empty";
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }
        /*只能输入  数字 ，字母 ，中文
        if( !/^[a-zA-Z0-9\u4e00-\u9fa5]+$/.test(this.value) && this.value!="" ){
            if(verify == "user" || verify == "address" || verify == "city"){
                $(this).next(".formtipsa").remove();
                var errorMsg = 'Input format is wrong';
                errorBorder($(this),errorMsg);
            }
        }*/
    }).keyup(function(){
       $(this).triggerHandler("blur");
    }).focus(function(){
       // $(this).triggerHandler("blur");
    });//end blur

  

    //重置
    $('.res').click(function(){
        $(".formtipsa").remove();
    });
})

// 其他提示语句加入  实例： $.valiform.setSuccessMsg("firstname","asd")
$.valiform={
    setSuccessMsg:function(name,succeedMsg){
           var me = $("[name="+name+"]") , $parent = me.parent();
           me.next(".formtipsa").remove();
           $parent.append('<div class="formtipsa onSuccess">'+succeedMsg+'</div>');
           successBorder(me);

    },
    setErrotMsg:function(name,errorMsg){
            var me = $("[name="+name+"]") , $parent = me.parent();
            me.next(".formtipsa").remove();
            errorBorder(me,errorMsg);
    },
    defaultMsg:function(name,Msg){
        var me = $("[name="+name+"]") , $parent = me.parent();
        me.next(".formtipsa").remove();
        $parent.append('<div class="formtipsa default">'+Msg+'</div>');
        me.removeClass("redborder");
        me.removeClass("greenborder");
    }
}

function successBorder(obj){
    obj.removeClass("redborder");
    obj.addClass("greenborder");
}

function errorBorder(obj,errorMsg){
    var $parent = obj.parent();
    obj.next(".formtipsa").remove();
    $parent.append('<div class="formtipsa onError">'+errorMsg+'</div>');
    obj.removeClass("greenborder");
    obj.addClass("redborder");
}

function errorImg(obj){
	var  me = obj.parents(".setimgborder");
	me.removeClass("greenborder");
	me.addClass("redborder");
}

function successImg(obj){
	var  me = obj.parents(".setimgborder");
	me.removeClass("redborder");
	me.addClass("greenborder");
}