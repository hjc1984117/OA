var adjustRestListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.adjustRestList = ko.observableArray([]);
    self_.listAdjustRest = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/adjustRest.php", data, function (results) {
            self_.adjustRestList.removeAll();
            $.each(results.list, function (index, adjustRest) {
                adjustRest.year = ((adjustRest.add_time).split(" ")[0]).split("-")[0];
                adjustRest.mouth = ((adjustRest.add_time).split(" ")[0]).split("-")[1];
                adjustRest.day = ((adjustRest.add_time).split(" ")[0]).split("-")[2];
                adjustRest.add_time = (adjustRest.add_time).split(" ")[0];
                adjustRest.rest_date = (adjustRest.rest_date).split(" ")[0];
                adjustRest.adjust_to = (adjustRest.adjust_to).split(" ")[0];
                self_.adjustRestList.push(adjustRest);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), deptid: $("#deptid").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), deptid: $("#deptid").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.updateAdjustRest = function (adjustRest) {
        updateAR(adjustRest);
    };
    self_.managerStop = function (adjustRest) {
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + adjustRest.id).show();
        $("#doback_" + adjustRest.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.selfDelete = function (adjustRest) {
        ycoa.UI.messageBox.confirm("确定删除该条调休申请流程单吗?", function (btn) {
            if (btn) {
                adjustRest.action = 3;
                ycoa.ajaxLoadPost("/api/attendance/adjustRest.php", JSON.stringify(adjustRest), function (result) {
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
    self_.selfEdit = function (adjustRest) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + adjustRest.id).show();
        $("#submit_" + adjustRest.id).show();
        $("#cancel_" + adjustRest.id).show();
        $("#tr_" + adjustRest.id + " input,#tr_" + adjustRest.id + " textarea,#tr_" + adjustRest.id + " select").removeAttr("disabled");
    };
    self_.cancelTr = function (adjustRest) {
        $("#tr_" + adjustRest.id).hide();
        $("#submit_" + adjustRest.id).hide();
        $("#cancel_" + adjustRest.id).hide();
    };
    self_.show = function (adjustRest) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + adjustRest.id).show();
        $("#cancel_" + adjustRest.id).show();
        $("#tr_" + adjustRest.id + " input,#tr_" + adjustRest.id + " textarea,#tr_" + adjustRest.id + " select").attr("disabled", "");
    };

    self_.managerStopSub = function (adjustRest) {
        adjustRest.opt = "stop";
        adjustRest.comment = $("#doback_" + adjustRest.id).find("textarea").val();
        adjustRest.optid = ycoa.user.userid();
        adjustRest.optname = ycoa.user.username();
        updateAR(adjustRest);
        $("#doback_" + adjustRest.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
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
    ko.applyBindings(adjustRestListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), sort: data.sort, sortname: data.sortname});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $("#searchUserName").val('');
        $("#deptid").val('');
        $(".search_dept_title").html("按照部门查询");
        $("#searchDateTime").val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id, searchDate: $("#searchDateTime").val(), searchName: $("#searchUserName").val()});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name, deptid: $("#deptid").val(), searchDate: $("#searchDateTime").val()});
    });
    $("#dataTable").searchDateTime(function (searchdate) {
        reLoadData({action: 1, searchDate: searchdate, searchName: $("#searchUserName").val(), deptid: $("#deptid").val()});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $(".form_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 4;
        ycoa.ajaxLoadPost("/api/attendance/adjustRest.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error("操作失败~");
            }
            ycoa.UI.block.hide();
        });
    });
    $("#open_dialog_btn").click(function () {
        $("#add_adjustRest_form input[type='text'],#add_adjustRest_form input[type='hidden'], #add_adjustRest_form textarea").val("");
        $("#add_adjustRest_form #username").val(ycoa.user.username());
        $("#add_adjustRest_form #userid").val(ycoa.user.userid());
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_adjustRest_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/adjustRest.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
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
                $.get(ycoa.getNoCacheUrl("/api/sys/workflowLog.php"), {action: 2, workflow_id: w_id}, function (result) {
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

function updateAR(adjustRest) {
    adjustRest.action = 2;
    ycoa.ajaxLoadPost("/api/attendance/adjustRest.php", JSON.stringify(adjustRest), function (result) {
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
    adjustRestListViewModel.listAdjustRest(data);
}