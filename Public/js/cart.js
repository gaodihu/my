// JavaScript Document
var Cart = {};
Cart.update = function(key,qty,success){
	$.ajax({
		url: 'index.php?route=checkout/cart/update',
		type: 'post',
		data: 'key='+key+'&qty='+qty,
		dataType: 'json',
		success: function(json) {
				success(json);
		}
	});
};
Cart.remove = function(key,success){
	$.ajax({
		url: 'index.php?route=checkout/cart/remove',
		type: 'post',
		data: 'key='+key,
		dataType: 'json',
		success: function(json) {
				success(json);
		}
	});
};
Cart.validateCoupon=function(coupon,success){
	$.ajax({
		url: 'index.php?route=checkout/cart/validateCoupon',
		type: 'post',
		data: 'coupon='+coupon,
		dataType: 'json',
		success: function(json) {
				success(json);
		}
	});	
}
Cart.cancelCoupon=function(coupon,success){
	$.ajax({
		url: 'index.php?route=checkout/cart/cancelCoupon',
		type: 'post',
		data: 'coupon='+coupon,
		dataType: 'json',
		success: function(json) {
				success(json);
		}
	});	
}


