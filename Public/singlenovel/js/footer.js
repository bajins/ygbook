function updatecache(){
	$.ajax({
		type: 'get',
		url: '/home/index/updatecache',
		timeout: 5000,
		data: {id: bookid, hash: hash},
		dataType: 'json',
		success: function(data){
			if(data.status == 'error'){
				layer.msg('已是最新章节，暂无更新！');
			}
			$('#loadingtip').html('（更新成功！）');
			if(data.status == 'success'){
				layer.msg('最新章节抓取成功！');
				var newlisthtml=listhtml='';
				var chapterhtml = $('#chapterhtml').html();
				$.each(data.content, function(i, item){
					if(item.title){
						listhtml = chapterhtml.replace('{}', '');
						listhtml = listhtml.replace('{subid}', Math.floor(item.id/1000));
						listhtml = listhtml.replace('{id}', parseInt(item.id) + parseInt(index_rule));
						listhtml = listhtml.replace('{cid}', parseInt(item.cid) + parseInt(cindex_rule));
						listhtml = listhtml.replace('{dir}', item.cate);
						listhtml = listhtml.replace('{title}', item.title);
						newlisthtml += listhtml;
					}
				});
				$('#newchapter').html(newlisthtml);
			}
		},
		complete : function(xhr,status){
			if(status == 'timeout'){
				$('#loadingtip').html('（抓取超时，请重试！）');
				layer.msg('抓取超时，网络繁忙，请稍后重试！');
			}
		}
	});
}
$(function() {
	if(typeof(bookid) != "undefined"){
		setTimeout(updatecache, 2000);
	}
});
