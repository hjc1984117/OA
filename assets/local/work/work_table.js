var year_selecter = 0;
var isCreate = false;
var weekDatNum = 0;
var current_cell, current_mouseenter, cus_cell_select_array = {}, direction, change_cell = {}, paste_board = {};

$(function () {
    init({action: 1});
    bindMenuEvent();
    $.get(ycoa.getNoCacheUrl("/api/attendance/attrclasses_list.php"), {action: 3}, function (result) {
        var html = "<ul>";
        $.each(result.list, function (index, d) {
            html += "<li class='menu_item' v=" + d.text + ">" + d.text + "<span>" + d.desc + "</span></li>";
        });
        html += "<li class='menu_item' v='0'>0<span>休</span></li>";
        html += "</ul>";
        $(".classz_ltems_panle_context").html(html);
        if (ycoa.SESSION.PERMIT.hasPagePermitButton("1030503")) {
            createExcelEvent();
        }
    });
    $("#btn_submit_primary").click(function () {
        ycoa.UI.messageBox.confirm("请确保本次录入的数据是一个月排班的完整数据,一旦录入将不提供数据修改~", function (btn) {
            if (btn) {
                var array = {};
                array['userid'] = ycoa.user.userid();
                array['dept_id'] = ycoa.user.dept1_id();
                array['action'] = 1;
                $('.add_work_table_form .work_select,.add_work_table_form textarea,.add_work_table_form input,.add_work_table_form .auto_input').each(function () {
                    if ($(this).attr("name") != 'remark') {
                        if ($(this).attr("name") == 'can_id') {
                            if ($(this).val()) {
                                array['id'] = $(this).val();
                            }
                        } else {
                            array[$(this).attr("name")] = $(this).attr('v');
                        }
                    } else {
                        array[$(this).attr("name")] = $(this).val();
                    }
                });
                var jsonStr = JSON.stringify(array);
                ycoa.ajaxLoadPost('/api/work/work_table.php', jsonStr, function (result) {
                    if (result.code == 0) {
                        $("#btn_close_primary").click();
                        ycoa.UI.toast.success(result.msg);
                        init({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    });
    $("#btn_submit_primary_day").click(function () {
        ycoa.UI.messageBox.confirm("请确保本次录入的数据是一整天用餐的完整数据,一旦录入将不提供数据修改~", function (btn) {
            if (btn) {
                var data = $("#add_work_day_form").serializeJson();
                data.dept_id = ycoa.user.dept1_id();
                data.action = 2;
                data = JSON.stringify(data);
                ycoa.ajaxLoadPost("/api/work/work_table.php", data, function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        init({action: 1});
                    } else {
                        ycoa.UI.toast.warning(result.msg);
                    }
                });
            }
        });
    });
    $("#open_dialog_btn").click(function () {
        $(".add_work_table_form #add_date").val("");
        ycoa.ajaxLoadGet("/api/work/work_table.php", {action: 2}, function (result) {
            $(".add_work_table_form #add_date").attr('v', result.current_date);
            var data = result.list;
            if (data) {
                $(".add_work_table_form #can_id").val(data['id']);
                $(".add_work_table_form #remark").val(data['remark']);
                $(".add_work_table_form .work_select").each(function () {
                    var self_ = $(this);
                    var value = data[self_.attr("name")];
                    self_.attr('v', value);
                    switch (value) {
                        case 'A':
                            self_.html("A");
                            self_.addClass("wdefault");
                            break;
                        case 'B':
                            self_.html("B");
                            self_.addClass("wdefault");
                            break;
                        case 'A_T':
                            self_.html("A(测)");
                            self_.addClass("wtest");
                            break;
                        case 'B_T':
                            self_.html("B(测)");
                            self_.addClass("wtest");
                            break;
                        case '休':
                            self_.html("休");
                            self_.addClass("wx");
                            break;
                        case '节':
                            self_.html("节假日");
                            self_.addClass("wj");
                            break;
                    }
                });
            } else {
                $(".add_work_table_form #can_id").val("");
                $(".add_work_table_form #remark").val("");
                $(".add_work_table_form .work_select").each(function () {
                    var self_ = $(this);
                    self_.attr('v', "");
                    self_.html("");
                });
            }
        });
    });
    $("#dataTable").reLoad(function () {
        init({action: 1});
        $("#searchTime").val("");
        $("#dept_id").val("");
    });
    if ([1, 2].indexOf(ycoa.user.dept1_id()) != -1) {
        $("#dataTable").searchDept(function (d) {
            init({action: 1, dept_id: d, searchTime: $("#searchTime").val()});
            $("#dept_id").val(d);
        });
    }
    $("#dataTable").searchAutoStatus(array['mouth'], function (d) {
        var date = (year_selecter == 0 ? '2015' : year_selecter) + "-" + d.id;
        init({action: 1, searchTime: date, dept_id: $("#dept_id").val()});
        $("#searchTime").val(date);
    }, '月份');
    $("#secelt_button button").click(function () {
        var html = $(this).html();
        var v = $(this).attr("v");
        var c = $(this).attr("c");
        var sun = $('#sun').is(':checked');
        var sta = $('#sta').is(':checked');
        $(".add_work_table_form .work_select").each(function () {
            $(this).attr('v', '');
            $(this).html('');
            $(this).attr('class', 'work_select');
            var w = $(this).attr("w");
            if (w == 'week_0') {
                if (sun) {
                    $(this).attr('v', v);
                    $(this).html(html);
                    $(this).attr('class', 'work_select ' + c);
                }
            } else if (w == 'week_6') {
                if (sta) {
                    $(this).attr('v', v);
                    $(this).html(html);
                    $(this).attr('class', 'work_select ' + c);
                }
            } else {
                $(this).attr('v', v);
                $(this).html(html);
                $(this).attr('class', 'work_select ' + c);
            }
        });
    });
    $(".prve_mouth").click(function () {
        var currentDate = $(".add_work_table_title").html();
        currentDate = currentDate.replace("年", "-");
        currentDate = currentDate.replace("月", "-01");
        var currentDate = currentDate.split('-');
        var year = currentDate[0];
        var mouth = currentDate[1];
        if (parseInt(mouth) == 1) {
            year = parseInt(year) - 1;
            mouth = 12;
        } else {
            mouth = parseInt(mouth) - 1;
        }
        var change_date = new Date(year, mouth, 0);
        var maxDay = change_date.getDate();
        change_date.setDate(1);
        var curWeek = change_date.getDay();
        $(".add_work_table_title").html(year + "年" + mouth + "月");
        $(".add_work_table_form #add_date").attr("v", year + "-" + mouth + "-01");
        createAddWorkTableFormHtml(maxDay, curWeek);
        $(".next_mouth").show();
        $(".prve_mouth").hide();
    });
    $(".next_mouth").click(function () {
        var currentDate = $(".add_work_table_title").html();
        currentDate = currentDate.replace("年", "-");
        currentDate = currentDate.replace("月", "-01");
        var currentDate = currentDate.split('-');
        var year = currentDate[0];
        var mouth = currentDate[1];
        if (parseInt(mouth) == 12) {
            year = parseInt(year) + 1;
            mouth = 1;
        } else {
            mouth = parseInt(mouth) + 1;
        }
        var change_date = new Date(year, mouth, 0);
        var maxDay = change_date.getDate();
        change_date.setDate(1);
        var curWeek = change_date.getDay();
        $(".add_work_table_title").html(year + "年" + mouth + "月");
        $(".add_work_table_form #add_date").attr("v", year + "-" + mouth + "-01");
        createAddWorkTableFormHtml(maxDay, curWeek);
        $(".next_mouth").hide();
        $(".prve_mouth").show();
    });
    $(window.document).scroll(function () {
        var scrolltop = $(document).scrollTop();
        if (scrolltop >= 200) {
            $("#fixed_table_title").css("width", $("#dataTable").css('width')).html("<thead>" + $("#dataTable thead").html() + "</thead>");
            $("#fixed_table_title").show();
        } else {
            $("#fixed_table_title").html("").hide();
        }
    });
    $(".classz_ltems_panle_title_btn").click(function () {
        if ($(this).hasClass("fa-chevron-down")) {
            $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            $(".classz_ltems_panle_context").show();
        } else {
            $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            $(".classz_ltems_panle_context").hide();
        }
    });
    $(".classz_ltems_panle_outer").draggable({handle: ".classz_ltems_panle_title_name"});
});
/**
 * 星期转换
 * @param {type} str
 * @returns {data|getUpercaseNum.data}
 */
function getUpercaseNum(str) {
    var data = {'1': '一', '2': '二', '3': '三', '4': '四', '5': '五', '6': '六', '0': '日'};
    return data[str];
}

/**
 * 获取后台数据
 * @param {type} data
 * @returns {undefined}
 */
function init(data) {
    ycoa.ajaxLoadGet("/api/work/work_table.php", data, function (results) {
        var currentDate = (results.currentDate).split('-');
        var c_d = (results.c_d).split('-');
        var week = results.currentWeek;
        var curWeek = parseInt(results.currentWeek);
        $('.table_title').html(results.title);
        $('.add_work_table_title').html(parseInt(c_d[0]) + "年" + parseInt(c_d[1]) + "月");
        var maxDay = new Date(currentDate[0], currentDate[1], 0).getDate();
        createWeekListHtml(week);
        createTableDateHtml(maxDay);
        createTableContextHtml(results, maxDay);
        createAddWorkTableFormHtml(maxDay, curWeek);
        for (var j = 1; j <= (results.current_year - results.start_year); j++) {
            array['year'].push({id: (results.start_year + j), text: (results.start_year + j) + '年'});
        }
        if (!isCreate) {
            $("#dataTable").searchAutoStatus(array['year'], function (d) {
                year_selecter = d.id;
            }, '年份');
            isCreate = true;
        }
    });
}

/**
 * 绑定添加 排班计划 下拉菜单事件
 * @returns {undefined}
 */
function bindMenuEvent() {
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $('.work_select').live('click', function () {
        var self_ = $(this);
        var X = self_.offset().top;
        var Y = self_.offset().left;
        $('.select_menu_work').css({top: X + 40, left: Y + 10});
        $('.select_menu_work li').unbind('click');
        $('.select_menu_work li').bind('click', function () {
            self_.attr('v', $(this).attr('v'));
            self_.text($(this).text());
            self_.removeAttr("class");
            self_.attr('class', 'work_select ' + $(this).attr('c'));
            $('.select_menu_work').hide();
        });
        $('.select_menu_work').show();
    });
    $(".week_title").live('click', function () {
        var self_ = $(this);
        var X = self_.offset().top;
        var Y = self_.offset().left;
        $('.select_menu_work').css({top: X + 40, left: Y + 10});
        $('.select_menu_work li').unbind('click');
        $('.select_menu_work li').bind('click', function () {
            $(".add_work_table_form [w='week_" + self_.attr('v') + "']").attr('v', $(this).attr('v'));
            $(".add_work_table_form [w='week_" + self_.attr('v') + "']").html($(this).html());
            $(".add_work_table_form [w='week_" + self_.attr('v') + "']").attr('class', 'work_select ' + $(this).attr('c'));
            $('.select_menu_work').hide();
        });
        $('.select_menu_work').show();
    });
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (!target.hasClass('work_select') && !target.hasClass('week_title')) {
            if (target.closest(".select_menu_work").length == 0) {
                $(".select_menu_work").hide();
            }
        }
        if (target.closest(".cus_cells_contextmenu").length == 0) {
            $(".cus_cells_contextmenu").hide();
        }
        if (target.closest(".cus_cells_copy_paste").length == 0) {
            $(".cus_cells_copy_paste").hide();
        }
    });
    $("#excel_work_inport").change(function () {
        ycoa.UI.block.show();
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        var file = fileToUpload[0].files[0];
        if ('xlsx'.indexOf(hz.toLowerCase()) > -1) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var xhr = new XMLHttpRequest();
                var fd = new FormData();
                fd.append("action", 2);
                fd.append('work_table', file);
                xhr.open('POST', ycoa.getNoCacheUrl('/api/work/work_table.php'), true);
                xhr.onload = function () {
                    var data = $.parseJSON(xhr.responseText);
                    if (data.code == 0) {
                        ycoa.UI.toast.success("文件上传成功,后台正在解析,请稍后~");
                    } else {
                        ycoa.UI.toast.warning(data.msg)
                    }
                    ycoa.UI.block.hide();
                };
                xhr.send(fd);
            };
            reader.readAsDataURL(file);
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning("上传文件格式不正确,请检查后重试~");
        }
    });
    $("#btn_toexcel_primary").click(function () {
        location.href = '/api/work/work_table.php?action=11';
    });
}

/**
 * 根据后台时期动态生成数据中展示表格星期列头
 * @param {type} week
 * @returns {undefined}
 */
function createWeekListHtml(week) {
    var table_week_html;
    for (var j = 0; j <= 30; j++) {
        switch (week) {
            case 0:
            case 6:
                table_week_html += "<td class='weekend'>" + getUpercaseNum(week) + "</td>";
                break;
            default:
                table_week_html += "<td>" + getUpercaseNum(week) + "</td>";
                break
        }
        week++;
        if (week > 6) {
            week = 0;
        }
    }
    $(".table_week").html(table_week_html);
}

/**
 * 根据后台时期动态生成数据中展示表格时期列头
 * @param {type} maxDay
 * @returns {undefined}
 */
function createTableDateHtml(maxDay) {
    var table_date_html;
    var autoDate = 0;
    for (var i = 0; i <= 32; i++) {
        if (i == 0) {
            table_date_html += "<td class='td_first' rowspan='2'>姓名\\日期</td>";
        } else if (i == 32) {
            table_date_html += "<td  class='td_last' rowspan='2'>备注</td>";
        } else {
            if (i <= maxDay) {
                table_date_html += "<td class='td_month'>" + i + "</td>";
            } else {
                table_date_html += "<td class='td_month'>" + (++autoDate) + "</td>";
            }
        }
    }
    $(".table_date").html(table_date_html);
}

/**
 * 动态生成 排班计划展示列表
 * @param {type} results
 * @param {type} maxDay
 * @returns {undefined}
 */
function createTableContextHtml(results, maxDay) {
    if (results.list.length == 0) {
        $(".table_context").html("");
    } else {
        var table_context_html;
        $.each(results.list, function (index, d) {
            table_context_html += "<tr date='" + d['date'] + "' did='" + d['id'] + "' uid='" + d['userid'] + "'>";
            table_context_html += "<td class='w_user_name'>" + d['name'] + "</td>";
            for (var i = 1; i <= 31; i++) {
                var val = (ycoa.util.isEmpty(d["m" + i]) ? "" : d["m" + i]);
                if (i < maxDay + 1) {
                    table_context_html += "<td id=" + (i + "_" + index) + " i=" + index + " m='" + i + "' class='w_value'>" + val + "</td>";
                } else {
                    table_context_html += "<td id=" + (i + "_" + index) + " i=" + index + " m='" + i + "' class='w_value'></td>";
                }
            }
            table_context_html += "<td class='remark w_value'>" + (ycoa.util.isEmpty(d['remark']) ? "" : d['remark']) + "</td>";
            table_context_html += "</tr>";
        });
        $(".table_context").html(table_context_html);
    }
}

/*
 * 模拟Excel事件
 * @returns {undefined}
 */
function createExcelEvent() {
    $(".w_value").live("dblclick", function () {
        if ($(this).hasClass("remark")) {
            $(".contenteditable").removeClass("contenteditable").removeAttr("contenteditable");
            $(this).addClass("contenteditable").attr("contenteditable", true).focus();
        }
    }).live("click", function (e) {
        if (!$(this).hasClass("contenteditable") && $(this).hasClass("remark")) {
            $(".contenteditable").removeClass("contenteditable").removeAttr("contenteditable");
        }
        $(".cus_cell_label").html("").hide();
    }).live("change", function () {
        setCusCell($(this));
    }).live("mousedown", function (e) {
        if (e.which === 3) {
            var x = e.pageX;
            var y = e.pageY;
            var height = $(window).height();
            var width = $(window).width();
            y = height - y > 380 ? y : y - 380;
            x = width - x > 222 ? x : x - 222;
            $(".cus_cells_contextmenu").css({top: y, left: x}).show();
        } else {
            if (!e.ctrlKey) {
                $(".cus_cell_select").removeClass("cus_cell_select");
            }
            $(this).addClass("cus_cell_select");
            setCusCell($(this));
        }
    });
    $(".w_user_name").live("mousedown", function (e) {
        if (e.which === 3) {
            var x = e.pageX;
            var y = e.pageY;
            $(".cus_cells_copy_paste").attr('d', $(this).parent("tr").attr('uid')).css({top: y, left: x}).show();
        }
    });
    $(".table_context").on("keyup", ".w_value", function (e) {
        setCusCell($(this));
    });
//    CTRL + S 保存修改
    $(document).keydown(function (e) {
        if (e.keyCode === 83 && e.ctrlKey) {
            if (JSON.stringify(change_cell) !== "{}") {
                $.each(change_cell, function (i, d) {
                    var that = $("#dataTable tr[uid=" + i + "]");
                    var uid = that.attr("uid");
                    var id = that.attr("did");
                    var date = that.attr("date");
                    var data_array = {id: id, date: date, userid: uid};
                    $("#dataTable tr[uid=" + i + "] td").each(function (index, d) {
                        if (index > 0 && index < 32) {
                            data_array['m' + $(this).attr('m')] = $(this).html();
                        }
                        if (index === 32) {
                            data_array['remark'] = $(this).html();
                        }
                    });
                    change_cell[i] = data_array;
                });
                ycoa.ajaxLoadPost("/api/work/work_table.php", {action: 3, json_data: JSON.stringify(change_cell)}, function (res) {
                    if (res.code == 0) {
                        change_cell = {};
                    }
                    ycoa.UI.toast.info(res.msg);
                });
            } else {
                ycoa.UI.toast.warning("表格未作任何修改~");
            }
            return false;
        }
    });
    $(".cus_cell_point,.w_value").live("mousedown", function (e) {
        current_mouseenter = null;
        var ctrlKeyDownArray = null;
        $(".w_value").live("mouseenter", function () {
            current_mouseenter = $(this);
        }).live("mouseleave", function () {
            current_mouseenter = null;
        });
        if (!$(this).hasClass("w_value")) {
            cus_cell_select_array = set_cus_cell_select_array();
        }
        var d = document;
        var that = $(this);
        var page = {
            event: function (evt) {
                var ev = evt || window.event;
                return ev;
            },
            pageX: function (evt) {
                var e = this.event(evt);
                return e.pageX || (e.clientX + document.body.scrollLeft - document.body.clientLeft);
            },
            pageY: function (evt) {
                var e = this.event(evt);
                return e.pageY || (e.clientY + document.body.scrollTop - document.body.clientTop);
            },
            layerX: function (evt) {
                var e = this.event(evt);
                return e.layerX || e.offsetX;
            },
            layerY: function (evt) {
                var e = this.event(evt);
                return e.layerY || e.offsetY;
            }
        }
        if (that.setCapture) {
            that.setCapture();
        } else if (window.captureEvents) {
            window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
        }
        d.onmousemove = function (e) {
            if (current_cell != null && current_mouseenter != null && e.which === 1) {
                ctrlKeyDownArray = change_current_cell_selecter(e, !that.hasClass("w_value"));
            }
        };
        d.onmouseup = function () {
            if (that.releaseCapture) {
                that.releaseCapture();
            } else if (window.releaseEvents) {
                window.releaseEvents(Event.MOUSEMOVE | Event.MOUSEUP);
            }
            d.onmousemove = null;
            d.onmouseup = null;
            $(".w_value").die("mouseenter").die("mouseleave");
            if (ctrlKeyDownArray !== null) {
                ctrlKeyDownCopy(e, ctrlKeyDownArray, that);
            }
        };
    });
    create_contextmenu_item();
}
/*
 * 设置单元格选中之后的边框
 * @param {type} el
 * @returns {undefined}
 */
function setCusCell(el) {
    var left = el.offset().left;
    var top = el.offset().top;
    var height = parseInt(el.css('height').replace("px", ""));
    var width = parseInt(el.css('width').replace("px", ""));
    $(".cus_cells").show();
    $(".cus_cell_top").stop(true, true).animate({top: top, left: left, width: (width - 1)}, 200);
    $(".cus_cell_bottom").stop(true, true).animate({top: (top + height - 1), left: left, width: (width - 4)}, 200);
    $(".cus_cell_left").stop(true, true).animate({top: top, left: left, height: height}, 200);
    $(".cus_cell_right").stop(true, true).animate({top: top, left: (left + width - 1), height: (height - 4)}, 200);
    $(".cus_cell_point").stop(true, true).animate({top: (top + height - 3), left: (left + width - 3)}, 200);
    el.addClass("cus_cell_select");
    $(".cus_cells").css("border", "solid 1px #338E5B");
    current_cell = el;
}

function ctrlKeyDownCopy(e, dataArray, el) {
    var current = dataArray.current;
    var mouse = dataArray.mouse;
    var offset = (cus_cell_select_array['offset'] === undefined) ? {m: 0, i: 0} : cus_cell_select_array['offset'];
    var max_x = offset.m;
    var max_y = offset.i;
    var offset_x = offset.x_num;
    var offset_y = offset.y_num;
    $(".cus_cell_select").removeClass("cus_cell_select");
    for (var i = mouse.i; i <= current.i; i++) {
        for (var j = mouse.m; j <= current.m; j++) {
            if (!el.hasClass("w_value") && e.ctrlKey) {
                var str = cus_cell_select_array[create_new_xy(max_x, offset_x, j) + "_" + create_new_xy(max_y, offset_y, i)];
                $("#" + j + "_" + i).addClass("cus_cell_select").html(str);
            } else {
                $("#" + j + "_" + i).addClass("cus_cell_select");
            }
        }
    }
    var cus_cell_select = $(".cus_cell_select");
    cus_cell_select.each(function () {
        var uid = $(this).parent("tr").attr("uid");
        change_cell[uid] = true;
    });
    $(".cus_cell_label").html("").hide();
}

function set_cus_cell_select_array() {
    var array = {};
    var size = $(".cus_cell_select").size();
    $(".cus_cell_select").each(function (index, el) {
        if (index == 0) {
            array['offset'] = {i: 0, m: 0, x_num: parseInt($(el).attr("m")), y_num: parseInt($(el).attr("i"))};
            array['mouse'] = {x: parseInt($(el).attr("m")), y: parseInt($(el).attr("i"))};
        }
        if (index == (size - 1)) {
            var x_num = Math.abs(parseInt($(el).attr("m")) - array['offset']['x_num']) + 1;
            var y_num = Math.abs(parseInt($(el).attr("i")) - array['offset']['y_num']) + 1;
            array['offset'] = {i: parseInt($(el).attr("i")), m: parseInt($(el).attr("m")), x_num: x_num, y_num: y_num};
            array['current'] = {x: parseInt($(el).attr("m")), y: parseInt($(el).attr("i"))};
        }
        array[$(el).attr("m") + "_" + $(el).attr("i")] = $(el).html();
    });
    return array;
}

function set_paste_array() {
    var array = {};
    var offset_x = 0;
    var offset_y = 0;
    var size = $(".cus_cell_select").size();
    $(".cus_cell_select").each(function (index, el) {
        var this_x = parseInt($(el).attr("m"));
        var this_y = parseInt($(el).attr("i"));
        if (index == 0) {
            offset_x = this_x;
            offset_y = this_y;
            array['offset'] = {x: this_x, y: this_y, x_num: this_x, y_num: this_y};
            array['mouse'] = {x: this_x, y: this_y};
        }
        if (index == (size - 1)) {
            var x_num = Math.abs(this_x - array['offset']['x_num']) + 1;
            var y_num = Math.abs(this_y - array['offset']['y_num']) + 1;
            array['offset'] = {x: array['offset']['x'], y: array['offset']['y'], x_num: x_num, y_num: y_num};
            array['current'] = {x: this_x, y: this_y};
        }
        array[(this_x - offset_x) + "_" + (this_y - offset_y)] = $(el).html();
    });
    return array;
}

function create_new_xy(maxnum, offset, num) {
    while (num > maxnum) {
        num = num - offset;
    }
    return num;
}

function change_current_cell_selecter(e, showLabel) {
    var curren_cell_month = parseInt(current_cell.attr("m"));
    var current_mouseenter_month = parseInt(current_mouseenter.attr("m"));
    var curren_cell_index = parseInt(current_cell.attr("i"));
    var current_mouseenter_index = parseInt(current_mouseenter.attr("i"));
    var height = parseInt(current_mouseenter.css('height').replace("px", ""));
    var width = parseInt(current_mouseenter.css('width').replace("px", ""));
    var top = current_mouseenter.offset().top;
    var left = current_mouseenter.offset().left;
    var current_top = current_cell.offset().top;
    var current_left = current_cell.offset().left;
    if (current_cell.html() && e.ctrlKey && showLabel) {
        $(".cus_cell_label").css({top: current_mouseenter.offset().top + current_mouseenter.height() + 10, left: current_mouseenter.offset().left + 5}).show().html(current_cell.html());
    }
    if ((curren_cell_month === current_mouseenter_month) && (curren_cell_index !== current_mouseenter_index)) {//上下移动
        var abs = Math.abs(curren_cell_index - current_mouseenter_index);
        if ((curren_cell_index - current_mouseenter_index) > 0) {//上
            $(".cus_cell_top").stop(true, true).animate({top: top, left: left, width: width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: (current_top + height - 1), left: current_left, width: width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: top, left: left, height: (abs + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: top, left: left + width - 1, height: (abs + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: (current_top + height - 3), left: (current_left + width - 3)}, 200);
            direction = "上";
            return{mouse: {i: current_mouseenter_index, m: current_mouseenter_month}, current: {i: curren_cell_index, m: curren_cell_month}};
        } else {//向下
            $(".cus_cell_top").stop(true, true).animate({top: current_top, left: current_left, width: width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: (top + height - 1), left: left, width: width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: current_top, left: current_left, height: (abs + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: current_top, left: current_left + width - 1, height: (abs + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: top + height - 3, left: left + width - 3}, 200);
            direction = "下";
            return {mouse: {i: curren_cell_index, m: current_mouseenter_month}, current: {i: current_mouseenter_index, m: curren_cell_month}};
        }
    } else if ((curren_cell_month !== current_mouseenter_month) && (curren_cell_index === current_mouseenter_index)) {//左右移动
        var abs = Math.abs(curren_cell_month - current_mouseenter_month);
        if ((curren_cell_month - current_mouseenter_month) > 0) {//左
            $(".cus_cell_top").stop(true, true).animate({top: top, left: left, width: (abs + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: top + height - 1, left: left, width: (abs + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: top, left: left, height: height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: current_top, left: (current_left + width - 1), height: height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: current_top + height - 3, left: current_left + width - 3, }, 200);
            direction = "左";
            return  {mouse: {i: current_mouseenter_index, m: current_mouseenter_month}, current: {i: curren_cell_index, m: curren_cell_month}};
        } else {//右
            $(".cus_cell_top").stop(true, true).animate({top: current_top, left: current_left, width: (abs + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: current_top + height - 1, left: current_left, width: (abs + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: current_top, left: current_left, height: height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: top, left: (left + width - 1), height: height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: top + height - 3, left: left + width - 3, }, 200);
            direction = "右";
            return {mouse: {i: current_mouseenter_index, m: curren_cell_month}, current: {i: curren_cell_index, m: current_mouseenter_month}};
        }
    } else if ((curren_cell_month !== current_mouseenter_month) && (curren_cell_index !== current_mouseenter_index)) {//斜起
        var abs_h = Math.abs(curren_cell_index - current_mouseenter_index);
        var abs_w = Math.abs(curren_cell_month - current_mouseenter_month);
        if ((curren_cell_index - current_mouseenter_index) > 0 && (curren_cell_month - current_mouseenter_month) > 0) {//左上
            $(".cus_cell_top").stop(true, true).animate({top: top, left: left, width: (abs_w + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: top + (abs_h + 1) * height - 1, left: left, width: (abs_w + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: top, left: left, height: (abs_h + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: top, left: current_left + width - 1, height: (abs_h + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: (current_top + height - 3), left: (current_left + width - 3)}, 200);
            direction = "左上";
            return {mouse: {i: current_mouseenter_index, m: current_mouseenter_month}, current: {i: curren_cell_index, m: curren_cell_month}};
        } else if ((curren_cell_index - current_mouseenter_index) > 0 && (curren_cell_month - current_mouseenter_month) < 0) {//右上
            $(".cus_cell_top").stop(true, true).animate({top: top, left: current_left, width: (abs_w + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: current_top + height - 1, left: current_left, width: (abs_w + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: top, left: current_left, height: (abs_h + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: top, left: left + width - 1, height: (abs_h + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: top + (abs_h + 1) * height - 3, left: left + width - 3}, 200);
            direction = "右上";
            return  {mouse: {i: current_mouseenter_index, m: curren_cell_month}, current: {i: curren_cell_index, m: current_mouseenter_month}};
        } else if ((curren_cell_index - current_mouseenter_index) < 0 && (curren_cell_month - current_mouseenter_month) > 0) {//左下
            $(".cus_cell_top").stop(true, true).animate({top: top - (abs_h * height), left: left, width: (abs_w + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: (top + height) - 1, left: left, width: (abs_w + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: top - (abs_h * height), left: left, height: (abs_h + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: current_top, left: current_left + width - 1, height: (abs_h + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: (top + height) - 3, left: current_left + width - 3}, 200);
            direction = "左下";
            return {mouse: {i: curren_cell_index, m: current_mouseenter_month}, current: {i: current_mouseenter_index, m: curren_cell_month}};
        } else if ((curren_cell_index - current_mouseenter_index) < 0 && (curren_cell_month - current_mouseenter_month) < 0) {//右下
            $(".cus_cell_top").stop(true, true).animate({top: current_top, left: current_left, width: (abs_w + 1) * width}, 200);
            $(".cus_cell_bottom").stop(true, true).animate({top: (top + height) - 1, left: left - (abs_w) * width, width: (abs_w + 1) * width - 4}, 200);
            $(".cus_cell_left").stop(true, true).animate({top: current_top, left: current_left, height: (abs_h + 1) * height}, 200);
            $(".cus_cell_right").stop(true, true).animate({top: current_top, left: (left + width) - 1, height: (abs_h + 1) * height - 4}, 200);
            $(".cus_cell_point").stop(true, true).animate({top: (top + height - 3), left: (left + width - 3)}, 200);
            direction = "右下";
            return {mouse: {i: curren_cell_index, m: curren_cell_month}, current: {i: current_mouseenter_index, m: current_mouseenter_month}};
        }
    } else {
        setCusCell(current_cell);
    }
}

function create_contextmenu_item() {
    $(".table_context")[0].oncontextmenu = function () {
        return false;
    };
    $(".cus_cells_contextmenu")[0].oncontextmenu = function () {
        return false;
    };
    $(".cus_cells_copy_paste")[0].oncontextmenu = function () {
        return false;
    };
    $(".cus_cells_contextmenu").html($(".classz_ltems_panle_context").html());
    $(".cus_cells_contextmenu ul").prepend("<li class='menu_item' v='' style='width:200px;text-align:center;'>清除</li>");
    $(".menu_item").live("click", function () {
        var cus_cell_select = $(".cus_cell_select");
        cus_cell_select.html($(this).attr("v"));
        cus_cell_select.each(function () {
            var uid = $(this).parent("tr").attr("uid");
            change_cell[uid] = true;
        });
        $(".cus_cells_contextmenu").hide();
    });
    $(".cus_cells_copy_paste div").click(function () {
        var that = $(this);
        var uid = that.parent("div").attr('d');
        switch (that.attr("class")) {
            case 'cells_copy':
                paste_board = {};
                $(".table_context tr[uid='" + uid + "'] td").each(function (index, el) {
                    var this_ = $(el);
                    if (this_.hasClass("w_value") && !this_.hasClass("remark")) {
                        var key = 'm' + this_.attr('m');
                        var val = this_.html();
                        paste_board[key] = val;
                    }
                    paste_board['remark'] = $(".table_context tr[uid='" + uid + "'] .remark").html();
                });
                break;
            case 'cells_paste':
                if (JSON.stringify(paste_board) != "{}") {
                    $(".table_context tr[uid='" + uid + "'] td").each(function (index, el) {
                        var this_ = $(el);
                        if (this_.hasClass("w_value") && !this_.hasClass("remark")) {
                            var m = this_.attr('m');
                            this_.html(paste_board['m' + m]);
                        }
                        $(".table_context tr[uid='" + uid + "'] .remark").html(paste_board['remark']);
                    });
                } else {
                    ycoa.UI.toast.warning("请先复制,然后再粘贴~");
                }
                break;
        }
        that.parent("div").hide();
    });
}

/**
 * 动态生成添加 排班计划 弹出框内容
 * @param {type} maxDay
 * @param {type} curWeek
 * @returns {undefined}
 */
function createAddWorkTableFormHtml(maxDay, curWeek) {
    var add_work_table_html;
    var time = 0;
    for (var i = 0; i < 6; i++) {
        if (time >= maxDay) {
            break;
        }
        add_work_table_html += "<tr>";
        for (var j = 0; j < 7; j++) {
            if (i == 0) {
                if (j < curWeek) {
                    add_work_table_html += "<td></td>";
                } else {
                    if (time < maxDay) {
                        var t = ++time;
                        add_work_table_html += "<td><span>" + t + "</span><div class='work_select' w='week_" + j + "' name='m" + t + "' id='m" + t + "' v=''></div></td>";
                    }
                }
            } else {
                if (time < maxDay) {
                    var t = ++time;
                    add_work_table_html += "<td><span>" + t + "</span><div class='work_select' w='week_" + j + "' name='m" + t + "' id='m" + t + "' v=''></div></td>";
                } else {
                    add_work_table_html += "<td></td>";
                }
            }
        }
        add_work_table_html += "</tr>";
    }
    $(".add_work_table_form tbody").html(add_work_table_html);
    $(".add_work_table_form tbody").append("<tr><td colspan='7'><textarea class='form-control' placeholder='备注' name='remark' id='remark'></textarea></td></tr>");
}

var array = {
    year: [{id: '2015', text: '2015年'}],
    mouth: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}]
};