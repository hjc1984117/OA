var rception_array, customer_array, headmaster_array;
var GenerationOperationListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.generationOperationList = ko.observableArray([]);
    self_.listGenerationOperation = function (data) {
        ycoa.ajaxLoadGet("/api/second_sale/generationOperation.php", data, function (results) {
            self_.generationOperationList.removeAll();
            $.each(results.list, function (index, generationOperation) {
                generationOperation.isArrears_text = generationOperation.isArrears === 1 ? "√" : "";
                generationOperation.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3050402');
                generationOperation.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3050403');
                generationOperation.show = ycoa.SESSION.PERMIT.hasPagePermitButton('3050404');
                self_.generationOperationList.push(generationOperation);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {
                    action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    workName: $("#workName").val(), ptqqName: $("#ptqqName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            }, function (pageNo) {
                var data = {
                    action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    workName: $("#workName").val(), ptqqName: $("#ptqqName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            });
        });
    };
    self_.delGenerationOperation = function (generationOperation) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                generationOperation.action = 2;
                ycoa.ajaxLoadPost("/api/second_sale/generationOperation.php", JSON.stringify(generationOperation), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.editGenerationOperation = function (generationOperation) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + generationOperation.id).show();
        $("#submit_" + generationOperation.id).show();
        $("#cancel_" + generationOperation.id).show();
        if (!$("#form_" + generationOperation.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + generationOperation.id));
        }
        $("#tr_" + generationOperation.id + " input,#tr_" + generationOperation.id + " textarea").removeAttr("disabled");
    };
    self_.showGenerationOperation = function (generationOperation) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + generationOperation.id).show();
        $("#cancel_" + generationOperation.id).show();
        if (!$("#form_" + generationOperation.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + generationOperation.id));
        }
        $("#tr_" + generationOperation.id + " input,#tr_" + generationOperation.id + " textarea").attr("disabled", "");
    };
    self_.cancelTr = function (generationOperation) {
        $("#tr_" + generationOperation.id).hide();
        $("#submit_" + generationOperation.id).hide();
        $("#cancel_" + generationOperation.id).hide();
    };
    self_.doEditSubmit = function (generationOperation) {
        var formid = "form_" + generationOperation.id;
        var data = $("#" + formid).serializeJson();
        data.remark = $("#" + formid + " #remark_edit").html();
        data.action = 3;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale/generationOperation.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
}();
$(function () {
    ko.applyBindings(GenerationOperationListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        var data = {
            action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), ptqqName: $("#ptqqName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").reLoad(function () {
        $("#workName").val("");
        $("#ptqqName").val("");
        $("#searchDateTime").val("");
        $('#searchStartTime').val("");
        $('#searchEndTime').val("");
        reLoadData({action: 1});
    });
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: name, ptqqName: $("#ptqqName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    }, '平台/售后/班主任', 'workName');
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), ptqqName: name, searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    }, '平台/QQ号', 'ptqqName');
    $("#dataTable").searchDateTimeSlot(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), ptqqName: $("#ptqqName").val(), searchTime: $('#searchDateTime').val(), searchStartTime: d.start, searchEndTime: d.end
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").searchDateTime(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), ptqqName: $("#ptqqName").val(), searchTime: d, searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_generationOperation_form input,#add_generationOperation_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#open_dialog_fill_btn").click(function () {
        $("#add_fillarrears_form input").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_fillarrears_form #remark_fillarrears").html("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#add_fillarrears_form #qq").keypress(function (e) {
        if (e.keyCode == 13) {
            if ($(this).val()) {
                $.get(ycoa.getNoCacheUrl("/api/second_sale/generationOperation.php"), {action: 5, qq: $(this).val()}, function (res) {
                    if (res.length == 1) {
                        var data = res[0];
                        $("#add_fillarrears_form input").each(function () {
                            var el = $(this);
                            el.val(data[el.attr("id")]);
                        });
                        $("#add_fillarrears_form #parent_id").val(data.id);
                    } else if (res.length > 1) {
                        var html = "";
                        $.each(res, function (index, d) {
                            html += "<div class='auto_tr' v='" + (JSON.stringify(d)) + "'>";
                            html += "<div class='auto_td' title='" + (d.add_time) + "'>" + (d.add_time) + "</div>";
                            html += "<div class='auto_td' title='" + (d.platform_num) + "'>" + (d.platform_num) + "</div>";
                            html += "<div class='auto_td' title='" + (d.qq) + "'>" + (d.qq) + "</div>";
                            html += "<div class='auto_td' title='" + (d.payment_amount) + "'>" + (d.payment_amount) + "</div>";
                            html += "<div class='auto_td' title='" + (d.customer) + "'>" + (d.customer) + "</div>";
                            html += "<div class='auto_td' title='" + (d.platform_sales) + "'>" + (d.platform_sales) + "</div>";
                            html += "<div class='auto_td' title='" + (d.headmaster) + "'>" + (d.headmaster) + "</div>";
                            html += "</div>";
                        });
                        $(".auto_tbody").html(html);
                        var y = $(window).height();
                        var x = $(window).width();
                        $(".div_avatar_outer").css({top: ((y - 500) / 2) + 'px', left: ((x - 750) / 2) + 'px'}).show();
                    } else if (res.length == 0) {
                        ycoa.UI.toast.warning("未匹配到相应的数据,请核对后重试~");
                        $("#add_fillarrears_form input").val("");
                        $("#add_fillarrears_form #remark_fillarrears").html("");
                    }
                });
            }
        }
    });
    $(".auto_tr").live("click", function () {
        var json_str = $(this).attr("v");
        json_str = $.parseJSON(json_str);
        $("#add_fillarrears_form input").each(function () {
            var el = $(this);
            el.val(json_str[el.attr("id")]);
        });
        $("#add_fillarrears_form #parent_id").val(json_str.id);
        $(".div_avatar_outer").hide();
    });
    $(".div_avatar_close_btn").click(function () {
        $(".div_avatar_outer").hide();
    });
    $("#btn_submit_primary").click(function () {
        $("#add_generationOperation_form").submit();
    });
    $("#btn_submit_fill_primary").click(function () {
        $("#add_fillarrears_form").submit();
    });
    $("#btn_toexcel_primary").click(function () {
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time || end_time) {
            window.location.href = "/api/second_sale/generationOperation.php?action=10&start_time=" + start_time + "&end_time=" + end_time;
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#add_generationOperation_form #isArrears").autoRadio(array['isArrears']);
    $("#add_generationOperation_form #payment_method").autoEditSelecter(array['payment_method']);
    $("#add_generationOperation_form #remark").pasteImgEvent();
    $("#add_fillarrears_form #remark_fillarrears").pasteImgEvent();

    $.get(ycoa.getNoCacheUrl("/api/second_sale/customer.php"), {action: 2, type: 1}, function (res) {
        $("#add_generationOperation_form #platform_sales").autoEditSelecter(res, function (d) {
            $("#add_generationOperation_form #platform_sales_id").val(d.id);
        });
        rception_array = res;
    });
    $.get(ycoa.getNoCacheUrl("/api/second_sale/customer.php"), {action: 2, type: 5}, function (res) {
        $("#add_generationOperation_form #headmaster").autoEditSelecter(res, function (d) {
            $("#add_generationOperation_form #headmaster_id").val(d.id);
        });
        headmaster_array = res;
    });

    $.get(ycoa.getNoCacheUrl("/api/second_sale/platform.php"), {action: 2}, function (res) {
        $("#add_generationOperation_form #customer").autoEditSelecter(res, function (d) {
            $("#add_generationOperation_form #customer_id").val(d.id);
        });
        customer_array = res;
    });

    if (jQuery.ui) {
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
    }
});
function reLoadData(data) {
    GenerationOperationListViewModel.listGenerationOperation(data);
}

function updateCL(generationOperation) {
    generationOperation.action = 2;
    ycoa.ajaxLoadPost("/api/second_sale/generationOperation.php", JSON.stringify(generationOperation), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}

var array = {
    isArrears: [{id: '1', text: '是'}, {id: '0', text: '否'}],
    payment_method: [{id: '银行卡转款', text: '银行卡转款'}, {id: '信用卡', text: '信用卡'}, {id: '花呗', text: '花呗'}, {id: '支付宝', text: '支付宝'}, {id: '微信', text: '微信'}, {id: '财付通', text: '财付通'}, {id: 'QQ钱包', text: 'QQ钱包'}]
};
function initEditSeleter(el) {
    $("#isArrears", el).autoRadio(array['isArrears']);
    $("#payment_method", el).autoEditSelecter(array['payment_method']);
    $("#platform_sales", el).autoEditSelecter(rception_array, function (d) {
        $("#platform_sales_id", el).val(d.id);
    });
    $("#headmaster", el).autoEditSelecter(headmaster_array, function (d) {
        $("#headmaster_id", el).val(d.id);
    });
    $("#customer", el).autoEditSelecter(customer_array, function (d) {
        $("#customer_id", el).val(d.id);
    });
    $("#remark_edit", el).pasteImgEvent();
    el.attr('autoEditSelecter', 'autoEditSelecter');
}