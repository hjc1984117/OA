var year_selecter = 0;
var isCreate = false;
var SaleStatisticsMouthViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.saleStatisticsMouthList = ko.observableArray([]);
    self_.listSaleStatisticsDay = function (data) {
        ycoa.ajaxLoadGet("/api/sale/saleStatisticsMouth.php", data, function (results) {
            self_.saleStatisticsMouthList.removeAll();
            $.each(results.list, function (index, saleStatistics) {
                var reltime = saleStatistics.reltime.split("-");
                saleStatistics.reltime = reltime[0] + "-" + reltime[1];
                saleStatistics.index = (index + 1);
                self_.saleStatisticsMouthList.push(saleStatistics);
            });
            for (var j = 1; j <= (results.current_year - results.start_year); j++) {
                array['year'].push({id: (results.start_year + j), text: (results.start_year + j) + '年'});
            }
            if (!isCreate) {
                $("#dataTable").searchAutoStatus(array['year'], function (d) {
                    year_selecter = d.id;
                }, '年份');
                isCreate = true;
            }
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchTime: $('#searchTime').val(), searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchTime: $('#searchTime').val(), searchChannel: $("#searchChannel").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
            if (results.is_manager) {
                create_total_tr_data(results.total_month_count);
            }
        });
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchTime: $('#searchTime').val(), searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchTime").val('');
    });
    $("#dataTable").searchAutoStatus(array['channel'], function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchChannel: d.id, searchTime: $('#searchTime').val(), searchName: $("#searchName").val()});
        $("#searchChannel").val(d.id);
    }, '按渠道筛选');
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchChannel: $("#searchChannel").val(), searchTime: $('#searchTime').val(), searchName: name});
    }, '按名称查找');
    $("#dataTable").searchAutoStatus(array['mouth'], function (d) {
        var date = (year_selecter == 0 ? '2015' : year_selecter) + "-" + d.id;
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchChannel: $("#searchChannel").val(), searchTime: date, searchName: $("#searchName").val()});
        $("#searchTime").val(date);
    }, '月份');
    ko.applyBindings(SaleStatisticsMouthViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});

    $(".date-picker-bind-mouseover").datepicker({
        format: 'yyyy-mm',
        weekStart: 1,
        autoclose: true,
        startView: 1,
        maxViewMode: 1,
        minViewMode: 1,
        forceParse: false,
        language: 'zh-CN'
    });

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $('.popovers').popover();
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        var channel = $("#toexcel_form #channel").val();
        if (start_time || end_time) {
            location.href = "/api/sale/saleStatisticsMouth.php?channel=" + channel + "&start_time=" + start_time + "&end_time=" + end_time + "&action=11";
        }
    });
});
function reLoadData(data) {
    SaleStatisticsMouthViewModel.listSaleStatisticsDay(data);
}
function create_total_tr_data(total_month_count) {
    $("#saleStatisticsDayList").find("#total_tr_data").remove();
    var html = "<tr style='background:#ffff99' id='total_tr_data'>"
    html += "<td></td>";
    html += "<td>0</td>";
    html += "<td>" + (total_month_count.reltime) + "</td>";
    html += "<td><span style='color:red;'>当月统计<\span></td>";
    html += "<td></td>";
    html += "<td>" + (total_month_count.into_count) + "</td>";
    html += "<td>" + (total_month_count.accept_count) + "</td>";
    html += "<td>" + (total_month_count.deal_count) + "</td>";
    html += "<td>" + (total_month_count.timely_count) + "</td>";
    html += "<td>" + (total_month_count.amount) + "</td>";
    html += "<td style='color:red;'>" + (total_month_count.commission) + "</td>";
    html += "<td>" + (total_month_count.loss_number) + "</td>";
    html += "<td style='color:blue;'>" + (total_month_count.loss_rate) + "</td>";
    html += "<td style='color:green;'>" + (total_month_count.timely_rate) + "</td>";
    html += "<td style='color:green;'>" + (total_month_count.timely_turnover_ratio) + "</td>";
    html += "<td style='color:red;'>" + (total_month_count.conversion_rate) + "</td>";
    html += "<td>" + (total_month_count.average_price) + "</td>";
    html += "<td></td>";
    html += "</tr>";
    $("#saleStatisticsDayList").prepend(html);
}
var array = {
    year: [{id: '2015', text: '2015年'}],
    mouth: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}],
    channel: [{id: '百度(PC端)', text: '百度(PC端)'}, {id: '百度(YD端)', text: '百度(YD端)'}, {id: '360', text: '360'}, {id: '搜狗', text: '搜狗'}, {id: 'UC神马', text: 'UC神马'}]
};