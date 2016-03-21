var WordListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.wordList = ko.observableArray([]);
    self_.listWord = function (data) {
        ycoa.ajaxLoadGet("/api/work/word.php", data, function (results) {
            self_.wordList.removeAll();
            $.each(results.list, function (index, word) {
                word.date = new Date(word.date).format("yyyy-MM-dd");
                word.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('1030902');
                word.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('1030903');
                word.show = ycoa.SESSION.PERMIT.hasPagePermitButton('1030904');
                word.showFile = ycoa.SESSION.PERMIT.hasPagePermitButton('1030905') && word.pdf_file_name;
                word.downLoad = ycoa.SESSION.PERMIT.hasPagePermitButton('1030906') && word.pdf_file_name;
                self_.wordList.push(word);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.setTop = function (word) {
        word.action = 4;
        ycoa.ajaxLoadPost("/api/work/word.php", JSON.stringify(word), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.delWord = function (word) {
        WordListViewModel.wordList.remove(word);
    };
    self_.editWord = function (word) {
        $('#edit_word_form #id').val(word.id);
        $('#edit_word_form #sys_class').val(word.sys_class);
        $('#edit_word_form #sys_title').val(word.sys_title);
        $('#edit_word_form #sys_content').val(word.sys_content);
        $('#edit_word_form #file_path').val(word.file_path);
        $('#edit_word_form #pdf_file_name').val(word.pdf_file_name);
        $(".edit_upload_btn span").html("添加附件");
        $(".edit_upload_bar").width(0);
    };
    self_.showWord = function (word) {
        $("#myzhiModal #sys_contents").val(word.sys_content);
        $("#cancel_" + word.id).show();
    };
    self_.showFile = function (word) {
        window.open(ycoa.getNoCacheUrl("/api/culture/show_file.php?doc=" + base64encode(word.pdf_file_name)));
    };
    self_.downLoadFile = function (word) {
        window.location.href = ycoa.getNoCacheUrl('/api/culture/fileDownLoad.php?filename=' + base64encode(word.file_path) + "&title=" + word.sys_title);
    };
    self_.deleteWord = function (word) {
        ycoa.UI.messageBox.confirm("确定要删除该条制度信息吗?删除后不可恢复~", function (btn) {
            if (btn) {
                word.action = 5;
                ycoa.ajaxLoadPost("/api/work/word.php", JSON.stringify(word), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({});
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
    $("#myModal #sys_class").autoEditSelecter([{id: '报表', text: "报表"}]);
    $("#myEditModal #sys_class").autoEditSelecter([{id: '报表', text: "报表"}]);
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
    });
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").searchAutoStatus([{id: 0, text: "报表"}, {id: 0, text: "自定义"}, {id: 0, text: "全部"}], function (data) {
        if (data.text != "全部") {
            reLoadData({sys_class: data.text});
        } else {
            reLoadData({});
        }
    }, '制度分类');
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    }, "制度标题");
    ko.applyBindings(WordListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $('.reload').click(function () {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo()});
    });

    $("#clear_fileToUpload").click(function () {
        $("#add_word_form #file_path").val("");
        $("#add_word_form #pdf_file_name").val("");
        $(".add_upload_btn span").html("添加附件");
        $(".add_upload_bar").width(0);
    });
    $("#clear_editFileToUpload").click(function () {
        $("#edit_word_form #file_path").val("");
        $("#edit_word_form #pdf_file_name").val("");
        $(".edit_upload_btn span").html("添加附件");
        $(".edit_upload_bar").width(0);
    });

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#myModal #btn_submit_primary").click(function () {
        $("#add_word_form").submit();
    });
    $("#myEditModal #btn_submit_primary").click(function () {
        $("#edit_word_form").submit();
    });

    $("#open_dialog_btn").click(function () {
        $(".add_upload_btn span").html("添加附件");
        $(".add_upload_bar").width(0);
        $("#add_word_form input,#add_word_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });


    $(".form_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.id = $(this).attr("val");
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/word.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#add_word_form #sys_class_").change(function () {
        if ($(this).val() != "其他") {
            $("#add_word_form #sys_class").val($(this).val());
            $("#add_word_form #sys_class").attr("readonly", "");
        } else {
            $("#add_word_form #sys_class").removeAttr("readonly");
            $("#add_word_form #sys_class").val("");
        }
    });


    var fileHZ = "pptxlsdoctxthtmlpdfjpggif";
    $("#add_fileupload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#add_file_upload").ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {
                    $('#add_upload_showimg').empty();
                    $(".add_upload_progress").show();
                    var percentVal = '0%';
                    $('.add_upload_bar').width(percentVal);
                    $('.add_upload_percent').html(percentVal);
                    $(".add_upload_btn span").html("上传中...");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.add_upload_bar').width(percentVal);
                    $('.add_upload_percent').html(percentVal);
                    if (percentVal == '100%') {
                        $(".add_upload_btn span").html("转换中..");
                    }
                },
                complete: function (xhr) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.code == 0) {
                        $(".add_upload_btn span").html("上传成功");
                        $("#add_word_form #file_path").val(data.file_path);
                        $("#add_word_form #pdf_file_name").val(data.pdf_file_name);
                    } else {
                        $(".add_upload_btn span").html("上传失败");
                        ycoa.UI.toast.warning(data.message);
                    }
                },
                success: function (data) {
                    $(".add_upload_btn span").html("添加附件");
                },
                error: function (xhr) {
                    $(".add_upload_btn span").html("上传失败");
                    $('.add_upload_bar').width('0');

                }
            });
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning('暂不支持该类型文件的上传~');
        }
    });
    $("#edit_fileupload").change(function () {
        var fileToUpload = $(this);
        var files = fileToUpload.val().split(".");
        var hz = files[files.length - 1];
        if (fileHZ.indexOf(hz.toLowerCase()) > -1) {
            $("#edit_file_upload").ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {
                    $('#edit_upload_showimg').empty();
                    $(".edit_upload_progress").show();
                    var percentVal = '0%';
                    $('.edit_upload_bar').width(percentVal);
                    $('.edit_upload_percent').html(percentVal);
                    $(".edit_upload_btn span").html("上传中...");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.edit_upload_bar').width(percentVal);
                    $('.edit_upload_percent').html(percentVal);
                    if (percentVal == '100%') {
                        $(".edit_upload_btn span").html("转换中..");
                    }
                },
                complete: function (xhr) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.code == 0) {
                        $(".edit_upload_btn span").html("上传成功");
                        $("#edit_word_form #file_path").val(data.file_path);
                        $("#edit_word_form #pdf_file_name").val(data.pdf_file_name);
                    } else {
                        $(".edit_upload_btn span").html("上传失败");
                        ycoa.UI.toast.warning(data.message);
                    }
                },
                success: function (data) {
                    $(".edit_upload_btn span").html("添加附件");
                },
                error: function (xhr) {
                    $(".edit_upload_btn span").html("上传失败");
                    $('.edit_upload_bar').width('0');

                }
            });
            fileToUpload.val("");
        } else {
            ycoa.UI.toast.warning('暂不支持该类型文件的上传~');
        }
    });

    $('#myzhiModal #sys_contents').xheditor({tools: '', skin: 'nostyle', html5Upload: false, width: '300', height: '500'});
    $('#myModal #sys_content').xheditor({tools: 'Cut,Copy,Paste,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|Hr,Table,|,Source,Preview', skin: 'nostyle', html5Upload: false, width: '300', height: '350'});
    $('#myEditModal #sys_content').xheditor({tools: 'Cut,Copy,Paste,|,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|Hr,Table,|,Source,Preview', skin: 'nostyle', html5Upload: false, width: '300', height: '350'});
    $('.popovers').popover();
});
function reLoadData(data) {
    WordListViewModel.listWord(data);
}