var CashbackListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.cashbackList = ko.observableArray([]);
    self_.listCashback = function (data) {
        ycoa.ajaxLoadGet("/api/sale/cashback.php", data, function (results) {
            self_.cashbackList.removeAll();
            customer_list = results.customer_list;
            $.each(results.list, function (index, cashback) {
               // cashback.date = new Date(cashback.date).format("yyyy-MM-dd");
                cashback.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2010801");
                cashback.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2010802");
                cashback.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2010803");
                self_.cashbackList.push(cashback);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()});
            });
        });
    };
    self_.delCashback = function (cashback) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                cashback.action = 3;
                ycoa.ajaxLoadPost("/api/sale/cashback.php", JSON.stringify(cashback), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        CashbackListViewModel.cashbackList.remove(cashback);
                        reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editCashback = function (cashback) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + cashback.id).show();
        $("#submit_" + cashback.id).show();
        $("#cancel_" + cashback.id).show();
        $("#tr_" + cashback.id + " input,#tr_" + cashback.id + " textarea").removeAttr("disabled", "");
        //实际责任和日期不可改
        $("#tr_" + cashback.id + " table input[name='duty']").attr("disabled", true);
      //  $("#tr_" + cashback.id + " table input[name='date']").attr("disabled", true);
    };
    self_.cancelTr = function (cashback) {
        $("#tr_" + cashback.id).hide();
        $("#submit_" + cashback.id).hide();
        $("#cancel_" + cashback.id).hide();
    };
    self_.showCashback = function (cashback) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + cashback.id).show();
        $("#submit_" + cashback.id).hide();
        $("#cancel_" + cashback.id).show();
        $("#tr_" + cashback.id + " input,#tr_" + cashback.id + " textarea").attr("disabled", "");
    };
}();
$(function () {
    ko.applyBindings(CashbackListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), status: $("#status").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });

    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });

    $("#cashback").click(function () {
        $("#add_cashback_form input[type='text']").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale/cashback.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/cashback.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
     $(".date-time-picker-bind-mouseover").live("mouseover", function () {
        $(this).datetimepicker({autoclose: true});
    });
});
function reLoadData(data) {
    CashbackListViewModel.listCashback(data);
}