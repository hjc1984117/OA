var lateListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.lateList = ko.observableArray([]);
    self_.listAbsenteeism = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/late.php", data, function (results) {
            self_.lateList.removeAll();
            $.each(results.list, function (index, late) {
                late.date = new Date(late.date).format("yyyy-MM-dd");
                late.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1020703");
                late.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1020702");
                self_.lateList.push(late);
            });
            $("#page_no").val(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };

    self_.editLate = function (late) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + late.id).show();
        $("#submit_" + late.id).show();
        $("#cancel_" + late.id).show();
    };
    self_.selfDelete = function (late) {
        ycoa.UI.messageBox.confirm("确定删除该条迟到记录吗?", function (btn) {
            if (btn) {
                late.action = 3;
                ycoa.ajaxLoadPost("/api/attendance/late.php", JSON.stringify(late), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success("操作成功~");
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error("操作失败~");
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.cancelTr = function (late) {
        $("#tr_" + late.id).hide();
        $("#submit_" + late.id).hide();
        $("#cancel_" + late.id).hide();
    };
};

$(function () {
    ko.applyBindings(lateListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $("#searchUserName").val('');
        $("#deptid").val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $("#dataTable").searchDateTime(function (searchdate) {
        reLoadData({action: 1, searchDate: searchdate});
    });
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: data.sort, sortname: data.sortname});
    });

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_late_form input[type='text'],#add_late_form input[type='hidden']").val("");
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
    $("#add_late_form #username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $(".second_table #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $(".second_table #userid").val(data.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_late_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/late.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        ycoa.ajaxLoadPost("/api/attendance/late.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error("操作失败~");
            }
            ycoa.UI.block.hide();
        });
    });

});

function reLoadData(data) {
    lateListViewModel.listAbsenteeism(data);
}