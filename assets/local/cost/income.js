var IncomeListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.incomeList = ko.observableArray([]);
    self_.listIncome = function (data) {
        ycoa.ajaxLoadGet("/api/cost/income.php", data, function (results) {
            self_.incomeList.removeAll();
            $.each(results.list, function (index, income) {
                income.addtime = new Date(income.addtime).format("yyyy-MM-dd");
                income.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1040602");
                income.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1040603");
                income.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1040604");
                self_.incomeList.push(income);
            });
            //$("#page_no").val(results.page_no);
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                ycoa.SESSION.PAGE.setPageSize(pageSize);
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delIncome = function (income) {
        ycoa.UI.messageBox.confirm("确定要删除该条行政收入信息吗?~", function (btn) {
            if (btn) {
                income.action = 3;
                ycoa.ajaxLoadPost('/api/cost/income.php', JSON.stringify(income), function (result) {
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
    self_.editIncome = function (income) {
        $(".second_tr").hide();
        $("#tr_" + income.id).show();
        $("#submit_" + income.id).show();
        $("#cancel_" + income.id).show();
    };
    self_.cancelTr = function (income) {
        $("#tr_" + income.id).hide();
        $("#submit_" + income.id).hide();
        $("#cancel_" + income.id).hide();
    };
    self_.showIncome = function (income) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + income.id).show();
        $("#cancel_" + income.id).show();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
    });
    $("#dataTable").searchAutoStatus([{id: 1, text: '通过'}, {id: 2, text: '未通过'}], function (d) {
        reLoadData({action: 1, status: d.id});
    },'按状态搜索');

    ko.applyBindings(IncomeListViewModel, $("#dataTable")[0]);

    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });

//    $("#dropdown-menu_income_status li").click(function () {
//        reLoadData({action: 1, status: $(this).attr("val"), pagesize: ycoa.SESSION.PAGE.getPageSize(), ageno: ycoa.SESSION.PAGE.getPageNo(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
//        $("#status").val($(this).attr('val'));
//    });

    $("#btn_submit_primary").click(function () {
        $("#add_income_form").submit();
    });

    $("#open_dialog_btn").click(function () {
        $("#add_income_form input[type='text'],#add_income_form input[type='hidden'], #add_income_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });

    $("#add_income_form #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $("#add_income_form #userid").val(data.id);
        });
    });

    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/cost/income.php", data, function (result) {
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
function reLoadData(data) {
    IncomeListViewModel.listIncome(data);
}