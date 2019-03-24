<?php
$_category = F('category');
$_setting = F('setting');
$ip = get_client_ip();
if ($_setting['seo']['blackiplist']) {
    $blacklist = explode('|', $_setting['seo']['blackiplist']);
    foreach ($blacklist as $key => $value) {
        if (strexists($ip, str_replace('*', '', $value))) {
            die('your ip is not allowed!');
        }
    }
}
foreach ($_category as $key => $value) {
    $dirs .= $dirs ? '|' . $value['dir'] : $value['dir'];
}
$_listurl = getext($_setting['seo']['listurl'], false);
$_listurl_extra = getext($_setting['seo']['listurl'], true);
$_viewurl = getext($_setting['seo']['viewurl']);
$_chapterurl = getext($_setting['seo']['chapterurl'], false);
$_sitemap_url = getext($_setting['seo']['sitemap_url']);
$_tag_url = getext($_setting['seo']['tag_url']);
$_author_url = getext($_setting['seo']['author_url']);
$_seoword_url = getext($_setting['seo']['seoword_url']);
$_topurl = getext($_setting['seo']['topurl']);
$_fullurl = getext($_setting['seo']['fullurl']);
$_allurl = getext($_setting['seo']['allurl'], false);
$_allurl_extra = getext($_setting['seo']['allurl'], true);
$_listurl_regx = '/^' . str_replace(array('{dir}', '/', '{page}'), array('([' . $dirs . ']+)', '\\/', '(\\d+)'), $_listurl) . '$/';
$_listurl_extra_regx = '/^' . str_replace(array('{dir}', '/', '{page}'), array('([' . $dirs . ']+)', '\\/', '(\\d+)'), $_listurl_extra) . '$/';
$_viewurl_regx = '/^' . str_replace(array('{dir}', '/', '{page}', '{id}', '{subid}', '{}', '{pinyin}'), array('([' . $dirs . ']+)', '\\/', '(\\d+)', '(\\d+)', '(\\d+)', '(\\w{0})', '(\\w+)'), $_viewurl) . '$/';
$_chapterurl_regx = '/^' . str_replace(array('{dir}', '/', '{id}', '{cid}', '{subid}', '{}', '{pinyin}'), array('([' . $dirs . ']+)', '\\/', '(\\d+)', '([0-9_]+)', '(\\d+)', '(\\w{0})', '(\\w+)'), $_chapterurl) . '$/';
$_sitemap_url_regx = '/^' . str_replace('/', '\\/', $_sitemap_url) . '$/';
$_tag_url_regx = '/^' . str_replace(array('/', '{id}', '{ename}'), array('\\/', '(\\d+)', '(.*+)'), $_tag_url) . '$/';
$_author_url_regx = '/^' . str_replace(array('/', '{author}'), array('\\/', '(.*)'), $_author_url) . '$/';
$_seoword_url_regx = '/^' . str_replace(array('/', '{id}', '{ename}'), array('\\/', '(\\d+)', '(.*+)'), $_seoword_url) . '$/';
$_topurl_regx = '/^' . str_replace('/', '\\/', $_topurl) . '$/';
$_fullurl_regx = '/^' . str_replace('/', '\\/', $_fullurl) . '$/';
$_allurl_regx = '/^' . str_replace('/', '\\/', $_allurl) . '$/';
$_allurl_extra_regx = '/^' . str_replace(array('/', '{page}'), array('\\/', '(\\d+)'), $_allurl_extra) . '$/';
function getext($xzv_2, $xzv_4 = false)
{
    if (!$xzv_4) {
        $xzv_2 = preg_replace('/\\{ellipsis\\}(.*?)\\{\\/ellipsis\\}/', '', $xzv_2);
    } else {
        $xzv_2 = str_replace(array('{ellipsis}', '{/ellipsis}'), array('', ''), $xzv_2);
    }
    $xzv_5 = strpos($xzv_2, '.') ? strpos($xzv_2, '.') + 1 : null;
    if ($xzv_5) {
        $xzv_3 = substr($xzv_2, $xzv_5, '5');
        $xzv_3 = str_replace('.' . $xzv_3, '', $xzv_2);
    }
    $xzv_6 = $xzv_5 ? $xzv_3 : $xzv_2;
    $xzv_6 = dimlstr($xzv_6);
    return $xzv_6;
}
function dimlstr($xzv_0)
{
    if (substr($xzv_0, -1) == '/') {
        $xzv_1 = substr($xzv_0, 0, strlen($xzv_0) - 1);
    } else {
        $xzv_1 = $xzv_0;
    }
    return $xzv_1;
}
return array('HOME_URL' => $_setting['seo']['webdir'] ? $_setting['seo']['webdir'] : '/', 'VIEW_RULE' => str_replace('{}', '', $_setting['seo']['viewurl']), 'CHAPTER_RULE' => str_replace('{}', '', $_setting['seo']['chapterurl']), 'SORT_RULE' => preg_replace('/\\{ellipsis\\}(.*?)\\{\\/ellipsis\\}/', '', $_setting['seo']['listurl']), 'ID_RULE' => $_setting['seo']['idrule'], 'CID_RULE' => $_setting['seo']['cidrule'], 'TAG_RULE' => $_setting['seo']['tag_url'], 'AUTHOR_RULE' => $_setting['seo']['author_url'], 'SEOWORD_RULE' => $_setting['seo']['seoword_url'], 'URL_HTML_SUFFIX' => '', 'DEFAULT_FILTER' => 'trim,htmlspecialchars', 'URL_MODEL' => 2, 'URL_ROUTER_ON' => true, 'URL_ROUTE_RULES' => array("{$_topurl_regx}" => 'home/index/showlist?cate=top', "{$_fullurl_regx}" => 'home/index/showlist?cate=full', "{$_allurl_regx}" => 'home/index/showlist?cate=all', "{$_allurl_extra_regx}" => 'home/index/showlist?cate=all&page=:1', "{$_viewurl_regx}" => 'home/index/view?cate=:1&id=:2', "{$_listurl_extra_regx}" => 'home/index/showlist?cate=:1&page=:2', "{$_listurl_regx}" => 'home/index/showlist?cate=:1', "{$_chapterurl_regx}" => 'home/index/showchapter?cate=:1&id=:2&cid=:3', "{$_sitemap_url_regx}" => 'home/extend/sitemapxml', "{$_tag_url_regx}" => 'home/extend/taglist?id=:1', "{$_author_url_regx}" => 'home/extend/author?author=:1', "{$_seoword_url_regx}" => 'home/extend/seoword?id=:1'), 'DATA_CACHE_SUBDIR' => true, 'DATA_PATH_LEVEL' => 2);