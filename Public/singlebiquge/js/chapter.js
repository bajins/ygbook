function jumpPage() {
	if (event.keyCode == 37) location = preview_page;
	if (event.keyCode == 39) location = next_page;
	if (event.keyCode == 13) location = index_page;
}
document.onkeydown = jumpPage;
var layerindex;
$(function(){
	getload();
	$('#content').on('click', 'button', function(){
		getload();
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
		getdata(chapter_id, false);
	}
}
function getdata(cid, _async){
	if(window.localStorage){
		var localdata = window.localStorage.getItem(localpre + '_' + article_id + '_' + cid);
		if(localdata === null || localdata.length < 3){
			var res = '';
			$.ajax({
				type:'POST',
				url:'/home/index/ajaxchapter',
				data:{
					id:article_id, eKey:hash, cid:cid, basecid: chapter_id
				},
				async: true,
				dataType: "json",
				success: function(data){
					if(data.status == 'success'){
						layer.close(layerindex);
						res = data.info.content;
						window.localStorage.setItem(localpre + '_' + article_id + '_' + cid, res);
						if(document.getElementById("cload")){
							$('#cload').hide();
							$('#cload').html(data.info.chaptercont_par);
							window.localStorage.setItem(localpre + '_' + article_id + '_' + cid + '_clientkey', data.info.chaptercont_par);
						}
						if(!_async){
							$('#content').html(res);
							if(document.getElementById("cload")){
								content_init();
							}
							if(cid == chapter_id && nextcid > -1){
								getdata(nextcid, true);
							}
							if(cid == nextcid || nextcid == -1){
								getdata(prevcid, true);
							}
						}
					} else {
						if(!_async){
							$('#content').html('<p class="preload">转码失败，请重试！<button>重新载入</button></p>');
						}
					}
				}
			});
		} else {
			if(!_async){
				layer.close(layerindex);
				$('#content').html(localdata);
				if(document.getElementById("cload")){
					$('#cload').hide();
					var clientkey = window.localStorage.getItem(localpre + '_' + article_id + '_' + cid + '_clientkey');
					$('#cload').html(clientkey);
					content_init();
				}
			}
		}
	} else {
		var res = '';
		$.ajax({
			type:'POST',
			url:'/home/index/ajaxchapter',
			data:{
				id:article_id, eKey:hash, cid:cid, basecid: chapter_id
			},
			async: true,
			dataType: "json",
			success: function(data){
				if(data.status == 'success'){
					res = data.info.content;
					if(!_async){
						$('#content').html(res);
						if(document.getElementById("cload")){
							$('#cload').html(data.info.chaptercont_par);
							content_init();
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
}
