$('#report_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
    	reportnt: {
            required: true
        },
        accounts: {
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
	    reporttimetype:{
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
    	ycoa.SESSION.PAGE.setPageSize(20);
    	ycoa.SESSION.PAGE.setPageNo(1);
    	ycoa.SESSION.SORT.setSort("");
        ycoa.SESSION.SORT.setSortName("");
    	ReportListViewModel.listData();
        var reporttimetype=$("#reporttimetype").val();
        var reportnt = $("#reportnt").val();
        var reporttype = $("#reporttype").val();
    	$("#reportdatalistbox table").hide();
    	if(reportnt=='2'){
    		$("#dataTable_06").show();
    	}else{
    		if(reporttimetype=="4" && reporttype=="1"){//关键词，小时数据，没有排名
    			$("#dataTable_0"+reporttimetype+"_houer").show();
    		}else{
    			$("#dataTable_0"+reporttimetype).show();
    		}
    	}
    	$("#btn_close_report").click();
    }
});

$('#report_export_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
    	export_file_name: {
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
    	var total=$("#total_data_num").val();
    	total=parseInt(total);
    	if(total >0){
    		var accounts = encodeURI($("#accounts").val());
    		var sdate = $("#cond_startdate").val();
    		var edate = $("#cond_enddate").val();
    		var reportnt = $("#reportnt").val();
    		var reporttype = $("#reporttype").val();
    		var reporttimetype = $("#reporttimetype").val();
    		var fname=encodeURI($("#export_file_name").val());
    		
    		
    		var tourl="/api/sem/ndata.php?action=2&accounts=" + accounts + "&sdate="+sdate+"&edate="+edate+"&reportnt="+reportnt+"&reporttype="+reporttype+"&reporttimetype="+reporttimetype+"&fname="+fname;
    		 location.href = tourl;
    		 $("#btn_close_export").click();
    	}else{
    		alert("无数据可以导出。");
    	}
    	
    }
});