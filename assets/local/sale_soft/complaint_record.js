var complaintRecordListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.complaintRecordList = ko.observableArray([]);
    self_.listcomplaintRecord = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/complaint_record.php", data, function (results) {
            self_.complaintRecordList.removeAll();
            $.each(results.list, function (index, complaintRecord) {
                complaintRecord.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3031002");
                complaintRecord.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3031003");
                complaintRecord.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3031004");
                self_.complaintRecordList.push(complaintRecord);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchName").val()});
            }, function (pageNo) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchName").val()});
            });
        });
    };
    self_.delComplaintRecord = function (complaintRecord) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                complaintRecord.action = 2;
                ycoa.ajaxLoadPost("/api/sale_soft/complaint_record.php", JSON.stringify(complaintRecord), function (result) {
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
    self_.doEditComplaintRecord = function (complaintRecord) {
        var formid = "form_" + complaintRecord.id;
        var data = $("#" + formid).serializeJson();
        data.action = 3;
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        ycoa.ajaxLoadPost("/api/sale_soft/complaint_record.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editComplaintRecord = function (complaintRecord) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + complaintRecord.id).show();
        $("#submit_" + complaintRecord.id).show();
        $("#cancel_" + complaintRecord.id).show();
        if (!$("#form_" + complaintRecord.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + complaintRecord.id));
        }
        $("#tr_" + complaintRecord.id + " input,#tr_" + complaintRecord.id + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (complaintRecord) {
        $("#tr_" + complaintRecord.id).hide();
        $("#submit_" + complaintRecord.id).hide();
        $("#cancel_" + complaintRecord.id).hide();
    };
    self_.showComplaintRecord = function (complaintRecord) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + complaintRecord.id).show();
        $("#submit_" + complaintRecord.id).hide();
        $("#cancel_" + complaintRecord.id).show();
        if (!$("#form_" + complaintRecord.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + complaintRecord.id));
        }
        $("#tr_" + complaintRecord.id + " input,#tr_" + complaintRecord.id + " textarea").attr("disabled", "");
    };
    self_.showLog = function (complaintRecord) {
        var w_id = complaintRecord.id;
        $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
            $(this).addClass("data_open");
        });
        $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
        $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 12, obj_id: w_id}, function (result) {
            if (result.list.length > 0 && result.list) {
                var html = "<ul>";
                $.each(result.list, function (idnex, d) {
                    html += "<li>[" + d.addtime + " (" + d.username + ")] <br>" + d.changed_desc + "</li>";
                });
                html += "</ul>";
                $("#dataLog_detail_" + w_id).html(html);
            } else {
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/workflowLog_detail_nodata.png'>");
            }
        });
    };
}();
$(function () {
    ko.applyBindings(complaintRecordListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchName').val('');
    });

    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: name});
    }, '按关键字查找', 'searchName');

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale_soft/complaint_record.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
    $("#add_complaint_record_form #shop").autoRadio(array['shop']);

});
function reLoadData(data) {
    data.action = 1;
    complaintRecordListViewModel.listcomplaintRecord(data);
}
function initEditSeleter(el) {
    $("#shop", el).autoRadio(array["shop"]);
    el.attr('autoEditSelecter', 'autoEditSelecter');
}
var array = {
    shop: [{id: '开店软件总经销', text: '开店软件总经销'}, {id: '非凡e品', text: '非凡e品'}, {id: '伤心的rain', text: '伤心的rain'}]
};