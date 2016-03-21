var paidLeaveListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.paidLeaveList = ko.observableArray([]);
    self_.listPaidLeave = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/paidLeave.php", data, function (results) {
            self_.paidLeaveList.removeAll();
            $.each(results.list, function (index, paidLeave) {
                var status = paidLeave.status;
                paidLeave.starttime = new Date(paidLeave.starttime).format("yyyy-MM-dd hh:mm:ss");
                paidLeave.endtime = new Date(paidLeave.endtime).format("yyyy-MM-dd hh:mm:ss");
                paidLeave.paidLeaveHours = (paidLeave.hours / 24).toFixed(0) + '天' + (paidLeave.hours % 24).toFixed(0) + '小时';
                //状态{-1:终止,0:新建,1:待部门经理审核,2:部门经理退回,3:待行政审核,4:行政退回,5:完成,6:员工申请退回,7:部门经理申请退回,8:部门经理同意退回:9:行政同意退回}
                paidLeave.start = ycoa.SESSION.PERMIT.hasPagePermitButton("1020302") && ((status == 0 || status == 2 || status == 8) && paidLeave.userid == ycoa.user.userid());
                paidLeave.empApplyBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020303") && ((status == 1 || status == 4 || status == 9) && paidLeave.userid == ycoa.user.userid());
                paidLeave.deptManagerOk = ycoa.SESSION.PERMIT.hasPagePermitButton("1020304") && (status == 1 || status == 4 || status == 9 || status == 6);
                paidLeave.deptManagerBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020305") && (status == 1 || status == 4 || status == 9 || status == 6);
                paidLeave.deptManagerAgreeBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020306") && status == 6;
                paidLeave.deptManagerApplyBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020307") && status == 3;
                paidLeave.adminManagerOk = ycoa.SESSION.PERMIT.hasPagePermitButton("1020308") && (status == 3 || status == 7);
                paidLeave.adminManagerBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020309") && (status == 3 || status == 7);
                paidLeave.adminManagerAgreeBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020310") && status == 7;
                paidLeave.shutdown = ycoa.SESSION.PERMIT.hasPagePermitButton("1020311") && (status != -1 && status != 0 && status != 5);
                self_.paidLeaveList.push(paidLeave);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({deptid: $("#deptid").val(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.start = function (paidLeave) {
        paidLeave.status = 1;
        paidLeave.msg = "提交";
        updatePaidLeave(paidLeave);
    };
    self_.empApplyBack = function (paidLeave) {
        paidLeave.status = 6;
        paidLeave.msg = "申请退回";
        updatePaidLeave(paidLeave);
    };
    self_.deptManagerOk = function (paidLeave) {
        paidLeave.status = 3;
        paidLeave.msg = "部门经理审核";
        updatePaidLeave(paidLeave);
    };
    self_.deptManagerBack = function (paidLeave) {
        paidLeave.status = 2;
        paidLeave.msg = "部门经理退回";
        updatePaidLeave(paidLeave);
    };
    self_.deptManagerApplyBack = function (paidLeave) {
        paidLeave.status = 7;
        paidLeave.msg = "申请退回";
        updatePaidLeave(paidLeave);
    };
    self_.deptManagerAgreeBack = function (paidLeave) {
        paidLeave.status = 8;
        paidLeave.msg = "同意退回";
        updatePaidLeave(paidLeave);
    };
    self_.adminManagerOk = function (paidLeave) {
        paidLeave.status = 5;
        paidLeave.msg = "行政审核";
        updatePaidLeave(paidLeave);
    };
    self_.adminManagerBack = function (paidLeave) {
        paidLeave.status = 4;
        paidLeave.msg = "行政退回";
        updatePaidLeave(paidLeave);
    };
    self_.adminManagerAgreeBack = function (paidLeave) {
        paidLeave.status = 9;
        paidLeave.msg = "同意退回";
        updatePaidLeave(paidLeave);
    };
    self_.shutdown = function (paidLeave) {
        paidLeave.status = -1;
        paidLeave.msg = "终止";
        updatePaidLeave(paidLeave);
    };
};

$(function () {
    ko.applyBindings(paidLeaveListViewModel, $("#dataTable")[0]);
    reLoadData({});
    $("#dataTable").sort(function (data) {
        reLoadData({deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
        $("#deptid").val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_paidLeave_form input[type='text'],#add_paidLeave_form input[type='hidden']").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".date-picker-bind-mouseover").datetimepicker({
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $("#add_paidLeave_form #username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_paidLeave_form").submit();
    });
});
function updatePaidLeave(paidLeave) {
    paidLeave.action = 2;
    ycoa.ajaxLoadPost("/api/attendance/paidLeave.php", JSON.stringify(paidLeave), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success(paidLeave.msg + "成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error(paidLeave.msg + "失败~");
        }
        ycoa.UI.block.hide();
    });
}
function reLoadData(data) {
    paidLeaveListViewModel.listPaidLeave(data);
}