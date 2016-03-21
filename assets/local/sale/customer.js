var CustomerListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.customerList = ko.observableArray([]);
    self_.listCustomer = function (data) {
        ycoa.ajaxLoadGet("/api/sale/customer.php", data, function (results) {
            self_.customerList.removeAll();
            $.each(results.list, function (index, customer) {
                customer.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2010102");
                customer.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2010103");
                customer.begin = ycoa.SESSION.PERMIT.hasPagePermitButton("2010106") && ((customer.toplimit > customer.finish) && customer.status == 0);
                customer.end = ycoa.SESSION.PERMIT.hasPagePermitButton("2010106") && ((customer.toplimit > customer.finish) && customer.status != 0);
                customer.qqReception_txt = customer.qqReception === 1 ? '√' : '';
                customer.tmallReception_qj_txt = customer.tmallReception_qj === 1 ? '√' : '';
                customer.tmallReception_zy_txt = customer.tmallReception_zy === 1 ? '√' : '';
                customer.cShop_txt = customer.cShop === 1 ? '√' : '';
                customer.ather = function () {
                    var array_ = new Array();
                    if (customer.qqReception === 1) {
                        array_.push('qqReception');
                    }
                    if (customer.tmallReception_qj === 1) {
                        array_.push('tmallReception_qj');
                    }
                    if (customer.tmallReception_zy === 1) {
                        array_.push('tmallReception_zy');
                    }
                    if (customer.cShop === 1) {
                        array_.push('cShop');
                    }
                    return array_.toString();
                }();
                self_.customerList.push(customer);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({
                    action: 1,
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val()
                });
            }, function (pageNo) {
                reLoadData({
                    action: 1,
                    pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val()});
            });
        });
    };
    self_.delCustomer = function (customer) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                customer.action = 3;
                ycoa.ajaxLoadPost("/api/sale/customer.php", JSON.stringify(customer), function (result) {
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
    self_.editCustomer = function (customer) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + customer.id).show();
        $("#submit_" + customer.id).show();
        $("#cancel_" + customer.id).show();
        if (!$("#form_" + customer.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + customer.id));
        }
    };
    self_.cancelTr = function (customer) {
        $("#tr_" + customer.id).hide();
        $("#submit_" + customer.id).hide();
        $("#cancel_" + customer.id).hide();
    };
    self_.updateStatus = function (customer) {
        var status = customer.status == 1 ? 0 : 1;
        ycoa.ajaxLoadPost("/api/sale/customer.php", JSON.stringify({action: 40, status: status, id: customer.id}), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({
                    action: 1,
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val()
                });
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({
            action: 1,
            sort: data.sort, sortname: data.sortname,
            pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(),
            searchName: $("#searchUserName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });
    $("#myModal #customer_text,#dataTable #customer_text").live("focus", function (data) {
        var self = this;
        $(this).empSearchAutoComplete(function (data) {
            $(self).parent().find("#customer").val(data.id);
        });
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
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
    ko.applyBindings(CustomerListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#add_customer_form #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [6]}, function (data, el) {
            el.val(data.name);
            $("#add_customer_form #userid").val(data.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_customer_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_customer_form input,#add_customer_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_customer_form input[type='checkbox']").removeAttr("checked");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#btn_toexcel_primary").live("click", function () {
        var num = ycoa.UI.checkBoxVal();
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (!num)
            num = 0;
        if (!start_time)
            start_time = 0;
        if (!end_time)
            end_time = 0;
        location.href = '/api/sale/excelout.php?num=' + num + '&start_time=' + start_time + '&end_time=' + end_time + '';
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data.qqReception = 0;
        data.tmallReception_qj = 0;
        data.tmallReception_zy = 0;
        data.cShop = 0;
        var ather = (data.ather).split(",");
        if (ather.indexOf("qqReception") !== -1) {
            data.qqReception = 1;
        }
        if (ather.indexOf("tmallReception_qj") !== -1) {
            data.tmallReception_qj = 1;
        }
        if (ather.indexOf("tmallReception_zy") !== -1) {
            data.tmallReception_zy = 1;
        }
        if (ather.indexOf("cShop") !== -1) {
            data.cShop = 1;
        }
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/customer.php", data, function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#action_btn").click(function () {
        var data = {action: 4};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/customer.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#all_stop_btn").click(function () {
        var data = {action: 41};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale/customer.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_customer_form #ather").autoRadio(array['ather']);
});
function reLoadData(data) {
    CustomerListViewModel.listCustomer(data);
}
function initEditSeleter(el) {
    $("#ather", el).autoRadio(array['ather']);
    el.attr('autoEditSelecter', 'autoEditSelecter');
}
var array = {
    ather: [{id: 'qqReception', text: 'QQ接待'}, {id: 'cShop', text: 'C店'}, {id: 'tmallReception_qj', text: '旗舰店'}, {id: 'tmallReception_zy', text: '专营店'}]
};