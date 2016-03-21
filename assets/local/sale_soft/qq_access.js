var AccessListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.accessList = ko.observableArray([]);
    self_.listAccess = function (data) {
        ycoa.ajaxLoadGet("/api/sale_soft/qq_access.php", data, function (results) {
            self_.accessList.removeAll();
            $.each(results.list, function (index, access) {
            access.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3031302") && (access.hasEdit || access.add_userid == ycoa.user.userid());
            access.edit = true;//ycoa.SESSION.PERMIT.hasPagePermitButton("3031303") && (access.hasEdit || access.add_userid == ycoa.user.userid());
            access.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3031304");
            access.can_access = ycoa.SESSION.PERMIT.hasPagePermitButton("3031305") && ((!access.access_time) ? true : false) && access.presales_id === ycoa.user.userid();
            access.type = ((access.access_time) ? 1 : 0);
            access.hasval = ycoa.user.userid() === access.presales_id && access.hasValidation !== 1 && access.type === 0;
         //   access.reSetPresales = ycoa.SESSION.PERMIT.hasPagePermitButton("2011006");
            self_.accessList.push(access);
            });
            var TodayTotals = results.TodayTotals;
            var TodayBaiduPCTotals = TodayTotals.TodayBaiduPCTotals;
            var TodayBaiduMTotals = TodayTotals.TodayBaiduMTotals;
            var Today360Totals = TodayTotals.Today360Totals;
            var TodaySogouTotals = TodayTotals.TodaySogouTotals;
            var TodayUCTotals = TodayTotals.TodayUCTotals;
            var TodayBaiduZhiDaoTotals = TodayTotals.TodayBaiduZhiDaoTotals;
            var TodayWangMengTotals = TodayTotals.TodayWangMengTotals;
            var TodayWeiXinTotals = TodayTotals.TodayWeiXinTotals;
            var TodayYouHuaZhanTotals = TodayTotals.TodayYouHuaZhanTotals;
            var TodayNoTotals = TodayTotals.TodayNoTotals;
            var TodayTotalsHtml = "<span style='color: rgb(194, 22, 22);'>百度PC:YD(" + TodayBaiduPCTotals + ":" + TodayBaiduMTotals + ")&nbsp;合计&nbsp;" + (TodayBaiduPCTotals + TodayBaiduMTotals) + "单&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(158, 158, 21);'>360:" + Today360Totals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>搜狗:" + TodaySogouTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>UC:" + TodayUCTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>百度知道:" + TodayBaiduZhiDaoTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>网盟:" + TodayWangMengTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>微信:" + TodayWeiXinTotals + "&nbsp;</span>";
             TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>优化站:" + TodayYouHuaZhanTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(26, 26, 187);'>无渠道匹配:" + TodayNoTotals + "&nbsp;</span>";
            TodayTotalsHtml += "<span style='color:rgb(8, 105, 8);'>总数:" + (TodayBaiduPCTotals + TodayBaiduMTotals + Today360Totals + TodaySogouTotals +TodayUCTotals+TodayBaiduZhiDaoTotals+TodayWangMengTotals+TodayWeiXinTotals+TodayYouHuaZhanTotals+TodayNoTotals) + "</span>";
            $('.TodayTotals').html(TodayTotalsHtml);
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({
                    action: 1,
                    pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(), searchChannel: $("#searchChannel").val(),
                    searchStatus:$("#searchStatus").val()
                });
            }, function (pageNo) {
                reLoadData({
                    action: 1,
                    pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(),
                    sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
                    searchName: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(), searchChannel: $("#searchChannel").val(), searchStatus:$("#searchStatus").val()
                });
            });
        });
    };
    self_.delAccess = function (access) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                access.action = 3;
                ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", JSON.stringify(access), function (result) {
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
    self_.editAccess = function (access) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + access.id).show();
        $("#submit_" + access.id).show();
        $("#cancel_" + access.id).show();
        if (access.hasChangePresales) {
            if (!$("#form_" + access.id).attr('autoEditSelecter')) {
                initEditSeleter($("#form_" + access.id));
            }
        }
        $("#tr_" + access.id + " input,#tr_" + access.id + " textarea").removeAttr("disabled");
        //编辑渠道
        var edit_channel_user = [161,162,163,178,254];//不在这个数组中的人没权限
        var edit_channel_role = [1104,1110,1112];//不在这个角色里的没权限      
        if(edit_channel_user.indexOf(ycoa.user.userid())!= -1 || edit_channel_role.indexOf(access.role_id)!= -1){
                $("#form_"+ access.id +" #channel").attr("disabled",false);
        }

        //如果渠道是微信的话，默认是没有分配售前的，需要在编辑中手动选择
        if(access.channel == "微信" && access.presales == ""){           
            $("#form_"+ access.id +" #presales").attr("placeholder","请点击选择售前");
             $("#form_"+ access.id +" #presales").click(function () {
                ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (data, el) {
                    el.val(data.name);
                    $("#form_"+ access.id +" #presales_id").val(data.id);
                });
            });
        }
    };
    self_.doEdit = function (access) {
        var formid = "form_" + access.id;
        var data = $("#" + formid).serializeJson();
        if (access.hasValidation === 1) {
            data.hasValidation = 2;
        }
        data.action = 2;
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.doAccess = function (access) {
        access.action = 2;
        access.do_access = '我干';
        ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", JSON.stringify(access), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success('确认成功~');
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error('确认失败,请稍后重试~');
            }
            ycoa.UI.block.hide();
        });
    };
    self_.cancelTr = function (access) {
        $("#tr_" + access.id).hide();
        $("#submit_" + access.id).hide();
        $("#cancel_" + access.id).hide();
    };
    self_.showAccess = function (access) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + access.id).show();
        $("#cancel_" + access.id).show();
        $("#tr_" + access.id + " input,#tr_" + access.id + " textarea").attr("disabled", "");
    };
    self_.doVal = function (access) {
        var formid = "form_" + access.id;
        var data = $("#" + formid).serializeJson();
        data.hasValidation = 1;
        data.action = 2;
        var work_kv = new Array();
        $("#" + formid + " .second_table input,#" + formid + " .second_table textarea").each(function () {
            if ($(this).attr("placeholder")) {
                work_kv.push('"' + ($(this).attr('name')) + '":"' + $(this).attr('placeholder') + '"');
            }
        });
        data.key_names = $.parseJSON("{" + work_kv.toString() + "}");
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
//    self_.reSetPresales = function (access) {
//        ycoa.UI.messageBox.confirm("确定重新分配售后~", function (btn) {
//            if (btn) {
//                access.action = 4;
//                ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", JSON.stringify(access), function (result) {
//                    if (result.code === 0) {
//                        ycoa.UI.toast.success(result.msg);
//                        reLoadData({action: 1});
//                    } else {
//                        ycoa.UI.toast.error(result.msg);
//                    }
//                    ycoa.UI.block.hide();
//                });
//            }
//        });
//    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        reLoadData({
            action: 1,
            sort: data.sort, sortname: data.sortname,
            pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(),
            searchName: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(), searchChannel: $("#searchChannel").val()
        });
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $('#searchDateTime').val('');
        $("#searchChannel").val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: name, searchTime: $('#searchDateTime').val(), searchChannel: $("#searchChannel").val(),searchStatus:$("#searchStatus").val()
        });
    }, '按关键字搜索');
    $("#dataTable").searchAutoStatus(channelSearchArray, function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(),searchStatus:$("#searchStatus").val(), searchChannel: d.id
        });
    }, "渠道搜索", "searchChannel");
    $("#dataTable").searchDateTime(function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchChannel: $("#searchChannel").val(),searchStatus:$("#searchStatus").val(),searchTime: d
        });
    }, '添加时间');
    $("#dataTable").searchAutoStatus(statusSearchArray, function (d) {
        reLoadData({
            action: 1,
            sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(),
            searchName: $("#searchUserName").val(), searchTime: $('#searchDateTime').val(),searchChannel: $("#searchChannel").val(), searchStatus: d.id
        });
    }, "状态搜索", "searchStatus");
    ko.applyBindings(AccessListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_access_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_access_form input,#add_access_form textarea").each(function () {
            if (!$(this).hasClass("not-clear")) {
                $(this).val("");
            }
        });
        $("#add_access_form input[type='checkbox']").removeAttr("checked");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".data_list_div").live("mouseenter", function () {
        var self_ = $(this);
        self_.data('lastEnter', new Date().getTime());
        setTimeout(function () {
            var t1 = new Date().getTime(), t2 = self_.data('lastEnter');
            if (t2 > 0 && t1 - t2 >= 500) {
                var w_id = self_.attr("val");
                $("#dataLog_detail_" + w_id).animate({opacity: 'show'}, 50, function () {
                    $(this).addClass("data_open");
                });
                $("#dataLog_detail_" + w_id).html("<img src='../../assets/global/img/input-spinner.gif' style='margin-top: 130px'>");
                $.get(ycoa.getNoCacheUrl("/api/sys/dataChangeLog.php"), {action: 11001, obj_id: w_id}, function (result) {
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
            }
        }, 500);
    }).live('mouseleave', function () {
        $(this).data('lastEnter', 0);
    });
    $(".data_open").live("mouseleave", function () {
        $(this).hide();
        $(this).removeClass("data_open");
    });
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        var type = $("#toexcel_form #type").val();
        if (start_time || end_time) {
            location.href = '/api/sale_soft/qq_access.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    //$("#add_access_form #channel").autoRadio(channelArray);
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    //渠道设置
    $("#channel_site_form textarea").attr("disabled",true);
    $("#channel_site_form textarea").css("backgroundColor","#F0F0F0");
    $("#channel_site_form :checkbox").live("click",function(){
        var checkbox_arr = $("#channel_site_form :checkbox"); 
        var textarea = $("#channel_site_form textarea");
        for(var i=0;i<checkbox_arr.length;i++){
            if(checkbox_arr[i].checked){
                textarea[i].disabled = false;
                textarea[i].style.backgroundColor="#FFFFFF";
            }
            else{
                textarea[i].disabled = true;
                textarea[i].style.backgroundColor="#F0F0F0";
            }
        }
        
    });
    $("#open_dialog_btn_channel_site").click(function(){
         //  $('#channelSiteModal').css("display","block");
           ycoa.ajaxLoadGet("/api/sale_soft/qq_access.php", {action: 12}, function (result){
                 var textarea = $("#channel_site_form textarea");
                 for(channel in result){                  
                     $("textarea[name='"+channel+"']").val(result[channel]);
                 }           
           });
    });
    $("#btn_channel_site_primary").click(function(){
        $("#channel_site_form").submit();
    });
});
function initEditSeleter(el) {
    $("#presales", el).click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (d, el) {
            el.val(d.name);
            $("#presales_id", el.parent("td")).val(d.id);
        });
    });
}
function reLoadData(data) {
    data.action = 1;
    AccessListViewModel.listAccess(data);
}
//var channelArray = [{id: '百度(PC)', text: '百度(PC)', default: true},{id: '百度(移动)', text: '百度(移动)'},{id: '360', text: '360'}, {id: '搜狗', text: '搜狗'},{id: 'UC', text: 'UC'},{id: '百度知道', text: '百度知道'},{id: '网盟', text: '网盟'}];
var channelSearchArray = [{id: '', text: '全部'}, {id: '百度(PC)', text: '百度(PC)'},{id: '百度(移动)', text: '百度(移动)'},{id: '360', text: '360'}, {id: '搜狗', text: '搜狗'},{id: 'UC', text: 'UC'},{id: '百度知道', text: '百度知道'},{id: '网盟', text: '网盟'},{id: '微信', text: '微信'},{id: '优化站', text: '优化站'},{id: '无渠道匹配', text: '无渠道匹配'}];
var statusSearchArray=[{id:'',text:'全部'},{id:'已接入',text:'已接入'},{id:'未接入',text:'未接入'},{id:'待验证',text:'待验证'},{id:'已验证',text:'已验证'}];

