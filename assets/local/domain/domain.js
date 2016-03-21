var DomainListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.domainList = ko.observableArray([]);
    self_.listDomain = function (data) {
        ycoa.ajaxLoadGet("/api/domain/domain.php", data, function (results) {
            self_.domainList.removeAll();
            $.each(results.list, function (index, domain) {
                domain.dele = ycoa.SESSION.PERMIT.hasPagePermitButton('3040102');
                domain.edit = ycoa.SESSION.PERMIT.hasPagePermitButton('3040103');
                domain.show = ycoa.SESSION.PERMIT.hasPagePermitButton('3040104');
                domain.json = domain.name;
                self_.domainList.push(domain);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                var data = {action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()};
                reLoadData(concat(data, getSearchArray()));
            }, function (pageNo) {
                var data = {action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()};
                reLoadData(concat(data, getSearchArray()));
            });
        });
    };
    self_.delDomain = function (domain) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                domain.action = 3;
                ycoa.ajaxLoadPost("/api/domain/domain.php", JSON.stringify(domain), function (result) {
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
    self_.editDomain = function (domain) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + domain.id).show();
        $("#submit_" + domain.id).show();
        $("#cancel_" + domain.id).show();
        if (!$("#form_" + domain.id).attr('autoEditSelecter')) {
            initEditSeleter($("#form_" + domain.id));
        }
    };
    self_.cancelTr = function (domain) {
        $("#tr_" + domain.id).hide();
        $("#submit_" + domain.id).hide();
        $("#cancel_" + domain.id).hide();
    };
    self_.showDomain = function (domain) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + domain.id).show();
        $("#cancel_" + domain.id).show();
    };
}();
$(function () {
    $("#dataTable").sort(function (data) {
        var data = {action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val()};
        reLoadData(concat(data, getSearchArray()));
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
        $("#search_domain_form input").val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    }, '关键字查找');
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(DomainListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});

    $("#add_domain_form #useed,#edit_domain_form #useed,#search_domain_form #useed").autoEditSelecter(array["useed"]);
    $("#add_domain_form #isBurn,#edit_domain_form #isBurn,#search_domain_form #isBurn").autoRadio(array["isBurn"]);
    $("#add_domain_form #recordSituation,#edit_domain_form #recordSituation,#search_domain_form #recordSituation").autoEditSelecter(array["recordSituation"]);
    $("#add_domain_form #recordType,#edit_domain_form #recordType,#search_domain_form #recordType").autoRadio(array["recordType"]);
    $("#add_domain_form #category,#edit_domain_form #category,#search_domain_form #category").autoEditSelecter(array['category']);
    $("#add_domain_form #nameType,#edit_domain_form #nameType,#search_domain_form #nameType").autoEditSelecter(array['nameType']);
    $("#add_domain_form #nameBusiness,#edit_domain_form #nameBusiness,#search_domain_form #nameBusiness").autoEditSelecter(array['nameBusiness']);

    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_domain_form").submit();
    });

    $("#btn_search_primary").click(function () {
        var array = getSearchArray();
        if (array['hasVal']) {
            reLoadData(array);
            $("#btn_search_close_primary").click();
        }
    });

    $("#open_dialog_btn").click(function () {
        $("#add_domain_form input,#add_domain_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#open_dialog_btn_super_search").click(function () {
        $("#search_domain_form input").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#open_dialog_btn_edit").click(function () {
        $("#edit_domain_form input,#edit_domain_form textarea").val("");
        var names = "";
        $.each(getCheckBoxData(), function (index, d) {
            if (index > 0 && index % 3 === 0) {
                names += (d + ",\n");
            } else {
                names += (d + ",");
            }
        });
        $("#edit_domain_form #name").val(names.substr(0, names.length - 1));
    });
    $("#btn_submit_edit_primary").click(function () {
        $("#edit_domain_form").submit();
    });
    $(".domain_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        ycoa.ajaxLoadPost("/api/domain/domain.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });

    $("#btn_toexcel_primary").click(function () {
        var par = "?action=11";
        $("#search_domain_form input").each(function () {
            if ($(this).val()) {
                par += ("&" + $(this).attr("name") + "=" + $(this).val());
            }
        });
        location.href = '/api/domain/domain.php' + par;
    });

    $(".selecter_datepicker").datepicker({autoclose: true});
});
function getCheckBoxData() {
    var idList = new Array();
    $("#dataTable tbody input[type='checkbox']").each(function (node) {
        if ($(this).attr("checked")) {
            idList.push($(this).val());
        }
    });
    return idList;
}
function reLoadData(data) {
    DomainListViewModel.listDomain(data);
}

var array = {
    useed: [{'id': '百度竞价', 'text': '百度竞价'}, {'id': '360竞价', 'text': '360竞价'}, {'id': '搜狗竞价', 'text': '搜狗竞价'}, {'id': '未使用', 'text': '未使用'}, {'id': '百度品专', 'text': '百度品专'}, {'id': '360品专', 'text': '360品专'}, {'id': '搜狗品专', 'text': '搜狗品专'}],
    isBurn: [{'id': '是', 'text': '是'}, {'id': '否', 'text': '否'}],
    recordSituation: [{'id': '未备案', 'text': '未备案'}, {'id': '阿里云备案中', 'text': '阿里云备案中'}, {'id': '其他备案中', 'text': '其他备案中'}, {'id': '阿里云', 'text': '阿里云'}, {'id': '其他', 'text': '其他'}],
    recordType: [{'id': '正规', 'text': '正规'}, {'id': '快速', 'text': '快速'}],
    category: [{'id': '总表', 'text': '总表'}, {'id': '星密码', 'text': '星密码'}, {'id': '店宝宝', 'text': '店宝宝'}, {'id': 'E品优程', 'text': 'E品优程'}, {'id': '商通达', 'text': '商通达'}, {'id': '创想范', 'text': '创想范'}, {'id': '心主题', 'text': '心主题'}, {'id': '其他', 'text': '其他'}],
    nameType: [{'id': '.cn', 'text': '.cn'}, {'id': '.com', 'text': '.com'}, {'id': '.net', 'text': '.net'}, {'id': '.biz', 'text': '.biz'}, {'id': '.org', 'text': '.org'}, {'id': '.ren', 'text': '.ren'}, {'id': '.wang', 'text': '.wang'}, {'id': '.中国', 'text': '.中国'}, {'id': '.公司/网络', 'text': '.公司/网络'}],
    nameBusiness: [{'id': '万网', 'text': '万网'}, {'id': '西部数码', 'text': '西部数码'}, {'id': 'qqau.com', 'text': 'qqua.com'}]
};

function initEditSeleter(el) {
    $("#useed", el).autoEditSelecter(array["useed"]);
    $("#isBurn", el).autoRadio(array["isBurn"]);
    $("#recordSituation", el).autoEditSelecter(array["recordSituation"]);
    $("#recordType", el).autoRadio(array["recordType"]);
    $("#category", el).autoEditSelecter(array['category']);
    $("#nameType", el).autoEditSelecter(array['nameType']);
    $("#nameBusiness", el).autoEditSelecter(array['nameBusiness']);
    $("#dueDate", el).datepicker({autoclose: true});
    el.attr('autoEditSelecter', 'autoEditSelecter');
}

function getSearchArray() {
    var array = {action: 1, hasVal: false};
    $("#search_domain_form input").each(function () {
        if ($(this).val()) {
            array[$(this).attr("name")] = $(this).val();
            array['hasVal'] = true;
        }
    });
    return array;
}

function  concat(a, b) {
    if (a && b) {
        var c = JSON.stringify(a).replace("}", ",") + (JSON.stringify(b).replace("{", ""));
        return $.parseJSON(c);
    }
    return {};
}