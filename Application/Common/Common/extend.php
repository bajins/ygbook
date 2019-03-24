<?php
function pagelist_thinkphp($count, $perpage){
	$rollpage = 5;
	$page = new \Think\Page($count, $perpage);
	$page->setConfig('first', '首页');
	$page->setConfig('last', '末页');
	$page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%');
	$page->lastSuffix = false;
	$page->rollPage = $rollpage;
	return $page->show();
}
function deimg($url, $id, $articleurl, $isthumb=false, $isdecode=true){
	$setting = F('setting');
	$oid = intval($id + $setting['seo']['idrule']);
	$subid = floor($oid/1000);
	if($url == '/Public/images/nocover.jpg'){
		return $isthumb ? $url : '<img src="'.$url.'">';
	} else {
		if(substr($url , 0, 4) != 'http'){
			$imgurl = fillurl($articleurl, $url);
		} else {
			$imgurl = $url;
		}
		if($isdecode){
			if($imgurl){
				if($setting['seo']['piclocal_type'] == 'tolocal'){
					if(!strexists($imgurl, 'nocover') && !strexists($imgurl, 'nopic')){
						$caiji = new \Org\Util\Caiji;
						$imgdata = $caiji->get_url($imgurl, true);
						$imgurl = null;
						if($imgdata && $imgdata != ''){
							$datainfo = @unpack('C2chars', substr($imgdata, 0, 2));
							$datacode = intval($datainfo['chars1'].$datainfo['chars2']);
							if(in_array($datacode, array(255216, 7173, 6677, 13780))){
								$newimage = 'uploads/'.$subid.'/'.$oid.'.jpg';
								writefile('./' . $newimage, $imgdata);
								$imgurl = '/' . $newimage;
							}
						}
					} else {
						$imgurl = null;
					}
				} elseif($setting['seo']['piclocal_type'] == 'tocdn') {
					$qiniu = new \Org\Util\Qiniu;
					$imgurl = $qiniu->to_qiniu($imgurl);
				}
			}
			if(!$imgurl){
				$imgurl = '/Public/images/nocover.jpg';
			}
		}
		return $isthumb ? $imgurl : '<img src="'.$imgurl.'">';
	}
}

/* extra在type=list时表示mode，在其他情况下表示url */
function pickrun($type = 'list', $pid, $extra){
	$pick = new \Org\Util\Pick;
	switch ($type) {
		case 'list':
			if($extra == 'index'){
				$pick->picklist(null, 'initialize');
				return '';
			} elseif($extra == 'admin') {
				return $pick->picklist($pid, 'admin');
			} elseif($extra == 'test') {
				return $pick->picklist($pid, 'test');
			} else {
				$pick->picklist();
			}
			break;
		case 'content':
			return $pick->pickcont($extra, $pid);
			break;
		case 'chapter':
			return $pick->pickchapter($extra, $pid);
			break;
		case 'chaptercontent':
			return $pick->pickchaptercont($extra, $pid);
			break;
		default:
			return '';
			break;
	}
}

function spiderlog(){
	$spiderlog = new \Org\Util\Spider;
	$spiderlog->checkspider();
}

function cronlog($log){
	$spiderlog = new \Org\Util\Spider;
	$spiderlog->addlogs($log);
}

function showcover($imgurl){
	if(!$imgurl || strexists($imgurl, 'nocover')){
		$thumb = '/Public/images/nocover.jpg';
	} else {
		$thumb = $imgurl;
		$setting = F('setting');
		if($setting['seo']['piclocal_type'] == 'tocdn' && substr($thumb, 0, 4) != 'http' && substr($thumb, 0, 4) != '/Pub'){
			$thumb = $setting['extra']['qiniu_domian'] . $thumb;
		}
	}
	return $thumb;
}

function pushapi($id, $active = false){
	$setting = F('setting');
	if($setting['seo']['pushapi']){
		$pushsign = S('push_error_sign');
		$needpush = false;
		if($pushsign < strtotime(date('Y-m-d', NOW_TIME)) || $active){
			$needpush = true;
		}
		$check = M('articles')->where('id=%d', $id)->find();
		if($check['push'] == 0 && $needpush){
			$domian_prefix = is_HTTPS() ? 'https://' : 'http://';
			$pcdomain = $setting['seo']['pcdomain'];
			$pushurl = $domian_prefix . $pcdomain . reurl('view', array('id' => $check['id'], 'cate' => $check['cate'], 'posttime' => $check['posttime']));
			$api = htmlspecialchars_trans($setting['seo']['pushapi'], 'pick');
			$pushresult = push_curl($api, $pushurl);
			$pushres = json_decode($pushresult, true);
			if($pushres['success'] > 0){
				M('articles')->where('id=%d', $id)->setField('push', 1);
				if($active){
					S('push_error_sign', null);
				}
				$return['status'] = true;
				$return['info'] = $pushres;
				return $return;
			} elseif (in_array($pushres['error'], array(400, 401, 404))){
				S('push_error_sign', NOW_TIME, 3600);
				$return['status'] = false;
				$return['info'] = $pushres;
				return $return;
			}
		}
	}
}

function push_curl($api, $url){
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => $url,
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$return = curl_exec($ch);
	curl_close($ch);
	return $return;
}

function reurl($mode, $data, $domain = 'default'){
	$view_rule = C('VIEW_RULE');
	$viewlist_rule = C('VIEWLIST_RULE');
	$sort_rule = C('SORT_RULE');
	$tag_rule = C('TAG_RULE');
	$author_rule = C('AUTHOR_RULE');
	$seoword_rule = C('SEOWORD_RULE');
	$chapter_rule = C('CHAPTER_RULE');
	$id_rule = C('ID_RULE');
	$cid_rule = C('CID_RULE');
	$allurl = $setting['seo']['allurl'];
	$topurl = $setting['seo']['topurl'];
	$fullurl = $setting['seo']['fullurl'];
	$category = F('category');
	if($mode == 'view'){
		$subid = floor(($data['id'] + $id_rule)/1000);
		$rewriteurl = C('HOME_URL').str_replace(array('{dir}', '{id}', '{subid}', '{}', '{pinyin}'), array($data['cate'] ? $data['cate'] : $category['default']['dir'], $data['id'] + $id_rule, $subid, '', $data['pinyin']), $view_rule);
	} elseif($mode == 'viewlist') {
		$subid = floor(($data['id'] + $id_rule)/1000);
		$rewriteurl = C('HOME_URL').str_replace(array('{dir}', '{id}', '{subid}', '{}', '{pinyin}'), array($data['cate'] ? $data['cate'] : $category['default']['dir'], $data['id'] + $id_rule, $subid, '', $data['pinyin']), $viewlist_rule);
	} elseif($mode == 'tag') {
		$rewriteurl = C('HOME_URL').str_replace(array('{id}', '{ename}'), array($data['id'], $data['ename']), $tag_rule);
	} elseif($mode == 'author') {
		$rewriteurl = C('HOME_URL').str_replace('{author}', urlencode($data['author']), $author_rule);
	} elseif($mode == 'seoword') {
		$rewriteurl = C('HOME_URL').str_replace(array('{id}', '{ename}'), array($data['id'], $data['ename']), $seoword_rule);
	} elseif($mode == 'chapter') {
		$subid = floor(($data['id'] + $id_rule)/1000);
		if(strexists($chapter_rule, '{pinyin}') && !$data['pinyin']){
			$articledb = F('view/book/'.$subid.'/'.($data['id'] + $id_rule));
			$data['pinyin'] = $articledb['pinyin'];
		}
		$newcid = isset($data['sub']) ? ($data['cid'] + $cid_rule) . '_'.intval($data['sub']) : ($data['cid'] + $cid_rule);
		$rewriteurl = C('HOME_URL').str_replace(array('{dir}', '{id}', '{cid}', '{subid}', '{}', '{pinyin}'), array($data['cate'], $data['id'] + $id_rule, $newcid, $subid, '', $data['pinyin']), $chapter_rule);
	} else {
		if($data == 'all'){
			$setting = F('setting');
			$rewriteurl = C('HOME_URL').preg_replace('/\{ellipsis\}(.*?)\{\/ellipsis\}/', '', $allurl);
		} elseif($data == 'top') {
			$setting = F('setting');
			$rewriteurl = C('HOME_URL').$topurl;
		} else {
			$data = ($data == 'default' || !$data) ? $category['default']['dir'] : $data;
			$rewriteurl = C('HOME_URL').str_replace('{dir}', $data, $sort_rule);
		}
	}
	return $rewriteurl;
}

function getcate($dir, $return = NULL){
	$category = F('category');
	$cateurl = reurl('cate', $dir);
	$cate = $dir == $category['default']['dir'] ? 'default' : $dir;
	$catename = $category[$cate]['name'];
	switch ($return) {
		case 'href':
			$result = $cateurl;
			break;
		case 'text':
			$result = $catename;
			break;
		default:
			if($return){
				$result = '<'.$return.'><a href="'.$cateurl.'" title="'.$catename.'">'.$catename.'</a></'.$return.'>';
			} else {
				$result = '<a href="'.$cateurl.'" title="'.$catename.'">'.$catename.'</a>';
			}
			break;
	}
	return $result;
}

/*
 * 补全url，兼容https
 */
function fillurl($refurl,$surl) {
	if(strstr($surl, ' ')){
		$surl = explode(' ', $surl)[0];
	}
	$i = $pathStep = 0;
	$dstr = $pstr = $okurl = '';
	$refurl = trim($refurl);
	$surl = trim($surl);
	$urls = @parse_url($refurl);
	$scheme = $urls['scheme'] == 'https' ? 'https' : 'http';
	$basehost = ( (!isset($urls['port']) || $urls['port']=='80') ? $urls['host'] : $urls['host'].':'.$urls['port']);
	$basepath = $basehost;
	$paths = explode('/', preg_replace("/^".$scheme.":\/\//i", "", $refurl));
	$n = count($paths);
	for($i=1;$i < ($n-1);$i++) {
		if(!preg_match("/[\?]/", $paths[$i])) $basepath .= '/'.$paths[$i];
	}
	if(!preg_match("/[\?\.]/", $paths[$n-1])) {
		$basepath .= '/'.$paths[$n-1];
	}
	if($surl=='') {
		return $basepath;
	}
	$pos = strpos($surl, "#");
	if($pos>0) {
		$surl = substr($surl, 0, $pos);
	}

	//用 '/' 表示网站根的网址
	if($surl[0]=='/') {
		$okurl = $basehost.$surl;
	} else if($surl[0]=='.') {
		if(strlen($surl)<=2) {
			return '';
		} elseif($surl[1]=='/') {
			$okurl = $basepath . preg_replace('/^./', '', $surl);
		} elseif(substr ( $surl, 0, 3 ) == '../') {
			$surl = substr ( $surl, strlen ( $surl ) - (strlen ( $surl ) - 3), strlen ( $surl ) - 3 ); 
			for($i=1; $i < ($n-2); $i++) {
				if(!preg_match("/[\?]/", $paths[$i])) $path1 .= '/'.$paths[$i];
			}
			$okurl = $basehost . $path1 . '/' . $surl;
		} else {
			$okurl = $basepath . '/' . $surl;
		}
	} else {
		if( strlen($surl) < 7 ) {
			$okurl = $basepath.'/'.$surl;
		} elseif( preg_match("/^".$scheme.":\/\//i",$surl) ) {
			$okurl = $surl;
		} else {
			$okurl = $basepath.'/'.$surl;
		}
	}
	$okurl = preg_replace("/^".$scheme.":\/\//i", '', $okurl);
	$okurl = $scheme.'://'.preg_replace("/\/{1,}/", '/', $okurl);
	return $okurl;
}

/* php7+兼容 */
if (!function_exists('mysql_escape_string')) {
	function mysql_escape_string($data){
		return $data;
	}
}
?>