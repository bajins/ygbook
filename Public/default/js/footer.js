function updatecache(){
	$.ajax({
		type: 'get',
		url: '/home/index/updatecache',
		data: {id: bookid, hash: hash},
		dataType: 'json',
		success: function(data){
			if(data.status == 'success'){
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
				$('#newchapter dl').html(newlisthtml);
				
			}
			$('.loading-tip').hide();
			$('.loading-success').show();
		}
	});
}
//返回顶部
function gotoTop(min_height){
	var gotoTop_html = '<div id="gotoTop">返回顶部</div>';
	$("#footer").append(gotoTop_html);
	$("#gotoTop").click(
		function(){$('html,body').animate({scrollTop:0},100);
	}).hover(
		function(){$(this).addClass("hover");},
		function(){$(this).removeClass("hover");
	});
	min_height ? min_height = min_height : min_height = 600;
	$(window).scroll(function(){
		var s = $(window).scrollTop();
		if( s > min_height){
			$("#gotoTop").fadeIn(100);
		}else{
			$("#gotoTop").fadeOut(200);
		};
	});
};
document.writeln('<div id="popbg"><div id="popwin"><img src=""><br>手机扫描二维码阅读</div></div>');
$(function() {
	gotoTop();
	setTimeout(updatecache, 2000);
	$('#towap').on('click', function(){
		var wapurl = encodeURIComponent($(this).attr('href'));
		$('#popwin img').attr('src', 'http://bshare.optimix.asia/barCode?site=weixin&url='+wapurl);
		$('#popbg').show();
		return false;
	});
	$('#popbg,#popwin').on('click', function(){
		$('#popbg').hide();
	});
});