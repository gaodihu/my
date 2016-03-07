var Cart = {},CartEvent = {load_time : null};
/* Cart Ajax ----------------------------------------------------------------------------- */
Cart.add = function (pid, qty,ship_to,success) {
    common.lodShow();
    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: 'product_id=' + pid + '&quantity=' + qty+ '&ship_to='+ship_to,
        dataType: 'json',
        success: function (json) {
            success(json);
            common.lodHide();
        }
    });
}
Cart.update = function (key, qty, success) {
    common.lodShow();
    $.ajax({
        url: 'index.php?route=checkout/cart/update',
        type: 'post',
        data: 'key=' + key + '&qty=' + qty,
        dataType: 'json',
        success: function (json) {
            success(json);
            common.lodHide();
        }
    });
};
Cart.remove = function (key, success) {
    common.lodShow();
    $.ajax({
        url: 'index.php?route=checkout/cart/remove',
        type: 'post',
        data: 'key=' + key,
        dataType: 'json',
        success: function (json) {
            success(json);
            common.lodHide();
        }
    });
};
Cart.validateCoupon = function (coupon, success) {
    common.lodShow();
    $.ajax({
        url: 'index.php?route=checkout/cart/validateCoupon',
        type: 'post',
        data: 'coupon=' + coupon,
        dataType: 'json',
        success: function (json) {
            success(json);
            common.lodHide();
        }
    });
}
Cart.cancelCoupon = function (coupon, success) {
    $.ajax({
        url: 'index.php?route=checkout/cart/cancelCoupon',
        type: 'post',
        data: 'coupon=' + coupon,
        dataType: 'json',
        success: function (json) {
            success(json);
        }
    });
}

/* Cart Event ----------------------------------------------------------------------------- */
// 购物车修改
CartEvent.totalQuantity=function(key){
    var num = parseFloat($("#num_"+key).val());
    CartEvent.timeUpdata(key,num);
}

// 购物车减
CartEvent.minusQuantity=function(key){
    var num =  parseFloat($("#num_"+key).val()) - 1;
    if (num <= 0) {
        return;
    }
    $("#num_"+key).val(num);
    CartEvent.timeUpdata(key,num);

}

// 购物车加
CartEvent.plusQuantity=function(key){
    var num =  parseFloat($("#num_"+key).val()) + 1;
    $("#num_"+key).val(num);
    CartEvent.timeUpdata(key,num);

}

// 购物车加
CartEvent.timeUpdata=function(key,num){
    if(null != CartEvent.down_time){
        //停止时钟down_time
        clearInterval(CartEvent.down_time);
    }
    //1妙不操作就跳转
    CartEvent.down_time = setTimeout(function(){
        Cart.update(key,num,CartEvent.fresh_cart);
    },1000);
}

// 删除提示框
CartEvent.delCart=function(key) {
    $.popConfirm.show("Remove from your cart?");
    $.popConfirm.yesfn = function () {
        //隐藏提示
		$.popConfirm.hide();
        Cart.remove(key,CartEvent.fresh_cart);

    }

}

// 购物车数量显示
CartEvent.cartNo=function() {
    var car_no = 0;
    $(".cart-list .num").each(function () {
        car_no += parseFloat($(this).val());
    })
    console.log(car_no);
    $(".menu").find(".icon-shopping-cart").html("<span>" + car_no + "</span>");
}

//刷新数据
CartEvent.fresh_cart=function(data){
	if(data['error']=='0'){
		var content = data.content;
		$("#shopping_cart_qty").html(data.sub_qty);
		$("#cart_total").html(data.sub_qty);
		$('#cart_content').html(content);
	}else{
		if(data['message']){
			common.alertInfo(data['message']);
		}else if(data['return']){
			window.location.href=data['return'];
		}
		
	}
}





