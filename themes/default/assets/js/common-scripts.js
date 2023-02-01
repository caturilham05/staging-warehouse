if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.newbuttons = function()
{
    return {
        init: function() {
            var undo = this.button.addFirst('undo', 'Undo');
            var redo = this.button.addAfter('undo', 'redo', 'Redo');
            this.button.addCallback(undo, this.buffer.undo);
            this.button.addCallback(redo, this.buffer.redo);
            this.button.remove('indent'); this.button.remove('outdent');
            this.button.remove('bold'); this.button.remove('italic');
            this.button.remove('deleted');
            var dropdown = {};
            dropdown.point1 = { title: 'Bold', func: this.newbuttons.bCallback };
            dropdown.point2 = { title: 'Italic', func: this.newbuttons.iCallback };
            dropdown.point3 = { title: 'Underline', func: this.newbuttons.uCallback };
            dropdown.point4 = { title: 'Deleted', func: this.newbuttons.delCallback };
            var button = this.button.addAfter('formatting', 'style', 'Emphasis');
            this.button.setAwesome('style', 'fa-bars');
            this.button.addDropdown(button, dropdown);
        },
        uCallback: function() { this.inline.format('u'); },
        bCallback: function() { this.inline.format('strong'); },
        iCallback: function() { this.inline.format('em'); },
        delCallback: function() { this.inline.format('del'); }
    };
};

!function(a){FormValidation.Framework.Bootstrap=function(b,c,d){c=a.extend(!0,{button:{selector:'[type="submit"]:not([formnovalidate])',disabled:"disabled"},err:{clazz:"help-block",parent:"^(.*)col-(xs|sm|md|lg)-(offset-){0,1}[0-9]+(.*)$"},icon:{valid:null,invalid:null,validating:null,feedback:"form-control-feedback"},row:{selector:".form-group",valid:"has-success",invalid:"has-error",feedback:"has-feedback"}},c),FormValidation.Base.apply(this,[b,c,d])},FormValidation.Framework.Bootstrap.prototype=a.extend({},FormValidation.Base.prototype,{_fixIcon:function(a,b){var c=this._namespace,d=a.attr("type"),e=a.attr("data-"+c+"-field"),f=this.options.fields[e].row||this.options.row.selector,g=a.closest(f);if("checkbox"===d||"radio"===d){var h=a.parent();h.hasClass(d)?b.insertAfter(h):h.parent().hasClass(d)&&b.insertAfter(h.parent())}0===g.find("label").length&&b.addClass("fv-icon-no-label"),0!==g.find(".input-group").length&&b.addClass("fv-bootstrap-icon-input-group").insertAfter(g.find(".input-group").eq(0))},_createTooltip:function(a,b,c){var d=this._namespace,e=a.data(d+".icon");if(e)switch(c){case"popover":e.css({cursor:"pointer","pointer-events":"auto"}).popover("destroy").popover({container:"body",content:b,html:!0,placement:"auto top",trigger:"hover click"});break;case"tooltip":default:e.css({cursor:"pointer","pointer-events":"auto"}).tooltip("destroy").tooltip({container:"body",html:!0,placement:"auto top",title:b})}},_destroyTooltip:function(a,b){var c=this._namespace,d=a.data(c+".icon");if(d)switch(b){case"popover":d.css({cursor:"","pointer-events":"none"}).popover("destroy");break;case"tooltip":default:d.css({cursor:"","pointer-events":"none"}).tooltip("destroy")}},_hideTooltip:function(a,b){var c=this._namespace,d=a.data(c+".icon");if(d)switch(b){case"popover":d.popover("hide");break;case"tooltip":default:d.tooltip("hide")}},_showTooltip:function(a,b){var c=this._namespace,d=a.data(c+".icon");if(d)switch(b){case"popover":d.popover("show");break;case"tooltip":default:d.tooltip("show")}}}),a.fn.bootstrapValidator=function(b){var c=arguments;return this.each(function(){var d=a(this),e=d.data("formValidation")||d.data("bootstrapValidator"),f="object"==typeof b&&b;e||(e=new FormValidation.Framework.Bootstrap(this,a.extend({},{events:{formInit:"init.form.bv",formPreValidate:"prevalidate.form.bv",formError:"error.form.bv",formSuccess:"success.form.bv",fieldAdded:"added.field.bv",fieldRemoved:"removed.field.bv",fieldInit:"init.field.bv",fieldError:"error.field.bv",fieldSuccess:"success.field.bv",fieldStatus:"status.field.bv",localeChanged:"changed.locale.bv",validatorError:"error.validator.bv",validatorSuccess:"success.validator.bv"}},f),"bv"),d.addClass("fv-form-bootstrap").data("formValidation",e).data("bootstrapValidator",e)),"string"==typeof b&&e[b].apply(e,Array.prototype.slice.call(c,1))})},a.fn.bootstrapValidator.Constructor=FormValidation.Framework.Bootstrap}(jQuery);

function date(format, timestamp) {

    var that = this;
    var jsdate, f;
    // Keep this here (works, but for code commented-out below for file size reasons)
    // var tal= [];
    var txt_words = [
        'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    // trailing backslash -> (dropped)
    // a backslash followed by any character (including backslash) -> the character
    // empty string -> empty string
    var formatChr = /\\?(.?)/gi;
    var formatChrCb = function (t, s) {
        return f[t] ? f[t]() : s;
    };
    var _pad = function (n, c) {
        n = String(n);
        while (n.length < c) {
            n = '0' + n;
        }
        return n;
    };
    f = {
        // Day
        d: function () {
            // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () {
            // Shorthand day name; Mon...Sun
            return f.l()
                .slice(0, 3);
        },
        j: function () {
            // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () {
            // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () {
            // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function () {
            // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j();
            var i = j % 10;
            if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
                i = 0;
            }
            return ['st', 'nd', 'rd'][i - 1] || 'th';
        },
        w: function () {
            // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () {
            // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j());
            var b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5);
        },

        // Week
        W: function () {
            // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
            var b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function () {
            // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () {
            // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () {
            // Shorthand month name; Jan...Dec
            return f.F()
                .slice(0, 3);
        },
        n: function () {
            // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () {
            // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0))
                .getDate();
        },

        // Year
        L: function () {
            // Is leap year?; 0 or 1
            var j = f.Y();
            return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
        },
        o: function () {
            // ISO-8601 year
            var n = f.n();
            var W = f.W();
            var Y = f.Y();
            return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
        },
        Y: function () {
            // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () {
            // Last two digits of year; 00...99
            return f.Y()
                .toString()
                .slice(-2);
        },

        // Time
        a: function () {
            // am or pm
            return jsdate.getHours() > 11 ? 'pm' : 'am';
        },
        A: function () {
            // AM or PM
            return f.a()
                .toUpperCase();
        },
        B: function () {
            // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2;
            // Hours
            var i = jsdate.getUTCMinutes() * 60;
            // Minutes
            // Seconds
            var s = jsdate.getUTCSeconds();
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () {
            // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () {
            // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () {
            // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () {
            // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () {
            // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () {
            // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () {
            // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function () {
            // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
            /*              return that.date_default_timezone_get();
             */
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () {
            // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0);
            // Jan 1
            var c = Date.UTC(f.Y(), 0);
            // Jan 1 UTC
            var b = new Date(f.Y(), 6);
            // Jul 1
            // Jul 1 UTC
            var d = Date.UTC(f.Y(), 6);
            return ((a - c) !== (b - d)) ? 1 : 0;
        },
        O: function () {
            // Difference to GMT in hour format; e.g. +0200
            var tzo = jsdate.getTimezoneOffset();
            var a = Math.abs(tzo);
            return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
        },
        P: function () {
            // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ':' + O.substr(3, 2));
        },
        T: function () {
            return 'UTC';
        },
        Z: function () {
            // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function () {
            // ISO-8601 date.
            return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () {
            // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () {
            // Seconds since UNIX epoch
            return jsdate / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = (timestamp === undefined ? new Date() : // Not provided
            (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
                new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}

function strtotime(text, now) {

    var parsed, match, today, year, date, days, ranges, len, times, regex, i, fail = false;

    if (!text) {
        return fail;
    }

    // Unecessary spaces
    text = text.replace(/^\s+|\s+$/g, '')
        .replace(/\s{2,}/g, ' ')
        .replace(/[\t\r\n]/g, '')
        .toLowerCase();

    match = text.match(
        /^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/);

    if (match && match[2] === match[4]) {
        if (match[1] > 1901) {
            switch (match[2]) {
                case '-':
                {
                    // YYYY-M-D
                    if (match[3] > 12 || match[5] > 31) {
                        return fail;
                    }

                    return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
                case '.':
                {
                    // YYYY.M.D is not parsed by strtotime()
                    return fail;
                }
                case '/':
                {
                    // YYYY/M/D
                    if (match[3] > 12 || match[5] > 31) {
                        return fail;
                    }

                    return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
            }
        } else if (match[5] > 1901) {
            switch (match[2]) {
                case '-':
                {
                    // D-M-YYYY
                    if (match[3] > 12 || match[1] > 31) {
                        return fail;
                    }

                    return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
                case '.':
                {
                    // D.M.YYYY
                    if (match[3] > 12 || match[1] > 31) {
                        return fail;
                    }

                    return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
                case '/':
                {
                    // M/D/YYYY
                    if (match[1] > 12 || match[3] > 31) {
                        return fail;
                    }

                    return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
            }
        } else {
            switch (match[2]) {
                case '-':
                {
                    // YY-M-D
                    if (match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)) {
                        return fail;
                    }

                    year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];
                    return new Date(year, parseInt(match[3], 10) - 1, match[5],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
                case '.':
                {
                    // D.M.YY or H.MM.SS
                    if (match[5] >= 70) {
                        // D.M.YY
                        if (match[3] > 12 || match[1] > 31) {
                            return fail;
                        }

                        return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
                                match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                    }
                    if (match[5] < 60 && !match[6]) {
                        // H.MM.SS
                        if (match[1] > 23 || match[3] > 59) {
                            return fail;
                        }

                        today = new Date();
                        return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                                match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000;
                    }

                    // invalid format, cannot be parsed
                    return fail;
                }
                case '/':
                {
                    // M/D/YY
                    if (match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)) {
                        return fail;
                    }

                    year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];
                    return new Date(year, parseInt(match[1], 10) - 1, match[3],
                            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000;
                }
                case ':':
                {
                    // HH:MM:SS
                    if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
                        return fail;
                    }

                    today = new Date();
                    return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
                            match[1] || 0, match[3] || 0, match[5] || 0) / 1000;
                }
            }
        }
    }

    // other formats and "now" should be parsed by Date.parse()
    if (text === 'now') {
        return now === null || isNaN(now) ? new Date()
            .getTime() / 1000 | 0 : now | 0;
    }
    if (!isNaN(parsed = Date.parse(text))) {
        return parsed / 1000 | 0;
    }

    date = now ? new Date(now * 1000) : new Date();
    days = {
        'sun': 0,
        'mon': 1,
        'tue': 2,
        'wed': 3,
        'thu': 4,
        'fri': 5,
        'sat': 6
    };
    ranges = {
        'yea': 'FullYear',
        'mon': 'Month',
        'day': 'Date',
        'hou': 'Hours',
        'min': 'Minutes',
        'sec': 'Seconds'
    };

    function lastNext(type, range, modifier) {
        var diff, day = days[range];

        if (typeof day !== 'undefined') {
            diff = day - date.getDay();

            if (diff === 0) {
                diff = 7 * modifier;
            } else if (diff > 0 && type === 'last') {
                diff -= 7;
            } else if (diff < 0 && type === 'next') {
                diff += 7;
            }

            date.setDate(date.getDate() + diff);
        }
    }

    function process(val) {
        var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
            type = splt[0],
            range = splt[1].substring(0, 3),
            typeIsNumber = /\d+/.test(type),
            ago = splt[2] === 'ago',
            num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1);

        if (typeIsNumber) {
            num *= parseInt(type, 10);
        }

        if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
            return date['set' + ranges[range]](date['get' + ranges[range]]() + num);
        }

        if (range === 'wee') {
            return date.setDate(date.getDate() + (num * 7));
        }

        if (type === 'next' || type === 'last') {
            lastNext(type, range, num);
        } else if (!typeIsNumber) {
            return false;
        }

        return true;
    }

    times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
    '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
    '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)';
    regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?';

    match = text.match(new RegExp(regex, 'gi'));
    if (!match) {
        return fail;
    }

    for (i = 0, len = match.length; i < len; i++) {
        if (!process(match[i])) {
            return fail;
        }
    }

    // ECMAScript 5 only
    // if (!match.every(process))
    //    return false;

    return (date.getTime() / 1000);
}

(function(p,z){function q(a){return!!(""===a||a&&a.charCodeAt&&a.substr)}function m(a){return u?u(a):"[object Array]"===v.call(a)}function r(a){return"[object Object]"===v.call(a)}function s(a,b){var d,a=a||{},b=b||{};for(d in b)b.hasOwnProperty(d)&&null==a[d]&&(a[d]=b[d]);return a}function j(a,b,d){var c=[],e,h;if(!a)return c;if(w&&a.map===w)return a.map(b,d);for(e=0,h=a.length;e<h;e++)c[e]=b.call(d,a[e],e,a);return c}function n(a,b){a=Math.round(Math.abs(a));return isNaN(a)?b:a}function x(a){var b=c.settings.currency.format;"function"===typeof a&&(a=a());return q(a)&&a.match("%v")?{pos:a,neg:a.replace("-","").replace("%v","-%v"),zero:a}:!a||!a.pos||!a.pos.match("%v")?!q(b)?b:c.settings.currency.format={pos:b,neg:b.replace("%v","-%v"),zero:b}:a}var c={version:"0.4.1",settings:{currency:{symbol:"$",format:"%s%v",decimal:".",thousand:",",precision:2,grouping:3},number:{precision:0,grouping:3,thousand:",",decimal:"."}}},w=Array.prototype.map,u=Array.isArray,v=Object.prototype.toString,o=c.unformat=c.parse=function(a,b){if(m(a))return j(a,function(a){return o(a,b)});a=a||0;if("number"===typeof a)return a;var b=b||".",c=RegExp("[^0-9-"+b+"]",["g"]),c=parseFloat((""+a).replace(/\((.*)\)/,"-$1").replace(c,"").replace(b,"."));return!isNaN(c)?c:0},y=c.toFixed=function(a,b){var b=n(b,c.settings.number.precision),d=Math.pow(10,b);return(Math.round(c.unformat(a)*d)/d).toFixed(b)},t=c.formatNumber=c.format=function(a,b,d,i){if(m(a))return j(a,function(a){return t(a,b,d,i)});var a=o(a),e=s(r(b)?b:{precision:b,thousand:d,decimal:i},c.settings.number),h=n(e.precision),f=0>a?"-":"",g=parseInt(y(Math.abs(a||0),h),10)+"",l=3<g.length?g.length%3:0;return f+(l?g.substr(0,l)+e.thousand:"")+g.substr(l).replace(/(\d{3})(?=\d)/g,"$1"+e.thousand)+(h?e.decimal+y(Math.abs(a),h).split(".")[1]:"")},A=c.formatMoney=function(a,b,d,i,e,h){if(m(a))return j(a,function(a){return A(a,b,d,i,e,h)});var a=o(a),f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format);return(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal))};c.formatColumn=function(a,b,d,i,e,h){if(!a)return[];var f=s(r(b)?b:{symbol:b,precision:d,thousand:i,decimal:e,format:h},c.settings.currency),g=x(f.format),l=g.pos.indexOf("%s")<g.pos.indexOf("%v")?!0:!1,k=0,a=j(a,function(a){if(m(a))return c.formatColumn(a,f);a=o(a);a=(0<a?g.pos:0>a?g.neg:g.zero).replace("%s",f.symbol).replace("%v",t(Math.abs(a),n(f.precision),f.thousand,f.decimal));if(a.length>k)k=a.length;return a});return j(a,function(a){return q(a)&&a.length<k?l?a.replace(f.symbol,f.symbol+Array(k-a.length+1).join(" ")):Array(k-a.length+1).join(" ")+a:a})};if("undefined"!==typeof exports){if("undefined"!==typeof module&&module.exports)exports=module.exports=c;exports.accounting=c}else"function"===typeof define&&define.amd?define([],function(){return c}):(c.noConflict=function(a){return function(){p.accounting=a;c.noConflict=z;return c}}(p.accounting),p.accounting=c)})(this);

(function(c){var b=function(d,e){this.options=e;this.$elementFilestyle=[];this.$element=c(d)};b.prototype={clear:function(){this.$element.val("");this.$elementFilestyle.find(":text").val("");this.$elementFilestyle.find(".badge").remove()},destroy:function(){this.$element.removeAttr("style").removeData("filestyle").val("");this.$elementFilestyle.remove()},disabled:function(d){if(d===true){if(!this.options.disabled){this.$element.attr("disabled","true");this.$elementFilestyle.find("label").attr("disabled","true");this.options.disabled=true}}else{if(d===false){if(this.options.disabled){this.$element.removeAttr("disabled");this.$elementFilestyle.find("label").removeAttr("disabled");this.options.disabled=false}}else{return this.options.disabled}}},buttonBefore:function(d){if(d===true){if(!this.options.buttonBefore){this.options.buttonBefore=true;if(this.options.input){this.$elementFilestyle.remove();this.constructor();this.pushNameFiles()}}}else{if(d===false){if(this.options.buttonBefore){this.options.buttonBefore=false;if(this.options.input){this.$elementFilestyle.remove();this.constructor();this.pushNameFiles()}}}else{return this.options.buttonBefore}}},icon:function(d){if(d===true){if(!this.options.icon){this.options.icon=true;this.$elementFilestyle.find("label").prepend(this.htmlIcon())}}else{if(d===false){if(this.options.icon){this.options.icon=false;this.$elementFilestyle.find(".fa").remove()}}else{return this.options.icon}}},input:function(e){if(e===true){if(!this.options.input){this.options.input=true;if(this.options.buttonBefore){this.$elementFilestyle.append(this.htmlInput())}else{this.$elementFilestyle.prepend(this.htmlInput())}this.$elementFilestyle.find(".badge").remove();this.pushNameFiles();this.$elementFilestyle.find(".group-span-filestyle").addClass("input-group-btn")}}else{if(e===false){if(this.options.input){this.options.input=false;this.$elementFilestyle.find(":text").remove();var d=this.pushNameFiles();if(d.length>0&&this.options.badge){this.$elementFilestyle.find("label").append(' <span class="badge">'+d.length+"</span>")}this.$elementFilestyle.find(".group-span-filestyle").removeClass("input-group-btn")}}else{return this.options.input}}},size:function(d){if(d!==undefined){var f=this.$elementFilestyle.find("label"),e=this.$elementFilestyle.find("input");f.removeClass("btn-lg btn-sm");e.removeClass("input-lg input-sm");if(d!="nr"){f.addClass("btn-"+d);e.addClass("input-"+d)}}else{return this.options.size}},buttonText:function(d){if(d!==undefined){this.options.buttonText=d;this.$elementFilestyle.find("label span").html(this.options.buttonText)}else{return this.options.buttonText}},buttonName:function(d){if(d!==undefined){this.options.buttonName=d;this.$elementFilestyle.find("label").attr({"class":"btn "+this.options.buttonName})}else{return this.options.buttonName}},iconName:function(d){if(d!==undefined){this.$elementFilestyle.find(".fa").attr({"class":".fa "+this.options.iconName})}else{return this.options.iconName}},htmlIcon:function(){if(this.options.icon){return'<span class="fa '+this.options.iconName+'"></span> '}else{return""}},htmlInput:function(){if(this.options.input){return'<input type="text" class="form-control '+(this.options.size=="nr"?"":"input-"+this.options.size)+'" disabled> '}else{return""}},pushNameFiles:function(){var d="",f=[];if(this.$element[0].files===undefined){f[0]={name:this.$element[0]&&this.$element[0].value}}else{f=this.$element[0].files}for(var e=0;e<f.length;e++){d+=f[e].name.split("\\").pop()+", "}if(d!==""){this.$elementFilestyle.find(":text").val(d.replace(/\, $/g,""))}else{this.$elementFilestyle.find(":text").val("")}return f},constructor:function(){var h=this,f="",g=h.$element.attr("id"),d=[],i="",e;if(g===""||!g){g="filestyle-"+c(".bootstrap-filestyle").length;h.$element.attr({id:g})}i='<span class="group-span-filestyle '+(h.options.input?"input-group-btn":"")+'"><label for="'+g+'" class="btn '+h.options.buttonName+" "+(h.options.size=="nr"?"":"btn-"+h.options.size)+'" '+(h.options.disabled?'disabled="true"':"")+">"+h.htmlIcon()+h.options.buttonText+"</label></span>";f=h.options.buttonBefore?i+h.htmlInput():h.htmlInput()+i;h.$elementFilestyle=c('<div class="bootstrap-filestyle input-group">'+f+"</div>");h.$elementFilestyle.find(".group-span-filestyle").attr("tabindex","0").keypress(function(j){if(j.keyCode===13||j.charCode===32){h.$elementFilestyle.find("label").click();return false}});h.$element.css({position:"absolute",clip:"rect(0px 0px 0px 0px)"}).attr("tabindex","-1").after(h.$elementFilestyle);if(h.options.disabled){h.$element.attr("disabled","true")}h.$element.change(function(){var j=h.pushNameFiles();if(h.options.input==false&&h.options.badge){if(h.$elementFilestyle.find(".badge").length==0){h.$elementFilestyle.find("label").append(' <span class="badge">'+j.length+"</span>")}else{if(j.length==0){h.$elementFilestyle.find(".badge").remove()}else{h.$elementFilestyle.find(".badge").html(j.length)}}}else{h.$elementFilestyle.find(".badge").remove()}});if(window.navigator.userAgent.search(/firefox/i)>-1){h.$elementFilestyle.find("label").click(function(){h.$element.click();return false})}}};var a=c.fn.filestyle;c.fn.filestyle=function(e,d){var f="",g=this.each(function(){if(c(this).attr("type")==="file"){var j=c(this),h=j.data("filestyle"),i=c.extend({},c.fn.filestyle.defaults,e,typeof e==="object"&&e);if(!h){j.data("filestyle",(h=new b(this,i)));h.constructor()}if(typeof e==="string"){f=h[e](d)}}});if(typeof f!==undefined){return f}else{return g}};c.fn.filestyle.defaults={buttonText:"Choose file",iconName:"fa-folder-open",buttonName:"btn-default",size:"nr",input:true,badge:true,icon:true,buttonBefore:false,disabled:false};c.fn.filestyle.noConflict=function(){c.fn.filestyle=a;return this};c(function(){c(".filestyle").each(function(){var e=c(this),d={input:e.attr("data-input")==="false"?false:true,icon:e.attr("data-icon")==="false"?false:true,buttonBefore:e.attr("data-buttonBefore")==="true"?true:false,disabled:e.attr("data-disabled")==="true"?true:false,size:e.attr("data-size"),buttonText:e.attr("data-buttonText"),buttonName:e.attr("data-buttonName"),iconName:e.attr("data-iconName"),badge:e.attr("data-badge")==="false"?false:true};e.filestyle(d)})})})(window.jQuery);
