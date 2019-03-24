function jumpPage() {
	if (event.keyCode == 37) location = preview_page;
	if (event.keyCode == 39) location = next_page;
	if (event.keyCode == 13) location = index_page;
}
document.onkeydown = jumpPage;
var layerindex;
$(function(){
	getload();
	$('#content').on('click', '.preload button', function(){
		getload();
	});
	$('#content').on('click', '.loadnextpage button', function(){
		layerindex = layer.msg('小说转码中...', {
			icon: 16
			,shade: 0.01,time: 2
		});
		ajaxload('id='+article_id+'&eKey='+hash+'&cid='+chapter_id+'&basecid='+chapter_id+'&nextpage='+$(this).attr('data-nextpage')+'&pagehash='+$(this).attr('data-pagehash'), chapter_id, false);
	});
})
function getload(){
	if($('.preload').length <= 0){
		return;
	}
	var loadnum = Math.floor(getCookie('loadnum_' + article_id + '_' + chapter_id));
	if(loadnum >= 2){
		layerindex = layer.msg('转码失败，将进入源站阅读...', {
			icon: 16
			,shade: 0.01,time: 5
		});
		setCookie('loadnum_' + article_id + '_' + chapter_id, 0);
		$('#source').attr('src', sourceurl);
		$('#source').show();
		$('#ifexplorer').css('height', window.innerHeight - 150 + 'px');
		$('#content').hide();
	} else {
		setCookie('loadnum_' + article_id + '_' + chapter_id, loadnum + 1);
		layerindex = layer.msg('小说转码中...', {
			icon: 16
			,shade: 0.01,time: 2
		});
		$('#content').html(preloadhtml);
		ajaxload('id='+article_id+'&eKey='+hash+'&cid='+chapter_id+'&basecid='+chapter_id, chapter_id, false);
	}
}
function ajaxload(postdata, cid, _async){
	var res = '';
	$.ajax({
		type:'POST',
		url:'/home/index/ajaxchapter',
		data:postdata,
		async: true,
		dataType: "json",
		success: function(data){
			if(data.status == 'success'){
				res = data.info.content;
				if(!_async){
					var toloadhtml = '';
					if(data.nextpage){
						toloadhtml = '<p style="text-align:center" class="loadnextpage"><button data-nextpage="'+data.nextpage+'" data-pagehash="'+data.hash+'">本章未完，加载下一页>></button></p>';
					}
					$('#content').html(res+toloadhtml);
					if(document.getElementById("cload")){
						$('#cload').html(data.info.chaptercont_par + toloadhtml);
						content_init();
					}
					if(cid == chapter_id && nextcid > -1){
						ajaxload('id='+article_id+'&eKey='+hash+'&cid='+nextcid+'&basecid='+chapter_id, nextcid, true);
					}
					if(cid == nextcid || nextcid == -1){
						ajaxload('id='+article_id+'&eKey='+hash+'&cid='+prevcid+'&basecid='+chapter_id, prevcid, true);
					}
				}
			} else {
				if(!_async){
					$('#content').html('<p class="preload">转码失败，请重试！<button>重新载入</button></p>');
				}
			}
		}
	});
}