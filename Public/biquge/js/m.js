function isWap(){
    var pda_user_agent_list = new Array("2.0 MMP", "240320", "AvantGo","BlackBerry", "Blazer",
			"Cellphone", "Danger", "DoCoMo", "Elaine/3.0", "EudoraWeb", "hiptop", "IEMobile", "KYOCERA/WX310K", "LG/U990",
			"MIDP-2.0", "MMEF20", "MOT-V", "NetFront", "Newt", "Nintendo Wii", "Nitro", "Nokia",
			"Opera Mini", "Opera Mobi",
			"Palm", "Playstation Portable", "portalmmm", "Proxinet", "ProxiNet",
			"SHARP-TQ-GX10", "Small", "SonyEricsson", "Symbian OS", "SymbianOS", "TS21i-10", "UP.Browser", "UP.Link",
			"Windows CE", "WinWAP", "Android", "iPhone", "iPod", "iPad", "Windows Phone", "HTC"/*, "GTB"*/);
	var pda_app_name_list = new Array("Microsoft Pocket Internet Explorer");

	var user_agent = navigator.userAgent.toString();
	for (var i=0; i<pda_user_agent_list.length; i++) {
		if (user_agent.indexOf(pda_user_agent_list[i]) >= 0) {
			return true;
		}
	}
	var appName = navigator.appName.toString();
	for (var i=0; i<pda_app_name_list.length; i++) {
		if (user_agent.indexOf(pda_app_name_list[i]) >= 0) {
			return true;
		}
	}
 
	return false;
}

if(isWap()){
    var currentHref=location.href;
    currentHref=currentHref.replace("www.","m.");
    location.href=currentHref;
}