// 表单验证
$(function(){
    //文本框失去焦点后
    $(document).on("blur",".form input,.form select",function(){
        var verify = $(this).attr("verify");
        var $parent = $(this).parent();
        var okMsg = '<div class="formtips onSuccess">Correct.</div>';

		// 隐藏不提示
		if($(this).css("display") == 'none'){
				$(this).removeClass("redborder");
				return;
		}
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
		
				 //密码1
        if(verify == "pw1"){

            if( this.value.length <4 || this.value.length>20 ||  this.value==""){
                var errorMsg = 'Password The length of the input 4 ~ 20.';
				errorBorder($(this),errorMsg);
            }else{
				  successBorder($(this));
			}
        }
		
		 //密码2
        if(verify == "pw2"){
			if($("input[verify=pw1]").val()!=this.value || this.value.length <4 || this.value.length>20){
				var errorMsg = 'Confirm Password error';
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

    }).change(function(){
       $(this).triggerHandler("blur");
    });//end blur

    //提交，最终验证。
    $(document).on("click",".send",function(event){

        $(this).parents("form").find("input,select").trigger('blur');
        var numError = $(this).parents(".form").find(".redborder").length;

        if(numError>0){
      		//alert(numError);

            return false;
        }
    });
			

})

// 其他提示语句加入  实例： $.valiform.setSuccessMsg("firstname","asd")
$.valiform={
    setSuccessMsg:function(name,succeedMsg){
           var me = $("[name="+name+"]") , $parent = me.parent();
           me.next(".formtips").remove();
           $parent.append('<div class="formtips onSuccess">'+succeedMsg+'</div>');
           successBorder(me);

    },
    setErrotMsg:function(name,errorMsg){
            var me = $("[name="+name+"]") , $parent = me.parent();
            me.next(".formtips").remove();
            errorBorder(me,errorMsg);
    },
    defaultMsg:function(name,Msg){
        var me = $("[name="+name+"]") , $parent = me.parent();
        me.next(".formtips").remove();
        $parent.append('<div class="formtips default">'+Msg+'</div>');
        me.removeClass("redborder");
        me.removeClass("greenborder");
    }
	
	
}
function successBorder(obj){
    var tag = "li";
    if(obj.parent("li").length==0){
         tag = "div";
    }
    obj.parent(tag).find(".formtips").hide();
    obj.removeClass("redborder");
    obj.addClass("greenborder");
}

function errorBorder(obj,errorMsg){
    var tag = "li";
    if(obj.parent("li").length==0){
        tag = "div";
    }
    var $parent = obj.parent(tag);
    if(obj.parent(tag).find(".formtips").length == 0){
        $parent.append('<div class="formtips onError">'+errorMsg+'</div>');
    }else{
		obj.parent(tag).find(".formtips").text(errorMsg);
	}
    obj.parent(tag).find(".formtips").show();
    obj.removeClass("greenborder");
    obj.addClass("redborder");
}


