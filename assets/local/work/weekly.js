var WeeklyListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.weeklyList = ko.observableArray([]);
    self_.listWeekly = function (data) {
        ycoa.ajaxLoadGet("/api/work/weekly.php", data, function (results) {
            self_.weeklyList.removeAll();
            $.each(results.list, function (index, weekly) {
                //weekly.addtime = new Date(weekly.addtime).format("yyyy-MM-dd");
                weekly.dele = ycoa.SESSION.PERMIT.hasPagePermitButton("1030302");
                weekly.edit = ycoa.SESSION.PERMIT.hasPagePermitButton("1030302");
                weekly.show = ycoa.SESSION.PERMIT.hasPagePermitButton("1030304");
                self_.weeklyList.push(weekly);
            });
            ycoa.SESSION.PAGE.setPageNo(results.page_no);
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), status: $("#status").val(), searchName: $("#searchUserName").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.delWeekly = function (weekly) {
        ycoa.UI.messageBox.confirm('确认删除？', function (del) {
            if (del) {
                weekly.action = 3;
                ycoa.ajaxLoadPost("/api/work/weekly.php", JSON.stringify(weekly), function (result) {
                    if (result.code == 0) {
                        ycoa.UI.toast.success(result.msg);
                        WeeklyListViewModel.weeklyList.remove(weekly);
                        reLoadDate();
                    } else {
                        ycoa.UI.toast.error(result.msg);
                    }
                    ycoa.UI.block.hide();
                });

            }
        });
    };
    self_.editWeekly = function (weekly) {
        $(".second_tr").hide();
        $(".submit_btn").hide();
        $(".cancel_btn").hide();
        $("#tr_" + weekly.id).show();
        $("#submit_" + weekly.id).show();
        $("#cancel_" + weekly.id).show();
        $("#form_"+ weekly.id +" #matter").removeAttr("readonly");
    };
    self_.cancelTr = function (weekly) {
        $("#tr_" + weekly.id).hide();
        $("#submit_" + weekly.id).hide();
        $("#cancel_" + weekly.id).hide();
    };
    self_.showWeekly = function (weekly) {
//            $(".second_tr").hide();
//            $(".submit_btn").hide();
//            $(".cancel_btn").hide();
//            $("#tr_" + weekly.id).show();
//            $("#cancel_" + weekly.id).show();
            $("#myzhiModal #matter").val(weekly.matter);
            $("#myzhiModal #matter").attr("readonly","readonly");
    };
}();

$(function () {
  
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, sort: data.sort, sortname: data.sortname, pagesize: ycoa.SESSION.PAGE.getPageSize(), deptid: $('#deptid').val(), searchName: $("#searchUserName").val(), status: $("#status").val()});
    });
    $("#dataTable").reLoad(function () {
        reLoadData({action: 1});
        $('#searchUserName').val('');
    });
    $("#dataTable").searchDept(function (id) {
        reLoadData({action: 1, deptid: id});
    });
    $("#dataTable").searchUserName(function (name) {
        reLoadData({action: 1, searchName: name});
    });
    $(".date-picker-bind-mouseover").live("mouseover", function () {
        $(this).datepicker({autoclose: true});
    });
    ko.applyBindings(WeeklyListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable thead input[id='checkall']").change(function () {
        if ($(this).attr("checked")) {
            $("#dataTable tbody input[type='checkbox']").attr("checked", "checked");
        } else {
            $("#dataTable tbody input[type='checkbox']").removeAttr("checked");
        }
    });
    $("#add_weekly_form #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $("#add_weekly_form #userid").val(data.id);
        });
    });
    $(".second_table #username").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $(".second_table #userid").val(data.id);
        });
    });
    $("#btn_submit_primary").click(function () {
        $("#add_weekly_form").submit();
    });
    $("#open_dialog_btn").click(function () {
        $("#add_weekly_form input[type='text'],#add_weekly_form input[type='hidden'], #add_weekly_form textarea").val("");
        $(".has-error,.has-success").each(function () {
            $(this).removeClass("has-error").removeClass("has-success");
        });
        $(".fa-warning,.fa-check").each(function () {
            $(this).removeClass("fa-warning").removeClass("fa-check");
        });
    });
    $("#btn_toexcel_primary").live("click", function () {
        var num = ycoa.UI.checkBoxVal();
        var start_time = $("#toexcel_form #start_time").val();
        var end_time = $("#toexcel_form #end_time").val();
        if (!num)
            num = 0;
        if (!start_time)
            start_time = 0;
        if (!end_time)
            end_time = 0;
        //var data = $("#toexcel_form #start_time").serializeJson();
        //data = JSON.stringify(data);
        //alert(data);
        //var start_time=data[0]["start_time"];
        //var end_time=data[0]["end_time"];
        //str = '/api/work/excelout.php?num='+num+'&start_time='+start_time+'&end_time='+end_time+'';
        location.href = '/api/work/excelout.php?num=' + num + '&start_time=' + start_time + '&end_time=' + end_time + '';

        //ycoa.ajaxLoadPost("/api/work/excelout.php",data,function(result){
        //if(result.code == 0){
        //    ycoa.UI.toast.success(result.msg);
        //    $('.reload').click();
        //}else{
        //    ycoa.UI.toast.error(result.msg);
        //}
        //ycoa.UI.block.hide();
        //});

    });
    $(".dept_submit_btn").live("click", function () {
        var formid = "form_" + $(this).attr("val");
        var data = $("#" + formid).serializeJson();
        data.action = 2;
        data = JSON.stringify(data);
        ycoa.ajaxLoadPost("/api/work/weekly.php", data, function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({});
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    });
    $('.popovers').popover();
});
function reLoadData(data) {
    WeeklyListViewModel.listWeekly(data);
}