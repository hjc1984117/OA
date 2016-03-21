var customer_list, presales_list;
var SalecountListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.salecountList = ko.observableArray([]);
    self_.listSalecount = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/salecount.php", data, function (results) {
            self_.salecountList.removeAll();
            var currentDate = results.currentDate;
            $.each(results.list, function (index, salecount) {
                var addTime = salecount.addtime.split(" ")[0];
                salecount.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3030302");
                salecount.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3030303") && results.is_manager ? true : ((ycoa.user.userid() === salecount.presales_id) && (currentDate === addTime));
                salecount.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3030304");
                salecount.nick_name = salecount.customer_id === 0 ? "" : salecount.nick_name;
                salecount.reSetSalecount = ycoa.SESSION.PERMIT.hasPagePermitButton("3030306") && salecount.customer_id === 0 && ((salecount.status === 0 && salecount.conflictWith === 0) || (salecount.status !== 0 && salecount.conflictWith !== 0));
                salecount.isTimely_txt = salecount.isTimely === 1 ? '√' : '';
                salecount.isQQTeach_txt = salecount.isQQTeach === 1 ? '√' : '';
                salecount.color_ = salecount.status === 0 ? 'color:#F00' : '';
                salecount.setPresales = ycoa.SESSION.PERMIT.hasPagePermitButton("3030309");
                salecount.setNickName = ycoa.SESSION.PERMIT.hasPagePermitButton("3030310") && salecount.customer_id !== 0;
                salecount.cashback = ycoa.SESSION.PERMIT.hasPagePermitButton("3030311");
                salecount.refund = ycoa.SESSION.PERMIT.hasPagePermitButton("3030307");
                salecount.ather = function () {
                    var array_ = new Array();
                    if (salecount.isTimely === 1) {
                        array_.push('isTimely');
                    }
                    if (salecount.isQQTeach === 1) {
                        array_.push('isQQTeach');
                    }
                    return array_.toString();
                }();
                self_.salecountList.push(salecount);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchChannel: $("#searchChannel").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), searchSetmeal: $('#searchSetmeal').val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), status: $('#searchStatus').val()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchChannel: $("#searchChannel").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), searchSetmeal: $('#searchSetmeal').val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), status: $('#searchStatus').val()});
            });
        });
    };
    self_.delSalecount = function (salecount) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                salecount.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/salecount.php", JSON.stringify(salecount), function (result) {
                    if (result.code == 0) {
                        if (result.conflict_id !== 0) {
                            reSetSaleCount({sale_id: result.conflict_id, action: 4});
                        } else {
                            reLoadData({action: 1});
                        }
                        ycoa.UI.toast.success(result.msg);
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.setNickName = function (salecount) {
        var html = "<div class='cu_div' id='doback_nick_name_" + salecount.id + "'>";
        html += "<select name='nick_name' id='nick_name' style='width:300px;height:41px; ;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            if (salecount.isQQTeach !== 0) {
                if (d.qqReception === 1) {
                    html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
                }
            } else {
                html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
            }
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setNickNameOk' isQQTeach = '" + (salecount.isQQTeach) + "' isTimely='" + (salecount.isTimely) + "' val='" + salecount.id + "' status='" + salecount.status + "'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCancel' val='" + salecount.id + "' status='" + salecount.status + "'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $("#customer_td_" + salecount.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_nick_name_" + salecount.id).show();
        $("#doback_nick_name_" + salecount.id).animate({width: '383px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.setPresales = function (salecount) {
        var html = "<div class='cu_div' id='doback_presales_" + salecount.id + "'>";
        html += "<select name='presales' id='presales' style='width:300px;height:41px; ;float:left' class='form-control'>";
        $.each(presales_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setPresalesOk' isQQTeach = '" + (salecount.isQQTeach) + "' isTimely='" + (salecount.isTimely) + "' val='" + salecount.id + "' status='" + salecount.status + "'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCancel' val='" + salecount.id + "' status='" + salecount.status + "'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $("#customer_td_" + salecount.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_presales_" + salecount.id).show();
        $("#doback_presales_" + salecount.id).animate({width: '383px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.setSalecount = function (salecount) {
        ycoa.ajaxLoadPost("/api/sale_soft/salecount.php", {sale_id: salecount.id, action: 4}, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editSalecount = function (salecount) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + salecount.id).show();
        $("#submit_" + salecount.id).show();
        $("#cancel_" + salecount.id).show();
        if (!$("#form_" + salecount.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + salecount.id));
        }
        $("#tr_" + salecount.id + " input,#tr_" + salecount.id + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (salecount) {
        $("#tr_" + salecount.id).hide();
        $("#submit_" + salecount.id).hide();
        $("#cancel_" + salecount.id).hide();
    };
    self_.showSalecount = function (salecount) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + salecount.id).show();
        $("#cancel_" + salecount.id).show();
        if (!$("#form_" + salecount.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + salecount.id));
        }
        $("#tr_" + salecount.id + " input,#tr_" + salecount.id + " textarea").attr("disabled", "");
    };
    self_.cashback = function (salecount) {
        if((salecount.customer_id)==0){
         $("#add_cashback_form .auto_radio_li[v='售后']" ).hide();
        }
        else{
             $("#add_cashback_form .auto_radio_li[v='售后']" ).show();
        }
        $("#add_cashback_form #s_id").val(salecount.id);
    };
    self_.refund = function (salecount) {
        $('#add_refund_form #s_id').val(salecount.id);
        $('#add_refund_form #totalmoney').val(salecount.money);
        $('#add_refund_form #add_user').val(ycoa.user.username());
        
        if((salecount.customer_id)==0){
         $("#add_refund_form .auto_radio_li[v='售后']" ).hide();
        }
        else{
             $("#add_refund_form .auto_radio_li[v='售后']" ).show();
        }
    };
    
}();
$(function () {
    createTodayTotals();
    get_p_c_list();
    ko.applyBindings(SalecountListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchChannel: $("#searchChannel").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), searchSetmeal: $('#searchSetmeal').val(), searchName: $("#searchUserName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $('#searchSetmeal').val('');
        $('#searchStartTime').val('');
        $('#searchEndTime').val('');
        $('#searchStatus').val('');
        $("#searchChannel").val('');
        createTodayTotals();
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchChannel: $("#searchChannel").val(), searchName: name, searchSetmeal: $("#searchSetmeal").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), status: $('#searchStatus').val()});
    });
    $("#dataTable").searchAutoStatus(array['setmeal'], function (d) {
        reLoadData({action: 1, searchChannel: $("#searchChannel").val(), searchName: $("#searchUserName").val(), searchSetmeal: d.text, searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), status: $('#searchStatus').val()});
        $('#searchSetmeal').val(d.text);
    }, "按照版本搜索");
    $("#dataTable").searchAutoStatus(array['channel'], function (d) {
        reLoadData({action: 1, searchChannel: d.id, searchName: $("#searchUserName").val(), searchSetmeal: $('#searchStartTime').val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), status: $('#searchStatus').val()});
        $('#searchChannel').val(d.id);
    }, '接入渠道');
    $("#dataTable").searchAutoStatus([{id: 0, text: '全部'}, {id: 1, text: '正常'}, {id: 2, text: '串号'}, {id: 3, text: '未分配售后'}], function (d) {
        reLoadData({action: 1, searchChannel: $("#searchChannel").val(), searchName: $("#searchUserName").val(), searchSetmeal: $('#searchStartTime').val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val(), status: d.id});
        $('#searchStatus').val(d.id);
    }, '按状态搜索');
    $("#dataTable").searchDateTimeSlot(function (d) {
        reLoadData({action: 1, searchChannel: $("#searchChannel").val(), searchName: $("#searchUserName").val(), searchSetmeal: $("#searchSetmeal").val(), searchStartTime: d.start, searchEndTime: d.end, status: $('#searchStatus').val()});
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
    $("#btn_submit_primary").click(function () {
        var msg = "<img src='../../assets/global/img/heihei.jpg' style='height: 100px;width: auto;margin-right:20px;float:left;'>";
        msg += "<div style='float:left;line-height:100px;font-size:16px;'>你确定添加的没有问题了吗？亲，错一次就要罚款30元噢 ~</div>";
        msg += "<div style='clear:both;'></div>";
        ycoa.UI.messageBox.confirm(msg, function (btn) {
            if (btn) {
                $("#add_salecount_form").submit();
            }
        });
    });
    $("#btn_cashback_primary").click(function () {
        $("#add_cashback_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_salecount_form input,#add_salecount_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_salecount_form #attachment").html("");
        $("#add_salecount_form input[type='checkbox']").removeAttr("checked");
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
        var type = $("#toexcel_form #type").val();
        if (start_time || end_time) {
            if (parseInt(type) === 1) {
                location.href = '/api/sale_soft/salecount.php?data_status=' + $("#toexcel_form #data_status").val() + '&start_time=' + start_time + '&end_time=' + end_time + '&action=11&type=' + type;
            } else {
                location.href = '/api/sale_soft/salecount.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11&type=' + type;
            }
        }
    });
    $("#toexcel_form #type").change(function () {
        if (parseInt($(this).val()) === 1) {
            $("#toexcel_form .label_data_status").show();
        } else {
            $("#toexcel_form .label_data_status").hide();
        }
    });
    $(".dept_submit_btn").live("click", function (salecount) {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data.attachment = $("#" + formid + " #attachment_edit").html();
        data.isTimely = 0;
        data.isQQTeach = 0;
        var ather_ = (data.ather).split(",");
        if (ather_.indexOf("isTimely") !== -1) {
            data.isTimely = 1;
        }
        if (ather_.indexOf("isQQTeach") !== -1) {
            data.isQQTeach = 1;
        }
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        work_kv.push('"attachment":"附件"');
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/salecount.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#btn_refund_primary").click(function () {
        $("#add_refund_form").submit();
    });
    $("#add_refund_form #money").blur(function () {
        $("#retrieve").val($("#totalmoney").val() - $(this).val());
    });
    $("#add_refund_form #refund_type").autoRadio(array["refund_type"]);
    $("#add_refund_form #refund_rate").autoRadio(array["refund_rate"]);
    $("#add_refund_form #ind_refund_rate").autoRadio(array["ind_refund_rate"]);
    $("#add_refund_form #duty").autoRadio(array["duty"]);
    $("#add_refund_form #status").autoRadio(array["status"]);
    $("#add_refund_form #reason").autoRadio(array["reason"]);
    $("#add_refund_form #refund_shop").autoRadio(array["refund_shop"]);
    $("#add_refund_form #is_logoff_dbb").autoRadio(array["bool"]);

    $("#add_salecount_form #money").autoEditSelecter(array["money"]);
    $("#add_salecount_form #ww").autoEditSelecter(array["ww"]);
    $("#add_salecount_form #setmeal").autoEditSelecter(array["setmeal"]);
    $("#add_salecount_form #payment").autoEditSelecter(array["payment"]);
    $("#add_salecount_form #channel").autoEditSelecter(array["channel"]);
    $("#add_salecount_form #province").autoEditSelecter(array["province"]);
    $("#add_salecount_form #qq").autoEditSelecter(array["qq"]);
    $("#add_salecount_form #c_shop").autoEditSelecter(array["c_shop"]);
    $("#add_salecount_form #ather").autoRadio(array["ather"]);
    $("#add_salecount_form #reSetSalecountNow").autoRadio(array["bool"]);

    $("#add_cashback_form #duty").autoRadio(array["duty"]);
    $("#add_cashback_form #cashback_shop").autoRadio(array["cashback_shop"]);
    $("#add_cashback_form #cd_alipay").autoRadio(array["cd_alipay_c"]);
    $("#add_cashback_form #cashback_way").autoRadio(array["cashback_way"]);
    $("#add_cashback_form #cashback_reason").autoRadio(array["cashback_reason"]);

    $("#add_salecount_form #arrearsbox").click(function () {
        if ($("#add_salecount_form #arrearsbox").prop('checked')) {
            $("#add_salecount_form #arrears").show();
        } else {
            $("#add_salecount_form #arrears").hide();
        }
    });
    $("#add_salecount_form #att").click(function () {
        if ($("#add_salecount_form #att").prop('checked')) {
            $("#add_salecount_form #editor").stop().animate({height: 'toggle'});
        } else {
            $("#add_salecount_form #editor").stop().animate({height: 'toggle'});
        }
    });
    $("#btn_close_primary").click(function () {
        $("#add_salecount_form #editor").hide();
    });
    $("#add_salecount_form #attachment").pasteImgEvent();
    $("#quikWrite").blur(function () {
        var self = this;
        var context = $(self).val();
        if (context.trim()) {
            context = context.trim();
            context = context.split(",");
            if (context.length == 3) {
                var wwName = context[0].split(" ");
                if (wwName.length == 2) {
                    $("#add_salecount_form #ww").val(wwName[0].trim());
                    $("#add_salecount_form #name").val(wwName[1].trim());
                    $("#add_salecount_form #mobile").val(context[1].trim());
                    $("#add_salecount_form #province").val(context[2].trim());
                } else {
                    ycoa.UI.toast.warning("参数格式不正确，检查后重新再试~");
                }
            } else {
                ycoa.UI.toast.warning("参数格式不正确，检查后重新再试~");
            }
        }
    });
    $("#setPresalesOk").live("click", function () {
        var id = $(this).attr("val");
        var status = $(this).attr("status");
        var v = $("#doback_presales_" + id).find("select").val();
        var t = $("#doback_presales_" + id).find("select").find('option:selected').text();
        updateCL({id: id, status: status, presales: t, presales_id: v, mode: 'set', isQQTeach: $(this).attr('isQQTeach'), isTimely: $(this).attr('isTimely')});
        $("#doback_presales_" + id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
    });
    $("#setNickNameOk").live("click", function () {
        var id = $(this).attr("val");
        var status = $(this).attr("status");
        var v = $("#doback_nick_name_" + id).find("select").val();
        var t = $("#doback_nick_name_" + id).find("select").find('option:selected').text();
        t = t.replace(")", "").split("(");
        var nick_name = t[1];
        var customer = t[0];
        updateCL({id: id, status: status, customer_id: v, customer: customer, nick_name: nick_name, mode: 'set', isQQTeach: $(this).attr('isQQTeach'), isTimely: $(this).attr('isTimely')});
        $("#doback_nick_name_" + id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
    });
    $(".data_list_div").live("mouseenter", function () {
        var self_ = $(this);
        self_.data('lastEnter', new Date().getTime());
        setTimeout(function () {
            var t1 = new Date().getTime(), t2 = self_.data('lastEnter');
            if (t2 > 0 && t1 - t2 >= 500) {
                var w_id = self_.attr("val");
                $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
                    $(this).addClass("data_open");
                });
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
                $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 6, obj_id: w_id}, function (result) {
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
        }, 500);
    }).live('mouseleave', function () {
        $(this).data('lastEnter', 0);
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
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
    $("#show_ranking").click(function () {
        ycoa.ajaxLoadGet("/api/sale_soft/salecount.php", {action: 2, time_unit: 1}, function (result) {
            var html = "<ul>";
            $.each(result, function (index, d) {
                var clas = "num";
                if (index == 0) {
                    clas = "num_1";
                } else if (index == 1) {
                    clas = "num_2";
                } else if (index == 2) {
                    clas = "num_3";
                }
                html += "<li><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.presales) + "</span><span class='count'>" + (d.sale_count) + "</span><span class='money'>" + (Math.ceil(d.money)) + "元</span></li>";
            });
            html += "</ul>";
            $(".auto_tab_main .auto_tab_context div[var='1']").html(html);
            var width = $(window).width();
            var height = $(window).height();
            $(".auto_tab_main").animate({left: ((width - 450) / 2) + "px", top: ((height - 540) / 2) + "px"}, 500);
        });
    });
    $(".auto_tab_main .auto_tab_title li").click(function () {
        var self = $(this);
        var data = new Date().getTime();
        if (!self.hasClass("select")) {
            if ((data - parseInt($(".auto_tab_main").attr("t"))) > 500) {
                var v = self.attr("var");
                ycoa.ajaxLoadGet("/api/sale_soft/salecount.php", {action: 2, time_unit: v}, function (result) {
                    var html = "<ul>";
                    $.each(result, function (index, d) {
                        var clas = "num";
                        if (index == 0) {
                            clas = "num_1";
                        } else if (index == 1) {
                            clas = "num_2";
                        } else if (index == 2) {
                            clas = "num_3";
                        }
                        html += "<li><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.presales) + "</span><span class='count'>" + (d.sale_count) + "</span><span class='money'>" + (Math.ceil(d.money)) + "元</span></li>";
                    });
                    html += "</ul>";
                    $(".auto_tab_main .auto_tab_context div[var='" + v + "']").html(html);
                });
                $(".auto_tab_main").attr("t", data);
                $(".auto_tab_main li").removeClass("select");
                $(this).addClass("select");
                $(".auto_tab_context .open").animate({height: '0px'}, 300, function () {
                    $(this).removeClass("open");
                    $(this).hide();
                });
                $(".auto_tab_context div[var='" + v + "']").show();
                $(".auto_tab_context div[var='" + v + "']").animate({height: '458px'}, 300, function () {
                    $(this).addClass("open");
                });
            }
        }
    });
    $(".auto_tab_close .close_btn").click(function () {
        $(".auto_tab_main").animate({left: (($(window).width() + 500) * 1) + "px", top: "-600px"}, 500);
        $(".auto_tab_main").animate({left: '-500px', top: '-600px'});
    });
    if ([1, 369, 291].indexOf(ycoa.user.userid()) !== -1) {
        $("#add_refund_form #add_user").click(function () {
            ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11, 17]}, function (d, el) {
                el.val(d.name);
                el.parent().find("#userid").val(d.id);
            });
        });
    }
    $(".date-time-picker-bind-mouseover").datetimepicker({autoclose: true});
    if (jQuery.ui) {
        $('.auto_tab_main').draggable({handle: ".title"});
    }
});
function reLoadData(data) {
    SalecountListViewModel.listSalecount(data);
}

function reSetSaleCount(data) {
    $.post(ycoa.getNoCacheUrl("/api/sale_soft/salecount.php"), data, function () {
        reLoadData({action: 1});
    });
}

function updateCL(salecount) {
    salecount.action = 2;
    ycoa.ajaxLoadPost("/api/sale_soft/salecount.php", JSON.stringify(salecount), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}

function createTodayTotals() {
    $.get(ycoa.getNoCacheUrl("/api/sale_soft/salecount.php"), {action: 4}, function (results) {
        var TodayTotals = results;

        var TodayWWTotals = TodayTotals.TodayWWTotalsSoft;
        var TodayBDTotals = TodayTotals.TodayBDTotalsSoft;
        var Today360Totals = TodayTotals.Today360TotalsSoft;
        var TodaySGTotals = TodayTotals.TodaySGTotalsSoft;
        var TodayUCTotals = TodayTotals.TodayUCTotalsSoft;
        var TodayYHZTotals = TodayTotals.TodayYHZTotalsSoft;
        var TodayWMTotals = TodayTotals.TodayWMTotalsSoft;

        var TodayWWTimelyTotals = TodayTotals.TodayWWTimelyTotalsSoft;
        var TodayBDTimelyTotals = TodayTotals.TodayBDTimelyTotalsSoft;
        var Today360TimelyTotals = TodayTotals.Today360TimelyTotalsSoft;
        var TodaySGTimelyTotals = TodayTotals.TodaySGTimelyTotalsSoft;
        var TodayUCTimelyTotals = TodayTotals.TodayUCTimelyTotalsSoft;
        var TodayYHZTimelyTotals = TodayTotals.TodayYHZTimelyTotalsSoft;
        var TodayWMTimelyTotals = TodayTotals.TodayWMTimelyTotalsSoft;

        var TodayTotalsHtml = "<span style='color:black;'>百度:" + TodayBDTotals + "(及时:" + TodayBDTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:green;'>360:" + Today360Totals + "(及时:" + Today360TimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:#EAEA1E;'>搜狗:" + TodaySGTotals + "(及时:" + TodaySGTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:orange;'>UC:" + TodayUCTotals + "(及时:" + TodayUCTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:brown;'>旺旺:" + TodayWWTotals + "(及时:" + TodayWWTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:blue;'>优化站:" + TodayYHZTotals + "(及时:" + TodayYHZTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color:blue;'>网盟:" + TodayWMTotals + "(及时:" + TodayWMTimelyTotals + ")&nbsp;</span>";
        TodayTotalsHtml += "<span style='color: red;'>共:" + (TodayWWTotals + TodayBDTotals + Today360Totals + TodaySGTotals + TodayUCTotals + TodayYHZTotals + TodayWMTotals);
        TodayTotalsHtml += "(及时:" + (TodayWWTimelyTotals + TodayBDTimelyTotals + Today360TimelyTotals + TodaySGTimelyTotals + TodaySGTimelyTotals + TodayYHZTimelyTotals + TodayWMTimelyTotals) + ")</span>";
        if (!isNaN(TodayWWTotals) && !isNaN(TodayBDTotals) && !isNaN(Today360Totals) && !isNaN(TodaySGTotals) && !isNaN(TodayUCTotals) && !isNaN(TodayYHZTotals) && !isNaN(TodayWMTotals)) {
            $('.TodayTotals').html(TodayTotalsHtml);
        }
    });
}

function get_p_c_list() {
    $.get(ycoa.getNoCacheUrl("/api/sale_soft/salecount.php"), {action: 10}, function (results) {
        if (results.code === 0) {
            customer_list = results.customer_list;
            presales_list = results.presales_list;
        }
    });
}

function initEditSeleter(el) {
    $("#money", el).autoEditSelecter(array["money"]);
    $("#setmeal", el).autoEditSelecter(array["setmeal"]);
    $("#payment", el).autoEditSelecter(array["payment"]);
    $("#channel", el).autoEditSelecter(array["channel"]);
    $("#province", el).autoEditSelecter(array["province"]);
    $("#qq", el).autoEditSelecter(array["qq"]);
    $("#ww", el).autoEditSelecter(array["ww"]);
    $("#cashback_reason", el).autoEditSelecter(array["cashback_reason"]);
    $("#nick_name", el).autoEditSelecter(customer_list, function (data) {
        data.el.parent("div").parent("td").find("#customer_id").val(data.id);
    });
    $("#c_shop", el).autoEditSelecter(array["c_shop"]);
    $("#ather", el).autoRadio(array["ather"]);
    $("#attachment_edit", el).pasteImgEvent();
    el.attr('autoEditSelecter', 'autoEditSelecter');
}

var array = {
    ww: [{id: '无', text: '无', default: true}],
    qq: [{id: '无', text: '无', default: true}, {id: '旺旺', text: '旺旺'}],
    money: [{id: 600, text: 600}, {id: 800, text: 800, default: true}],
    setmeal: [{id: '基础版', text: '基础版'}, {id: '高级版', text: '高级版', default: true}, {id: '神秘版', text: '神秘版'}],
    payment: [{id: '淘宝', text: '淘宝'}, {id: '支付宝', text: '支付宝'}, {id: '财付通', text: '财付通'}, {id: '拍拍', text: '拍拍'}, {id: 'QQ钱包', text: 'QQ钱包'}, {id: '微信', text: '微信'}, {id: '微店', text: '微店'}, {id: '工行', text: '工行'}, {id: '农行', text: '农行'}, {id: '建行', text: '建行'}, {id: '中行', text: '中行'}, {id: '邮政', text: '邮政'}, {id: '信用社', text: '信用社'}, {id: '成都农商', text: '成都农商'}],
    channel: [{id: '旺旺', text: '旺旺'}, {id: '百度', text: '百度'}, {id: '360', text: '360'}, {id: 'UC神马', text: 'UC神马'}, {id: '搜狗', text: '搜狗'}, {id: '优化站', text: '优化站'}, {id: '百度知道', text: '百度知道'}, {id: '网盟', text: '网盟'}],
    province: [{id: '河北省', text: '河北省', default: true}, {id: '山西省', text: '山西省'}, {id: '辽宁省', text: '辽宁省'}, {id: '吉林省', text: '吉林省'}, {id: '黑龙江省', text: '黑龙江省'}, {id: '江苏省', text: '江苏省'}, {id: '浙江省', text: '浙江省'}, {id: '安徽省', text: '安徽省'}, {id: '福建省', text: '福建省'}, {id: '江西省', text: '江西省'}, {id: '山东省', text: '山东省'}, {id: '河南省', text: '河南省'}, {id: '湖北省', text: '湖北省'}, {id: '湖南省', text: '湖南省'}, {id: '广东省', text: '广东省'}, {id: '海南省', text: '海南省'}, {id: '四川省', text: '四川省'}, {id: '贵州省', text: '贵州省'}, {id: '云南省', text: '云南省'}, {id: '陕西省', text: '陕西省'}, {id: '甘肃省', text: '甘肃省'}, {id: '青海省', text: '青海省'}, {id: '台湾省', text: '台湾省'}, {id: '北京市', text: '北京市'}, {id: '天津市', text: '天津市'}, {id: '上海市', text: '上海市'}, {id: '重庆市', text: '重庆市'}, {id: '广西自治区', text: '广西自治区'}, {id: '内蒙古自治区', text: '内蒙古自治区'}, {id: '宁夏自治区', text: '宁夏自治区'}, {id: '新疆自治区', text: '新疆自治区'}],
    refund_type: [{id: '部分退款', text: '部分退款'}, {id: '全额退款', text: '全额退款'}, {id: '未退', text: '未退'}],
    refund_rate: [{id: '0.00', text: '0.00'}, {id: '0.50', text: '0.50'}, {id: '1.00', text: '1.00'}],
    ind_refund_rate: [{id: '0.00', text: '0.00'}, {id: '0.50', text: '0.50'}, {id: '1.00', text: '1.00'}],
    controlman: [{id: '退款专员', text: '退款专员'}, {id: '售后老师', text: '售后老师'}],
    duty: [{id: '售前', text: '售前'},{id: '售后', text: '售后'}, {id: '部门', text: '部门'}],
    cashback_shop: [{id: '伤心', text: '伤心'}, {id: '非凡', text: '非凡'}],
    cashback_way: [{id: '转账', text: '转账'}, {id: '店铺', text: '店铺'}],
    cd_alipay: [{id: 'i淘支付宝', text: 'i淘支付宝'}, {id: '非凡支付宝', text: '非凡支付宝'}, {id: '开店支付宝', text: '开店支付宝'}, {id: '伤心支付宝', text: '伤心支付宝'}],
    status: [{id: '转账', text: '转账'}, {id: '店铺', text: '店铺'}, {id: '未退', text: '未退'}],
    cd_alipay_c: [{id: '非凡支付宝', text: '非凡支付宝'}, {id: 'i淘支付宝', text: 'i淘支付宝'}, {id: '伤心支付宝', text: '伤心支付宝'}, {id: '未退', text: '未退'}],
    refund_shop: [{id: '非凡e品', text: '非凡e品'}, {id: '开店总经销', text: '开店总经销'}, {id: '伤心的rain', text: '伤心的rain'}],
   // cashback_reason: [{id: '售后', text: '售后'}, {id: '部门', text: '部门'}],
    bool: [{id: '是', text: '是'}, {id: '否', text: '否'}],
    c_shop: [{id: '闽州e收入', text: '闽州e收入'}, {id: '开店收入', text: '开店收入'}, {id: '非凡收入', text: '非凡收入'}, {id: '伤心的雨收入', text: '伤心的雨收入'}, {id: '转账收入', text: '转账收入'}, {id: 'i淘花呗软件部收入', text: 'i淘花呗软件部收入'}],
    ather: [{id: 'isTimely', text: '及时'}, {id: 'isQQTeach', text: 'QQ教学'}],
    reason: [{id: '个人', text: '个人'}, {id: '货源', text: '货源'}, {id: '二销', text: '二销'}, {id: '软件', text: '软件'}, {id: '服务', text: '服务'}, {id: '收货', text: '收货'}, {id: '无理由', text: '无理由'}, {id: '其他', text: '其他'}]
};