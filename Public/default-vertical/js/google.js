!function () {
    var a = document.createElement("script");
    a.type = "text/javascript", a.async = !0, a.src = "https://apis.google.com/js/client:plusone.js?onload=render";
    var b = document.getElementsByTagName("script")[0];
    b.parentNode.insertBefore(a, b)
}();


function render() {
    gapi.signin.render('googleAuth', {
        callback: 'signinCallback',
        clientid: '822079490015-nn4kn49cl7iat8jvtqko5m2r2ieesr7q.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        requestvisibleactions: 'http://schemas.google.com/AddActivity',
        scope: 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email',
        approvalprompt: "force"
    });
}


function signinCallback(authResult) {
    if (authResult['access_token']) {
        // 已成功授权
        // 用户已授权，隐藏登录按钮，例如：    
        // 加载api
        gapi.client.load("oauth2", "v2", function () {
            var request = gapi.client.oauth2.userinfo.get();
            request.execute(function (obj) {
                // 取得登录邮箱并显示
                var email = obj['email'];
                var given_name = obj['given_name'];
                var gid = obj['id'];
                if (email && given_name && gid) {
                    var url = '/index.php?route=account/google';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: 'email=' + email + "&gid=" + gid + "&given_name=" + given_name,
                        dataType: 'json',
                        success: function (res) {
                            if (res.flag == 1) {
                                var href= location.href;
                                if(href.indexOf('checkout/cart')!= -1){
                                    location.href = '/index.php?route=checkout/cart';
                                }else {
                                    if (res.callback != '') {
                                        location.href = decodeURIComponent(res.callback);
                                    } else {
                                        location.href = '/';
                                    }
                                }

                            } else {
                                if (res.callback != '') {
                                    location.href = decodeURIComponent(res.callback);
                                } else {
                                    location.href = '/';
                                }
                            }
                        }
                    });
                }
            });
        });

    } else if (authResult['error']) {

    }
}