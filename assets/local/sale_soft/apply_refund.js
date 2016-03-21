var customer_list = {};
var ApplyRefundListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.applyRefundList = ko.observableArray([]);
    self_.listRefund = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/apply_refund.php", data, function (results) {
            customer_list = results.user_list;
            self_.applyRefundList.removeAll();
            $.each(results.list, function (index, refund) {
                refund.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3031202");
                refund.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3031203");
                refund.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3031204");
                refund.do_int = ycoa.SESSION.PERMIT.hasPagePermitButton("3031207") && (refund.done_userid === 0);
                refund.done = ycoa.SESSION.PERMIT.hasPagePermitButton("3031208") && (refund.done_userid !== 0) && ((refund.end_time === "") || (refund.end_time === null));
                self_.applyRefundList.push(refund);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val(), estatus: $("#rstatus").val()});
            }, function (pageNo) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val(), estatus: $("#rstatus").val()});
            });
        });
    };
    self_.delRefund = function (refund) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                refund.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/apply_refund.php", JSON.stringify(refund), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        ApplyRefundListViewModel.applyRefundList.remove(refund);
                        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.doneRefund = function (refund) {
        ycoa.ajaxLoadPost("/api/sale_soft/apply_refund.php", JSON.stringify({action: 5, rid: refund.id}), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editRefund = function (refund) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + refund.id).show();
        $("#submit_" + refund.id).show();
        $("#cancel_" + refund.id).show();
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
    };
    self_.do_int = function (refund) {
        var html = "<div class='cu_div' id='doback_" + refund.id + "'>";
        html += "<select name='done_user_name' id='done_user_name' style='width:150px;height:41px; ;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setCustomerOk' rid='" + (refund.id) + "' ><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCancel'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $(".cu_div", $("#customer_td_" + refund.id)).remove();
        $("#customer_td_" + refund.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + refund.id).show();
        $("#doback_" + refund.id).animate({width: '232px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
}();
$(function () {
    ko.applyBindings(ApplyRefundListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchDateTime").val('');
        $("#rstatus").val('');
        $("#searchAutoStatus").val('');
        $("#rstatus").val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: name, searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val(), estatus: $("#rstatus").val()});
    });
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: 1, text: '退款'}, {id: 2, text: '售后'}, {id: 3, text: '其他'}], function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: d.id, estatus: $("#rstatus").val()});
    }, '申请方式', 'rstatus');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '介入', text: '介入'}, {id: '未介入', text: '未介入'}], function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: d.id, rstatus: $("#rstatus").val(), estatus: $("#rstatus").val()});
    }, '介入状态');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '处理', text: '处理'}, {id: '未处理', text: '未处理'}], function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(), searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val(), estatus: d.id});
    }, '处理状态', 'estatus');
    $("#dataTable").searchDateTime(function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchDateTime: d, searchAutoStatus: $("#searchAutoStatus").val(), rstatus: $("#rstatus").val(), estatus: $("#rstatus").val()});
    }, '介入时间');
    $(".date-time-picker-bind-mouseover").live("mouseover", function () {
        $(this).datetimepicker({autoclose: true});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").each(function () {
                var name = $(this).parent("td").parent("tr").find("td:eq(12)");
                if (name.html() === "") {
                    $(this).attr("checked", "checked");
                }
            });
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#dataTable tbody input[type='checkbox']").live('change', function () {
        var name = $(this).parent("td").parent("tr").find("td:eq(12)");
        if (name.html() !== "") {
            $(this).removeAttr('checked');
        }
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale_soft/apply_refund.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $("#done_username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (d, el) {
            el.val(d.name);
            el.parent("td").find("#done_userid").val(d.id);
        });
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/apply_refund.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#setCustomerOk").live("click", function () {
        var rid = $(this).attr('rid');
        var doneid = $("#done_user_name").val();
        ycoa.ajaxLoadPost('/api/sale_soft/apply_refund.php', {action: 4, rid: rid, done: doneid}, function (result) {
            if (result.code !== 0) {
                ycoa.UI.toast.warning("退款介入失败,请稍后重试~");
            } else {
                ycoa.UI.toast.success("退款介入成功~");
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            }
        });
    });
    $("#setCancel").live("click", function () {
        $(this).parent(".cu_div").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
    });
    $("#do_int_btn").click(function () {

        var html = "<div class='cu_div_' id='doback_all' style='height: 34px;width:0px;background: #cecece;border: none;right: 25px;position:absolute;left:15px;top: 46px;;display: none;'>";
        html += "<select name='doin_name' id='doin_name' style='width:180px;height:34px;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setDoInOk' style='height:34px;width:34px;cursor: pointer;'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "</div>";
        $(".cu_div", $(".permit_buttons")).remove();
        $(".permit_buttons").append(html);

        $("#doback_all").animate({width: '219px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    });
    $("#setDoInOk").live("click", function () {
        var r_ids = new Array();
        $("#dataTable tbody input[type='checkbox']").each(function () {
            if ($(this).attr("checked")) {
                r_ids.push($(this).attr('value'));
            }
        });
        if (r_ids.length !== 0) {
            var doneid = $("#doin_name").val();
            ycoa.ajaxLoadPost('/api/sale_soft/apply_refund.php', {action: 4, rid: r_ids.toString(), done: doneid}, function (result) {
                if (result.code !== 0) {
                    ycoa.UI.toast.warning("退款介入失败,请稍后重试~");
                } else {
                    ycoa.UI.toast.success("退款介入成功~");
                    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
                }
            });
        } else {
            ycoa.UI.toast.warning("退款介入失败,请先选择数据~");
        }
    });
    $("#open_apply_refund_btn").click(function () {
        $("#add_apply_refund_form .col-md-4").removeClass("has-error").removeClass("has-success");
        $("#add_apply_refund_form input,#add_apply_refund_form textarea").val("");
    });
    $("#btn_apply_refund_primary").click(function () {
        $("#add_apply_refund_form").submit();
    });
    $("#add_apply_refund_form #done_username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (d, el) {
            el.val(d.name);
            el.parent("div").find("#done_userid").val(d.id);
        });
    });
    $("#add_apply_refund_form #quit_write").keypress(function (e) {
        if (e.keyCode === 13 && $(this).val()) {
            ycoa.ajaxLoadGet("/api/sale_soft/salecount.php", {key_word: $(this).val(), action: 3}, function (results) {
                if (results.length >= 1) {
                    $.each(results[0], function (i, d) {
                        switch (i) {
                            case 'id':
                                $("#add_apply_refund_form #sale_id").val(d);
                                break;
                            case 'addtime':
                                $("#add_apply_refund_form #sale_addtime").val(d);
                                break;
                            default:
                                $("#add_apply_refund_form #" + i).val(d);
                                break;
                        }
                    });
                } else {
                    ycoa.UI.toast.warning("暂无匹配信息,行核对后重试~");
                }
            });
            return false;
        }
    });
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest(".doback_open").length == 0) {
            $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
                $(this).hide();
                $(this).removeClass("doback_open");
            });
        }
    });
});
function reLoadData(data) {
    data.action = 1;
    ApplyRefundListViewModel.listRefund(data);
}