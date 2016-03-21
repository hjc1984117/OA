var __location__ = window.location.toString();
var __host__ = "http://" + window.location.host;
var __path__ = __location__.replace(__host__, "");
var __VERSION__ = '1.0.0';

if (typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function () {
        return $.trim(this);
    };
}
String.prototype.MD5 = function (bit) {
    var sMessage = this;
    function RotateLeft(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }
    function AddUnsigned(lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4)
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        if (lX4 | lY4)
        {
            if (lResult & 0x40000000)
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            else
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
        } else
            return (lResult ^ lX8 ^ lY8);
    }
    function F(x, y, z) {
        return (x & y) | ((~x) & z);
    }
    function G(x, y, z) {
        return (x & z) | (y & (~z));
    }
    function H(x, y, z) {
        return (x ^ y ^ z);
    }
    function I(x, y, z) {
        return (y ^ (x | (~z)));
    }
    function FF(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    }
    function GG(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    }
    function HH(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    }
    function II(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    }
    function ConvertToWordArray(sMessage) {
        var lWordCount;
        var lMessageLength = sMessage.length;
        var lNumberOfWords_temp1 = lMessageLength + 8;
        var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
        var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (sMessage.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    }
    function WordToHex(lValue) {
        var WordToHexValue = "", WordToHexValue_temp = "", lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValue_temp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length - 2, 2);
        }
        return WordToHexValue;
    }
    var x = Array();
    var k, AA, BB, CC, DD, a, b, c, d
    var S11 = 7, S12 = 12, S13 = 17, S14 = 22;
    var S21 = 5, S22 = 9, S23 = 14, S24 = 20;
    var S31 = 4, S32 = 11, S33 = 16, S34 = 23;
    var S41 = 6, S42 = 10, S43 = 15, S44 = 21;
    // Steps 1 and 2. Append padding bits and length and convert to words 
    x = ConvertToWordArray(sMessage);
    // Step 3. Initialise 
    a = 0x67452301;
    b = 0xEFCDAB89;
    c = 0x98BADCFE;
    d = 0x10325476;
    // Step 4. Process the message in 16-word blocks 
    for (k = 0; k < x.length; k += 16) {
        AA = a;
        BB = b;
        CC = c;
        DD = d;
        a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
        d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
        c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
        b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
        a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
        d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
        c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
        b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
        a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
        d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
        c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
        b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
        a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
        d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
        c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
        b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
        a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
        d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
        c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
        b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
        a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
        d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
        c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
        b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
        a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
        d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
        c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
        b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
        a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
        d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
        c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
        b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
        a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
        d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
        c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
        b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
        a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
        d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
        c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
        b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
        a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
        d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
        c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
        b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
        a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
        d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
        c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
        b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
        a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
        d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
        c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
        b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
        a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
        d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
        c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
        b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
        a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
        d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
        c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
        b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
        a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
        d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
        c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
        b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
        a = AddUnsigned(a, AA);
        b = AddUnsigned(b, BB);
        c = AddUnsigned(c, CC);
        d = AddUnsigned(d, DD);
    }
    if (bit == 32) {
        return WordToHex(a) + WordToHex(b) + WordToHex(c) + WordToHex(d);
    } else {
        return WordToHex(b) + WordToHex(c);
    }
}

//扩展时间类 yyy-MM-dd hh:mm:ss
Date.prototype.format = function (format) {
    var o = {
        "M+": this.getMonth() + 1, //month 
        "d+": this.getDate(), //day 
        "h+": this.getHours(), //hour 
        "m+": this.getMinutes(), //minute 
        "s+": this.getSeconds(), //second 
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter 
        "S": this.getMilliseconds() //millisecond 
    }

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

var ycoa = new function ($) {
    var self = this;
    window.ycoa = this;
    this.YCOASYSCONFIG = {
        ycoaUrl: __location__,
        userCookie: "YCOA_USER",
        sessionKey: "YCOA_SESSION_KEY"
    };

    /**************** 分页 ****************/
    this.initPagingContainers = function (node, data, callback, pageCallback) {
        var pageNo = data.page_no;
        var maxPageNo = data.max_page_no;
        var total = data.total_count;
        var pageNumberCount = 6;
        ycoa.SESSION.PAGE.setPageNo(pageNo);
        var elements = new Array();
        if (pageNo < 1) {
            pageNo = 1;
        }
        if (pageNo > maxPageNo) {
            pageNo = maxPageNo;
        }
        var leftPageNumberCount = Math.floor(pageNumberCount / 2);
        var startPageNumber = 1;
        var endPageNumber = 1;
        if (pageNo > leftPageNumberCount) {
            startPageNumber = pageNo - leftPageNumberCount;
        }
        endPageNumber = Math.min(startPageNumber + pageNumberCount - 1, maxPageNo);
        if (pageNo > 1) {
            elements.push({pageNo: 1, current: false, text: "<<"});
            elements.push({pageNo: pageNo - 1, current: false, text: "<"});
        }
        for (var i = startPageNumber; i <= endPageNumber; i++) {
            elements.push({current: i === pageNo, text: i, pageNo: i});
        }
        if (pageNo < maxPageNo) {
            elements.push({pageNo: pageNo + 1, current: false, text: ">"});
            elements.push({pageNo: maxPageNo, current: false, text: ">>"});
        }
        var pagingNumbersTemplate = "<div class='paging_nation btn-group'>";
        for (var i = 0; i < elements.length; i++) {
            if (maxPageNo != 1) {
                if (pageNo == elements[i].pageNo) {
                    pagingNumbersTemplate += "<button type='button' class='btn btn-default' disabled='true'>" + elements[i].text + "</button>";
                } else {
                    pagingNumbersTemplate += "<button type='button' value='" + elements[i].pageNo + "' class='btn btn-default'>" + elements[i].text + "</button>";
                }
            }
        }
        pagingNumbersTemplate += "</div>";
        if (callback) {
            pagingNumbersTemplate += "<span class='page_info'>" + "当前第" + pageNo + "/" + maxPageNo + "页 共" + total + "条数据 每页显示<select id='page_size'></select>条数据</span>";
        }
        node.html(pagingNumbersTemplate);
        var dds = [20, 40, 60, 80, 100];
        $.each(dds, function (index, d) {
            if (ycoa.SESSION.PAGE.getPageSize() == d) {
                node.find("#page_size").append("<option selected='' value='" + d + "'>" + d + "</option>");
            } else {
                node.find("#page_size").append("<option value='" + d + "'>" + d + "</option>");
            }
        });
        if (callback) {
            node.find("#page_size").unbind("change");
            node.find("#page_size").bind("change", function () {
                ycoa.SESSION.PAGE.setPageSize($(this).val());
                callback($(this).val());
            });
        }
        if (pageCallback) {
            $(".paging_nation button").unbind("click");
            $(".paging_nation button").bind("click", function () {
                if ($(this).val()) {
                    pageCallback($(this).val());
                }
            });
        }
    };

    this.util = {
        getQueryString: function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return null;
        },
        isEmpty: function (obj) {
            return obj === undefined || obj === null || obj === "";
        },
        focus: function (element) {
            var $element = (element instanceof jQuery) ? element : $(element);
            if (this.isMsie8()) {
                $element[0].focus();
            } else {
                $element.focus();
            }
        },
        stringCompareIgnoreCase: function (s1, s2) {
            if (self.util.isEmpty(s1) && self.util.isEmpty(s2)) {
                return true;
            }

            if (self.util.isEmpty(s1) || self.util.isEmpty(s2)) {
                return false;
            }

            return s1.toUpperCase() === s2.toUpperCase();
        },
        isMsie8: function () {
            var rv = -1;
            var ua = navigator.userAgent;
            var re = new RegExp("Trident\/([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null) {
                rv = parseFloat(RegExp.$1);
            }
            return (rv == 4);
        },
        queryString: function (name, defaultValue) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
            var results = regex.exec(location.search);
            return results === null ? defaultValue : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    };

    this.request = function (p) {
        url = location.href, ik = url.indexOf("?");
        if (ik == -1)
            return null;
        var paraString = url.substring(ik + 1, url.length).split("&");
        var paraObj = {};
        for (i = 0; j = paraString[i]; i++) {
            paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = self.encodehtml(decodeURIComponent(j.substring(j.indexOf("=") + 1, j.length)));
        }
        return self.util.isEmpty(p) ? (paraObj) : paraObj[p];
    };
    this.jsonObservation = function (jsonObject) {
        var viewModel = {};
        for (var key in jsonObject) {
            viewModel[key] = ko.observable(jsonObject[key]);
        }
        return viewModel;
    };
    var defaultUser = {userid: 0, username: "", employee_no: "", dept1_id: 0, dept1_name: '', role_id: 0, role_type: '', avatar: '../../upload_avatar/default.png'};
    this.user = this.jsonObservation(defaultUser);
    this.cookie = {
        setAccount: function (data) {
            //$.cookie(self.YCOASYSCONFIG.userCookie, base64encode(utf16to8(JSON.stringify(data))), {path: '/'});
            self.user.userid(data.userid);
            self.user.username(data.username);
            self.user.employee_no(data.employee_no);
            self.user.dept1_id(data.dept1_id);
            self.user.dept1_name(data.dept1_name);
            self.user.role_id(data.role_id);
            self.user.role_type(data.role_type);
            if (data.avatar) {
                self.user.avatar(data.avatar);
            }
        },
        getAccount: function () {
            if (self.user.userid() <= 0) {
                var sessStr = $.cookie(self.YCOASYSCONFIG.userCookie);
                if (!self.util.isEmpty(sessStr)) {
                    //var data = JSON.parse(utf8to16(base64decode(sessStr)));
                    var data = JSON.parse(base64decode(sessStr));
                    this.setAccount(data);
                }
            }
            return self.user; //未登录时弹出登录层，代码预留区域
        },
        cookieKeys: {
            userCookie: self.YCOASYSCONFIG.userCookie,
            sessionKey: self.YCOASYSCONFIG.sessionKey
        },
        clearAll: function () {
            for (var key in this.cookieKeys) {
                $.cookie(this.cookieKeys[key], null, {path: '/', expires: -1});
            }
            this.setAccount(defaultUser);
        }
    };
    this.logout = function () {
        self.cookie.clearAll();
        if (typeof (outurl) != "undefined" && !self.util.isEmpty(outurl))
            location.href = outurl;
        else
            location.reload();
    };
    this.user = this.cookie.getAccount();
    this.isLogin = ko.computed(function () {
        return (!ycoa.util.isEmpty(self.user)) && self.user.userid() > 0 && (!ycoa.util.isEmpty(self.user.username()));
    }, ycoa);

    this.getNoCacheUrl = function (url) {
        if (url.indexOf("ts=") > 0) {
            return url;
        }
        if (url.indexOf('?') > 0) {
            return url + "&ts=" + new Date().getTime() + "&__VSERSION__=" + __VERSION__;
        }
        return url + "?ts=" + new Date().getTime() + "&__VSERSION__=" + __VERSION__;
    };
    this.ajaxGet = function (url, data, success, failed, thisObject) {
        if (!ycoa.util.isEmpty(failed) && typeof (failed) !== "function") {
            thisObject = failed;
        }
        if (!ycoa.util.isEmpty(thisObject)) {
            thisObject = this;
        }
        this.ajax(ycoa.getNoCacheUrl(url), data, function (data) {
            if (data.code == '0x301') {
                localStorage.setItem("loginError", "用户身份过期,请重新登录~");
                if (__path__ != "/page/index.html") {
                    window.top.location.href = __host__ + "/page/login.html";
                } else {
                    window.location.href = __host__ + "/page/login.html";
                }
            } else if (data.code == '0x600') {
                ycoa.UI.toast.warning(data.msg);
            } else {
                if (data.code == "0") {
                    if (typeof (success) === "function") {
                        success.apply(thisObject, [data]);
                    }
                } else if (typeof (failed) === "function") {
                    failed.apply(thisObject, [data]);
                }
            }
        }, {type: "POST"});
    };
    this.ajax = function (url, data, callback, settings) {
        data = prepareAjaxData(data);
        var ajaxSettings = {
            type: "POST",
            dataType: "json",
            url: ycoa.getNoCacheUrl(url),
            data: data,
            success: function (data) {
                if (data.code == '0x301') {
                    localStorage.setItem("loginError", "用户身份过期,请重新登录~");
                    if (__path__ != "/page/index.html") {
                        window.top.location.href = __host__ + "/page/login.html";
                    } else {
                        window.location.href = __host__ + "/page/login.html";
                    }
                } else if (data.code == '0x600') {
                    ycoa.UI.toast.warning(data.msg);
                } else {
                    if (typeof (callback) === "function") {
                        callback.apply(this, [data]);
                    }
                }
            },
            error: function (e) {
                consol.log(e.responseText);
            }
        };
        if (!ycoa.util.isEmpty(settings)) {
            $.extend(ajaxSettings, settings);
        }
        return $.ajax(ajaxSettings);
    };
    this.ajaxLoadGet = function (url, data, success) {
        ycoa.UI.block.show();
        $.get(ycoa.getNoCacheUrl(url), data, function (result) {
            ycoa.UI.block.hide();
            if (result.code == '0x301') {
                localStorage.setItem("loginError", "用户身份过期,请重新登录~");
                if (__path__ != "/page/index.html") {
                    window.top.location.href = __host__ + "/page/login.html";
                } else {
                    window.location.href = __host__ + "/page/login.html";
                }
            } else if (result.code == '0x600') {
                ycoa.UI.toast.warning(result.msg);
            } else {
                success(result);
            }
        });
    };
    this.ajaxLoadPost = function (url, data, success) {
        ycoa.UI.block.show();
        $.post(ycoa.getNoCacheUrl(url), data, function (result) {
            ycoa.UI.block.hide();
            if (result.code == '0x301') {
                localStorage.setItem("loginError", "用户身份过期,请重新登录~");
                if (__path__ != "/page/index.html") {
                    window.top.location.href = __host__ + "/page/login.html";
                } else {
                    window.location.href = __host__ + "/page/login.html";
                }
            } else if (result.code == '0x600') {
                ycoa.UI.toast.warning(result.msg);
            } else {
                success(result);
            }
        });
    };

    this.UI = new function () {
        var uiSelf = this;
        uiSelf.toast = new function () {
            var toastSelf = this;
            var toastType = ['success', 'info', 'warning', 'error'];
            if (window.toastr) {
                toastr.options = {closeButton: true, debug: false, progressBar: true, positionClass: "toast-bottom-right", preventDuplicates: false, showDuration: 300, hideDuration: 1000, timeOut: 5000, extendedTimeOut: 1000, showEasing: "swing", hideEasing: "swing", showMethod: "slideDown", hideMethod: "slideUp"};
            }
            toastSelf.success = function (message, title) {
                toastr[toastType[0]](message ? message : "你的操作成功啦~", title ? title : "消息提示");
            };
            toastSelf.info = function (message, title) {
                toastr[toastType[1]](message ? message : "你有新消息啦~", title ? title : "消息提示");
            };
            toastSelf.warning = function (message, title) {
                toastr[toastType[2]](message ? message : "你的操作可能没有成功~", title ? title : "消息提示");
            };
            toastSelf.error = function (message, title) {
                toastr[toastType[3]](message ? message : "你的操作失败咯~", title ? title : "消息提示");
            };
        };
        uiSelf.messageBox = new function () {
            var boxSelf = this;
            boxSelf.alert = function (message, callback) {
                bootbox.alert(message ? message : "消息提示", callback);
            };
            boxSelf.confirm = function (message, callback) {
                if (callback) {
                    bootbox.confirm(message ? message : "Are you sure?", function (result) {
                        callback(result);
                    });
                } else {
                    boxSelf.alert("该操作回调函数是必须的,哪怕什么也不做~");
                }
            };
        };
        uiSelf.block = new function () {
            var blockSelf = this;
            //options={target: el, iconOnly: true}
            blockSelf.show = function (loading) {
                if (loading === undefined) {
                    loading = true;
                }
                var options = $.extend(true, {}, options);
                var html = '';
                if (loading) {
                    html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' +
                            '<img src="../../assets/global/img/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
                }
                $.blockUI({
                    message: html,
                    baseZ: 99999,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.1,
                        cursor: 'auto'
                    }
                });

            };
            blockSelf.hide = function () {
                $.unblockUI();
            };
        };
        /**
         * 
         * @param {type} el 触发事件的元素
         * @param {type} callback 返回触发事件元素本身和node.id,node.text,node.parent,node.parents[]等参数
         * @param {type} byClickDept2 是否支持二级部门点击
         * @param {type} hasZH 是否显示  佐航
         */
        uiSelf.deptDropDownTree = function (el, callback, byClickDept2, opt) {
            var offset = el.offset();
            var top = offset.top + el.outerHeight() - 1;
            var left = offset.left;
            if (($(window).width() - left) < 250) {
                left = $(window).width() - 190;
            }
            var thisId = "jstree_" + el.attr("id");
            $("#" + thisId).remove();
            var html = "<div id='" + thisId + "' class='ps-container' style='box-shadow: rgba(102, 102, 102, 0.0980392) 5px 5px;z-index: 10060; top:" + top + "px;left:" + left + "px;background: white;border: solid 1px #cecece;position: absolute;display:none;padding-bottom:10px;padding-top:10px;height: 310px;width: 180px;overflow: auto;display:none;'></div>";
            $("body").append(html);
            var array_data = {};
            if (opt && opt.hasZH) {
                array_data.zh = 1;
            }
            if (opt && opt.hasZHB) {
                array_data.zhb = 1;
            }
            ycoa.ajaxLoadGet('/api/sys/dept.php', array_data, function (result) {
                ycoa.UI.block.hide();
                $('#' + thisId).jstree({
                    'core': {
                        "themes": {"responsive": false},
                        'data': result
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder icon-state-warning icon-lg"
                        },
                        "file": {
                            "icon": "fa fa-file icon-state-warning icon-lg"
                        }
                    }
                }).bind("select_node.jstree", function (event, data) {
                    if (!byClickDept2) {
                        if (data.node.children.length == 0) {
                            $('#' + thisId).hide(500);
                            callback(data.node, el);
                        }
                    } else {
                        $('#' + thisId).hide(500);
                        callback(data.node, el);
                    }
                });
                $(document).bind("click", function (e) {
                    var target = $(e.target);
                    if (target.closest("#" + thisId).length == 0) {
                        $("#" + thisId).hide();
                    }
                });
                $("#" + thisId).show();
            });
        };
        /**
         * 
         * @param {type} options 
         *                      options.el:触发事件的元素,必选
         *                      options.type:人员选择类型,默认'more' 可选'only','more':多选,'only':单选
         *                      options.groupId 默认显示的部门,默认所有
         * @param {type} callback 
         *                      options.type == 'more': Array(Object), id=userid,name=username,触发事件元素本身
         *                      options.type == 'only': Object, id=userid,name=username,触发事件元素本身
         */
        uiSelf.empSeleter = function (options, callback) {
            var groupId = options.groupId ? options.groupId : 0;
            var el = options.el;
            var type = options.type ? options.type : 'more';
            var offset = el.offset();
            var top = offset.top + el.outerHeight() - 1;
            var left = offset.left;
            var mainId = "jstree_main_" + el.attr("id");
            $("#" + mainId).remove();
            var treeId = "jstree_" + el.attr("id");
            var html = "<div id='" + mainId + "' style='height: 310px; width: 422px;z-index: 10060; top: " + top + "px; left: " + left + "px;background: #cecece;border: 1px solid rgb(206, 206, 206);position: absolute; overflow: hidden; background: white;box-shadow: 5px 5px rgba(102, 102, 102, 0.1);display:none;'>";
            html += "<div id='" + treeId + "' style='height: 100%;width: 180px;float: left;overflow:auto;'>";
            html += "</div>";
            html += "<div class='selecter_emplist'>";
            if (type == "more") {
                html += "<div class='tools_bar'><div class='checkall' do='checkall'>全选</div><div class='quit'>退出</div><div class='ok'>确定</div></div>";
                html += "<div class='ps-container emplist_items' style='height: 283px;' id='emplist_items'>";
            } else if (type == "only") {
                html += "<div class='ps-container emplist_items'style='height: 310px;' id='emplist_items'>";
            }
            html += "</div>";
            html += "</div>";
            html += "</div>";
            $("body").append(html);
            $(".tools_bar .checkall").unbind("click");
            $(".tools_bar .checkall").bind("click", function () {
                if ($(this).attr("do") == "checkall") {
                    $("#emplist_items .emp_text").addClass("checked");
                    $(this).attr("do", "uncheckall");
                } else {
                    $("#emplist_items .emp_text").removeClass("checked");
                    $(this).attr("do", "checkall");
                }
            });
            $(".tools_bar .ok").unbind("click");
            $(".tools_bar .ok").bind("click", function () {
                var vals = new Array();
                $("#emplist_items .checked").each(function () {
                    vals.push({id: $(this).attr("val"), name: $(this).html()});
                });
                if (callback) {
                    callback(vals, el);
                }
                $("#" + mainId).hide(500);
            });
            $(".tools_bar .quit").unbind("click");
            $(".tools_bar .quit").bind("click", function () {
                $("#" + mainId).hide(500);
            });
            ycoa.ajaxLoadGet("/api/sys/dept.php", {groupId: groupId.toString()}, function (result) {
                ycoa.UI.block.hide();
                $('#' + treeId).jstree({
                    'core': {
                        "themes": {"responsive": false},
                        'data': result
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder icon-state-warning icon-lg"
                        },
                        "file": {
                            "icon": "fa fa-file icon-state-warning icon-lg"
                        }
                    }
                }).bind("select_node.jstree", function (event, data) {
                    var data;
                    if (data.node.parents.length > 1) {//二级菜单点击
                        data = {'dept2_id': data.node.id, 'action': 1};
                    } else {//一级菜单点击
                        data = {'dept1_id': data.node.id, 'action': 1};
                    }
                    ycoa.ajaxLoadGet("/api/sys/cache.php", data, function (empArray) {
                        $("#emplist_items .emp_text").unbind("click");
                        var empHtml = "";
                        $.each(empArray, function (index, empList) {
                            empHtml += "<div class='title'>" + index + "</div>";
                            $.each(empList, function (num, emp) {
                                empHtml += "<div class='emp_text' val='" + emp.userid + "'>" + emp.username + "</div>";
                            });
                        });
                        empHtml += "<div class='last'></div>";
                        $("#emplist_items", $("#" + mainId)).html(empHtml);
                        $("#emplist_items .emp_text").bind("click", function () {
                            if (type == 'more') {
                                $(this).toggleClass('checked');
                            } else if (type == 'only') {
                                if (callback) {
                                    callback({id: $(this).attr('val'), name: $(this).html()}, el);
                                }
                                $("#" + mainId).hide(500);
                            }
                        });
                    });
                });
                $(document).bind("click", function (e) {
                    var target = $(e.target);
                    if (target.closest("#" + mainId).length == 0) {
                        $("#" + mainId).hide();
                    }
                });
                $("#" + mainId).show();
            });
        };
        /**
         * 
         * @param {type} options 
         *                      options.el:触发事件的元素,必选
         * @param {type} callback 
         *                          id=roleid,name=rolename
         */
        uiSelf.roleSeleter = function (options, callback) {
            var groupId = options.groupId ? options.groupId : 0;
            var el = options.el;
            var offset = el.offset();
            var top = offset.top + el.outerHeight() - 1;
            var left = offset.left;
            var mainId = "jstree_main_" + el.attr("id");
            $("#" + mainId).remove();
            var treeId = "jstree_" + el.attr("id");
            var html = "<div id='" + mainId + "' style='height: 310px; width: 273px;z-index: 10060; top: " + top + "px; left: " + left + "px;background: #cecece;border: 1px solid rgb(206, 206, 206);position: absolute; overflow: hidden; background: white;box-shadow: 5px 5px rgba(102, 102, 102, 0.1);'>";
            html += "<div id='" + treeId + "' style='height: 100%;width: 140px;float: left;overflow:auto;'>";
            html += "</div>";
            html += "<div class='selecter_rolelist'>";
            html += "<div class='ps-container rolelist_items'style='height: 308px;' id='rolelist_items'>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            $("body").append(html);
            $(".tools_bar .checkall").unbind("click");
            $(".tools_bar .checkall").bind("click", function () {
                if ($(this).attr("do") == "checkall") {
                    $("#rolelist_items .role_text").addClass("checked");
                    $(this).attr("do", "uncheckall");
                } else {
                    $("#rolelist_items .role_text").removeClass("checked");
                    $(this).attr("do", "checkall");
                }
            });
            ycoa.ajaxLoadGet("/api/sys/dept.php", {groupId: groupId.toString()}, function (result) {
                ycoa.UI.block.hide();
                $('#' + treeId).jstree({
                    'core': {
                        "themes": {"responsive": false},
                        'data': result
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder icon-state-warning icon-lg"
                        },
                        "file": {
                            "icon": "fa fa-file icon-state-warning icon-lg"
                        }
                    }
                }).bind("select_node.jstree", function (event, data) {
                    var data;
                    if (data.node.parents.length > 1) {//二级菜单点击
                        data = {'dept2_id': data.node.id, 'action': 2};
                    } else {//一级菜单点击
                        data = {'dept1_id': data.node.id, 'action': 2};
                    }
                    ycoa.ajaxLoadGet("/api/sys/cache.php", data, function (roleArray) {
                        $("#rolelist_items .role_text").unbind("click");
                        var empHtml = "";
                        $.each(roleArray, function (num, role) {
                            empHtml += "<div class='role_text' val='" + role.id + "'>" + role.text + "</div>";
                        });
                        empHtml += "<div class='last'></div>";
                        $("#rolelist_items", $("#" + mainId)).html(empHtml);
                        $("#rolelist_items .role_text").bind("click", function () {
                            callback({id: $(this).attr('val'), name: $(this).html()}, el);
                            $("#" + mainId).hide(500);
                        });

                    });
                });
                $(document).bind("click", function (e) {
                    var target = $(e.target);
                    if (target.closest("#" + mainId).length == 0) {
                        $("#" + mainId).hide();
                    }
                });
                $("#" + mainId).show();
            });
        };
        uiSelf.checkBoxVal = function () {
            var idList = new Array();
            $("#dataTable tbody input[type='checkbox']").each(function (node) {
                if ($(this).attr("checked")) {
                    idList.push($(this).val());
                }
            });
            return idList.toString();
        };
    };
    this.SESSION = new function () {
        var self = this;
        self.PERMIT = new function () {
            var perSelf = this;
            perSelf.hasPagePermitButton = function (id, call) {
                var permitButtons = ycoa.SESSION.PERMIT.getPermitButtons();
                if (call) {
                    return call(permitButtons.indexOf(id) < 0 ? false : true);
                }
                return permitButtons.indexOf(id) < 0 ? false : true;
            };
            perSelf.setPermitButtons = function (buttons) {
                localStorage.setItem('permit_buttons', buttons);
            };
            perSelf.getPermitButtons = function () {
                var buttons = localStorage.getItem('permit_buttons');
                if (buttons) {
                    return localStorage.getItem('permit_buttons').split(",");
                }
                return new Array();
            };
        };
        self.PAGE = new function () {
            var pageSelf = this;
            var pathname = window.location.pathname.MD5();
            pageSelf.setPageNo = function (pageNo) {
                localStorage.setItem("page_no_" + pathname, pageNo);
            };
            pageSelf.getPageNo = function () {
                return localStorage.getItem("page_no_" + pathname);
            };
            pageSelf.setPageSize = function (pageSize) {
                localStorage.setItem("page_size_" + pathname, pageSize);
            };
            pageSelf.getPageSize = function () {
                return localStorage.getItem("page_size_" + pathname) == null ? 20 : localStorage.getItem("page_size_" + pathname);
            };
            pageSelf.clearPage = function () {
                localStorage.removeItem("page_no_" + pathname);
                localStorage.removeItem("page_size_" + pathname);
            };
        };
        self.SORT = new function () {
            var sortSelf = this;
            var pathname = window.location.pathname.MD5();
            sortSelf.getSort = function () {
                return localStorage.getItem("sort_" + pathname);
            };
            sortSelf.setSort = function (sort) {
                localStorage.setItem("sort_" + pathname, sort);
            };
            sortSelf.getSortName = function () {
                return localStorage.getItem("sortname_" + pathname);
            };
            sortSelf.setSortName = function (sortname) {
                localStorage.setItem("sortname_" + pathname, sortname);
            };
        };
        self.setAutoParameter = function (key, value) {
            localStorage.setItem(key, value);
        };
        self.getAutoParameter = function (key) {
            if (localStorage.getItem(key)) {
                return localStorage.getItem(key);
            } else {
                return null;
            }
        };
        self.clearAutoParameter = function (key) {
            localStorage.removeItem(key);
        };
    };
}(jQuery);
(function ($) {
    /**
     * 一下自定义控件均有依赖于第三方控件
     */

    /**
     * form表单数据格式化
     * @returns {@this;@pro;value|Array}
     */
    $.fn.serializeJson = function () {
        var serializeObj = {};
        var array = this.serializeArray();
        var str = this.serialize();
        $(array).each(function () {
            if (serializeObj[this.name]) {
                if ($.isArray(serializeObj[this.name])) {
                    serializeObj[this.name].push(this.value);
                } else {
                    serializeObj[this.name] = [serializeObj[this.name], this.value];
                }
            } else {
                serializeObj[this.name] = this.value;
            }
        });
        return serializeObj;
    };
    /**
     * 表格排序,在需要排序的表格头上加 sort='对应字段名字',需要后台支持
     * @param {type} callback
     */
    $.fn.sort = function (callback) {
        var self_ = this;
        self_.children("thead").children("tr").children("th").each(function (i) {
            if (typeof ($(this).attr("sort")) != "undefined") {
                $(this).addClass("sorting");
                $(this).click(function () {
                    var classs = "sorting_desc";
                    var backVar = {'sort': 'DESC', 'sortname': ''};
                    if ($(this).hasClass("sorting_desc")) {
                        backVar.sort = 'ASC';
                        classs = "sorting_asc";
                    }
                    if ($(this).hasClass("sorting_asc")) {
                        backVar.sort = '';
                        classs = "";
                    }
                    //$("#dataTable thead tr th").removeClass("sorting_desc");
                    //$("#dataTable thead tr th").removeClass("sorting_asc");

                    $(this).parent().find("th").removeClass("sorting_desc");
                    $(this).parent().find("th").removeClass("sorting_asc");


                    $(this).addClass(classs);
                    if (callback) {
                        backVar.sortname = $(this).attr('sort');
                        ycoa.SESSION.SORT.setSort(backVar.sort);
                        ycoa.SESSION.SORT.setSortName(backVar.sortname);
                        callback(backVar);
                    }
                });
            }
        });
    };
    /**
     * 按照时间查询控件
     * @param {type} callback
     * @param {type} title
     */
    $.fn.searchDateTime = function (callback, title) {
        var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
        if (portletTitle.hasClass("portlet-title")) {
            portletTitle.append("<div class='input-group auto-group' ><input type='text' id='searchDateTime' class='form-control' data-date-format='yyyy-mm-dd' readonly=''  placeholder='" + (!title ? "时间" : title) + "'><span class='input-group-addon' id='searchByDateTime'><i class='fa fa-search'></i></span></div>");
            $("#searchDateTime").live("mouseover", function () {
                $(this).datepicker({autoclose: true}, function (data) {
                    if (callback) {
                        callback(data);
                    }
                });
            });
            $("#searchByDateTime").live("click", function () {
                var searcherDate = $("#searchDateTime").val();
                if (searcherDate !== '') {
                    if (callback) {
                        callback(searcherDate);
                    }
                }
            });
        }
    };

    /**
     * 时间段选择控件
     * @param {type} callback
     */
    $.fn.searchDateTimeSlot = function (callback) {
        var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
        if (portletTitle.hasClass("portlet-title")) {
            this.each(function () {
                var self = $(this);
                var date_array = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '24:00'];
                var time_date = "<option selected=''>时间</option>";
                $.each(date_array, function (index, d) {
                    time_date += "<option value='" + d + "'>" + d + "</option>";
                });
                var html = "<div class='input-group auto-group searchDateTimeSlot' style='max-width: 480px !important;'>";
                html += "<input type='text' id='searchStartTime' style='display: none;'>";
                html += "<input type='text' id='searchEndTime' style='display: none;'>";
                html += "<input type='text' id='startDate' class='form-control' data-date-format='yyyy-mm-dd' readonly=''  placeholder='开始日期' style='float:left;width:110px;'>";
                html += "<select class='form-control' id='startTime' style='float:left;width:90px;border-left: none;'>";
                html += time_date;
                html += "</select>";
                html += "<span class='input-group-addon'><i class='fa fa-exchange'></i></span>";
                html += "<input type='text' id='endDate' class='form-control' data-date-format='yyyy-mm-dd' readonly='' placeholder='结束日期' style='float:left;width:111px;'>";
                html += "<select class='form-control' id='endTime' style='float:left;width:90px;border-left: none;'>";
                html += time_date;
                html += "</select>";
                html += "<span class='input-group-addon' id='searchByDateTimeSlot'><i class='fa fa-search'></i></span>";
                html += "</div>";
                portletTitle.append(html);
                $("#startDate").live("mouseover", function () {
                    $(this).datepicker({autoclose: true}, function (d) {
                        var dom = $(".searchDateTimeSlot").find("#startTime");
                        if (dom.val().replace("时间", "")) {
                            $(".searchDateTimeSlot").find("#searchStartTime").val(d + " " + dom.val());
                        } else {
                            $(".searchDateTimeSlot").find("#searchStartTime").val(d);
                        }
                    });
                });
                $("#endDate").live("mouseover", function () {
                    $(this).datepicker({autoclose: true}, function (d) {
                        var dom = $(".searchDateTimeSlot").find("#endTime");
                        if (dom.val().replace("时间", "")) {
                            $(".searchDateTimeSlot").find("#searchEndTime").val(d + " " + dom.val());
                        } else {
                            $(".searchDateTimeSlot").find("#searchEndTime").val(d);
                        }

                    });
                });
                $("#startTime").live("change", function () {
                    var dom = $(".searchDateTimeSlot").find("#searchStartTime");
                    var val = dom.val().split(" ");
                    if ($(this).val() != "时间") {
                        if (dom.val()) {
                            if (val.length == 1) {
                                dom.val(dom.val() + " " + $(this).val());
                            } else {
                                dom.val(val[0] + " " + $(this).val());
                            }
                        } else {
                            ycoa.UI.toast.warning("请先选择开始日期~");
                        }
                    } else {
                        dom.val(val[0]);
                    }
                });
                $("#endTime").live("change", function () {
                    var dom = $(".searchDateTimeSlot").find("#searchEndTime");
                    var val = dom.val().split(" ");
                    if ($(this).val() != "时间") {
                        if (dom.val()) {
                            if (val.length == 1) {
                                dom.val(dom.val() + " " + $(this).val());
                            } else {
                                dom.val(val[0] + " " + $(this).val());
                            }
                        } else {
                            ycoa.UI.toast.warning("请先选择结束日期~");
                        }
                    } else {
                        dom.val(val[0]);
                    }
                });
                $("#searchByDateTimeSlot").live("click", function () {
                    var searchStartTime = $("#searchStartTime").val();
                    var searchEndTime = $("#searchEndTime").val();
                    if (callback) {
                        if (searchStartTime != '' && searchEndTime != '') {
                            if (searchStartTime.indexOf("-") != -1 && searchEndTime.indexOf("-")) {
                                if (searchStartTime <= searchEndTime) {
                                    callback({start: searchStartTime, end: searchEndTime});
                                } else {
                                    ycoa.UI.toast.warning("操作提示", "结束时间必须大于开始时间~");
                                }
                            } else {
                                ycoa.UI.toast.warning("请先选开始或者束日期~");
                            }
                        } else {
                            if (searchStartTime != '' || searchEndTime != '') {
                                if (searchStartTime.indexOf("-") != -1 || searchEndTime.indexOf("-")) {
                                    callback({start: searchStartTime, end: searchEndTime});
                                } else {
                                    ycoa.UI.toast.warning("请先选开始或者束日期~");
                                }
                            }
                        }
                    }
                });
            });
        }
    };

    /**
     * 按照用户名称查询控件
     * @param {type} callback
     * @param {type} title
     */
    $.fn.searchUserName = function (callback, title, name) {
        if (!name) {
            name = "searchUserName";
        }
        this.each(function () {
            var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
            if (portletTitle.hasClass("portlet-title")) {
                portletTitle.append("<div class='input-group auto-group'><input type='text' id='" + name + "' name='" + name + "' class='form-control' placeholder='" + (!title ? '按照名称查找' : title) + "'><span class='input-group-addon' id='searchBy" + name + "'><i class='fa fa-search'></i></span></div>");
                $("#searchBy" + name).live('click', function () {
                    var searcherName = $("#" + name).val();
                    callback(searcherName);
                });
                $(window).keypress(function (e) {
                    if (e.which == 13) {
                        if ($("#" + name).is(':focus')) {
                            $("#searchBy" + name).click();
                        }
                    }
                });
            } else {
                console.log("----------------亲,你确定你的表格格式是按照我们约定好的了么?----------------");
            }
        });
    };

    /**
     * 按照部门查询控件
     * @param {type} callback
     */
    $.fn.searchDept = function (callback, name) {
        var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
        if (!name) {
            name = "searchDeptName";
        }
        if (portletTitle.hasClass("portlet-title")) {
            portletTitle.append("<div class='btn-group bootstrap-select bs-select form-control input-small' style='float:right;margin-left:5px;border:solid 1px #cecece;min-width:150px !important;'><input type='hidden' id='" + name + "'/><button id='dept_selecter' type='button' class='btn dropdown-toggle selectpicker ' style='background:#ffffff;' data-toggle='dropdown'><span class='search_dept_title filter-option pull-left'>按照部门筛选</span><span class='caret'></span></button></div>");
            $("#dept_selecter").live('click', function () {
                ycoa.UI.deptDropDownTree($(this), function (node, el) {
                    $(".search_dept_title").html(node.text);
                    callback(node.id);
                    $("#" + name).val(node.id);
                });
            });
        } else {
            console.log("----------------亲,你确定你的表格格式是按照我们约定好的了么?----------------");
        }
    };

    /*
     * 自定义可手动填写下拉框
     * array: [{value:1,text:'1111'},{value:3,text:'2222'}] 必选参数
     * callback: 选择选项后的回调函数 返回 {value:xxx,text:xxx}  可选参数
     * */
    $.fn.autoEditSelecter = function (array, callback) {
        if (typeof (array) == "object") {
            this.each(function () {
                var self = $(this);
                self.css('background', 'url(../../assets/global/img/down_bg.png) no-repeat right');
                var date = Math.round((new Date().getTime()) * (Math.random() * 100));
                var html = "<div id='autoEditSelecter_" + date + "' class='btn-group bootstrap-select bs-select form-control' style='min-width:150px !important;'>";
                html += "<div class='dropdown-menu open' style='max-height: 363px; overflow: hidden; min-height: 0px;'>";
                html += "<ul class='dropdown-menu inner selectpicker' role='menu' style='max-height: 361px; overflow-y: auto; min-height: 0px;' id='dropdown_menu_auto_status_" + date + "'>";
                $.each(array, function (index, d) {
                    if (self.val()) {
                        if (d.id == self.val()) {
                            self.val(d.text);
                        }
                    } else {
                        if (d.default) {
                            self.val(d.text);
                        }
                    }
                    html += "<li rel='0' class='selected' v='" + d.id + "' t='" + d.text + "' d='" + date + "'>";
                    html += "<a tabindex='0' class='' style=''>";
                    html += "<span class='text'>" + d.text + "</span>";
                    html += "<i class='fa fa-check icon-ok check-mark'></i>";
                    html += "</a>";
                    html += "</li>";
                });
                html += "</ul>";
                html += "</div>";
                html += "</div>";
                self.attr('data-toggle', 'dropdown');
                self.addClass("dropdown-toggle");
                self.parent().prepend(html);
                self.prependTo($(self).parent().find("#autoEditSelecter_" + date));
                $("#autoEditSelecter_" + date + " li").live("click", function () {
                    self.val($(this).attr("t"));
                    if (callback) {
                        callback({id: $(this).attr("v"), text: $(this).attr("t"), el: self});
                    }
                });
            });
        } else {
            console.log("----------------亲,你给我的数据格式不对,我无法为你生成控件?----------------");
        }
    };

    /**
     * 自定义下拉选项
     * array 下拉数据 [{id:1,text:"text"},{id:2,text:"text2"}]
     * */
    $.fn.searchAutoStatus = function (array, callback, title, name) {
        var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
        if (!name) {
            name = "searchAutoStatus";
        }
        if (portletTitle.hasClass("portlet-title")) {
            if (typeof (array) == 'object') {
                this.each(function () {
                    var date = Math.round((new Date().getTime()) * (Math.random() * 100));
                    var html = "<div class='btn-group bootstrap-select bs-select form-control input-small' style='float:right;margin-left:5px;border:solid 1px #cecece;min-width:150px !important;' >";
                    html += "<button type='button' class='btn dropdown-toggle selectpicker ' style='background:#ffffff;' data-toggle='dropdown'>";
                    html += "<span class='filter-option pull-left'>" + (!title ? "搜索" : title) + "</span>";
                    html += "<span class='caret'></span>";
                    html += "<input type='hidden' id='" + name + "'/>";
                    html += "</button>";
                    html += "<div class='dropdown-menu open' style='max-height: 363px; overflow: hidden; min-height: 0px;'>";
                    html += "<ul class='dropdown-menu inner selectpicker' role='menu' style='max-height: 361px; overflow-y: auto; min-height: 0px;' id='dropdown_menu_auto_status_" + date + "'>";
                    $.each(array, function (index, d) {
                        html += "<li rel='0' class='selected' v='" + d.id + "' t='" + d.text + "' d='" + date + "'>";
                        html += "<a tabindex='0' class='' style=''>";
                        html += "<span class='text'>" + d.text + "</span>";
                        html += "<i class='fa fa-check icon-ok check-mark'></i>";
                        html += "</a>";
                        html += "</li>";
                    });
                    html += "</ul>";
                    html += "</div>";
                    html += "</div>";
                    portletTitle.append(html);
                    $("#dropdown_menu_auto_status_" + date + " li").live('click', function () {
                        $(this).parent("ul").parent("div").parent("div").find("button:first-child").children("span:first-child").html($(this).attr("t"));
                        $(this).parent("ul").parent("div").parent("div").find("button").find("input").val($(this).attr("v"));
                        if (callback) {
                            callback({id: $(this).attr("v"), text: $(this).attr("t")});
                        }
                    });
                });
            } else {
                console.log("----------------亲,你确定你给我的数据是OBJECT格式的么?----------------");
            }
        } else {
            console.log("----------------亲,你确定你的表格格式是按照我们约定好的了么?----------------");
        }
    };

    /**
     * 刷新控件
     * @param {type} callback
     */
    $.fn.reLoad = function (callback) {
        var portletTitle = $(this).parent(".portlet-body").prev('.portlet-title');
        if (portletTitle.hasClass("portlet-title")) {
            portletTitle.append("<div class='tools' style='float: right; '><i class='icon-reload' style='margin-left: 5px;cursor: pointer;font-size: 25px' time='0'></i></div>");
            $('.icon-reload').live('click', function () {
                var thisTime = $(this).attr('time');
                if (thisTime == '0') {
                    $(this).attr('time', new Date().getTime());
                    callback();
                } else {
                    var thisTime = parseInt(thisTime);
                    if (((new Date().getTime()) - thisTime) > 3000) {
                        $(this).attr('time', new Date().getTime());
                        callback();
                    } else {
                        ycoa.UI.toast.warning('刷新频率过高,请稍后重试~');
                    }
                }
            });
        } else {
            console.log("----------------亲,你确定你的表格格式是按照我们约定好的了么?----------------");
        }
    };

    /**
     * 按照简拼查询(类似百度搜索)
     * @param {type} callback
     * @returns {unresolved}
     */
    $.fn.empSearchAutoComplete = function (callback) {
        var self = this;
        var cache = {};
        self.autocomplete({
            minLength: 1,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response($.map(cache[ term ], function (item) {
                        return {id: item.userid, value: item.username};
                    }));
                    return;
                }
                $.ajax({
                    url: ycoa.getNoCacheUrl("/api/user/user.php?action=0"),
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        cache[ term ] = data;
                        response($.map(data, function (item) {
                            return {id: item.userid, value: item.username};
                        }));
                    }
                });
            },
            select: function (event, ui) {
                if (callback) {
                    callback({name: ui.item.value, id: ui.item.id}, self);
                }
            }
        });
    };

    /**
     * 自定义radio按钮
     * @param {type} array [{id:xx,text:xx},{id:xx,text:xx}]
     * @param {type} callback
     */
    $.fn.autoRadio = function (array, callback) {
        if (typeof (array) === "object") {
            this.each(function () {
                var self = $(this);
                var checkMore = false;
                if (self.attr("checkMore") && (self.attr("checkMore") === "true")) {
                    checkMore = true;
                }
                var date = Math.round((new Date().getTime()) * (Math.random() * 100));
                var html = "<ul class='auto_radio_ul' id='auto_radio_ul_" + date + "'>";
                if (checkMore) {
                    var selfVal = self.val().split(",");
                    $.each(array, function (index, d) {
                        if (selfVal.length >= 1 && selfVal != "") {
                            if (selfVal.indexOf((d.id).toString()) !== -1) {
                                html += "<li rel='0' class='auto_radio_li auto_radio_li_checked' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                            } else {
                                html += "<li rel='0' class='auto_radio_li' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                            }
                        } else {
                            html += "<li rel='0' class='auto_radio_li " + (d.default ? "auto_radio_li_checked" : "") + "' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                        }
                    });
                    html += "</ul>";
                } else {
                    var selfVal = self.val();
                    $.each(array, function (index, d) {
                        if (selfVal) {
                            if (selfVal.toString() === (d.id).toString()) {
                                html += "<li rel='0' class='auto_radio_li auto_radio_li_checked' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                            } else {
                                html += "<li rel='0' class='auto_radio_li' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                            }
                        } else {
                            html += "<li rel='0' class='auto_radio_li " + (d.default ? "auto_radio_li_checked" : "") + "' v='" + d.id + "' d='" + date + "'>" + d.text + "</li>";
                        }
                    });
                    html += "</ul>";
                }
                self.hide();
                self.parent().append(html);
                self.appendTo(self.parent().find("#auto_radio_ul_" + date));
                $("#auto_radio_ul_" + date + " li").live("click", function () {
                    if (checkMore) {
                        var array = new Array();
                        var array1 = new Array();
                        if ($(this).hasClass('auto_radio_li_checked')) {
                            $(this).removeClass('auto_radio_li_checked');
                        } else {
                            $(this).addClass('auto_radio_li_checked');
                        }
                        $(".auto_radio_li_checked", $(this).parent("ul")).each(function () {
                            array.push($(this).attr('v'));
                            array1.push({id: $(this).attr('v'), text: $(this).text()});
                        });
                        self.val(array.toString());
                        array = null;
                        if (callback) {
                            callback(array1);
                        }
                    } else {
                        if ($(this).hasClass('auto_radio_li_checked')) {
                            $(this).removeClass('auto_radio_li_checked');
                            self.val('');
                        } else {
                            $("li", self.parent()).removeClass('auto_radio_li_checked');
                            $(this).addClass('auto_radio_li_checked');
                            self.val($(this).attr("v"));
                            if (callback) {
                                callback({id: $(this).attr("v"), text: $(this).text()});
                            }
                        }
                    }
                });
            });
        } else {
            console.log("----------------亲,你给我的数据格式不对,我无法为你生成控件?----------------");
        }
    };

    /**
     * 图片粘贴上传
     */
    $.fn.pasteImgEvent = function () {
        $(document).on({
            dragleave: function (e) {    //拖离 
                e.preventDefault();
            },
            drop: function (e) {  //拖后放 
                e.preventDefault();
            },
            dragenter: function (e) {    //拖进 
                e.preventDefault();
            },
            dragover: function (e) {    //拖来拖去 
                e.preventDefault();
            }
        });
        this.each(function () {
            var self = $(this);
            $(this)[0].removeEventListener('paste', function () {
                console.log("removing the paste event!");
            });
            $(this)[0].addEventListener('paste', function (e) {
                if (e.clipboardData && e.clipboardData.items[0].type.indexOf('image') > -1) {
                    var reader = new FileReader();
                    var file = e.clipboardData.items[0].getAsFile();
                    reader.onload = function (e) {
                        var xhr = new XMLHttpRequest();
                        var fd = new FormData();
                        fd.append('upfile', file);
                        xhr.open('POST', ycoa.getNoCacheUrl('/upload/imageUp.php'), true);
                        xhr.onload = function () {
                            var data = $.parseJSON(xhr.responseText);
                            if (data.url) {
                                self.append("<img src = '../../upload/" + data.url + "' style='max-width:100%;'></img>");
                            }
                        };
                        xhr.send(fd);
                    };
                    reader.readAsDataURL(file);
                }
            }, false);
        });
    };

})(jQuery);

if (__path__ != "/page/login.html") {
    if (!ycoa.isLogin()) {
        localStorage.setItem("loginError", "用户身份过期,请重新登录~");
        if (__path__ != "/page/index.html") {
            window.top.location.href = __host__ + "/page/login.html";
        } else {
            window.location.href = __host__ + "/page/login.html";
        }
    }
}

