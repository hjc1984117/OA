var rception_array, is_manager;
var PlatformListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.platformList = ko.observableArray([]);
    self_.listPlatform = function (data) {
        ycoa.ajaxLoadGet("/api/second_sale_soft/platform.php", data, function (results) {
            self_.platformList.removeAll();
            $.each(results.list, function (index, platform) {
                platform.isTeach_text = platform.isTeach === 1 ? "√" : "";
                platform.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3060102')  && ( ycoa.user.userid() === platform.customer_id || platform.is_manager === true);
                platform.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3060103') && ( (ycoa.user.userid() === platform.customer_id && platform.is_edit === 0)|| platform.is_manager === true);
                platform.show = ycoa.SESSION.PERMIT.hasPagePermitButton('3060104');
                platform.te = ycoa.SESSION.PERMIT.hasPagePermitButton('3060106') && platform.isTe === 0;
                self_.platformList.push(platform);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {
                    action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    workName: $("#workName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            }, function (pageNo) {
                var data = {
                    action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    workName: $("#workName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            });
        });
    };
    self_.delPlatform = function (platform) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                platform.action = 2;
                ycoa.ajaxLoadPost("/api/second_sale_soft/platform.php", JSON.stringify(platform), function (result) {
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
    self_.editPlatform = function (platform) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + platform.id).show();
        $("#submit_" + platform.id).show();
        $("#cancel_" + platform.id).show();
        if (!$("#form_" + platform.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + platform.id));
        }
        $("#tr_" + platform.id + " input,#tr_" + platform.id + " textarea").removeAttr("disabled");
    };
    self_.showPlatform = function (platform) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + platform.id).show();
        $("#cancel_" + platform.id).show();
        if (!$("#form_" + platform.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + platform.id));
        }
        $("#tr_" + platform.id + " input,#tr_" + platform.id + " textarea").attr("disabled", "");
    };
    self_.cancelTr = function (platform) {
        $("#tr_" + platform.id).hide();
        $("#submit_" + platform.id).hide();
        $("#cancel_" + platform.id).hide();
    };
    self_.doEditSubmit = function (platform) {
        var formid = "form_" + platform.id;
        var data = $("#" + formid).serializeJson();
        data.remark = $("#" + formid + " #remark_edit").html();
        data.action = 3;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale_soft/platform.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.doTe = function (platform) {
        platform.isTe = 1;
        updateCL(platform);
    };
}();
$(function () {
    ko.applyBindings(PlatformListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        var data = {
            action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").reLoad(function () {
        $("#workName").val("");
        $("#wwName").val("");
        $("#searchDateTime").val("");
        $('#searchStartTime').val("");
        $('#searchEndTime').val("");
        reLoadData({action: 1});
    });
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: name, searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    }, '关键字查找', 'workName');
    $("#dataTable").searchDateTimeSlot(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), searchTime: $('#searchDateTime').val(), searchStartTime: d.start, searchEndTime: d.end
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").searchDateTime(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            workName: $("#workName").val(), searchTime: d, searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
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
        $("#add_platform_form input").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_platform_form #remark").html("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
        $("#add_platform_form #customer").val(ycoa.user.username());
        $("#add_platform_form #customer_id").val(ycoa.user.userid());
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
        $("#add_fillarrears_form #customer").val(ycoa.user.username());
        $("#add_fillarrears_form #customer_id").val(ycoa.user.userid());
    });
    $("#add_fillarrears_form #qq").keypress(function (e) {
        if (e.keyCode == 13) {
            if ($(this).val()) {
                $.get(ycoa.getNoCacheUrl("/api/second_sale_soft/platform.php"), {action: 5, qq: $(this).val()}, function (res) {
                    if (res.length == 1) {
                        var data = res[0];
                        $("#add_fillarrears_form input").each(function () {
                            var el = $(this);
                            if (data[el.attr("id")]) {
                                el.val(data[el.attr("id")]);
                            }
                        });
                        $("#add_fillarrears_form #parent_id").val(data.id);
                    } else if (res.length > 1) {
                        var html = "";
                        $.each(res, function (index, d) {
                            html += "<div class='auto_tr' v='" + (JSON.stringify(d)) + "'>";
                            html += "<div class='auto_td' title='" + (d.add_time) + "'>" + (d.add_time) + "</div>";
                            html += "<div class='auto_td' title='" + (d.ww) + "'>" + (d.ww) + "</div>";
                            html += "<div class='auto_td' title='" + (d.qq) + "'>" + (d.qq) + "</div>";
                            html += "<div class='auto_td' title='" + (d.money) + "'>" + (d.money) + "</div>";
                            html += "<div class='auto_td' title='" + (d.name) + "'>" + (d.name) + "</div>";
                            html += "<div class='auto_td' title='" + (d.customer) + "'>" + (d.customer) + "</div>";
                            html += "<div class='auto_td' title='" + (d.rception_staff) + "'>" + (d.rception_staff) + "</div>";
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
    $("#add_platform_form #quick_write").keypress(function (e) {
        if (e.keyCode === 13 && $(this).val()) {
            $.get(ycoa.getNoCacheUrl("/api/sale_soft/salecount.php"), {action: 20, qk: $(this).val()}, function (res) {
                if (res.length === 1) {
                    var data = res[0];
                    $("#add_platform_form input").each(function () {
                        var el = $(this);
                        if (data[el.attr("id")]) {
                            el.val(data[el.attr("id")]);
                        }
                    });
                    $("#add_platform_form #phone").val(data.mobile);
                } else if (res.length > 1) {
                    var y = $(window).height();
                    var x = $(window).width();
                    var html = "<div style='position:fixed;z-index:99999;top:" + ((y - 500) / 2) + "px;left:" + ((x - 750) / 2) + "px;height: 400px;width: 700px;box-shadow: 0 5px 15px rgba(0,0,0,.5);border: 1px solid #999;background:#ffffff;'>";
                    html += "<div style='height:40px;line-height:40px;'>";
                    html += "<div title='关闭' style='height:40px;width:40px;line-height:40px;float:right;cursor:pointer;text-align: center;' onclick='close_sale_qk(this)'>x</div>";
                    html += "</div>";
                    html += "<div style='overflow-x: hidden;overflow-y: auto;height: 358px;'>";
                    html += "<table style='width:698px;border-top: 1px solid #999;'>";
                    html += "<thead>";
                    html += "<tr style='height:40px;border-bottom: 1px solid #999;'>";
                    html += "<td style='width:140px;'>添加日期</td>";
                    html += "<td style='width:140px;'>旺旺</td>";
                    html += "<td style='width:100px;'>QQ</td>";
                    html += "<td style='width:80px;'>真实姓名</td>";
                    html += "<td>联系电话</td>";
                    //html += "<td>付款金额</td>";
                    html += "</thead>";
                    html += "<tbody>";
                    $.each(res, function (index, d) {
                        html += "<tr style='height:40px;width:698px;border-bottom: 1px solid #999;cursor:pointer;' v='" + (JSON.stringify(d)) + "' onclick=set_sale_pl_val(this);>";
                        html += "<td>" + (d.addtime) + "</div>";
                        html += "<td>" + (d.ww) + "</div>";
                        html += "<td>" + (d.qq) + "</div>";
                        html += "<td>" + (d.name) + "</div>";
                        html += "<td>" + (d.mobile) + "</div>";
                        //html += "<td>" + (d.money) + "</div>";
                        html += "</tr>";
                    });
                    html += "</tbody>";
                    html += "</table>";
                    html += "</div>";
                    html += "</div>";
                    $("body").append(html);
                } else if (res.length == 0) {
                    ycoa.UI.toast.warning("未匹配到相应的数据,请核对后重试~");
                    $("#add_platform_form input").each(function () {
                        if (['customer', 'customer_id'].indexOf($(this).attr('id')) === -1) {
                            $(this).val("");
                        }
                    });
                    $("#add_platform_form #remark").html("");
                }
            });
        }
    });
    $("#add_platform_form #quick_write_paste").blur(function(){
            var self = this;
            var context = $(self).val();
            var re =/^旺旺：(.*)QQ：(.*)手机：(.*)支付宝账号：(.*)转款人姓名：(.*)收款账号：(.*)交易\/订单号：(.*)$/;
            var result=  re.test(context);
            if(!result)
                 ycoa.UI.toast.warning("参数格式不正确或者输入项缺失，请检查后重新再试~");
            var result=  re.exec(context);      
           $("#add_platform_form #ww").val(result[1]);
           $("#add_platform_form #qq").val(result[2]);
           $("#add_platform_form #phone").val(result[3]);
           $("#add_platform_form #alipay_account").val(result[4]);
           $("#add_platform_form #name").val(result[5]);
           $("#add_platform_form #payment_method").val(result[6]);
           $("#add_platform_form #tra_num").val(result[7]);
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
        var msg = "<img src='../../assets/global/img/heihei.jpg' style='height: 100px;width: auto;margin-right:20px;float:left;'>";
        msg += "<div style='float:left;line-height:100px;font-size:16px;'>你确定添加的没有问题了吗？亲，错了提成就没了噢 ~</div>";
        msg += "<div style='clear:both;'></div>";
        ycoa.UI.messageBox.confirm(msg, function (btn) {
            if (btn) {
                $("#add_platform_form").submit();
            }
        });
    });
    $("#btn_submit_fill_primary").click(function () {
        $("#add_fillarrears_form").submit();
    });
    $("#btn_toexcel_primary").click(function () {
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (start_time || end_time) {
            window.location.href = "/api/second_sale_soft/platform.php?action=10&start_time=" + start_time + "&end_time=" + end_time;
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#add_platform_form #isTeach").autoRadio(array['isTeach']);
    $("#add_platform_form #diamond_card").autoEditSelecter(array['diamond_card']);
//    $("#add_platform_form #payment_method").autoEditSelecter(array['payment_method']);
//   $("#add_fillarrears_form #payment_method").autoEditSelecter(array['payment_method']);
//    , function (d) {
//        if ((d.text).indexOf("微信") === -1) {
//            $("#add_platform_form #label_tra_num").html("交易号<span class='required'>*</span>");
//        } else {
//            $("#add_platform_form #label_tra_num").html("交易号");
//        }
//    }

    $("#add_platform_form #remark").pasteImgEvent();
    $("#add_fillarrears_form #remark_fillarrears").pasteImgEvent();
    $("#auto_tab").autoRadio([{id: 1, text: '流量', default: true}, {id: 2, text: '实物', default: true}, {id: 3, text: '装修', default: true}]);
    $.get(ycoa.getNoCacheUrl("/api/second_sale/customer.php"), {action: 2, type: 1}, function (res) {
        $("#add_platform_form #rception_staff").autoEditSelecter(res, function (d) {
            $("#add_platform_form #rception_staff_id").val(d.id);
        });
        rception_array = res;
    });

    $("#show_ranking").click(function () {
        ycoa.ajaxLoadGet("/api/second_sale_soft/platform.php", {action: 11, time_unit: 1, tab: $("#auto_tab").val()}, function (result) {
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
                html += "<li><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.customer) + "</span><span class='count'>" + (d.count) + "</span></li>";
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
                ycoa.ajaxLoadGet("/api/second_sale_soft/platform.php", {action: 11, time_unit: v, tab: $("#auto_tab").val()}, function (result) {
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
                        html += "<li><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.customer) + "</span><span class='count'>" + (d.count) + "</span></li>";
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
    $("#add_platform_form input").keyup(function () {
        var that = $(this);
        var val = that.val();
        if (val.indexOf(" ") !== -1) {
            that.val($.trim(that.val()));
        }
    });
    //只有指定的管理员能补录数据
    ycoa.ajaxLoadGet("/api/second_sale_soft/platform.php", {action: 21}, function (result) {
        is_manager = result.is_manager;
        if (result.is_manager) {
            $("#add_platform_form .date-time-picker-bind-mouseover").datetimepicker({autoclose: true});
            $("#add_fillarrears_form .date-time-picker-bind-mouseover").datetimepicker({autoclose: true});
            $("#add_platform_form #customer,#add_fillarrears_form #customer").click(function () {
                ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (data, el) {
                    el.val(data.name);
                    $("#customer_id", el.parent()).val(data.id);
                });
            });
        } else {
            $("#add_platform_form #add_time_manager").remove();
            $("#add_fillarrears_form #add_time_manager").remove();
        }
    });
    if (jQuery.ui) {
        $('.auto_tab_main').draggable({handle: ".title"});
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
        $('.auto_tab_main').draggable({handle: ".title"});
    }
});
function close_sale_qk(d) {
    $(d).parent().parent().remove();
}
function set_sale_pl_val(d) {
    var data = JSON.parse($(d).attr("v"));
    $("#add_platform_form input").each(function () {
        var el = $(this);
        if (data[el.attr("id")]) {
            el.val(data[el.attr("id")]);
        }
    });
    $("#add_platform_form #phone").val(data.mobile);
    $(d).parent().parent().parent().parent().remove();
}
function reLoadData(data) {
    PlatformListViewModel.listPlatform(data);
}

function updateCL(platform) {
    platform.action = 3;
    ycoa.ajaxLoadPost("/api/second_sale_soft/platform.php", JSON.stringify(platform), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({action: 1});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}

var array = {
    isTeach: [{id: '1', text: '是'}, {id: '0', text: '否'}],
    diamond_card: [{id: '基础版', text: '基础版'}, {id: '标准版', text: '标准版'}, {id: '专业版', text: '专业版'}, {id: '豪华版', text: '豪华版'}, {id: '尊贵版', text: '尊贵版'}, {id: '至尊版', text: '至尊版'}],
    payment_method: function () {
        var val = "llw8988@163.com(周峰),zhouslowly@foxmail.com(周峰),286878263@qq.com(周峰),kllpay@126.com(王远敏),kellpay@163.com（王远敏）,lingfanllvip@163.com（刘博）,中国银行（王远敏）,邮政银行（王远敏）,交通银行（王远敏）,工商银行（王远敏）,建设银行（王远敏）,农业银行（王远敏）,农村信用社（王远敏）,店铺/掌柜：飞凰品牌,微信llw8988（王远敏）,微信:fsxwxsk（王远敏）,微信:fsxskzy（王远敏）,财付通:2405658931（王远敏）";
        val = val.split(",");
        var array = new Array();
        $.each(val, function (i, d) {
            array.push({id: d, text: d});
        });
        return array;
    }()
};
function initEditSeleter(el) {
    $("#isTeach", el).autoRadio(array['isTeach']);
    $("#diamond_card", el).autoEditSelecter(array['diamond_card']);
    //$("#payment_method", el).autoEditSelecter(array['payment_method']);
    $("#rception_staff", el).autoEditSelecter(rception_array, function (d) {
        $("#rception_staff_id", el).val(d.id);
    });
    if (is_manager) {
        $(".date-time-picker-bind-mouseover", el).datetimepicker({autoclose: true});
        $("#customer", el).click(function () {
            ycoa.UI.empSeleter({el: $(this), groupId: [11], type: 'only'}, function (d, el) {
                el.val(d.name);
                $("#customer_id", el.parent()).val(d.id);
            });
        });
    }
    $("#remark_edit", el).pasteImgEvent();
    el.attr('autoEditSelecter', 'autoEditSelecter');
}