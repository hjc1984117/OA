var point_data = {};
var img_name;
var maxPageNo = 0;
var pageNo = 0;
var canLoad = true;
var dragenter = false;
$(function () {
    getDailyShare();
    bindEvent();
    createEMPanel();
    sendTalk();
    dropUploadImage();
});
/**
 * 获取员工说说列表以及回复
 * @returns {undefined}
 */
function getDailyShare() {
    ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 3}, function (result) {
        if (!result.show_btn) {
            $(".sp-ranking-button").remove();
        }
        if (!result.show_btn) {
            $(".sp-saft-access-button").remove();
        }
    });
    ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 1, pagesize: 10}, function (result) {
        if (result.code === 0) {
            if (result.avatar) {
                $(".div_head_avatar>img").attr('src', result.avatar);
            }
            maxPageNo = result.max_page_no;
            pageNo = result.page_no;
            create_talk_html(result);
            $("#user_talk").append("<div style='clear: both;'></div>");
        } else {
            ycoa.UI.toast.warning(result.msg);
        }
    });
    ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 2}, function (result) {
        if (result.code == 0) {
            var talk = result.list;
            var html = "<div class='div_daily_share_context'>";
            html += "<div class='div_daily_share_context_main'>";
            html += "<div class='div_daily_share_context_avatar'>";
            html += "<img src='" + (talk.avatar ? talk.avatar : '../../upload_avatar/default.png') + "'>";
            html += "</div>";
            html += "<div class='div_daily_share_context_right'>";
            html += "<div class='div_daily_share_context_name'>" + talk.username + "</div>";
            html += "<div class='div_daily_share_context_info'>";
            var date = (talk.addtime).split(" ")[0];
            if (date == result.current_date) {
                html += (talk.addtime).split(" ")[1];
            } else {
                html += talk.addtime;
            }
            html += " 回复(" + talk.reply_num + ")</div>";
            html += "</div>";
            html += "<div style='clear: both;'></div>";
            html += "</div>";
            html += "<div class='div_daily_share_content'>";
            html += talk.content;
            html += "<div class='div_daily_share_reply' id='div_daily_share_reply_" + talk.id + "'>";
            if (talk.comment) {
                $.each(talk.comment, function (index, c) {
                    html += "<div class='div_daily_share_reply_list' id='div_daily_share_reply_list_" + c.id + "'>";
                    html += "<div class='div_daily_share_reply_left'>";
                    html += "<img src='" + (c.avatar ? c.avatar : '../../upload_avatar/default.png') + "'>";
                    html += "</div>";
                    html += "<div class='div_daily_share_reply_right' id='div_daily_share_reply_right_" + c.id + "'>";
                    html += "<div class='div_daily_share_reply_right_top'>";
                    html += "<div class='daily_share_reply_name'>" + c.username + "&nbsp;:&nbsp;</div>" + c.content;
                    html += "<div style='clear: both;'></div>";
                    html += "</div>";
                    html += "<div class='div_daily_share_reply_right_botton'>";
                    var date = (c.addtime).split(" ")[0];
                    if (date == result.current_date) {
                        html += (c.addtime).split(" ")[1];
                    } else {
                        html += c.addtime;
                    }
                    html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + talk.id + ",'comment_id':" + c.id + ",'to_userid':" + c.userid + ",'to_username':'" + c.username + "'}\"><img src='../../assets/global/img/expression/hf.png'/></span>";
                    if (c.userid == ycoa.user.userid()) {
                        html += "<span class='div_daily_share_btn_delete' i='" + c.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png'/></span>";
                    }
                    html += "</div>";
                    if (c.comment) {
                        $.each(c.comment, function (index, s) {
                            html += "<div class='div_daily_share_reply_second_list'>";
                            html += "<div class='div_daily_share_reply_second_left'>";
                            html += "<img src='" + (s.avatar ? s.avatar : '../../upload_avatar/default.png') + "'>";
                            html += "</div>";
                            html += "<div class='div_daily_share_reply_second_right'>";
                            html += "<div class='div_daily_share_reply_second_right_top'>";
                            html += "<div class='daily_share_reply_name'>" + s.username + "&nbsp;<span>回复&nbsp;</span></div>";
                            html += "<div class='daily_share_reply_name'>" + s.to_username + "&nbsp;:&nbsp;</div>" + s.content;
                            html += "<div style='clear: both;'></div>";
                            html += "</div>";
                            html += "<div class='div_daily_share_reply_second_right_botton'>";
                            var date = (s.addtime).split(" ")[0];
                            if (date == result.current_date) {
                                html += (s.addtime).split(" ")[1];
                            } else {
                                html += s.addtime;
                            }
                            html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + talk.id + ",'comment_id':" + c.id + ",'to_userid':" + s.userid + ",'to_username':'" + s.username + "'}\"><img src='../../assets/global/img/expression/hf.png'/></span>";
                            if (s.userid == ycoa.user.userid()) {
                                html += "<span class='div_daily_share_btn_delete' i='" + s.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png'/></span>";
                            }
                            html += "</div>";
                            html += "</div>";
                            html += "<div style='clear: both;'></div>";
                            html += "</div>";
                        });
                    }
                    html += "</div>";
                    html += "<div style='clear: both;'></div>";
                    html += "</div>";
                });
            }
            html += "</div>";
            html += "</div>";
            html += "<div class='div_comment_outer'>";
            html += "<div class='div_comment_placeholder' placeholder='我也说一句'>我也说一句</div>";
            html += "<div class='div_comment_write_info_outer'>";
            html += "<div class='div_comment_write_info' id='div_comment_write_info_" + talk.id + "' talk_id='" + talk.id + "' contenteditable='true'></div>";
            html += "<span class='icon-emoticon-smile expression_btn' v='" + talk.id + "'></span> <button class='send_comment_btn' v='" + talk.id + "'>发表</button>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "<div style='clear: both;'></div>";
            $("#daily_share").append(html);
        } else {
            if (result) {
                ycoa.UI.toast.warning(result.msg);
            }
        }
    });
}
/**
 * 发表说说
 * @returns {undefined}
 */
function sendTalk() {
    $(".send_btn").click(function () {
        if ($(".div_write_info").html()) {
            var text = $("#div_write_info").text();
            var img = $("#div_write_info").find("img");
            if ((text.length) + (img.length) > 200) {
                ycoa.UI.toast.warning("动态文字长度不能超过200个文字哦~");
                return;
            }
            var data = {
                content: $(".div_write_info").html(),
                userid: ycoa.user.userid(),
                action: 1
            };
            if ($("#daily_share_checkbox").is(":checked")) {
                data.type = 1;
            } else {
                data.type = 0;
            }
            ycoa.ajaxLoadPost("/api/center/personal_center.php", JSON.stringify(data), function (result) {
                if (result.code != 0) {
                    ycoa.UI.toast.warning(result.msg);
                } else {
                    if (result.talk) {
                        var talk = result.talk;
                        var html = "<div class='div_daily_share_context' id='div_daily_share_context_" + talk.id + "'>";
                        html += "<div class='div_daily_share_context_main'>";
                        html += "<div class='div_daily_share_context_avatar'><img src='" + (talk.avatar ? talk.avatar : '../../upload_avatar/default.png') + "'></div>";
                        html += "<div class='div_daily_share_context_right'>";
                        html += "<div class='div_daily_share_context_name'>" + talk.username;
                        if (talk.userid == ycoa.user.userid()) {
                            html += "<span class='div_talk_btn_delete' title='删除' i='" + talk.id + "'>";
                            html += "<img src='../../assets/global/img/expression/sc.png'></span>";
                        }
                        html += "</div>";
                        html += "<div class='div_daily_share_context_info'>";
                        html += (talk.addtime).split(" ")[1];
                        html += " 回复(0)</div>";
                        html += "</div>";
                        html += "<div style='clear: both;'></div>";
                        html += "</div>";
                        html += "<div class='div_daily_share_content'>" + talk.content;
                        html += "<div class='div_daily_share_reply' id='div_daily_share_reply_" + talk.id + "'></div></div>";
                        html += "<div class='div_comment_outer'><div class='div_comment_placeholder' placeholder='我也说一句'>我也说一句</div>";
                        html += "<div class='div_comment_write_info_outer'>";
                        html += "<div class='div_comment_write_info' id='div_comment_write_info_" + talk.id + "' talk_id='" + talk.id + "' contenteditable='true'></div>";
                        html += "<span class='icon-emoticon-smile expression_btn' v='" + talk.id + "'></span>";
                        html += "<button class='send_comment_btn' v='" + talk.id + "'>发表</button>";
                        html += "</div>";
                        html += "</div>";
                        html += "</div>";
                        if (talk.type == 0) {
                            if ($("#user_talk .div_daily_share_context").length > 0) {
                                $("#user_talk .div_daily_share_context:first").before(html);
                            } else {
                                $("#user_talk .div_daily_share_title").after(html);
                            }
                        } else {
                            if ($("#daily_share .div_daily_share_context").length == 0) {
                                $("#daily_share").append(html);
                            } else {
                                $("#daily_share .div_daily_share_context").replaceWith(html);
                            }
                        }
                    }
                    ycoa.UI.toast.success(result.msg);
                    $(".div_write_info").empty();
                }
            });
        }
    });
}
/**
 * 绑定事件
 * @returns {undefined}
 */
function bindEvent() {
    $(".div_user_info .name").html(ycoa.user.username());
    $(".div_user_info .code").html(ycoa.user.employee_no());
    $(".div_user_info .dept").html(ycoa.user.dept1_name());
    $(".head-info").html(ycoa.user.username() + "的个人中心");
    var d = [1, 2];
    if (d.indexOf(ycoa.user.dept1_id()) === -1) {
        $("#daily_share_checkbox").parent("label").parent("span").remove();
    }
    $(".div_head_avatar").mouseenter(function () {
        $(".div_head_avatar_change").show();
    }).mouseleave(function () {
        $(".div_head_avatar_change").hide();
    });
    $(".div_comment_placeholder").live('click', function () {
        $(this).hide();
        $(this).parent(".div_comment_outer").find(".div_comment_write_info_outer").show();
        $(this).parent(".div_comment_outer").find(".div_comment_write_info_outer").find(".div_comment_write_info").focus();
    });
    $(".div_comment_write_info").live('blur', function () {
        if (!$(this).parent(".div_comment_write_info_outer").parent(".div_comment_outer").hasClass("canHide")) {
            $(this).parent(".div_comment_write_info_outer").parent(".div_comment_outer").find(".div_comment_placeholder").show();
            $(this).parent(".div_comment_write_info_outer").hide();
        }
    });
    $(".div_daily_share_btn").live("click", function (e) {
        var x = e.pageX;
        var y = e.pageY;
        $(".div_reply_second_outer").attr('data', $(this).attr('data'));
        var data = $.parseJSON($(this).attr('data').replace(/'/g, "\""));
        $(".div_reply_second_title").html("回复:" + data.to_username);
        var width = $(window).width();
        $(".div_reply_second_outer").css({top: (y - 20) + 'px', left: ((width - 300) / 2) + 'px'});
        $(".div_reply_second_outer").show();
    });
    $(".send_comment_btn").live('click', function () {
        var self = $(this);
        var content_ = $("#div_comment_write_info_" + $(this).attr('v'));
        var text = content_.text();
        var img = content_.find("img");
        if ((text.length) + (img.length) > 100) {
            ycoa.UI.toast.warning("回复文字长度不能超过100个文字哦~");
            return;
        }
        if ($.trim(content_.html())) {
            var data = {
                action: 2,
                userid: ycoa.user.userid(),
                username: ycoa.user.username(),
                talk_id: content_.attr('talk_id'),
                content: $.trim(content_.html()),
                comment_id: content_.attr('comment_id')
            };
            ycoa.ajaxLoadPost("/api/center/personal_center.php", JSON.stringify(data), function (result) {
                if (result.code != 0) {
                    ycoa.UI.toast.warning(result.msg);
                } else {
                    if (result.comment) {
                        var comment = result.comment;
                        var html = "<div class='div_daily_share_reply_list' id='div_daily_share_reply_list_" + comment.id + "'>";
                        html += "<div class='div_daily_share_reply_left'><img src='" + (comment.avatar ? comment.avatar : '../../upload_avatar/default.png') + "'></div>";
                        html += "<div class='div_daily_share_reply_right' id='div_daily_share_reply_right_" + comment.id + "'>";
                        html += "<div class='div_daily_share_reply_right_top'>";
                        html += "<div class='daily_share_reply_name'>" + comment.username + "&nbsp;:&nbsp;</div>" + comment.content + "<div style='clear: both;'></div></div>";
                        html += "<div class='div_daily_share_reply_right_botton'>";
                        html += (comment.addtime).split(" ")[1];
                        html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + comment.talk_id + ",'comment_id':" + comment.id + ",'to_userid':" + comment.userid + ",'to_username':'" + comment.username + "'}\"><img src='../../assets/global/img/expression/hf.png'></span>";
                        html += "<span class='div_daily_share_btn_delete' i='" + comment.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png' style='display: none;'></span></div>";
                        html += "</div>";
                        html += "<div style='clear: both;'>";
                        html += "</div>";
                        html += "</div>";
                        $("#div_daily_share_reply_" + comment.talk_id).append(html);
                        $("#div_comment_write_info_" + comment.talk_id).empty();
                        $("#div_comment_write_info_" + comment.talk_id).parent(".div_comment_write_info_outer").hide();
                        $("#div_comment_write_info_" + comment.talk_id).parent(".div_comment_write_info_outer").parent(".div_comment_outer").find(".div_comment_placeholder").show();
                        var dom = self.parent().parent().parent().find(".div_daily_share_context_main").find(".div_daily_share_context_info");
                        var html = dom.html().trim();
                        var num = html.replace(" ", "");
                        num = parseInt(num.substring(num.lastIndexOf("(") + 1, (num.length - 1)));
                        dom.html(dom.html().replace("回复(" + num + ")", "回复(" + (num + 1) + ")"));
                    }
                    ycoa.UI.toast.success(result.msg);
                }
            });
        }
    });
    $(".div_daily_share_reply_right_botton,.div_daily_share_reply_second_right").live("mouseenter", function () {
        $(this).find(".div_daily_share_btn_delete img").show();
    }).live("mouseleave", function () {
        $(this).find(".div_daily_share_btn_delete img").hide();
    });
    $(".div_daily_share_context_name").live("mouseenter", function () {
        $(this).find(".div_talk_btn_delete img").show();
    }).live("mouseleave", function () {
        $(this).find(".div_talk_btn_delete img").hide();
    });
    $(".div_reply_second_close_btn").click(function () {
        $(".div_reply_second_outer").hide();
    });
    $(".send_reply_second_btn").click(function () {
        var data = $.parseJSON($(".div_reply_second_outer").attr('data').replace(/'/g, "\""));
        data.content = $(".div_reply_second_content_write_info").html();
        data.action = 2;
        data.userid = ycoa.user.userid();
        data.username = ycoa.user.username();
        ycoa.ajaxLoadPost("/api/center/personal_center.php", JSON.stringify(data), function (result) {
            console.log(result);
            if (result.code != 0) {
                ycoa.UI.toast.warning(result.msg);
            } else {
                ycoa.UI.toast.success(result.msg);
                if (result.comment) {
                    var comment = result.comment;
                    var html = "<div class='div_daily_share_reply_second_list'>";
                    html += "<div class='div_daily_share_reply_second_left'>";
                    html += "<img src='" + (comment.avatar ? comment.avatar : '../../upload_avatar/default.png') + "'></div>";
                    html += "<div class='div_daily_share_reply_second_right'>";
                    html += "<div class='div_daily_share_reply_second_right_top'>";
                    html += "<div class='daily_share_reply_name'>" + comment.username + "&nbsp;<span>回复&nbsp;</span></div>";
                    html += "<div class='daily_share_reply_name'>" + comment.to_username + "&nbsp;:&nbsp;</div>" + comment.content + "<div style='clear: both;'></div></div>";
                    html += "<div class='div_daily_share_reply_second_right_botton'>";
                    html += (comment.addtime).split(" ")[1];
                    html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + comment.talk_id + ",'comment_id':" + comment.comment_id + ",'to_userid':" + comment.to_userid + ",'to_username':'" + comment.to_username + "'}\"><img src='../../assets/global/img/expression/hf.png'></span>";
                    html += "<span class='div_daily_share_btn_delete' i='" + comment.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png' style='display: none;'></span>";
                    html += "</div>";
                    html += "</div>";
                    html += "<div style='clear: both;'></div>";
                    html += "</div>";
                    $("#div_daily_share_reply_right_" + comment.comment_id).append(html);
                    $(".div_reply_second_outer").hide();
                }
                $(".div_reply_second_content_write_info").empty();
            }
        });
    });
    $(".div_daily_share_btn_delete").live("click", function () {
        var self_ = $(this);
        ycoa.ajaxLoadPost("/api/center/personal_center.php", {action: 3, id: $(this).attr('i')}, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                self_.parent("div").parent("div").parent("div").animate({opacity: 0}, function () {
                    if (self_.parent("div").hasClass("div_daily_share_reply_right_botton")) {
                        var dom = self_.parent('div').parent('div').parent('div').parent('div').parent('div').parent('div').find('.div_daily_share_context_main').find('.div_daily_share_context_right').find('.div_daily_share_context_info');
                        var html = dom.html().trim();
                        var num = html.replace(" ", "");
                        num = parseInt(num.substring(num.lastIndexOf("(") + 1, (num.length - 1)));
                        dom.html(dom.html().replace("回复(" + num + ")", "回复(" + (num - 1) + ")"));
                    }
                    $(this).remove();
                });
            } else {
                ycoa.UI.toast.warning(result.msg);
            }
        });
    });
    $(".div_talk_btn_delete").live('click', function () {
        var self_ = $(this);
        ycoa.UI.messageBox.confirm("确定删除此条动态吗？", function (btn) {
            if (btn) {
                ycoa.ajaxLoadPost("/api/center/personal_center.php", {action: 4, id: self_.attr('i')}, function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        $("#div_daily_share_context_" + self_.attr('i')).hide(function () {
                            $(this).remove();
                        });
                    } else {
                        ycoa.UI.toast.warning(result.msg);
                    }
                });
            }
        });
    });

    $(".div_comment_outer").live('mouseenter', function () {
        $(this).addClass("canHide");
    }).live('mouseleave', function () {
        $(this).removeClass("canHide");
    });
    $(".div_head_avatar").click(function () {
        var y = $(window).height();
        var x = $(window).width();
        $(".div_avatar_outer").css({top: ((y - 500) / 2) + 'px', left: ((x - 750) / 2) + 'px'});
        $(".div_img_outer>img").attr('src', '');
        $(".div_avatar_content_right>div").removeAttr("style");
        $(".div_avatar_content_right>div>img").remove();
        $(".avatar_upload_outer").show();
        $(".div_img_outer").hide();
        $(".div_avatar_outer").show();
        $(".avatar_upload_bar").width("0%");
        $(".avatar_upload_percent").html("0%");
        $(".avatar_upload_btn span").html("上传头像");
        $(".div_avatar_bottom>button").hide();
    });
    $(".div_avatar_close_btn").click(function () {
        $(".div_avatar_outer").hide();
    });
    var fileHZ = "jpgifpng";
    $("#avatar_fileupload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#avatar_file_upload").ajaxSubmit({
                dataType: 'json',
                url: ycoa.getNoCacheUrl('/api/center/avatar_upload.php'),
                data: {userid: ycoa.user.userid(), action: 1},
                beforeSend: function () {
                    $('#avatar_upload_showimg').empty();
                    $(".avatar_upload_progress").show();
                    var percentVal = '0%';
                    $('.avatar_upload_bar').width(percentVal);
                    $('.avatar_upload_percent').html(percentVal);
                    $(".avatar_upload_btn span").html("上传中...");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.avatar_upload_bar').width(percentVal);
                    $('.avatar_upload_percent').html(percentVal);
                },
                complete: function (xhr) {
                },
                success: function (data) {
                    if (data.code == 0) {
                        $(".avatar_upload_btn span").html("上传失败");
                        $(".avatar_upload_btn span").html("选择头像");
                        $(".div_img_outer>img").attr('src', '../../upload_avatar/temp/' + data.dir);
                        $(".avatar_upload_outer").hide();
                        $(".div_img_outer").show();
                        $(".avatar_upload_bar").width("0%");
                        $(".avatar_upload_percent").html("0%");
                        $(".avatar_upload_progress").hide();
                        $(".div_avatar_bottom>button").show();
                        img_name = data.dir;
                        var cutter = new jQuery.UtrialAvatarCutter({
                            //主图片所在容器ID
                            content: "div_img_outer",
                            //缩略图配置,ID:所在容器ID;width,height:缩略图大小
                            purviews: [{id: "picture_126", width: 100, height: 100}, {id: "picture_50", width: 50, height: 50}, {id: "picture_30", width: 30, height: 30}],
                            //选择器默认大小
                            selector: {width: 200, height: 200},
                            showCoords: function (c) { //当裁剪框变动时，将左上角相对图片的X坐标与Y坐标 宽度以及高度
                                point_data = c;
                            },
                            cropattrs: {boxWidth: 500, boxHeight: 500}
                        });
                        cutter.reload('../../upload_avatar/temp/' + data.dir);
                    } else {
                        $(".avatar_upload_btn span").html("上传失败");
                        ycoa.UI.toast.warning(data.msg);
                    }
                },
                error: function (xhr) {
                    $(".avatar_upload_btn span").html("上传失败");
                    $('.avatar_upload_bar').width('0');
                }
            });
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning('暂不支持该类型文件的上传~');
        }
    });
    $(".cut_upload").click(function () {
        point_data.name = img_name;
        point_data.action = 2;
        point_data.userid = ycoa.user.userid();
        ycoa.ajaxLoadPost("/api/center/avatar_upload.php", JSON.stringify(point_data), function (result) {
            if (result.code != 0) {
                ycoa.UI.toast.warning(result.msg);
            } else {
                $(".div_head_avatar>img").attr('src', result.avatar);
                $(".div_user_info_avatar>img", parent.document).attr("src", result.avatar);
                ycoa.UI.toast.success(result.msg);
            }
            $(".div_avatar_outer").hide();
        });
    });
    $(window).scroll(function () {
        if ((($(document).height() - $(window).height()) - $(document).scrollTop()) <= 400) {
            if (pageNo < maxPageNo) {
                if (canLoad) {
                    canLoad = false;
                    $.get(ycoa.getNoCacheUrl("/api/center/personal_center.php"), {action: 1, pagesize: 10, pageno: (pageNo + 1)}, function (result) {
                        canLoad = true;
                        maxPageNo = result.max_page_no;
                        pageNo = result.page_no;
                        create_talk_html(result);
                    });
                }
            } else {
                $(".div_daily_share_load_more").html("没有更多数据了...");
            }
        }
    });
    $(".div_gotop_outer").click(function () {
        $(document).scrollTop(0);
    });
    $(".link_panel_add_link_button").click(function () {
        var word = $(".link_word").val();
        var link = $(".link_text").val();
        if (word && link) {
            var isurl = IsURL(link);
            if (isurl) {
                var html = "";
                if (link.length > 7) {
                    var subLink = link.split(":");//.toLowerCase();
                    if (subLink.length != 0) {
                        subLink = subLink[0].toLowerCase();
                        if (subLink === "http" || subLink === "https" || subLink === "ftp" || subLink === "rtsp" || subLink === "mms") {
                            html = "<a href='" + link + "' target='_blank'>" + word + "<\a>";
                        } else {
                            html = "<a href='http://" + link + "' target='_blank'>" + word + "<\a>";
                        }
                    } else {
                        html = "<a href='http://" + link + "' target='_blank'>" + word + "<\a>";
                    }
                } else {
                    html = "<a href='http://" + link + "' target='_blank'>" + word + "<\a>";
                }
                $("#div_write_info").append(html);
                $(".link_panel_outer").hide();
            } else {
                ycoa.UI.toast.warning("请输入合法的URL地址~");
            }
        }
    });
    $(".sp-ranking-button").click(function () {
        window.location.href = "../client/salecount_ranking_list.html";
    });
    $(".sp-saft-access-button").click(function () {
        window.location.href = "../client/qqaccess_client.html";
    });
    if (jQuery.ui) {
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
        $(".expression_panel_outer").draggable({handle: ".expression_panel_close"});
        $(".link_panel_outer").draggable({handle: ".link_panel_title_context"});
        $(".div_reply_second_outer").draggable({handle: ".div_reply_second_close"});
    }
}
/**
 * 初始化表情面板
 * @returns {undefined}
 */
function createEMPanel() {
    var first_img = [4006, 6101, 4018, 6046, 4009, 6098, 4005, 4012, 6107, 6036, 4011, 4007, 6097, 6100, 6104];
    var for_s_e = ['100_204', '7000_7119', '7120_7239', '7240_7359', '7360_7449'];
    for (var i = 1; i <= 5; i++) {
        var html = "<ul class='expression_panel_outer_ul_" + i + " open' v='" + i + "'>";
        if (i === 1) {
            for (var j = 0; j < first_img.length; j++) {
                html += "<li data-id='" + first_img[j] + "'><img src='http://ctc.qzonestyle.gtimg.cn/qzone/em/e" + first_img[j] + ".gif'></li>";
            }
            for (var k = 100; k <= 204; k++) {
                html += "<li data-id='" + k + "'></li>";
            }
        } else {
            var start = parseInt(for_s_e[i - 1].split("_")[0]);
            var end = parseInt(for_s_e[i - 1].split("_")[1]);
            for (var l = start; l <= end; l++) {
                html += "<li data-id='" + l + "'></li>";
            }
        }
        html += "</ul>";
        $(".expression_panel_top").append(html);
    }
    $(".expression_panel_button .prv_page").click(function () {
        if (!$(this).hasClass("vis")) {
            var totle_page = $(".totle_page").text();
            $(".expression_panel_top ul[v='" + parseInt(totle_page) + "']").hide();
            $(".expression_panel_top ul[v='" + (parseInt(totle_page) - 1) + "']").show();
            $(".totle_page").text(parseInt(totle_page) - 1);
            if (2 === parseInt(totle_page)) {
                $(".expression_panel_button .prv_page").addClass("vis");
            }
            if ((parseInt(totle_page) - 1) < 5) {
                $(".expression_panel_button .next_page").removeClass("vis");
            }
        }
    });
    $(".expression_panel_button .next_page").click(function () {
        if (!$(this).hasClass("vis")) {
            var totle_page = $(".totle_page").text();
            $(".expression_panel_top ul[v='" + parseInt(totle_page) + "']").hide();
            $(".expression_panel_top ul[v='" + (parseInt(totle_page) + 1) + "']").show();
            $(".totle_page").text(parseInt(totle_page) + 1);
            if (4 === parseInt(totle_page)) {
                $(".expression_panel_button .next_page").addClass("vis");
            }
            if ((parseInt(totle_page) + 1) > 1) {
                $(".expression_panel_button .prv_page").removeClass("vis");
            }
        }
    });
    $(".expression_panel_top li").click(function () {
        if ($(".expression_panel_outer").attr('v')) {
            $("#div_comment_write_info_" + $(".expression_panel_outer").attr('v')).append("<img src='http://ctc.qzonestyle.gtimg.cn/qzone/em/e" + $(this).attr("data-id") + ".gif'>");
        } else {
            $("." + $(".expression_panel_outer").attr('to')).append("<img src='http://ctc.qzonestyle.gtimg.cn/qzone/em/e" + $(this).attr("data-id") + ".gif'>");
        }
        $(".expression_panel_outer").removeAttr("v");
        $(".expression_panel_outer").removeAttr("to");
        $(".expression_panel_outer").hide();
    });
    $(".expression_btn").live('click', function (e) {
        var x = e.pageX;
        var y = e.pageY;
        if ($(this).attr('v')) {
            $(".expression_panel_outer").attr('v', $(this).attr('v'));
        }
        if ($(this).attr('to')) {
            $(".expression_panel_outer").attr('to', $(this).attr('to'));
        }
        var width = $(window).width();
        $(".expression_panel_outer").css({top: (y - 100) + 'px', left: ((width - 300) / 2) + 'px'});
        $(".expression_panel_outer").show();
    });
    $(".expression_panel_close_btn").click(function () {
        $(".expression_panel_outer").removeAttr("v");
        $(".expression_panel_outer").removeAttr("to");
        $(".expression_panel_outer").hide();
    });
    $(".show_link_panel_btn").click(function () {
        var w = $(window).width();
        var h = $(window).height();
        $(".link_panel_outer").css({top: ((h - 200) / 2) + 'px', left: ((w - 350) / 2) + 'px'});
        $(".link_panel_outer").show();
        $(".link_word").val("");
        $(".link_text").val("");
    });

    $(".link_panel_title_close_btn").click(function () {
        $(".link_panel_outer").hide();
    });
}
function create_talk_html(result) {
    $.each(result.list, function (index, d) {
        var html = "<div class='div_daily_share_context' id='div_daily_share_context_" + d.id + "'>";
        html += "<div class='div_daily_share_context_main'>";
        html += "<div class='div_daily_share_context_avatar'>";
        html += "<img src='" + (d.avatar ? d.avatar : '../../upload_avatar/default.png') + "'>";
        html += "</div>";
        html += "<div class='div_daily_share_context_right'>";
        html += "<div class='div_daily_share_context_name'>" + d.username;
        if (d.userid == ycoa.user.userid()) {
            html += "<span class='div_talk_btn_delete' title='删除' i='" + d.id + "'>";
            html += "<img src='../../assets/global/img/expression/sc.png'></span>";
        }
        html += "</div>";
        html += "<div class='div_daily_share_context_info'>";
        var date = (d.addtime).split(" ")[0];
        if (date == result.current_date) {
            html += (d.addtime).split(" ")[1];
        } else {
            html += d.addtime;
        }
        html += " 回复(" + d.reply_num + ")</div>";
        html += "</div>";
        html += "<div style='clear: both;'></div>";
        html += "</div>";
        html += "<div class='div_daily_share_content'>";
        html += d.content;
        html += "<div class='div_daily_share_reply' id='div_daily_share_reply_" + d.id + "'>";
        if (d.comment) {
            $.each(d.comment, function (index, c) {
                html += "<div class='div_daily_share_reply_list' id='div_daily_share_reply_list_" + c.id + "'>";
                html += "<div class='div_daily_share_reply_left'>";
                html += "<img src='" + (c.avatar ? c.avatar : '../../upload_avatar/default.png') + "'>";
                html += "</div>";
                html += "<div class='div_daily_share_reply_right' id='div_daily_share_reply_right_" + c.id + "'>";
                html += "<div class='div_daily_share_reply_right_top'>";
                html += "<div class='daily_share_reply_name'>" + c.username + "&nbsp;:&nbsp;</div>" + c.content;
                html += "<div style='clear: both;'></div>";
                html += "</div>";
                html += "<div class='div_daily_share_reply_right_botton'>";
                var date = (c.addtime).split(" ")[0];
                if (date == result.current_date) {
                    html += (c.addtime).split(" ")[1];
                } else {
                    html += c.addtime;
                }
                html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + d.id + ",'comment_id':" + c.id + ",'to_userid':" + c.userid + ",'to_username':'" + c.username + "'}\"><img src='../../assets/global/img/expression/hf.png'/></span>";
                if (c.userid == ycoa.user.userid()) {
                    html += "<span class='div_daily_share_btn_delete' i='" + c.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png'/></span>";
                }
                html += "</div>";
                if (c.comment) {
                    $.each(c.comment, function (index, s) {
                        html += "<div class='div_daily_share_reply_second_list'>";
                        html += "<div class='div_daily_share_reply_second_left'>";
                        html += "<img src='" + (s.avatar ? s.avatar : '../../upload_avatar/default.png') + "'>";
                        html += "</div>";
                        html += "<div class='div_daily_share_reply_second_right'>";
                        html += "<div class='div_daily_share_reply_second_right_top'>";
                        html += "<div class='daily_share_reply_name'>" + s.username + "&nbsp;<span>回复&nbsp;</span></div>";
                        html += "<div class='daily_share_reply_name'>" + s.to_username + "&nbsp;:&nbsp;</div>" + s.content;
                        html += "<div style='clear: both;'></div>";
                        html += "</div>";
                        html += "<div class='div_daily_share_reply_second_right_botton'>";
                        var date = (s.addtime).split(" ")[0];
                        if (date == result.current_date) {
                            html += (s.addtime).split(" ")[1];
                        } else {
                            html += s.addtime;
                        }
                        html += "<span class='div_daily_share_btn' title='回复' data=\"{'talk_id':" + d.id + ",'comment_id':" + c.id + ",'to_userid':" + s.userid + ",'to_username':'" + s.username + "'}\"><img src='../../assets/global/img/expression/hf.png'/></span>";
                        if (s.userid == ycoa.user.userid()) {
                            html += "<span class='div_daily_share_btn_delete' i='" + s.id + "' title='删除'><img src='../../assets/global/img/expression/sc.png'/></span>";
                        }
                        html += "</div>";
                        html += "</div>";
                        html += "<div style='clear: both;'></div>";
                        html += "</div>";
                    });
                }
                html += "</div>";
                html += "<div style='clear: both;'></div>";
                html += "</div>";
            });
        }
        html += "</div>";
        html += "</div>";
        html += "<div class='div_comment_outer'>";
        html += "<div class='div_comment_placeholder' placeholder='我也说一句'>我也说一句</div>";
        html += "<div class='div_comment_write_info_outer'>";
        html += "<div class='div_comment_write_info' id='div_comment_write_info_" + d.id + "' talk_id='" + d.id + "' contenteditable='true'></div>";
        html += "<span class='icon-emoticon-smile expression_btn' v='" + d.id + "'></span> <button class='send_comment_btn' v='" + d.id + "'>发表</button>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        $("#user_talk").append(html);
    });
}

function dropUploadImage() {
    $(document).on({
        dragleave: function (e) {    //拖离 
            e.preventDefault();
        },
        drop: function (e) {  //拖后放 
            e.preventDefault();
        },
        dragenter: function (e) {    //拖进 
            e.preventDefault();
        },
        dragover: function (e) {    //拖来拖去 
            e.preventDefault();
        }
    });
    $("img").live('dragend', function (e) {  //拖后放
        if (dragenter) {
            $("#div_write_info").append("<img src='" + ($(this).attr("src")) + "'>");
            dragenter = false;
        }
    });
    var box = document.getElementById('div_write_info'); //拖拽区域
    box.addEventListener("dragenter", function () {
        dragenter = true;
    });
    box.addEventListener("dragleave", function () {
        dragenter = false;
    });
    box.addEventListener("drop", function (e) {
        var files = e.dataTransfer.files;
        if (files.length == 1) {
            var file = files[0];
            if ((file.type).indexOf('image') != -1) {
                if (file.size <= (600 * 1000)) {
                    var data = new FormData();
                    data.append('action', 3);
                    data.append('imageUpload', files[0]);
                    $.ajax({
                        url: ycoa.getNoCacheUrl('/api/center/avatar_upload.php'),
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        cache: false,
                        processData: false,
                        contentType: false
                    }).done(function (result) {
                        if (result.code != 0) {
                            ycoa.UI.toast.warning("上传提示~", result.msg);
                        } else {
                            $("#div_write_info").append("<img src='" + result.dir + "'>");
                        }
                    });
                } else {
                    ycoa.UI.toast.warning("上传提示~", "上传的图片不能大于600BK~");
                }
            } else {
                ycoa.UI.toast.warning("上传提示~", "只允许上传图片~");
            }
        }
    });
    box.addEventListener('paste', function (e) {
        if (e.clipboardData && e.clipboardData.items[0].type.indexOf('image') > -1) {
            var that = this;
            var reader = new FileReader();
            var file = e.clipboardData.items[0].getAsFile();
            reader.onload = function (e) {
                var xhr = new XMLHttpRequest();
                var fd = new FormData();
                fd.append("action", 3);
                fd.append('imageUpload', file); // this.result得到图片的base64
                xhr.open('POST', ycoa.getNoCacheUrl('/api/center/avatar_upload.php'), true);
                xhr.onload = function () {
                    var data = $.parseJSON(xhr.responseText);
                    if (data.code == 0) {
                        var img = new Image();
                        img.src = data.dir;
                        that.appendChild(img);  // 这里是把上传后得到的地址插入到#box里
                    }
                };
                xhr.send(fd);
            };
            reader.readAsDataURL(file);
        }
    }, false);
    $(".div_write_upload").click(function () {
        $("#div_write_upload").click();
    });
    var fileHZ = "jpgifpng";
    $("#div_write_upload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#write_file_upload").ajaxSubmit({
                dataType: 'json',
                data: {action: 3},
                url: ycoa.getNoCacheUrl('/api/center/avatar_upload.php'),
                success: function (data) {
                    if (data.code == 0) {
                        $("#div_write_info").append("<img src='" + data.dir + "'>");
                    } else {
                        ycoa.UI.toast.warning(data.msg);
                    }
                },
                error: function (xhr) {
                    ycoa.UI.toast.warning("上传失败");
                }
            });
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning('暂不支持该类型文件的上传~');
        }
    });
}

function IsURL(str_url) {
    var strRegex = "^((https|http|ftp|rtsp|mms)?://)?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((/?)|(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
    var re = new RegExp(strRegex);
    return re.test(str_url);
} 