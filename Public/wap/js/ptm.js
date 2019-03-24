(function(window) {
    var ptm = {};
    var PTDialog = function(options) {
            var that = this;
            that.config = $.extend({
                title: "",
                content: "内容",
                shadeClose: true,
                anim: true
            }, options);
            that.show();
        },
        index = 0;
    window.PTDialogData = {
        timer: {},
        end: {}
    };
    PTDialog.prototype.show = function() {
        var that = this,
            config = that.config;
        that.id = 'ptm-alert' + index;
        var title = (function() {
            var titype = typeof config.title === 'object';
            return config.title ?
                '<p class="ptm-alert-title" style="' + (titype ? config.title[1] : '') + '">' + (titype ? config.title[0] : config.title) + '</p>' :
                '';
        }());
        var button = (function() {
            var btndom = '';
            if (config.okval) {
                if (config.cancelval) {
                    btndom = '<a class="ptm-alert-btn-ok">' + config.okval + '</a>';
                } else {
                    btndom = '<a class="ptm-alert-btn-ok" style="margin:0 20%">' + config.okval + '</a>';
                }
            }
            if (config.cancelval) {
                btndom = '<a class="ptm-alert-btn-cancel">' + config.cancelval + '</a>' + btndom;
            }
            if (btndom) {
                btndom = '<div class="ptm-alert-btn">' + btndom + '</div>'
            } else {
                config.time = config.time ? config.time : 2;
            }
            return btndom;
        }());
        var content = (function() {
            return '<p class="ptm-alert-content">' + config.content + '</p>';
        }());
        var html = '<div class="ptm-alert-shade ' + that.id + '"></div><div class="ptm-alert-box ' + that.id + '" data-index="' + index + '">' + title + content + button + '</div>';
        $('body').append(html);
        var elem = that.elem = $('#' + that.id)[0];
        setTimeout(function() {
            config.success && config.success($('.' + that.id));
        }, config.time * 1000);
        that.index = index++;
        that.action(config, elem);
    };
    PTDialog.prototype.action = function(config, elem) {
        var that = this;
        //自动关闭
        if (config.time) {
            PTDialogData.timer[that.index] = setTimeout(function() {
                $ptm.alert.close(that.index);
            }, config.time * 1000);
        }
        //确认取消
        $('.ptm-alert-btn-ok', elem).on('click', function() {
            config.okfunc && config.okfunc();
            $ptm.alert.close(that.index);
        });
        $('.ptm-alert-btn-cancel', elem).on('click', function() {
            config.cancelfunc && config.cancelfunc();
            $ptm.alert.close(that.index);
        });
        $('.ptm-alert-shade', elem).on('click', function() {
            $ptm.alert.close(that.index);
        });
        config.end && (PTDialogData.end[that.index] = config.end);
    };
    ptm.range = function(element, callback) {
        var time = null;
        var distance, offsetLeft, tooltipWidth, max, min;
        var _init = function() {
            max = element.attr('max');
            min = element.attr('min');
            distance = Math.abs(max - min);
            offsetLeft = element[0].offsetLeft;
            tooltipWidth = element.width() - 28;
            element.after('<div class="ptm-range-tip ptm-hide">' + element.val() + '</div>');
            var scaleWidth = (tooltipWidth / distance) * Math.abs(element.val() - min);
            element.next().css('left', (offsetLeft + scaleWidth - 11) + 'px');
            element.on("change", function() {
                _showTip();
            });
            element.on("touchmove", function() {
                _showTip();
            });
            element.on("touchend", function() {
                _hideTip();
            });
        }
        var _showTip = function() {
            element.next().removeClass("ptm-hide");
            var scaleWidth = (tooltipWidth / distance) * Math.abs(element.val() - min);
            element.next().css('left', (offsetLeft + scaleWidth - 11) + 'px');
            element.next().text(element.val());
            callback(element.val());
        };
        var _hideTip = function() {
            if (time) {
                clearTimeout(time);
            }
            time = setTimeout(function() {
                element.next().addClass("ptm-hide");
            }, 1500);
        };
        _init();
    };
    ptm.alert = {
        //无按钮
        open: function(option) {
            option = option ? option : {};
            return new PTDialog(option);
        },
        close: function(index) {
            var ibox = $('.ptm-alert' + index);
            if (!ibox) return;
            ibox.remove();
            clearTimeout(PTDialogData.timer[index]);
            delete PTDialogData.timer[index];
            typeof PTDialogData.end[index] === 'function' && PTDialogData.end[index]();
            delete PTDialogData.end[index];
        },
        //关闭所有layer层
        closeAll: function() {
            var boxs = $('.ptm-alert-box');
            for (var i = 0, len = boxs.length; i < len; i++) {
                layer.close((boxs[0].data('index') | 0));
            }
        }
    };
    ptm.toast = function(con) {
        var text = con ? con : '加载中...';
        var html = '<div class="ptm-toast"><div class="ptm-toast-area"><p class="ptm-toast-content">' + text + '</p></div></div>';
        $('.ptm-toast').remove();
        $('body').append(html);
        $('.ptm-toast').fadeIn(300);
        setTimeout(function() {
            $('.ptm-toast').fadeOut(300)
        }, 1500);
    };
    ptm.loading = {
        open: function(con) {
            var text = con ? con : '加载中...';
            var html = '<div class="ptm-loading-toast"><div class="ptm-loading-area"><p class="ptm-loading-content"><i class="ptm-loading-anim"></i>' + text + '</p></div></div>';
            $('.ptm-loading-toast').remove();
            $('body').append(html);
            $('.ptm-loading-toast').show(300);
        },
        close: function() {
            $('.ptm-loading-toast').hide(300);
            $('.ptm-loading-toast').remove();
        }
    };
    ptm.tab = function(nav, con) {
        $(nav).on('click', function() {
            $(this).addClass('active').siblings().removeClass('active');
            $(con).eq($(this).index()).addClass('active').siblings().removeClass('active');
        })
    };
    ptm.actionsheet = {
        open: function() {
            var mask = $('.ptm-actionsheet-mask');
            var Actionsheet = $('.ptm-actionsheet');
            Actionsheet.addClass('ptm-actionsheet-toggle');
            mask.show().addClass('ptm-actionsheet-mask-toggle').click(function() {
                $ptm.actionsheet.close();
            });
            $('#actionsheet_cancel').click(function() {
                $ptm.actionsheet.close();
            });
            Actionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
        },
        close: function() {
            var mask = $('.ptm-actionsheet-mask');
            var Actionsheet = $('.ptm-actionsheet');
            Actionsheet.removeClass('ptm-actionsheet-toggle');
            $('.ptm-actionsheet-mask').removeClass('ptm-actionsheet-mask-toggle');
            Actionsheet.on('transitionend', function() {
                $('.ptm-actionsheet-mask').hide();
            }).on('webkitTransitionEnd', function() {
                $('.ptm-actionsheet-mask').hide();
            })
        }
    }
    window.$ptm = ptm;
})(window, $);