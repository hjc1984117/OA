var year_selecter = 0;
var isCreate = false;
var CustomerStatisticsDayViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.customerStatisticsDayList = ko.observableArray([]);
    //获取列表数据
    self_.listCustomerStatisticsDay = function (data) {
        
        ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", data, function (results) {
            self_.customerStatisticsDayList.removeAll();
            $.each(results.list, function(index, customerStatistics) {
            customerStatistics.show = true;
            customerStatistics.edit = true;
            if(!isToday(customerStatistics.date)){
                 customerStatistics.edit = false;
                 if(customerStatistics.is_manager){
                     customerStatistics.edit = true;
                 }
            }
            customerStatistics.index = (index + 1);
            if(customerStatistics.index == 1){
                 customerStatistics.edit = false;
            }
            customerStatistics.date_show = customerStatistics.date.substring(0,10);
            self_.customerStatisticsDayList.push(customerStatistics);           
        });
         //在列表上方实时显示流量业绩、实物业绩、装修业绩的笔数和金额
        var total_statistics = results.total_statistics_data;
        $("#title_statistics_total").html("合计：二销（"+total_statistics.total.count+"笔 | "+total_statistics.total.money+"元）");
        $("#title_statistics_baidu").html("百度：流量（"+total_statistics.channel_baidu.platform.count+"笔 | "+total_statistics.channel_baidu.platform.money+"元） \n\
         实物（"+total_statistics.channel_baidu.physica.count+"笔 | "+total_statistics.channel_baidu.physica.money+"元） \n\
         装修（"+total_statistics.channel_baidu.decoration.count+"笔 | "+total_statistics.channel_baidu.decoration.money+"元）     "
                );
        $("#title_statistics_360").html("360：流量（"+total_statistics.channel_360.platform.count+"笔 | "+total_statistics.channel_360.platform.money+"元） \n\
         实物（"+total_statistics.channel_360.physica.count+"笔 | "+total_statistics.channel_360.physica.money+"元） \n\
         装修（"+total_statistics.channel_360.decoration.count+"笔 | "+total_statistics.channel_360.decoration.money+"元）     "
                );
        ycoa.SESSION.PAGE.setPageNo(results.page_no);
        ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchTime: $('#searchTime').val(), searchName: $("#searchUserName").val(),searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchTime: $('#searchTime').val(), searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(),searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    
    self_.showCustomerStatistics = function (customerStatistics) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + customerStatistics.id).show();
        $("#cancel_" + customerStatistics.id).show();
        $("#tr_"+ customerStatistics.id +" table input").attr("disabled", "disable");  
    };
    
    self_.cancelTr = function (customerStatistics) {
       $("#tr_" + customerStatistics.id).hide();
       $("#submit_" + customerStatistics.id).hide();
       $("#cancel_" + customerStatistics.id).hide();
   };
   
    self_.editCustomerStatistics =function(customerStatistics){
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + customerStatistics.id).show();
        $("#submit_" + customerStatistics.id).show();
        $("#cancel_" + customerStatistics.id).show();
        $("#tr_"+ customerStatistics.id +" table input").attr("disabled", "disable");
        $("#tr_"+ customerStatistics.id +" table input[name='change_Q']").attr("disabled", false);
        if( customerStatistics.is_manager){
              $("#tr_"+ customerStatistics.id +" table input").attr("disabled",false);
              $("#tr_"+ customerStatistics.id +" table input[name='user_name']").attr("disabled",true);
         }
        if(!isToday(customerStatistics.date))
        {
            if( customerStatistics.is_manager){
                 $("#tr_"+ customerStatistics.id +" table input").attr("disabled",false);
                 $("#tr_"+ customerStatistics.id +" table input[name='user_name']").attr("disabled",true);
            }
            else{
                $("#tr_"+ customerStatistics.id +" table input").attr("disabled","disable");
            }   
        }
    };
    
    self_.doEditCustomerStatistics = function (customerStatistics) {
      //正则验证只能输入正整数和0
        var re = /^([1-9]\d*|[0]{1,1})$/ ; 
        $("#tr_"+ customerStatistics.id +" table input").each(function(){
           if($(this).attr("disabled") != "disabled" && $(this).attr("name")!= "refund_num" && !re.test( $(this).val()))
             { 
                  ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>请输入正确的数值</div>~");
                  exit;
             }
        });
        customerStatistics.action=1;
        ycoa.ajaxLoadPost("/api/second_sale/customer_statistics.php", JSON.stringify(customerStatistics), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
}();
    
$(function () {
    ko.applyBindings(CustomerStatisticsDayViewModel, $("#dataTable")[0]);
        reLoadData({action: 1});
        $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val("");
        $("#searchTeam").val("");
        $("#searchGroup").val("");
        $('#searchTime').val("");
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),searchTime: $('#searchTime').val(), searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(),searchName: name});
    }, '按名称查找');
    
    $("#dataTable").searchDateTime(function (time) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchTeam: $("#searchTeam").val(),searchGroup: $("#searchGroup").val(), searchTime: time, searchName: $("#searchName").val()});
        $("#searchTime").val(time);
    }, '按照日期查询');
    
    $("#dataTable").searchAutoStatus(array['team'], function (d) {
     reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchTeam: d.id, searchTime: $('#searchTime').val(), searchName: $("#searchName").val(),searchGroup: $("#searchGroup").val()});
     $("#searchTeam").val(d.id);
    }, '按渠道筛选');
 
    $("#dataTable").searchAutoStatus(array['group'], function (d) {
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchGroup: d.id, searchTime: $('#searchTime').val(), searchName: $("#searchName").val(),searchTeam: $("#searchTeam").val()});
    $("#searchGroup").val(d.id);
    }, '按小组筛选');
   //导出EXCEL
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        
        var start_timestamp = Date.parse(start_time);
        var end_timestamp = Date.parse(end_time);
        if(start_timestamp > end_timestamp)
        {
             ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>开始时间不能大于结束时间</div>~");
             exit;
        }
        if (start_time || end_time) {
            location.href = "/api/second_sale/customer_statistics.php?start_time=" + start_time + "&end_time=" + end_time + "&action=11";
        }
    });
    $("#show_ranking").click(function () {
        ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", {action:13,time_unit:1,team:1}, function (result) {
            var style="";
            var html = "<ul>";
             html += "<li><span class='num'>排名</span><span class='presales' style='width:100px;'>姓名</span><span class='count'style='width:120px;'>二销金额</span><span class='money'>换算金额</span></li>";
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
                html += "<li><span class='num' style='"+style+"'>" + (index + 1) + "</span><span class='presales' style='width:120px;'>" + (d.user_name) + "</span><span class='count'style='width:100px;'>" + (d.sum) + "</span><span class='money'>" + (d.proportion) + "</span></li>";
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
                ycoa.ajaxLoadGet("/api/second_sale/customer_statistics.php", {action: 13, time_unit:1,team:v}, function (result) {
                    var html = "<ul>";
                    html += "<li><span class='num'>排名</span><span class='presales' style='width:100px;'>姓名</span><span class='count'style='width:120px;'>二销金额</span><span class='money'>换算金额</span></li>";
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
                        html += "<li><span class='num' style='"+style+"'>" + (index + 1) + "</span><span class='presales' style='width:120px;'>" + (d.user_name) + "</span><span class='count' style='width:100px;'>" + (d.sum) + "</span><span class='money'>" + (d.proportion)+ "</span></li>";
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
    group:[{id:'单人组',text:'单人组'},{id:'双人组',text:'双人组'}]
};

function reLoadData(data) {
    data.action=1;
    CustomerStatisticsDayViewModel.listCustomerStatisticsDay(data);
}
//判断传入的时间是否是当天
function isToday(date){
var now = new Date(); 
var nowStr = now.format("yyyy-MM-dd");
var date_temp = date.substring(0,10);
return nowStr==date_temp;
}