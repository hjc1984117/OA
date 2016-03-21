$('#report_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
    	reporttt: {
            required: true
        },
        accounts: {
	        required: true
	    },
	    systemtype:{
	    	required: true
	    },
	    reporttype:{
	    	required: true
	    },
	    cond_startdate:{
	    	required: true
	    },
	    cond_enddate:{
	    	required: true
	    },
	    report_file_name:{
	    	required: true
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
    	var reporttt=$("#reporttt").val();
    	var accounts=encodeURI($("#accounts").val());
    	var systemtype=$("#systemtype").val();
    	var reporttype=$("#reporttype").val();
    	var startdate=$("#cond_startdate").val();
    	var enddate=$("#cond_enddate").val();
    	var filename=encodeURI($("#report_file_name").val());
    	$("#btn_close_download").click();
    	location.href = '/api/sem/download.php?action=2&rt=' + reporttt +'&rtt='+reporttype+'&=ac' + accounts + '&st='+systemtype+'&sdate=' + startdate+'&ndate='+enddate+'&fname='+filename;
    }
});