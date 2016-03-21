$('#add_meet_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {
//        meettem_no: {
//            minlength: jQuery.validator.format("员工编号最小长度为{0}"),
//            required: "员工编号不能为空"
//        }, username: {
//            minlength: jQuery.validator.format("员工姓名最小长度为{0}"),
//            required: "请输入员工姓名"
//        }
    },
    rules: {
        meet_title: {
            minlength: 1,
            required: true
        },
        meet_content: {
            required: true,
            minlength: 2
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-9').addClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-6').addClass('has-error');
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-9').removeClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-6').removeClass('has-error');
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-9').removeClass('has-error').addClass('has-success'); // set success class to the control group
        $(element).closest('.col-md-6').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var formData = $("#add_meet_form").serializeJson();
        add_meettem(formData);
    }
});
function add_meettem(data) {
    data.action = 1;
    data = JSON.stringify(data);
    ycoa.ajaxLoadPost("/api/work/meet.php", data, function (result) {
        if (result.code == 0) {
            $("#add_meet_form").parent().parent().find('#btn_close_primary').click();
            ycoa.UI.toast.success(result.msg);
            MeetListViewModel.listMeet({action: 1});
        } else {
            ycoa.UI.toast.error(result.msg);
        }
        ycoa.UI.block.hide();
    });
}

$('#edit_meet_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {
//        meettem_no: {
//            minlength: jQuery.validator.format("员工编号最小长度为{0}"),
//            required: "员工编号不能为空"
//        }, username: {
//            minlength: jQuery.validator.format("员工姓名最小长度为{0}"),
//            required: "请输入员工姓名"
//        }
    },
    rules: {
        meet_title: {
            minlength: 1,
            required: true
        },
        meet_content: {
            required: true,
            minlength: 2
        }

    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-9').addClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-6').addClass('has-error');
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-9').removeClass('has-error'); // set error class to the control group
        $(element).closest('.col-md-6').removeClass('has-error');
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-9').removeClass('has-error').addClass('has-success'); // set success class to the control group
        $(element).closest('.col-md-6').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var formData = $("#edit_meet_form").serializeJson();
        edit_meettem(formData);
    }
});
function edit_meettem(data) {
    data.action = 2;
    data = JSON.stringify(data);
    ycoa.ajaxLoadPost("/api/work/meet.php", data, function (result) {
        if (result.code == 0) {
            $("#edit_meet_form").parent().parent().find('#btn_close_primary').click();
            ycoa.UI.toast.success(result.msg);
            MeetListViewModel.listMeet({action: 1});
        } else {
            ycoa.UI.toast.error(result.msg);
        }
        ycoa.UI.block.hide();
    });
}