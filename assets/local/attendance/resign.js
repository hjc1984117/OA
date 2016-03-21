var resignListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.resignList = ko.observableArray([]);
    self_.listPaidLeave = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/resign.php", data, function (results) {
            self_.resignList.removeAll();
            $.each(results.list, function (index, resign) {
                if (resign.signdate) {
                    resign.sign_date = resign.signdate.split(" ")[1];
                    resign.signdate = resign.signdate.split(" ")[0];
                }
                resign.addtime = new Date(resign.addtime).format("yyyy-MM-dd");
                resign.signtype = resign.signtype.toString();
                self_.resignList.push(resign);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, deptid: $("#deptid").val(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.updateResign = function (resign) {
        updateRs(resign);
    };
    self_.stop = function (resign) {
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + resign.id).show();
        $("#doback_" + resign.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.selfDelete = function (resign) {
        ycoa.UI.messageBox.confirm("确定删除该条修改补卡(补签)流程信息?", function (btn) {
            if (btn) {
                resign.action = 4;
                ycoa.ajaxLoadPost("/api/attendance/resign.php", JSON.stringify(resign), function (result) {
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
    self_.selfEdit = function (resign) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + resign.id).show();
        $("#submit_" + resign.id).show();
        $("#cancel_" + resign.id).show();
    };
    self_.cancelTr = function (resign) {
        $("#tr_" + resign.id).hide();
        $("#submit_" + resign.id).hide();
        $("#cancel_" + resign.id).hide();
    };
    self_.show = function (resign) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + resign.id).show();
        $("#cancel_" + resign.id).show();
        $("#tr_" + resign.id + " input,#tr_" + resign.id + " textarea,#tr_" + resign.id + " select").attr("disabled", "");
    };
    self_.managerStopSub = function (resign) {
        resign.opt = "stop";
        resign.comment = $("#doback_" + resign.id).find("textarea").val();
        resign.optid = ycoa.user.userid();
        resign.optname = ycoa.user.username();
        updateRs(resign);
        $("#doback_" + resign.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
    self_.managerStopClose = function (resign) {
        $("#doback_" + resign.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
};
$(function () {
    ko.applyBindings(resignListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $("#dataTable").searchDateTime(function (searchdate) {
        reLoadData({action: 1, searchDate: searchdate});
    }, '补卡时间');
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: data.sort, sortname: data.sortname, seachDate: $("#searchDateTime").val()});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#open_dialog_btn").click(function () {
        $("#add_resign_form input[type='text'],#add_resign_form input[type='hidden']").val("");
        $("#add_resign_form #username").val(ycoa.user.username());
        $("#add_resign_form #userid").val(ycoa.user.userid());
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
        data.action = 3;
        ycoa.ajaxLoadPost("/api/attendance/resign.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error("操作失败~");
            }
            ycoa.UI.block.hide();
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_resign_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/resign.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
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
                    $(this).addClass("workflow_open");
                });
                $("#workflowLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
                $.get(ycoa.getNoCacheUrl("/api/sys/workflowLog.php"), {action: 4, workflow_id: w_id}, function (result) {
                    if (result.list.length > 0) {
                        var html = "<ul>";
                        $.each(result.list, function (idnex, d) {
                            html += "<li>[" + d.addtime + " " + d.role_type_name + "(" + d.username + ")]->" + d.opt + "</li>";
                            if (d.comment) {
                                html += "<li>" + d.comment + "</li>";
                            }
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
    $(".workflow_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("workflow_open");
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

function reLoadData(data) {
    resignListViewModel.listPaidLeave(data);
}
function updateRs(resign) {
    resign.action = 2;
    resign.signdate = resign.signdate + " " + resign.sign_date;
    ycoa.ajaxLoadPost("/api/attendance/resign.php", JSON.stringify(resign), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success(result.msg + "成功~");
            reLoadData({action: 1});
        } else {
            ycoa.UI.toast.error(result.msg + "失败~");
        }
        ycoa.UI.block.hide();
    });
}