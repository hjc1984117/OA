var penaltyListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.penaltyList = ko.observableArray([]);
    self_.listPenalty = function (data) {
        ycoa.ajaxLoadGet("/api/attendance/penalty.php", data, function (results) {
            self_.penaltyList.removeAll();
            $.each(results.list, function (index, penalty) {
                penalty.addtime = new Date(penalty.addtime).format("yyyy-MM-dd");
                self_.penaltyList.push(penalty);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), deptid: $("#deptid").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), deptid: $("#deptid").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.stop = function (penalty) {
        $(".doback_open").animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
        });
        $("#doback_" + penalty.id).show();
        $("#doback_" + penalty.id).animate({width: '382px', opacity: 'show'}, 500, function () {
            $(this).addClass("doback_open");
        });
    };
    self_.updatePenalty = function (penalty) {
        updatePL(penalty);
    };
    self_.deletePenalty = function (penalty) {
        ycoa.UI.messageBox.confirm("确定删除该条行政处罚信息?", function (btn) {
            if (btn) {
                penalty.action = 3;
                ycoa.ajaxLoadPost("/api/attendance/penalty.php", JSON.stringify(penalty), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success("操作成功~");
                        reLoadData({});
                    } else {
                        ycoa.UI.toast.error("操作失败~");
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.managerStopSub = function (penalty) {
        penalty.opt = "stop";
        penalty.comment = $("#doback_" + penalty.id).find("textarea").val();
        penalty.optid = ycoa.user.userid();
        penalty.optname = ycoa.user.username();
        updatePL(penalty);
        $("#doback_" + penalty.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
    self_.managerStopClose = function (penalty) {
        $("#doback_" + penalty.id).animate({opacity: 'toggle', width: '0px'}, 500, function () {
            $(this).hide();
            $(this).removeClass("doback_open");
            $(this).find("textarea").val("");
        });
    };
};
$(function () {
    ko.applyBindings(penaltyListViewModel, $("#dataTable")[0]);
    reLoadData({});
    $("#dataTable").sort(function (data) {
        reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $("#deptid").val(), sort: data.sort, sortname: data.sortname});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
        $("#deptid").val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    });
    $("#dataTable").searchDateTime(function (searchdate) {
        reLoadData({searchDate: searchdate});
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
    $("#open_dialog_btn").click(function () {
        $("#add_penalty_form input[type='text'],#add_penalty_form input[type='hidden'], #add_penalty_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        })
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#add_penalty_form #username").click(function () {
        var groupIds = [];
        var dept_id = ycoa.user.dept1_id();
        if (dept_id != 1 && dept_id != 2) {
            groupIds.push(dept_id);
        }
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: groupIds}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_penalty_form").submit();
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
                $.get(ycoa.getNoCacheUrl("/api/sys/workflowLog.php"), {action: 1, workflow_id: w_id}, function (result) {
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
    $("#add_penalty_form #reason").autoEditSelecter(array['reason'], function (d) {
        if (d.id == '未戴工牌') {
            $("#add_penalty_form #money").val(20);
        } else if (d.id == '擅离岗位') {
            $("#add_penalty_form #money").val(30);
        }
    });
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/attendance/penalty.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
});

function updatePL(penalty) {
    penalty.action = 2;
    ycoa.ajaxLoadPost("/api/attendance/penalty.php", JSON.stringify(penalty), function (result) {
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
function reLoadData(data) {
    data.action = 1;
    penaltyListViewModel.listPenalty(data);
}


var array = {
    reason: [{id: '未戴工牌', text: '未戴工牌'}, {id: '擅离岗位', text: '擅离岗位'}]
};