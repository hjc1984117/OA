$('#add_borrowmoney_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: { },
    rules: {
        borrowmoney_no: {
            minlength: 5,
            required: true
        },
        username: {
            required: true,
            minlength: 2
        },
        phone: {
            required: true,
            minlength: 11,
            mobile: true
        },
        idcard: {
            required: true,
            rangelength: [15, 18]
        },
        join_time: {
            required: true,
            date: true
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
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
        $(element).closest('.col-md-3').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_borrowmoney_form").serializeJson();
        data.action = 1;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/money/borrowmoney.php", data, function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                BorrowmoneyListViewModel.listBorrowmoney({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});