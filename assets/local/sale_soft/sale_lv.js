var EmployeeListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.employeeList = ko.observableArray([]);
    self_.listEmployee = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/saleStatisticsDay.php", data, function (results) {
            self_.employeeList.removeAll();
            $.each(results.list, function (index, employee) {
                employee.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2010607");
                self_.employeeList.push(employee);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchUserName: $("#searchUserName").val()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchUserName: $("#searchUserName").val()});
            });
        });
    };
    self_.editEmployee = function (employee) {
        if ($("#tr_" + employee.userid).css("display") == "none") {
            $(".second_tr").hide();
            $(".submit_btn").hide();
            $(".cancel_btn").hide();
            $("#tr_" + employee.userid).show();
            $("#submit_" + employee.userid).show();
            $("#cancel_" + employee.userid).show();
            $("#tr_" + employee.userid).attr("t", "edit");
        } else {
            if ($("#tr_" + employee.userid).attr("t") == "edit") {
                self_.cancelTr(employee);
            } else {
                $(".submit_btn").hide();
                $(".cancel_btn").hide();
                $("#submit_" + employee.userid).show();
                $("#cancel_" + employee.userid).show();
                $("#tr_" + employee.userid).attr("t", "edit");
            }
        }
        $("#tr_" + employee.userid + " input,#tr_" + employee.userid + " textarea").removeAttr("disabled");
    };

    self_.doEmployee = function (employee) {
        employee.action = 4;
        ycoa.ajaxLoadPost("/api/sale_soft/saleStatisticsDay.php", JSON.stringify(employee), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.cancelTr = function (employee) {
        $("#tr_" + employee.userid).hide();
        $("#submit_" + employee.userid).hide();
        $("#cancel_" + employee.userid).hide();
        $("#tr_" + employee.userid).removeAttr("t");
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchUserName: $("#searchUserName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val("");
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchUserName: name});
    });
    ko.applyBindings(EmployeeListViewModel, $("#dataTable")[0]);
    reLoadData({action: 2});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
});
function reLoadData(data) {
    data.action = 2;
    EmployeeListViewModel.listEmployee(data);
}