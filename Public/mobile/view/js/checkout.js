var Address = {} ,AddressEvent={}, CheckOut={} ,CheckOutEvent={};
/*  Address -------------------------------------------------- */
AddressEvent.shipping=function(shipping_id,pk){
	
	if(shipping_id && pk){
		
		Address.changeShipping(pk,shipping_id,function(json){
			
			 CheckOut.payment_method();
			 //console.log(json['subtol_coutent']);
             $('#order_subtoal').html(json['subtol_coutent']);
			
		});
	}
}

AddressEvent.add_new_address=function(type,from) {
        $('#add_address_from :input[type=text]').val('');
        $('#add_address_from :input[name=address_id]').val('');
        $('#add_address_from').find('#address_type').val(type);
        $('#add_address_from').find('#address_from').val(from);
        $('#add_address_from :input[type=text]').removeClass('greenborder');
       //event.preventDefault();
        if ($(".checkout_tc,.blkbg").is(":hidden")) {
            $(".checkout_tc,.blkbg").show();
        } else {
            $(".checkout_tc,.blkbg").hide();
        }        
}

Address.changeShipping=function(pk,shipping_method,success_fn) {
    common.lodShow();
        $.ajax({
            url: 'index.php?route=checkout/checkout/changeShipping',
            type: 'post',
            data: 'shipping_method=' + shipping_method + '&pk='+pk,
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 1) {
                     alert(json['message']);
                }
                else {
					success_fn(json);

                }
                common.lodHide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                
            }
        })
}



/*  CheckOut -------------------------------------------------- */
CheckOutEvent.payment=function(payment_code) {
        if (payment_code) {
            CheckOut.changePayment(payment_code);
        }
}

CheckOut.payment_method=function() {
    common.lodShow();
	$.ajax({
		url: 'index.php?route=checkout/payment_method',
		type: 'get',
		data: '',
		success: function(html) {
			$('#payment_list').html(html);
            common.lodHide();
		}
	})
}

CheckOut.changePayment=function(payment_code) {
        common.lodShow();
        $.ajax({
            url: 'index.php?route=checkout/checkout/changePayment',
            type: 'post',
            data: 'payment_code=' + payment_code,
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
				console.log(json);
                if (json['error'] == 1) {
                    //alert(json['message']);
                    //window.location.href="'"+json['redirect']+"'"
                    window.location.href = "index.php?route=checkout/checkout";
                }
                else {
                    //$('#shipping_list').html(json['shipping_content']);
                    //$('#order_subtoal').html(json['subtol_coutent']);
                }
                common.lodHide();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
}




