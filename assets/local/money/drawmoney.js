var DrawmoneyListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.drawmoneyList = ko.observableArray([]);
    self_.listDrawmoney = function (data) {
        ycoa.ajaxLoadGet("/api/money/drawmoney.php", data, function (results) {
            self_.drawmoneyList.removeAll();
            $.each(results.list, function (index, drawmoney) {
                self_.drawmoneyList.push(drawmoney);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), status: $("#status").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), searchName: $("#searchUserName").val(), deptid: $('#deptid').val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.updateDrawmoney = function (drawmoney) {
        updateCL(drawmoney);
    };
    self_.managerStop = function (drawmoney) {
        drawmoney.opt = "stop";
        updateCL(drawmoney);
    };
    self_.selfDelete = function (drawmoney) {
        ycoa.UI.messageBox.confirm("确定删除该条领款流程单吗?", function (btn) {
            if (btn) {
                drawmoney.action = 3;
                ycoa.ajaxLoadPost("/api/money/drawmoney.php", JSON.stringify(drawmoney), function (result) {
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
    self_.selfEdit = function (drawmoney) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + drawmoney.id).show();
        $("#submit_" + drawmoney.id).show();
        $("#cancel_" + drawmoney.id).show();
    };
    self_.cancelTr = function (drawmoney) {
        $("#tr_" + drawmoney.id).hide();
        $("#submit_" + drawmoney.id).hide();
        $("#cancel_" + drawmoney.id).hide();
    };
    self_.show = function (drawmoney) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + drawmoney.id).show();
        $("#cancel_" + drawmoney.id).show();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), status: $("#status").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#deptid').val('');
        $('#searchUserName').val('');
    });
    //$("#dataTable").searchUserStatus(function (id) {
    //    reLoadData({action: 1, status: $("#status").val()});
    //});
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(DrawmoneyListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
//    $("#add_drawmoney_form #username").live("click",function () {
//        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
//            el.val(data.name);
//            $("#add_drawmoney_form #userid").val(data.id);
//        });
//    });
//    $(".second_table #username").live("click",function () {
//        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
//            el.val(data.name);
//            $(".second_table #userid").val(data.id);
//        });
//    });
    $("#btn_submit_primary").click(function () {
        $("#add_drawmoney_form").submit();
    });
    
    $("#open_dialog_btn").click(function () {
        $("#add_drawmoney_form input[type='text'],#add_drawmoney_form input[type='hidden'], #add_drawmoney_form textarea").val("");
        $("#add_drawmoney_form #username").val(ycoa.user.username());
        $("#add_drawmoney_form #userid").val(ycoa.user.userid());
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
        data.action = 4;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/money/drawmoney.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $('.popovers').popover();
});
function updateCL(drawmoney) {
    drawmoney.action = 2;
    ycoa.ajaxLoadPost("/api/money/drawmoney.php", JSON.stringify(drawmoney), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
};
function reLoadData(data) {
    DrawmoneyListViewModel.listDrawmoney(data);
}