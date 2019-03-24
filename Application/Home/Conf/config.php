<?php
mobile_adaptation();
$_setting = F('setting');
$webhost = $_SERVER['HTTP_HOST'];
$pcdomain = $_setting['seo']['pcdomain'];
$mobiledomain = $_setting['seo']['mobiledomain'];
$datadomain = $webhost == $mobiledomain ? 'wap' : 'default';
!$_setting['seo']['pctheme'] && $_setting['seo']['pctheme'] = 'biquge';
!$_setting['seo']['waptheme'] && $_setting['seo']['waptheme'] = 'wap';
$theme = $_setting['seo']['pctheme'];
if($webhost == $mobiledomain){
	$theme = $_setting['seo']['waptheme'];
}
$domian_prefix = is_HTTPS() ? 'https://' : 'http://';
$htmlcacherules = array();
$htmlcacheon = false;
if(strexists($_setting['extra']['html_option'], 'index')){
	$htmlcacherule['index:index'] = array($webhost.'/home', 3600);
}
if(strexists($_setting['extra']['html_option'], 'list')){
	$htmlcacherule['index:showlist'] = array($webhost.'/cate/{cate}_{page}', 3600);
}
if(strexists($_setting['extra']['html_option'], 'view')){
	$htmlcacherule['index:view'] = array($webhost.'/{:subid}/{id}', 3600);
}
if(strexists($_setting['extra']['html_option'], 'chapter')){
	$htmlcacherule['index:showchapter'] = array($webhost.'/chapter/{:subid}/{id}/{cid}', 86400);
}
if(count($htmlcacherule) > 0){
	$htmlcacheon = true;
}
return array(
	'PCDOMAIN' => $pcdomain,
	'WAPDOMAIN' => $mobiledomain,
	'DATADOMAIN' => $datadomain,
	'PCHOST' => $domian_prefix . $pcdomain,
	'WAPHOST' => $domian_prefix . $mobiledomain,
	'NOWHOST' => $domian_prefix . $webhost,
	'PCDOMAIN' => $_setting['seo']['pcdomain'],
	'DEFAULT_THEME' => $theme,
	/* 静态缓存 */
	'HTML_CACHE_ON' => $htmlcacheon,
	'HTML_FILE_SUFFIX' => '.html',
	'HTML_CACHE_RULES' => $htmlcacherule,
);