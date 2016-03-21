var WorkListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.workList = ko.observableArray([]);
    self_.listWork = function (data) {
        ycoa.ajaxLoadGet("/api/work/work_content.php", data, function (results) {
            self_.workList.removeAll();
            $.each(results.list, function (index, work) {
                work.add = ycoa.SESSION.PERMIT.hasPagePermitButton("1030101");
                work.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1030102");
                work.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1030103");
                work.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1030104");
                self_.workList.push(work);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                ycoa.SESSION.PAGE.setPageSize(pageSize);
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.deleWork = function (work) {
        WorkListViewModel.workList.remove(work);
    };
    self_.editWork = function (work) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + work.role_id).show();
        $("#submit_" + work.role_id).show();
        $("#cancel_" + work.role_id).show();
    };
    self_.cancelTr = function (work) {
        $("#tr_" + work.role_id).hide();
        $("#submit_" + work.role_id).hide();
        $("#cancel_" + work.role_id).hide();
    };
    self_.showWork = function (work) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + work.role_id).show();
        $("#cancel_" + work.role_id).show();
    };
}();
$(function () {
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(WorkListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $(".paging_nation button").live("click", function () {
        if ($(this).val()) {
            reLoadData({action: 1, pageno: $(this).val(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        }
    });
    $('.reload').click(function () {
        WorkListViewModel.listWork({action: 1, pageno: $('#page_no').val(), status: $("#status").val()});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#dropdown-menu_work_status li").click(function () {
        WorkListViewModel.listWork({action: 1, status: $(this).attr("val")});
        $("#status").val($(this).val());
    });
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    WorkListViewModel.listWork({action: 11, workids: ycoa.UI.checkBoxVal(), pageno: $('#page_no').val()});
                }
            });
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_work_form").submit();
    });
    $("#add_work_form #username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $("#open_dialog_btn").click(function () {
        $("#add_work_form input[type='text'],#add_work_form input[type='hidden'], #add_work_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/work_content.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#role_text").live("click", function () {
        ycoa.UI.roleSeleter({el: $(this), groupId: []}, function (node, el) {
            el.val(node.name);
            el.parent().children("#role_id").val(node.id);
        });
    });
    $('.popovers').popover();
});

function reLoadData(data) {
    WorkListViewModel.listWork(data);
}