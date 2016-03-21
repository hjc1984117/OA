var customer_list;
var current_Date;
var ReportListViewModel = new function() {
	var self_ = this;
	self_.dataAccount = ko.observableArray([]);
	self_.dataProject = ko.observableArray([]);
	self_.dataKeyword = ko.observableArray([]);
	self_.dataKeywordHouer = ko.observableArray([]);
	self_.dataSearch = ko.observableArray([]);
	self_.dataUnit = ko.observableArray([]);
	
	self_.dataAccountHZ = ko.observableArray([]);

	self_.acountList = ko.observableArray([]);
	self_.listAcount = function() {
		var data = {
			action : 3
		};
		ycoa.ajaxLoadGet("/api/sem/account.php", data, function(results) {
			self_.acountList.removeAll();
			$.each(results.list, function(index, testdata) {
				self_.acountList.push(testdata);
			});
		});
	};
	self_.listData = function() {
		var accounts = encodeURI($("#accounts").val());
		var sdate = $("#cond_startdate").val();
		var edate = $("#cond_enddate").val();
		var reportnt = $("#reportnt").val();
		var reporttype = $("#reporttype").val();
		var reporttimetype = $("#reporttimetype").val();

		var sort = ycoa.SESSION.SORT.getSort();
		var sortname = ycoa.SESSION.SORT.getSortName();
		var data = {
			action : 1,
			accounts : accounts,
			sdate : sdate,
			edate : edate,
			reportnt : reportnt,
			reporttype : reporttype,
			reporttimetype : reporttimetype,
			sort : sort,
			sortname : sortname,
			pageno : ycoa.SESSION.PAGE.getPageNo(),
			pagesize : ycoa.SESSION.PAGE.getPageSize()
		};

		ycoa.ajaxLoadGet("/api/sem/ndata.php", data, function(results) {
			self_.dataAccount.removeAll();
			self_.dataProject.removeAll();
			self_.dataUnit.removeAll();
			self_.dataKeyword.removeAll();
			self_.dataSearch.removeAll();
			self_.dataAccountHZ.removeAll();
			self_.dataKeywordHouer.removeAll();

			$.each(results.list, function(index, row) {
				
				if(reportnt=='2'){
					self_.dataAccountHZ.push(row);
				}else{
					if (reporttimetype == '1') {
						self_.dataAccount.push(row);
					} else if (reporttimetype == '2') {
						self_.dataProject.push(row);
					} else if (reporttimetype == "3") {
						self_.dataUnit.push(row);
					} else if (reporttimetype == "4") {
						if(reporttype=="1"){
							self_.dataKeywordHouer.push(row);
						}else{
							self_.dataKeyword.push(row);
						}
					} else if (reporttimetype == "5") {
						self_.dataSearch.push(row);
					}
				}
			});
			$("#total_data_num").val(results.total_count);// 总得数据量

			// ----page info ----------------
			ycoa.SESSION.PAGE.setPageNo(results.page_no);
			ycoa.initPagingContainers($("#paging-container"), results,
					function(pageSize) {
						ycoa.SESSION.PAGE.setPageSize(pageSize);
						ReportListViewModel.listData();
					}, function(pageNo) {
						ycoa.SESSION.PAGE.setPageNo(pageNo);
						ReportListViewModel.listData();
					});
		});
	};

}();
$(function() {
	ko.applyBindings(ReportListViewModel);
	ReportListViewModel.listAcount();
	initReportCond();// --初始化报表的参数表单--------------

	$("#reportdatalistbox table").hide();// 隐藏显示，只有点生成报表的时候再显示

	// -----生成报表---------------------
	$("#btn_submit_report").click(function() {
		$("#report_form").submit();
	});
	// -----下载报表---------------------
	$("#btn_submit_export").click(function() {
		$("#report_export_form").submit();
	});

	// -----重新加载数据---------------------
	$("#dataTable").reLoad(function() {
		$("#report_form").submit();
	});

	$("#dataTable_01").sort(function(data) {
		ReportListViewModel.listData();
	});
	$("#dataTable_02").sort(function(data) {
		ReportListViewModel.listData();
	});
	$("#dataTable_03").sort(function(data) {
		ReportListViewModel.listData();
	});
	$("#dataTable_04").sort(function(data) {
		ReportListViewModel.listData();
	});
	$("#dataTable_05").sort(function(data) {
		ReportListViewModel.listData();
	});
	$("#dataTable_06").sort(function(data) {
		ReportListViewModel.listData();
	});

	$("#search_account_total").click(function() {
		if ($(this).attr("checked")) {
			$("#searchChannelAccount .accids").attr("checked", "checked");
		} else {
			$("#searchChannelAccount .accids").removeAttr("checked");
		}
	});
	$("#btn_submit_ca").click(
			function() {
				var ids = "";
				var names = "";
				$("#searchChannelAccount .accids").each(
						function() {
							if ($(this).attr("checked")) {
								ids += ids == "" ? $(this).val() : ","
										+ $(this).val();
								names += names == "" ? $(this).attr('data')
										: "," + $(this).attr('data');
							}
						});
				$("#accounts").val(ids);
				$("#accountnames").text(names);
				$("#accountnames").attr("title", names);
				$("#btn_close_ca").click();
			});

	$(".date-time-picker-bind-mouseover").live("mouseover", function() {
		$(this).datetimepicker({
			autoclose : true
		});
	});

	$(".date-picker-bind-mouseover").live("mouseover", function() {
		$(this).datepicker({
			autoclose : true
		});
	});

	$("#report_form #reportnt")
			.autoRadio(
					basedata_reportnt,
					function(d) {
						if (d.id == 2) {
							$("#reporttimetype_content")
									.html(
											'<i class="fa"></i><input type="hidden"  id="reporttimetype" name="reporttimetype" value="1">');
							$("#reporttimetype").autoRadio(
									basedata_report_singletimetypes,
									function(d) {
										initReporttype(d);
									});
						} else {
							$("#reporttimetype_content")
									.html(
											'<i class="fa"></i><input type="hidden"  id="reporttimetype" name="reporttimetype" value="1">');
							$("#reporttimetype").autoRadio(
									basedata_report_timetypes, function(d) {
										initReporttype(d);
									});
						}
						var data = {
							id : 1
						};
						initReporttype(data);
					});

	$("#report_form #reporttype").autoRadio(basedata_report_types);

	$("#report_form #reporttimetype").autoRadio(basedata_report_timetypes,
			function(d) {
				initReporttype(d);
			});
	$("#report_form #quickDate").autoRadio(basedata_report_quickdate,
			function(d) {
				var today = getTodayDate();

				switch (d.id) {
				case '1':
					$("#cond_startdate").val(today);
					$("#cond_enddate").val(today);
					break;
				case '2':
					var yesday = addDate(today, -1);
					$("#cond_startdate").val(yesday);
					$("#cond_enddate").val(yesday);
					break;
				case '3':
					var end = addDate(today, -1);
					var start = addDate(today, -8);
					$("#cond_startdate").val(start);
					$("#cond_enddate").val(end);
					break;
				case '4':
					var end = addDate(today, -1);
					var start = addDate(today, -31);
					$("#cond_startdate").val(start);
					$("#cond_enddate").val(end);
					break;
				}
			});

	$("#open_dialog_export_btn").click(function() {
		var sdate = $("#cond_startdate").val();
		var edate = $("#cond_enddate").val();

		var reportnt = $("#reportnt").val();
		var reporttype = $("#reporttype").val();
		var reporttimetype = $("#reporttimetype").val();

		var fname = "";
		if (reportnt == '1') {
			fname = "分账户";
		} else if (reportnt == '2') {
			fname = "汇总";
		}
		switch (reporttimetype) {
			case '1':
				fname += "_账户";
				break;
			case '2':
				fname += "_计划";
				break;
			case '3':
				fname += "_单元";
				break;
			case '4':
				fname += "_关键词";
				break;
			case '5':
				fname += "_搜索词";
				break;
			default:
				break;
		}
		switch (reporttype) {
			case '1':
				fname += "_分时";
				break;
			case '2':
				fname += "_分日";
				break;
			case '3':
				fname += "_分周";
				break;
			case '4':
				fname += "_分月";
				break;
			default:
				break;
		}
		fname+="@"+sdate+"~"+edate+".cvs";
		
		$("#export_file_name").val(fname);

	});

	
	$(".search_account_list_item").live("click",function(event){
		var obj=$(this).find(".accids");
		if($(obj).attr("checked")){
			$(obj).removeAttr("checked");
		}else{
			$(obj).attr("checked","checked");
		}
		event.stopPropagation();
	});
	$(".search_account_list_item .accids").live("click",function(event){
		event.stopPropagation();
	});
	
	$("#cond_startdate").change(function(){
		$("#quickDate").val("");
		$(".quickDateBox .auto_radio_li_checked").removeClass("auto_radio_li_checked");
	});
	$("#cond_enddate").change(function(){
		$("#quickDate").val("");
		$(".quickDateBox .auto_radio_li_checked").removeClass("auto_radio_li_checked");
	});
});

function initReporttype(obj) {
	if (obj.id == 5) {
		$("#rreporttype_content")
				.html(
						'<i class="fa"></i><input type="hidden"  id="reporttype" name="reporttype" value="2">');
		$("#reporttype").autoRadio(basedata_report_types_search);
	} else {
		$("#rreporttype_content")
				.html(
						'<i class="fa"></i><input type="hidden"  id="reporttype" name="reporttype" value="1">');
		$("#reporttype").autoRadio(basedata_report_types);
	}
}

function initReportCond() {
	$("#accounts").val('');
	$("#accountnames").html('');
	$("#accountnames").attr("title", "");
	$("#cond_startdate").val('');
	$("#cond_enddate").val('');
}
function reLoadDataAccount(data) {

	ReportListViewModel.listData(data);
}

function addDate(date, days) {
	var d = new Date(date);
	d.setDate(d.getDate() + days);
	var month = d.getMonth() + 1;
	var day = d.getDate();
	if (month < 10) {
		month = "0" + month;
	}
	if (day < 10) {
		day = "0" + day;
	}
	var val = d.getFullYear() + "-" + month + "-" + day;
	return val;
}

function getTodayDate() {
	var d = new Date();
	var month = d.getMonth() + 1;
	var day = d.getDate();
	if (month < 10) {
		month = "0" + month;
	}
	if (day < 10) {
		day = "0" + day;
	}
	var val = d.getFullYear() + "-" + month + "-" + day;
	return val;
}
