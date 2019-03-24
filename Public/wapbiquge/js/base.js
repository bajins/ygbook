var nowpage = 1,
    perpage = 50,
    maxpage = 1,
    orderway, thispage = 1,
    optionhtml = '';
maxpage = Math.ceil($('#chapterlist li').length / perpage);
$(function() {
    if (maxpage > 1) {
        $('[rel^=chapterstat] em').text(maxpage);
        for (i = 1; i <= maxpage; i++) {
            optionhtml += '<option value="' + i + '">第' + i + '页</option>';
        }
        $('select#cpage').html(optionhtml);
        $('[rel^=nextpage]').on('click', function() {
            loading();
            newpage = nowpage + 1;
            setTimeout("showChapterByPage('" + newpage + "')", 1000);
        });
        $('[rel^=prevpage]').on('click', function() {
            loading();
            newpage = nowpage - 1;
            setTimeout("showChapterByPage('" + newpage + "')", 1000);
        });
        $('#cpage').on('change', function() {
            loading();
            newpage = $(this).val();
            setTimeout("showChapterByPage('" + newpage + "')", 1000);
        });
    }
    if ("undefined" != typeof bookid) {
        setTimeout(updatecache, 2000);
    }
});

function loading() {
    layer.open({ type: 2, content: '加载中……', time: 1 });
}

function showChapterByPage(page) {
    if (page >= maxpage) {
        alert('到最后一页了');
        // showChapterByPage(maxpage);
        return;
    } else if (page <= 1) {
        alert('到第一页了');
        // showChapterByPage(1);
        return;
    }
    $('#chapterlist li').each(function(i) {
        if (i > page * perpage - 1 || i < (page - 1) * perpage) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
    nowpage = Math.floor(page);
    $('#cpage').val(nowpage);
}

function showChapterByOrder(sc) {
    orderway = sc;
    $('#chapterlist li').each(function() {
        $(this).prependTo('#chapterlist');
    });
}

function updatecache() {
    $.ajax({
        type: 'get',
        url: '/home/index/updatecache',
        data: { id: bookid, hash: hash },
        dataType: 'json',
        success: function(data) {
            if (data.status == 'error') {
                layer.open({ content: '已是最新章节，暂无更新！', skin: 'msg', time: 2 });
            }
            if (data.status == 'success') {
                layer.open({ content: '最新章节抓取成功！', skin: 'msg', time: 2 });
                var newlisthtml = listhtml = '';
                var chapterhtml = $('#chapterhtml').html();
                $.each(data.content, function(i, item) {
                    if (item.title) {
                        listhtml = chapterhtml.replace('{}', '');
                        listhtml = listhtml.replace('{subid}', Math.floor(item.id / 1000));
                        listhtml = listhtml.replace('{id}', parseInt(item.id) + parseInt(index_rule));
                        listhtml = listhtml.replace('{cid}', parseInt(item.cid) + parseInt(cindex_rule));
                        listhtml = listhtml.replace('{dir}', item.cate);
                        listhtml = listhtml.replace('{title}', item.title);
                        newlisthtml += listhtml;
                    }
                });
                $('#newchaperlist').html(newlisthtml);
            }
            $('.loadingtip').text('已更新');
        },
        complete: function(xhr, status) {
            if (status == 'timeout') {
                $('.loadingtip').text('更新超时');
                layer.open({ content: '抓取超时，网络繁忙，请稍后重试！', skin: 'msg', time: 2 });
            }
        }
    });
}