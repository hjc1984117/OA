var ExpenditureViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.expenditureList = ko.observableArray([]);
    self_.listExpenditure = function (data) {
        ycoa.ajaxLoadGet("/api/cost/expenditure.php", data, function (results) {
            self_.expenditureList.removeAll();
            $.each(results.list, function (index, expenditure) {
                expenditure.date = new Date(expenditure.date).format("yyyy-MM-dd");
                expenditure.dele = true;
                expenditure.edit = true;
                expenditure.show = true;
                self_.expenditureList.push(expenditure);
            });
            $("#page_no").val(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delExpenditure = function (expenditure) {
        ycoa.UI.messageBox.confirm("确定要删除该条支出信息吗?~", function (btn) {
            if (btn) {
                expenditure.action = 3;
                ycoa.ajaxLoadPost('/api/cost/expenditure.php', JSON.stringify(expenditure), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.editExpenditure = function (expenditure) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + expenditure.id).show();
        $("#submit_" + expenditure.id).show();
        $("#cancel_" + expenditure.id).show();
        if (!$("#form_" + expenditure.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + expenditure.id));
        }
        $("#form_" + expenditure.id + " input,#form_" + expenditure.id + " textarea").removeAttr("readonly");

    };
    self_.cancelTr = function (expenditure) {
        $("#tr_" + expenditure.id).hide();
        $("#submit_" + expenditure.d).hide();
        $("#cancel_" + expenditure.id).hide();
    };
    self_.showExpenditure = function (expenditure) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + expenditure.id).show();
        $("#cancel_" + expenditure.id).show();
        if (!$("#form_" + expenditure.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + expenditure.id));
        }
        $("#form_" + expenditure.id + " input,#form_" + expenditure.id + " textarea").attr("readonly", '');
    };
}();
$(function () {

    ko.applyBindings(ExpenditureViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $('#deptid').val('');
        $('#status').val('');
        $('#searchUserName').val('');
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
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    reLoadData({action: 11, expenditureids: ycoa.UI.checkBoxVal(), pageno: $('#page_no').val()});
                }
            });
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_expenditure_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_expenditure_form input[type='text'],#add_expenditure_form input[type='hidden'], #add_expenditure_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".expenditure_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/cost/expenditure.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_expenditure_form #handling").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            el.parent().find("#handling_id").val(node.id);
        });
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#add_expenditure_form #e_project").autoEditSelecter(array['e_project']);
    $('.popovers').popover();
});

function reLoadData(data) {
    ExpenditureViewModel.listExpenditure(data);
}

var array = {
    e_project: [
        {id: '水费', text: '水费'},
        {id: '电费', text: '电费'},
        {id: '网费', text: '网费'},
        {id: '物管费', text: '物管费'},
        {id: '、垃圾处理费', text: '垃圾处理费'},
        {id: '植被租金', text: '植被租金'},
        {id: '各部门采购', text: '各部门采购'},
        {id: '综合部门采购', text: '综合部门采购'},
        {id: '季度活动支出', text: '季度活动支出'},
        {id: '安装维修等费用', text: '安装维修等费用'},
        {id: '邮费', text: '邮费'},
        {id: '运费', text: '运费'},
        {id: '车费', text: '车费'}
    ]
};

function initEditSeleter(el) {
    $("#e_project", el).autoEditSelecter(array["e_project"]);
    $("#handling", el).click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            el.parent().find("#handling_id").val(node.id);
        });
    });
    el.attr('autoEditSelecter', 'autoEditSelecter');
}