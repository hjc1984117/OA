jQuery.validator.addMethod("mobile", function (value, element) {
    var length = value.length;
    var mobile = /^(((1[34578][0-9]{1})|(15[0-9]{1}))+\d{8})$/
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "手机号码格式错误");
$('#add_adjustRest_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {
    },
    rules: {
        username: {
            required: true,
            minlength: 2
        },
        phone: {
            required: true,
            minlength: 1,
            mobile: true
        },
        rest_date: {
            required: true,
            minlength: 10
        },
        rest_days: {
            required: true,
            number: true
        },
        adjust_to: {
            required: true,
            minlength: 10
        },
        adjust_days: {
            required: true,
            number: true
        },
        reason: {
            required: true,
            minlength: 8
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-3,.col-md-8').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-3,.col-md-8').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-3,.col-md-8').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_adjustRest_form").serializeJson();
        data.action = 1;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/attendance/adjustRest.php", data, function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                reLoadData({action: 1});
                ycoa.UI.toast.success(result.msg);
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});