$('#add_customerdetails_form').validate({
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
            number: true
        },
        money: {
            required: true,
            number: true
        },
        payment: {
            required: true,
            minlength: 1
        },
        is_receive: {
            required: true,
            minlength: 1
        },
        is_reviews: {
            required: true,
            minlength: 1
        },
        is_addto_reviews: {
            required: true,
            minlength: 1
        },
        is_add_qq: {
            required: true,
            minlength: 1
        },
        is_customerdetails:{
            required:true,
            minlength:1
        },
        is_two_sales:{
            required:true,
            minlength:1
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
        var data = $("#add_customerdetails_form").serializeJson();
        data.action = 1;
        ycoa.ajaxLoadPost("/api/sale_soft/customerdetails.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#add_customerdetails_form input,#add_customerdetails_form textarea").val("");
                $("#add_customerdetails_form #myEditor p").html("");
                $("#add_customerdetails_form ul.auto_radio_ul li.auto_radio_li").removeClass("auto_radio_li_checked");
                $("#btn_close_customerdetails").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});