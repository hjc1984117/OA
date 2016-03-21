var point_data = {};
var settings_array = {};
var img_name;
var maxPageNo = 0;
var pageNo = 0;
var canLoad = true;
var dragenter = false;
$(function () {
    set_index_swf_size()
    loadSettings();
    getDailyShare();
    bindEvent();
    createEMPanel();
    sendTalk();
    dropUploadImage();
    createContextMenu();
});

function set_index_swf_size() {
    $(".index_swf").height($(window).height());
    $(".index_swf_inner").height($(window).height());
}

function loadSettings() {
    $.get(ycoa.getNoCacheUrl("/api/center/personal_center.php"), {action: 4}, function (result) {
        if (result.code === 0) {
            if (result.data.settings) {
                settings_array = $.parseJSON(result.data.settings);
            } else {
                settings_array = {'background-attachment': "scroll", 'background-position': "center top", 'background-repeat': "no-repeat", 'background-size': "", 'center_title': "block", 'custom_msg': "block", 'dblc': "block", 'mrfx': "block", 'qywh': "block", 'send_talk': "block", 'style': "overflow-x: hidden; cursor: auto;", 'ygdt': "block"};
            }
        } else {
            settings_array = {'background-attachment': "scroll", 'background-position': "center top", 'background-repeat': "no-repeat", 'background-size': "", 'center_title': "block", 'custom_msg': "block", 'dblc': "block", 'mrfx': "block", 'qywh': "block", 'send_talk': "block", 'style': "overflow-x: hidden; cursor: auto; ", 'ygdt': "block"};
        }
        $("body").attr("style", settings_array.style);
        $(".div_context_left ul").css("display", settings_array.dblc);
        $("#daily_share").css("display", settings_array.mrfx);
        $("#user_talk").css("display", settings_array.ygdt);
        $(".div_write_main").css("display", settings_array.send_talk);
        $(".div_title").css("display", settings_array.custom_msg);
        $(".head-info").css("display", settings_array.center_title);
        $(".index_swf").css("display", settings_array.qywh);
    });
}
/**
 * 获取员工说说列表以及回复
 * @returns {undefined}
 */
function getDailyShare() {
    ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 1, pagesize: 10}, function (result) {
        if (result.code == 0) {
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
            html += " 浏览(" + talk.browse_num + ")  回复(" + talk.reply_num + ")</div>";
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
            html += "<span class='icon-emoticon-smile expression_btn' v='" + talk.id + "'></span>";
            html += "<span class='fa fa-photo upload_image_btn' v='" + talk.id + "'></span>";
            html += "<button class='send_comment_btn' v='" + talk.id + "'>发表</button>";
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
                        html += " 浏览(0)  回复(0)</div>";
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
    if (d.indexOf(ycoa.user.dept1_id()) == -1) {
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
        var x = ($(window).width() - 450) / 2;
        var y = e.pageY;
        $(".div_reply_second_outer").attr('data', $(this).attr('data'));
        var data = $.parseJSON($(this).attr('data').replace(/'/g, "\""));
        $(".div_reply_second_title").html("回复:" + data.to_username);
        $(".div_reply_second_outer").css({top: (y + 10) + 'px', left: (x - 40) + 'px'});
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
    $(".div_gotop_outer").mouseenter(function () {
        $(".div_gotop_back", $(this)).hide();
        $(".div_gotop_text", $(this)).show();
    }).mouseleave(function () {
        $(".div_gotop_back", $(this)).show();
        $(".div_gotop_text", $(this)).hide();
    });
    $(".div_setting_outer").mouseenter(function () {
        $(".div_setting_back", $(this)).hide();
        $(".div_setting_text", $(this)).show();
    }).mouseleave(function () {
        $(".div_setting_back", $(this)).show();
        $(".div_setting_text", $(this)).hide();
    });
    $(".corporate_culture_outer").mouseenter(function () {
        $(".corporate_culture_back", $(this)).hide();
        $(".corporate_culture_text", $(this)).show();
    }).mouseleave(function () {
        $(".corporate_culture_back", $(this)).show();
        $(".corporate_culture_text", $(this)).hide();
    });
    $(window).scroll(function () {
        if ($(document).scrollTop() > 200) {
            $(".div_gotop_outer").show();
        } else {
            $(".div_gotop_outer").hide();
        }
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
    $(".div_setting_outer").click(function () {
        $(".div_setting_panle_outer").css("left", ($(window).width() - 800) / 2).css("top", ($(window).height() - 600) / 2);
        $(".div_setting_panle_outer").animate({opacity: 1});
    });
    $(".corporate_culture_outer").click(function () {
        $(".img_view").css({left: '', top: ''});
        $(".photo_layer_body").css("line-height", $(window).height() - 85 + "px").height($(window).height() - 41);
        $('body').css('overflow-y', 'hidden');
        $(".img_view").css("width", "").attr("src", "../../assets/global/img/welcom.jpg");
        $(".photo_layer").attr("old_width", 0);
        $(".img_size_progress_outer").css("left", (($(window).width() - 1052) / 2) + 'px');
        $(".img_size_progress_btn").css("left", '500px');
        $(".photo_layer").height($(window).height()).show();
    });
    $(".link_panel_add_link_button").click(function () {
        var word = $(".link_word").val();
        var link = $(".link_text").val();
        if (word && link) {
            var http = "";
            if (link.indexOf("://") == -1) {
                http = "http://";
            }
            var html = "<a href='" + (http + link) + "' target='_blank' title='" + word + "'>" + word + "<\a>";
            $("#div_write_info").append(html);
            $(".link_panel_outer").hide();
        } else {
            ycoa.UI.toast.warning("请输入文本标签与合法的URL地址~");
        }
    });
    $(".show_at_panel_btn").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'more'}, function (d) {
            $.each(d, function (index, res) {
                $(".div_write_info").append("<em contenteditable='false' tabindex='-1' onclick='return false;' class='at_em' v='" + res.id + "' title='" + res.name + "'>@" + res.name + "</em>&nbsp;");
            });
        });
    });
    $(".show_film_panel_btn").click(function (e) {
        $(".film_panel_outer").css({top: (e.pageY + 30) + 'px', left: ((($(window).width() - 410) / 2) - 115) + 'px'});
        $(".film_panel_outer").show();
        $(".film_word").val("");
        $(".film_text").val("");
    });
    $(".film_panel_title_close_btn").click(function () {
        $(this).parent("").parent().hide();
    });
    $(".film_panel_add_film_button").click(function () {
        var word = $(".film_word").val();
        var link = $(".film_text").val();
        if (word && link) {
            var html = "<p>" + word + "</p>";
            html += "<div style='width:100%;height:400px;'>";
            html += "<object type='application/x-shockwave-flash' data='" + link + "' width='100%' height='100%' id='tb_player_17577074' style='visibility: visible;'>";
            html += "<param name='allowFullScreen' value='true'>";
            html += "<param name='allowScriptAccess' value='always'>";
            html += "<param name='wmode' value='transparent'>";
            html += "<param name='menu' value='false'>";
            html += "<param name='quality' value='high'>";
            html += "<param name='flashvars' value='autoplay = false'>";
            html += "</object>";
            html += "</div>";
            $("#div_write_info").append(html);
            $(".film_panel_outer").hide();
        } else {
            ycoa.UI.toast.warning("请输入文本标签与合法的URL地址~");
        }
    });
    $(".div_daily_share_content img").live("click", function () {
        $(".img_view").css({left: '', top: ''});
        var pase_path = "../../upload_avatar/talk/";
        var src = $(this).attr("src");
        if (src.indexOf(pase_path) !== -1) {
            $(".photo_layer").height($(window).height()).show();
            $(".photo_layer_body").css("line-height", $(window).height() - 85 + "px").height($(window).height() - 41);
            $('body').css('overflow-y', 'hidden');
            if (src.indexOf('../../upload_avatar/talk/O_') !== -1) {
                src = src.replace("../../upload_avatar/talk/", "../../upload_avatar/talk/ori/");
            }
            $(".img_view").css("width", "").attr("src", src);
            $(".photo_layer").attr("old_width", 0);
        }
        $(".img_size_progress_outer").css("left", (($(window).width() - 1052) / 2) + 'px');
        $(".img_size_progress_btn").css("left", '500px');
    });
    $('body').keyup(function (e) {
        if (e.keyCode === 27) {
            $(".photo_layer_close").click();
        }
    });
    $(".photo_layer_close").click(function () {
        $('body').css('overflow-y', 'auto');
        $(".photo_layer").hide();
    });
    $(".div_setting_panle_title_button").click(function () {
        $(".div_setting_panle_outer").animate({opacity: 0}, function () {
            $(this).css("left", -1000);
        });
        $(".bg_img_seting_par_back").click();
    });
    $(".div_comment_write_info").focus(function () {
        $(this).pasteImgEvent();
    });
    $(".bg_img_list").mouseenter(function () {
        $(this).find(".bg_img_content").stop(true, true).show().animate({'height': '40px', 'margin-top': '60px'});
    }).mouseleave(function () {
        $(this).find(".bg_img_content").stop(true, true).animate({height: '0px', 'margin-top': '100px'}, function () {
            $(this).hide();
        });
    });
    $(".bg_review").click(function () {
        var background = $(this).parent(".bg_img_content").parent(".bg_img_list").css('backgroundImage').replace("bg_img", "bg_img/ori");
        $('body').css({"background": background});
        $('body').css({"background-position": 'center top'});
        $('body').css({"background-repeat": 'no-repeat'});
    });
    $(".bg_used").click(function () {
        $("#background-attachment").val(settings_array['background-attachment']);
        $("#background-position").val(settings_array['background-position']);
        $("#background-repeat").val(settings_array['background-repeat']);
        $("#background-size").val(settings_array['background-size']);
        $("#center_title").val(settings_array.center_title);
        $("#custom_msg").val(settings_array.custom_msg);
        $("#dblc").val(settings_array.dblc);
        $("#mrfx").val(settings_array.mrfx);
        $("#qywh").val(settings_array.qywh);
        $("#send_talk").val(settings_array.send_talk);
        $("#ygdt").val(settings_array.ygdt);
        $(".bg_img_list_outer").animate({'opacity': '0'}, function () {
            $(this).hide();
            $(".bg_img_seting_par").show().animate({'opacity': '1'});
        });
        var background = $(this).parent(".bg_img_content").parent(".bg_img_list").css('backgroundImage').replace("bg_img", "bg_img/ori");
        $('body').css({"background": background});
        $('body').css({"background-position": 'center top'});
        $('body').css({"background-repeat": 'no-repeat'});
    });
    $(".center_setting").click(function () {
        $("#background-attachment").val(settings_array['background-attachment']);
        $("#background-position").val(settings_array['background-position']);
        $("#background-repeat").val(settings_array['background-repeat']);
        $("#background-size").val(settings_array['background-size']);
        $("#center_title").val(settings_array.center_title);
        $("#custom_msg").val(settings_array.custom_msg);
        $("#dblc").val(settings_array.dblc);
        $("#mrfx").val(settings_array.mrfx);
        $("#qywh").val(settings_array.qywh);
        $("#send_talk").val(settings_array.send_talk);
        $("#ygdt").val(settings_array.ygdt);
        $(".bg_img_list_outer").animate({'opacity': '0'}, function () {
            $(this).hide();
            $(".bg_img_seting_par").show().animate({'opacity': '1'});
        });
    });
    $(".bg_img_seting_par_back").click(function () {
        $(".bg_img_seting_par").animate({'opacity': '0'}, function () {
            $(this).hide();
            $(".bg_img_list_outer").show().animate({'opacity': '1'});
        });
    });
    $(".bg_img_seting_par_body select").change(function () {
        $("body").css($(this).attr("id"), $(this).val());
    });
    $("#bg_color").spectrum({
        color: "#000",
        className: "full-spectrum",
        showInitial: true,
        showPalette: true,
        showSelectionPalette: true,
        maxPaletteSize: 10,
        preferredFormat: "hex",
        localStorageKey: "spectrum.demo",
        chooseText: "确认",
        cancelText: "取消",
        change: function (color) {
            $("body").css("color", color.toHexString());
        },
        palette: [
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
        ]
    });
    $("#bg_page_color").spectrum({
        color: "#fff",
        className: "full-spectrum",
        showInitial: true,
        showPalette: true,
        showSelectionPalette: true,
        maxPaletteSize: 10,
        preferredFormat: "hex",
        localStorageKey: "spectrum.demo",
        chooseText: "确认",
        cancelText: "取消",
        change: function (color) {
            $("body").css("background", color.toHexString());
        },
        palette: [
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)", "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
        ]
    });
    $(".set_model").change(function () {
        if ($(this).attr("v") === ".index_swf") {
            if ($(this).val() === "block") {
                $(".index_swf").show().animate({opacity: 1});
            } else {
                $(".index_swf").animate({opacity: 0}, function () {
                    $(".index_swf").hide();
                });
            }
        } else {
            $($(this).attr("v")).css("display", $(this).val());
        }

    });
    $(".bg_img_seting_par_submit").click(function () {
        var setting_array = {};
        $(".bg_img_seting_par_body select").each(function () {
            setting_array[$(this).attr("id")] = $(this).val();
        });
        setting_array['style'] = $("body").attr("style");
        $(".bg_img_seting_par_body .set_model").each(function () {
            setting_array[$(this).attr("id")] = $(this).val();
        });
        ycoa.ajaxLoadPost("/api/center/personal_center.php", {action: 5, settings: JSON.stringify(setting_array)}, function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success("保存设置成功~");
                $(".div_setting_panle_outer").animate({opacity: 0}, function () {
                    $(this).css("left", -1000);
                });
                $(".bg_img_seting_par_back").click();
            } else {
                ycoa.UI.toast.warning("保存设置失败,请稍后重试~");
            }
        });
    });
    $(".return_default").click(function () {
        ycoa.UI.messageBox.confirm("确认恢复个人设置到初始值吗?", function (btn) {
            if (btn) {
                $.post("/api/center/personal_center.php", {action: 5, settings: ""}, function (result) {
                    if (result.code === 0) {
                        ycoa.UI.toast.success("保存成功~");
                        $("body").css({background: 'white', color: '#000'});
                    } else {
                        ycoa.UI.toast.success("保存失败~");
                    }
                });
            }
        });
    });
    $(".photo_layer").mousewheel(function (event, delta, deltaX, deltaY) {
        if (!$(".photo_layer").is(":hidden")) {
            if ($(".photo_layer").attr("old_width") === "0") {
                $(".photo_layer").attr("old_width", $(".img_view").width());
            }
            var left = Number($(".img_size_progress_btn").css("left").replace("px", ""));
            if (delta === 1) {
                if (left < 1000) {
                    $(".img_size_progress_btn").css("left", (left + 10) + 'px');
                }
            } else if (delta === -1) {
                if (left > 0) {
                    $(".img_size_progress_btn").css("left", (left - 10) + 'px');
                }
            }
            var x = Number($(".img_size_progress_btn").css("left").replace("px", ""));
            var old_width = $(".photo_layer").attr("old_width");
            if (x === 500) {
                $(".img_view").width(old_width);
            } else if (x < 500) {
                $(".img_view").width(old_width * ((x * 2) / 1000));
            } else if (x > 500) {
                $(".img_view").width(Number(old_width) + (old_width * ((x - 500) * 2 / 1000)));
            }
        }
    });
    $(".index_swf_inner").click(function () {
        $(".index_swf").animate({opacity: 0}, function () {
            $(this).hide();
        });
    });
    if (jQuery.ui) {
        $('.div_avatar_outer').draggable({handle: ".div_avatar_close_title"});
        $(".expression_panel_outer").draggable({handle: ".expression_panel_close"});
        $(".link_panel_outer").draggable({handle: ".link_panel_title_context"});
        $(".film_panel_outer").draggable({handle: ".film_panel_title_context"});
        $(".div_setting_panle_outer").draggable({handle: ".div_setting_panle_title"});
        $(".div_reply_second_outer").draggable({handle: ".div_reply_second_title"});
        $(".img_view").draggable();
        $('.img_size_progress_btn').draggable({
            axis: 'x',
            containment: 'parent',
            start: function (event, ui) {
                if ($(".photo_layer").attr("old_width") === "0") {
                    $(".photo_layer").attr("old_width", $(".img_view").width());
                }
            },
            drag: function (event, ui) {
                var x = ui.position.left;
                var old_width = $(".photo_layer").attr("old_width");
                if (x === 500) {
                    $(".img_view").width(old_width);
                } else if (x < 500) {
                    $(".img_view").width(old_width * ((x * 2) / 1000));
                } else if (x > 500) {
                    $(".img_view").width(Number(old_width) + (old_width * ((x - 500) * 2 / 1000)));
                }
            }
        });
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
    $(".expression_panel_top li").click(function (e) {
        if ($(".expression_panel_outer").attr('v')) {
            $("#div_comment_write_info_" + $(".expression_panel_outer").attr('v')).append("<img src='http://ctc.qzonestyle.gtimg.cn/qzone/em/e" + $(this).attr("data-id") + ".gif'>");
        } else {
            $("." + $(".expression_panel_outer").attr('to')).append("<img src='http://ctc.qzonestyle.gtimg.cn/qzone/em/e" + $(this).attr("data-id") + ".gif'>");
        }
        if (!e.ctrlKey) {
            $(".expression_panel_outer").removeAttr("v");
            $(".expression_panel_outer").removeAttr("to");
            $(".expression_panel_outer").hide();
        }
    });
    $(".expression_btn").live('click', function (e) {
        if ($(this).attr('v')) {
            $(".expression_panel_outer").attr('v', $(this).attr('v'));
        }
        if ($(this).attr('to')) {
            $(".expression_panel_outer").attr('to', $(this).attr('to'));
        }
        $(".expression_panel_outer").css({top: (e.pageY + 30) + 'px', left: ((($(window).width() - 410) / 2) - 115) + 'px'});
        $(".expression_panel_outer").show();
    });
    $(".expression_panel_close_btn").click(function () {
        $(".expression_panel_outer").removeAttr("v");
        $(".expression_panel_outer").removeAttr("to");
        $(".expression_panel_outer").hide();
    });
    $(".show_link_panel_btn").click(function (e) {
        $(".link_panel_outer").css({top: (e.pageY + 30) + 'px', left: ((($(window).width() - 410) / 2) - 115) + 'px'});
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
        html += " 浏览(" + d.browse_num + ")  回复(" + d.reply_num + ")</div>";
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
        html += "<span class='icon-emoticon-smile expression_btn' v='" + d.id + "'></span>";
        html += "<span class='fa fa-photo upload_image_btn' v='" + d.id + "'></span>";
        html += "<button class='send_comment_btn' v='" + d.id + "'>发表</button>";
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
                        if (result.code !== 0) {
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
                ycoa.UI.block.show();
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
                        ycoa.UI.block.hide();
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
    $(".bg_img_custom_setting").click(function () {
        $("#bg_img_fileupload").click();
    });
    var fileHZ = "jpgifpng";
    $("#div_write_upload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#write_file_upload").ajaxSubmit({
                dataType: 'json',
                url: ycoa.getNoCacheUrl('/api/center/avatar_upload.php'),
                data: {userid: ycoa.user.userid(), action: 3},
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
    $(".upload_image_btn").live("click", function () {
        $("#div_write_comment_upload").attr("v", $(this).attr("v")).click();
    });
    $("#div_write_comment_upload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#write_file_comment_upload").ajaxSubmit({
                dataType: 'json',
                url: ycoa.getNoCacheUrl('/api/center/avatar_upload.php'),
                data: {userid: ycoa.user.userid(), action: 3},
                success: function (data) {
                    if (data.code === 0) {
                        $("#div_comment_write_info_" + fileToUpload.attr("v")).append("<img src='" + data.dir + "'>");
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
    $("#bg_img_fileupload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#bg_img_file_upload").ajaxSubmit({
                dataType: 'json',
                url: ycoa.getNoCacheUrl('/api/center/bg_img_upload.php'),
                data: {userid: ycoa.user.userid(), action: 1},
                beforeSend: function () {
                    $('.bg_img_upload_progress').width("0%");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.bg_img_upload_progress').width(percentVal);
                },
                complete: function (xhr) {
                },
                success: function (data) {
                    if (data.code === 0) {
//                        var background = (data.dir).replace("bg_img", "bg_img/ori");
                        var background = data.dir;
                        $('body').css({"background-image": "url(" + background + ")"});
                        $(".bg_img_list_outer").animate({'opacity': '0'}, function () {
                            $(this).hide();
                            $(".bg_img_seting_par").show().animate({'opacity': '1'});
                        });
                        $('.bg_img_upload_progress').width("0%");
                    } else {
                        ycoa.UI.toast.warning(data.msg);
                    }
                },
                error: function (xhr) {
                    $('.bg_img_upload_progress').width('0%');
                }
            });
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning('暂不支持该类型文件的上传~');
        }
    });
}
function createContextMenu() {
    document.oncontextmenu = function (e) {
        var x = e.pageX;
        var y = e.pageY;
        $(".oncontextMenu").css({top: y + 'px', left: x + 'px'}).show();
        return false;
    };
    $(".menu_list").click(function () {
        var v = $(this).attr('v');
        switch (v) {
            case '1':
                window.location.reload();
                break;
        }
        $(".oncontextMenu").hide();
    });
    $(document).bind("click", function (e) {
        var target = $(e.target);
        if (target.closest(".oncontextMenu").length === 0) {
            $(".oncontextMenu").hide();
        }
    });
}