var RepertoryListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.repertoryList = ko.observableArray([]);
    self_.listRepertory = function (data) {
        ycoa.ajaxLoadGet("/api/cost/repertory.php", data, function (results) {
            self_.repertoryList.removeAll();
            $.each(results.list, function (index, repertory) {
                self_.repertoryList.push(repertory);
            });
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), searchName: $("#searchUserName").val()});
            });
        });
    };
    self_.cancelTr = function (repertory) {
        $("#tr_" + repertory.id).hide();
    };
}();
$(function () {
    ko.applyBindings(RepertoryListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $("#searchUserName").val('');
    });
    $("#dataTable").searchUserName(function (d) {
        reLoadData({sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName(), deptid: $("#deptid").val(), pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: d});
    }, '按物品名称查找');
});
function updateRepertory(repertory) {
    repertory.action = 4;
    ycoa.ajaxLoadPost("/api/cost/repertory.php", JSON.stringify(repertory), function (result) {
        if (result.code == 0) {
            ycoa.UI.toast.success(repertory.msg + "成功~");
            reLoadData({});
        } else {
            ycoa.UI.toast.error(repertory.msg + "失败~");
        }
        ycoa.UI.block.hide();
    });
}
function reLoadData(data) {
    RepertoryListViewModel.listRepertory(data);
}