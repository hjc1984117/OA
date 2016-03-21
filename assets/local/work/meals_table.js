var query_fields = {}, can_create = false;
for (var x = 1; x <= 31; x++) {
    query_fields['m_' + x] = true;
}
var attusercheckListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.attusercheckList = ko.observableArray([]);
    self_.listAttUserCheck = function (data) {
        ycoa.ajaxLoadGet("/api/work/meals_table.php", data, function (results) {
            self_.attusercheckList.removeAll();
            $.each(results.list, function (index, attuser) {
                attuser.fields = query_fields;
                self_.attusercheckList.push(attuser);
            });
        });
    };
};
$(function () {
    $(".portlet-body").css({overflow: 'auto'});
    var first_tr = $("#dataTable thead tr:first");
    var last_tr = $("#dataTable thead tr:last");
    for (var i = 1; i <= 31; i++) {
        first_tr.append("<th colspan='2' class='m_" + i + "'>" + i + "号</th>");
        last_tr.append("<th class='m_" + i + "'>上午</th><th class='m_" + i + "'>下午</th>");
    }
    ko.applyBindings(attusercheckListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $("#searchDate").val("");
        $("#searchDeptName").val("");
    });
    $("#dataTable").searchDept(function (d) {
        reLoadData({date: $("#searchDate").val(), dept: d});
    });
    $("#dataTable").searchAutoStatus(array['month'], function (d) {
        reLoadData({date: d.id, dept: $("#searchDeptName").val()});
    }, '按月份搜索', 'searchDate');

    $(".month_tool_btn_check_all").click(function () {
        $(".auto_radio_li").addClass("auto_radio_li_checked");
    });
    $(".month_tool_btn_uncheck_all").click(function () {
        $(".auto_radio_li").removeClass("auto_radio_li_checked");
    });
    $(".month_tool_btn_create").click(function () {
        $(".month_items_list .auto_radio_li").each(function () {
            var el = $(this);
            if (el.hasClass("auto_radio_li_checked")) {
                query_fields[el.attr("v")] = true;
                $("." + el.attr("v")).show();
            } else {
                query_fields[el.attr("v")] = false;
                $("." + el.attr("v")).hide();
            }
        });
        reLoadData({date: $("#searchDate").val(), dept: $("#searchDeptName").val()});
    });
    $(".month_items_list #month_items").autoRadio(array['days']);
});
function reLoadData(data) {
    data.action = 1;
    attusercheckListViewModel.listAttUserCheck(data);
}
var array = {
    month: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}],
    days: function () {
        var array = new Array();
        for (var i = 1; i <= 31; i++) {
            array.push({id: "m_" + i, text: i, default: true});
        }
        return array;
    }()
};