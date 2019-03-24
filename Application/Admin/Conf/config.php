<?php
$_setting = F('setting');
$webhost = $_SERVER['HTTP_HOST'];
$pcdomain = $_setting['seo']['pcdomain'];
$mobiledomain = $_setting['seo']['mobiledomain'];
$maindomain = 'default';
return array(
	'URL_MODEL' => 3,
	'PCDOMAIN' => $pcdomain,
	'WAPDOMAIN' => $mobiledomain,
	'MAINDOMAIN' => $maindomain,
	'LAYOUT_ON' => true,
	'LAYOUT_NAME' => 'layout',
	'URL_HTML_SUFFIX' => 'html',
	'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',
	'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',
);