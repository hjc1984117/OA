var rception_array;
var current_Date;
var PhysicaListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.physicaList = ko.observableArray([]);
    self_.listPhysica = function (data) {
        ycoa.ajaxLoadGet("/api/second_sale/physica.php", data, function (results) {
            self_.physicaList.removeAll();
            current_Date = results.current_date;
            $.each(results.list, function (index, physica) {
                physica.isTeaching_text = physica.isTeaching === 1 ? "√" : "";
                physica.isArrears_text = physica.isArrears === 1 ? "√" : "";
                physica.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3050202') && ( ycoa.user.userid() === physica.customer_id || physica.is_manager === true);
                physica.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3050203') && ( (physica.isTe === 0 && ycoa.user.userid() === physica.customer_id && physica.is_edit === 0) || physica.is_manager === true);//售后一般人员在审核后没有修改权限
               
                physica.show = ycoa.SESSION.PERMIT.hasPagePermitButton('3050204');
                physica.te = ycoa.SESSION.PERMIT.hasPagePermitButton('3050206') && physica.isTe === 0 && physica.is_manager === true;
                self_.physicaList.push(physica);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {
                    action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    keyWord: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            }, function (pageNo) {
                var data = {
                    action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    keyWord: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
                };
                if (data.searchStartTime || data.searchEndTime) {
                    data.searchTime = "";
                }
                reLoadData(data);
            });
        });
    };
    self_.delPhysica = function (physica) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                physica.action = 2;
                ycoa.ajaxLoadPost("/api/second_sale/physica.php", JSON.stringify(physica), function (result) {
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
    self_.editPhysica = function (physica) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + physica.id).show();
        $("#submit_" + physica.id).show();
        $("#cancel_" + physica.id).show();
        if (!$("#form_" + physica.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + physica.id));
        }
        $("#tr_" + physica.id + " input,#tr_" + physica.id + " textarea").removeAttr("disabled");
    };
    self_.showPhysica = function (physica) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + physica.id).show();
        $("#cancel_" + physica.id).show();
        if (!$("#form_" + physica.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + physica.id));
        }
        $("#tr_" + physica.id + " input,#tr_" + physica.id + " textarea").attr("disabled", "");
    };
    self_.cancelTr = function (physica) {
        $("#tr_" + physica.id).hide();
        $("#submit_" + physica.id).hide();
        $("#cancel_" + physica.id).hide();
    };
    self_.doEditSubmit = function (physica) {
        var formid = "form_" + physica.id;
        var data = $("#" + formid).serializeJson();
        data.remark = $("#" + formid + " #remark_edit").html();
        data.action = 3;

        var agent_price = data.all_price;
        agent_price == "" ? agent_price = 0 : agent_price = parseInt(agent_price);
        if (parseInt(data.free_decoration) > 2) {
            if ((agent_price * 0.8) < 2000) {
                ycoa.UI.toast.warning("代理金额少于2000，最高只能选择2次");
                $("#" + formid + " #free_decoration").val(0);
                return;
            }
        }
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale/physica.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.doTe = function (physica) {
        physica.isTe = 1;
        updateCL(physica);
    };
    self_.change_free_decoration = function (physica) {
        console.log(physica);
    };
}();
$(function () {
    ko.applyBindings(PhysicaListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $(".date-time-picker-bind-mouseover").datetimepicker({autoclose: true}, function (d) {
       if (d && current_Date) {
           d = d.split(" ")[0];
           if (d > current_Date) {
               ycoa.UI.toast.warning("添加时间不能大于当前时间~");
           }
       }
   });
    $("#dataTable").sort(function (data) {
        var data = {
            action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            keyWord: $("#searchUserName").val(), searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").reLoad(function () {
        $("#searchUserName").val("");
        $("#searchDateTime").val("");
        $('#searchStartTime').val("");
        $('#searchEndTime').val("");
        reLoadData({action: 1});
    });
    $("#dataTable").searchUserName(function (name) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            keyWord: name, searchTime: $("#searchDateTime").val(), searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    }, '关键字查找');
    $("#dataTable").searchDateTimeSlot(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            keyWord: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(), searchStartTime: d.start, searchEndTime: d.end
        };
        if (data.searchStartTime || data.searchEndTime) {
            data.searchTime = "";
        }
        reLoadData(data);
    });
    $("#dataTable").searchDateTime(function (d) {
        var data = {
            action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            keyWord: $("#searchUserName").val(), searchTime: d, searchStartTime: $('#searchStartTime').val(), searchEndTime: $('#searchEndTime').val()
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
        $("#add_physica_form input,#add_physica_form textarea").each(function () {
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
      //  $("#add_physica_form #customer").val(ycoa.user.username());
        $("#add_physica_form #customer_id").val(ycoa.user.userid());  
         //只有指定的管理员能补录数据
      ycoa.ajaxLoadGet("/api/second_sale/physica.php", {action:21}, function (result) {
          if(result.is_manager){
              $("#add_physica_form #add_time_manager").show();
              $("#add_physica_form #customer").val("");
              $("#add_physica_form #customer").live("click", function () {
                  ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6]}, function (data, el) {
                      el.val(data.name);
                      $("#add_physica_form #customer_id").val(data.id);
                  });
              });
          }
          else{
              $("#add_physica_form #customer").val(ycoa.user.username());
          }
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
        $("#add_fillarrears_form #customer_id").val(ycoa.user.userid());
         //只有指定的管理员能补录数据
      ycoa.ajaxLoadGet("/api/second_sale/physica.php", {action:21}, function (result) {
          if(result.is_manager){
              $("#add_fillarrears_form #add_time_manager").show();
              $("#add_fillarrears_form #customer").val("");
              $("#add_fillarrears_form #customer").live("click", function () {
                  ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6]}, function (data, el) {
                      el.val(data.name);
                      $("#add_fillarrears_form #customer_id").val(data.id);
                  });
              });
          }
          else{
               $("#add_fillarrears_form #customer").val(ycoa.user.username());
          }
      });
    });
    $("#add_fillarrears_form #qq").keypress(function (e) {
        if (e.keyCode == 13) {
            if ($(this).val()) {
                $.get(ycoa.getNoCacheUrl("/api/second_sale/physica.php"), {action: 5, qq: $(this).val()}, function (res) {
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
                            html += "<div class='auto_td' title='" + (d.phone) + "'>" + (d.phone) + "</div>";
                            html += "<div class='auto_td' title='" + (d.name) + "'>" + (d.name) + "</div>";
                            html += "<div class='auto_td' title='" + (d.tra_num) + "'>" + (d.tra_num) + "</div>";
                            html += "<div class='auto_td' title='" + (d.customer) + "'>" + (d.customer) + "</div>";
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
        var msg = "<img src='../../assets/global/img/heihei.jpg' style='height: 100px;width: auto;margin-right:20px;float:left;'>";
        msg += "<div style='float:left;line-height:100px;font-size:16px;'>你确定添加的没有问题了吗？亲，错了提成就没了噢 ~</div>";
        msg += "<div style='clear:both;'></div>";
        ycoa.UI.messageBox.confirm(msg, function (btn) {
            if (btn) {
                $("#add_physica_form").submit();
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
            window.location.href = "/api/second_sale/physica.php?action=10&start_time=" + start_time + "&end_time=" + end_time;
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#add_physica_form #isTeaching").autoRadio(array['isTeachingArrears']);
    $("#add_physica_form #isArrears").autoRadio(array['isTeachingArrears']);
    $("#add_physica_form #free_decoration").change(function () {
        var agent_price = $("#add_physica_form #all_price").val();
        agent_price == "" ? agent_price = 0 : agent_price = parseInt(agent_price);
        if (parseInt($(this).val()) > 2) {
            if ((agent_price * 0.8) < 2000) {
                ycoa.UI.toast.warning("代理金额少于2000，最高只能选择2次");
                $("#add_physica_form #free_decoration").val(0);
            }
        }
    });
//    $("#add_physica_form #payment_method").autoEditSelecter(array['payment_method'], function (d) {
//        if ((d.text).indexOf("微信") === -1) {
//            $("#add_physica_form #label_tra_num").html("交易号<span class='required'>*</span>");
//        } else {
//            $("#add_physica_form #label_tra_num").html("交易号");
//        }
//    });
    $("#add_physica_form #remark").pasteImgEvent();
    $("#add_fillarrears_form #remark_fillarrears").pasteImgEvent();
    $.get(ycoa.getNoCacheUrl("/api/second_sale/customer.php"), {action: 2, type: 2}, function (res) {
        $("#add_physica_form #rception").autoEditSelecter(res, function (d) {
            $("#add_physica_form #rception_id").val(d.id);
        });
        rception_array = res;
    });
   
    //$("#add_fillarrears_form #payment_method").autoEditSelecter(array['payment_method']);
    $("#auto_tab").autoRadio([{id: 1, text: '流量', default: true}, {id: 2, text: '实物', default: true}, {id: 3, text: '装修', default: true}]);
    $("#show_ranking").click(function () {
        ycoa.ajaxLoadGet("/api/second_sale/platform.php", {action: 11, time_unit: 1, tab: $("#auto_tab").val()}, function (result) {
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
                ycoa.ajaxLoadGet("/api/second_sale/platform.php", {action: 11, time_unit: v, tab: $("#auto_tab").val()}, function (result) {
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
    $("#add_physica_form #quick_write").keyup(function (e) {
        if (e.keyCode === 13 && $(this).val()) {
            $.get(ycoa.getNoCacheUrl("/api/second_sale/platform.php"), {action: 20, qk: $(this).val()}, function (res) {
                if (res.length === 1) {
                    var data = res[0];
                    $("#add_physica_form input").each(function () {
                        var el = $(this);
                        if (data[el.attr("id")]) {
                            el.val(data[el.attr("id")]);
                        }
                    });
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
                    $("#add_physica_form input").each(function () {
                        if (['customer', 'customer_id'].indexOf($(this).attr('id')) === -1) {
                            $(this).val("");
                        }
                    });
                    $("#add_physica_form #remark").html("");
                }
            });
        }
    });   
    $(".auto_tab_close .close_btn").click(function () {
        $(".auto_tab_main").animate({left: (($(window).width() + 500) * 1) + "px", top: "-600px"}, 500);
        $(".auto_tab_main").animate({left: '-500px', top: '-600px'});
    });

    if (jQuery.ui) {
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
        $('.auto_tab_main').draggable({handle: ".title"});
    }
});
function close_sale_qk(d) {
    $(d).parent().parent().remove();
}
function set_sale_pl_val(d) {
    var data = JSON.parse($(d).attr("v"));
    $("#add_physica_form input").each(function () {
        var el = $(this);
        if (data[el.attr("id")]) {
            el.val(data[el.attr("id")]);
        }
    });
    $("#add_physica_form #phone").val(data.mobile);
    $(d).parent().parent().parent().parent().remove();
}
function reLoadData(data) {
    PhysicaListViewModel.listPhysica(data);
}

function updateCL(physica) {
    physica.action = 3;
    ycoa.ajaxLoadPost("/api/second_sale/physica.php", JSON.stringify(physica), function (result) {
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
    isTeachingArrears: [{id: '1', text: '是'}, {id: '0', text: '否'}],
    payment_method: function () {
        var val = "chuangshouk@163.com(代桂莲),chuangxiangshou@163.com(代桂莲),thydx1@163.com(代桂莲),thydx2@163.com(吴雨燕),thydx3@163.com(吴雨燕),chuangshouru@163.com(朱红静),chuangshour@163.com(朱红静),邮政银行(代桂莲),建设银行(代桂莲),工商银行(代桂莲),邮政银行(吴雨燕),中国银行(吴雨燕),农业银行(吴雨燕),农村信用社(代桂莲),农村信用社(吴雨燕),微信:dailishoukuan,信用卡:棒棒糖亲子情侣馆,财付通：809834236(代桂莲)";
        val = val.split(",");
        var array = new Array();
        $.each(val, function (i, d) {
            array.push({id: d, text: d});
        });
        return array;
    }()
};

function initEditSeleter(el) {
    $("#isTeaching", el).autoRadio(array['isTeachingArrears']);
    $("#isArrears", el).autoRadio(array['isTeachingArrears']);
    //$("#payment_method", el).autoEditSelecter(array['payment_method']);
    $("#rception", el).autoEditSelecter(rception_array, function (d) {
        $("#rception_id", el).val(d.id);
    });
    $(".date-time-picker-bind-mouseover", el).datetimepicker({autoclose: true});
    $("#remark_edit", el).pasteImgEvent();
    el.attr('autoEditSelecter', 'autoEditSelecter');
}