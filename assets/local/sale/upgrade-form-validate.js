jQuery.validator.addMethod("mobile", function (value, element) {
    var length = value.length;
    var mobile = /^(((1[34578]{1}[0-9]{1})|(15[0-9]{1}))+\d{8})$/
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "手机号码格式错误");

$('#add_upgrade_form').validate({
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
        money: {
            required: true
        },
        name: {
            required: true,
            minlength: 2
        },
        mobile: {
            required: true,
            mobile: true
        },
        upgrade_sum: {
            required: true,
            number: true
        },
        payment: {
            required: true,
            minlength: 2
        },
        channel: {
            required: true,
            minlength: 2
        },
        add_time: {
            required: true,
            minlength: 19
        },
        add_name: {
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
        $(element).closest('.col-md-4').removeClass('has-error').addClass('has-success'); // set error class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_upgrade_form").serializeJson();
        data.action = 1;
        if (data.add_time) {
            var d = (data.add_time).split(" ")[0];
            if (d > current_Date) {
                ycoa.UI.toast.warning("添加时间不能大于当前时间~");
                return;
            }
        }
        data.attachment = $("#add_upgrade_form #attachment").html();
        ycoa.ajaxLoadPost("/api/sale/upgrade.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                UpgradeListViewModel.listUpgrade({});
                reLoadData({});
                ycoa.UI.toast.success(result.msg);
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});