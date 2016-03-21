$(function () {
    var content = UE.getEditor('content');
    $("#btn_submit_primary").click(function () {
        var data = $("#add_task_form").serializeJson();
        if (data.title === "") {
            ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>任务标题</div>不能为空哦~");
        } else if (data.type === "") {
            ycoa.UI.toast.warning("请选择<div style='color:red;font-size:18px;'>任务类型</div>~");
        } else if (!UE.getEditor('content').hasContents()) {
            ycoa.UI.toast.warning("<div style='color:red;font-size:18px;'>任务内容</div>不能为空哦~");
        } else {
            data.action = 1;
            ycoa.ajaxLoadPost("/api/work/task.php", JSON.stringify(data), function (result) {
                if (result.code === 0) {
                    ycoa.UI.toast.success(result.msg);
                    $("#add_task_form #title").val("");
                    $("#add_task_form #type").val("");
                    UE.getEditor('content').setContent('', false);
                } else {
                    ycoa.UI.toast.warning(result.msg);
                }
            });
        }
    });
});