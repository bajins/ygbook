function setreader(mode) {
    switch (mode) {
        case 'size-s':
            $('#BookText').css('font-size', '14px');
            setCookie('reader-size', 'small');
            $('.content_toolbar .ptm-pull-left button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=size-s]').removeClass('ptm-btn-outlined');
            break;
        case 'size-m':
            $('#BookText').css('font-size', '16px');
            setCookie('reader-size', 'middle');
            $('.content_toolbar .ptm-pull-left button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=size-m]').removeClass('ptm-btn-outlined');
            break;
        case 'size-l':
            $('#BookText').css('font-size', '20px');
            setCookie('reader-size', 'large');
            $('.content_toolbar .ptm-pull-left button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=size-l]').removeClass('ptm-btn-outlined');
            break;
        case 'mode-d':
            $('body').removeAttr('class').addClass('theme-default');
            setCookie('reader-bg', 'default');
            $('.content_toolbar .ptm-pull-right button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=mode-d]').removeClass('ptm-btn-outlined');
            break;
        case 'mode-p':
            $('body').removeAttr('class').addClass('theme-green');
            setCookie('reader-bg', 'green');
            $('.content_toolbar .ptm-pull-right button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=mode-p]').removeClass('ptm-btn-outlined');
            break;
        case 'mode-n':
            $('body').removeAttr('class').addClass('theme-moon');
            setCookie('reader-bg', 'night');
            $('.content_toolbar .ptm-pull-right button').removeClass('ptm-btn-outlined').addClass('ptm-btn-outlined');
            $('[data-tol^=mode-n]').removeClass('ptm-btn-outlined');
            break;
        default:
            break;
    }
}

function readerset() {
    var readersize = getCookie('reader-size');
    var readerbg = getCookie('reader-bg');
    if (readersize == 'small') {
        $('#BookText').css('font-size', '14px');
        $('[data-tol^=size-s]').removeClass('ptm-btn-outlined');
    } else if (readersize == 'large') {
        $('#BookText').css('font-size', '20px');
        $('[data-tol^=size-l]').removeClass('ptm-btn-outlined');
    } else {
        $('#BookText').css('font-size', '16px');
        $('[data-tol^=size-m]').removeClass('ptm-btn-outlined');
    }
    if (readerbg == 'green') {
        $('body').removeAttr('class').addClass('theme-green');
        $('[data-tol^=mode-p]').removeClass('ptm-btn-outlined');
    } else if (readerbg == 'night') {
        $('body').removeAttr('class').addClass('theme-moon');
        $('[data-tol^=mode-n]').removeClass('ptm-btn-outlined');
    } else {
        $('body').removeAttr('class').addClass('theme-default');
        $('[data-tol^=mode-d]').removeClass('ptm-btn-outlined');
    }
}

function setCookie(name, value) {
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}

function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)) {
        return unescape(arr[2]);
    } else {
        return null;
    }
}

$(function() {
    $('.ptm-title').html(articlename + '[' + $('.ptm-title').html() + ']');
    getload();
    $('#BookText').on('click', '.preload button', function() {
        getload();
    });
    $('#BookText').on('click', '.loadnextpage button', function() {
        layer.open({ type: 2, content: '内容转码中', time: 1 });
        ajaxload('id=' + article_id + '&eKey=' + hash + '&cid=' + chapter_id + '&basecid=' + chapter_id + '&nextpage=' + $(this).attr('data-nextpage') + '&pagehash=' + $(this).attr('data-pagehash'), chapter_id, false);
    });
    $('.fa-search').on('click', function() {
        location.href = '/home/search';
    });
    $('.content_toolbar button').on('click', function() {
        setreader($(this).data('tol'));
    });
    readerset();
})

function getload() {
    if ($('.preload').length <= 0) {
        return;
    }
    var loadnum = Math.floor(getCookie('loadnum_' + article_id + '_' + chapter_id));
    if (loadnum >= 2) {
        layer.open({ type: 2, content: '转码失败，将进入源站阅读...', time: 3 });
        setCookie('loadnum_' + article_id + '_' + chapter_id, 0);
        $('#source').attr('src', sourceurl);
        $('#source').show();
        $('#ifexplorer').css('height', window.innerHeight - 51 + 'px');
        $('.pt-reader').hide();
    } else {
        setCookie('loadnum_' + article_id + '_' + chapter_id, loadnum + 1);
        layer.open({ type: 2, content: '内容转码中', time: 1 });
        $('#BookText').html(preloadhtml);
        ajaxload('id=' + article_id + '&eKey=' + hash + '&cid=' + chapter_id + '&basecid=' + chapter_id, chapter_id, false);
    }
}

function ajaxload(postdata, cid, _async) {
    var res = '';
    $.ajax({
        type: 'POST',
        url: '/home/index/ajaxchapter',
        data: postdata,
        async: true,
        dataType: "json",
        success: function(data) {
            if (data.status == 'success') {
                res = data.info.content;
                if (!_async) {
                    var toloadhtml = '';
                    if (data.nextpage) {
                        toloadhtml = '<p style="text-align:center" class="loadnextpage"><button data-nextpage="' + data.nextpage + '" data-pagehash="' + data.hash + '">本章未完，加载下一页>></button></p>';
                    }
                    $('#BookText').html(res + toloadhtml);
                    if (document.getElementById("cload")) {
                        $('#cload').html(data.info.chaptercont_par + toloadhtml);
                        content_init();
                    }
                    if (cid == chapter_id && nextcid > -1) {
                        ajaxload('id=' + article_id + '&eKey=' + hash + '&cid=' + nextcid + '&basecid=' + chapter_id, nextcid, true);
                    }
                    if (cid == nextcid || nextcid == -1) {
                        ajaxload('id=' + article_id + '&eKey=' + hash + '&cid=' + prevcid + '&basecid=' + chapter_id, prevcid, true);
                    }
                }
            } else {
                if (!_async) {
                    $('#BookText').html('<p class="preload">转码失败，请重试！<button>重新载入</button></p>');
                }
            }
        }
    });
}