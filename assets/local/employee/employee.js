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
                if (employee.leave_time) {
                    employee.leave_time = new Date(employee.leave_time).format("yyyy-MM-dd")
                }
                var date = new Date(employee.join_time.replace(/-/g, '/'));
                employee.join_days = Math.floor(((Date.parse(new Date()) - date.getTime()) / 3600000) / 24);
                employee.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1010102");
                employee.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1010103");
                employee.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1010104");
                employee.reSetPassword = ycoa.SESSION.PERMIT.hasPagePermitButton("1010105");

                employee.edita = ycoa.SESSION.PERMIT.hasPagePermitButton("1010301");
                employee.showa = ycoa.SESSION.PERMIT.hasPagePermitButton("1010302");

                employee.deleq = ycoa.SESSION.PERMIT.hasPagePermitButton("1010402");
                employee.editq = ycoa.SESSION.PERMIT.hasPagePermitButton("1010403");
                self_.employeeList.push(employee);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, deptid: $('#searchDeptName').val(), mouth: $('#searchMouth').val(), status: $("#status").val(), searchName: $("#searchAutoStatus").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#searchDeptName').val(), mouth: $('#searchMouth').val(), status: $("#status").val(), searchName: $("#searchAutoStatus").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delEmployee = function (employee) {
        ycoa.UI.messageBox.confirm("确定要删除该条员工信息吗?删除后不可恢复~", function (btn) {
            if (btn) {
                employee.action = 5;
                ycoa.ajaxLoadPost("/api/user/employee.php", JSON.stringify(employee), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        ycoa.ajaxLoadPost("/backend/create-data-array.php", {key: '400636D5E1B1217701B4A62C996CB9BB'}, function () {
                        });
                        reLoadData({});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.editEmployee = function (employee) {
        if ($("#tr_" + employee.userid).css("display") == "none") {
            $(".second_tr").hide();
            $(".submit_btn").hide();
            $(".cancel_btn").hide();
            $("#tr_" + employee.userid).show();
            $("#submit_" + employee.userid).show();
            $("#cancel_" + employee.userid).show();
            $("#tr_" + employee.userid).attr("t", "edit");
        } else {
            if ($("#tr_" + employee.userid).attr("t") == "edit") {
                self_.cancelTr(employee);
            } else {
                $(".submit_btn").hide();
                $(".cancel_btn").hide();
                $("#submit_" + employee.userid).show();
                $("#cancel_" + employee.userid).show();
                $("#tr_" + employee.userid).attr("t", "edit");
            }
        }
        $("#tr_" + employee.userid + " input,#tr_" + employee.userid + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (employee) {
        $("#tr_" + employee.userid).hide();
        $("#submit_" + employee.userid).hide();
        $("#cancel_" + employee.userid).hide();
        $("#tr_" + employee.userid).removeAttr("t");
    };
    self_.showEmployee = function (employee) {
        if ($("#tr_" + employee.userid).css("display") == "none") {
            $(".second_tr").hide();
            $(".submit_btn").hide();
            $(".cancel_btn").hide();
            $("#tr_" + employee.userid).show();
            $("#cancel_" + employee.userid).show();
            $("#tr_" + employee.userid).attr("t", "show");
        } else {
            if ($("#tr_" + employee.userid).attr("t") == "show") {
                self_.cancelTr(employee);
            } else {
                $(".submit_btn").hide();
                $(".cancel_btn").hide();
                $("#tr_" + employee.userid).show();
                $("#cancel_" + employee.userid).show();
                $("#tr_" + employee.userid).attr("t", "show");
            }
        }
        $("#tr_" + employee.userid + " input,#tr_" + employee.userid + " textarea").attr("disabled", "");
    };
    self_.reSetPassword = function (employee) {
        ycoa.UI.messageBox.confirm("确定要将该员工的登陆密码重置吗?重置后的密码为随机密码,请及时修改~", function (btn) {
            if (btn) {
                employee.action = 6;
                ycoa.ajaxLoadPost("/api/user/employee.php", JSON.stringify(employee), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.messageBox.alert(result.msg);
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
    $("#dataTable").sort(function (data) {
        reLoadData({deptid: $('#searchAutoStatus').val(), mouth: $('#searchMouth').val(), status: $('#searchAutoStatus').val(), searchName: $('#searchUserName').val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: data.sort, sortname: data.sortname});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $('#searchMouth').val('');
        $('#searchUserName').val('');
        $('#searchDeptName').val('');
        $('#searchAutoStatus').val('');
    });
    if (getAction() != 4) {
        $("#dataTable").searchAutoStatus([{id: 1, text: '正式'}, {id: 2, text: '试用'}, {id: 4, text: '停薪留职'}, {id: 0, text: '全部'}], function (d) {
            reLoadData({deptid: $('#searchAutoStatus').val(), mouth: $('#searchMouth').val(), status: d.id, searchName: $('#searchUserName').val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
        }, '员工状态');
    }
    $("#dataTable").searchDept(function (id) {
        reLoadData({deptid: id, mouth: $('#searchMouth').val(), status: $('#searchAutoStatus').val(), searchName: $('#searchUserName').val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({deptid: $('#searchAutoStatus').val(), mouth: $('#searchMouth').val(), status: $('#searchAutoStatus').val(), searchName: name, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
    });
    if (getAction() == 1) {
        $("#dataTable").searchAutoStatus([{id: 1, text: '一月'}, {id: 2, text: '二月'}, {id: 3, text: '三月'}, {id: 4, text: '四月'}, {id: 5, text: '五月'}, {id: 6, text: '六月'}, {id: 7, text: '七月'}, {id: 8, text: '八月'}, {id: 9, text: '九月'}, {id: 10, text: '十月'}, {id: 11, text: '十一月'}, {id: 12, text: '十二月'}],
                function (d) {
                    reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#searchAutoStatus').val(), mouth: d.id, status: $('#searchAutoStatus').val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
                }, '按生日月份查询', 'searchMouth');
    }
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        var self = this;
        $(self).datepicker({autoclose: true}, function (d) {
            if ($(self).attr('id') == 'birthday') {
                var date = new Date(d.replace(/-/g, "/")).getTime();
                var currentDate = new Date();
                currentDate = new Date(currentDate.format("yyyy-MM-dd").replace(/-/g, "/")).getTime();
                var age = (currentDate - date) / 1000 / (3600 * 24) / 365;
                if ($(self).attr('t') == 1) {
                    $(self).parent('div').parent('div').parent('div').find('#age').val(Math.floor(age));
                } else {
                    $(self).parent('td').parent('tr').parent('tbody').find('#age').val(Math.floor(age));
                }
            }
        });
    });
    ko.applyBindings(EmployeeListViewModel, $("#dataTable")[0]);
    reLoadData({status: 0});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    var data = {
                        employeeids: ycoa.UI.checkBoxVal(),
                        action: 3
                    };
                    ycoa.ajaxLoadPost("/api/user/employee.php", JSON.stringify(data), function (result) {
                        if (result.code == 0) {
                            ycoa.UI.toast.success(result.msg);
                            ycoa.ajaxLoadPost("/backend/create-data-array.php", {key: '400636D5E1B1217701B4A62C996CB9BB'}, function () {
                            });
                            reLoadData({});
                        } else {
                            ycoa.UI.toast.error(result.msg);
                        }
                        ycoa.UI.block.hide();
                    });
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
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            var self_ = $(this);
            if (self_.attr("placeholder") && self_.attr('name') != 'dept1_text' && self_.attr('name') != 'role_text') {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        work_kv.push('"sex":"性别"');
        work_kv.push('"status":" 状态"');
        work_kv.push('"remark":" 备注"');
        work_kv.push('"dept1_id":" 部门"');
        work_kv.push('"role_id":" 职位"');
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/user/employee.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                ycoa.ajaxLoadPost("/backend/create-data-array.php", {key: '400636D5E1B1217701B4A62C996CB9BB'}, function () {
                });
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#role_text").live("click", function () {
        var deptId = $("#dept1_id", $(this).parent().parent()).val();
        if (deptId) {
            var g = new Array();
            g.push(deptId);
            ycoa.UI.roleSeleter({el: $(this), groupId: g}, function (node, el) {
                el.val(node.name);
                el.parent().children("#role_id").val(node.id);
            });
        } else {
            ycoa.UI.toast.warning("请先选择部门,再选择岗位~");
        }
    });
    $("#myModal #dept1_text,#dataTable #dept1_text").live("click", function () {
        var self_ = this;
        ycoa.UI.deptDropDownTree($(self_), function (node, el) {
            el.attr("value", node.text);
            el.parent().find("#dept2_id").val(node.id);
            if (node.parent == "#") {
                el.parent().find("#dept1_id").val(node.id);
            } else {
                el.parent().find("#dept1_id").val(node.parent);
            }
        });
    });
    $("#reLoadEmployeeShraes").click(function () {
        ycoa.ajaxLoadPost("/api/user/employee.php", {action: 4}, function (result) {
            if (result.code == 0) {
                $("#btn_close_primary").click();
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
        });
    });

    $(".data_list_div").live("mouseenter", function () {
        var self_ = $(this);
        self_.data('lastEnter', new Date().getTime());
        setTimeout(function () {
            var t1 = new Date().getTime(), t2 = self_.data('lastEnter');
            if (t2 > 0 && t1 - t2 >= 500) {
                var w_id = self_.attr("val");
                $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
                    $(this).addClass("data_open");
                });
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
                $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 1, obj_id: w_id}, function (result) {
                    if (result.list.length > 0) {
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
            }
        }, 500);
    }).live('mouseleave', function () {
        $(this).data('lastEnter', 0);
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
    $("#outexcel").click(function () {
        window.location.href = "/api/user/employee.php?action=11&type=1";
    });
    $("#outexcel_phone").click(function () {
        window.location.href = "/api/user/employee.php?action=11&type=2";
    });
    $("#outexcel_ex2").click(function () {
        window.location.href = "/api/user/employee.php?action=11&type=3";
    });
    $('.popovers').popover();
});
function reLoadData(data) {
    data.action = getAction();
    EmployeeListViewModel.listEmployee(data);
}

function getAction() {
    var pathname = window.location.pathname;
    if (pathname == "/page/employee/employee_list.html") {
        return 1;
    } else if (pathname == "/page/employee/phone_list.html") {
        return 2;
    } else if (pathname == "/page/employee/shares_list.html") {
        return 3;
    } else if (pathname == "/page/employee/quit_list.html") {
        return 4;
    }
}