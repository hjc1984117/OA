var create_detail = false;
var causeleaveListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.causeleaveList = ko.observableArray([]);
    self_.listCauseleave = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/causeleave.php", data, function (results) {
            self_.causeleaveList.removeAll();
            $.each(results.list, function (index, causeleave) {
                causeleave.clHours = Math.floor(causeleave.hours / 24) + '天' + Math.floor(causeleave.hours % 24) + '小时';
                if (causeleave.starttime) {
                    causeleave.start_time = causeleave.starttime.split(" ")[1];
                    causeleave.starttime = causeleave.starttime.split(" ")[0];
                }
                if (causeleave.endtime) {
                    causeleave.end_time = causeleave.endtime.split(" ")[1];
                    causeleave.endtime = causeleave.endtime.split(" ")[0];
                }
                self_.causeleaveList.push(causeleave);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var date = {action: 1, type: $("#search_type").val(), deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()};
                reLoadData(date);
            }, function (pageNo) {
                var date = {action: 1, type: $("#search_type").val(), deptid: $("#deptid").val(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()};
                reLoadData(date);
            });
        });
    };
    self_.updateCauseleave = function (causeleave) {
        updateCL(causeleave);
    };
    self_.managerStop = function (causeleave) {
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + causeleave.id).show();
        $("#doback_" + causeleave.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.selfDelete = function (causeleave) {
        ycoa.UI.messageBox.confirm("确定删除该条事假流程单吗?", function (btn) {
            if (btn) {
                causeleave.action = 3;
                ycoa.ajaxLoadPost("/api/attendance/causeleave.php", JSON.stringify(causeleave), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success("操作成功~");
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error("操作失败~");
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.selfEdit = function (causeleave) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + causeleave.id).show();
        $("#submit_" + causeleave.id).show();
        $("#cancel_" + causeleave.id).show();
    };
    self_.cancelTr = function (causeleave) {
        $("#tr_" + causeleave.id).hide();
        $("#submit_" + causeleave.id).hide();
        $("#cancel_" + causeleave.id).hide();
    };
    self_.show = function (causeleave) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + causeleave.id).show();
        $("#cancel_" + causeleave.id).show();
        $("#tr_" + causeleave.id + " input,#tr_" + causeleave.id + " textarea,#tr_" + causeleave.id + " select").attr("disabled", "");
    };
    self_.managerStopSub = function (causeleave) {
        causeleave.opt = "stop";
        causeleave.comment = $("#doback_" + causeleave.id).find("textarea").val();
        causeleave.optid = ycoa.user.userid();
        causeleave.optname = ycoa.user.username();
        updateCL(causeleave);
        $("#doback_" + causeleave.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
    self_.managerStopClose = function (causeleave) {
        $("#doback_" + causeleave.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
};

$(function () {
    ko.applyBindings(causeleaveListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, type: $("#search_type").val(), deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $("#deptid").val("");
        $("#searchUserName").val("");
        $("#searchStartTime").val("");
        $("#searchEndTime").val("");
        $("#search_type").val("");
    });
    $("#dataTable").searchAutoStatus([
        {id: 1, text: '事假'}, {id: 2, text: '病假'}, {id: 3, text: '产假'}, {id: 4, text: '丧假'}, {id: 5, text: '婚假'}, {id: 6, text: '陪产假'}, {id: 0, text: '全部'}
    ], function (d) {
        reLoadData({action: 1, type: d.id, deptid: $("#deptid").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()});
        $("#search_type").val(d.id);
    }, '按假别筛选');
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, type: $("#search_type").val(), deptid: id, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, type: $("#search_type").val(), deptid: $("#deptid").val(), searchName: name, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: $("#searchStartTime").val(), searchEndTime: $("#searchEndTime").val()});
    });
    $("#dataTable").searchDateTimeSlot(function (searchdate) {
        reLoadData({action: 1, type: $("#search_type").val(), deptid: $("#deptid").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchStartTime: searchdate.start, searchEndTime: searchdate.end});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_causeleave_form input[type='text'],#add_causeleave_form input[type='hidden'],#add_causeleave_form textarea").val("");
        $("#add_causeleave_form #username").val(ycoa.user.username());
        $("#add_causeleave_form #userid").val(ycoa.user.userid());
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $('.date-picker-bind-mouseover').live('mouseover', function () {
        $(this).datepicker({autoclose: true});
    });
    $('.timepicker-24').live('mouseover', function () {
        $(this).timepicker({
            autoclose: true,
            minuteStep: 5,
//            showSeconds: false,
            showMeridian: false
        });
    });
    $(".form_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        if (data.type == 1 || data.type == 2) {
            data.salary = 0;
        }
        data.starttime = data.starttime + " " + data.start_time;
        data.endtime = data.endtime + " " + data.end_time;
        data.action = 4;
        ycoa.ajaxLoadPost("/api/attendance/causeleave.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error("操作失败~");
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_causeleave_form #type").change(function () {
        var val = $(this).val();
        if (val != 1 && val != 2) {
            $("#add_causeleave_form #salary_for_type").show();
            $("#add_causeleave_form #salary").removeAttr("disabled");
        } else {
            $("#add_causeleave_form #salary_for_type").hide();
            $("#add_causeleave_form #salary").attr("disabled", "");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_causeleave_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/causeleave.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });


    $("#dataTable .data_list_div").live("mouseenter", function () {
        var self_ = $(this);
        self_.data('lastEnter', new Date().getTime());
        setTimeout(function () {
            var t1 = new Date().getTime(), t2 = self_.data('lastEnter');
            if (t2 > 0 && t1 - t2 >= 500) {
                var w_id = self_.attr("val");
                $("#workflowLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
                    $(this).addClass("data_open");
                });
                $("#workflowLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
                $.get(ycoa.getNoCacheUrl("/api/sys/workflowLog.php"), {action: 3, workflow_id: w_id}, function (result) {
                    if (result.list.length > 0) {
                        var html = "<ul>";
                        $.each(result.list, function (idnex, d) {
                            html += "<li>[" + d.addtime + " " + d.role_type_name + "(" + d.username + ")]->" + d.opt;
                            if (d.comment) {
                                html += "<br>" + d.comment;
                            }
                            html += "</li>";
                        });
                        html += "</ul>";
                        $("#workflowLog_detail_" + w_id).html(html);
                    } else {
                        $("#workflowLog_detail_" + w_id).html("<img src='../../assets/global/img/workflowLog_detail_nodata.png'>");
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
                $(this).find("textarea").val("");
            });
        }
    });
});
function updateCL(causeleave) {
    causeleave.action = 2;
    causeleave.starttime = causeleave.starttime + " " + causeleave.start_time;
    causeleave.endtime = causeleave.endtime + " " + causeleave.end_time;
    ycoa.ajaxLoadPost("/api/attendance/causeleave.php", JSON.stringify(causeleave), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({action: 1});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}
;
function reLoadData(data) {
    data.action = 1;
    causeleaveListViewModel.listCauseleave(data);
}