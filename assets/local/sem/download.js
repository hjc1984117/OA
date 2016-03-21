var SalecountListViewModel = new function() {
	var self_ = this;
	// self_.list = ko.observable("list");
	self_.acountList = ko.observableArray([]);
	self_.salecountList = ko.observableArray([]);
	self_.listAcount = function() {
		var pt=$("#systemtype").val();
		var data = {
			action : 3,
			pt:pt
		};
		ycoa.ajaxLoadGet("/api/sem/account.php", data, function(results) {
			self_.acountList.removeAll();
			$.each(results.list, function(index, testdata) {
				self_.acountList.push(testdata);
			});
		});
	};
	self_.listSalecount = function(data) {
		ycoa.ajaxLoadGet("/api/sem/download.php", data, function(results) {
			self_.salecountList.removeAll();
			$.each(results.list, function(index, testdata) {

				testdata.dele = ycoa.SESSION.PERMIT
						.hasPagePermitButton("3070202");
				;
				testdata.redown = ycoa.SESSION.PERMIT
						.hasPagePermitButton("3070201");
				;

				self_.salecountList.push(testdata);
			});

			// ----page info ----------------
			ycoa.SESSION.PAGE.setPageNo(results.page_no);
			ycoa.initPagingContainers($("#paging-container"), results,
					function(pageSize) {
						reLoadData({
							action : 1,
							pageno : ycoa.SESSION.PAGE.getPageNo(),
							pagesize : pageSize,
							sort : ycoa.SESSION.SORT.getSort(),
							sortname : ycoa.SESSION.SORT.getSortName()
						});
					}, function(pageNo) {
						reLoadData({
							action : 1,
							pageno : pageNo,
							pagesize : ycoa.SESSION.PAGE.getPageSize(),
							sort : ycoa.SESSION.SORT.getSort(),
							sortname : ycoa.SESSION.SORT.getSortName()
						});
					});
		});
	};

	self_.del = function(sdata) {
		ycoa.UI.messageBox.confirm('确认删除？', function(del) {
			if (del) {
				sdata.action = 1;
				ycoa.ajaxLoadPost("/api/sem/download.php", JSON
						.stringify(sdata), function(result) {
					if (result.code == 0) {
						ycoa.UI.toast.success(result.msg);
						reLoadData({
							action : 1
						});
					} else {
						ycoa.UI.toast.error(result.msg);
					}
					ycoa.UI.block.hide();
				});

			}
		});
	};
	self_.redown = function(sdata) {
		ycoa.UI.messageBox.confirm('确认重新下载该数据？',
				function(del) {
					if (del) {
						location.href = '/api/sem/download.php?action=3&id='
								+ sdata.id;
					}
				});
	};
}();

$(function() {
	ko.applyBindings(SalecountListViewModel);

	reLoadData({
		action : 1
	});
	

	$("#dataTable").reLoad(function() {
		reLoadData({
			action : 1,
			sort : ycoa.SESSION.SORT.getSort(),
			sortname : ycoa.SESSION.SORT.getSortName()
		});
	});

	$("#dataTable").sort(function(data) {
		var data = {
			action : 1,
			sort : data.sort,
			sortname : data.sortname,
			pageno : ycoa.SESSION.PAGE.getPageNo(),
			pagesize : ycoa.SESSION.PAGE.getPageSize()
		};
		reLoadData(data);
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
				$("#btn_close_ca").click();
	});

	$("#btn_submit_download").click(function() {
		$("#report_form").submit();
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

	$("#report_form #reporttt").autoRadio(basedata_reporttt);
	$("#report_form #systemtype").autoRadio(basedata_product_types);

	$("#dataTable thead input[id='checkall']").change(
			function() {
				if ($(this).attr("checked")) {
					$("#dataTable tbody input[type='checkbox']").attr(
							"checked", "checked");
				} else {
					$("#dataTable tbody input[type='checkbox']").removeAttr(
							"checked");
				}
			});

	$("#del_btn").click(function() {
		var ids = "";
		$("#dataTable .checkbox").each(function() {
			if ($(this).attr("checked")) {
				ids += ids == "" ? $(this).val() : "," + $(this).val();
			}
		});
		if (ids == "") {
			ycoa.UI.messageBox.alert('请先选择列表数据!');
		} else {
			var data = {
				id : ids
			};
			SalecountListViewModel.del(data);
		}
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
	
	
	$("#report_form .auto_radio_li").live("click",function(){
		defaultFileName();
	});
	$("#report_form #cond_startdate").change(function(){
		defaultFileName();
		initQuickDate();
	});
	$("#report_form #cond_enddate").change(function(){
		defaultFileName();
		initQuickDate();
	});
	
	$("#selectAccountBtn").click(function(){
		SalecountListViewModel.listAcount();
	});
	
	
	$("#report_form #reporttype").autoRadio(basedata_download_types,function(d){
		defaultFileName();
	});
	
});

function initQuickDate(){
	$("#quickDate").val("");
	$(".quickDateBox .auto_radio_li_checked").removeClass("auto_radio_li_checked");
}

/**
 * 获得默认的导出文件名称
 */
function defaultFileName(){
	var f_t=$("#reporttt").val();
	var f_time_s=$("#cond_startdate").val();
	var f_time_e=$("#cond_enddate").val();
	var f_st=$("#systemtype").val();
	var f_type=$("#reporttype").val();
	
	var fname=f_t==1?"ROI日报表":"交易数据报表";
	
	if(f_st !=""){
		var sts_name="";
		var sts=f_st.split(",");
		for(var i=0;i<sts.length;i++){
			for(var j=0;j<basedata_product_types.length;j++){
				if(basedata_product_types[j].id==sts[i]){
					sts_name+=sts_name==""?basedata_product_types[j].text:"_"+basedata_product_types[j].text;
				}
			}
		}
		fname+="_"+sts_name;
	}
	if(f_type=='2'){
		fname+="_汇总"
	}else{
		fname+="_分天"
	}
	if(f_time_s!="" || f_time_e!=""){
		fname+="@"+f_time_s+"~"+f_time_e;
	}
	
	if(fname!=""){
		$("#report_file_name").val(fname+".csv");
	}
}

function reLoadData(data) {
	SalecountListViewModel.listSalecount(data);
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
