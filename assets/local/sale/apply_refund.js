var customer_list = {};
var ApplyRefundListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.applyRefundList = ko.observableArray([]);
    self_.listRefund = function (data) {
        ycoa.ajaxLoadGet("/api/sale/apply_refund.php", data, function (results) {
            customer_list = results.user_list;
            self_.applyRefundList.removeAll();
            $.each(results.list, function (index, refund) {
                refund.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2011402");
                refund.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2011403");
                refund.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2011404");
                refund.do_int = ycoa.SESSION.PERMIT.hasPagePermitButton("2011407") && (refund.done_userid === 0);
                refund.done = ycoa.SESSION.PERMIT.hasPagePermitButton("2011408") && (refund.done_userid !== 0) && ((refund.end_time === "") || (refund.end_time === null));
                self_.applyRefundList.push(refund);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({
                    action: 1,
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize,
                    searchName: $("#searchUserName").val(), searchDateTime: $("#searchDateTime").val(),
                    searchAutoStatus: $("#searchAutoStatus").val(),
                    rstatus: $("#searchRstatus").val(), estatus: $("#searchEstatus").val()
                });
            }, function (pageNo) {
                reLoadData({
                    action: 1,
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    searchName: $("#searchUserName").val(),
                    searchDateTime: $("#searchDateTime").val(),
                    searchAutoStatus: $("#searchAutoStatus").val(),
                    rstatus: $("#searchRstatus").val(), estatus: $("#searchEstatus").val()
                });
            });
        });
    };
    self_.delRefund = function (refund) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                refund.action = 3;
                ycoa.ajaxLoadPost("/api/sale/apply_refund.php", JSON.stringify(refund), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
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
    self_.doneRefund = function (refund) {
        $("#add_remark_form #rid").val(refund.id);
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
        reLoadData({
            action: 1,
            sort: data.sort, sortname: data.sortname,
            pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),
            searchName: $("#searchUserName").val(),
            searchDateTime: $("#searchDateTime").val(),
            searchAutoStatus: $("#searchAutoStatus").val(),
            rstatus: $("#searchRstatus").val(),
            estatus: $("#searchEstatus").val()
        });
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchDateTime").val('');
        $("#searchRstatus").val('');
        $("#searchAutoStatus").val('');
        $("#searchEstatus").val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(),
            sortname: ycoa.SESSION.SORT.getSortName(), searchName: name,
            searchDateTime: $("#searchDateTime").val(),
            searchAutoStatus: $("#searchAutoStatus").val(),
            rstatus: $("#searchRstatus").val(),
            estatus: $("#searchEstatus").val()
        });
    });
    $("#dataTable").searchAutoStatus(array['rstatus'], function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(),
            searchDateTime: $("#searchDateTime").val(),
            searchAutoStatus: $("#searchAutoStatus").val(),
            rstatus: d.id, estatus: $("#searchEstatus").val()
        });
    }, '投诉来源', 'searchRstatus');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '介入', text: '介入'}, {id: '未介入', text: '未介入'}], function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(),
            searchDateTime: $("#searchDateTime").val(),
            searchAutoStatus: d.id,
            rstatus: $("#searchRstatus").val(),
            estatus: $("#searchEstatus").val()
        });
    }, '介入状态');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '处理', text: '处理'}, {id: '未处理', text: '未处理'}], function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(),
            searchDateTime: $("#searchDateTime").val(),
            searchAutoStatus: $("#searchAutoStatus").val(),
            rstatus: $("#searchRstatus").val(),
            estatus: d.id
        });
    }, '处理状态', 'searchEstatus');
    $("#dataTable").searchDateTime(function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(),
            searchDateTime: d,
            searchAutoStatus: $("#searchAutoStatus").val(),
            rstatus: $("#searchRstatus").val(),
            estatus: $("#searchEstatus").val()
        });
    }, '时间');
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
        var type = $("#toexcel_form #type").val();
        if (start_time || end_time) {
            location.href = '/api/sale/apply_refund.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11&type=' + type;
        }
    });
    $("#done_username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6]}, function (d, el) {
            el.val(d.name);
            el.parent("td").find("#done_userid").val(d.id);
        });
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/apply_refund.php", data, function (result) {
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
        ycoa.ajaxLoadPost('/api/sale/apply_refund.php', {action: 4, rid: rid, done: doneid}, function (result) {
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
            ycoa.ajaxLoadPost('/api/sale/apply_refund.php', {action: 4, rid: r_ids.toString(), done: doneid}, function (result) {
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
        $("#add_apply_refund_form input,#add_apply_refund_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
    });
    $("#btn_apply_refund_primary").click(function () {
        $("#add_apply_refund_form").submit();
    });
    $("#add_apply_refund_form #done_username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6]}, function (d, el) {
            el.val(d.name);
            el.parent("div").find("#done_userid").val(d.id);
        });
    });
    $("#add_apply_refund_form #quit_write").keypress(function (e) {
        if (e.keyCode === 13 && $(this).val()) {
            ycoa.ajaxLoadGet("/api/sale/salecount.php", {key_word: $(this).val(), action: 3}, function (results) {
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
    $("#btn_remark_primary").click(function () {
        var data = $("#add_remark_form").serializeJson();
        if (data.rid === "") {
            ycoa.UI.toast.warning("获取信息失败,请稍后重试~");
        } else if (data.remark === "") {
            ycoa.UI.toast.warning("备注为必填项~");
        } else {
            data.action = 5;
            ycoa.ajaxLoadPost("/api/sale/apply_refund.php", JSON.stringify(data), function (result) {
                if (result.code === 0) {
                    ycoa.UI.toast.success(result.msg);
                    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
                    $("#btn_remark_close_primary").click();
                } else {
                    ycoa.UI.toast.error(result.msg);
                }
                ycoa.UI.block.hide();
            });
        }
    });
    $("#btn_remark_close_primary").click(function () {
        $("#add_remark_form #remark").val("");
        $("#add_remark_form #rid").val("");
    });
    $("#add_apply_refund_form #reason").autoRadio(array['reason'], function (d) {

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
function initEditSeleter(el) {
    $("#reason", el).autoRadio(array["reason"]);
    el.attr('autoEditSelecter', 'autoEditSelecter');
}
var array = {
    reason: [{id: '个人', text: '个人'}, {id: '货源', text: '货源'}, {id: '二销', text: '二销'}, {id: '软件', text: '软件'}, {id: '服务', text: '服务'}, {id: '收货', text: '收货'}, {id: '其他', text: '其他'}],
    rstatus: [{id: '', text: '全部'}, {id: '退款', text: '退款'}, {id: '售后', text: '售后'}, {id: '400', text: '400'}, {id: '站内信', text: '站内信'}, {id: '负面', text: '负面'}, {id: '店铺', text: '店铺'}, {id: '官网', text: '官网'}, {id: '邮箱', text: '邮箱'}, {id: '旺旺', text: '旺旺'}, {id: '其他', text: '其他'}]};