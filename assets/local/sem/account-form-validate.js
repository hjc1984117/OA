$('#add_account_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
    	userid: {
            required: true
        },
        selectchannels: {
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
        $(element).closest('.col-md-8').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-8').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-8').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {

        var data = $("#add_account_form").serializeJson();
        data.action = 1;
        ycoa.ajaxLoadPost("/api/sem/account.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
                $("#add_account_form #userid").val("");
            	$("#add_account_form #username").val("");
            	$("#add_account_form ul.auto_radio_ul li.auto_radio_li").removeClass("auto_radio_li_checked");
            	$("#btn_close_primary").click();
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});