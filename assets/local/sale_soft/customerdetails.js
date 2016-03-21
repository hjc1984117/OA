var CustomerdetailsListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.customerdetailsList = ko.observableArray([]);
    self_.listCustomerdetails = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/customerdetails.php", data, function (results) {
            self_.customerdetailsList.removeAll();
            customer_list = results.customer_list;
            $.each(results.list, function (index, customerdetails) {
                customerdetails.date = new Date(customerdetails.date).format("yyyy-MM-dd");
                customerdetails.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3030902");
                customerdetails.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3030903");
                customerdetails.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3030904");
                customerdetails.save = ycoa.SESSION.PERMIT.hasPagePermitButton("3030905");
                customerdetails.is_adminedit = results.is_manager;
                customerdetails.is_refund_txt = customerdetails.is_refund == 1 ? "√" : "";
                customerdetails.is_two_sales_txt = customerdetails.is_two_sales == 1 ? "√" : "";
                customerdetails.color_ = customerdetails.is_receive == 0 ? "color:#F00;" : "";
                self_.customerdetailsList.push(customerdetails);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var search_data = {action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize};
                reLoadData(concat(search_data, getSearchArray()));
            }, function (pageNo) {
                var search_data = {action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize()};
                reLoadData(concat(search_data, getSearchArray()));
            });
        });
    };
    self_.delCustomerdetails = function (customerdetails) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                customerdetails.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/customerdetails.php", JSON.stringify(customerdetails), function (result) {
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
    self_.editCustomerdetails = function (customerdetails) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + customerdetails.id).show();
        if (customerdetails.edit_qq == 1) {
            $("#tr_" + customerdetails.id + " #qq").attr("readonly", "");
        }
        if (customerdetails.remark) {
            $("#tr_" + customerdetails.id + " #remark").attr("readonly", "");
        }
        $("#submit_" + customerdetails.id).show();
        $("#cancel_" + customerdetails.id).show();
    };
    self_.cancelTr = function (customerdetails) {
        $("#tr_" + customerdetails.id).hide();
        $("#submit_" + customerdetails.id).hide();
        $("#cancel_" + customerdetails.id).hide();
    };
    self_.showCustomerdetails = function (customerdetails) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + customerdetails.id).show();
        $("#submit_" + customerdetails.id).hide();
        $("#cancel_" + customerdetails.id).show();
        $("#tr_" + customerdetails.id + " input,#tr_" + customerdetails.id + " textarea").attr("disabled", "");
    };
    self_.saveCustomerdetails = function (customerdetails) {
        var el = $("#uptr_" + customerdetails.id);
        var data = {id: customerdetails.id};
        $("select", el).each(function () {
            data[$(this).attr("id")] = $(this).val();
        });
        updateCD(data);
    };
}();
$(function () {
    ko.applyBindings(CustomerdetailsListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        var search_data = {action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), status: $("#status").val()};
        reLoadData(concat(search_data, getSearchArray()));
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });

    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $("#dataTable").searchDateTime(function (name) {
        reLoadData({action: 1, searchTime: name});
    }, '按照日期查询');
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    $("#search_customerdetails_form #payment").autoEditSelecter(array["payment"]);
//    $("#search_customerdetails_form #is_receive").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_reviews").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_addto_reviews").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_add_qq").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_added").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_refund").autoRadio(array["bool"]);
//    $("#search_customerdetails_form #is_two_sales").autoRadio(array["bool"]);

    $("#search_customerdetails_form #is_receive").autoRadio(array["is_receive"]);
//    $("#search_customerdetails_form #is_reviews").autoRadio(array["is_reviews"]);
//    $("#search_customerdetails_form #is_addto_reviews").autoRadio(array["is_addto_reviews"]);
//    $("#search_customerdetails_form #is_add_qq").autoRadio(array["is_add_qq"]);
    $("#search_customerdetails_form #is_added").autoRadio(array["is_added"]);
    $("#search_customerdetails_form #is_refund").autoRadio(array["is_refund"]);
    $("#search_customerdetails_form #is_two_sales").autoRadio(array["is_two_sales"]);

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[class='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[class='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_search_primary").click(function () {
        var array = getSearchArray();
        if (array['hasVal']) {
            reLoadData(array);
            $("#btn_search_close_primary").click();
        }
    });
    $("#customerdetails").click(function () {
        $("#add_customerdetails_form input[type='text']").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale_soft/customerdetails.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }

    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/customerdetails.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#dataTable").on("change", ".table_checkbox", function () {
        var self = $(this);
        self.parent().parent().find(".saveCustomerdetails").show();
        switch (self.attr("id")) {
            case 'is_receive':
                if (self.val() == '0') {
                    self.parent().parent().css('color', '#F00');
                } else {
                    self.parent().parent().css('color', '');
                }
                break;
            case 'is_added':
                if (self.val() == '1') {
                    self.parent().parent().css('color', '');
                    self.parent().parent().find("#is_receive").val(1);
                }
                break;
        }
    });
    $(".selecter_datepicker").datepicker({autoclose: true});
});
function reLoadData(data) {
    data.action = 1;
    CustomerdetailsListViewModel.listCustomerdetails(data);
}
var array = {
    payment: [{id: '工行', text: '工行'}, {id: '农行', text: '农行'}, {id: '建行', text: '建行'}, {id: '中行', text: '中行'}, {id: '邮政', text: '邮政'}, {id: '信用社', text: '信用社'}, {id: '支付宝', text: '支付宝'}, {id: '财付通', text: '财付通'}, {id: '微信', text: '微信'}, {id: '淘宝', text: '淘宝'}, {id: '拍拍', text: '拍拍'}, {id: '花呗', text: '花呗'}],
    bool: [{'id': '1', 'text': '是'}, {'id': '0', 'text': '否'}],
    is_receive: [{'id': '1', 'text': '是否收货评价'}],
    is_reviews: [{'id': '1', 'text': '评价'}],
    is_addto_reviews: [{'id': '1', 'text': '已评'}],
    is_add_qq: [{'id': '1', 'text': '可加'}],
    is_added: [{'id': '1', 'text': '已加'}],
    is_refund: [{'id': '1', 'text': '退款'}],
    is_two_sales: [{'id': '1', 'text': '二销'}]

}
function getSearchArray() {
    var array = {action: 1, hasVal: false};
    $("#search_customerdetails_form input").each(function () {
        if ($(this).val()) {
            array[$(this).attr("name")] = $(this).val();
            array['hasVal'] = true;
        }
    });
    return array;
}

function  concat(a, b) {
    if (a && b) {
        var c = JSON.stringify(a).replace("}", ",") + (JSON.stringify(b).replace("{", ""));
        return $.parseJSON(c);
    }
    return {};
}

function updateCD(customerdetails) {
    customerdetails.action = 2;
    ycoa.ajaxLoadPost("/api/sale_soft/customerdetails.php", JSON.stringify(customerdetails), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}
