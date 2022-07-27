$(function () {

    $("#slider-goTop").hide();

    $(window).scroll(function () {
        if ($(window).scrollTop() > 100) {
            $("#slider-goTop").fadeIn();
        } else {
            $("#slider-goTop").fadeOut();
        }
    });

    $("#slider-goTop").click(function () {
        $('body,html').animate({ scrollTop: 0 }, 500);
        return false;
    });

    $('#slider-chat,#slider-qq,#slider-phone,#slider-wechat').hover(
        function () {
            $(this).next().show();
        },
        function () {
            $(this).next().hide();
        }
    );
});


$(function () {
    function a() {
        var a = $(window).width(),
            b = (768 - a) / 768 + 1,
            c = 1;
        fmhPara = $(".feature-mi").height() < 641 || $(".feature-ai").height() < 641 || $(".feature-bi").height() < 641 ? 0 : 1, 768 > a ? ($(".mi-headline-bg").css("height", $(".feature-mi").height() + 28 * b * c + "px"), $(".ai-headline-bg").css("height", $(".feature-ai").height() + 28 * b * c + "px"), $(".bi-headline-bg").css("height", $(".feature-bi").height() + 28 * b * c + "px"), $(".ee-headline-bg").css("height", $(".feature-ee").height() + parseInt($(".feature-ee").css("padding-top")) + 20 * b + "px")) : ($(".mi-headline-bg").removeAttr("style"), $(".ai-headline-bg").removeAttr("style"), $(".bi-headline-bg").removeAttr("style"), $(".ee-headline-bg").removeAttr("style"))
    }
    setTimeout(function () {
        a()
    }, 100), $(window).resize(function () {
        a()
    })
}), function (a, b) {
    "use strict";
    "function" == typeof define && "object" == typeof define.amd ? define([], function () {
        return b(a)
    }) : a.SineWaves = b(a)
}(this, function () {
    "use strict";

    function a(a) {
        if (this.options = i.defaults(this.options, a), this.el = this.options.el, delete this.options.el, !this.el) return false; //throw "No Canvas Selected";鍒樻槑鎺掗敊娉ㄩ攢
        if (this.ctx = this.el.getContext("2d"), this.waves = this.options.waves, delete this.options.waves, !this.waves || !this.waves.length) throw "No waves specified";
        this.dpr = window.devicePixelRatio || 1, this.updateDimensions(), window.addEventListener("resize", this.updateDimensions.bind(this)), this.setupUserFunctions(), this.easeFn = i.getFn(n, this.options.ease, "linear"), this.rotation = i.degreesToRadians(this.options.rotate), i.isType(this.options.running, "boolean") && (this.running = this.options.running), this.setupWaveFns(), this.loop()
    }
    function b(a, b) {
        return i.isType(a, "number") ? a : (a = a.toString(), a.indexOf("%") > -1 ? (a = parseFloat(a), a > 1 && (a /= 100), b * a) : a.indexOf("px") > -1 ? parseInt(a, 10) : void 0)
    }
    Function.prototype.bind || (Function.prototype.bind = function (a) {
        if ("function" != typeof this) throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
        var b = Array.prototype.slice.call(arguments, 1),
            c = this,
            d = function () { },
            e = function () {
                return c.apply(this instanceof d && a ? this : a, b.concat(Array.prototype.slice.call(arguments)))
            };
        return d.prototype = this.prototype, e.prototype = new d, e
    });
    for (var c = ["ms", "moz", "webkit", "o"], d = 0; d < c.length && !window.requestAnimationFrame; ++d) window.requestAnimationFrame = window[c[d] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[c[d] + "CancelAnimationFrame"] || window[c[d] + "CancelRequestAnimationFrame"];
    if (!window.requestAnimationFrame) {
        var e = 0;
        window.requestAnimationFrame = function (a) {
            var b = (new Date).getTime(),
                c = Math.max(0, 16 - (b - e)),
                d = window.setTimeout(function () {
                    a(b + c)
                }, c);
            return e = b + c, d
        }
    }
    window.cancelAnimationFrame || (window.cancelAnimationFrame = function (a) {
        clearTimeout(a)
    });
    var f = Math.PI / 180,

        g = 2.6 * Math.PI,
        h = Math.PI / 2,
        i = {},
        j = i.isType = function (a, b) {
            var c = {}.toString.call(a).toLowerCase();
            return c === "[object " + b.toLowerCase() + "]"
        },
        k = i.isFunction = function (a) {
            return j(a, "function")
        },
        l = i.isString = function (a) {
            return j(a, "string")
        },
        m = (i.isNumber = function (a) {
            return j(a, "number")
        }, i.shallowClone = function (a) {
            var b = {};
            for (var c in a) a.hasOwnProperty(c) && (b[c] = a[c]);
            return b
        }),
        n = (i.defaults = function (a, b) {
            j(b, "object") || (b = {});
            var c = m(a);
            for (var d in b) b.hasOwnProperty(d) && (c[d] = b[d]);
            return c
        }, i.degreesToRadians = function (a) {
            if (!j(a, "number")) throw new TypeError("Degrees is not a number");
            return a * f
        }, i.getFn = function (a, b, c) {
            return k(b) ? b : l(b) && k(a[b.toLowerCase()]) ? a[b.toLowerCase()] : a[c]
        }, {});
    n.linear = function (a, b) {
        return b
    }, n.sinein = function (a, b) {
        return b * (Math.sin(a * Math.PI - h) + 1) * .5
    }, n.sineout = function (a, b) {
        return b * (Math.sin(a * Math.PI + h) + 1) * .5
    }, n.sineinout = function (a, b) {
        return b * (Math.sin(a * g - h) + 1) * .5
    };
    var o = {};
    o.sine = function (a) {
        return Math.sin(a)
    }, o.sin = o.sine, o.sign = function (a) {
        return a = +a, 0 === a || isNaN(a) ? a : a > 0 ? 1 : -1
    }, o.square = function (a) {
        return o.sign(Math.sin(a * g))
    }, o.sawtooth = function (a) {
        return 2 * (a - Math.floor(a + .5))
    }, o.triangle = function (a) {
        return Math.abs(o.sawtooth(a))
    }, a.prototype.options = {
        speed: 10,
        rotate: 0,
        ease: "Linear",
        wavesWidth: "95%"
    }, a.prototype.setupWaveFns = function () {
        for (var a = -1, b = this.waves.length; ++a < b;) this.waves[a].waveFn = i.getFn(o, this.waves[a].type, "sine")
    }, a.prototype.setupUserFunctions = function () {
        i.isFunction(this.options.resizeEvent) && (this.options.resizeEvent.call(this), window.addEventListener("resize", this.options.resizeEvent.bind(this))), i.isFunction(this.options.initialize) && this.options.initialize.call(this)
    };
    var p = {
        timeModifier: 1,
        amplitude: 50,
        wavelength: 50,
        segmentLength: 10,
        lineWidth: 1,
        strokeStyle: "rgba(255, 255, 255, 0.2)",
        type: "Sine"
    };
    return a.prototype.getDimension = function (a) {
        return i.isNumber(this.options[a]) ? this.options[a] : i.isFunction(this.options[a]) ? this.options[a].call(this, this.el) : "width" === a ? this.el.clientWidth : "height" === a ? this.el.clientHeight : void 0
    }, a.prototype.updateDimensions = function () {
        var a = this.getDimension("width"),
            c = this.getDimension("height");
        this.width = this.el.width = a * this.dpr, this.height = this.el.height = c * this.dpr, this.el.style.width = a + "px", this.el.style.height = c + "px", this.waveWidth = b(this.options.wavesWidth, this.width), this.waveLeft = (this.width - this.waveWidth) / 2, this.yAxis = this.height / 2
    }, a.prototype.clear = function () {
        this.ctx.clearRect(0, 0, this.width, this.height)
    }, a.prototype.time = 0, a.prototype.update = function (a) {
        this.time = this.time - .007, "undefined" == typeof a && (a = this.time);
        var b = -1,
            c = this.waves.length;
        for (this.clear(), this.ctx.save(), this.rotation > 0 && (this.ctx.translate(this.width / 2, this.height / 2), this.ctx.rotate(this.rotation), this.ctx.translate(-this.width / 2, -this.height / 2)); ++b < c;) {
            var d = this.waves[b].timeModifier || 1;
            this.drawWave(a * d, this.waves[b])
        }
        this.ctx.restore(), b = void 0, c = void 0
    }, a.prototype.getPoint = function (a, b, c) {
        var d = a * this.options.speed + (-this.yAxis + b) / c.wavelength,
            e = c.waveFn.call(this, d, o),
            f = this.easeFn.call(this, b / this.waveWidth, c.amplitude);
        return d = b + this.waveLeft, e = f * e + this.yAxis, {
            x: d,
            y: e
        }
    }, a.prototype.drawWave = function (a, b) {
        b = i.defaults(p, b), this.ctx.lineWidth = b.lineWidth * this.dpr, this.ctx.strokeStyle = b.strokeStyle, this.ctx.lineCap = "butt", this.ctx.lineJoin = "round", this.ctx.beginPath(), this.ctx.moveTo(0, this.yAxis), this.ctx.lineTo(this.waveLeft, this.yAxis);
        var c, d;
        for (c = 0; c < this.waveWidth; c += b.segmentLength) d = this.getPoint(a, c, b), this.ctx.lineTo(d.x, d.y), d = void 0;
        c = void 0, b = void 0, this.ctx.lineTo(this.width, this.yAxis), this.ctx.stroke()
    }, a.prototype.running = !0, a.prototype.loop = function () {
        this.running === !0 && this.update(), window.requestAnimationFrame(this.loop.bind(this))
    }, a.prototype.Waves = o, a.prototype.Ease = n, a
}), $(function () {
    var a = new SineWaves({
        el: document.getElementById("waves"),

        speed: 4,
        width: function () {
            var a = $(document).width();

            //return 768 > a ? 3 * $("#waves").parent().width() : 1.4 * $("#waves").parent().width()
            return 1.4 * $("#waves").parent().width()
        },
        height: function () {
            return $("#waves").parent().height()
        },

        //wavesWidth: "100%",
        wavesWidth: "130%",//150
        ease: "SineInOut",
        waves: [{
            timeModifier: .5,
            lineWidth: 2,
            amplitude: 150,
            wavelength: 200,
            segmentLength: 1
        }, {
            timeModifier: .5,
            lineWidth: 2,
            amplitude: 100,
            wavelength: 150,
            segmentLength: 1
        }, {
            timeModifier: .5,
            lineWidth: 2,
            amplitude: 50,
            wavelength: 80,
            segmentLength: 1
        }],
        initialize: function () { },
        resizeEvent: function () {
            var a = this.ctx.createLinearGradient(0, 0, this.width, 0);

            //a.addColorStop(0, "rgba(255, 255, 255, 0)"), a.addColorStop(.5, "rgba(255, 255, 255, 0.2)"), a.addColorStop(1, "rgba(255, 255, 255, 0)");
            a.addColorStop(0, "rgba(255, 255, 255, 0)"), a.addColorStop(.1, "rgba(255, 255, 255, 0.2)"), a.addColorStop(1, "rgba(255, 255, 255, 0)");
            for (var b = -1, c = this.waves.length; ++b < c;) this.waves[b].strokeStyle = a;
            b = void 0, c = void 0, a = void 0
        }
    }),
        b = $("#waves"),
        c = $(document).scrollTop(),
        d = $(document).scrollTop() + $(window).height(),
        e = b.offset().top + b.height(),
        f = b.offset().top;
    (c > e || f > d) && (a.running = !1, a.update()), $(window).bind("scroll", function () {
        c = $(document).scrollTop(), d = $(document).scrollTop() + $(window).height(), e = b.offset().top + b.height(), f = b.offset().top, c > e || f > d ? (a.running = !1, a.update()) : (a.running = !0, a.update())
    })
});

$(function () {

    $('.web-chat').click(function () {
        var chatUrl = "http://p.qiao.baidu.com/cps/chat?siteId=10659290&userId=20073939";
        var iName = "啥";
        var iWidth = 720;
        var iHeight = 600;

        var iTop = (window.screen.availHeight - 30 - iHeight) / 2;

        var iLeft = (window.screen.availWidth - 10 - iWidth) / 2;
        window.open(chatUrl, iName, 'height=' + iHeight + ',width=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no');
    });

    $('#bs-example-navbar-collapse-1 li:last').addClass('hidden-sm');
});


function AddFavorite(title, url) {
    try {
        window.external.addFavorite(url, title);
    }
    catch (e) {
        try {
            window.sidebar.addPanel(title, url, "");
        }
        catch (e) {
            alert("Ctrl+D");
        }
    }
}


$(function () {
    /**通用-banner大图自定义缩放**/
    var zoomWidth = 992; //缩放阀值992px, 即所有小于992px的视口都会对原图进行缩放, 只是缩放比例不同
    var maxWidth = 1920; //最大宽度1920px
    var ratio = 1; //缩放比例
    var viewWidth = window.innerWidth; // 视口宽度
    var zoomSlider = function () {
        if (viewWidth < 768) { //当视口小于768时(移动端), 按992比例缩放
            ratio = viewWidth / zoomWidth; //视口宽度除以阀值, 计算缩放比例
        } else if (viewWidth < zoomWidth) { //当视口界于768与992之间时, bootstrap主宽度为750, 这区间图片缩放比例固定.
            ratio = zoomWidth / (zoomWidth + (zoomWidth - 750));
        } else { // PC端不缩放
            ratio = 1;
        }
        //ratio = viewWidth / zoomWidth; //视口宽度除以阀值, 计算缩放比例
        //ratio = (ratio<=1) ? ratio : 1; //如果比例值大于1, 说明视口宽度高于阀值, 则不进行任何缩放
        var width = maxWidth * ratio; //缩放宽度
        $(".my-slide img").each(function () {
            $(this).css({
                "width": width,
                "max-width": width,
                "margin-left": -(width - viewWidth) / 2
            }); //图片自适应居中, 图片宽度与视口宽度差除以2的值, 设置为负margin
        });
    }
    /**通用-我们的成绩等数字滚动特效**/
    var numOptions = {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.',
        prefix: '',
        suffix: ''
    }
    var numGroup = new Array(
        new CountUp("sum-apply", 0, 266, 0, 2.5, numOptions),
        new CountUp("sum-rate", 0, 34, 0, 2.5, numOptions),
        new CountUp("sum-urgent", 0, 10273, 0, 2.5, numOptions),
        // new CountUp("urgent-rate", 0, 100, 0, 2.5, numOptions)
    );
    var runNumber = function () {
        $('.run-number').each(function () {
            var oTop = $(this).offset().top;
            var sTop = $(window).scrollTop();
            var oHeight = $(this).height();
            var oIndex = $(this).index('.run-number');
            //console.log(oTop+'\r\n'+sTop+'\r\n'+oHeight+'\r\n'+$(window).height());
            if (oTop >= sTop && (oTop + (oHeight / 2)) < (sTop + $(window).height())) {
                numGroup[oIndex].start();
                //console.log('元素'+$(this).index('.run-number')+'可见');
            } else {
                //console.log('元素'+$(this).index('.run-number')+'不可见');
            }
        });
    }

    zoomSlider(); //页面加载时初始化并检查一次.
    runNumber(); //页面加载时判断一次
    /**视口发生变化时的事件**/
    $(window).resize(function () {
        viewWidth = window.innerWidth; // 重置视口宽度
        zoomSlider();//判断是否绽放banner
        runNumber();//判断是否执行动画
    });
    /**滚动事件**/
    $(window).scroll(function () {
        runNumber();
    });

    //首页-我们的服务
    $('.card-item').each(function () {
        $(this).mouseover(function () {
            $(this).addClass('card-active');
            $(this).siblings().removeClass('card-active');
            $(this).find(".btn").addClass('btn-outline-inverse').removeClass('btn-outline-blue');
            $(this).siblings().find(".btn").addClass('btn-outline-blue').removeClass('btn-outline-inverse');
        });
    });

});
