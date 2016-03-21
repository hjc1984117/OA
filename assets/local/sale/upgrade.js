var customer_list;
var current_Date;
var isCreate = false;
var UpgradeListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.upgradeList = ko.observableArray([]);
    self_.listUpgrade = function (data) {
        ycoa.ajaxLoadGet("/api/sale/upgrade.php", data, function (results) {
            self_.upgradeList.removeAll();
            customer_list = results.customer_list;
            current_Date = results.current_date;
            $.each(results.list, function (index, upgrade) {
                upgrade.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2010502");
                upgrade.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2010503") && results.is_manager ? true : ((ycoa.user.userid() === upgrade.add_name_id) && (results.current_date === upgrade.add_time));
                upgrade.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2010505");
                upgrade.setcustomer = ycoa.SESSION.PERMIT.hasPagePermitButton("2010506");
                self_.upgradeList.push(upgrade);
            });
            if (!isCreate) {
                $("#add_upgrade_form #nick_name").autoEditSelecter(customer_list, function (data) {
                    data.el.parent("div").parent("div").find("#customer_id").val(data.id);
                });
                isCreate = true;
            }
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()});
            });
        });
    };
    self_.delUpgrade = function (upgrade) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                upgrade.action = 3;
                ycoa.ajaxLoadPost("/api/sale/upgrade.php", JSON.stringify(upgrade), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        UpgradeListViewModel.upgradeList.remove(upgrade);
                        reLoadData();
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editUpgrade = function (upgrade) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + upgrade.id).show();
        $("#submit_" + upgrade.id).show();
        $("#cancel_" + upgrade.id).show();
        if (!$("#form_" + upgrade.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + upgrade.id));
        }
//        $("#customer_id").val(upgrade.customer_id2);
//        $("#customer_id2").val(upgrade.customer_id);
        $("#customer_id").val(upgrade.customer_id);
        $("#tr_" + upgrade.id + " input,#tr_" + upgrade.id + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (upgrade) {
        $("#tr_" + upgrade.id).hide();
        $("#submit_" + upgrade.id).hide();
        $("#cancel_" + upgrade.id).hide();
    };
    self_.showUpgrade = function (upgrade) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + upgrade.id).show();
        $("#submit_" + upgrade.id).show();
        $("#cancel_" + upgrade.id).show();
        if (!$("#form_" + upgrade.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + upgrade.id));
        }
        $("#tr_" + upgrade.id + " input,#tr_" + upgrade.id + " textarea").attr("disabled", "");
    };
    self_.setCustomer = function (upgrade) {
        var html = "<div class='cu_div' id='doback_" + upgrade.id + "'>";
        html += "<select name='nick_name' id='nick_name' style='width:300px;height:41px; ;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setCustomerOk' val='" + upgrade.id + "' status='" + upgrade.status + "' customer_id='" + upgrade.customer_id + "'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCustomerCancel' val='" + upgrade.id + "' status='" + upgrade.status + "'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $("#customer_td_" + upgrade.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + upgrade.id).show();
        $("#doback_" + upgrade.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.setNickName = function (upgrade) {
        var html = "<div class='cu_div' id='doback_nick_name_" + upgrade.id + "'>";
        html += "<select name='nick_name' id='nick_name' style='width:300px;height:41px; ;float:left' class='form-control'>";
        $.each(customer_list, function (index, d) {
            html += "<option value='" + d.id + "'>" + (d.text) + "</option>";
        });
        html += "</select>";
        html += "<span class='input-group-addon' id='setNickNameOk' val='" + upgrade.id + "' status='" + upgrade.status + "'><i class='glyphicon glyphicon-ok' title='提交'></i></span>";
        html += "<span class='input-group-addon' id='setCancel' val='" + upgrade.id + "' status='" + upgrade.status + "'><i class='glyphicon glyphicon-remove' title='取消'></i></span>";
        html += "</div>";
        $("#customer_td_" + upgrade.id).append(html);
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_nick_name_" + upgrade.id).show();
        $("#doback_nick_name_" + upgrade.id).animate({width: '383px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
}();
$(function () {
    ko.applyBindings(UpgradeListViewModel, $("#dataTable")[0]);
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
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $(".date-time-picker-bind-mouseover").datetimepicker({autoclose: true}, function (d) {
        if (d && current_Date) {
            d = d.split(" ")[0];
            if (d > current_Date) {
                ycoa.UI.toast.warning("添加时间不能大于当前时间~");
            }
        }
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_addupgrade_primary").click(function () {
        var msg = "<img src='../../assets/global/img/heihei.jpg' style='height: 100px;width: auto;margin-right:20px;float:left;'>";
        msg += "<div style='float:left;line-height:100px;font-size:16px;'>你确定添加的没有问题了吗？亲，错一次就要罚款30元噢 ~</div>";
        msg += "<div style='clear:both;'></div>";
        ycoa.UI.messageBox.confirm(msg, function (btn) {
            if (btn) {
                $("#add_upgrade_form").submit();
            }
        });
    });

    $("#upgrade").click(function () {
        $("#add_upgrade_form input[type='text']").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_upgrade_form #attachment").html("");
        if (ycoa.user.dept1_id() !== 4) {
            $("#add_upgrade_form #add_name").val(ycoa.user.username());
            $("#add_upgrade_form #add_time").val(new Date().format("yyyy-MM-dd hh:mm:ss"));
        }
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
            location.href = '/api/sale/upgrade.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data.attachment = $("#" + formid + " #attachment_edit").html();
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/upgrade.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_upgrade_form #att").click(function () {
        if ($("#add_upgrade_form #att").prop('checked')) {
            $("#add_upgrade_form #editor").stop().animate({height: 'toggle'});
        } else {
            $("#add_upgrade_form #editor").stop().animate({height: 'toggle'});
        }
    });
    $("#add_upgrade_form #payment").autoEditSelecter(array['payment']);
//    $("#add_upgrade_form #channel").autoEditSelecter(array['channel']);
    $("#add_upgrade_form #attachment").pasteImgEvent();


//    $("#setCustomerOk").live("click", function () {
//        var id = $(this).attr("val");
//        var status = $(this).attr("status");
//        var customer_id = $(this).attr("customer_id");
//        var v = $("#doback_" + id).find("select").val();
//        var t = $("#doback_" + id).find("select").find('option:selected').text();
//        t = t.replace(")", "").split("(");
//        var nick_name = t[1];
//        var customer = t[0];
//        updateCL({id: id, status: status, customer_id: customer_id, customer_id2: v, nick_name2: nick_name, customer2: customer});
//        $("#doback_" + id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
//            $(this).hide();
//            $(this).removeClass("doback_open");
//            $(this).find("textarea").val("");
//        });
//    });
//    $("#setNickNameOk").live("click", function () {
//        var id = $(this).attr("val");
//        var status = $(this).attr("status");
//        var v = $("#doback_nick_name_" + id).find("select").val();
//        var t = $("#doback_nick_name_" + id).find("select").find('option:selected').text();
//        t = t.replace(")", "").split("(");
//        var nick_name = t[1];
//        var customer = t[0];
//        updateCL({id: id, status: status, customer_id: v, customer: customer, nick_name: nick_name, mode: 'set'});
//        $("#doback_nick_name_" + id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
//            $(this).hide();
//            $(this).removeClass("doback_open");
//        });
//    });
//    $("#setCustomerCancel").live("click", function () {
//        var id = $(this).attr("val");
//        $("#doback_" + id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
//            $(this).hide();
//            $(this).removeClass("doback_open");
//            $(this).find("textarea").val("");
//        });
//    });
    $("#setCancel").live("click", function () {
        $(this).parent(".cu_div").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
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
    $("#add_upgrade_form #presales").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6, 7]}, function (node, el) {
            el.parent().find("#presales_id").val(node.id);
            el.val(node.name);
        });
    });
    $("#key_word").keypress(function (e) {
        if (e.keyCode == 13) {
            var thisVal = $(this).val();
            if (thisVal && thisVal.length >= 2) {
                $.get(ycoa.getNoCacheUrl("/api/sale/salecount.php"), {action: 3, key_word: thisVal}, function (res) {
                    if (res.length == 1) {
                        var data = res[0];
                        $("#add_upgrade_form input").each(function () {
                            var el = $(this);
                            var data_ = data[el.attr("id")];
                            if (data_) {
                                el.val(data_);
                            }
                        });
                        $("#add_upgrade_form #sale_id").val(data.id);
                    } else if (res.length > 1) {
                        var html = "";
                        $.each(res, function (index, d) {
                            html += "<div class='auto_tr' v='" + (JSON.stringify(d)) + "'>";
                            html += "<div class='auto_td' title='" + (d.addtime) + "'>" + (d.addtime) + "</div>";
                            html += "<div class='auto_td' title='" + (d.name) + "'>" + (d.name) + "</div>";
                            html += "<div class='auto_td' title='" + (d.ww) + "'>" + (d.ww) + "</div>";
                            html += "<div class='auto_td' title='" + (d.qq) + "'>" + (d.qq) + "</div>";
                            html += "<div class='auto_td' title='" + (d.mobile) + "'>" + (d.mobile) + "</div>";
                            html += "<div class='auto_td' title='" + (d.presales) + "'>" + (d.presales) + "</div>";
                            html += "<div class='auto_td' title='" + (d.nick_name ? d.nick_name : "") + "'>" + (d.nick_name ? d.nick_name : "") + "</div>";
                            html += "</div>";
                        });
                        $(".auto_tbody").html(html);
                        var y = $(window).height();
                        var x = $(window).width();
                        $(".div_avatar_outer").css({top: ((y - 500) / 2) + 'px', left: ((x - 750) / 2) + 'px'}).show();
                    } else if (res.length == 0) {
                        ycoa.UI.toast.warning("未匹配到相应的数据,请核对后重试~");
                        $("#add_upgrade_form input").val("");
                        $("#add_upgrade_form #remark_fillarrears").html("");
                    }
                });
            } else {
                ycoa.UI.toast.warning("请输入真确的内容后再回车~");
            }
        }
    });
    $(".auto_tr").live("click", function () {
        var json_str = $(this).attr("v");
        json_str = $.parseJSON(json_str);
        $("#add_upgrade_form input").each(function () {
            var el = $(this);
            var data_ = json_str[el.attr("id")];
            if (data_) {
                el.val(data_);
            }
        });
        $("#add_upgrade_form #sale_id").val(json_str.id);
        $(".div_avatar_outer").hide();
    });
    $(".div_avatar_close_btn").click(function () {
        $(".div_avatar_outer").hide();
    });
    if (jQuery.ui) {
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
    }
});
function reLoadData(data) {
    data.action = 1;
    UpgradeListViewModel.listUpgrade(data);
}

function updateCL(upgrade) {
    upgrade.action = 2;
    ycoa.ajaxLoadPost("/api/sale/upgrade.php", JSON.stringify(upgrade), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}
;

function initEditSeleter(el) {
    $("#payment", el).autoEditSelecter(array['payment']);
    $("#channel", el).autoEditSelecter(array['channel']);
    $("#nick_name", el).autoEditSelecter(customer_list, function (data) {
        data.el.parent("div").parent("td").find("#customer_id").val(data.id);
    });
    $(".date-time-picker-bind-mouseover", el).datepicker({autoclose: true});
    $("#attachment_edit", el).pasteImgEvent();
    el.attr('autoEditSelecter', 'autoEditSelecter');
}

var array = {
    payment: [{id: 'yitian40000', text: 'yitian40000'}, {id: '斜阳掌柜', text: '斜阳掌柜'}, {id: '工行', text: '工行'}, {id: '农行', text: '农行'}, {id: '建行', text: '建行'}, {id: '中行', text: '中行'}, {id: '邮政', text: '邮政'}, {id: '信用社', text: '信用社'}, {id: '袁荣彬支付宝', text: '袁荣彬支付宝'}, {id: '余代龙支付宝', text: '余代龙支付宝'}, {id: '刘广支付宝', text: '刘广支付宝'}, {id: '戚华支付宝', text: '戚华支付宝'}, {id: '财付通', text: '财付通'}, {id: '微信', text: '微信'}, {id: '旗舰店', text: '旗舰店'}, {id: '专营店', text: '专营店'}],
    channel: [{id: '百度', text: '百度'}, {id: '360', text: '360'}, {id: '搜狗', text: '搜狗'}, {id: '百度直通车', text: '百度直通车'}, {id: '360直通车', text: '360直通车'}, {id: '搜狗直通车', text: '搜狗直通车'}],
};