// 表单验证

$(function(){
    //文本框失去焦点后
    $(".form input,.form select").blur(function(){

        var verify = $(this).attr("verify");
        var $parent = $(this).parent();
        var okMsg = '<div class="formtips onSuccess">Correct.</div>';

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

        //用户名
        if(verify == "user"){

            if( this.value.length <1 || this.value.length>32 ||  this.value==""){
                var errorMsg = 'The length of the input 1 ~ 32.';
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }

        //地址
        if(verify == "address"){
            if(this.name == 'address_2' && this.value == ''){
                //address_2 可以为空
            } else{
                if( this.value.length < 3 || this.value.length>35 ||  this.value=="" ){
                    var errorMsg = 'The length of the input 3 ~ 35.';
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
        //手机号码
        if( verify == "number"){
			
            if( this.value=="" || this.value.length <1 || this.value.length>16  ){
                var errorMsg = "The maximum length of the phone number is 16 characters.";
                errorBorder($(this),errorMsg);
				return;
            }if( this.value!="" &&  !/^([\d-+#,*\s\.\(\)\/\\\[\]]*)$/.test(this.value) ){
                var errorMsg = "Please input valid phone number, supported formats: Number, Space and +-()";
                errorBorder($(this),errorMsg);
				return;
            }else{
                successBorder($(this));
            }
        }
        //Post Code
        if( verify == "postcode"){
            if( this.value=="" || this.value.length <2 || this.value.length>10  ){
                var errorMsg = "Post Code must be between 2 and 10 characters in length";
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }
        // 公司名称
        if( verify == "company"){
            if( this.value.length>35  ){
                var errorMsg = "The length of the input 1 ~ 35.";
                errorBorder($(this),errorMsg);
            }else{
                successBorder($(this));
            }
        }
        /*只能输入  数字 ，字母 ，中文
        if( !/^[a-zA-Z0-9\u4e00-\u9fa5]+$/.test(this.value) && this.value!="" ){
            if(verify == "user" || verify == "address" || verify == "city"){
                $(this).next(".formtips").remove();
                var errorMsg = 'Input format is wrong';
                errorBorder($(this),errorMsg);
            }
        }*/
    }).keyup(function(){
       $(this).triggerHandler("blur");
    }).focus(function(){
       // $(this).triggerHandler("blur");
    });//end blur

    //提交，最终验证。
    $('.send').click(function(){
        $("input").triggerHandler('blur');
        var numError = $('form .onError').length;

        if(numError){
            //console.log("验证失败");
            return false;
        }
    });

    //重置
    $('.res').click(function(){
        $(".formtips").hide();
    });
})

// 其他提示语句加入  实例： $.valiform.setSuccessMsg("firstname","asd")
$.valiform={
    setSuccessMsg:function(name,succeedMsg){
           var me = $("[name="+name+"]") , $parent = me.parent();
           me.parents("li").find(".formtips").hide();
            if(me.parents("li").find(".formtips").length == 0){
                $parent.append('<div class="formtips onSuccess">'+succeedMsg+'</div>');
            }

           successBorder(me);

    },
    setErrotMsg:function(name,errorMsg){
            var me = $("[name="+name+"]") , $parent = me.parent();
            me.parents("li").find(".formtips").show();
            errorBorder(me,errorMsg);
    },
    defaultMsg:function(name,Msg){
        var me = $("[name="+name+"]") , $parent = me.parent();
        me.parents("li").find(".formtips").show();
        if(me.parents("li").find(".formtips").length == 0){
            $parent.append('<div class="formtips default">'+Msg+'</div>');
        }

        me.removeClass("redborder");
        me.removeClass("greenborder");
    }
}

function successBorder(obj){
    obj.parents("li").find(".formtips").hide();
    obj.removeClass("redborder");
    obj.addClass("greenborder");
}

function errorBorder(obj,errorMsg){
    var $parent = obj.parent();
    if(obj.parents("li").find(".formtips").length == 0){
        $parent.append('<div class="formtips onError">'+errorMsg+'</div>');
    }
    obj.parents("li").find(".formtips").show();
    obj.removeClass("greenborder");
    obj.addClass("redborder");
}


