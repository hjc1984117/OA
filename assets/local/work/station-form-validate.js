$('#add_station_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {
//        employee_no: {
//            minlength: jQuery.validator.format("员工编号最小长度为{0}"),
//            required: "员工编号不能为空"
//        }, username: {
//            minlength: jQuery.validator.format("员工姓名最小长度为{0}"),
//            required: "请输入员工姓名"
//        }
    },
    rules: {
        dept2_id: {
            required: true,
            minlength: 1
        },
        pur: {
            required: true,
            minlength: 2
        },
        station_key1: {
            required: true,
            minlength: 2
        },
        demand: {
            required: true,
            minlength: 2
        },
        exp: {
            required: true,
            minlength: 1
        },
        credentials: {
            required: true,
            minlength: 2
        },
        position: {
            required: true,
            minlength: 4
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-8').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-8').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-8').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_station_form").serializeJson();
        data.action = 1;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/station.php", data, function (result) {
            if (result.code === 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                StationListViewModel.listStation({action: 1});  
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});