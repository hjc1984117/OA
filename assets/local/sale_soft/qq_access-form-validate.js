$('#add_access_form').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "", // validate all fields including form hidden inpu
    messages: {},
    rules: {
        visitor_info: {
            required: true,
            minlength: 10
        },
        channel: {
            required: true,
            minlength: 1
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
    },
    highlight: function (element) { // hightlight error inputs
        $(element).closest('.col-md-8').addClass('has-error'); // set error class to the control group
    },
    unhighlight: function (element) { // revert the change done by hightlight
        $(element).closest('.col-md-8').removeClass('has-error'); // set error class to the control group
    },
    success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        if (!icon.hasClass("fa")) {
            icon = $(element).parent('.auto_radio_ul').parent('.input-icon').children('i');
        }
        $(element).closest('.col-md-8').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
    },
    submitHandler: function (form) {
        
        var data = $("#add_access_form").serializeJson();
        var str = data.visitor_info.trim();
        if (!str || (str.indexOf("客人") === -1)) {
            ycoa.UI.toast.warning("请复制正确格式的数据进入文本框~");
            return;
        } else {
            data.customer_address = str.match(/\S+(?=客人)/)[0];
            data.customer_num = str.match(/(?!客人)\d{1,5}/)[0];
            data.qq_num = str.match(/\s([0-9a-zA-Z_\-]{6,20})/)[0];//匹配微信和QQ
            data.accesstime = str.match(/\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}/)[0];
        }
        if ((data.visitor_sour) !== "") {//搜索关键词:加盟淘宝店需要多少钱]:m.baidu.com 进入:m.ycxmm.cn/
            var key = data.visitor_sour.trim();
            if (!key || (key.indexOf("进入") === -1)) {
                ycoa.UI.toast.warning("请复制正确格式的数据进入文本框~");
                return;
            } else {
                if (key.indexOf("搜索关键词:") !== -1) {
//                    var s = key.match(/(?!搜索关键词:)\S+(?=])/)[0];
                    data.keyword = function () {
                        return key.substring(key.indexOf("搜索关键词:") + 6, key.lastIndexOf("]:"));
                    }();
                }
            //    var iu = key.match(/进入\:((https|http|ftp|rtsp|mms)?\:\\\\)?([a-z0-1]{0,}\.)?[a-z0-9]{0,}\.[a-z0-9]{0,}(\.[a-z0-9]{0,})(\S)*/)[0];
            var iu = key.match(/进入\:(\S)*/)[0];
                data.into_url = iu.split(":")[1];
            }
        }
        data.action = 1;
        ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
                $("#add_access_form textarea,#add_access_form input").each(function () {
                    if (!$(this).hasClass("not-clear")) {
                        $(this).val("");
                    }
                });
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            ycoa.UI.block.hide();
        });
    }
});
$("#channel_site_form").validate({
    submitHandler:function(form){
        var data = $("#channel_site_form").serializeJson();
        data.action = 5;
        ycoa.ajaxLoadPost("/api/sale_soft/qq_access.php", JSON.stringify(data), function (result) {
            if (result.code == 0) {
                ycoa.UI.toast.success(result.msg);
                reLoadData({action: 1});
                //$('#channelSiteModal').css("display","none");
            } else {
                ycoa.UI.toast.error(result.msg);
            }
            var checkbox_arr = $("#channel_site_form :checkbox"); 
            var textarea = $("#channel_site_form textarea");
            for(var i=0;i<checkbox_arr.length;i++){
                if(checkbox_arr[i].checked){
                    checkbox_arr[i].checked =false;
                    textarea[i].disabled = true;
                    textarea[i].style.backgroundColor="#F0F0F0";
                }
            }
            ycoa.UI.block.hide();
        });    
    }
});