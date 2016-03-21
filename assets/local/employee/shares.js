var EmployeeListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.employeeList = ko.observableArray([]);
    self_.listEmployee = function (data) {
        ycoa.ajaxLoadGet("/api/user/employee.php", data, function (results) {
            self_.employeeList.removeAll();
            $.each(results.list, function (index, employee) {
                employee.birthday = new Date(employee.birthday).format("yyyy-MM-dd");
                employee.join_time = new Date(employee.join_time).format("yyyy-MM-dd");
                employee.leave_time = new Date(employee.leave_time).format("yyyy-MM-dd");
                var date = new Date(employee.join_time.replace(/-/g, '/'));
                employee.join_days = Math.floor(((Date.parse(new Date()) - date.getTime()) / 3600000) / 24);
                employee.work_age = Math.floor(employee.join_days / 30);
                //employee.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1010304");
                employee.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1010301");
                employee.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1010302");
                self_.employeeList.push(employee);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                ycoa.SESSION.PAGE.setPageSize(pageSize);
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), status: $("#status").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delEmployee = function (employee) {
        EmployeeListViewModel.employeeList.remove(employee);
    };
    self_.editEmployee = function (employee) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + employee.userid).show();
        $("#submit_" + employee.userid).show();
        $("#cancel_" + employee.userid).show();
    };
    self_.cancelTr = function (employee) {
        $("#tr_" + employee.userid).hide();
        $("#submit_" + employee.userid).hide();
        $("#cancel_" + employee.userid).hide();
    };
    self_.showEmployee = function (employee) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + employee.userid).show();
        $("#cancel_" + employee.userid).show();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), status: $("#status").val()});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(EmployeeListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), status: $("#status").val()});
    $(".paging_nation button").live("click", function () {
        if ($(this).val()) {
            reLoadData({action: 1, pageno: $(this).val(), pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), deptid: $('#deptid').val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        }
    });
    $('.reload').click(function () {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), status: $("#status").val()});
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#dropdown-menu_employee_status li").click(function () {
        reLoadData({action: 1, status: $(this).attr("val"), deptid: $('#deptid').val(), pagesize: ycoa.SESSION.PAGE.getPageSize(), ageno: ycoa.SESSION.PAGE.getPageNo(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        $("#status").val($(this).attr('val'));
    });
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    reLoadData({action: 11, deptid: $('#deptid').val(), employeeids: ycoa.UI.checkBoxVal(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
                }
            });
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_employee_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_employee_form input[type='text'],#add_employee_form input[type='hidden'], #add_employee_form textarea").val("");
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
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/user/employee.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
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
    $("#myModal #dept2_text,#dataTable #dept2_text").live("click", function () {
        var self_ = this;
        ycoa.UI.deptDropDownTree($(self_), function (node, el) {
            if (node.parents.length > 1) {
                el.attr("value", node.text);
                el.nextAll().each(function () {
                    switch ($(this).attr("id")) {
                        case 'dept2_id':
                            $(this).attr("value", node.id);
                            break;
                        case 'dept1_id':
                            $(this).attr("value", node.parent);
                            break;
                    }
                });
            }
        });
    });
    $("#dept_selecter").click(function () {
        ycoa.UI.deptDropDownTree($(this), function (node, el) {
            $("#deptid").val(node.id);
            reLoadData({action: 1, status: $('#status').val(), deptid: node.id, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        });
    });
    $('.popovers').popover();
});
function reLoadData(data) {
    EmployeeListViewModel.listEmployee(data);
}