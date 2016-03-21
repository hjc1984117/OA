var customer_list;
var current_Date;
var SalecountListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.salecountList = ko.observableArray([]);
    self_.listSalecount = function (data) {
        ycoa.ajaxLoadGet("/api/sale/salecount.php", data, function (results) {
            self_.salecountList.removeAll();
            var currentDate = results.currentDate;
            customer_list = results.customer_list;
            current_Date = results.currentDate;
            $.each(results.list, function (index, salecount) {
                var addTime = salecount.addtime.split(" ")[0];
                salecount.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2010304");
                salecount.isTimely_txt = salecount.isTimely === 1 ? '√' : '';
                salecount.isQQTeach_txt = salecount.isQQTeach === 1 ? '√' : '';
                salecount.isTmallTeach_qj_txt = salecount.isTmallTeach_qj === 1 ? '√' : '';
                salecount.isTmallTeach_zy_txt = salecount.isTmallTeach_zy === 1 ? '√' : '';
                salecount.color_ = (salecount.status === 0 || salecount.status === 2) ? ((salecount.conflictWith === 0) ? "" : "color:#F00") : "";
                self_.salecountList.push(salecount);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {
                    action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, channel: $("#channel").val(), searchName: $("#searchUserName").val(),
                    searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchMouth: $("#searchMouth").val(), searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchMouth = "";
                }
                reLoadData(data);
            }, function (pageNo) {
                var data = {
                    action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), channel: $("#channel").val(),
                    searchName: $("#searchUserName").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchMouth: $("#searchMouth").val(),
                    searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchMouth = "";
                }
                reLoadData(data);
            });
        });
    };
    self_.cancelTr = function (salecount) {
        $("#tr_" + salecount.id).hide();
        $("#submit_" + salecount.id).hide();
        $("#cancel_" + salecount.id).hide();
    };
    self_.showSalecount = function (salecount) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + salecount.id).show();
        $("#cancel_" + salecount.id).show();
        if (!$("#form_" + salecount.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + salecount.id));
        }
        $("#tr_" + salecount.id + " input,#tr_" + salecount.id + " textarea").attr("disabled", "");
    };
}();
$(function () {
    ko.applyBindings(SalecountListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        var data = {
            action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), searchName: $("#searchUserName").val(),
            searchMouth: $("#searchMouth").val(), channel: $("#channel").val(), searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchMouth = "";
        }
        reLoadData(data);
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchMouth").val('');
        $('#searchSetmeal').val('');
        $('#searchStartTime').val('');
        $('#searchEndTime').val('');
        $('#channel').val('');
        $("#data_status").val('');
        $(".portlet-title .filter-option").html("不限");
    });
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $('#searchStartTime').val(), channel: $("#channel").val(),
            searchEndTime: $('#searchEndTime').val(), searchName: name, searchMouth: $("#searchMouth").val(), searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchMouth = "";
        }
        reLoadData(data);
    });
    $("#dataTable").searchAutoStatus(array['mouth'], function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), channel: $("#channel").val(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchMouth: d.id, searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
        };
        reLoadData(data);
        $("#searchMouth").val(d.id);
    }, '月份');
    $("#dataTable").searchAutoStatus([{id: '', text: '不限'}, {id: '普通', text: '普通'}, {id: '创业', text: '创业'}, {id: '财富', text: '财富'}], function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $('#searchStartTime').val(), channel: $("#channel").val(),
            searchEndTime: $('#searchEndTime').val(), searchName: $("#searchUserName").val(), searchMouth: $("#searchMouth").val(), searchSetmeal: d.id, status: $("#data_status").val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchMouth = "";
        }
        reLoadData(data);
        $('#searchSetmeal').val(d.id);
    }, "按照版本搜索");
    $("#dataTable").searchAutoStatus(array['channel'], function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $('#searchStartTime').val(), channel: d.id,
            searchEndTime: $('#searchEndTime').val(), searchName: $("#searchUserName").val(), searchMouth: $("#searchMouth").val(), searchSetmeal: $("#searchSetmeal").val(), status: $("#data_status").val()
        };
        reLoadData(data);
        $('#channel').val(d.id);
    }, '接入渠道');
    $("#dataTable").searchAutoStatus([{id: 0, text: '全部'}, {id: 1, text: '正常'}, {id: 2, text: '冲突'}, {id: 3, text: '未分配售后'}], function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $('#searchStartTime').val(), channel: $("#channel").val(),
            searchEndTime: $('#searchEndTime').val(), searchName: $("#searchUserName").val(), searchMouth: $("#searchMouth").val(), searchSetmeal: $("#searchSetmeal").val(), status: d.id
        };
        reLoadData(data);
        $("#data_status").val(d.id);
    }, "状态");
    $("#dataTable").searchDateTimeSlot(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchStartTime: d.start, searchEndTime: d.end, searchName: $("#searchUserName").val(), channel: $("#channel").val(), searchSetmeal: $('#searchSetmeal').val(), status: $("#data_status").val()
        };
        reLoadData(data);
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale/salecount.php?start_time=' + start_time + '&end_time=' + end_time + '&action=12';
        }
    });
});
function reLoadData(data) {
    data.changeNickName = true;
    SalecountListViewModel.listSalecount(data);
}
var array = {
    channel: [{id: '百度', text: '百度'}, {id: '360', text: '360'}, {id: '搜狗', text: '搜狗'}, {id: '百度直通车', text: '百度直通车'}, {id: '360直通车', text: '360直通车'}, {id: '搜狗直通车', text: '搜狗直通车'}],
    mouth: [{id: '', text: '不限'}, {id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}]
};