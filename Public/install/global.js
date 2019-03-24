/**
/**
 +----------------------------------------------------------
 * 表单提交
 +----------------------------------------------------------
 */
function installSubmit(form_id) {
	var formParam = $("#"+form_id).serialize(); //序列化表格内容为字符串
	$.ajax({
		type: "POST",
		url: $("#"+form_id).attr("action")+'&do=callback',
		data: formParam,
		dataType: "html",
		success: function(html) {
			if (!html) {
				$("#"+form_id).submit();
			} else {
				$("#cue").html(html);
			}
		}
	});
}