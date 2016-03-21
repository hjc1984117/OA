var FillarrearsListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.fillarrearsList = ko.observableArray([]);
    self_.listFillarrears = function (data) {
        ycoa.ajaxLoadGet("/api/second_sale_soft/fillarrears.php", data, function (results) {
            self_.fillarrearsList.removeAll();
            customer_list = results.customer_list;
            current_Date = results.current_date;
            $.each(results.list, function (index, fillarrears) {
                fillarrears.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("3060602")  && ( ycoa.user.userid() === fillarrears.customer_id || fillarrears.is_manager === true);
               // fillarrears.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("3060603");
                fillarrears.edit = function () {//售后一般人员在审核后没有修改权限;
                    if (fillarrears.type === 1) {
                        
                        return ycoa.SESSION.PERMIT.hasPagePermitButton("3060603")  && ( (ycoa.user.userid() === fillarrears.customer_id && fillarrears.is_edit === 0) || fillarrears.is_manager === true);
                    } else {
                       
                        return ycoa.SESSION.PERMIT.hasPagePermitButton("3060603")  && ( (fillarrears.isTe === 0 && ycoa.user.userid() === fillarrears.customer_id && fillarrears.is_edit === 0) || fillarrears.is_manager === true);
                    }
                }();
                fillarrears.show = ycoa.SESSION.PERMIT.hasPagePermitButton("3060604");
                fillarrears.te = ycoa.SESSION.PERMIT.hasPagePermitButton('3060606') && fillarrears.isTe === 0 && fillarrears.is_manager && fillarrears.type > 1;
                self_.fillarrearsList.push(fillarrears);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val()});
            });
        });
    };
    self_.delFillarrears = function (fillarrears) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                fillarrears.action = 3;
                ycoa.ajaxLoadPost("/api/second_sale_soft/fillarrears.php", JSON.stringify(fillarrears), function (result) {
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
    self_.editFillarrears = function (fillarrears) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + fillarrears.id).show();
        $("#submit_" + fillarrears.id).show();
        $("#cancel_" + fillarrears.id).show();
        if (!$("#form_" + fillarrears.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + fillarrears.id), fillarrears.type);
        }
        $("#tr_" + fillarrears.id + " input,#tr_" + fillarrears.id + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (fillarrears) {
        $("#tr_" + fillarrears.id).hide();
        $("#submit_" + fillarrears.id).hide();
        $("#cancel_" + fillarrears.id).hide();
    };
    self_.showFillarrears = function (fillarrears) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + fillarrears.id).show();
        $("#cancel_" + fillarrears.id).show();
        if (!$("#form_" + fillarrears.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + fillarrears.id), fillarrears.type);
        }
        $("#tr_" + fillarrears.id + " input,#tr_" + fillarrears.id + " textarea").attr("disabled", "");
    };
     self_.doTe = function (fillarrears) {
        fillarrears.isTe = 1;
        fillarrears.action = 2;
        ycoa.ajaxLoadPost("/api/second_sale_soft/fillarrears.php", JSON.stringify(fillarrears), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success("操作成功~");
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error("操作失败~");
            }
            ycoa.UI.block.hide();
        });
    };
}();
$(function () {
    ko.applyBindings(FillarrearsListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchName: name, searchType: $("#searchType").val()});
    });
    $("#dataTable").searchAutoStatus([{id: '', text: '全部'}, {id: 1, text: '流量业绩'}, {id: 2, text: '实物业绩'}, {id: 3, text: '装修业绩'}, {id: 4, text: '代运营'}], function (d) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pagesize: ycoa.SESSION.PAGE.getPageSize(), pageno: ycoa.SESSION.PAGE.getPageNo(), searchName: $("#searchUserName").val(), searchType: d.id});
        $("#searchType").val(d.id);
    }, '按类型筛选');
    $(".date-picker-bind-mouseover").datepicker({autoclose: true});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_addfill_primary").click(function () {
        $("#add_fill_form").submit();
    });
    $("#start_time,#end_time").val(new Date().format("yyyy-MM-dd"));
    $("#btn_toexcel_primary").live("click", function () {
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (start_time || end_time) {
            location.href = '/api/second_sale_soft/fillarrears.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data.remark = $("#" + formid + " #remark").html();
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/second_sale_soft/fillarrears.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                $('.cancel_btn').click();
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
});
function reLoadData(data) {
    data.action = 1;
    FillarrearsListViewModel.listFillarrears(data);
}
function updateCL(fillarrears) {
    fillarrears.action = 2;
    ycoa.ajaxLoadPost("/api/second_sale_soft/fillarrears.php", JSON.stringify(fillarrears), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success("操作成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}
;

function initEditSeleter(el, type) {
    el.attr('autoEditSelecter', 'autoEditSelecter');
    $("#payment_method", el).autoEditSelecter(array['pm' + type]);
    //只有指定的管理员能补录数据
    var api = "/api/second_sale_soft/platform.php";
    switch (type) {
        case 1:
            api = "/api/second_sale_soft/platform.php";
            break;
        case 2:
            api = "/api/second_sale_soft/physica.php";
            break;
        case 3:
            api = "/api/second_sale_soft/decoration.php";
            break;
    }
    ycoa.ajaxLoadGet(api, {action: 21}, function (result) {
        is_manager = result.is_manager;
        if (result.is_manager) {
            $(".date-time-picker-bind-mouseover", el).datetimepicker({autoclose: true});
            $("#customer", el).click(function () {
                ycoa.UI.empSeleter({el: $(this), type: 'only', groupId: [11]}, function (data, el) {
                    el.val(data.name);
                    $("#customer_id", el.parent()).val(data.id);
                });
            });
        }
    });
    $("#remark", el).pasteImgEvent();
}
var array = {
    pm1: function () {
        var val = "llw8988@163.com(周峰),zhouslowly@foxmail.com(周峰),286878263@qq.com(周峰),kllpay@126.com(王远敏),kellpay@163.com（王远敏）,lingfanllvip@163.com（刘博）,中国银行（王远敏）,邮政银行（王远敏）,交通银行（王远敏）,工商银行（王远敏）,建设银行（王远敏）,农业银行（王远敏）,农村信用社（王远敏）,店铺/掌柜：飞凰品牌,微信llw8988（王远敏）,微信:fsxwxsk（王远敏）,微信:fsxskzy（王远敏）,财付通:2405658931（王远敏）";
        val = val.split(",");
        var array = new Array();
        $.each(val, function (i, d) {
            array.push({id: d, text: d});
        });
        return array;
    }(),
    pm2: function () {
        var val = "chuangshouk@163.com(代桂莲),chuangxiangshou@163.com(代桂莲),thydx1@163.com(代桂莲),thydx2@163.com(吴雨燕),thydx3@163.com(吴雨燕),chuangshouru@163.com(朱红静),chuangshour@163.com(朱红静),邮政银行(代桂莲),建设银行(代桂莲),工商银行(代桂莲),邮政银行(吴雨燕),中国银行(吴雨燕),农业银行(吴雨燕),农村信用社(代桂莲),农村信用社(吴雨燕),微信:dailishoukuan,信用卡:棒棒糖亲子情侣馆,财付通：809834236(代桂莲)";
        val = val.split(",");
        var array = new Array();
        $.each(val, function (i, d) {
            array.push({id: d, text: d});
        });
        return array;
    }(),
    pm3: function () {
        var val = "支付宝：wypexsk1@qq.com(余静),支付宝：wypexsk@qq.com (郎根),邮政银行(余静),建设银行(余静),工商银行(余静),中国银行(余静),农商银行(余静),农业银行(余静),信用卡链接:美肤皇后1,信用卡链接:玻璃巷子,微信:wypexsk(余静),财付通：3183812620 (余静)";
        val = val.split(",");
        var array = new Array();
        $.each(val, function (i, d) {
            array.push({id: d, text: d});
        });
        return array;
    }()
};