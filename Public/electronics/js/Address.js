// JavaScript Document
//地址address js  效果
$(document).ready(function() {
    if ($('#order_form').attr('action')&&$('#order_form :input[name=address]').size() < 1) {
        $(".checkout_tc,.blkbg").show();
    }
    $(".enter_new a").click(add_new_address);

    $('.tanchuan .closebtn').live('click', function(event) {
        event.preventDefault();
        $(".checkout_tc,.blkbg").hide();
    })

    $('#address-form-cancel').live('click', function(event) {
        //event.preventDefault();
        $(".checkout_tc,.blkbg").toggle();
        return false;
    })
    $('#shipping-address-edit').live('click', function() {
        var from = $(this).attr('from');
		var type=$(this).attr('address_type');
        $.ajax({
            url: 'index.php?route=checkout/address/editAddress',
            type: 'post',
            data: 'id=' + $(this).attr('address-id'),
            dataType: 'json',
            success: function(json) {
                $('#address_form').html(json['content']);
                $('#add_address_from').find('#address_from').val(from);
				$('#add_address_from').find('#address_type').val(type);
                $('.checkout_tc,.blkbg').show();
                //$('#add_address_from').hide();

            }
        });
    })

    $('#add_address_from select[name=\'country_id\']').live('change', function() {
        $('#add_address_from select[name=\'zone_id\']').parent(".text").find(".formtips").remove();
        if ($(this).val() == ''){
            return;
		}
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#add_address_from select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="css/images/loader_16x16.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }
                html = '';
                if (json['zone'] != '') {
					html = '<option value="">Please Select...</option>';
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }

                    $('#add_address_from select[name=\'zone_id\']').html(html);
                    $('#add_address_from input[name=\'zone\']').hide();
					$('#add_address_from input[name=\'zone\']').attr('value','');
                    $('#add_address_from select[name=\'zone_id\']').show();

                    $('#add_address_from select[name=\'zone_id\']').attr("disabled",false);
                    $('#add_address_from input[name=\'zone\']').attr("disabled",true);

                    $('#add_address_from input[name=\'zone\']').attr("verify","");
                } else {
                    $('#add_address_from input[name=\'zone\']').val('');

                    $('#add_address_from input[name=\'zone\']').show();
                    
                    $('#add_address_from select[name=\'zone_id\']').hide();

                    $('#add_address_from input[name=\'zone\']').attr("disabled",false);
                    $('#add_address_from select[name=\'zone_id\']').attr("disabled",true);
                    $('#add_address_from input[name=\'zone\']').attr("verify","notnull");

                }



            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#add_address_from select[name=\'country_id\']').trigger('change');

    $('#save_address_button').live('click', function() {
        //form 验证



        $.ajax({
            url: 'index.php?route=checkout/address/addAddress',
            type: 'post',
            data: $('#add_address_from').serialize(),
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {

                if (json['error'] == 0) {
                    $('.checkout_tc,.blkbg').hide();
                    if (json['redirect']) {
                        window.location.href = json['redirect'];
                    }
                    else {
                        $('#address_list').html(json['content']);
                        update_shipping_list(json['address_id'], '','');
                    }
                } else {
                    $('#add_address_from select[name=\'country_id\']').trigger('change');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#update_address_button').live('click', function() {
        $(this).parents("form").find("input,select").trigger('blur');
        var numError = $(this).parents(".form").find(".redborder").length;
        if(numError>0){
            return false;
        }
        $.ajax({
            url: 'index.php?route=checkout/address/updateAddress',
            type: 'post',
            data: $('#add_address_from').serialize(),
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 0) {
                    $('.checkout_tc,.blkbg').hide();
                    if (json['redirect']) {
                        window.location.href = json['redirect'];
                    }
                    else {
                        $('#address_list').html(json['content']);
                        update_shipping_list(json['address_id'], '','');
                    }

                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#shipping-address-delete').live('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/address/delAddress',
            type: 'post',
            data: 'address_id=' + $(this).attr('address-id'),
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json,event) {
                if (json['error'] == 0) {
                    $('.checkout_tc,.blkbg').hide();
                    $('#address_list').html(json['content']);
                    if(json['empty'] == 1){
                        add_new_address(event);
                    }
                    update_shipping_list('', '','');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#shipping-address-default').live('click', function() {



        $.ajax({
            url: 'index.php?route=checkout/address/defaultAddress',
            type: 'post',
            data: 'address_id=' + $(this).attr('address-id'),
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 0) {
                    $('.checkout_tc,.blkbg').hide();
                    $('#address_list').html(json['content']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    $('#order_form').find("input[name='address']").live('click', function() {
        var address_id = $(this).val();
        update_shipping_list(address_id, '', '');
    });
    $('#order_form').find("input[dom='shipping']").live('click', function() {
        var shipping_id = $(this).val();
        var pk = $(this).attr('pk');
        if(shipping_id && pk){
            changeShipping(pk,shipping_id);
        }
    });
    $('#order_form').find("input[name='payment_code']").live('click', function() {
        var payment_code = $(this).val();
        if (payment_code) {
            change_payment(payment_code);
        }

    });
    $('#apply_point').live('click', function() {
        var point = parseInt($('#use_point').val());
		var error_message ='';
        if (point < config_point_reword) {
            error_message +=error_point;
        }
        if (point > total_points) {
            error_message +=error_than_points;
        }
		if(error_message){
			alert(error_message);
		}
		else{
			$.ajax({
				url: 'index.php?route=checkout/checkout/validatePoints',
				type: 'get',
				data: 'points=' + point,
				dataType: 'json',
				beforeSend: function() {

				},
				complete: function() {

				},
				success: function(json) {
					if (json['error'] == 0) {
						$('#order_subtoal').html(json['subtol_coutent']);
					}
					else {
						alert(json['message']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
        

    })

    $('#Cancel_point').live('click', function() {
        var point = $('#use_point').val();
        $.ajax({
            url: 'index.php?route=checkout/checkout/validatePoints',
            type: 'get',
            data: 'points=' + point + "&type=remove",
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error'] == 0) {
                    $('#order_subtoal').html(json['subtol_coutent']);
                }
                else {
                    alert(json['message']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    })
    
    //customer split package
    $('#customer_split_package').live('click', function() {
            var customer_split_package = $(this).val();
            if($(this).attr('checked') == 'checked'){
                $.ajax({
                    url: 'index.php?route=checkout/checkout/splitpackage',
                    type: 'post',
                    data: 'customer_split_package=' + customer_split_package,
                    dataType: 'json',
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(json) {
                        update_shipping_list('','','');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
        }

        }
    );
    
    
    
    function update_shipping_list(address_id, shipping_method_id, paymet_code) {
        $.ajax({
            url: 'index.php?route=checkout/checkout/calShippingFee',
            type: 'post',
            data:  'address_id=' +address_id + '&shipping_method_id=' + shipping_method_id ,
            dataType: 'json',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(json) {
                if (json['error']) {
                    window.location.reload();
                }
                else {
                    $('#shipping_list').html(json['shipping_content']);
                    $('#payment_list').html(json['payment_content']);
                    $('#order_subtoal').html(json['subtol_coutent']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }

     function changeShipping(pk,shipping_method) {
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
                    flush_payment();
                    $('#order_subtoal').html(json['subtol_coutent']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }

    function change_payment(payment_code) {
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
                if (json['error'] == 1) {
                    //alert(json['message']);
                    //window.location.href="'"+json['redirect']+"'"
                    window.location.href = "index.php?route=checkout/checkout";
                }
                else {
                    //$('#shipping_list').html(json['shipping_content']);
                    //$('#order_subtoal').html(json['subtol_coutent']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }
    function flush_payment() {
        $.ajax({
            url: 'index.php?route=checkout/payment_method',
            type: 'get',
            data: '',
            success: function(html) {
                $('#payment_list').html(html);
            }
        })
    }

    function add_new_address(event) {
        var type = $(this).attr('type');
        var from = $(this).attr('from');

        $('#add_address_from :input[type=text]').val('');
        $('#add_address_from :input[name=address_id]').val('');
        $('#add_address_from').find('#address_type').val(type);
        $('#add_address_from').find('#address_from').val(from);
        $('#add_address_from :input[type=text]').removeClass('greenborder');
        event.preventDefault();
        if ($(".checkout_tc,.blkbg").is(":hidden")) {
            $(".checkout_tc,.blkbg").show();
        } else {
            $(".checkout_tc,.blkbg").hide();
        }
        ;
    }
});