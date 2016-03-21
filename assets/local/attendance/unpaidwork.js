var unpaidworkListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.unpaidworkList = ko.observableArray([]);
    self_.listPaidLeave = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/unpaidwork.php", data, function (results) {
            self_.unpaidworkList.removeAll();
            $.each(results.list, function (index, unpaidwork) {
                var status = unpaidwork.status;
                unpaidwork.unpaidworkHours = (unpaidwork.hours / 24).toFixed(0) + '天' + (unpaidwork.hours % 24).toFixed(0) + '小时';
//              状态{-1:终止,0:新建,1:待部门经理审核,2:部门经理退回,3:待行政审核,4:行政退回,5:完成,6:员工申请退回,7:部门经理申请退回,8:部门经理同意退回:9:行政同意退回}
                unpaidwork.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1020601");
                unpaidwork.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1020601");
                unpaidwork.start = ycoa.SESSION.PERMIT.hasPagePermitButton("1020602") && ((status == 0 || status == 2 || status == 8) && unpaidwork.userid == ycoa.user.userid());
                unpaidwork.empApplyBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020603") && ((status == 1 || status == 9) && unpaidwork.userid == ycoa.user.userid());
                unpaidwork.deptManagerOk = ycoa.SESSION.PERMIT.hasPagePermitButton("1020604") && (status == 1 || status == 4 || status == 9 || status == 6);
                unpaidwork.deptManagerBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020605") && (status == 1 || status == 4 || status == 9 || status == 6);
                unpaidwork.deptManagerAgreeBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020606") && status == 6;
                unpaidwork.deptManagerApplyBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020607") && status == 3;
                unpaidwork.adminManagerOk = ycoa.SESSION.PERMIT.hasPagePermitButton("1020608") && (status == 3 || status == 7);
                unpaidwork.adminManagerBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020609") && (status == 3 || status == 7);
                unpaidwork.adminManagerAgreeBack = ycoa.SESSION.PERMIT.hasPagePermitButton("1020610") && status == 7;
                unpaidwork.shutdown = ycoa.SESSION.PERMIT.hasPagePermitButton("1020611") && (status != -1 && status != 0 && status != 5);
                self_.unpaidworkList.push(unpaidwork);
            });
            $("#page_no").val(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.editUnpaid = function (unpaidwork){
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + unpaidwork.id).show();
        $("#submit_" + unpaidwork.id).show();
        $("#cancel_" + unpaidwork.id).show();
    };
    self_.selfDelete = function (unpaidwork) {
        ycoa.UI.messageBox.confirm("确定删除该条停薪留职单吗?", function (btn) {
            if (btn) {
                unpaidwork.action = 3;
                ycoa.ajaxLoadPost("/api/attendance/unpaidwork.php", JSON.stringify(unpaidwork), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success("操作成功~");
                        reLoadData({});
                    } else {
                        ycoa.UI.toast.error("操作失败~");
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.start = function (unpaidwork) {
        unpaidwork.status = 1;
        unpaidwork.msg = "提交";
        updateUnpaidwork(unpaidwork);
    };
    self_.empApplyBack = function (unpaidwork) {
        unpaidwork.status = 6;
        unpaidwork.msg = "申请退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.deptManagerOk = function (unpaidwork) {
        unpaidwork.status = 3;
        unpaidwork.msg = "部门经理审核";
        updateUnpaidwork(unpaidwork);
    };
    self_.deptManagerBack = function (unpaidwork) {
        unpaidwork.status = 2;
        unpaidwork.msg = "部门经理退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.deptManagerApplyBack = function (unpaidwork) {
        unpaidwork.status = 7;
        unpaidwork.msg = "申请退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.deptManagerAgreeBack = function (unpaidwork) {
        unpaidwork.status = 8;
        unpaidwork.msg = "同意退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.adminManagerOk = function (unpaidwork) {
        unpaidwork.status = 5;
        unpaidwork.msg = "行政审核";
        updateUnpaidwork(unpaidwork);
    };
    self_.adminManagerBack = function (unpaidwork) {
        unpaidwork.status = 4;
        unpaidwork.msg = "行政退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.adminManagerAgreeBack = function (unpaidwork) {
        unpaidwork.status = 9;
        unpaidwork.msg = "同意退回";
        updateUnpaidwork(unpaidwork);
    };
    self_.shutdown = function (unpaidwork) {
        unpaidwork.status = -1;
        unpaidwork.msg = "终止";
        updateUnpaidwork(unpaidwork);
    };
};

$(function () {
    ko.applyBindings(unpaidworkListViewModel, $("#dataTable")[0]);
    reLoadData({});
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $('#deptid').val('');
        $('#searchUserName').val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    });
    $("#dataTable").searchDateTime(function (searchdate){
        reLoadData({searchDate: searchdate});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_unpaidwork_form input[type='text'],#add_unpaidwork_form input[type='hidden']").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#add_unpaidwork_form #username,#dataTable #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            el.parent().children("#userid").val(node.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_unpaidwork_form").submit();
    });

    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/attendance/unpaidwork.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    
 });
function updateUnpaidwork(unpaidwork) {
    unpaidwork.action = 2;
    ycoa.ajaxLoadPost("/api/attendance/unpaidwork.php", JSON.stringify(unpaidwork), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success(unpaidwork.msg + "成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error(unpaidwork.msg + "失败~");
        }
        ycoa.UI.block.hide();
    });
}
function reLoadData(data) {
    unpaidworkListViewModel.listPaidLeave(data);
}