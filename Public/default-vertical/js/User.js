// JavaScript Document
var User = {};
User.checkemail = function(email,message_obj){
	$.ajax({
		url: 'index.php?route=account/login/check_email',
		type: 'post',
		data: 'email='+email,
		dataType: 'json',
		success: function(json) {
			if (json['message']) {
				message+=json['message'];
				message_obj.html(message);
			}
		}
	});
};

