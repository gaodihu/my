$(function(){
	var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
});
window.fbAsyncInit = function() {
	FB.init({
	  appId: '818503434868779',
	  cookie: true,
	  xfbml: true,
	  oauth: true
	});
};
var FbAuth = {};
FbAuth.host = ('https:' == document.location.protocol ? 'https://' : 'http://') + location.host;
(function(v){
	v.facebook = {
		auth : function (data) {
			var url = FbAuth.host + '/index.php?route=account/fblogin';
			$.ajax({
				type: "POST",
				url: url,
				data:data,
                                dataType: 'json',
				success:function(res){
					
					if(res.ask == 1 ) {

                                              if(res.callback != ''){
                                                  location.href=decodeURIComponent(res.callback);
                                              }else{
                                                 location.href = '/';
                                              }
						
					}else{
                                             if(res.callback != ''){
                                                  location.href=decodeURIComponent(res.callback);
                                              }else{
												 location.href = '/';
                                              }
                                        }
				}	
			});
		},
		login : function(redirect) {
		    if (typeof(FB) == "undefined") {
		        return false;
		    }
		    var that = this;
			FB.login(function(response) {
		    	if (response.authResponse) {
		    		buid = response.authResponse.userID;
		            btoken = response.authResponse.accessToken;
                            
                            FB.api('/me', {scope: 'public_profile,email'}, function(response) {
                                
		                var data = {
		                	fbid : buid,
		                	token : btoken,
		                	fname : response.first_name,
		                	lname : response.last_name,
		                	email : response.email,
		                	redirect : redirect,
		                	type : 'facebook'
		                };

		                that.auth(data);
                              });

                            
		    		
		    	} else {
		    		return;
		    	}
		    }, {
		        scope: 'email'
		    });
		}	
	}
})(window.FbAuth);