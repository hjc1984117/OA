jQuery.validator.addMethod("mobile", function (value, element) {
    var length = value.length;
    var mobile = /^(((1[34578]{1}[0-9]{1})|(15[0-9]{1}))+\d{8})$/
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "手机号码格式错误");
$('#add_salecount_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        ww: {
            required: true,
            minlength: 1
        },
        qq: {
            required: true,
            minlength: 2
        },
        money: {
            required: true
        },
        name: {
            required: true,
            minlength: 2
        },
//        mobile: {
//            required: true,
//            mobile: true
//        },
        setmeal: {
            required: true,
            minlength: 2
        },
        payment: {
            required: true,
            minlength: 2
        },
        channel: {
            required: true,
            minlength: 2
        },
        province: {
            required: true,
            minlength: 2
        },
//        addtime: {
//            required: true,
//            minlength: 19
//        },
        presales: {
            required: true,
            minlength: 2
        },
        presales_id: {
            required: true,
            minlength: 1
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
        $(element).closest('.col-md-4').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-3').removeClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-4').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-3').removeClass('has-error').addClass('has-success'); // set success class to the control group
        $(element).closest('.col-md-4').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_salecount_form").serializeJson();
        data.action = 1;
        data.isTimely = 0;
        data.isQQTeach = 0;
        data.isTmallTeach_qj = 0;
        data.isTmallTeach_zy = 0;
        data.scheduledPackage = 0;
        var ather = (data.ather).split(",");
        if (ather.indexOf("isTimely") !== -1) {
            data.isTimely = 1;
        }
        if (ather.indexOf("isQQTeach") !== -1) {
            data.isQQTeach = 1;
        }
        if (ather.indexOf("scheduledPackage") !== -1) {
            data.scheduledPackage = 1;
        }
        if ((data.ather1).indexOf("isTmallTeach_qj") !== -1) {
            data.isTmallTeach_qj = 1;
        }
        if ((data.ather1).indexOf("isTmallTeach_zy") !== -1) {
            data.isTmallTeach_zy = 1;
        }
        if (data.presales_id !== ycoa.user.userid()) {
            data.status = 2;
        }
        if (data.addtime) {
            var d = (data.addtime).split(" ")[0];
            if (d > current_Date) {
                ycoa.UI.toast.warning("添加时间不能大于当前时间~");
                return;
            }
        }
        data.attachment = $("#add_salecount_form #attachment").html();
        ycoa.ajaxLoadPost("/api/sale/salecount.php", JSON.stringify(data), function (result) {
            if (result.code === 0) {
                $("#add_salecount_form input,#add_salecount_form textarea").val("");
                $("#add_salecount_form #myEditor p").html("");
                $("#add_salecount_form ul.auto_radio_ul li.auto_radio_li").removeClass("auto_radio_li_checked");
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