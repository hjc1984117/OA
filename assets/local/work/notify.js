var NotifyListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.notifyList = ko.observableArray([]);
    self_.listNotify = function (data) {
        ycoa.ajaxLoadGet("/api/work/notify.php", data, function (results) {
            self_.notifyList.removeAll();
            $.each(results.list, function (index, notify) {
                notify.addtime = new Date(notify.addtime).format("yyyy-MM-dd");
                notify.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1030402");
                notify.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1030402");
                notify.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1030404");
                self_.notifyList.push(notify);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delNotify = function (notify) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                notify.action = 3;
                ycoa.ajaxLoadPost("/api/work/notify.php", JSON.stringify(notify), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        NotifyListViewModel.notifyList.remove(notify);
                        reLoadData();
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editNotify = function (notify) {
        $("#myModal #title").val(notify.title);
        $("#myModal #content").val(notify.content);
        $("#myModal #btn_submit_primary").text("保存").show();
        $("#send_notify_form #id").val(notify.id);
    };
    self_.showNotify = function (notify) {
        $("#myModal #title").val(notify.title);
        $("#myModal #content").val(notify.content);
        $("#myModal #btn_submit_primary").hide();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), status: $("#status").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(NotifyListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#send_notify_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#send_notify_form input[type='text'],#send_notify_form input[type='hidden'], #send_notify_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
        $("#myModal #btn_submit_primary").text("发送并保存").show();
        $("#send_notify_form #id").val("");
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/notify.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('#cancel_1').click();
                reLoadData();
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $('#send_notify_form #content').xheditor({tools: 'Cut,Copy,Paste,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|Hr,Table,|,Source,Preview', skin: 'nostyle', html5Upload: false, width: '300', height: '350'});
    $('.popovers').popover();
});
function reLoadData(data) {
    NotifyListViewModel.listNotify(data);
}