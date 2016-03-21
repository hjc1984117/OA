$('#add_complaint_record_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        shop: {
            required: true,
            minlength: 2
        },
        complaint_custom: {
            required: true,
            minlength: 2
        },
        complaint_content: {
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
        var data = $("#add_complaint_record_form").serializeJson();
        var complaint_content = $("#add_complaint_record_form #complaint_content").html();
        if (complaint_content === "") {
            ycoa.UI.toast.warning("请输入投诉内容~");
            return;
        }
        data.complaint_content = complaint_content;
        data.action = 1;
        data.complaint_content = function () {
            if ((data.complaint_content) !== "") {
                var complaint_content = {complaint_content: data.complaint_content};
                var result_array = new Array();
                if ((typeof data.hand_result) === 'string') {
                    data.hand_result = new Array(data.hand_result);
                }
                $.each(data.hand_result, function (i, d) {
                    if (d !== "") {
                        var a = {text: d};
                        result_array.push(a);
                    }
                    data.hand_result.pop(d);
                });
                complaint_content.hand_result = result_array;
                var r_array = new Array();
                r_array.push(complaint_content);
                return JSON.stringify(r_array);
            } else {
                return "";
            }
        }();
        if (((typeof data.hand_result) === "object" && data.hand_result.length === 0) || (typeof data.hand_result) === "string" && data.hand_result === "") {
            data.hand_result = null;
        }
        ycoa.ajaxLoadPost("/api/sale/complaint_record.php", JSON.stringify(data), function (result) {
            if (result.code === 0) {
                $("#btn_close_cashback").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});