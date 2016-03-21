var expireReminderViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.expireReminderList = ko.observableArray([]);
    self_.listExpireReminder = function (data) {
        ycoa.ajaxLoadGet("/api/domain/expireReminder.php", data, function (results) {
            self_.expireReminderList.removeAll();
            $.each(results.list, function (index, expireReminder) {
                expireReminder.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3040202');
                expireReminder.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3040203');
                expireReminder.show = ycoa.SESSION.PERMIT.hasPagePermitButton('3040204');
                expireReminder.countdown = function () {
                    var countdown = expireReminder.countdown;
                    if (isNaN(countdown)) {
                        expireReminder.color_ = "color:red;";
                        return countdown;
                    } else {
                        var day = parseInt(countdown / (60 * 24));
                        var hours = parseInt((countdown % (60 * 24)) / 60);
                        var sec = countdown % 60;
                        if (day <= 10) {
                            expireReminder.color_ = "color:red;";
                        } else {
                            expireReminder.color_ = "";
                        }
                        if (day > 30) {
                            var mouth = parseInt(day / 30);
                            if (mouth >= 12) {
                                return parseInt(mouth / 12) + "年" + ((mouth % 12)) + "个月" + (day % 30) + "天" + hours + "小时" + sec + "分钟";
                            }
                            return mouth + "个月" + (day % 30) + "天" + hours + "小时" + sec + "分钟";
                        }
                        if (day === 0) {
                            return hours + "小时" + sec + "分钟";
                        } else {
                            return day + "天" + hours + "小时" + sec + "分钟";
                        }
                    }
                }();
                self_.expireReminderList.push(expireReminder);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), type: $("#type").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()};
                reLoadData(data);
            }, function (pageNo) {
                var data = {action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), type: $("#type").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()};
                reLoadData(data);
            });
        });
    };
    self_.delExpireReminder = function (expireReminder) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                expireReminder.action = 3;
                ycoa.ajaxLoadPost("/api/domain/expireReminder.php", JSON.stringify(expireReminder), function (result) {
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
    self_.editExpireReminder = function (expireReminder) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + expireReminder.id).show();
        $("#submit_" + expireReminder.id).show();
        $("#cancel_" + expireReminder.id).show();
        if (!$("#form_" + expireReminder.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + expireReminder.id));
        }
    };
    self_.cancelTr = function (expireReminder) {
        $("#tr_" + expireReminder.id).hide();
        $("#submit_" + expireReminder.id).hide();
        $("#cancel_" + expireReminder.id).hide();
    };
    self_.showExpireReminder = function (expireReminder) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + expireReminder.id).show();
        $("#cancel_" + expireReminder.id).show();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        var data = {action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchName: $("#searchUserName").val(), type: $("#type").val()};
        reLoadData(data);
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#type").val("");
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    }, '关键字查找');
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: '服务器', text: '服务器'}, {id: '域名', text: '域名'}, {id: '商务通', text: '商务通'}, {'id': '营销QQ', 'text': '营销QQ'}], function (d) {
        $("#type").val(d.id);
        reLoadData({action: 1, searchName: $("#searchUserName").val(), type: d.id, pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo()});
    }, '按照类别搜索');
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(expireReminderViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_expire_reminder_form").submit();
    });

    $("#btn_search_primary").click(function () {
        var array = getSearchArray();
        if (array['hasVal']) {
            reLoadData(array);
            $("#btn_search_close_primary").click();
        }
    });

    $("#open_dialog_btn").click(function () {
        $("#add_expire_reminder_form input[type='text'],#add_expire_reminder_form input[type='hidden'],#add_expire_reminder_form textarea").each(function () {
            $(this).val("");
        });
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });

    $(".expire_reminder_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        ycoa.ajaxLoadPost("/api/domain/expireReminder.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_expire_reminder_form #type").autoEditSelecter(array["type"]);
    $(".selecter_datepicker").datepicker({autoclose: true});
});
function reLoadData(data) {
    expireReminderViewModel.listExpireReminder(data);
}

var array = {
    type: [{'id': '服务器', 'text': '服务器'}, {'id': '域名', 'text': '域名'}, {'id': '商务通', 'text': '商务通'}, {'id': '营销QQ', 'text': '营销QQ'}]
};

function initEditSeleter(el) {
    $("#type", el).autoEditSelecter(array["type"]);
    $("#dueDate", el).datepicker({autoclose: true});
    el.attr('autoEditSelecter', 'autoEditSelecter');
}