var absenteeismListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.absenteeismList = ko.observableArray([]);
    self_.listAbsenteeism = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/absenteeism.php", data, function (results) {
            self_.absenteeismList.removeAll();
            $.each(results.list, function (index, absenteeism) {
                absenteeism.hasDelete = ycoa.SESSION.PERMIT.hasPagePermitButton('1020202');
                absenteeism.date = new Date(absenteeism.date).format("yyyy-MM-dd");
                absenteeism.abHours = (absenteeism.hours / 24).toFixed(0) + '天' + (absenteeism.hours % 24).toFixed(0) + '小时';
                self_.absenteeismList.push(absenteeism);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delete = function (absenteeism) {
        ycoa.UI.messageBox.confirm("确定删除该条旷工信息吗?", function (btn) {
            if (btn) {
                absenteeism.action = 2;
                ycoa.ajaxLoadPost("/api/attendance/absenteeism.php", JSON.stringify(absenteeism), function (result) {
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
};

$(function () {
    ko.applyBindings(absenteeismListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
    });
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
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_absenteeism_form input[type='text'],#add_absenteeism_form input[type='hidden']").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#add_absenteeism_form #username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_absenteeism_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/absenteeism.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
});

function reLoadData(data) {
    absenteeismListViewModel.listAbsenteeism(data);
}