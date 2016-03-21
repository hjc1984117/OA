$('#add_causeleave_form').validate({
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
        starttime: {
            required: true,
            minlength: 10
        },
        endtime: {
            required: true,
            minlength: 10
        },
        salary: {
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
        $(element).closest('.col-md-5').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-5').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-5').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_causeleave_form").serializeJson();
        data.action = 1;
        data.starttime = data.starttime + " " + data.start_time;
        data.endtime = data.endtime + " " + data.end_time;
        var vlaliStartTime = data.starttime + ":00";
        var vlaliEndTime = data.endtime + ":00";
        vlaliStartTime = new Date(vlaliStartTime.replace("-/g", "/")).getTime();
        vlaliEndTime = new Date(vlaliEndTime.replace("-/g", "/")).getTime();
        if (vlaliStartTime && vlaliEndTime) {
            if ((vlaliEndTime - vlaliStartTime) <= 0) {
                ycoa.UI.toast.warning("假期结束时间必须晚于假期开始时间~");
            } else {
                data = JSON.stringify(data);
                ycoa.ajaxLoadPost("/api/attendance/causeleave.php", data, function (result) {
                    if (result.code == 0) {
                        $("#btn_close_primary").click();
                        reLoadData({});
                        ycoa.UI.toast.success(result.msg);
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        } else {
            ycoa.UI.toast.warning("请填写完整假期信息~");
        }
    }
});