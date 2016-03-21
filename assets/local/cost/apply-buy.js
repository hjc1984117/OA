var ApplyCostListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.applyCostList = ko.observableArray([]);
    self_.listApplyCost = function (data) {
        ycoa.ajaxLoadGet("/api/cost/apply_cost.php", data, function (results) {
            self_.applyCostList.removeAll();
            $.each(results.list, function (index, applyCost) {
                applyCost.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1040102");
                applyCost.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1040103");
                applyCost.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1040104");
                self_.applyCostList.push(applyCost);
            });
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
            }, function (pageNo) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
            });
        });
    };
    self_.stop = function (applyCost) {
        applyCost.opt = "stop";
        updateAC(applyCost);
    };
    self_.editApplyCost = function (applyCost) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + applyCost.id).show();
        $("#submit_" + applyCost.id).show();
        $("#cancel_" + applyCost.id).show();
    };
    self_.updateApplyCost = function (applyCost) {
        updateAC(applyCost);
    };
    self_.deleteApplyCost = function (applyCost) {
        ycoa.UI.messageBox.confirm("确定删除该申购信息?", function (btn) {
            if (btn) {
                applyCost.action = 3;
                ycoa.ajaxLoadPost("/api/cost/apply_cost.php", JSON.stringify(applyCost), function (result) {
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
    self_.cancelTr = function (applyCost) {
        $("#tr_" + applyCost.id).hide();
    };
    self_.showApplyCost = function (applyCost) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + applyCost.id).show();
        $("#cancel_" + applyCost.id).show();
    };
}();
$(function () {
    ko.applyBindings(ApplyCostListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $("#searchDateTime").val('');
        $("#searchUserName").val('');
        $("#deptid").val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: id, searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
        $("#deptid").val(id);
    });
    $("#dataTable").searchDateTime(function (searchdate) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: searchdate});
        $("#searchDateTime").val(searchdate);
    });
    $("#dataTable").searchUserName(function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), searchName: d, searchDate: $("#searchDateTime").val()});
        $("#searchUserName").val(d);
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_app_buy_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_app_buy_form input[type='text'],#add_app_buy_form input[type='hidden'], #add_app_buy_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#dept1_text").live("click", function () {
        var self_ = this;
        ycoa.UI.deptDropDownTree($(self_), function (node, el) {
            el.attr("value", node.text);
            el.parent().find("#dept2_id").val(node.id);
            if (node.parent == "#") {
                el.parent().find("#dept1_id").val(node.id);
            } else {
                el.parent().find("#dept1_id").val(node.parent);
            }
        }, false, {hasZHB: true});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#add_app_buy_form #username,#dataTable #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $("#userid", el.parent()).val(data.id);
        });
    });
    $(".apply_cost_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 4;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/cost/apply_cost.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $('.popovers').popover();
});
function updateApplyCost(applyCost) {
    applyCost.action = 4;
    ycoa.ajaxLoadPost("/api/cost/apply_cost.php", JSON.stringify(applyCost), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success(applyCost.msg + "成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error(applyCost.msg + "失败~");
        }
        ycoa.UI.block.hide();
    });
}

function updateAC(applyCost) {
    applyCost.action = 2;
    ycoa.ajaxLoadPost("/api/cost/apply_cost.php", JSON.stringify(applyCost), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}

function reLoadData(data) {
    ApplyCostListViewModel.listApplyCost(data);
}