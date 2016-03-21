/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CountDown = function() {
    var _self = this;

    _self.countDay = 60 * 60 * 24;
    _self.countHour = 60 * 60;
    _self.countMinute = 60;

    // 倒计时 剩余秒数
    _self.deadline = ko.observable(0);
    _self.divider = ko.observable(6);

    // 计时器 显示时间
    // Y: 年,M: 月,D: 日,h: 时,m: 分,s: 秒,
    _self.calendar = {
        Y: ko.observable("00"),
        M: ko.observable("00"),
        D: ko.observable("00"),
        h: ko.observable("00"),
        m: ko.observable("00"),
        s: ko.observable("00")
    };
    _self.validate = ko.computed(function() {
        if (_self.deadline() < 0) _self.deadline(0);
    });
    _self.compute = function(index, divisor, remainder, l) {
        if (this.divider() < index)
            return 0;
        l = l || 2;
        var r = parseInt(this.deadline() / divisor);
        r = (this.divider() == index) ? r : r % remainder;
        r = r.toString();
        var reg = new RegExp("\\b(?=(\\d{1," + (l - 1) + "})$)");
        l = r.length + 1 >= l && l || l - r.length - 1;
        return r.replace(reg, new Array(l).join("0")) || "00";
    };

    _self.set_Y = ko.computed(function() {
        if (_self.divider() < 6)
            return 0;
        _self.calendar.Y(parseInt(_self.deadline() / _self.countDay / 365));
    });
    _self.set_M = ko.computed(function() {
        _self.calendar.M(_self.compute(5, _self.countDay * 31, 12));
    });
    _self.set_D = ko.computed(function() {
        _self.calendar.D(_self.compute(4, _self.countDay, 31));
    });
    _self.set_h = ko.computed(function() {
        _self.calendar.h(_self.compute(3, _self.countHour, 24));
    });
    _self.set_m = ko.computed(function() {
        _self.calendar.m(_self.compute(2, _self.countMinute, 60));
    });
    _self.set_s = ko.computed(function() {
        _self.calendar.s(_self.compute(1, 1, 60, 2));
    });

    _self.start = function() {
        if (!window.cdInterval) {
            var data = _self;
            window.cdInterval = setInterval(function() {
                if (data.deadline() > 0) {
                    data.deadline(data.deadline() - 1);
                } else {
                    window.clearInterval(cdInterval);
                    _self.finish();
                }
            }, 1000);
        }
    };
    _self.stop = function() {
        if (window.cdInterval) {
            window.clearInterval(cdInterval);
            window.cdInterval = null;
        }
    };
    // 倒计时结束执行事件
    _self.finish = function() {

    };
};
