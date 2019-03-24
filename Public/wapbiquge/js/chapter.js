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
    getload();
    $('#BookText').on('click', '.preload button', function() {
        getload();
    });
    $('#BookText').on('click', '.loadnextpage button', function() {
        layer.open({ type: 2, content: '内容转码中', time: 1 });
        ajaxload('id=' + article_id + '&eKey=' + hash + '&cid=' + chapter_id + '&basecid=' + chapter_id + '&nextpage=' + $(this).attr('data-nextpage') + '&pagehash=' + $(this).attr('data-pagehash'), chapter_id, false);
    });
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