var interval = null, url_ = "";
var AccessListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.accessList = ko.observableArray([]);
    self_.listAccess = function (data) {
        ycoa.ajaxLoadGet(url_, data, function (results) {
            self_.accessList.removeAll();
            $.each(results.list, function (index, access) {
                access.hasEnd = !(access.handle_time === null);
                access.hasval = access.hasValidation === 0 && !(access.access_time) && ycoa.user.userid() === access.presales_id;
                access.can_access = (ycoa.SESSION.PERMIT.hasPagePermitButton("3031305") || ycoa.SESSION.PERMIT.hasPagePermitButton("2011005")) && ((!access.access_time) ? true : false) && access.presales_id === ycoa.user.userid();
                access.end_class = (access.handle_time === null) ? "time_do_jump" : "";
                var startTime = (access.addtime);
                access.addtime = startTime.substring(startTime.indexOf(" "), startTime.length);
                self_.accessList.push(access);
            });
            ycoa.initPagingContainers($("#paging-container"), results, null, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
        clearInterval(interval);
        interval = setInterval(function () {
            $(".wait_time").each(function () {
                if ($(this).hasClass("time_do_jump")) {
                    $(this).text(parseInt($(this).text()) + 1);
                }
            });
        }, 1000);
    };
    self_.doAccess = function (access) {
        access.action = 2;
        access.do_access = '我干';
        ycoa.ajaxLoadPost(url_, JSON.stringify(access), function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success('确认成功~');
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo()});
            } else {
                ycoa.UI.toast.error('确认失败,请稍后重试~');
            }
            ycoa.UI.block.hide();
        });
    };
    self_.doVal = function (access) {
        var data = {id: access.id, hasValidation: 1, action: 2, do_val: "我操", key_names: {hasValidation: "是否需要QQ验证"}};
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost(url_, data, function (result) {
            if (result.code === 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo()});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    };
}();
ycoa.ajaxLoadGet("/api/center/personal_center.php", {action: 3}, function (result) {
    var is_manager = result.is_manager;
    if (!is_manager) {
        var u = result.url;
        if (u === "sale") {
            url_ = "/api/client/qqaccess_client.php";
            $(".data_type").remove();
        } else if (u === "sale_soft") {
            url_ = "/api/client/qqaccess_soft_client.php";
            $(".data_type").remove();
        } else {
            window.location.href = "../center/personal_center_client.html";
        }
    } else {
        url_ = "/api/client/qqaccess_client.php";
    }
    ko.applyBindings(AccessListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
});
$(function () {
    $(".data_type").click(function () {
        if (url_ !== $(this).attr("d")) {
            url_ = $(this).attr("d");
            $(".data_type").removeClass("d_checked");
            $(this).addClass("d_checked");
            reLoadData({});
        }
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

window.reload = function () {
    reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo()});
};