$(window).resize(function () {
    $(".permit_main,.jstree_dept,.rolelist_items,.permitlist_items").height($(window.parent.document).height() - 332);
    $(".portlet-body").height($(window.parent.document).height() - 320);
});
var role_array;
$(function () {
    $(".permit_main,.jstree_dept,.rolelist_items,.permitlist_items").height($(window.parent.document).height() - 332);
    $(".portlet-body").height($(window.parent.document).height() - 320);
    ycoa.ajaxLoadGet("/api/sys/dept.php", {zh: true}, function (result) {
        $.each(result, function (index, res) {
            res.state = {opened: true};
        });
        $(".jstree_dept").jstree({
            'core': {
                "themes": {"responsive": false},
                'data': result
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            }
        }).bind("select_node.jstree", function (event, data) {
            var mdata;
            if (data.node.parents.length > 1) {//二级菜单点击
                mdata = {'dept2_id': data.node.id, action: 2};
            } else {//一级菜单点击
                mdata = {'dept1_id': data.node.id, action: 2};
                $("#dept_table #parent_text").val("");
            }
            ycoa.ajaxLoadPost("/api/sys/cache.php", mdata, function (roleArray) {
                role_array = roleArray;
                var roleHtml = "";
                $.each(roleArray, function (num, role) {
                    roleHtml += "<div class='role_text' shares='" + role.shares + "' val='" + role.id + "'>" + role.text + "</div>";
                });
                roleHtml += "<div class='last'></div>";
                $(".rolelist_items").html(roleHtml);
            });

            $("#dept_text").val(data.node.text);
            $("#dept_table #dept_id").val(data.node.id);
            $("#dept_table #parent_id").val(data.node.parent == "#" ? 0 : data.node.parent);
            $.each(result, function (index, res) {
                if (res.children.length > 0) {
                    $.each(res.children, function (cIndex, cres) {
                        if ((data.node.id) == (cres.id)) {
                            $("#dept_table #parent_text").val(res.text);
                            return;
                        }
                    });
                }
            });
            $("#dept_table").show();
            $("#role_table").hide();
            $(".role_submit_btn").hide();
            $(".dept_submit_btn").hide();
        });
    });
    $("#dept_table #parent_text").click(function () {
        ycoa.UI.deptDropDownTree($(this), function (node, el) {
            if (node.parents.length == 1) {
                el.val(node.text);
                el.parent(".input-group").parent("td").find("#parent_id").val(node.id);
            } else {
                ycoa.UI.toast.warning("系统暂不支持三级部门的创建~", "操作提示");
            }
        }, false, {hasZH: true});
    });
    $("#role_table #dept1_text").click(function () {
        ycoa.UI.deptDropDownTree($(this), function (node, el) {
            el.val(node.text);
            if (node.parents.length > 1) {
                $("#role_table #dept2_id").val(node.id);
                $("#role_table #dept1_id").val(node.parent);
            } else {
                $("#role_table #dept2_id").val(node.id);
                $("#role_table #dept1_id").val(node.id);
            }
        }, false, {hasZH: true});
    });

    $(".rolelist_items .role_text").live("click", function () {
        var self = this;
        $(".rolelist_items .checked").removeClass("checked");
        $(this).addClass("checked");
        $.each(role_array, function (index, role) {
            if (role.id == $(self).attr("val")) {
                $("#role_table #dept1_text").val(role.dept1_text);
                $("#role_table #dept1_id").val(role.dept1_id);
                $("#role_table #dept2_id").val(role.dept2_id);
                $("#role_table #role_id").val(role.id);
                $("#role_table #role_text").val(role.text);
                $("#role_table #shares").val(role.shares);
                return;
            }
        });
        $("#dept_table").hide();
        $("#role_table").show();
        $(".role_submit_btn").hide();
        $(".dept_submit_btn").hide();
    });
//    $("#dept_table #clearDept").click(function () {
//        $("#dept_table #parent_text").val("");
//        $("#dept_table #parent_id").val("");
//    });
    $("#dept_table #clearDeptName").click(function () {
        $("#dept_table #dept_text").val("");
    });
    $("#role_table #clearDept").click(function () {
        $("#role_table #dept1_text").val("");
        $("#role_table #dept1_id").val("");
        $("#role_table #dept2_id").val("");
    });
    $("#role_table #clearRoleName").click(function () {
        $("#role_table #role_id").val("");
        $("#role_table #role_text").val("");
    });
    $("#role_table #clearShares").click(function () {
        $("#role_table #shares").val("");
    });
    $("#add_dept_btn").click(function () {
        $("#dept_table input").val("");
        $("#dept_table").show();
        $("#role_table").hide();
        $(".role_submit_btn").hide();
        $(".dept_submit_btn").show();
    });
    $("#add_role_btn").click(function () {
        $("#role_table input").val("");
        $("#dept_table").hide();
        $("#role_table").show();
        $(".role_submit_btn").show();
        $(".dept_submit_btn").hide();
    });
    $("#dept_table .dept_submit_btn").click(function () {
        var data = {
            deptid: $("#dept_table #dept_id").val(),
            deptname: $("#dept_table #dept_text").val(),
            parentid: $("#dept_table #parent_id").val(),
            action: 1
        };
        ycoa.ajaxLoadPost("/api/sys/dept.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                ycoa.ajaxLoadPost("/backend/create-data-array.php", {key: '400636D5E1B1217701B4A62C996CB9BB'}, function () {
                });
            } else {
                ycoa.UI.toast.error(result.msg);
            }
        });
    });
    $("#role_table .role_submit_btn").click(function () {
        var data = {
            dept1_id: $("#role_table #dept1_id").val(),
            dept2_id: 0,
            role_id: $("#role_table #role_id").val(),
            role_text: $("#role_table #role_text").val(),
            shares: $("#role_table #shares").val()
        };
        if (isNaN(data.shares)) {
            ycoa.UI.toast.warning("岗位股份只能为数字哦~");
        } else {
            ycoa.ajaxLoadPost("/api/sys/role.php", data, function (result) {
                if (result.code == 0) {
                    ycoa.UI.toast.success(result.msg);
                    ycoa.ajaxLoadPost("/backend/create-data-array.php", {key: '400636D5E1B1217701B4A62C996CB9BB'}, function () {
                    });
                } else {
                    ycoa.UI.toast.error(result.msg);
                }
            });
        }
    });
});