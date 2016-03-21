var attrclassesListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.attrclassesList = ko.observableArray([]);
    self_.listAttrClasses = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/attrclasses_list.php", data, function (results) {
            self_.attrclassesList.removeAll();
            $.each(results.list, function (index, attrclasses) {
                attrclasses.selfEdit = ycoa.SESSION.PERMIT.hasPagePermitButton("1020903");
                attrclasses.selfDelete = ycoa.SESSION.PERMIT.hasPagePermitButton("1020902");
                self_.attrclassesList.push(attrclasses);
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
    self_.selfEdit = function (attrclasses) {
        $("#edit_attrclasses_form #cid").attr("readonly", "");
        $("#edit_attrclasses_form input,#edit_attrclasses_form textarea").each(function () {
            $(this).val(attrclasses[$(this).attr('name')]);
        });
    };
    self_.selfDelete = function (attrclasses) {
        ycoa.UI.messageBox.confirm("确定删除配置<span style='color:red;'>" + (attrclasses.cid) + "</span>吗?", function (btn) {
            if (btn) {
                attrclasses.action = 2;
                ycoa.ajaxLoadPost("/api/attendance/attrclasses_list.php", JSON.stringify(attrclasses), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                });
            }
        });
    };
};
$(function () {
    ko.applyBindings(attrclassesListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $(".portlet-title input").val("");
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "下班时间", "etime");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "上班时间", "stime");
    $("#dataTable").searchUserName(function (name) {
        reLoadData(get_data());
    }, "班次", "cid");
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
    $.get(ycoa.getNoCacheUrl("/api/attendance/attrclasses_list.php"), {action: 2}, function (result) {
        $("#edit_attrclasses_form #userid").autoEditSelecter(result.list, function (d) {
            $("#edit_attrclasses_form #uname").val(d.id);
        });
    });
    $("#open_dialog_btn").click(function () {
        $("#edit_attrclasses_form input,#edit_attrclasses_form textarea").val("");
        $("#edit_attrclasses_form #cid").removeAttr("readonly");
    });
    $("#btn_submit_primary").click(function () {
        var data = $("#edit_attrclasses_form").serializeJson();
        if (typeof ($("#edit_attrclasses_form #cid").attr("readonly")) === 'undefined') {
            data.action = 1;
        } else {
            data.action = 3;
        }
        ycoa.ajaxLoadPost("/api/attendance/attrclasses_list.php", JSON.stringify(data), function (result) {
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
    attrclassesListViewModel.listAttrClasses(data);
}