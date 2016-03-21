var year_selecter = 0;
var isCreate = false;
var weekDatNum = 0;
$(function () {
    init({action: 1});
    bindMenuEvent();
    $("#btn_submit_primary").click(function () {
        ycoa.UI.messageBox.confirm("请确保本次录入的数据是一个月用餐的完整数据,一旦录入将不提供数据修改~", function (btn) {
            if (btn) {
                var array = {};
                array['userid'] = ycoa.user.userid();
                array['dept_id'] = ycoa.user.dept1_id();
                array['action'] = 1;
                $('.add_canteen_statistics_table_form .work_select,.add_canteen_statistics_table_form textarea,.add_canteen_statistics_table_form input,.add_canteen_statistics_table_form .auto_input').each(function () {
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
                ycoa.ajaxLoadPost('/api/work/canteen_statistics.php', jsonStr, function (result) {
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
                var data = $("#add_canteen_statistics_day_form").serializeJson();
                data.dept_id = ycoa.user.dept1_id();
                data.action = 2;
                data = JSON.stringify(data);
                ycoa.ajaxLoadPost("/api/work/canteen_statistics.php", data, function (result) {
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
        $(".add_canteen_statistics_table_form #add_date").val("");
        ycoa.ajaxLoadGet("/api/work/canteen_statistics.php", {action: 2}, function (result) {
            $(".add_canteen_statistics_table_form #add_date").attr('v', result.current_date);
            var data = result.list;
            if (data) {
                $(".add_canteen_statistics_table_form #can_id").val(data['id']);
                $(".add_canteen_statistics_table_form #remark").val(data['remark']);
                $(".add_canteen_statistics_table_form .work_select").each(function () {
                    var self_ = $(this);
                    var value = data[self_.attr("name")];
                    self_.attr('v', value);
                    switch (value) {
                        case 'N':
                            self_.html("中");
                            break;
                        case 'A':
                            self_.html("晚");
                            break;
                        case 'N/A':
                            self_.html("中/晚");
                            break;
                    }
                });
            } else {
                $(".add_canteen_statistics_table_form #can_id").val("");
                $(".add_canteen_statistics_table_form #remark").val("");
                $(".add_canteen_statistics_table_form .work_select").each(function () {
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
    $("#dataTable").searchDept(function (d) {
        init({action: 1, dept_id: d, searchTime: $("#searchTime").val()});
        $("#dept_id").val(d);
    });
    $("#dataTable").searchAutoStatus(array['mouth'], function (d) {
        var date = (year_selecter == 0 ? '2015' : year_selecter) + "-" + d.id;
        init({action: 1, searchTime: date, dept_id: $("#dept_id").val()});
        $("#searchTime").val(date);
    }, '月份');
    $("#btn_eat_n").click(function () {
        var sun = $('#sun').is(':checked');
        var sta = $('#sta').is(':checked');
        $(".add_canteen_statistics_table_form .work_select").each(function () {
            $(this).attr('v', 'N');
            $(this).html('中');
        });
        if (!sun) {
            $(".add_canteen_statistics_table_form [w='week_0']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_0']").html('');
        }
        if (!sta) {
            $(".add_canteen_statistics_table_form [w='week_6']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_6']").html('');
        }
    });
    $("#btn_eat_a").click(function () {
        var sun = $('#sun').is(':checked');
        var sta = $('#sta').is(':checked');
        $(".add_canteen_statistics_table_form .work_select").each(function () {
            $(this).attr('v', 'A');
            $(this).html('晚');
        });
        if (!sun) {
            $(".add_canteen_statistics_table_form [w='week_0']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_0']").html('');
        }
        if (!sta) {
            $(".add_canteen_statistics_table_form [w='week_6']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_6']").html('');
        }
    });
    $("#btn_eat_na").click(function () {
        var sun = $('#sun').is(':checked');
        var sta = $('#sta').is(':checked');
        $(".add_canteen_statistics_table_form .work_select").each(function () {
            $(this).attr('v', 'N/A');
            $(this).html('中/晚');
        });
        if (!sun) {
            $(".add_canteen_statistics_table_form [w='week_0']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_0']").html('');
        }
        if (!sta) {
            $(".add_canteen_statistics_table_form [w='week_6']").attr('v', '');
            $(".add_canteen_statistics_table_form [w='week_6']").html('');
        }
    });
    $("#btn_eat_no").click(function () {
        $(".add_canteen_statistics_table_form .work_select").each(function () {
            $(this).attr('v', '');
            $(this).html('');
        });
    });
    $("#open_write").click(function () {
        var self_ = $(this);
        var thisTime = $(this).attr('time');
        if (thisTime == '0') {
            self_.attr('time', new Date().getTime());
        } else {
            var thisTime = parseInt(thisTime);
            if (((new Date().getTime()) - thisTime) > 3000) {
                self_.attr('time', new Date().getTime());
            } else {
                ycoa.UI.toast.warning('点击频率过高,请稍后重试~');
                return;
            }
        }
        var data = {action: 3, key: 'write_canteen_statistics'};
        if (self_.children("i").hasClass("glyphicon-ok")) {
            data['value'] = 0;
        } else {
            data['value'] = 1;
        }
        ycoa.ajaxLoadPost("/api/sys/sys_ky.php", data, function (result) {
            if (result.code == 1) {
                ycoa.UI.toast.success(((result.value == 1) ? "开启" : "关闭") + "成功~");
                if (result.value == 1) {
                    self_.html("<i class='glyphicon glyphicon-ok'></i>关闭填单");
                    $(".permit_1030802").show();
                } else {
                    self_.html("<i class='glyphicon'></i>开启填单");
                    $(".permit_1030802").hide();
                }
            } else {
                ycoa.UI.toast.success(((result.value == 1) ? "开启" : "关闭") + "失败~");
            }
        });
    });
    $("#add_canteen_statistics_day_form #username").val(ycoa.user.username());
    $("#add_canteen_statistics_day_form #userid").val(ycoa.user.userid());

    $(".prve_mouth").click(function () {
        var currentDate = $(".add_canteen_statistics_table_title").html();
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
        $(".add_canteen_statistics_table_title").html(year + "年" + mouth + "月");
        $(".add_canteen_statistics_table_form #add_date").attr("v", year + "-" + mouth + "-01");
        createAddWorkTableFormHtml(maxDay, curWeek);
        $(".next_mouth").show();
        $(".prve_mouth").hide();
    });
    $(".next_mouth").click(function () {
        var currentDate = $(".add_canteen_statistics_table_title").html();
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
        $(".add_canteen_statistics_table_title").html(year + "年" + mouth + "月");
        $(".add_canteen_statistics_table_form #add_date").attr("v", year + "-" + mouth + "-01");
        createAddWorkTableFormHtml(maxDay, curWeek);
        $(".next_mouth").hide();
        $(".prve_mouth").show();
    });
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
    ycoa.ajaxLoadGet("/api/work/canteen_statistics.php", data, function (results) {
        var currentDate = (results.currentDate).split('-');
        var c_d = (results.c_d).split('-');
        var week = results.currentWeek;
        var curWeek = parseInt(results.currentWeek);
        $('.table_title').html(results.title);
        $('.add_canteen_statistics_table_title').html(parseInt(c_d[0]) + "年" + parseInt(c_d[1]) + "月");
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
        if (results.can_write == 1) {
            $("#open_write").html("<i class='glyphicon glyphicon-ok'></i>关闭填单");
            $(".permit_1030802").show();
        } else {
            $("#open_write").html("<i class='glyphicon'></i>开启填单");
            $(".permit_1030802").hide();
        }
    });
}

/**
 * 绑定添加 食堂统计 下拉菜单事件
 * @returns {undefined}
 */
function bindMenuEvent() {
    $('.work_select').live('click', function () {
        var self_ = $(this);
        var X = self_.offset().top;
        var Y = self_.offset().left;
        $('.select_menu_canteen').css({top: X + 40, left: Y + 10});
        $('.select_menu_canteen li').unbind('click');
        $('.select_menu_canteen li').bind('click', function () {
            self_.attr('v', $(this).attr('v'));
            self_.text($(this).text());
            self_.removeAttr("class");
            self_.attr('class', 'work_select ' + $(this).attr('c'));
            $('.select_menu_canteen').hide();
        });
        $('.select_menu_canteen').show();
    });
    $(".week_title").live('click', function () {
        var self_ = $(this);
        var X = self_.offset().top;
        var Y = self_.offset().left;
        $('.select_menu_canteen').css({top: X + 40, left: Y + 10});
        $('.select_menu_canteen li').unbind('click');
        $('.select_menu_canteen li').bind('click', function () {
            $(".add_canteen_statistics_table_form [w='week_" + self_.attr('v') + "']").attr('v', $(this).attr('v'));
            $(".add_canteen_statistics_table_form [w='week_" + self_.attr('v') + "']").html($(this).html());
            $('.select_menu_canteen').hide();
        });
        $('.select_menu_canteen').show();
    });
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (!target.hasClass('work_select') && !target.hasClass('week_title')) {
            if (target.closest(".select_menu_canteen").length == 0) {
                $(".select_menu_canteen").hide();
            }
        }
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
                table_week_html += "<td class='weekend_sun'>" + getUpercaseNum(week) + "</td>";
                break;
            case 6:
                table_week_html += "<td class='weekend_sat'>" + getUpercaseNum(week) + "</td>";
                if (weekDatNum == 0) {
                    weekDatNum = (j + 1);
                }
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
            table_date_html += "<td  class='td_last' rowspan='2'>统计</td>";
        } else {
            if (i <= maxDay) {
                table_date_html += "<td>" + i + "</td>";
            } else {
                table_date_html += "<td>" + (++autoDate) + "</td>";
            }
        }
    }
    $(".table_date").html(table_date_html);
}

/**
 * 动态生成 食堂统计展示列表
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
            table_context_html += "<tr>";
            table_context_html += "<td>" + d['name'] + "</td>";
            var table_context_html_c = "";
            var empty_num = 0;
            for (var i = 1; i <= 31; i++) {
                var val = (ycoa.util.isEmpty(d["m" + i]) ? "" : d["m" + i]);
                val = val.replace("N", "中");
                val = val.replace("A", "晚");
                if (i < maxDay + 1) {
                    if (!ycoa.util.isEmpty(val)) {
                        table_context_html_c += "<td>" + val + "</td>";
                    } else {
                        empty_num++;
                        if (i == weekDatNum || (i % 7) == weekDatNum) {
                            table_context_html_c += "<td class='weekend_sat'></td>";
                        } else if (i == (weekDatNum + 1) || (i % 7) == (weekDatNum + 1)) {
                            table_context_html_c += "<td class='weekend_sun'></td>";
                        } else {
                            table_context_html_c += "<td>" + val + "</td>";
                        }
                    }
                } else {
                    table_context_html_c += "<td></td>";
                }
            }
            if (empty_num == maxDay) {
                table_context_html_c = table_context_html_c.replace(/class=\'weekend_sat\'/g, "");
                table_context_html_c = table_context_html_c.replace(/class=\'weekend_sun\'/g, "");
            }
            table_context_html += table_context_html_c;
            table_context_html += "<td>" + (ycoa.util.isEmpty(d['count']) ? "" : d['count']) + "</td>";
            table_context_html += "</tr>";
        });
        $(".table_context").html(table_context_html);
    }
}

/**
 * 动态生成添加 食堂统计 弹出框内容
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
    $(".add_canteen_statistics_table_form tbody").html(add_work_table_html);
    $(".add_canteen_statistics_table_form tbody").append("<tr><td colspan='7'><textarea class='form-control' placeholder='备注' name='remark' id='remark'></textarea></td></tr>");

}

var array = {
    year: [{id: '2015', text: '2015年'}],
    mouth: [{id: '01', text: '一月'}, {id: '02', text: '二月'}, {id: '03', text: '三月'}, {id: '04', text: '四月'}, {id: '05', text: '五月'}, {id: '06', text: '六月'}, {id: '07', text: '七月'}, {id: '08', text: '八月'}, {id: '09', text: '九月'}, {id: '10', text: '十月'}, {id: '11', text: '十一月'}, {id: '12', text: '十二月'}]
};