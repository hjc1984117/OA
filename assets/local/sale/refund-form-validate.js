$('#add_refund_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        money: {
            required: true,
            number: true
        },
        retrieve: {
            required: true,
            number: true
        },
        record_amount: {
            required: true,
            number: true
        },
        refund_type: {
            required: true,
            minlength: 1
        },
        refund_rate: {
            required: true,
            minlength: 1
        },
        duty: {
            required: true,
            minlength: 1
        },
        status: {
            required: true,
            minlength: 1
        },
        is_logoff_dbb: {
            required: true
        },
        reason: {
            required: true,
            minlength: 1
        }
//        ,
//        date: {
//            required: true,
//            minlength: 1
//        }
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
        $(element).closest('.col-md-4').addClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-5').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-3').removeClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-4').removeClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-5').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-3').removeClass('has-error').addClass('has-success'); // set success class to the control group
        $(element).closest('.col-md-4').removeClass('has-error').addClass('has-success'); // set success class to the control group
        $(element).closest('.col-md-5').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_refund_form").serializeJson();
        data.action = 1;
        ycoa.ajaxLoadPost("/api/sale/refund.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#add_refund_form input,#add_refund_form textarea").val("");
                $("#add_refund_form #myEditor p").html("");
                $("#add_refund_form ul.auto_radio_ul li.auto_radio_li").removeClass("auto_radio_li_checked");
                $("#btn_close_refund").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({
                    action: 1,
                    sort: ycoa.SESSION.SORT.getSort(),
                    sortname: ycoa.SESSION.SORT.getSortName(),
                    pageno: ycoa.SESSION.PAGE.getPageNo(),
                    pagesize: ycoa.SESSION.PAGE.getPageSize()
                });
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});