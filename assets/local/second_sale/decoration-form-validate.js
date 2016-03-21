$('#add_decoration_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        ww: {
            required: true,
            minlength: 2
        },
        qq: {
            required: true,
            minlength: 5,
            number: true
        },
        name: {
            required: true,
            minlength: 2
        },
        phone: {
            required: true,
            minlength: 5
        },
        decoration_packages: {
            required: true,
            minlength: 2
        },
        decoration_price: {
            required: true,
            minlength: 1,
            number: true
        },
        alipay_account: {
            required: true,
            minlength: 2
        },
        payment_method: {
            required: true,
            minlength: 5
        },
//        tra_num: {
//            required: true,
//            minlength: 2
//        },
        customer: {
            required: true,
            minlength: 2
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-3').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-3').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-3').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_decoration_form").serializeJson();
        if ((data.payment_method).indexOf("微信") === -1 && data.tra_num === "") {
            $("#add_decoration_form #tra_num").prev(".fa").addClass("fa-warning");
            $("#add_decoration_form #tra_num").parent(".input-icon").parent(".col-md-3").addClass("has-error");
            return;
        }
        data.action = 1;
        ycoa.ajaxLoadPost("/api/second_sale/decoration.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});


$('#add_fillarrears_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        fill_sum: {
            required: true,
            number: true
        },
        qq: {
            required: true,
            minlength: 5
        },
        ww: {
            required: true,
            minlength: 2
        },
        name: {
            required: true,
            minlength: 2
        },
        phone: {
            required: true,
            minlength: 5
        },
        mobile: {
            required: true,
            minlength: 5
        },
        payment_method: {
            required: true,
            minlength: 5
        },
        tra_num: {
            required: true,
            minlength: 2
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-3').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-3').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-3').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_fillarrears_form").serializeJson();
        data.action = 1;
        data.type = 3;
        data.remark = $("#add_fillarrears_form #remark_fillarrears").html();
        ycoa.ajaxLoadPost("/api/second_sale/fillarrears.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#btn_close_fill_primary").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});