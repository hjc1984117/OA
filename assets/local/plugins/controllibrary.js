//**********************************
// 这是基于jQuery的控件库
// 姓名:肖湘峰
// 时间：2013年7月9日 17:23:05
//**********************************
(function ($) {
    //水印文本框控件
    $.fn.TextBoxWatermark = function (val, percent) {
        percent = 0.2 || percent;
        var $txt = $(this);
        $txt.wrap("<div style=\"position:relative;float:left\"></div>");
        var h = $txt[0].offsetHeight - parseFloat($txt.css("padding-top").substring(0, $txt.css("padding-top").length - 2));
        var p = parseFloat(h * 0.2);
        var lh = h - p * 2;
        var $lab = $("<label style=\"position:absolute;color:#CCC;font-family:微软雅黑;cursor:text;margin:0px;left:3px;top:3px;\">" + val + "</label>");
        if (/msie/.test(navigator.userAgent.toLowerCase())) {
            $lab = $("<label style=\"position:absolute;color:#CCC;font-family:微软雅黑;cursor:text;left:0px;top:3px;\">" + val + "</label>");
        }
        $lab.css({ "font-size": lh + "px" }).appendTo($txt.parent()).bind("click", function () {
            $(this).hide();
            $txt.focus();
        });
        if ($txt.val() != "") $lab.hide();
        $txt.bind("blur", function () {
            if (($(this).val() || $(this).text()) == "") {
                $lab.show();
            }
        }).bind("focus", function () {
            $lab.hide();
        });
    }
    $.fn.watermark = function (c, t) {
        var e = function (e) {
            var i = $(this);
            if (!i.val()) {
                var w = t || i.attr('title'), $c = $($("<div />").append(i.clone()).html().replace(/type=\"?password\"?/, 'type="text"')).val(w).addClass(c);
                i.replaceWith($c);
                $c.focus(function () {
                    $c.replaceWith(i); setTimeout(function () { i.focus(); }, 1);
                })
                .change(function (e) {
                    i.val($c.val()); $c.val(w); i.val() && $c.replaceWith(i);
                })
                .closest('form').submit(function () {
                    $c.replaceWith(i);
                });
            }
        };
        return $(this).bind('blur change', e).change();
    };
    //自定义提示信息控件
    $.fn.showTitle = function (val) {
        var keyupTime;
        clearInterval(keyupTime);
        $("#" + this.id + "_mess").remove();
        var $txt = $(this);
        if ($txt.val() == "") {
            var $title = $("<div id='" + this.id + "_mess' style='position:absolute;background-color:red;'>" + val + "</div>");
            var x = $txt.position().left, y = $txt.position().top + $txt.height();
            $title.css({ "left": x + "px", "top": y + "px" }).appendTo($txt.parent());
            $txt.focus();
            $txt.bind("focus", function () {
                keyupTime = setInterval(function () {
                    if ($txt.val() != "") {
                        $("#" + this.id + "_mess").remove();
                        clearInterval(keyupTime);
                    }
                }, 80);
            });
        }
    }

    $.fn.myDialog = function (p) {
        var $t = $("<div style=\"display: none;position: fixed;left: 450px; top: 200px; background-color: blue;  line-height: 50px; padding: 20px 20px 20px 40px; margin: 10px auto;\" class=\"border-76b3e6\"></div>")
        .append($("<img style=\"float: left\" src=\"../../content/front/images/error.png\" />"))
        .append($("<div style=\"line-height: 20px; float: left;\"><p></p></div>"))
        .append($("<span></span>"));
        $("#overlay").html($t);
        var oo = document.getElementById("Button1");
        var op = {
            height: p.height || 50,
            width: p.width || 350,
            message: p.message || "提示信息",
            button: p.button || { '确定': function () { $t.hide() } }
        };
        for (var p in op.button) {
            $("<input type=\"button\" class=\"submit-blue float-left\" value=\"" + p + "\" />")
            .bind("click", function () { op.button[this.value]($t) }).appendTo($t.children("span"));
        }

        $t.find("p").text(op.message);
        $t.attr({ "height": op.height, "width": op.width });
        $(this).bind("click", function () { $t.show(); });
    }

    $.fn.fixedDialog = function (p, m) {
        $(this).hide();
        if (m != undefined && m!=null) $(this).find("p").html(m);
        switch (p) {
            case "open": $(this).css({ "z-index": "1001" }).show().prev().show(); return;
            case "close": $(this).hide().prev().hide(); return;
        }
        var left = $(document).width() / 2 - $(this).width() / 2;
        $(this).css({ "display": "none", "left": left + "px", "top": ($(window).height() - $(this).height()) / 2 + "px", "position": "fixed" })
            .before($("<div class=\"overlay\"></div>"));
    }

    //自制radio控件
    $.fn.check = function () {
        var val;
        $(this).each(function () {
            if ($(this).attr("ck") == "ck") { val = $(this).value(); return false; }
        });
        return val;
    }
    $.fn.value = function () {
        var obj = new Object();
        return $(this).attr("value");
    }
    $.fn.radio = function (fun,bfc) {
        var $pth = $(this);
        $pth.find("a").bind("click", function () {//sort - down
            if (bfc != undefined) {
                if (bfc($(this)) == false) return $pth;
            }
            var $th = $(this);
            if ($th.attr("ck") != "ck") {
                $pth.find("a").removeAttr("ck").removeClass("selected");
                $th.attr({ "ck": "ck", "class": "selected" });
                if (fun != undefined) fun.apply(this);
            }
        });
        return $pth;
    }

    $.fn.getCkObj = function () {
        var obj = {};
        $(this).find("li").each(function (i) {
            var val = $(this).find("a").check();
            if (val != undefined) obj[this.id] = val;
        });
        return obj;
    }
    $.fn.verTextArea = function (max) {
        var $t = $(this)
        if (max == "init") {
            $t.each(function () {
                $(this).css({ "border": "1px solid rgb(169, 169, 169)", "outline": "" });
                $(this).data("tc").css("color", "#bababa").html("限" + $(this).data("max") + "字");
            });
            return $t;
        }
        $(this).each(function () {
            var $p = $("<p>限" + max + "字</p>").css("color", "#bababa");
            $(this).after($p).data({"tc":$p,"max":max}).bind("keyup", function () {
                var $th = $(this), val = $th.val() || $th.text(), len = val.length, dk = "error";
                if (len <= 0) {
                    $(this).css({"border": "1px solid rgb(169, 169, 169)","outline":""});
                    $p.css("color", "rgb(186, 186, 186)").css("color", "#bababa").html("限" + max + "字");
                    $th.data(dk, 0);
                } else if (len > 0 && len < max) {
                    $(this).css({ "border": "1px solid rgb(169, 169, 169)", "outline": "" });
                    $p.css("color", "rgb(186, 186, 186)").html("已输入<span style='color:rgb(19, 187, 19)'>" + len + "</span>字，还可输入<span style='color:rgb(19, 187, 19)'>" + (max - len) + "</span>字");
                    $th.data(dk, 1);
                }
                else {
                    $(this).css({"border": "2px solid red","outline":"0 none"});
                    $p.css("color", "red").html("已超出" + (len - max) + "字");
                    $th.data(dk, 0);
                }
            });
        });
    }
})(jQuery)