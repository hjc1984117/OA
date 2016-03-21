var canCreateTabBar = true;
var toolBar_left_ = 0;
var navbar_isopen = true;
var canOpenMenu = true;
var permitMenus = new Array();
$(function () {
    ko.applyBindings(MenuListViewModel, $(".div_main_left_menu")[0]);
    MenuListViewModel.listMenu();
    Layout.autoLayout();
    Layout.autoEvent();
});
var Layout = new function () {
    var self = this;
    self.autoLayout = function () {
        $('body').css('overflow', 'hidden');
        var width = $(window).width();
        var height = $(window).height();
        if (width < 1700) {
            width = 1700;
        }
        if (height < 800) {
            height = 800;
        }
        $(".div_layout_pc_main_outer").width(width).height(height);
        $(".div_main_page_outer").width(width).height(height - 110);
        if (navbar_isopen) {
            $(".toolBarTitle").width(width - 230 - 45);
            $(".div_div_navbar_right").width(width - 230);
            $(".div_main_right_frame_page").width(width - 230);
        } else {
            $(".toolBarTitle").width(width - 80);
            $(".div_div_navbar_right").width(width - 50);
            $(".div_main_right_frame_page").width(width);
        }
        $(".div_main_right_frame_page").height(height - 110);
        $(".menu_outer_list").height(height - 110);
        $('body').css('overflow', 'auto');
        $(".slimScrollDiv").height(height - 110);
    };
    self.autoEvent = function () {
        $(".menu_outer_list").slimscroll({height: ($(window).height() - 110) + 'px', width: '230px', color: '#1f1f1f'});
        $('.modal').modal({backdrop: false, keyboard: false, show: false});
        $(".div_div_navbar_left_btn").click(function () {
            var self_el = $(this);
            var width = $(window).width();
            if (navbar_isopen) {
                $(".div_main_left_menu,.search_menu_div").animate({'width': '0px'});
                $(".div_main_right_frame_page").animate({'width': width + 'px'});
                self_el.attr('title', '打开菜单');
                $(".slimScrollDiv").animate({"z-index": -1});
                $(".div_div_navbar_left").animate({'width': '50px'});
                self_el.animate({"margin": "5px 10px"});
                $(".div_div_navbar_right").animate({'width': (width - 50) + 'px'});
                $(".toolBarTitle").animate({'width': width - 100});
                $(".mouse_open_close").show().animate({'width': '35px'});
                navbar_isopen = false;
            } else {
                $(".div_main_left_menu,.search_menu_div").animate({'width': '230px'});
                $(".div_main_right_frame_page").animate({'width': (width - 230) + 'px'});
                self_el.attr('title', '关闭菜单');
                $(".slimScrollDiv").animate({"z-index": 0, 'width': '230px'});
                $(".div_div_navbar_left").animate({'width': '230px'});
                self_el.animate({"margin": "5px 20px"});
                $(".div_div_navbar_right").animate({'width': (width - 230) + 'px'});
                $(".toolBarTitle").animate({'width': width - 280});
                $(".mouse_open_close").animate({'width': '5px'}, function () {
                    $(this).hide();
                });
                navbar_isopen = true;
            }
        });
        $(".mouse_open_close").mouseenter(function () {
            $(".mouse_open_close").addClass("enter").animate({'width': '5px'}, function () {
                $(this).hide();
                $(".slimScrollDiv").css({'z-index': '1'}).animate({'width': '230px'});
            });
        });
        $(".slimScrollDiv").mouseleave(function () {
            if ($(".mouse_open_close").hasClass("enter") && $(".slimScrollDiv").css('z-index') !== '0') {
                $(".slimScrollDiv").animate({'width': '0px'}, function () {
                    $(this).css({'z-index': '-1'});
                    $(".mouse_open_close").removeClass("enter").show().animate({'width': '35px'});
                });
            }
        });
        $(".toolBar-ul").on("click", ".toolBar-name", function () {
            if (!$(this).parent("li").hasClass("toolbar-current")) {
                var index_ = $(this).parent("li").attr("id").replace("tab_", "");
                var pageContentWrapper = $(".div_main_right_frame_page");
                pageContentWrapper.children("iframe").hide();
                pageContentWrapper.children("#iframe_" + index_).show();
                $(".toolbar-default").removeClass("toolbar-current");
                $(".toolbar-close").hide(300)
                $(this).parent("li").addClass("toolbar-current");
                if ($(this).next(".toolbar-close")) {
                    $(this).next(".toolbar-close").show();
                }
            }
        });
        $(".toolBar-ul").on("dblclick", ".toolBar-name", function () {
            if (!$(this).parent("li").hasClass("toolbar-first")) {
                $(this).next(".toolbar-close").click();
            }
        });
        $(".toolBar-ul").on("click", ".toolbar-close", function () {
            $(this).animate({'opacity': 0});
            $(".toolbar-default").removeClass("toolbar-current");
            var index_ = $(this).parent("li").attr("id").replace("tab_", "");
            var pageContentWrapper = $(".div_main_right_frame_page");
            var currentIframe = pageContentWrapper.children("#iframe_" + index_);
            $(this).parent("li").animate({width: '0px'}, function () {
                if ($(this).next("li").attr("class")) {
                    $(this).next("li").addClass("toolbar-current");
                    $(this).next("li").find(".toolbar-close").show();
                    currentIframe.next("iframe").show();
                    currentIframe.remove();
                } else {
                    $(this).prev("li").addClass("toolbar-current");
                    $(this).prev("li").find(".toolbar-close").show();
                    currentIframe.prev("iframe").show();
                    currentIframe.remove();
                }
                $(this).remove();
            });
            var toolBarUl = $(".toolBar-ul");
            var toolBarTitle = $(".toolBarTitle");
            var maxNum = parseInt((toolBarTitle.width()) / 100);
            var currentNum = toolBarUl.children("li").length;
            if (maxNum <= currentNum) {
                var left = parseInt(toolBarUl.css("left")) * -1;
                var maxLeft = parseInt((toolBarUl.children("li").length) - (parseInt((toolBarTitle.width()) / 100))) * 100;
                if (left != maxLeft) {
                    if (toolBar_left_ != 0) {
                        $(".toolBar-ul").animate({left: (toolBar_left_ + 100) + 'px'}, function () {
                            toolBar_left_ += 100;
                        });
                    }
                }
            } else {
                $(".toolbar-pre-btn").hide();
                $(".toolbar-next-btn").hide();
            }
        });
        $(".toolbar-pre-btn").click(function () {
            var toolBar = $(".toolBar-ul");
            var toolBarTitle = $(".toolBarTitle");
            var maxNum = parseInt((toolBarTitle.width()) / 100);
            var currentNum = toolBar.children("li").length;
            if ((currentNum - maxNum) > 0) {
                var left = parseInt(toolBar.css("left")) * -1;
                var maxLeft = parseInt(currentNum - maxNum) * 100;
                if (left != maxLeft) {
                    toolBar.animate({left: (toolBar_left_ - 100) + 'px'}, function () {
                        toolBar_left_ -= 100;
                    });
                }
            }
        });
        $(".toolbar-next-btn").click(function () {
            if ($(".toolBar-ul").css("left") < "0px") {
                $(".toolBar-ul").animate({left: (toolBar_left_ + 100) + 'px'}, function () {
                    toolBar_left_ += 100;
                });
            }
        });
        $(".div_user_info_bar").click(function () {
            $(".div_user_info_menu_list").show();
        });
        $(".div_user_info_menu_list>ul").mouseleave(function () {
            $(".div_user_info_menu_list").hide();
        });
        $(".loginout").click(function () {
            ycoa.cookie.clearAll();
            localStorage.setItem("loginError", "你已经成功退出系统~");
            window.location = __host__ + "/page/login.html";
        });
        $("#btn_submit_primary").click(function () {
            var data = $("#change_pwd_form").serializeJson();
            if (data.old_pwd == "" || data.old_pwd.length < 6) {
                ycoa.UI.toast.warning("请输入长度大于6位数的原始密码~");
            } else if (data.new_pwd == "" || data.new_pwd.length < 6) {
                ycoa.UI.toast.warning("请输入长度大于6位数的新密码~");
            } else if (data.re_pwd == "" || data.re_pwd.length < 6) {
                ycoa.UI.toast.warning("请输入长度大于6位数的新密码~");
            } else {
                if (data.new_pwd == data.re_pwd) {
                    data.userid = ycoa.user.userid();
                    data.old_pwd = data.old_pwd.MD5();
                    data.new_pwd = data.new_pwd.MD5();
                    data.action = 4;
                    ycoa.ajaxLoadPost("/api/user/user.php", JSON.stringify(data), function (results) {
                        if (results.code != 0) {
                            ycoa.UI.toast.warning(results.msg);
                        } else {
                            ycoa.UI.toast.success(results.msg);
                            setTimeout("ycoa.logout()", 3000);
                        }
                    });
                } else {
                    ycoa.UI.toast.warning("两次输入的密码不匹配,请重新输入~");
                }
            }
        });
        $(".div_user_info_username").html(ycoa.user.username());
        $(".div_user_info_username").attr('title', ycoa.user.username());
        $(".div_user_info_avatar>img").attr('src', ycoa.user.avatar());
        $(".search_btn").click(function () {
            $(".search-in").each(function () {
                $(this).removeClass("search-in");
            });
            if ($(this).hasClass("searchOpen")) {
                var searchVal = $(".search_input").val();
                if (searchVal) {
                    var menuAyrray1 = new Array();
                    var menuAyrray2 = new Array();
                    $.each(permitMenus, function (index, menu) {
                        if ((menu.text).indexOf(searchVal) !== -1) {
                            menuAyrray1.push(menu.id);
                        }
                        if ((menu.childs).length > 0) {
                            $.each(menu.childs, function (i, m) {
                                if ((m.text).indexOf(searchVal) !== -1) {
                                    menuAyrray2.push(m.id);
                                }
                            });
                        }
                    });
                    var menuAyrray = menuAyrray1.concat(menuAyrray2);
                    if (menuAyrray.length > 0) {
                        $.each(menuAyrray, function (z, d) {
                            if ((d.toString()).length === 3) {
                                var par_node = $("#menu_first_item_" + d).find(".menu_first_item_content");
                                if (!par_node.parent(".menu_first_item").hasClass("menu_open")) {
                                    par_node.click();
                                }
                                $("#menu_first_item_" + d).addClass("search-in");
                            } else if ((d.toString()).length === 5) {
                                var par_node = $("#jump_" + d).parent(".menu_second_item").parent(".menu_inside_list").parent(".menu_first_item").find(".menu_first_item_content");
                                if (!par_node.parent(".menu_first_item").hasClass("menu_open")) {
                                    par_node.click();
                                }
                                $("#jump_" + d).parent(".menu_second_item").addClass("search-in");
                            }
                        });
                    } else {
                        ycoa.UI.toast.warning("暂未搜索到合适的菜单项~");
                    }
                } else {
                    $(".div_div_navbar_left").click();
                }
            } else {
                $(this).addClass("searchOpen");
                $(".search_input").animate({width: '180px', opacity: '1'}, function () {
                    $(this).css('padding', '0px 5px');
                    $(this).focus();
                });
                $(this).css({background: '#cecece', color: '#4d4d4d'});
            }
        });
        $(".search_input").keypress(function (e) {
            if (e.keyCode === 13) {
                $(".search_btn").click();
            }
        });
        $(document).bind("click", function (e) {
            var target = $(e.target);
            if (target.closest(".search_menu_input_div").length === 0) {
                $(".search_btn").removeClass("searchOpen");
                $(".search_input").css('padding', '0px');
                $(".search_input").animate({width: '0px', opacity: '0'});
                $(".search_btn").css({background: '', color: ''});
                $(".search_input").val("");
            }
        });
    };
    self.createTab = function (currentFix, tabName, url) {
        if (canCreateTabBar) {
            var toolBarUl = $(".toolBar-ul");
            var toolBarTitle = $(".toolBarTitle");
            var pageContentWrapper = $(".div_main_right_frame_page");
            $(".toolbar-default").removeClass("toolbar-current");
            $(".toolbar-close").hide();
            toolBarUl.append("<li id='tab_" + currentFix + "' class='toolbar-default toolbar-current' style='width:100px;opacity:0;'><div class='toolBar-name' unselectable='on' onselectstart='return false;'>" + tabName + "</div><div class='toolbar-close'>X</div></li>");
            pageContentWrapper.append("<iframe src='" + url + "' id = 'iframe_" + currentFix + "' style='width: 100%;height: 100%;margin: 0px;padding: 0px;display:none;' allowtransparency='true' frameborder='0' scrolling='auto'></iframe>");
            var maxNum = parseInt((toolBarTitle.width()) / 100);
            var currentNum = toolBarUl.children("li").length;
            if ((maxNum - currentNum) < 0) {
                $(".toolbar-pre-btn").show();
                $(".toolbar-next-btn").show();
                toolBarUl.animate({left: (toolBar_left_ - 100) + 'px'}, function () {
                    toolBar_left_ -= 100;
                });
            }
            $("#tab_" + currentFix).animate({'opacity': '1'}, function () {
                $(this).children(".toolbar-close").show();
                pageContentWrapper.children("iframe").hide();
                $("#iframe_" + currentFix).show();
            });
            canCreateTabBar = false;
            var intervalCreateTabBarClick = window.setInterval(function () {
                window.clearInterval(intervalCreateTabBarClick);
                canCreateTabBar = true;
            }, 600);
        }
    }
};
var MenuListViewModel = new function () {
    var self_ = this;
    self_.mode = ko.observable("list");
    self_.menuList = ko.observableArray([]);
    self_.listMenu = function () {
        var ts = new Date().getTime();
        ycoa.ajaxLoadGet('/api/sys/menus.php', {}, function (result) {
            self_.menuList.push({disabled: '', href: "", icon: "", id: '', parent_id: '', sort: '', text: "", childs: []});
            $.each(result.permitMenus, function (index, menu) {
                permitMenus.push(menu);
                if (menu.childs) {
                    $.each(menu.childs, function (index, m) {
                        m.href = m.href + "?ts=" + ts;
                    });
                }
                self_.menuList.push(menu);
            });
            ycoa.SESSION.PERMIT.setPermitButtons(result.permitButtons);
            jumpPage("j");
        });
    };
    self_.openFirstMenu = function (menu) {
        $("#menu_inside_list_" + menu.id).removeClass("search-in");
        $(".menu_open > .menu_inside_list").hide(function () {
            $(this).prev(".menu_first_item_content").find(".arrow ").removeClass("fa-angle-down").addClass("fa-angle-left");
            $(this).parent(".menu_first_item").removeClass("menu_open");
        });
        if (!$("#menu_first_item_" + menu.id).hasClass("menu_open") && canOpenMenu) {
            canOpenMenu = false;
            $("#menu_inside_list_" + menu.id).show(function () {
                $(this).prev(".menu_first_item_content").find(".arrow").removeClass("fa-angle-left").addClass("fa-angle-down");
                $(this).parent(".menu_first_item").addClass("menu_open");
                canOpenMenu = true;
            });
        }
    };
    self_.openSecondMenu = function (menu) {
        $("div[num='" + (menu.id) + "']").parent(".menu_second_item").removeClass("search-in");
        if (menu.href) {
            var currentFix = menu.id;
            var tabli = $("#tab_" + currentFix);
            if (tabli.attr("class")) {
                $(".toolbar-default").removeClass("toolbar-current");
                $(".toolbar-close").hide();
                $("#tab_" + currentFix).children(".toolbar-close").show();
                $("#tab_" + currentFix).addClass("toolbar-current");
                $("#tab_" + currentFix).show();
                $(".div_main_right_frame_page").children("iframe").hide();
                $("#iframe_" + currentFix).show();
            } else {
                Layout.createTab(currentFix, menu.text, menu.href);
            }
        }
    };
}();
$(window).resize(function () {
    console.log("亲,不要没事拖着玩咯^_^");
    Layout.autoLayout();
    window.frames[0].set_index_swf_size();
});
function jumpParentPage(code) {
    if (code) {
        var find_a = $("#jump_" + code);
        find_a.parent("li").parent(".menu_inside_list").parent(".menu_first_item").find(".menu_first_item_content").click();
        find_a.click();
    }
}
function jumpPage(name) {
    var jumpids = ycoa.util.getQueryString(name);
    if (jumpids) {
        jumpids = base64decode(jumpids);
        jumpids = jumpids.split(",");
        if (jumpids.length === 1) {
            var find_a = $("#jump_" + jumpids);
            find_a.parent("li").parent(".menu_inside_list").parent(".menu_first_item").find(".menu_first_item_content").click();
            find_a.click();
        } else {
            var juid = 0;
            var myInterval = setInterval(function () {
                var find_a = $("#jump_" + jumpids[juid]);
                find_a.click();
                juid = juid + 1;
                if (juid == jumpids.length) {
                    window.clearInterval(myInterval);
                }
            }, 800);
        }
    }
}