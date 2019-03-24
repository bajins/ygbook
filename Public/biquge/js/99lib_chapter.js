base64 = {
	map: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
	decode: function(a) {
		var b = binary = '';
		for (var i = 0; i < a.length; i++) {
			if (a.substr(i, 1) == '=') {
				break
			};
			var c = this.map.indexOf(a.charAt(i)).toString(2);
			binary += {
				1 : '00000',
				2 : '0000',
				3 : '000',
				4 : '00',
				5 : '0',
				6 : ''
			} [c.length] + c
		};
		binary = binary.match(/[0-1]{8}/g);
		for (var i = 0; i < binary.length; i++) {
			b += String.fromCharCode(parseInt(binary[i], 2))
		};
		return b
	}
};
function content_init(){
	var clientkey = $('#cload').html(),newhtml = [],newcode='',j=0;
	var e = base64.decode(clientkey).split(/[A-Z]+%/);
	for (var i = 0; i < e.length; i++) {
		if (e[i] < 5) {
			newhtml[e[i]] = $('#content').children('div').eq(i).html();
			j++
		} else {
			newhtml[e[i] - j] = $('#content').children('div').eq(i).html();
		}
	}
	for (var j = 0; j < e.length; j++) {
		newcode += newhtml[j] + '<br><br>';
	}
	$('#content').html(newcode);
}