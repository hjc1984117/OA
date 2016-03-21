var query_fields = {dy: true, username: true, deptname: true}, can_create = false;
for (var x = 1; x <= 31; x++) {
    query_fields['m_' + x] = true;
}
var attusercheckListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.attusercheckList = ko.observableArray([]);
    self_.listAttUserCheck = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/attusercheck_list.php", data, function (results) {
            self_.attusercheckList.removeAll();
            $.each(results.list, function (index, attuser) {
                attuser.fields = query_fields;
                self_.attusercheckList.push(attuser);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = get_data();
                data.pagesize = pageSize;
                reLoadData(data);
            }, function (pageNo) {
                var data = get_data();
                data.pageno = pageNo;
                reLoadData(data);
            });
            if (!false) {
                var array_day = new Array();
                for (var i = parseInt(results.current_day); i >= 1; i--) {
                    array_day.push({id: i, text: i + "号"});
                }
                $("#toexcel_form #days").autoEditSelecter(array_day);

                var array_month = new Array();
                for (var i = parseInt(results.current_month); i >= 1; i--) {
                    array_month.push({id: i, text: i + "月", default: true});
                }
                $("#toexcel_form #month_text").autoEditSelecter(array_month);
            }
        });
    };
    self_.selfEdit = function (attuser) {
        $("#edit_attuser_form input").each(function () {
            $(this).val(attuser[$(this).attr('name')]);
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
        $("#month").val("");
        $("#dept").val("");
        $("#username").val("");
        reLoadData({action: 1});
    });
    $("#dataTable").searchDept(function (d) {
        $("#dept").val(d);
        reLoadData(get_data());
    });
    $("#dataTable").searchAutoStatus(array['month'], function (d) {
        $("#month").val(d.id);
        reLoadData(get_data());
    }, '按月份搜索');
    $("#dataTable").sort(function (d) {
        var data = get_data();
        data.sort = d.sort;
        data.sortname = d.sortname;
        reLoadData(data);
    });
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
        var data = get_data();
        data.sort = ycoa.SESSION.SORT.getSort();
        data.sortname = ycoa.SESSION.SORT.getSortName();
        reLoadData(data);
    });
    $("#outexcel").click(function () {
        $("#toexcel_form input").val("");
    });
    $("#toexcel_form #month_text").autoEditSelecter(array['month'], function (d) {
        $("#toexcel_form #month").val(d.id);
    });
    $("#toexcel_form #excel_type").autoRadio([{id: 11, text: '全表导出', default: true}, {id: 12, text: '异常导出'}], function (d) {
        if (d.id == 11) {
            $("#all_table").show();
            $("#day_table").hide();
        } else {
            $("#all_table").hide();
            $("#day_table").show();
        }
    });
    $(".month_items_list #month_items").autoRadio(array['days']);
    $("#toexcel_form #dept_name").click(function () {
        ycoa.UI.deptDropDownTree($(this), function (node, el) {
            el.attr("value", node.text);
            el.parent().find("#dept_id").val(node.id);
        });
    });
    $("#btn_toexcel_primary").click(function () {
        var month = $("#toexcel_form #month").val();
        var dept_id = $("#toexcel_form #dept_id").val();
        location.href = "/api/attendance/attusercheck_list.php?days=" + $("#toexcel_form #days").val().replace("号", "") + "&month=" + month + "&dept_id=" + dept_id + "&action=" + ($("#excel_type").val() ? $("#excel_type").val() : 11);
    });
});

function get_data() {
    var data = {month: $("#month").val(), dept: $("#dept").val()};
    data.action = 1;
    data.pageno = ycoa.SESSION.PAGE.getPageNo();
    data.pagesize = ycoa.SESSION.PAGE.getPageSize();
    return data;
}

function reLoadData(data) {
    attusercheckListViewModel.listAttUserCheck(data);
}

var array = {
    month: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}],
    days: function () {
        var array = new Array();
        array.push({id: 'dy', text: '日期', default: true});
        array.push({id: 'username', text: '用户', default: true});
        array.push({id: 'deptname', text: '部门', default: true});
        for (var i = 1; i <= 31; i++) {
            array.push({id: "m_" + i, text: i, default: true});
        }
        return array;
    }()
};