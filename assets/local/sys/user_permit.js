$(window).resize(function () {
    $(".permit_main,.jstree_dept,.rolelist_items,.permitlist_items").height($(window.parent.document).height() - 332);
    $(".portlet-body").height($(window.parent.document).height() - 320);
});
var depts_js_tree_model;
var dept_id;
$(function () {
    $(".permit_main,.jstree_dept,.rolelist_items,.permitlist_items").height($(window.parent.document).height() - 332);
    $(".portlet-body").height($(window.parent.document).height() - 320);
    ycoa.ajaxLoadGet("/api/sys/dept.php", {zh: true}, function (result) {
        $.each(result, function (index, res) {
            res.state = {opened: true};
        });
        depts_js_tree_model = $(".jstree_dept").jstree({
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
            $(".permitlist_items").hide();
            dept_id = data.node.id;
            var array_data = {action: 3};
            if (data.node.parents.length > 1) {//二级菜单点击
                array_data['dept2_id'] = data.node.id;
            } else {//一级菜单点击
                array_data['dept1_id'] = data.node.id;
            }
            ycoa.ajaxLoadPost("/api/sys/cache.php", array_data, function (userArray) {
                var roleHtml = "";
                $.each(userArray, function (i, user) {
                    roleHtml += "<div class='role_text' val='" + user.userid + "'>" + user.username + "</div>";
                });
                roleHtml += "<div class='last'></div>";
                $(".rolelist_items").html(roleHtml);
            });
        }).delegate("a", "click", function (event, data) {
            event.preventDefault();
        });
    });
    $(".rolelist_items .role_text").live("click", function () {
        $(".permitlist_items").show();
        var self_ = $(this);
        $(".rolelist_items .role_text").removeClass("checked");
        self_.addClass("checked");
        var data = {action: 3, userId: self_.attr("val")};
        ycoa.ajaxLoadGet("/api/sys/permit.php", data, function (result) {
            if (result.code == 0) {
                createCtrlsTree(result.list);
            } else {
                ycoa.UI.toast.warning(result.msg);
            }
        });
    });
    $(".permitlist_items .menus_text,.permitlist_items .cltrs_text").live("click", function () {
        $(this).toggleClass("checked");
        if ($(this).hasClass("firstmenu")) {
            if ($(this).hasClass("checked")) {
                $(this).parent("td").next("td").children("table").find("div").addClass("checked");
            } else {
                $(this).parent("td").next("td").children("table").find("div").removeClass("checked");
            }
        }
        if ($(this).hasClass("secondtmenu")) {
            if ($(this).hasClass("checked")) {
                $(this).parent("td").next("td").find("div").addClass("checked");
                $(this).parent("td").parent("tr").parent("tbody").parent("table").parent("td").prev("td").find("div").addClass("checked");
            } else {
                $(this).parent("td").next("td").find("div").removeClass("checked");
            }
        }
        if ($(this).hasClass("button")) {
            if ($(this).hasClass("checked")) {
                $(this).parent("td").prev("td").find("div").addClass("checked");
                $(this).parent("td").parent("tr").parent("tbody").parent("table").parent("td").prev("td").find("div").addClass("checked");
            }
        }
    });
    $("#save_permit_btn").click(function () {
        var userIds = new Array();
        var menuids = new Array();
        var ctrlsids = new Array();
        if (!dept_id) {
            ycoa.UI.toast.warning("操作提示", "你还未进行任何操作,无需提交~");
        } else {
            $(".rolelist_items .checked").each(function () {
                userIds.push($(this).attr("val"));
            });
            $(".permitlist_items .checked").each(function () {
                var ids = $(this).attr("ids");
                if (ids.length != 7) {
                    menuids.push(ids);
                } else {
                    ctrlsids.push(ids);
                }
            });
            var data = {
                'user_ids': userIds.toString(),
                'menu_ids': menuids.toString(),
                'ctrl_ids': ctrlsids.toString(),
                'action': 2
            };
            if (!data.user_ids) {
                ycoa.UI.toast.warning("操作提示~", "请先选择用户后在保存~");
                return;
            }
            ycoa.ajaxLoadPost("/api/sys/permit.php", data, function (result) {
                if (result.code != 0) {
                    ycoa.UI.toast.error("操作提示~", "保存用户权限失败~");
                } else {
                    ycoa.UI.toast.success("操作提示~", "保存用户权限成功~");
                }
            });
        }
    });

    $("#refresh_permit_btn").click(function () {
        var data = {key: '400636D5E1B1217701B4A62C996CB9BB'};
        ycoa.ajaxLoadPost("/backend/create-data-array.php", data, function (result) {
            if (result.states == 'successed') {
                ycoa.UI.toast.success("刷新权限缓存成功~");
            } else {
                ycoa.UI.toast.warning("刷新权限缓存失败~");
            }
        });
    });
});
function createCtrlsTree(results) {
    var permithtml = "<table style='margin: 10px 0px;border-top: solid 1px #f7f7f7;border-bottom: solid 1px #f7f7f7;'>";
    $.each(results, function (index, firstmenu) {
        permithtml += "<tr>";
        permithtml += "<td><div class='menus_text firstmenu ";
        if (firstmenu.state.selected) {
            permithtml += "checked";
        }
        permithtml += "' ids='" + firstmenu.id + "' ><i class='" + firstmenu.icon + "'></i>" + firstmenu.text + "</div></td>";
        permithtml += "<td colspan='2'>";
        permithtml += "<table>";
        $.each(firstmenu.children, function (index, secondtmenu) {
            permithtml += "<tr>";
            permithtml += "<td><div class='menus_text secondtmenu ";
            if (secondtmenu.state.selected) {
                permithtml += "checked";
            }
            permithtml += "' ids='" + secondtmenu.id + "' ><i class='" + secondtmenu.icon + "'></i>" + secondtmenu.text + "</div></td>";
            permithtml += "<td>";
            $.each(secondtmenu.children, function (index, button) {
                permithtml += "<div class='menus_text button ";
                if (button.state.selected) {
                    permithtml += "checked";
                }
                permithtml += "' ids='" + button.id + "' >" + button.text + "</div>";
            });
            permithtml += "</td>";
            permithtml += "</tr>";
        });
        permithtml += "</table>";
        permithtml += "</td>";
        permithtml += "</tr>";
    });
    permithtml += "</table>";
    $(".permitlist_items").html(permithtml);
}