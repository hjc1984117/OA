var RefundListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.refundList = ko.observableArray([]);
    self_.listRefund = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/refund.php", data, function (results) {
            self_.refundList.removeAll();
            customer_list = results.customer_list;
            $.each(results.list, function (index, refund) {
              //  refund.date = new Date(refund.date).format("yyyy-MM-dd");
                refund.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3030202");
                refund.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3030203");
                refund.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3030205");
                self_.refundList.push(refund);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), refund_type: $("#searchRefundType").val()});
            }, function (pageNo) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), refund_type: $("#searchRefundType").val()});
            });
        });
    };
    self_.delRefund = function (refund) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                refund.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/refund.php", JSON.stringify(refund), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        RefundListViewModel.refundList.remove(refund);
                        reLoadData({action: 1});
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
        $("#tr_" + refund.id + " input,#tr_" + refund.id + " textarea").removeAttr("disabled");
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
        if (!$("#form_" + refund.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + refund.id));
        }
        $("#tr_" + refund.id + " input,#tr_" + refund.id + " textarea").attr("disabled", "");
    };
    self_.showLog = function (refund) {
        var w_id = refund.id;
        $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
            $(this).addClass("data_open");
        });
        $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
        $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 10, obj_id: w_id}, function (result) {
            if (result.list.length > 0 && result.list) {
                var html = "<ul>";
                $.each(result.list, function (idnex, d) {
                    html += "<li>[" + d.addtime + " (" + d.username + ")] <br>" + d.changed_desc + "</li>";
                });
                html += "</ul>";
                $("#dataLog_detail_" + w_id).html(html);
            } else {
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/workflowLog_detail_nodata.png'>");
            }
        });
    }
}();
$(function () {
    ko.applyBindings(RefundListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), refund_type: $("#searchRefundType").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchRefundType").val('');
        $("#searchDateTime").val();
    });

    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: name, searchTime: $("#searchDateTime").val(), refund_type: $("#searchRefundType").val()});
    });
    $("#dataTable").searchDateTime(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchTime: name, refund_type: $("#searchRefundType").val()});
    }, '按照日期查询');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '部分退款', text: '部分退款'}, {id: '全额退款', text: '全额退款'}], function (d) {
        $("#searchRefundType").val(d.id);
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), refund_type: d.id});
    }, '按退款类型搜索');
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $(".date-time-picker-bind-mouseover").live("mouseover", function () {
    $(this).datetimepicker({autoclose: true});
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
            location.href = '/api/sale_soft/refund.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table select").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/refund.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
});
function reLoadData(data) {
    data.action = 1;
    RefundListViewModel.listRefund(data);
}

function initEditSeleter(el) {
    $("#refund_type", el).autoRadio(array["refund_type"]);
    $("#refund_rate", el).autoRadio(array["refund_rate"]);
    $("#ind_refund_rate", el).autoRadio(array["ind_refund_rate"]);
    $("#duty", el).autoRadio(array["duty"]);
    $("#status", el).autoRadio(array["status"]);
    $("#reason", el).autoRadio(array["reason"]);
    $("#customer", el).click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (d, el) {
            el.val(d.name);
            $("#customer_id", el.parent()).val(d.id);
        });
    });
    el.attr('autoEditSelecter', 'autoEditSelecter');
}

var array = {
    refund_type: [{id: '部分退款', text: '部分退款'}, {id: '全额退款', text: '全额退款'}, {id: '未退', text: '未退'}],
    refund_rate: [{id: 0.00, text: '0.00'}, {id: 0.50, text: '0.50'}, {id: 1.00, text: '1.00'}],
    ind_refund_rate: [{id: '0.00', text: '0.00'}, {id: '0.50', text: '0.50'}, {id: '1.00', text: '1.00'}],
    duty: [{id: '售前', text: '售前'}, {id: '售后', text: '售后'}, {id: '部门', text: '部门'}, {id: '共同', text: '共同'}],
    status: [{id: '转账', text: '转账'}, {id: '店铺', text: '店铺'}, {id: '未退', text: '未退'}],
    reason: [{id: '个人', text: '个人'}, {id: '货源', text: '货源'}, {id: '二销', text: '二销'}, {id: '软件', text: '软件'}, {id: '服务', text: '服务'}, {id: '收货', text: '收货'}, {id: '其他', text: '其他'}]
};