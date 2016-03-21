var time_out = null;
var ReceptionListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.receptionList = ko.observableArray([]);
    self_.listReception = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/qq_reception.php", data, function (results) {
            self_.receptionList.removeAll();
            $.each(results.list, function (index, reception) {
                reception.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3031403");
                reception.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3031402");
                reception.begin = ((reception.toplimit > reception.finish) && reception.status === 0);//ycoa.SESSION.PERMIT.hasPagePermitButton("2011106") &&
                reception.end = ((reception.toplimit > reception.finish) && reception.status !== 0);//ycoa.SESSION.PERMIT.hasPagePermitButton("2011106") &&
                reception.workin = reception.status0 === 1;
                reception.workout = reception.status0 === 0;
                reception.color_ = reception.status === 0 ? 'color:red' : '';
                self_.receptionList.push(reception);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({
                    action: 1,
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val()
                });
            }, function (pageNo) {
                reLoadData({
                    action: 1,
                    pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val()
                });
            });
        });
    };
    self_.delReception = function (reception) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                reception.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", JSON.stringify(reception), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editReception = function (reception) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + reception.id).show();
        $("#submit_" + reception.id).show();
        $("#cancel_" + reception.id).show();
        if (!$("#form_" + reception.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + reception.id));
        }
    };
    self_.cancelTr = function (reception) {
        $("#tr_" + reception.id).hide();
        $("#submit_" + reception.id).hide();
        $("#cancel_" + reception.id).hide();
    };
    self_.updateStatus = function (reception) {
        //对于售前客服而言，只有夏冬，致远，大白可以操作所有人正常/暂停，其他的售前连他们自己的都不能操作
        // || [0, 1101, 1102, 1103,1112].indexOf(ycoa.user.role_id()) !== -1
        if ([161,163,165,178,185,186].indexOf(ycoa.user.userid()) !== -1) {
           
            if (reception.status0 === 0 && reception.status === 0) {
                ycoa.UI.toast.warning("下班状态,无法启用~");
            } else {
                reception.status = (reception.status === 1 ? 0 : 1);
                reception.action = 40;
                ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", JSON.stringify(reception), function (result) {
                    if (result.code === 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        } else {
            ycoa.UI.toast.warning("无权操作接单状态~");
        }
    };
    self_.updateStatus0 = function (reception) {
        if (reception.presales_id === ycoa.user.userid() || [0, 1101, 1102, 1103,1112].indexOf(ycoa.user.role_id()) !== -1 || [165,166,213].indexOf(ycoa.user.userid()) !== -1) {
            reception.status0 = (reception.status0 === 1 ? 0 : 1);
            reception.action = 30;
            ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", JSON.stringify(reception), function (result) {
                if (result.code === 0) {
                    ycoa.UI.toast.success(result.msg);
                    reLoadData({action: 1});
                } else {
                    ycoa.UI.toast.error(result.msg);
                }
                ycoa.UI.block.hide();
            });
        } else {
            ycoa.UI.toast.warning("无权操作上下班~");
        }
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({
            action: 1,
            sort: data.sort, sortname: data.sortname,
            pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),
            searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val()
        });
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#searchChannel").val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: name, searchChannel: $("#searchChannel").val()
        });
    });
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: 705, text: '百度(PC)'}, {id: 716, text: '百度(移动)'}, {id: 706, text: '360'}, {id: 711, text: '搜狗'}], function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchChannel: d.id
        });
    }, '渠道搜索', 'searchChannel');
    $('.timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 5,
        showSeconds: false,
        showMeridian: false
    });
    ko.applyBindings(ReceptionListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#add_reception_form #presales").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (data, el) {
            el.val(data.name);
            $("#add_reception_form #presales_id").val(data.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_reception_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_reception_form input,#add_reception_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_reception_form input[type='checkbox']").removeAttr("checked");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#action_btn").click(function () {
        var data = {action: 4};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#all_stop_btn").click(function () {
        var data = {action: 41};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/qq_reception.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#dataTable").on("mouseenter", ".updateStatus0", function () {
        loadLog($(this), 10001);
    }).on("mouseleave", ".updateStatus0", function () {
        clearTimeout(time_out);
    });
    $("#dataTable").on("mouseenter", ".updateStatus", function () {
        loadLog($(this), 10000);
    }).on("mouseleave", ".updateStatus", function () {
        clearTimeout(time_out);
    });
    $("#dataTable").on("mouseleave", ".dataLog_detail", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
});

function loadLog(that, action) {
    var w_id = that.attr('val');
    time_out = setTimeout(function () {
        clearTimeout(time_out);
        var logel = null;
        if (action === 10001) {
            logel = $("#dataLog_detail_" + w_id);
        } else {
            logel = $("#dataLog_detail1_" + w_id);
        }
        logel.animate({opacity: 'show'}, 50, function () {
            that.addClass("data_open");
            logel.html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
        });
        $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: action, obj_id: w_id}, function (result) {
            if (result.list.length > 0 && result.list) {
                var html = "<ul>";
                $.each(result.list, function (idnex, d) {
                    html += "<li>[" + d.addtime + " (" + d.username + ")] <br>" + d.changed_desc + "</li>";
                });
                html += "</ul>";
                logel.html(html);
            } else {
                logel.html("<img src='../../assets/global/img/workflowLog_detail_nodata.png'>");
            }
        });
    }, 600);
}

function reLoadData(data) {
    ReceptionListViewModel.listReception(data);
}
function initEditSeleter(el) {
    $('.timepicker-24', el).timepicker({
        autoclose: true,
        minuteStep: 5,
        showSeconds: false,
        showMeridian: false
    });
//    $("#presales", el).click(function () {
//        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (data, node) {
//            node.val(data.name);
//            $("#presales_id", el).val(data.id);
//        });
//    });
    el.attr('autoEditSelecter', 'autoEditSelecter');
}