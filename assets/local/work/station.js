var StationListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.stationList = ko.observableArray([]);
    self_.listStation = function (data) {
        ycoa.ajaxLoadGet("/api/work/station.php", data, function (results) {
            self_.stationList.removeAll();
            $.each(results.list, function (index, station) {
                station.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1030202");
                station.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1030203");
                station.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1030204");
                self_.stationList.push(station);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                ycoa.SESSION.PAGE.setPageSize(pageSize);
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delStation = function (station) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                station.action = 3;
                ycoa.ajaxLoadPost("/api/work/station.php", JSON.stringify(station), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData();
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editStation = function (station) {
        $("#myzhiModal #dept1_text").val(station.dept1_text);
        $("#myzhiModal #role_text").val(station.role_text);
        $("#myzhiModal #role_id").val(station.role_id);
        $("#myzhiModal #pur").val(station.pur);
        $("#myzhiModal #station_key1").val(station.station_key1);
        $("#myzhiModal #demand").val(station.demand);
        $("#myzhiModal #exp").val(station.exp);
        $("#myzhiModal #credentials").val(station.credentials);
        $("#myzhiModal #position").val(station.position);
        $("#mychaModalLabel").hide();
        $("#myxiuModalLabel").show();
        $("#btn_submitok_primary").show();
    };
    self_.cancelTr = function (station) {
        $("#tr_" + station.role_id).hide();
        $("#submit_" + station.role_id).hide();
        $("#cancel_" + station.role_id).hide();
    };
    self_.showStation = function (station) {
        $("#myzhiModal #dept1_text").val(station.dept1_text);
        $("#myzhiModal #role_text").val(station.role_text);
        $("#myzhiModal #pur").val(station.pur);
        $("#myzhiModal #station_key1").val(station.station_key1);
        $("#myzhiModal #demand").val(station.demand);
        $("#myzhiModal #exp").val(station.exp);
        $("#myzhiModal #credentials").val(station.credentials);
        $("#myzhiModal #position").val(station.position);
        $("#myxiuModalLabel").hide();
        $("#mychaModalLabel").show();
        $("#btn_submitok_primary").hide();
        $("#myzhiModal input,#myzhiModal textarea,#myzhiModal select").attr("disabled", "");
        
    };
}();
$(function () {
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(StationListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $(".paging_nation button").live("click", function () {
        if ($(this).val()) {
            reLoadData({action: 1, pageno: $(this).val(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        }
    });
    $('.reload').click(function () {
        StationListViewModel.listStation({action: 1, pageno: $('#page_no').val(), status: $("#status").val()});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#dropdown-menu_station_status li").click(function () {
        StationListViewModel.listStation({action: 1, status: $(this).attr("val")});
        $("#status").val($(this).val());
    });
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    StationListViewModel.listStation({action: 11, stationids: ycoa.UI.checkBoxVal(), pageno: $('#page_no').val()});
                }
            });
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_station_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_station_form input,#add_station_form textarea").each(function () {
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
//    $(".dept_submit_btn").live("click", function () {
    $("#btn_submitok_primary").live("click", function () {
        var data = $("#edit_station_form").serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/station.php", data, function (result) {
            if (result.code === 0) {
                $("#btn_close_zhi").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData();
            } else {
                ycoa.UI.toast.error(result.msg);
            }
             ycoa.UI.block.hide();
        });
    });
    $("#role_text").live("click", function () {
        ycoa.UI.roleSeleter({el: $(this), groupId: []}, function (node, el) {
            el.val(node.name);
            el.parent().children("#role_id").val(node.id);
        });
    });


    $("#myModal #dept1_text,#dataTable #dept1_text").live("click", function () {
        var self_ = this;
        ycoa.UI.deptDropDownTree($(self_), function (node, el) {
            el.attr("value", node.text);
            el.parent().find("#dept1_id").val(node.id);
        });
    });
    $('.popovers').popover();
});
function reLoadData(data) {
    StationListViewModel.listStation(data);
}