var is_set = false;
var TaskListViewModel = new function () {
    var self_ = this;
    self_.list = ko.observable("list");
    self_.taskList = ko.observableArray([]);
    self_.listTask = function (data) {
        
        ycoa.ajaxLoadGet("/api/work/task.php?action="+$("#task_action_deliver").attr("tmp_value"), data, function (results) {

            self_.taskList.removeAll();
            $.each(results.list, function (index, task) {
                task.submit = (task.status === 0 || task.status === 4 || task.status === 6) && (ycoa.user.userid() === task.addUserId);
                task.start = (task.status === 1) && (ycoa.user.userid() === task.takeOverId);
                task.complete = (task.status === 2) && (ycoa.user.userid() === task.takeOverId);
                task.reject = (task.status === 1) && (ycoa.user.userid() === task.takeOverId);
                task.stop = (task.status === 1 || task.status === 2) && (ycoa.user.userid() === task.addUserId);
                task.del = (task.status === 5) && (ycoa.user.userid() === task.addUserId);
                task.reStart = (task.status === -1 || task.status === 3 || task.status === 5) && (ycoa.user.userid() === task.addUserId);
                self_.taskList.push(task);
            });
            ycoa.initPagingContainers($("#paging-container"), results, function (pageSize) {
                reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: pageSize, searchName: $("#searchUserName").val(), searchType: $("#searchType").val(), searchStatus: $("#searchStatus").val(), searchTime: $("#searchDateTime").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            }, function (pageNo) {
                reLoadData({action: 1, pageno: pageNo, pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val(), searchStatus: $("#searchStatus").val(), searchTime: $("#searchDateTime").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()});
            });
        });
    };
    self_.showTaskDetails = function (task) {
        if (!is_set) {
            UE.getEditor('content').setHeight(140);
            is_set = true;
        }
        UE.getEditor('content').setContent('', false);
        change_btn_send_task_status(task.status);
        ycoa.ajaxLoadGet("/api/work/task.php", {action: 2, task_id: task.id}, function (result) {
            var height = $(window).height() - 203;
            $(".div_task_content").height(height);
            $(".div_task_content_body").height(height - (200 + 60));
            $(".div_task_content").show();
            if (result.code === 0) {
                $("#task_id").val(task.id);
                $("img", $(".div_task_content_head")).attr("src", result.avatar);
                change_task_status(result.task)
                var task_html = "";
                $.each(result.list, function (index, d) {
                    if (d.addUserId === (ycoa.user.userid())) {
                        task_html += "<div class = 'rightd'><div class = 'rightimg'>";
                        if (d.addUserId === 1) {
                            task_html += "[<span style='color: rgb(247, 150, 70);'>系统</span>]";
                        } else {
                            task_html += d.addUserName;
                        }
                        task_html += "</div><div class='speech right' ><div>" + (d.content) + "</div><div class='task_time'>" + getDate(result.c_date, d.addTime) + "</div></div></div>";
                    } else {
                        task_html += "<div class = 'leftd'> <div class = 'leftimg'>";
                        if (d.addUserId === 1) {
                            task_html += "[<span style='color: rgb(247, 150, 70);'>系统</span>]";
                        } else {
                            task_html += d.addUserName;
                        }
                        task_html += "</div><div class='speech left'><div>" + (d.content) + "</div><div class='task_time'>" + getDate(result.c_date, d.addTime) + "</div></div></div>";
                    }
                });
                $(".div_task_content_body").html(task_html);
            } else {
                ycoa.UI.toast.warning("获取详情信息失败,请稍后重试~");
            }
        });
        $("#dataTable tbody tr").removeClass("tr_checked");
        $("#task_tr_" + task.id).addClass("tr_checked");
    };
    self_.submitTask = function (task) {
        task.status = 1;
        changeTask(task);
    };
    self_.startTask = function (task) {
        task.status = 2;
        changeTask(task);
    };
    self_.completeTask = function (task) {
        task.status = 3;
        changeTask(task);
    };
    self_.rejectTask = function (task) {
        task.status = 4;
        changeTask(task);
    };
    self_.stopTask = function (task) {
        task.status = 5;
        changeTask(task);
    };
    self_.reStartTask = function (task) {
        task.status = 6;
        changeTask(task);
    };
    self_.deleteTask = function (task) {
        ycoa.UI.messageBox.confirm("确定要删除该条任务信息吗?~", function (btn) {
            if (btn) {
                task.status = -1;
                changeTask(task);
                $(".div_task_content").hide();
            }
        });
    };
}();
$(function () {
    ko.applyBindings(TaskListViewModel, $("#dataTable")[0]);
    reLoadData({action: 1});
    $("#dataTable").sort(function (data) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val(), searchStatus: $("#searchStatus").val(), searchTime: $("#searchDateTime").val(), sort: data.sort, sortname: data.sortname});
    });
    $(".form-body").reLoad(function () {
        reLoadData({action: 1});
        $("#searchType").val("");
        $("#searchStatus").val("");
        $("#searchUserName").val("");
        $("#searchDateTime").val("");
    });
    $(".form-body").searchAutoStatus(array['type'], function (d) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchType: d.id, searchStatus: $("#searchStatus").val(), searchTime: $("#searchDateTime").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()})
        $("#searchType").val(d.id);
    }, '按类型搜索');
    $(".form-body").searchAutoStatus(array['status'], function (d) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val(), searchStatus: d.id, searchTime: $("#searchDateTime").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()})
        $("#searchStatus").val(d.id);
    }, '按状态搜索');
    $(".form-body").searchUserName(function (d) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: d, searchType: $("#searchType").val(), searchStatus: $("#searchStatus").val(), searchTime: $("#searchDateTime").val(), sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()})
        $("#searchUserName").val(d);
    }, '按关键字搜索');
    $(".form-body").searchDateTime(function (d) {
        reLoadData({action: 1, pageno: ycoa.SESSION.PAGE.getPageNo(), pagesize: ycoa.SESSION.PAGE.getPageSize(), searchName: $("#searchUserName").val(), searchType: $("#searchType").val(), searchStatus: $("#searchStatus").val(), searchTime: d, sort: ycoa.SESSION.SORT.getSort(), sortname: ycoa.SESSION.SORT.getSortName()})
        $("#searchDateTime").val(d);
    });
    $("#add_task_btn").click(function () {
       // window.open("../../page/work/task_add.html");
       
    });
    $("#btn_send_task").click(function () {
        var taskId = $("#task_id").val();
        if (UE.getEditor('content').hasContents()) {
            if (taskId) {
                var content = UE.getEditor('content').getContent();
                ycoa.ajaxLoadPost("/api/work/task.php", JSON.stringify({action: 2, content: content, taskId: taskId}), function (result) {
                    if (result.code === 0) {
                        var task_html = "<div class = 'rightd'><div class = 'rightimg'>";
                        if (result.addUserId === 1) {
                            task_html += "[<span style='color: rgb(247, 150, 70);'>系统</span>]";
                        } else {
                            task_html += result.addUserName;
                        }
                        task_html += "</div><div class='speech right' ><div>" + (result.content) + "</div><div class='task_time'>" + getDate(result.c_date, result.addTime) + "</div></div></div>";
                        $(".div_task_content_body").append(task_html);
                        UE.getEditor('content').setContent('', false);
                    } else {
                        ycoa.UI.toast.warning(result.msg);
                    }
                });
            } else {
                ycoa.UI.toast.warning("获取任务信息失败,请重新选择~");
            }
        } else {
            ycoa.UI.toast.warning("任务详情内容不可以是空的哦~");
        }
    });
    $(".div_task_content_body").bind('DOMNodeInserted', function (e) {
        $('.div_task_content_body').scrollTop(100000);
    });

    $(".rightd img,.leftd img").live("click", function () {
        $(".img_view").css({left: '', top: ''});
        var src = $(this).attr("src");
        $(".photo_layer").height($(window).height()).show();
        $(".photo_layer_body").css("line-height", $(window).height() - 85 + "px").height($(window).height() - 41);
        $('body').css('overflow-y', 'hidden');
        if (src.indexOf('../../upload_avatar/talk/O_') !== -1) {
            src = src.replace("../../upload_avatar/talk/", "../../upload_avatar/talk/ori/");
        }
        $(".img_view").css("width", "").attr("src", src);
        $(".photo_layer").attr("old_width", 0);
        $(".img_size_progress_outer").css("left", (($(window).width() - 1052) / 2) + 'px');
        $(".img_size_progress_btn").css("left", '500px');
    });
    $(".photo_layer").mousewheel(function (event, delta, deltaX, deltaY) {
        if (!$(".photo_layer").is(":hidden")) {
            if ($(".photo_layer").attr("old_width") === "0") {
                $(".photo_layer").attr("old_width", $(".img_view").width());
            }
            var left = Number($(".img_size_progress_btn").css("left").replace("px", ""));
            if (delta === 1) {
                if (left < 1000) {
                    $(".img_size_progress_btn").css("left", (left + 10) + 'px');
                }
            } else if (delta === -1) {
                if (left > 0) {
                    $(".img_size_progress_btn").css("left", (left - 10) + 'px');
                }
            }
            var x = Number($(".img_size_progress_btn").css("left").replace("px", ""));
            var old_width = $(".photo_layer").attr("old_width");
            if (x === 500) {
                $(".img_view").width(old_width);
            } else if (x < 500) {
                $(".img_view").width(old_width * ((x * 2) / 1000));
            } else if (x > 500) {
                $(".img_view").width(Number(old_width) + (old_width * ((x - 500) * 2 / 1000)));
            }
        }
    });
    $(".img_view").draggable();
    $('.img_size_progress_btn').draggable({
        axis: 'x',
        containment: 'parent',
        start: function (event, ui) {
            if ($(".photo_layer").attr("old_width") === "0") {
                $(".photo_layer").attr("old_width", $(".img_view").width());
            }
        },
        drag: function (event, ui) {
            var x = ui.position.left;
            var old_width = $(".photo_layer").attr("old_width");
            if (x === 500) {
                $(".img_view").width(old_width);
            } else if (x < 500) {
                $(".img_view").width(old_width * ((x * 2) / 1000));
            } else if (x > 500) {
                $(".img_view").width(Number(old_width) + (old_width * ((x - 500) * 2 / 1000)));
            }
        }
    });
    $('body').keyup(function (e) {
        if (e.keyCode === 27) {
            $(".photo_layer_close").click();
        }
    });
    $(".photo_layer_close").click(function () {
        $('body').css('overflow-y', 'auto');
        $(".photo_layer").hide();
    });
    UE.getEditor('content');
    
    //选项卡
    $("#task_status div").click(function(){
        var self = $(this);
        $("#task_action_deliver").attr("tmp_value",self.attr("action"));
      
      $("#task_status div").each(function(index, element) {
        $(this).css("background-color","#E26A6A");
    });
       self.css("background-color","#3D3D3D");
        TaskListViewModel.listTask();
    });
    $("#task_status div").mouseover(function () {
          var self = $(this);
         self.css("background-color","#3D3D3D");
         self.css("cursor","hand");
     });
      $("#task_status div").mouseout(function () {
      
          var self = $(this);
          if($("#task_action_deliver").attr("tmp_value")!=self.attr("action"))
          {
               self.css("background-color","#E26A6A");
          }
     });
     //新增任务
      $("#dept1_text").live("click", function () {
        var self_ = this;
        var selected_dept_id;
        ycoa.UI.deptDropDownTree($(self_), function (node, el) {
            el.attr("value", node.text);
            el.parent().find("#dept2_id").val(node.id);
            if (node.parent == "#") {
                el.parent().find("#dept1_id").val(node.id);
            } else {
                el.parent().find("#dept1_id").val(node.parent);
            }
            selected_dept_id=node.id;
        }, false, {hasZHB: true});
        return selected_dept_id;
    });
    $("#takeOverName").live("click", function () {
        ycoa.UI.empSeleter({el: $(this), type: 'only'}, function (data, el) {
            el.val(data.name);
            $("#TakeOverId", el.parent()).val(data.id);
            //制定化需求，选择人员后附加上部门信息，去掉单独填写部门的功能
            ycoa.ajaxLoadGet("/api/work/task.php?action=0",{user_id:$("#TakeOverId").val()} , function (result) {
               $("#takeOverName").val(data.name+"["+result.dept+"]");
               $("#dept1_id").val(result.dept_id);
            });
        });
    });
    //验证后AJAX
    var content = UE.getEditor('content');
    $("#btn_submit_add_task").click(function () {
        var data = $("#add_task_form").serializeJson();
        if (data.title === "") {
            ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>任务标题</div>不能为空哦~");
        } else if (data.dept1_text === "") {
            ycoa.UI.toast.warning("请选择<div style='color:red;font-size:18px;'>部门</div>~");
        } else if (data.takeOverName === "") {
            ycoa.UI.toast.warning("请选择<div style='color:red;font-size:18px;'>接收人</div>~");
        }else if (!UE.getEditor('content').hasContents()) {
            ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>任务内容</div>不能为空哦~");
        } else {
            data.action = 1;
            ycoa.ajaxLoadPost("/api/work/task.php", JSON.stringify(data), function (result) {
                if (result.code === 0) {
                    ycoa.UI.toast.success(result.msg);
                   $("#add_task").css("display","none");
                    window.location.reload("../../../page/center/personal_center.html");
                } else {
                    ycoa.UI.toast.warning(result.msg);
                }
            });
        }
    });
});
$(window).resize(function () {
    var height = $(window).height() - 203;
    $(".div_task_content").height(height);
    $(".div_task_content_body").height(height - (200 + 60));
});
function reLoadData(data) {
    TaskListViewModel.listTask(data);
}
function changeTask(task) {
    task.action = 3;
    ycoa.ajaxLoadPost("/api/work/task.php", JSON.stringify(task), function (result) {
        if (result.code === 0) {
            var task_html = "<div class = 'leftd'><div class = 'leftimg'>";
            if (result.addUserId === 1) {
                task_html += "[<span style='color: rgb(247, 150, 70);'>系统</span>]";
            } else {
                task_html += result.addUserName;
            }
            task_html += "</div><div class='speech left' ><div>" + (result.content) + "</div><div class='task_time'>" + getDate(result.c_date, result.addTime) + "</div></div></div>";
            $(".div_task_content_body").append(task_html);
            change_task_status(result.task);
            ycoa.UI.toast.success("操作成功~");
            reLoadData({action: 1});
        } else {
            ycoa.UI.toast.error("操作失败~");
        }
        ycoa.UI.block.hide();
    });
}
function getDate(c_date, date) {
    if (date.indexOf(c_date) === -1) {
        return date;
    } else {
        return date.split(" ")[1];
    }
}

function change_task_status(task) {
    $(".data_row1").html("[" + (task.addTime) + "]由[" + (task.addUserName) + "]添加,并指派给[" + (task.takeOverName) + "]");
    var html = "";
    switch (task.status) {
        case -1:
            html = "该项任务已删除,暂不能进行任何操作";
            break;
        case 0:
            html = "该项任务还未提交,暂不能进行任何操作";
            break;
        case 1:
            html = "该项任务已提交,等待技术人员操作";
            break;
        case 2:
            html += "[" + (task.takeOverName) + "]已在[" + (task.startTime) + "]接受此任务";
            break;
        case 3:
            html = "[" + (task.takeOverName) + "]已在[" + (task.endTime) + "]完成了该项任务";
            break;
        case 4:
            html = "该项任务由于需求不明确,叙述不清楚等原因,已被[" + (task.takeOverName) + "]驳回,请修改需求或补充叙述";
            break;
        case 5:
            html = "该项任务由于特殊原因,已被终止";
            break;
        case 6:
            html = "该项任务已经被重新启动,等待技术人员接手";
            break;
    }
    $(".data_row2").html(html);
    if (!is_set) {
        UE.getEditor('content').setHeight(140);
        is_set = true;
    }
    var height = $(window).height() - 203;
    $(".div_task_content").height(height);
    $(".div_task_content_body").height(height - (200 + 60));
    $(".div_task_content").show();
    change_btn_send_task_status(task.status);
}
function change_btn_send_task_status(status) {
    switch (status) {
        case -1:
        case 3:
        case 5:
            $("#btn_send_task").hide();
            break;
        case 0:
        case 1:
        case 2:
        case 4:
        case 6:
            $("#btn_send_task").show();
            break;
    }
}

var array = {
    type: [{id: "", text: '全部'}, {id: 1, text: 'OA网页版'}, {id: 2, text: 'OA客户端'}, {id: 3, text: '星密码'}, {id: 4, text: '店宝宝'}, {id: 5, text: '淘货源'}, {id: 6, text: '会员软件'}, {id: 99, text: '其他'}],
    status: [{id: "", text: '全部'}, {id: 0, text: '已保存'}, {id: 1, text: '已添加'}, {id: 2, text: '处理中'}, {id: 3, text: '待验收'}, {id: 4, text: '已驳回'}, {id: 5, text: '已暂停'}, {id: 6, text: '已挂起'}, {id: 7, text: '已退回'}, {id: 8, text: '已撤销'}, {id: 9, text: '已完成'}, {id: 10, text: '已委派'}, {id: -1, text: '已删除'}]
};