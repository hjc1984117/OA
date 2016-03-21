var SupplyListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.supplyList = ko.observableArray([]);
    self_.listSupply = function (data) {
        ycoa.ajaxLoadGet("/api/cost/supply.php", data, function (results) {
            self_.supplyList.removeAll();
            $.each(results.list, function (index, supply) {
                supply.status = supply.status.toString();
                supply.addtime = new Date(supply.addtime).format("yyyy-MM-dd");
                supply.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1040202");
                supply.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1040203");
                supply.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1040204");
                self_.supplyList.push(supply);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), searchDate: $("#searchDateTime").val()});
            });
        });
    };
    self_.delSupply = function (supply) {
        ycoa.UI.messageBox.confirm("确定要删除该条领料信息吗?~", function (btn) {
            if (btn) {
                supply.action = 3;
                ycoa.ajaxLoadPost('/api/cost/supply.php', JSON.stringify(supply), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.editSupply = function (supply) {
        $(".second_tr").hide();
        $("#tr_" + supply.id).show();
        $("#submit_" + supply.id).show();
        $("#cancel_" + supply.id).show();
    };
    self_.cancelTr = function (supply) {
        $("#tr_" + supply.id).hide();
        $("#submit_" + supply.id).hide();
        $("#cancel_" + supply.id).hide();
    };
    self_.showSupply = function (supply) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + supply.id).show();
        $("#cancel_" + supply.id).show();
    };
}();
$(function () {
    ko.applyBindings(SupplyListViewModel, $("#dataTable")[0]);
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
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#btn_submit_primary").click(function () {
        $("#add_supply_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_supply_form input[type='text'],#add_supply_form input[type='hidden'], #add_supply_form textarea").val("");
//        $("#add_supply_form #username").val(ycoa.user.username());
//        $("#add_supply_form #userid").val(ycoa.user.userid());
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#add_supply_form #username,#dataTable #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $("#userid", el.parent()).val(data.id);
        });
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/cost/supply.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/cost/supply.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $('.popovers').popover();
});
function reLoadData(data) {
    SupplyListViewModel.listSupply(data);
}