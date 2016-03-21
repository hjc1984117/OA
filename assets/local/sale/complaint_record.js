var hand_personnel_array = {};
var complaintRecordListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.complaintRecordList = ko.observableArray([]);
    self_.listcomplaintRecord = function (data) {
        ycoa.ajaxLoadGet("/api/sale/complaint_record.php", data, function (results) {
            self_.complaintRecordList.removeAll();
            $.each(results.list, function (index, complaintRecord) {
                if ((complaintRecord.complaint_content).indexOf("[{") > -1) {
                    var complaint_json = JSON.parse(complaintRecord.complaint_content.replace("[]", "[{\"text\":\"\"}]").replace(",{}", "").replace("{}", ""));
                    complaintRecord.complaint_content = complaint_json;
                    complaintRecord.hand_result = "";
                } else {
                    complaintRecord.complaint_content = [{complaint_content: complaintRecord.complaint_content, hand_result: [{text: complaintRecord.hand_result}]}];
                }
                complaintRecord.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2011202");
                complaintRecord.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2011203");
                complaintRecord.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2011204");
                console.log(complaintRecord);
                self_.complaintRecordList.push(complaintRecord);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({
                    action: 1,
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize,
                    searchName: $("#searchName").val(),
                    searchSource: $("#searchCS").val(), searchPerson: $("#searchPerson").val(), searchResult: $("#searchResult").val()
                });
            }, function (pageNo) {
                reLoadData({
                    action: 1,
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    searchName: $("#searchName").val(),
                    searchSource: $("#searchCS").val(), searchPerson: $("#searchPerson").val(), searchResult: $("#searchResult").val()
                });
            });
        });
    };
    self_.delComplaintRecord = function (complaintRecord) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                complaintRecord.action = 2;
                ycoa.ajaxLoadPost("/api/sale/complaint_record.php", JSON.stringify(complaintRecord), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        reLoadData({action: 1});
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });
            }
        });
    };
    self_.doEditComplaintRecord = function (complaintRecord) {
        var formid = "form_" + complaintRecord.id;
        complaintRecord = $("#" + formid).serializeJson();
        complaintRecord.action = 3;
        var record_array = new Array();
        $(".complaint_content_item", $("#" + formid)).each(function () {
            if ($(".complaint_content", $(this)).html() !== "") {
                var com = {complaint_content: $(".complaint_content", $(this)).html()};
                var res_array = new Array();
                $("#hand_result", $(this)).each(function () {
                    if ($(this).val() !== "") {
                        var r = {text: $(this).val()};
                        res_array.push(r);
                    }
                });
                com.hand_result = res_array;
                record_array.push(com);
            }
        });
        if (((typeof complaintRecord.hand_result) === "object" && complaintRecord.hand_result.length === 0) || (typeof complaintRecord.hand_result) === "string" && complaintRecord.hand_result === "") {
            complaintRecord.hand_result = "";
        }
        complaintRecord.complaint_content = JSON.stringify(record_array);
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        complaintRecord.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        ycoa.ajaxLoadPost("/api/sale/complaint_record.php", JSON.stringify(complaintRecord), function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editComplaintRecord = function (complaintRecord) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + complaintRecord.id).show();
        $("#submit_" + complaintRecord.id).show();
        $("#cancel_" + complaintRecord.id).show();
        $("#tr_" + complaintRecord.id + " input,#tr_" + complaintRecord.id + " textarea").removeAttr("disabled");
        if (!$("#form_" + complaintRecord.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + complaintRecord.id));
        }
    };
    self_.cancelTr = function (complaintRecord) {
        $("#tr_" + complaintRecord.id).hide();
        $("#submit_" + complaintRecord.id).hide();
        $("#cancel_" + complaintRecord.id).hide();
    };
    self_.showComplaintRecord = function (complaintRecord) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + complaintRecord.id).show();
        $("#submit_" + complaintRecord.id).hide();
        $("#cancel_" + complaintRecord.id).show();
        $("#tr_" + complaintRecord.id + " input,#tr_" + complaintRecord.id + " textarea").attr("disabled", "");
        if (!$("#form_" + complaintRecord.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + complaintRecord.id));
        }
    };
    self_.showLog = function (complaintRecord) {
        var w_id = complaintRecord.id;
        $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
            $(this).addClass("data_open");
        });
        $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
        $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 11, obj_id: w_id}, function (result) {
            if (result.list.length > 0 && result.list) {
                var html = "<ul>";
                $.each(result.list, function (idnex, d) {
                    html += "<li>[" + d.addtime + " (" + d.username + ")] <br>" + d.changed_desc + "</li>";
                });
                html += "</ul>";
                $("#dataLog_detail_" + w_id).html(html);
            } else {
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/workflowLog_detail_nodata.png'>");
            }
        });
    };
}();
$(function () {
    ko.applyBindings(complaintRecordListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({
            action: 1,
            sort: data.sort, sortname: data.sortname,
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: $("#searchName").val(),
            searchSource: $("#searchCS").val(), searchPerson: $("#searchPerson").val()
        });
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchName').val('');
        $('#searchCS').val("");
        $("#searchPerson").val("");
        $("#searchResult").val("");
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: name, searchSource: $("#searchCS").val(), searchPerson: $("#searchPerson").val(), searchResult: $("#searchResult").val()
        });
    }, '按关键字查找', 'searchName');
    $("#dataTable").searchAutoStatus(array['complaint_source'], function (cs) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: $("#searchName").val(), searchSource: cs.id, searchPerson: $("#searchPerson").val(), searchResult: $("#searchResult").val()
        });
    }, "投诉来源", "searchCS");
    $("#dataTable").searchAutoStatus([{id: 1, text: '已分配'}, {id: '2', text: '未分配'}], function (pl) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: $("#searchName").val(), searchSource: $("#searchCS").val(), searchPerson: pl.id, searchResult: $("#searchResult").val()
        });
    }, "分配状态", "searchPerson");
    $("#dataTable").searchAutoStatus([{id: 1, text: '已处理'}, {id: '2', text: '未处理'}], function (sr) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: $("#searchName").val(), searchSource: $("#searchCS").val(), searchPerson: $("#searchPerson").val(), searchResult: sr.id
        });
    }, "处理状态", "searchResult");
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/sale/complaint_record.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
    $("#add_complaint_record_form #quick_write").keypress(function (e) {
        if (e.keyCode === 13 && $(this).val()) {
            $.get(ycoa.getNoCacheUrl("/api/sale/salecount.php"), {action: 3, key_word: $(this).val()}, function (res) {
                if (res.length === 1) {
                    var data = res[0];
                    data.ac_name = data.name;
                    data.shop = data.payment;
                    data.phone = data.mobile;
                    data.complaint_custom = data.presales;
                    $("#add_complaint_record_form input").each(function () {
                        var el = $(this);
                        if (data[el.attr("id")]) {
                            el.val(data[el.attr("id")]);
                        }
                    });
                } else if (res.length > 1) {
                    var y = $(window).height();
                    var x = $(window).width();
                    var html = "<div style='position:fixed;z-index:99999;top:" + ((y - 500) / 2) + "px;left:" + ((x - 750) / 2) + "px;height: 400px;width: 700px;box-shadow: 0 5px 15px rgba(0,0,0,.5);border: 1px solid #999;background:#ffffff;'>";
                    html += "<div style='height:40px;line-height:40px;'>";
                    html += "<div title='关闭' style='height:40px;width:40px;line-height:40px;float:right;cursor:pointer;text-align: center;' onclick='close_sale_qk(this)'>x</div>";
                    html += "</div>";
                    html += "<div style='overflow-x: hidden;overflow-y: auto;height: 358px;'>";
                    html += "<table style='width:698px;border-top: 1px solid #999;'>";
                    html += "<thead>";
                    html += "<tr style='height:40px;border-bottom: 1px solid #999;'>";
                    html += "<td style='width:140px;'>添加日期</td>";
                    html += "<td style='width:140px;'>旺旺</td>";
                    html += "<td style='width:100px;'>QQ</td>";
                    html += "<td style='width:80px;'>真实姓名</td>";
                    html += "<td>联系电话</td>";
                    html += "</thead>";
                    html += "<tbody>";
                    $.each(res, function (index, d) {
                        d.ac_name = d.name;
                        d.shop = d.payment;
                        d.phone = d.mobile;
                        d.complaint_custom = d.presales;
                        html += "<tr style='height:40px;width:698px;border-bottom: 1px solid #999;cursor:pointer;' v='" + (JSON.stringify(d)) + "' onclick=set_sale_pl_val(this);>";
                        html += "<td>" + (d.addtime) + "</div>";
                        html += "<td>" + (d.ww) + "</div>";
                        html += "<td>" + (d.qq) + "</div>";
                        html += "<td>" + (d.ac_name) + "</div>";
                        html += "<td>" + (d.phone) + "</div>";
                        html += "</tr>";
                    });
                    html += "</tbody>";
                    html += "</table>";
                    html += "</div>";
                    html += "</div>";
                    $("body").append(html);
                } else if (res.length === 0) {
                    ycoa.UI.toast.warning("未匹配到相应的数据,请核对后重试~");
                    $("#add_complaint_record_form input,#add_complaint_record_form textarea").val("");
                    $("#add_complaint_record_form #complaint_content").html("");
                }
            });
        }
    });
    $("#add_complaint_record_form #add_hand_result").click(function () {
        var html = "<div class='form-group new_add'><label class='control-label col-md-2' for='hand_result'>处理结果</label><div class='col-md-8'><div class='input-icon right'><i class='fa'></i><textarea class='form-control' name='hand_result' id='hand_result' aria-invalid='false'></textarea></div></div><span class='glyphicon glyphicon-minus' id='remove_hand_result' style='cursor: pointer;margin-top: 20px;' title='删除处理结果'></span></div>";
        $("#add_complaint_record_form #hand_result_outer").append(html);
    });
    $("#add_complaint_record_form").on("click", "#remove_hand_result", function () {
        $(this).closest(".form-group").slideUp(function () {
            $(this).remove();
        });
    });
    $("#open_dialog_btn").click(function () {
        $("#add_complaint_record_form #remove_hand_result").click();
        $("#add_complaint_record_form #complaint_content").html("");
        $("#add_complaint_record_form input,#add_complaint_record_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
    });
    $.get(ycoa.getNoCacheUrl("/api/sale/complaint_record.php"), {action: 10}, function (result) {
        hand_personnel_array = result;
        $("#add_complaint_record_form #hand_personnel").autoEditSelecter(result);
    });
    $("#add_complaint_record_form #complaint_source").autoRadio(array['complaint_source']);
    $("#add_complaint_record_form #complaint_content").pasteImgEvent();
});
function set_sale_pl_val(d) {
    var data = JSON.parse($(d).attr("v"));
    $("#add_complaint_record_form input").each(function () {
        var el = $(this);
        if (data[el.attr("id")]) {
            el.val(data[el.attr("id")]);
        }
    });
    $("#add_platform_form #phone").val(data.mobile);
    $(d).parent().parent().parent().parent().remove();
}
function close_sale_qk(d) {
    $(d).parent().parent().remove();
}
function reLoadData(data) {
    data.action = 1;
    complaintRecordListViewModel.listcomplaintRecord(data);
}
function initEditSeleter(el) {
    $("#complaint_content", el).pasteImgEvent();
    $(".add_complaint_content", el).click(function () {
        var html = "<div class='complaint_content_item' style='border: solid 1px #ccc;padding: 5px;margin-bottom: 5px;'>";
        html += "<div class='form-group'>";
        html += "<label class='control-label col-md-2'>";
        html += "<span class='glyphicon glyphicon-minus remove_complaint_content' style='cursor: pointer;margin-top: 20px;' title='删除投诉'></span>投诉内容：</label>";
        html += "<div class='col-md-10'>";
        html += "<div contenteditable='true' name='complaint_content' class='complaint_content' id='complaint_content' style='overflow: auto;text-align: left;height: 150px;padding: 5px;border: solid 1px #f3f3f3;outline: none;' data-bind='html:complaint_content'></div>";
        html += "</div>";
        html += "</div>";
        html += "<div class='hand_result_outer'>";
        html += "<div class='form-group'>";
        html += "<label class='control-label col-md-2'>";
        html += "<span class='fa fa-plus add_hand_result' style='cursor: pointer;margin-top: 20px;' title='删除处理结果'></span>处理结果：</label>";
        html += "<div class='col-md-10'>";
        html += "<textarea class='form-control' name='hand_result' class='hand_result' id='hand_result' placeholder='处理结果' data-bind='value:hand_result'></textarea>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        $(".complaint_content_outer", el).append(html);
        $("#complaint_content", el).pasteImgEvent();
    });
    el.on("click", ".remove_complaint_content", function () {
        $(this).closest(".complaint_content_item").slideUp(function () {
            $(this).remove();
        });
    });
    el.on("click", ".add_hand_result", function () {
        var html = "<div class='form-group'>";
        html += "<label class='control-label col-md-2'>";
        html += "<span class='glyphicon glyphicon-minus remove_hand_result' style='cursor: pointer;margin-top: 20px;' title='添加处理结果'></span>";
        html += "处理结果：";
        html += "</label>";
        html += "<div class='col-md-10'>";
        html += "<textarea class='form-control' name='hand_result' class='hand_result' id='hand_result' placeholder='处理结果' data-bind='value:hand_result'></textarea>";
        html += "</div>";
        html += "</div>";
        $(this).closest(".hand_result_outer").append(html);
        $("#complaint_content", el).pasteImgEvent();
    });
    el.on("click", ".remove_hand_result", function () {
        $(this).closest(".form-group").slideUp(function () {
            $(this).remove();
        });
    });
    $("#hand_personnel", el).autoEditSelecter(hand_personnel_array);
    $("#complaint_source", el).autoRadio(array['complaint_source']);
    el.attr('autoEditSelecter', 'autoEditSelecter');
}

var array = {
    complaint_source: [{id: '400', text: '400'}, {id: '站内信', text: '站内信'}, {id: '负面', text: '负面'}, {id: '店铺', text: '店铺'}, {id: '官网', text: '官网'}, {id: '邮箱', text: '邮箱'}, {id: '旺旺', text: '旺旺'}, {id: 'QQ接待', text: 'QQ接待'}]
};
