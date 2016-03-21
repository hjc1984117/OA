var CustomerListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.customerList = ko.observableArray([]);
    self_.listCustomer = function (data) {
        ycoa.ajaxLoadGet("/api/second_sale_soft/customer.php", data, function (results) {
            self_.customerList.removeAll();
            $.each(results.list, function (index, customer) {
                customer.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3060502');
                customer.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3060503');
                customer.begin = ycoa.SESSION.PERMIT.hasPagePermitButton('3060506') && ((customer.toplimit > customer.finish) && customer.status == 0);
                customer.end = ycoa.SESSION.PERMIT.hasPagePermitButton('3060506') && ((customer.toplimit > customer.finish) && customer.status != 0);
                customer.qqReception_txt = customer.qqReception == '1' ? '√' : '';
                customer.tmallReception_txt = customer.tmallReception == '1' ? '√' : '';
                self_.customerList.push(customer);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), type: $("#type").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), type: $("#type").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delCustomer = function (customer) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                customer.action = 3;
                ycoa.ajaxLoadPost("/api/second_sale_soft/customer.php", JSON.stringify(customer), function (result) {
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
        customer.action = 2;
        customer.status = customer.status == 1 ? 0 : 1;
        ycoa.ajaxLoadPost("/api/second_sale_soft/customer.php", JSON.stringify(customer), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), type: $("#type").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#type").val("");
    });
    $("#myModal #customer_text,#dataTable #customer_text").live("focus", function (data) {
        var self = this;
        $(this).empSearchAutoComplete(function (data) {
            $(self).parent().find("#customer").val(data.id);
        });
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name, type: $("#type").val()});
    });

    $("#dataTable").searchAutoStatus(array['type'], function (d) {
        reLoadData({action: 1, searchName: $("#searchUserName").val(), type: d.id});
        $("#type").val(d.id);
    }, '按平台筛选');
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $('.timepicker-24').live('mouseover', function () {
        $(this).timepicker({
            autoclose: true,
            minuteStep: 5,
            showMeridian: false
        });
    });
    ko.applyBindings(CustomerListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#add_customer_form #username").live("click", function () {
        if (!$("#add_customer_form #type").val()) {
            ycoa.UI.toast.warning("请先选择所属平台~");
            return;
        }
        var gid = -1;
        switch ($("#add_customer_form #type").val()) {
            case '1':
            case '4':
            case '5':
                gid = 500;
                break;
            case '2':
            case '3':
                gid = 5;
                break;
        }
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [gid]}, function (data, el) {
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
        $("#add_customer_form .auto_radio_li").removeClass("auto_radio_li_checked");
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
        data.qqReception = (data.qqReception) == 'on' ? 1 : data.qqReception;
        data.tmallReception = (data.tmallReception) == 'on' ? 1 : data.tmallReception;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale_soft/customer.php", data, function (result) {
            if (result.code == 0) {
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
        ycoa.ajaxLoadPost("/api/second_sale_soft/customer.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#all_stop_btn").click(function () {
        var data = {action: 41};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale_soft/customer.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#add_customer_form #type").autoRadio(array['type']);

});
function reLoadData(data) {
    CustomerListViewModel.listCustomer(data);
}
var array = {
    type: [{id: 1, text: '平台'}, {id: 2, text: '实物'}, {id: 3, text: '装修'}, {id: 4, text: '代运营'}, {id: 5, text: '班主任'}]
};

function initEditSeleter(el) {
    $("#type", el).autoRadio(array['type']);
    el.attr('autoEditSelecter', 'autoEditSelecter');
}