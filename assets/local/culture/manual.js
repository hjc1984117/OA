var ManualListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.manualList = ko.observableArray([]);
    self_.listManual = function (data) {
        ycoa.ajaxLoadGet("/api/culture/manual.php", data, function (results) {
            self_.manualList.removeAll();
            $.each(results.list, function (index, manual) {
                manual.date = new Date(manual.date).format("yyyy-MM-dd");
                manual.editManual = ycoa.SESSION.PERMIT.hasPagePermitButton('1050202');
                manual.deleteManual = ycoa.SESSION.PERMIT.hasPagePermitButton('1050203');
                manual.showManual = ycoa.SESSION.PERMIT.hasPagePermitButton('1050204');
                manual.showFile = ycoa.SESSION.PERMIT.hasPagePermitButton('1050205') && manual.pdf_file_name;
                manual.downLoadFile = ycoa.SESSION.PERMIT.hasPagePermitButton('1050206') && manual.pdf_file_name;
                manual.setTop = ycoa.SESSION.PERMIT.hasPagePermitButton('1050207') && !manual.top;
                self_.manualList.push(manual);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.setTop = function (manual) {
        manual.action = 4;
        ycoa.ajaxLoadPost("/api/culture/manual.php", JSON.stringify(manual), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editManual = function (manual) {
        $('#edit_manual_form #id').val(manual.id);
        $('#edit_manual_form #sys_class').val(manual.sys_class);
        $('#edit_manual_form #sys_title').val(manual.sys_title);
        $('#edit_manual_form #sys_content').val(manual.sys_content);
        $('#edit_manual_form #file_path').val(manual.file_path);
        $('#edit_manual_form #pdf_file_name').val(manual.pdf_file_name);
        $(".edit_upload_btn span").html("添加附件");
        $(".edit_upload_bar").width(0);
    };
    self_.showManual = function (manual) {
        $("#myzhiModal #sys_contents").val(manual.sys_content);
        $("#cancel_" + manual.id).show();
    };
    self_.showFile = function (manual) {
        window.open(ycoa.getNoCacheUrl("/api/culture/show_file.php?doc=" + base64encode(manual.pdf_file_name)));
    };
    self_.downLoadFile = function (manual) {
        window.location.href = ycoa.getNoCacheUrl('/api/culture/fileDownLoad.php?filename=' + base64encode(manual.file_path) + "&title=" + manual.sys_title);
    };
    self_.deleteManual = function (manual) {
        ycoa.UI.messageBox.confirm("确定要删除该条制度信息吗?删除后不可恢复~", function (btn) {
            if (btn) {
                manual.action = 5;
                ycoa.ajaxLoadPost("/api/culture/manual.php", JSON.stringify(manual), function (result) {
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
    $("#myModal #sys_class").autoEditSelecter([{id: '考勤', text: "考勤"}, {id: '福利', text: "福利", default: true}]);
    $("#myEditModal #sys_class").autoEditSelecter([{id: '考勤', text: "考勤"}, {id: '福利', text: "福利", default: true}]);
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
    });
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").searchAutoStatus([{id: 0, text: "福利"}, {id: 0, text: "考勤"}, {id: 0, text: "自定义"}, {id: 0, text: "全部"}], function (data) {
        if (data.text != "全部") {
            reLoadData({sys_class: data.text});
        } else {
            reLoadData({});
        }
    }, '手册分类');
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    }, "标题");
    ko.applyBindings(ManualListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    $('.reload').click(function () {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo()});
    });

    $("#clear_fileToUpload").click(function () {
        $("#add_manual_form #file_path").val("");
        $("#add_manual_form #pdf_file_name").val("");
        $(".add_upload_btn span").html("添加附件");
        $(".add_upload_bar").width(0);
    });
    $("#clear_editFileToUpload").click(function () {
        $("#edit_manual_form #file_path").val("");
        $("#edit_manual_form #pdf_file_name").val("");
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
        $("#add_manual_form").submit();
    });
    $("#myEditModal #btn_submit_primary").click(function () {
        $("#edit_manual_form").submit();
    });

    $("#open_dialog_btn").click(function () {
        $(".add_upload_btn span").html("添加附件");
        $(".add_upload_bar").width(0);
        $("#add_manual_form input,#add_manual_form textarea").each(function () {
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
        ycoa.ajaxLoadPost("/api/culture/manual.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#add_manual_form #sys_class_").change(function () {
        if ($(this).val() != "其他") {
            $("#add_manual_form #sys_class").val($(this).val());
            $("#add_manual_form #sys_class").attr("readonly", "");
        } else {
            $("#add_manual_form #sys_class").removeAttr("readonly");
            $("#add_manual_form #sys_class").val("");
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
                        $("#add_manual_form #file_path").val(data.file_path);
                        $("#add_manual_form #pdf_file_name").val(data.pdf_file_name);
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
                        $("#edit_manual_form #file_path").val(data.file_path);
                        $("#edit_manual_form #pdf_file_name").val(data.pdf_file_name);
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
    ManualListViewModel.listManual(data);
}