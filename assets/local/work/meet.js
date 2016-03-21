var MeetListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.meetList = ko.observableArray([]);
    self_.listMeet = function (data) {
        ycoa.ajaxLoadGet("/api/work/meet.php", data, function (results) {
            self_.meetList.removeAll();
            $.each(results.list, function (index, meet) {
                meet.date = new Date(meet.date).format("yyyy-MM-dd");
                meet.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('1030602');
                meet.show = ycoa.SESSION.PERMIT.hasPagePermitButton('1030603');
                meet.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('1030604');
                self_.meetList.push(meet);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delMeet = function (meet) {
        MeetListViewModel.meetList.remove(meet);
    };
    self_.editMeet = function (meet) {
        $('#edit_meet_form #id').val(meet.id);
        $('#edit_meet_form #meet_class').val(meet.meet_class);
        $('#edit_meet_form #meet_title').val(meet.meet_title);
        $('#edit_meet_form #meet_content').val(meet.meet_content);
//        $(".second_tr").hide();
//        $("#tr_" + meet.id).show();
//        $("#submit_" + meet.id).show();
//        $("#cancel_" + meet.id).show();
//        $("#myzhiModal #meet_contents")
    };
    self_.showMeet = function (meet) {
        $("#myzhiModal #meet_contents").val(meet.meet_content);
        $("#cancel_" + meet.id).show();
    };
    self_.deleMeet = function (meet) {
        ycoa.UI.messageBox.confirm("确定要删除该条会议记录吗?删除后不可恢复~", function (btn) {
            if (btn) {
                meet.action = 5;
                ycoa.ajaxLoadPost("/api/work/meet.php", JSON.stringify(meet), function (result) {
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
}();
$(function () {
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
    });
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    }, "会议主题");
    ko.applyBindings(MeetListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $('.reload').click(function () {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo()});
    });
    
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    
    $("#myModal #btn_submit_primary").click(function () {
        $("#add_meet_form").submit();
    });
    $("#myEditModal #btn_submit_primary").click(function () {
        $("#edit_meet_form").submit();
    });

    $("#open_dialog_btn").click(function () {
        $("#add_meet_form input,#add_meet_form textarea").each(function () {
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
    });


    $(".form_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.id = $(this).attr("val");
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/meet.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#add_meet_form #meet_class_").change(function () {
        if ($(this).val() != "其他") {
            $("#add_meet_form #meet_class").val($(this).val());
            $("#add_meet_form #meet_class").attr("readonly", "");
        } else {
            $("#add_meet_form #meet_class").removeAttr("readonly");
            $("#add_meet_form #meet_class").val("");
        }
    });

    $('#myzhiModal #meet_contents').xheditor({tools: '', skin: 'nostyle', html5Upload: false, width: '300', height: '500'});
    $('#myModal #meet_content').xheditor({tools: 'Cut,Copy,Paste,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|Hr,Table,|,Source,Preview', skin: 'nostyle', html5Upload: false, width: '300', height: '350'});
    $('#myEditModal #meet_content').xheditor({tools: 'Cut,Copy,Paste,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|Hr,Table,|,Source,Preview', skin: 'nostyle', html5Upload: false, width: '300', height: '350'});
    $('.popovers').popover();
});
function reLoadData(data) {
    MeetListViewModel.listMeet(data);
}