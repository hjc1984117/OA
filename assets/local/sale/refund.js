var RefundListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.refundList = ko.observableArray([]);
    self_.listRefund = function (data) {
        ycoa.ajaxLoadGet("/api/sale/refund.php", data, function (results) {
            self_.refundList.removeAll();
            customer_list = results.customer_list;
            $.each(results.list, function (index, refund) {
                refund.date = new Date(refund.date).format("yyyy-MM-dd");
                refund.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2010202");
                refund.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2010203");
                refund.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2010204");
                self_.refundList.push(refund);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val()});
            });
        });
    };
    self_.delRefund = function (refund) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                refund.action = 3;
                ycoa.ajaxLoadPost("/api/sale/refund.php", JSON.stringify(refund), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        RefundListViewModel.refundList.remove(refund);
                        reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.editRefund = function (refund) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + refund.id).show();
        $("#submit_" + refund.id).show();
        $("#cancel_" + refund.id).show();
        if (!$("#form_" + refund.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + refund.id));
        }
    };
    self_.cancelTr = function (refund) {
        $("#tr_" + refund.id).hide();
        $("#submit_" + refund.id).hide();
        $("#cancel_" + refund.id).hide();
    };
    self_.showRefund = function (refund) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + refund.id).show();
        $("#submit_" + refund.id).hide();
        $("#cancel_" + refund.id).show();
        $("#tr_" + refund.id + " input,#tr_" + refund.id + " textarea").attr("disabled", "");
        if (!$("#form_" + refund.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + refund.id));
        }
    };
}();
$(function () {
    ko.applyBindings(RefundListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    createRefundRate();
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
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#refund").click(function () {
        $("#add_refund_form input[type='text']").val("");
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
            location.href = '/api/sale/refund.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.retrieve = data.totalmoney - data.money;
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/refund.php", data, function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
});
function reLoadData(data) {
    data.action = 1;
    RefundListViewModel.listRefund(data);
}
function initEditSeleter(el) {
    $("#customer", el).click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6, 7]}, function (d, el) {
            el.val(d.name);
            $("#customer_id", el.parent()).val(d.id);
        });
    });
    $(".date-time-picker-bind-mouseover", el).datepicker({autoclose: true});
    el.attr('autoEditSelecter', 'autoEditSelecter');
}
function createRefundRate() {
    $.get(ycoa.getNoCacheUrl("/api/sale/refund.php"), {action: 5}, function (result) {
        if (result) {
            var refund_rate = result.refund_rate;
            var ind_refund_rate = result.ind_refund_rate;
            var html = "<span style='color: rgb(194, 22, 22);'>本月实际退款笔数总和:" + refund_rate + "</span>&nbsp;&nbsp;&nbsp;";
            html += "<span style='color:rgb(158, 158, 21);'>本月个人退款笔数总和" + ind_refund_rate + "</span>";
            $(".RefundRate").html(html);
        }
    });
}