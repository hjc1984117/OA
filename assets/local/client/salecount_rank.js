$(function () {
    var url_ = "";
    ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 3}, function (result) {
        var is_manager = result.is_manager;
        if (is_manager) {
            url_ = "sale";
        } else {
            var u = result.url;
            if (u) {
                url_ = u;
                $(".data_type").remove();
            } else {
                window.location.href = "../center/personal_center_client.html";
            }
        }
        bindDataEvent(url_);
    });
});
function bindDataEvent(url_) {
    $(".data_type").click(function () {
        url_ = $(this).attr("d");
        $(".data_type").removeClass("d_checked");
        $(this).addClass("d_checked");
    });
    ycoa.ajaxLoadGet("/api/" + url_ + "/salecount.php", {action: 4}, function (results) {
        var TodayTotalsHtml = "<ul>";
        var TodayTotals = results;
        if (url_ === "sale") {
            var TodayBaiduPCTotals = TodayTotals.TodayBaiduPCTotals;
            var TodayBaiduMTotals = TodayTotals.TodayBaiduMTotals;
            var Today360Totals = TodayTotals.Today360Totals;
            var TodaySogouTotals = TodayTotals.TodaySogouTotals;
            var TodayBaiduPCTimelyTotals = TodayTotals.TodayBaiduPCTimelyTotals;
            var TodayBaiduMTimelyTotals = TodayTotals.TodayBaiduMTimelyTotals;
            var Today360TimelyTotals = TodayTotals.Today360TimelyTotals;
            var TodaySogouTimelyTotals = TodayTotals.TodaySogouTimelyTotals;
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 17px;'><span class='presales'>x百度PC:YD</span><span class='count'>" + TodayBaiduPCTotals + ":" + TodayBaiduMTotals + "</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 17px;'><span class='presales'>及时PC:YD</span><span class='count'>" + TodayBaiduPCTimelyTotals + ":" + TodayBaiduMTimelyTotals + "</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 17px;'><span class='presales'>百度合计</span><span class='count'>" + (TodayBaiduPCTotals + TodayBaiduMTotals) + "(及时:" + (TodayBaiduPCTimelyTotals + TodayBaiduMTimelyTotals) + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color:rgb(158, 158, 21);font-size: 17px;'><span class='presales'>360</span><span class='count'>" + Today360Totals + "(及时:" + Today360TimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color:rgb(26, 26, 187);font-size: 17px;'><span class='presales'>搜狗</span><span class='count'>" + TodaySogouTotals + "(及时:" + TodaySogouTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color:rgb(8, 105, 8);font-size: 17px;'><span class='presales'>总计</span><span class='count'>" + (TodayBaiduPCTotals + TodayBaiduMTotals + Today360Totals + TodaySogouTotals) + "(及时:" + (TodayBaiduPCTimelyTotals + TodayBaiduMTimelyTotals + Today360TimelyTotals + TodaySogouTimelyTotals) + ")</span></li>";
        } else {
            var TodayWWTotals = TodayTotals.TodayWWTotalsSoft;
            var TodayBDTotals = TodayTotals.TodayBDTotalsSoft;
            var Today360Totals = TodayTotals.Today360TotalsSoft;
            var TodaySGTotals = TodayTotals.TodaySGTotalsSoft;
            var TodayUCTotals = TodayTotals.TodayUCTotalsSoft;
            var TodayYHZTotals = TodayTotals.TodayYHZTotalsSoft;

            var TodayWWTimelyTotals = TodayTotals.TodayWWTimelyTotalsSoft;
            var TodayBDTimelyTotals = TodayTotals.TodayBDTimelyTotalsSoft;
            var Today360TimelyTotals = TodayTotals.Today360TimelyTotalsSoft;
            var TodaySGTimelyTotals = TodayTotals.TodaySGTimelyTotalsSoft;
            var TodayUCTimelyTotals = TodayTotals.TodayUCTimelyTotalsSoft;
            var TodayYHZTimelyTotals = TodayTotals.TodayYHZTimelyTotalsSoft;

            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>百度</span><span class='count'>" + TodayBDTotals + "(及时:" + TodayBDTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>360</span><span class='count'>" + Today360Totals + "(及时:" + Today360TimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>搜狗</span><span class='count'>" + TodaySGTotals + "(及时:" + TodaySGTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>UC神马</span><span class='count'>" + TodayUCTotals + "(及时:" + TodayUCTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>旺旺</span><span class='count'>" + TodayWWTotals + "(及时:" + TodayWWTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>优化站</span><span class='count'>" + TodayYHZTotals + "(及时:" + TodayYHZTimelyTotals + ")</span></li>";
            TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>总计</span><span class='count'>" +
                    (TodayBDTotals + Today360Totals + TodaySGTotals + TodayUCTotals + TodayWWTotals + TodayYHZTotals) +
                    "(及时:" +
                    (TodayBDTimelyTotals + Today360TimelyTotals + TodaySGTimelyTotals + TodayUCTimelyTotals + TodayWWTimelyTotals + TodayYHZTimelyTotals) +
                    ")</span></li>";
        }
        TodayTotalsHtml += "</ul>";
        $(".auto_tab_main .auto_tab_context div[var='1']").html(TodayTotalsHtml).animate({width: '100%'}, 300, function () {
            $(this).addClass("open");
        });
    });
    $(".auto_tab_main .auto_tab_title .li_sale").click(function () {
        var self = $(this);
        var date = new Date().getTime();
        if ((date - parseInt($(".auto_tab_main").attr("t"))) > 500) {
            var v = self.attr("var");
            if (v === '4') {
                ycoa.ajaxLoadGet("/api/" + url_ + "/salecount.php", {action: 4}, function (results) {
                    var TodayTotalsHtml = "<ul>";
                    var TodayTotals = results;
                    if (url_ === "sale") {
                        var TodayBaiduPCTotals = TodayTotals.TodayBaiduPCTotals;
                        var TodayBaiduMTotals = TodayTotals.TodayBaiduMTotals;
                        var Today360Totals = TodayTotals.Today360Totals;
                        var TodaySogouTotals = TodayTotals.TodaySogouTotals;
                        var TodayBaiduPCTimelyTotals = TodayTotals.TodayBaiduPCTimelyTotals;
                        var TodayBaiduMTimelyTotals = TodayTotals.TodayBaiduMTimelyTotals;
                        var Today360TimelyTotals = TodayTotals.Today360TimelyTotals;
                        var TodaySogouTimelyTotals = TodayTotals.TodaySogouTimelyTotals;
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);'><span class='presales'>百度PC:YD</span><span class='count'>" + TodayBaiduPCTotals + ":" + TodayBaiduMTotals + "</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);'><span class='presales'>及时PC:YD</span><span class='count'>" + TodayBaiduPCTimelyTotals + ":" + TodayBaiduMTimelyTotals + "</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);'><span class='presales'>百度合计</span><span class='count'>" + (TodayBaiduPCTotals + TodayBaiduMTotals) + "(及时:" + (TodayBaiduPCTimelyTotals + TodayBaiduMTimelyTotals) + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color:rgb(158, 158, 21);'><span class='presales'>360</span><span class='count'>" + Today360Totals + "(及时:" + Today360TimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color:rgb(26, 26, 187);'><span class='presales'>搜狗</span><span class='count'>" + TodaySogouTotals + "(及时:" + TodaySogouTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color:rgb(8, 105, 8);'><span class='presales'>总计</span><span class='count'>" + (TodayBaiduPCTotals + TodayBaiduMTotals + Today360Totals + TodaySogouTotals) + "(及时:" + (TodayBaiduPCTimelyTotals + TodayBaiduMTimelyTotals + Today360TimelyTotals + TodaySogouTimelyTotals) + ")</span></li>";
                    } else {
                        var TodayWWTotals = TodayTotals.TodayWWTotalsSoft;
                        var TodayBDTotals = TodayTotals.TodayBDTotalsSoft;
                        var Today360Totals = TodayTotals.Today360TotalsSoft;
                        var TodaySGTotals = TodayTotals.TodaySGTotalsSoft;
                        var TodayUCTotals = TodayTotals.TodayUCTotalsSoft;
                        var TodayYHZTotals = TodayTotals.TodayYHZTotalsSoft;

                        var TodayWWTimelyTotals = TodayTotals.TodayWWTimelyTotalsSoft;
                        var TodayBDTimelyTotals = TodayTotals.TodayBDTimelyTotalsSoft;
                        var Today360TimelyTotals = TodayTotals.Today360TimelyTotalsSoft;
                        var TodaySGTimelyTotals = TodayTotals.TodaySGTimelyTotalsSoft;
                        var TodayUCTimelyTotals = TodayTotals.TodayUCTimelyTotalsSoft;
                        var TodayYHZTimelyTotals = TodayTotals.TodayYHZTimelyTotalsSoft;

                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>百度</span><span class='count'>" + TodayBDTotals + "(及时:" + TodayBDTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>360</span><span class='count'>" + Today360Totals + "(及时:" + Today360TimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>搜狗</span><span class='count'>" + TodaySGTotals + "(及时:" + TodaySGTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>UC神马</span><span class='count'>" + TodayUCTotals + "(及时:" + TodayUCTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>旺旺</span><span class='count'>" + TodayWWTotals + "(及时:" + TodayWWTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>优化站</span><span class='count'>" + TodayYHZTotals + "(及时:" + TodayYHZTimelyTotals + ")</span></li>";
                        TodayTotalsHtml += "<li class='li_rate' style='color: rgb(194, 22, 22);font-size: 13px;'><span class='presales'>总计</span><span class='count'>" +
                                (TodayBDTotals + Today360Totals + TodaySGTotals + TodayUCTotals + TodayWWTotals + TodayYHZTotals) +
                                "(及时:" +
                                (TodayBDTimelyTotals + Today360TimelyTotals + TodaySGTimelyTotals + TodayUCTimelyTotals + TodayWWTimelyTotals + TodayYHZTimelyTotals) +
                                ")</span></li>";
                    }
                    TodayTotalsHtml += "</ul>";
                    $(".auto_tab_main .auto_tab_context div[var='" + v + "']").html(TodayTotalsHtml);
                });
            } else if (v === '5') {
                ycoa.ajaxLoadGet("/api/second_" + url_ + "/platform.php", {action: 11, time_unit: 1}, function (result) {
                    var html = "<div class='second_tab_title'>";
                    html += "<ul>";
                    html += "<li class = 'second_sale second_select' var = '1' >当天</li>";
                    html += "<li class='second_sale' var = '2'>近七天</li>";
                    html += "<li class='second_sale' var = '3'>当月</li>";
                    html += "</ul>";
                    html += "</div>";
                    html += "<div class = 'second_tab_context'>";
                    html += "<div class = 'second_open' c_var = '1' >";
                    html += "<ul>";
                    $.each(result, function (index, d) {
                        var clas = "num";
                        if (index == 0) {
                            clas = "num_1";
                        } else if (index == 1) {
                            clas = "num_2";
                        } else if (index == 2) {
                            clas = "num_3";
                        }
                        html += "<li class='li_second_rate'><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.customer) + "</span><span class='count'>" + (d.count) + "</span></li>";
                    });
                    html += "</ul>";
                    html += "</div>";
                    html += "<div class = '' c_var = '2'>";
                    html += "</div>";
                    html += "<div class = '' c_var = '3'>";
                    html += "</div>";
                    html += "</div>";
                    $(".auto_tab_main .auto_tab_context div[var='" + v + "']").html(html);
                    $(".second_tab_context .second_open").animate({width: '100%'});
                });
            } else {
                ycoa.ajaxLoadGet("/api/" + url_ + "/salecount.php", {action: 2, time_unit: v}, function (result) {
                    var html = "<ul>";
                    $.each(result, function (index, d) {
                        var clas = "num";
                        if (index == 0) {
                            clas = "num_1";
                        } else if (index == 1) {
                            clas = "num_2";
                        } else if (index == 2) {
                            clas = "num_3";
                        }
                        html += "<li class='li_rate'><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.presales) + "</span><span class='count'>" + (d.sale_count) + "</span></li>";
                    });
                    html += "</ul>";
                    $(".auto_tab_main .auto_tab_context div[var='" + v + "']").html(html);
                });
            }
            $(".auto_tab_main").attr("t", date);
            $(".auto_tab_main li").removeClass("select");
            $(this).addClass("select");
            $(".auto_tab_context .open").animate({width: '0px'}, 300, function () {
                $(this).removeClass("open");
                $(this).hide();
                $(".auto_tab_context div[var='" + v + "']").show();
                $(".auto_tab_context div[var='" + v + "']").animate({width: '100%'}, 300, function () {
                    $(this).addClass("open");
                });
            });
        }
    });
    $(".auto_tab_context").on("click", ".second_sale", function () {
        var c_var = $(this).attr("var");
        var html = "<ul>";
        ycoa.ajaxLoadGet("/api/second_" + url_ + "/platform.php", {action: 11, time_unit: c_var}, function (result) {
            $.each(result, function (index, d) {
                var clas = "num";
                if (index == 0) {
                    clas = "num_1";
                } else if (index == 1) {
                    clas = "num_2";
                } else if (index == 2) {
                    clas = "num_3";
                }
                html += "<li class='li_second_rate'><span class='" + clas + "'>" + (index + 1) + "</span><span class='presales'>" + (d.customer) + "</span><span class='count'>" + (d.count) + "</span></li>";
            });
            html += "</ul>";
        });
        $(".second_tab_title li").removeClass("second_select");
        $(this).addClass("second_select");
        $(".second_tab_context .second_open").animate({width: '0px'}, 300, function () {
            $(this).removeClass("second_open");
            $(this).hide();
            $(".second_tab_context div[c_var='" + c_var + "']").addClass("second_open");
            $(".second_tab_context div[c_var='" + c_var + "']").html(html);
            $(".second_tab_context div[c_var='" + c_var + "']").animate({width: '100%'});
        });
    });
}