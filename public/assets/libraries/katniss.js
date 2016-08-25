/**
 * Created by Nguyen Tuan Linh on 2015-12-11.
 */
!function (e) {
    function a(e) {
        return o ? o[e] : (o = require("unicode/category/So"), t = ["sign", "cross", "of", "symbol", "staff", "hand", "black", "white"].map(function (e) {
            return new RegExp(e, "gi")
        }), o[e])
    }

    function r(e, o) {
        e = e.toString(), "string" == typeof o && (o = {replacement: o}), o = o || {}, o.mode = o.mode || r.defaults.mode;
        for (var s, u = r.defaults.modes[o.mode], l = ["replacement", "multicharmap", "charmap", "remove", "lower"], n = 0, m = l.length; m > n; n++)s = l[n], o[s] = s in o ? o[s] : u[s];
        "undefined" == typeof o.symbols && (o.symbols = u.symbols);
        var i = [];
        for (var s in o.multicharmap)if (o.multicharmap.hasOwnProperty(s)) {
            var d = s.length;
            -1 === i.indexOf(d) && i.push(d)
        }
        for (var c, p, f, h = "", n = 0, m = e.length; m > n; n++) {
            if (f = e[n], !i.some(function (a) {
                    var r = e.substr(n, a);
                    return o.multicharmap[r] ? (n += a - 1, f = o.multicharmap[r], !0) : !1
                }) && (o.charmap[f] ? (f = o.charmap[f], c = f.charCodeAt(0)) : c = e.charCodeAt(n), o.symbols && (p = a(c)))) {
                f = p.name.toLowerCase();
                for (var y = 0, O = t.length; O > y; y++)f = f.replace(t[y], "");
                f = f.replace(/^\s+|\s+$/g, "")
            }
            f = f.replace(/[^\w\s\-\.\_~]/g, ""), o.remove && (f = f.replace(o.remove, "")), h += f
        }
        return h = h.replace(/^\s+|\s+$/g, ""), h = h.replace(/[-\s]+/g, o.replacement), h = h.replace(o.replacement + "$", ""), o.lower && (h = h.toLowerCase()), h
    }

    var o, t;
    if (r.defaults = {mode: "pretty"}, r.multicharmap = r.defaults.multicharmap = {
            "<3": "love",
            "&&": "and",
            "||": "or",
            "w/": "with"
        }, r.charmap = r.defaults.charmap = {
            "À": "A",
            "Á": "A",
            "Â": "A",
            "Ã": "A",
            "Ä": "A",
            "Å": "A",
            "Æ": "AE",
            "Ç": "C",
            "È": "E",
            "É": "E",
            "Ê": "E",
            "Ë": "E",
            "Ì": "I",
            "Í": "I",
            "Î": "I",
            "Ï": "I",
            "Ð": "D",
            "Ñ": "N",
            "Ò": "O",
            "Ó": "O",
            "Ô": "O",
            "Õ": "O",
            "Ö": "O",
            "Ő": "O",
            "Ø": "O",
            "Ù": "U",
            "Ú": "U",
            "Û": "U",
            "Ü": "U",
            "Ű": "U",
            "Ý": "Y",
            "Þ": "TH",
            "ß": "ss",
            "à": "a",
            "á": "a",
            "â": "a",
            "ã": "a",
            "ä": "a",
            "å": "a",
            "æ": "ae",
            "ç": "c",
            "è": "e",
            "é": "e",
            "ê": "e",
            "ë": "e",
            "ì": "i",
            "í": "i",
            "î": "i",
            "ï": "i",
            "ð": "d",
            "ñ": "n",
            "ò": "o",
            "ó": "o",
            "ô": "o",
            "õ": "o",
            "ö": "o",
            "ő": "o",
            "ø": "o",
            "ù": "u",
            "ú": "u",
            "û": "u",
            "ü": "u",
            "ű": "u",
            "ý": "y",
            "þ": "th",
            "ÿ": "y",
            "ẞ": "SS",
            "α": "a",
            "β": "b",
            "γ": "g",
            "δ": "d",
            "ε": "e",
            "ζ": "z",
            "η": "h",
            "θ": "8",
            "ι": "i",
            "κ": "k",
            "λ": "l",
            "μ": "m",
            "ν": "n",
            "ξ": "3",
            "ο": "o",
            "π": "p",
            "ρ": "r",
            "σ": "s",
            "τ": "t",
            "υ": "y",
            "φ": "f",
            "χ": "x",
            "ψ": "ps",
            "ω": "w",
            "ά": "a",
            "έ": "e",
            "ί": "i",
            "ό": "o",
            "ύ": "y",
            "ή": "h",
            "ώ": "w",
            "ς": "s",
            "ϊ": "i",
            "ΰ": "y",
            "ϋ": "y",
            "ΐ": "i",
            "Α": "A",
            "Β": "B",
            "Γ": "G",
            "Δ": "D",
            "Ε": "E",
            "Ζ": "Z",
            "Η": "H",
            "Θ": "8",
            "Ι": "I",
            "Κ": "K",
            "Λ": "L",
            "Μ": "M",
            "Ν": "N",
            "Ξ": "3",
            "Ο": "O",
            "Π": "P",
            "Ρ": "R",
            "Σ": "S",
            "Τ": "T",
            "Υ": "Y",
            "Φ": "F",
            "Χ": "X",
            "Ψ": "PS",
            "Ω": "W",
            "Ά": "A",
            "Έ": "E",
            "Ί": "I",
            "Ό": "O",
            "Ύ": "Y",
            "Ή": "H",
            "Ώ": "W",
            "Ϊ": "I",
            "Ϋ": "Y",
            "ş": "s",
            "Ş": "S",
            "ı": "i",
            "İ": "I",
            "ğ": "g",
            "Ğ": "G",
            "а": "a",
            "б": "b",
            "в": "v",
            "г": "g",
            "д": "d",
            "е": "e",
            "ё": "yo",
            "ж": "zh",
            "з": "z",
            "и": "i",
            "й": "j",
            "к": "k",
            "л": "l",
            "м": "m",
            "н": "n",
            "о": "o",
            "п": "p",
            "р": "r",
            "с": "s",
            "т": "t",
            "у": "u",
            "ф": "f",
            "х": "h",
            "ц": "c",
            "ч": "ch",
            "ш": "sh",
            "щ": "sh",
            "ъ": "u",
            "ы": "y",
            "ь": "",
            "э": "e",
            "ю": "yu",
            "я": "ya",
            "А": "A",
            "Б": "B",
            "В": "V",
            "Г": "G",
            "Д": "D",
            "Е": "E",
            "Ё": "Yo",
            "Ж": "Zh",
            "З": "Z",
            "И": "I",
            "Й": "J",
            "К": "K",
            "Л": "L",
            "М": "M",
            "Н": "N",
            "О": "O",
            "П": "P",
            "Р": "R",
            "С": "S",
            "Т": "T",
            "У": "U",
            "Ф": "F",
            "Х": "H",
            "Ц": "C",
            "Ч": "Ch",
            "Ш": "Sh",
            "Щ": "Sh",
            "Ъ": "U",
            "Ы": "Y",
            "Ь": "",
            "Э": "E",
            "Ю": "Yu",
            "Я": "Ya",
            "Є": "Ye",
            "І": "I",
            "Ї": "Yi",
            "Ґ": "G",
            "є": "ye",
            "і": "i",
            "ї": "yi",
            "ґ": "g",
            "č": "c",
            "ď": "d",
            "ě": "e",
            "ň": "n",
            "ř": "r",
            "š": "s",
            "ť": "t",
            "ů": "u",
            "ž": "z",
            "Č": "C",
            "Ď": "D",
            "Ě": "E",
            "Ň": "N",
            "Ř": "R",
            "Š": "S",
            "Ť": "T",
            "Ů": "U",
            "Ž": "Z",
            "ą": "a",
            "ć": "c",
            "ę": "e",
            "ł": "l",
            "ń": "n",
            "ś": "s",
            "ź": "z",
            "ż": "z",
            "Ą": "A",
            "Ć": "C",
            "Ę": "E",
            "Ł": "L",
            "Ń": "N",
            "Ś": "S",
            "Ź": "Z",
            "Ż": "Z",
            "ā": "a",
            "ē": "e",
            "ģ": "g",
            "ī": "i",
            "ķ": "k",
            "ļ": "l",
            "ņ": "n",
            "ū": "u",
            "Ā": "A",
            "Ē": "E",
            "Ģ": "G",
            "Ī": "I",
            "Ķ": "K",
            "Ļ": "L",
            "Ņ": "N",
            "Ū": "U",
            "ė": "e",
            "į": "i",
            "ų": "u",
            "Ė": "E",
            "Į": "I",
            "Ų": "U",
            "ț": "t",
            "Ț": "T",
            "ţ": "t",
            "Ţ": "T",
            "ș": "s",
            "Ș": "S",
            "ă": "a",
            "Ă": "A",
            "Ạ": "A",
            "Ả": "A",
            "Ầ": "A",
            "Ấ": "A",
            "Ậ": "A",
            "Ẩ": "A",
            "Ẫ": "A",
            "Ằ": "A",
            "Ắ": "A",
            "Ặ": "A",
            "Ẳ": "A",
            "Ẵ": "A",
            "Ẹ": "E",
            "Ẻ": "E",
            "Ẽ": "E",
            "Ề": "E",
            "Ế": "E",
            "Ệ": "E",
            "Ể": "E",
            "Ễ": "E",
            "Ị": "I",
            "Ỉ": "I",
            "Ĩ": "I",
            "Ọ": "O",
            "Ỏ": "O",
            "Ồ": "O",
            "Ố": "O",
            "Ộ": "O",
            "Ổ": "O",
            "Ỗ": "O",
            "Ơ": "O",
            "Ờ": "O",
            "Ớ": "O",
            "Ợ": "O",
            "Ở": "O",
            "Ỡ": "O",
            "Ụ": "U",
            "Ủ": "U",
            "Ũ": "U",
            "Ư": "U",
            "Ừ": "U",
            "Ứ": "U",
            "Ự": "U",
            "Ử": "U",
            "Ữ": "U",
            "Ỳ": "Y",
            "Ỵ": "Y",
            "Ỷ": "Y",
            "Ỹ": "Y",
            "Đ": "D",
            "ạ": "a",
            "ả": "a",
            "ầ": "a",
            "ấ": "a",
            "ậ": "a",
            "ẩ": "a",
            "ẫ": "a",
            "ằ": "a",
            "ắ": "a",
            "ặ": "a",
            "ẳ": "a",
            "ẵ": "a",
            "ẹ": "e",
            "ẻ": "e",
            "ẽ": "e",
            "ề": "e",
            "ế": "e",
            "ệ": "e",
            "ể": "e",
            "ễ": "e",
            "ị": "i",
            "ỉ": "i",
            "ĩ": "i",
            "ọ": "o",
            "ỏ": "o",
            "ồ": "o",
            "ố": "o",
            "ộ": "o",
            "ổ": "o",
            "ỗ": "o",
            "ơ": "o",
            "ờ": "o",
            "ớ": "o",
            "ợ": "o",
            "ở": "o",
            "ỡ": "o",
            "ụ": "u",
            "ủ": "u",
            "ũ": "u",
            "ư": "u",
            "ừ": "u",
            "ứ": "u",
            "ự": "u",
            "ử": "u",
            "ữ": "u",
            "ỳ": "y",
            "ỵ": "y",
            "ỷ": "y",
            "ỹ": "y",
            "đ": "d",
            "€": "euro",
            "₢": "cruzeiro",
            "₣": "french franc",
            "£": "pound",
            "₤": "lira",
            "₥": "mill",
            "₦": "naira",
            "₧": "peseta",
            "₨": "rupee",
            "₩": "won",
            "₪": "new shequel",
            "₫": "dong",
            "₭": "kip",
            "₮": "tugrik",
            "₯": "drachma",
            "₰": "penny",
            "₱": "peso",
            "₲": "guarani",
            "₳": "austral",
            "₴": "hryvnia",
            "₵": "cedi",
            "¢": "cent",
            "¥": "yen",
            "元": "yuan",
            "円": "yen",
            "﷼": "rial",
            "₠": "ecu",
            "¤": "currency",
            "฿": "baht",
            $: "dollar",
            "₹": "indian rupee",
            "©": "(c)",
            "œ": "oe",
            "Œ": "OE",
            "∑": "sum",
            "®": "(r)",
            "†": "+",
            "“": '"',
            "”": '"',
            "‘": "'",
            "’": "'",
            "∂": "d",
            "ƒ": "f",
            "™": "tm",
            "℠": "sm",
            "…": "...",
            "˚": "o",
            "º": "o",
            "ª": "a",
            "•": "*",
            "∆": "delta",
            "∞": "infinity",
            "♥": "love",
            "&": "and",
            "|": "or",
            "<": "less",
            ">": "greater",
            _: "-"
        }, r.defaults.modes = {
            rfc3986: {
                replacement: "-",
                symbols: !0,
                remove: null,
                lower: !0,
                charmap: r.defaults.charmap,
                multicharmap: r.defaults.multicharmap
            },
            pretty: {
                replacement: "-",
                symbols: !0,
                remove: /[.]/g,
                lower: !1,
                charmap: r.defaults.charmap,
                multicharmap: r.defaults.multicharmap
            }
        }, "undefined" != typeof define && define.amd) {
        for (var s in r.defaults.modes)r.defaults.modes.hasOwnProperty(s) && (r.defaults.modes[s].symbols = !1);
        define([], function () {
            return r
        })
    } else if ("undefined" != typeof module && module.exports)a(), module.exports = r; else {
        for (var s in r.defaults.modes)r.defaults.modes.hasOwnProperty(s) && (r.defaults.modes[s].symbols = !1);
        e.slug = r
    }
}(this);

function strRepeat(string, repeat) {
    for (var i = 0; i < repeat - 1; ++i) {
        string += string;
    }
    return string;
}

function toDigits(digit, minLength) {
    if (typeof minLength === 'undefined') {
        minLength = 2;
    }
    var max = Math.pow(10, minLength - 1);
    return digit < max ? strRepeat('0', minLength - 1) + digit : digit;
}

function urlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    else {
        return decodeURIComponent(results[1]) || 0;
    }
}

function isUnset(variable) {
    return variable == undefined || typeof variable === 'undefined' || variable == null;
}

function isSet(variable) {
    return !isUnset(variable);
}

function startWith(strSrc, strWith) {
    if (isSet(strSrc)) {
        return strSrc.toString().indexOf(strWith) == 0;
    }
    return false;
}

jQuery.fn.extend({
    registerSlugTo: function ($tos) {
        return this.on('keyup', function () {
            var slugTo = slug(jQuery(this).val(), {lower: true});
            $tos.each(function () {
                var $to = jQuery(this);
                if ($to.is('span')) {
                    $to.text(slugTo);
                }
                else {
                    $to.val(slugTo);
                }
            });
        });
    },
    registerSlug: function ($clones) {
        var $this = this;
        if (typeof $clones !== 'undefined') {
            $this.on('keyup', function () {
                $clones.each(function () {
                    var $clone = jQuery(this);
                    if ($clone.is('span')) {
                        $clone.text($this.val());
                    }
                    else {
                        $clone.val($this.val());
                    }
                });
            });
        }
        return $this.on('keydown', function (e) {
            if (e.shiftKey || e.ctrlKey || e.altKey || e.metaKey) {
                return false;
            }

            var code = e.keyCode;
            // 65-90,48-57,189,8,9,13,35-40,46,96-105
            return (code >= 65 && code <= 90) ||
                (code >= 48 && code <= 57) ||
                (code >= 35 && code <= 40) ||
                (code >= 96 && code <= 105) ||
                ([189, 8, 9, 13, 46].indexOf(code) != -1);
        });
    }
});

function NumberFormatHelper() {
    var DEFAULT_NUMBER_FORMAT = 'point_comma';
    this.DEFAULT_NUMBER_OF_DECIMAL_POINTS = 2;

    this.type = typeof SETTINGS_NUMBER_FORMAT === 'undefined' ? DEFAULT_NUMBER_FORMAT : SETTINGS_NUMBER_FORMAT;
    this.numberOfDecimalPoints = this.DEFAULT_NUMBER_OF_DECIMAL_POINTS;
}
NumberFormatHelper.prototype.modeInt = function (numberOfDecimalPoints) {
    this.mode(0);
};
NumberFormatHelper.prototype.modeNormal = function (numberOfDecimalPoints) {
    this.mode(this.DEFAULT_NUMBER_OF_DECIMAL_POINTS);
};
NumberFormatHelper.prototype.mode = function (numberOfDecimalPoints) {
    this.numberOfDecimalPoints = numberOfDecimalPoints;
};
NumberFormatHelper.prototype.format = function (number) {
    number = parseFloat(number);
    switch (this.type) {
        case 'point_comma':
            return this.formatPointComma(number);
        case 'point_space':
            return this.formatPointSpace(number);
        case 'comma_point':
            return this.formatCommaPoint(number);
        case 'comma_space':
            return this.formatCommaSpace(number);
        default:
            return number;
    }
};
NumberFormatHelper.prototype.formatPointComma = function (number) {
    return number.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
};
NumberFormatHelper.prototype.formatPointSpace = function (number) {
    return number.toFixed(this.numberOfDecimalPoints).replace(/(\d)(?=(\d{3})+\.)/g, '$1 ');
};
NumberFormatHelper.prototype.formatCommaPoint = function (number) {
    number = this.formatPointSpace(number);
    return number.replace('.', ',').replace(' ', '.');
};
NumberFormatHelper.prototype.formatCommaSpace = function (number) {
    number = this.formatPointSpace(number);
    return number.replace('.', ',');
};

function KatnissApi() {
    this.REQUEST_TYPE_POST = 'post';
    this.REQUEST_TYPE_GET = 'get';
    this.REQUEST_TYPE_PUT = 'put';
    this.REQUEST_TYPE_DELETE = 'delete';
}
KatnissApi.prototype.buildUrl = function (relativePath) {
    return startWith(relativePath, KATNISS_API_URL) ?
        relativePath : KATNISS_API_URL + '/' + relativePath;
};
KatnissApi.prototype.buildParams = function (params) {
    if (Object.prototype.toString.call(params) === '[object FormData]') {
        params.append('_app', JSON.stringify(KATNISS_APP));
        params.append('_settings', JSON.stringify(KATNISS_SETTINGS));
    }
    else {
        params._app = KATNISS_APP;
        params._settings = KATNISS_SETTINGS;
    }
    return params;
};
KatnissApi.prototype.post = function (relativePath, params, done, fail) {
    jQuery.post(
        this.buildUrl(relativePath),
        this.buildParams(params)
    ).done(function (response) {
        if (isSet(done)) {
            done.callback(this, response);
        }
    }).fail(function (response) {
        if (isSet(fail)) {
            fail.callback(this, response);
        }
    });
};
KatnissApi.prototype.request = function (relativePath, requestType, params, options) {
    if (!isSet(options)) {
        options = {};
    }
    options.type = requestType;
    options.data = this.buildParams(params);
    if (!isSet(options.dataType)) {
        options.dataType = 'json';
    }
    jQuery.ajax(this.buildUrl(relativePath), options);
};