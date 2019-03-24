var nowpage = 1,
    perpage = 50,
    maxpage = 1,
    orderway, thispage = 1;
maxpage = Math.ceil($('#chapterlist li').length / perpage);
$(function() {
    $('#loadingbg').on('click', function() {
        $('#loadingbg').hide();
    });
    $('.pt-card-7 li').on('click', function() {
        var burl = $(this).find('a:eq(0)').attr('href');
        if (burl) {
            window.location.href = burl;
        }
    });
    $('[rel^=chapterstat]').html('<span>' + nowpage + '/' + maxpage + '页</span><i class="fa fa-angle-down pt-dir-icon"></i>');
    $('a .fa-search').on('click', function() {
        $('.ptm-search').toggle();
        $('.searchinput').focus();
    });
    $('form[name=searchinline],form[name=searchbtm]').submit(function() {
        if (!$(this).children('.searchinput').val()) {
            alert('请输入要搜索的作品名或作者名，宁可少字不能错字哦！');
            return false;
        } else {
            $('#loadingbg').show();
            return true;
        }
    });
    if ($('#chapterlist li').length > 0 || $('.searchbtn').length > 0) {
        $('body').append('<div id="loadingbg"><i class="ptm-iconfont fa fa-spinner fa-spin"></i></div>');
    }
    $('[rel^=nextpage]').on('click', function() {
        $('#loadingbg').show();
        setTimeout("showChapterByPage(nowpage+1)", 1000);
    });
    $('[rel^=prevpage]').on('click', function() {
        $('#loadingbg').show();
        setTimeout("showChapterByPage(nowpage-1)", 1000);
    });
    $('[rel^=allchapter]').on('click', function() {
        if ($('#chapterlist').css('height') != 'auto') {
            $('#chapterlist').css('height', 'auto');
            $('#chapterlist li').show();
            $(this).text('部分');
            maxpage = 1;
        } else {
            $('#chapterlist').css('height', '1749px');
            maxpage = Math.ceil($('#chapterlist li').length / perpage);
            $(this).text('全部');
        }
    });
    $('[rel^=reversechapter]').on('click', function() {
        $('#loadingbg').show();
        if (orderway == 'desc') {
            $(this).text('正序');
            setTimeout("showChapterByOrder('asc')", 1000);
        } else {
            $(this).text('倒序');
            setTimeout("showChapterByOrder('desc')", 1000);
        }
        setTimeout("showChapterByPage(1)", 1000);
    });
    $('[rel^=chapterstat]').on('click', function() {
        var selhtml = '';
        for (var i = 0; i < maxpage; i++) {
            if (i == nowpage - 1) {
                var current_li = ' class="active"';
            }
            selhtml += '<li data-cpage="' + (i + 1) + '" ' + current_li + '>' + (i * perpage + 1) + '章 - ' + ((i + 1) * perpage) + '章</li>';
            current_li = '';
        }
        $('.pt-dir-sel ul').html(selhtml);
        $('.sel').removeClass('ptm-hide');
    })
    $('.ptm-alert-shade').on('click', function() {
        $('.sel').addClass('ptm-hide');
    })
    $('.pt-dir-sel').on('click', 'li', function() {
        thispage = parseInt($(this).data('cpage'));
        if (thispage != nowpage) {
            $('.sel').addClass('ptm-hide');
            $('#loadingbg').show();
            setTimeout("showChapterByPage(thispage)", 1000);
        }
    });
    if ("undefined" != typeof bookid) {
        setTimeout(updatecache, 2000);
    }
});

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
    nowpage = page;
    $('[rel^=chapterstat]').html('<span>' + page + '/' + maxpage + '页</span><i class="fa fa-angle-down pt-dir-icon"></i>');
    $('#loadingbg').hide();
}

function showChapterByOrder(sc) {
    orderway = sc;
    $('#chapterlist li').each(function() {
        $(this).prependTo('#chapterlist');
    });
    $('#loadingbg').hide();
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
            $('#loading-tip').html('<span class="ptm-text-success"><i class="ptm-iconfont fa fa-info-circle"></i> 机器人已为您抓取最新章节</span>');
            $('#loading-tip1').html('<i class="ptm-iconfont fa fa-info-circle"></i> 已是最新');
            $('#loading-tip1').addClass('ptm-text-success');
        },
        complete: function(xhr, status) {
            if (status == 'timeout') {
                $('#loading-tip').html('抓取超时，网络繁忙，请稍后重试！');
                $('#loading-tip1').html('<i class="ptm-iconfont fa fa-warning"></i> 更新失败');
                $('#loading-tip1').addClass('ptm-text-warning');
                layer.open({ content: '抓取超时，网络繁忙，请稍后重试！', skin: 'msg', time: 2 });
            }
        }
    });
}