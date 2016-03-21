var phoneSysListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.phoneSysList = ko.observableArray([]);
    self_.listPhoneSys = function (data) {
        ycoa.ajaxLoadGet("/api/sale/phone_sys.php", data, function (results) {
            self_.phoneSysList.removeAll();
            $.each(results.list, function (index, phoneSys) {
                phoneSys.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("2011302");
                phoneSys.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("2011303");
                phoneSys.show = ycoa.SESSION.PERMIT.hasPagePermitButton("2011304");
                self_.phoneSysList.push(phoneSys);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchName").val()});
            }, function (pageNo) {
                reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchName").val()});
            });
        });
    };
    self_.delPhoneSys = function (phoneSys) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                phoneSys.action = 2;
                ycoa.ajaxLoadPost("/api/sale/phone_sys.php", JSON.stringify(phoneSys), function (result) {
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
    self_.doEditPhoneSys = function (phoneSys) {
        var formid = "form_" + phoneSys.id;
        var data = $("#" + formid).serializeJson();
        data.action = 3;
        ycoa.ajaxLoadPost("/api/sale/phone_sys.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
    self_.editPhoneSys = function (phoneSys) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + phoneSys.id).show();
        $("#submit_" + phoneSys.id).show();
        $("#cancel_" + phoneSys.id).show();
        if (!$("#form_" + phoneSys.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + phoneSys.id));
        }
        $("#tr_" + phoneSys.id + " input,#tr_" + phoneSys.id + " textarea").removeAttr("disabled");
    };
    self_.cancelTr = function (phoneSys) {
        $("#tr_" + phoneSys.id).hide();
        $("#submit_" + phoneSys.id).hide();
        $("#cancel_" + phoneSys.id).hide();
    };
    self_.showPhoneSys = function (phoneSys) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + phoneSys.id).show();
        $("#submit_" + phoneSys.id).hide();
        $("#cancel_" + phoneSys.id).show();
        if (!$("#form_" + phoneSys.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + phoneSys.id));
        }
        $("#tr_" + phoneSys.id + " input,#tr_" + phoneSys.id + " textarea").attr("disabled", "");
    };
}();
$(function () {
    var city_html = "<option value='请选择'>请选择</option>";
    $.each(city_area_array, function (index, d) {
        city_html += "<option value='" + index + "'>" + (d.name) + "</option>";
    });
    $("#add_phone_sys_form #city").html(city_html);

    $("#add_phone_sys_form #city").change(function () {
        var that = $(this);
        if (that.val() != "请选择") {
            var area_html = "<option value='请选择'>请选择</option>";
            $.each(city_area_array[that.val()].area, function (i, d) {
                area_html += "<option value='" + (d.name) + "'>" + (d.name) + "</option>";
            });
            $("#add_phone_sys_form #area").html(area_html);
        } else {
            $("#add_phone_sys_form #area").html("<option value='请选择'>请选择</option>");
        }
    });
    ko.applyBindings(phoneSysListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchName').val('');
    });

    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: name});
    }, '按关键字查找', 'searchName');

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
            location.href = '/api/sale/phone_sys.php?start_time=' + start_time + '&end_time=' + end_time + '&action=11';
        }
    });
    $("#add_phone_sys_form #bind_shop").autoEditSelecter(array['bind_shop']);
    $("#add_phone_sys_form #use_username").autoRadio(array['use_username']);
    $("#add_phone_sys_form #used").autoRadio(array['used']);
    $("#add_phone_sys_form #is_arrearage").autoRadio(array['is_arrearage']);
});
function reLoadData(data) {
    data.action = 1;
    phoneSysListViewModel.listPhoneSys(data);
}
function initEditSeleter(el) {
    $("#bind_shop", el).autoEditSelecter(array['bind_shop']);
    $("#use_username", el).autoRadio(array['use_username']);
    $("#used", el).autoRadio(array['used']);
    $("#is_arrearage", el).autoRadio(array['is_arrearage']);
    $("#city", el).autoEditSelecter(function () {
        var arr = new Array();
        $.each(city_area_array, function (index, d) {
            arr.push({id: index, text: d.name});
        });
        return arr;
    }(), function (d) {
        var area_array = city_area_array[d.id].area;
        var ul_el = $("#area", el).parent("div").find("div").find("ul");
        var d_ = $(ul_el.find("li")[0]).attr('d');
        ul_el.empty();
        var html = "";
        console.log(area_array);
        $.each(area_array, function (index, a) {
            if (index == 0) {
                $("#area", el).val(a.name);
            }
            html += "<li rel='0' class='selected' v='" + (a.name) + "' t='" + (a.name) + "' d='" + d_ + "'><a tabindex='0' class='' style=''><span class='text'>" + (a.name) + "</span><i class='fa fa-check icon-ok check-mark'></i></a></li>";
        });
        ul_el.html(html);
    });
    var city_val = $("#city", el).val();
    if (city_val) {
        $("#area", el).autoEditSelecter(function () {
            var arr = new Array();
            $.each(city_area_array, function (i, d) {
                if ((d.name) === city_val) {
                    $.each(d.area, function (i, date) {
                        arr.push({id: date.name, text: date.name});
                    });
                    return;
                }
            });
            return arr;
        }());
    }
    el.attr('autoEditSelecter', 'autoEditSelecter');
}
var array = {
    use_username: [{id: '售前', text: '售前'}, {id: '售后', text: '售后'}, {id: '商务通', text: '商务通'}, {id: '主管', text: '主管'}, {id: '经理', text: '经理'}],
    used: [{id: '售前', text: '售前'}, {id: '售后', text: '售后'}, {id: '商务通', text: '商务通'}],
    is_arrearage: [{id: '否', text: '否'}, {id: '是', text: '是'}],
    bind_shop: [{id: '中国e商城', text: '中国e商城'}, {id: 'jinricardtb001', text: 'jinricardtb001'}, {id: '伤心的rain', text: '伤心的rain'}, {id: '非凡e品', text: '非凡e品'}, {id: '开店软件总经销', text: '开店软件总经销'}, {id: '闽州e商城', text: '闽州e商城'}, {id: 'i淘意不绝i', text: 'i淘意不绝i'}]
};