var isCreate = false;
var CustomerStatisticsMonthViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.customerStatisticsMonthList = ko.observableArray([]);
    //获取列表数据
    self_.listCustomerStatisticsMonth = function (data) {
        
        ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", data, function (results) {
            self_.customerStatisticsMonthList.removeAll();
            $.each(results.list, function(index, customerStatistics) {
                customerStatistics.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2010604");
                customerStatistics.index = (index + 1);
                self_.customerStatisticsMonthList.push(customerStatistics);
            });
           
        ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchTime: $('#searchTime').val(), searchName: $("#searchUserName").val(),searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchTime: $('#searchTime').val(), searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(),searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
}();
    
$(function () {
    ko.applyBindings(CustomerStatisticsMonthViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
    reLoadData({action: 1});
    $('#searchUserName').val("");
    $("#searchTeam").val("");
    $("#searchGroup").val("");
    $('#searchTime').val("");
    });
 
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 2, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),searchTime: $('#searchTime').val(), searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(),searchName: name});
    }, '按名称查找');
     $("#dataTable").searchAutoStatus(array['mouth'], function (d) {
        var tmpDate = new Date();
        var year_selecter = tmpDate.getFullYear();
        var date =  year_selecter + "-" + d.id;
        $("#customerStatisticsMonth").text(date);
        reLoadData({action: 2, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(), searchTime: date, searchName: $("#searchName").val()});
        $("#searchTime").val(date);
    }, '月份');
    $("#dataTable").searchAutoStatus(array['team'], function (d) {
     reLoadData({action: 2, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchTeam: d.id, searchTime: $('#searchTime').val(), searchName: $("#searchName").val(),searchGroup: $("#searchGroup").val()});
     $("#searchTeam").val(d.id);
    }, '按渠道筛选');
 
    $("#dataTable").searchAutoStatus(array['group'], function (d) {
    reLoadData({action: 2, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchGroup: d.id, searchTime: $('#searchTime').val(), searchName: $("#searchName").val(),searchTeam: $("#searchTeam").val()});
    $("#searchGroup").val(d.id);
    }, '按小组筛选');
   //导出EXCEL
    $('.popovers').popover();
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
   
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        var channel = $("#toexcel_form #channel").val();
        if (start_time || end_time) {
            location.href = "/api/second_sale/customer_statistics.php?start_time=" + start_time + "&end_time=" + end_time + "&action=12";
        }
    });
    //计算日期在列表显示
    var tmpDate = new Date();
    var customerStatisticsMonthDate = tmpDate.getFullYear() + "-" +(tmpDate.getMonth()+1);
    $("#customerStatisticsMonth").text(customerStatisticsMonthDate);
    
        $("#show_ranking").click(function () {
        ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", {action:13,time_unit:2,team:1}, function (result) {
            var html = "<ul>";
             html += "<li style='width:700px;'><span class='num'>排名</span><span class='presales' style='width:120px;'>姓名</span><span class='count'style='width:100px;'>业绩金额</span><span class='money'>换算金额</span><span class='money'>转化率</span><span class='money'>均价</span><span class='money'>晋升等级</span></li>";
            $.each(result, function (index, d) {   
                switch(index+1){
                            case 1:
                                style="background-color:#FF0000";
                                break;
                            case 2:
                                 style="background-color:#FF6600";
                                break;
                            case 3:
                                style="background-color:#FF9901";
                                break;
                            case 4:
                            default:
                                style="background-color:#8DB9F5";
                                break;
                        }
                html += "<li style='width:700px;'><span class='num' style='"+style+"'>" + (index + 1) + "</span><span class='presales' style='width:120px;'>" + (d.user_name) + "</span><span class='count'style='width:100px;'>" + (d.second_sales) + "</span><span class='money'>" + (d.proportion) + "</span><span class='money'>" + (d.conversion_rate) + "</span><span class='money'>" + (d.average_price) + "</span><span class='num'  style='background-color:"+level_style[d.promotion_level]+"'>" + (d.promotion_level) + "</span></li>";
            });
            html += "</ul>";
            $(".auto_tab_main .auto_tab_context div[var='1']").html(html);
            var width = $(window).width();
            var height = $(window).height();
            $(".auto_tab_main").animate({left: ((width - 450) / 2) + "px", top: ((height - 540) / 2) + "px"}, 500);
        });
    });
    $(".auto_tab_main .auto_tab_title li").click(function () {
        var self = $(this);
        var data = new Date().getTime();
        if (!self.hasClass("select")) {
            if ((data - parseInt($(".auto_tab_main").attr("t"))) > 500) {
                var v = self.attr("var");
                ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", {action: 13, time_unit:2,team:v}, function (result) {
                    var html = "<ul>";
                     html += "<li style='width:700px;'><span class='num'>排名</span><span class='presales' style='width:120px;'>姓名</span><span class='count'style='width:100px;'>业绩金额</span><span class='money'>换算金额</span><span class='money'>转化率</span><span class='money'>均价</span><span class='money'>晋升等级</span></li>";
                    $.each(result, function (index, d) {
                       switch(index+1){
                            case 1:
                                style="background-color:#FF0000";
                                break;
                            case 2:
                                 style="background-color:#FF6600";
                                break;
                            case 3:
                                style="background-color:#FF9901";
                                break;
                            case 4:
                            default:
                                style="background-color:#8DB9F5";
                                break;
                        }
                        html += "<li style='width:700px;'><span class='num' style='"+style+"'>" + (index + 1) + "</span><span class='presales' style='width:120px;'>" + (d.user_name) + "</span><span class='count'style='width:100px;'>" + (d.second_sales) + "</span><span class='money'>" + (d.proportion) + "</span><span class='money'>" + (d.conversion_rate) + "</span><span class='money'>" + (d.average_price) + "</span><span class='num' style='background-color:"+level_style[d.promotion_level]+"'>" + (d.promotion_level) + "</span></li>";
                    });
                    html += "</ul>";
                    $(".auto_tab_main .auto_tab_context div[var='" + v + "']").html(html);
                });
                $(".auto_tab_main").attr("t", data);
                $(".auto_tab_main li").removeClass("select");
                $(this).addClass("select");
                $(".auto_tab_context .open").animate({height: '0px'}, 300, function () {
                    $(this).removeClass("open");
                    $(this).hide();
                });
                $(".auto_tab_context div[var='" + v + "']").show();
                $(".auto_tab_context div[var='" + v + "']").animate({height: '458px'}, 300, function () {
                    $(this).addClass("open");
                });
            }
        }
    });
    $(".auto_tab_close .close_btn").click(function () {
        $(".auto_tab_main").animate({left: (($(window).width() + 500) * 1) + "px", top: "-600px"}, 500);
        $(".auto_tab_main").animate({left: '-500px', top: '-600px'});
    });
});

var array = {
    team: [{id: '百度', text: '百度'},{id:'360',text:'360'}],
    group:[{id:'单人组',text:'单人组'},{id:'双人组',text:'双人组'}],
    mouth: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}]
};
var level_style={
    '↑3':['#FF0000'],
    '↑2':['#FF6600'],
    '↑1':['#FF9901'],
    '-':['#CDCDCD'],
    '↓1':['#999999'],
    '↓2':['#676667']
    }
function reLoadData(data) {
    data.action=2;
    CustomerStatisticsMonthViewModel.listCustomerStatisticsMonth(data);
}

  