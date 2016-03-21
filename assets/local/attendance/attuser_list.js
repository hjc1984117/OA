var attuserListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.attuserList = ko.observableArray([]);
    self_.listAttUser = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/attuser_list.php", data, function (results) {
            self_.attuserList.removeAll();
            $.each(results.list, function (index, attuser) {
                attuser.selfEdit = ycoa.SESSION.PERMIT.hasPagePermitButton("1020801");
                self_.attuserList.push(attuser);
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
        });
    };
    self_.selfEdit = function (attuser) {
        $("#edit_attuser_form input").each(function () {
            $(this).val(attuser[$(this).attr('name')]);
        });
    };
};
$(function () {
    ko.applyBindings(attuserListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $(".portlet-title input").val("");
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "姓名", "uname");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "考勤姓名", "aname");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "用户ID", "userid");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "考勤编号", "bid");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "考勤ID", "atid");
    $("#dataTable").sort(function (d) {
        var data = get_data()
        data.sort = d.sort;
        data.sortname = d.sortname;
        reLoadData(data);
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $.get(ycoa.getNoCacheUrl("/api/attendance/attuser_list.php"), {action: 2}, function (result) {
        $("#edit_attuser_form #userid").autoEditSelecter(result.list, function (d) {
            $("#edit_attuser_form #uname").val(d.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        var data = $("#edit_attuser_form").serializeJson();
        data.userid = (data.userid).split("(")[0];
        data.action = 3;
        ycoa.ajaxLoadPost("/api/attendance/attuser_list.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
        });
    });
});

function get_data() {
    var data = $(".portlet-title input").serializeJson();
    data.action = 1;
    data.pageno = ycoa.SESSION.PAGE.getPageNo();
    data.pagesize = ycoa.SESSION.PAGE.getPageSize();
    return data;
}

function reLoadData(data) {
    attuserListViewModel.listAttUser(data);
}