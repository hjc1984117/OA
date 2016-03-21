//调用方法：var 变量名 = getParameter("要获取的参数名", URL地址)
//var r = getParameter("vehicleid", location.href);
//var pName = r.split("=")[0]; //获取参数名
//var pValue = r.split("=")[1]; //获取参数值
//javascript获取指定参数及其对应的值
function getParameter(paraStr, url) {
    var result = "";
    //获取URL中全部参数列表数据
    var str = "&" + url.split("?")[1];
    var paraName = paraStr + "=";
    //判断要获取的参数是否存在
    if (str.indexOf("&" + paraName) != -1) {
        //如果要获取的参数到结尾是否还包含“&”
        if (str.substring(str.indexOf(paraName), str.length).indexOf("&") != -1) {
            //得到要获取的参数到结尾的字符串
            var TmpStr = str.substring(str.indexOf(paraName), str.length);
            //截取从参数开始到最近的“&”出现位置间的字符
            result = TmpStr.substr(TmpStr.indexOf(paraName), TmpStr.indexOf("&") - TmpStr.indexOf(paraName));
        }
        else {
            result = str.substring(str.indexOf(paraName), str.length);
        }
    }
    else {
        result = "无此参数";
    }
    return (result.replace("&", ""));
}

function getParameterValue(paraStr, url) {
    return getParameter(paraStr, url).split("=")[1];
}

// 用于判断数组中是否存在某值
function IsContain(arr, val) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == val)
            return true;
    }
    return false;
}

// 打开模式窗口
function windowOpenDialog(url, target, widval, heival) {
    var iWidth = widval;    //窗口宽度
    var iHeight = heival;   //窗口高度 
    var iTop = (window.screen.height - iHeight) / 2;
    var iLeft = (window.screen.width - iWidth) / 2;
    var feature = "dialogHeight: " + iHeight + "px; dialogWidth: " + iWidth + "px; dialogTop: " + iTop + "; dialogLeft: " + iLeft + "; resizable: no; status: no;scroll:no;help:no;location:no;";
    return window.showModalDialog(url, target, feature);
}

// 转换数字 如果转换后非数字返回0
function parseNumber(val) {
    return isNaN(parseInt(val)) ? 0 : parseInt(val);
}

// 格式化数字，保留几位小数
// num ： 传入需要格式化的数字
// len ：   需要保留的小数位数
// 返回转换后的数字。
function FormatNum(num, len) {
    if (!arguments[1]) len = 2;
    return isNaN(parseFloat(num)) ? 0 : parseFloat(num).toFixed(len);
}

// 绑定字典下拉列表
// type          字典类型
// select_id    需要绑定的下拉列表ID
function BindDictSelect(type, select_id, select_Value) {
    if (!arguments[0] || !arguments[1]) return;
    if (!arguments[2]) select_Value = "";

    jQuery.ajax({
        url: "Ajax.aspx",
        data: "paramvalue=" + type + "&paramtype=_getDictQuery",
        type: "post",
        success: function (message) {
            var oHtml = "<option value=''>请选择</option>";
            if (message != "none") {
                var list = eval("(" + message + ")").Dict;
                for (var i = 0; i < list.length; i++) {
                    var dict = list[i];
                    if (dict.DictValue == select_Value)
                        oHtml += "<option value=\"" + dict.DictValue + "\" selected>" + dict.DictItem + "</option>";
                    else
                        oHtml += "<option value=\"" + dict.DictValue + "\">" + dict.DictItem + "</option>";
                }
            }
            jQuery(select_id).html(oHtml);
        },
        error: function () { }
    });
}
function BindHobby(type, select_id) {
    jQuery.ajax({
        url: "Ajax.aspx",
        data: "paramvalue=" + type + "&paramtype=_getDictQuery",
        type: "post",
        success: function (message) {
            var oHtml = "<option value=''>请选择</option>";
            if (message != "none") {
                var list = eval("(" + message + ")").Dict;
                for (var i = 0; i < list.length; i++) {
                    var dict = list[i];
                    oHtml += "<option value=\"" + dict.DictValue + "\">" + dict.DictItem + "</option>";
                }
            }
            jQuery(select_id).html(oHtml);
        },
        error: function () { }
    });
}

// 绑定设置日期选择框
// 参数说明
//      inputid         绑定的控件ID
//      format         日期格式 %Y：年; %m：月; %d：日; %H：时; %M：分; %s：秒;
//      button         触发显示选择器的控件ID
//      align            显示方式 br 在绑定控件下类似换行
//      singleClick   是否单击显示
function BindCalendarControl(inputid, format, button, align, singleClick) {
    if (!arguments[0]) return;
    if (!arguments[1]) format = "%Y-%m-%d";
    if (!arguments[2]) button = inputid;
    if (!arguments[3]) align = "Br";
    if (!arguments[4]) singleClick = true;

    Calendar.setup({
        inputField: inputid,
        ifFormat: format,
        button: button,
        align: align,
        singleClick: singleClick
    });
}

// 绑定设置  自动补全功能
// 参数说明：
//      inputid      响应自动补全的表单ID
//      pType       返回记录的查询类型
//      divid         存放返回记录的DIV对象ID
//      valueid      存放选中记录值的表单ID
//      url            执行操作的URL地址
function BingAutoComplete(inputid, pType, divid, valueid, url) {
    if (!arguments[0] || !arguments[1]) return;
    if (!arguments[2]) divid = "autocomplete";
    if (!arguments[3]) valueid = inputid;
    if (!arguments[4]) url = "Ajax.aspx";

    option = {
        divid: divid,
        strPara: 'paramtype=' + pType + '&&paramvalue',
        zIndex: 999,
        desfun: function (sText, sValue) {
            if (sValue != "none") {
                jQuery(valueid).val(sValue);
            }
        }
    };
    jQuery(inputid).AutoComplete(url, option);
}

// 默认分页参数
var _cPage = 1;
var _pSize = 15;
var _orderColumn = "Id";
var _orderMode = "Asc";

/*-------------------------------------
| 根据当前页索引、分页显示条数、总记录数设置分页内容
| 参数：
|	rCount：	总记录数
|   pageId：  绑定分页的ID
|	cPage：	当前页索引
|	pSize：		页显示记录数
-------------------------------------*/
function SetPager(cPage, rCount, pageId, pSize, setPageFunc) {
    if (!arguments[2]) pageId = "#pager_container";
    if (!arguments[3]) pSize = 15;

	if(setPageFunc === undefined || setPageFunc === null || setPageFunc === ""){
		setPageFunc="SetPagerNo";
	}
	
    cPage = parseInt(cPage);
    rCount = parseInt(rCount);
    var pCount = Math.ceil(rCount / pSize);
    var pPage = cPage - 1, nPage = cPage + 1;
    if (pCount < 1) pCount = 1;
    if (pPage < 1) pPage = 1;
    if (nPage > pCount) nPage = pCount;

    var pager = "<div  class=\"asp_net_pager\" pindex=\"" + cPage + "\">";
    if (cPage > 1) {
        pager += "<a class=\"otherPage\"  href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(1);\" title=\"转到第1页\">首页</a><a class=\"otherPage\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + pPage + ");\" title=\"转到第" + pPage + "页\">上一页</a>";
    } else {
        pager += "<a class=\"undo\">首页</a><a class=\"undo\">上一页</a>";
    }
    var bPage = Math.ceil(cPage / 5) * 5 - 4;
    if (bPage < 1) bPage = 1;
    var ePage = bPage + 4;
    if (ePage > pCount) ePage = pCount;

    if (bPage > 5) pager += "<a class=\"otherPage\" title=\"转到第" + (bPage - 1) + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + (bPage - 1) + ");\">...</a>";
    for (var i = bPage; i <= ePage; i++) {
        if (i == cPage)
            pager += "<a class=\"current\">" + cPage + "</a>";
        else
            pager += "<a class=\"otherPage\" title=\"转到第" + i + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + i + ");\">" + i + "</a>";
    }
    if (ePage < pCount) pager += "<a class=\"otherPage\" title=\"转到第" + (ePage + 1) + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + (ePage + 1) + ");\">...</a>";

    if (cPage < pCount) {
        pager += "<a class=\"otherPage\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + nPage + ");\" title=\"转到第" + nPage + "页\">下一页</a><a class=\"otherPage\" href=\"javascript:void(0);\" onclick=\"javascript:" + setPageFunc + "(" + pCount + ");\" title=\"转到第" + pCount + "页\">末页</a><div class=\"clear\"></div>";
    } else {
        pager += "<a class=\"undo\">下一页</a><a class=\"undo\">末页</a><div class=\"clear\"></div>";
    }

    pager += "</div><div class=\"clear\"></div>";
    $(pageId).html(pager);
}

/*-------------------------------------
| 根据当前页索引、分页显示条数、总记录数设置分页内容
| 参数：
|	rCount：	总记录数
|   pageId：  绑定分页的ID
|	cPage：	当前页索引
|	pSize：		页显示记录数
-------------------------------------*/
function CreatePager(cPage, rCount, pageId, pager_fun, pSize) {
    if (!arguments[2]) pageId = "#pager_container";
    if (!arguments[3]) pager_fun = "SetPagerNo";
    if (!arguments[4]) pSize = 15;

    cPage = parseInt(cPage);
    rCount = parseInt(rCount);
    var pCount = Math.ceil(rCount / pSize);
    var pPage = cPage - 1, nPage = cPage + 1;
    if (pCount < 1) pCount = 1;
    if (pPage < 1) pPage = 1;
    if (nPage > pCount) nPage = pCount;

    var pager = "<div class=\"asp_net_pager\">";
    if (cPage > 1) {
        pager += "<a href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(1);\" title=\"转到第1页\">首页</a><a href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + pPage + ");\" title=\"转到第" + pPage + "页\">上一页</a>";
    } else {
        pager += "<a class=\"undo\">首页</a><a class=\"undo\">上一页</a>";
    }
    var bPage = Math.ceil(cPage / 5) * 5 - 4;
    if (bPage < 1) bPage = 1;
    var ePage = bPage + 4;
    if (ePage > pCount) ePage = pCount;

    if (bPage > 5) pager += "<a class=\"otherPage\" title=\"转到第" + (bPage - 1) + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + (bPage - 1) + ");\">...</a>";
    for (var i = bPage; i <= ePage; i++) {
        if (i == cPage)
            pager += "<a class=\"current\">" + cPage + "</a>";
        else
            pager += "<a class=\"otherPage\" title=\"转到第" + i + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + i + ");\">" + i + "</a>";
    }
    if (ePage < pCount) pager += "<a class=\"otherPage\" title=\"转到第" + (ePage + 1) + "页\" href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + (ePage + 1) + ");\">...</a>";

    if (cPage < pCount) {
        pager += "<a class=\"otherPage\" href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + nPage + ");\" title=\"转到第" + nPage + "页\">下一页</a><a class=\"otherPage\" href=\"javascript:void(0);\" onclick=\"javascript:" + pager_fun + "(" + pCount + ");\" title=\"转到第" + pCount + "页\">末页</a><div class=\"clear\"></div>";
    } else {
        pager += "<a class=\"undo\">下一页</a><a class=\"undo\">末页</a><div class=\"clear\"></div>";
    }

    pager += "</div>";
    $(pageId).html(pager);
}


// 跳转操作
function PagerGo(go, pCount) {
    if (isNaN(go)) {
        alert("页索引无效，请输入纯数字后重试！");
    } else {
        go = parseInt(go);
        if (go < 1 || go > pCount)
            alert("页索引超出范围！");
        else
            SetPagerNo(go);
    }
}

// 排序
function OrderTable(order) {
    if (order != _orderColumn) {
        _orderColumn = order;
        _orderMode = "Asc";
    } else {
        _orderMode = _orderMode != "Asc" ? "Asc" : "Desc";
    }
    SearchQuery(_cPage, _pSize);
}

// jQuery 操作增加、修改
// 参数说明：
//      op          标识是增加（add）操作还是修改（edit）操作
//      type       后台操作TYPE
//      value      后台操作所需参数值集合
//      fun_callback    设置成功后的回调函数
//      url         后台执行URL
function jQueryOperate(op, type, value, fun_callback, url) {
    if (!arguments[0] || !arguments[1] || !arguments[2]) return;
    
    if (!arguments[3]) fun_callback = "";
    if (!arguments[4]) url = "Ajax.aspx";

    jQuery.ajax({
        type: 'post',
        url: url,
        data: "paramvalue=" + escape(value) + "&paramtype=" + type,
        cache: false,
        success: function (message) {
            if (message != "error" && message != "none") {
                if (fun_callback != "") fun_callback;
                alert(OperateMsg(op));
            }
        }, error: function () { alert("失败！"); }
    });
}
function TestCallback() {alert("成功！"); }
// 操作成功后提示
function OperateMsg(op) {
    var msg = "";
    switch (op) {
        case "add":
            msg = "添加成功！";
            break;
        case "edit":
            msg = "修改成功！";
            break;
        case "del":
            msg = "删除成功！";
            break;
        default:
            msg = "操作成功！";
            break;
    }
    return msg;
}