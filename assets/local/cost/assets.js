var AssetsViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.assetsList = ko.observableArray([]);
    self_.listAssets = function (data) {
        ycoa.ajaxLoadGet("/api/cost/assets.php", data, function (results) {
            self_.assetsList.removeAll();
            $.each(results.list, function (index, assets) {
                assets.whether_use = assets.whether_use.toString();
                assets.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1040402");
                assets.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1040403");
                ;
                assets.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1040404");
                ;
                self_.assetsList.push(assets);
            });
            $("#page_no").val(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delAssets = function (assets) {
        ycoa.UI.messageBox.confirm("确定要删除该条资产信息吗?~", function (btn) {
            if (btn) {
                assets.action = 3;
                ycoa.ajaxLoadPost('/api/cost/assets.php', JSON.stringify(assets), function (result) {
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
    self_.editAssets = function (assets) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + assets.id).show();
        $("#submit_" + assets.id).show();
        $("#cancel_" + assets.id).show();
    };
    self_.cancelTr = function (assets) {
        $("#tr_" + assets.id).hide();
        $("#submit_" + assets.d).hide();
        $("#cancel_" + assets.id).hide();
    };
    self_.showAssets = function (assets) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + assets.id).show();
        $("#cancel_" + assets.id).show();
    };
}();
$(function () {
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(AssetsViewModel, $("#dataTable")[0]);
    reLoadData({});
    $("#dataTable").sort(function (data) {
        reLoadData({sort: data.sort, sortname: data.sortname, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({});
        $('#deptid').val('');
        $('#status').val('');
        $('#searchUserName').val('');
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({searchName: name});
    });
    $("#add_assets_form #username").click(function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (node, el) {
            el.val(node.name);
            $("#userid").val(node.id);
        });
    });
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $(".btn_empployee_quit").click(function () {
        if (ycoa.UI.checkBoxVal()) {
            bootbox.confirm("确定将该员工设置为'离职'状态吗?", function (result) {
                if (result) {
                    reLoadData({action: 11, assetsids: ycoa.UI.checkBoxVal(), pageno: $('#page_no').val()});
                }
            });
        }
    });
    $("#btn_submit_primary").click(function () {
        $("#add_assets_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_assets_form input[type='text'],#add_assets_form input[type='hidden'], #add_assets_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $(".assets_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/cost/assets.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $("#myModal #name,#dataTable #name").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
        });
    });
    $("#myModal #dept2_text,#dataTable #dept2_text").live("click", function () {
        ycoa.UI.deptDropDownTree($(this), function (data, el) {
            el.val(data.text);
            $("#dept2_id", el.parent()).val(data.id);
            $("#dept1_id", el.parent()).val(data.parents.length > 1 ? data.parent : data.id);
        });
    });
    $('.popovers').popover();
});

function reLoadData(data) {
    AssetsViewModel.listAssets(data);
}