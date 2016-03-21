$('#add_sale_statistics_mouth_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        username: {
            required: true,
            minlength: 1
        },
        into_count: {
            required: true,
            number: true
        },
        accept_count: {
            required: true,
            number: true
        },
        deal_count: {
            required: true,
            number: true
        },
        timely_count: {
            required: true,
            number: true
        },
        amount: {
            required: true,
            number: true
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-6').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-6').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.col-md-6').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        var data = $("#add_sale_statistics_mouth_form").serializeJson();
        data.action = 1;
        //data.presales = ycoa.user.userid();
        ycoa.ajaxLoadPost("/api/sale_soft/saleStatisticsDay.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                SaleStatisticsDayViewModel.listSaleStatisticsDay({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});