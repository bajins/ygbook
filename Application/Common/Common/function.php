<?php

require_once COMMON_PATH . "Common/extend.php";
function strexists($string, $find)
{
	return !(strpos($string, $find) === false);
}
function htmlspecialchars_trans($string, $transway = 'encode')
{
	if (!verify_license()) {
		exit("&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;");
	}
	if ($transway == "encode") {
		$newstring = str_replace(array("\r\n", "\r", "\n", "&nbsp;"), array("[line]", "[line]", "[line]", "[space]"), $string);
	} else {
		if ($transway == "decode") {
			$newstring = str_replace(array("[line]", "[space]"), array("\r\n", "&amp;nbsp;"), htmlspecialchars($string));
		} else {
			if ($transway == "pick") {
				$newstring = str_replace(array("&amp;", "[space]"), array("&", "&nbsp;"), $string);
			}
		}
	}
	return $newstring;
}
function getRandChar($length)
{
	$str = NULL;
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max = strlen($strPol) - 1;
	$i = 0;
	while ($i < $length) {
		$str .= $strPol[rand(0, $max)];
		$i = $i + 1;
	}
	return $str;
}
function encodekey($id)
{
	return substr(md5("yg_" . $id), 5, 16);
}
function unique_array($array, $total, $unique = true)
{
	$newArray = array();
	if($unique){
		$array = array_unique($array);
	}
	shuffle($array);
	$length = count($array);
	$i = 0;
	while ($i < $total) {
		if ($i < $length) {
			$newArray[] = $array[$i];
		}
		$i = $i + 1;
	}
	return $newArray;
}
function is_HTTPS()
{
	if (!isset($_SERVER['HTTPS'])) {
		return false;
	}
	if ($_SERVER["HTTPS"] === 1) {
		return true;
	}
	if ($_SERVER["HTTPS"] === "on") {
		return true;
	}
	if ($_SERVER["SERVER_PORT"] == 443) {
		return true;
	}
	return false;
}
function g2u($str)
{
	$charset = mb_detect_encoding($str, array("UTF-8", "GBK", "GB2312"));
	$charset = strtolower($charset);
	if ("cp936" == $charset) {
		$charset = "GBK";
	}
	if ("utf-8" != $charset) {
		$str = iconv($charset, "UTF-8//IGNORE", $str);
	}
	return $str;
}
function toutf8($str)
{
	$encode = mb_detect_encoding($str, array("ASCII", "UTF-8", "GB2312", "EUC-CN", "GBK", "BIG5"));
	if (in_array($encode, array("GB2312", "EUC-CN", "GBK"))) {
		$str = iconv($encode, "UTF-8", $str);
	}
	return $str;
}
function str_insert($str, $i, $substr)
{
	$j = 0;
	while ($j < $i) {
		$startstr .= $str[$j];
		$j = $j + 1;
	}
	$j = $i;
	while ($j < strlen($str)) {
		$laststr .= $str[$j];
		$j = $j + 1;
	}
	$str = $startstr . $substr . $laststr;
	return $str;
}
function getip()
{
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} else {
		if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} else {
			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
				$ip = getenv("REMOTE_ADDR");
			} else {
				if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
					$ip = $_SERVER["REMOTE_ADDR"];
				} else {
					$ip = "unknown";
				}
			}
		}
	}
	return $ip;
}
function cleanHtml($str)
{
	$str = trim($str);
	$str = preg_replace("/<(style.*?)>(.*?)<(\\/style.*?)>/si", "", $str);
	$str = preg_replace("/<(\\/?style.*?)>/si", "", $str);
	$str = preg_replace("/<(script.*?)>(.*?)<(\\/script.*?)>/si", "", $str);
	$str = preg_replace("/<(\\/?script.*?)>/si", "", $str);
	$str = strip_tags($str, "");
	$str = str_replace("\t", "", $str);
	$str = str_replace("\r\n", "", $str);
	$str = str_replace("\r", "", $str);
	$str = str_replace("\n", "", $str);
	$str = str_replace(" ", "", $str);
	$str = str_replace("　", "", $str);
	return trim($str);
}
function writefile($file, $str)
{
	if (!is_dir(dirname($file))) {
		mkdir(dirname($file), 511, true);
	}
	file_put_contents($file, $str);
}
function mkdirs($dir, $dread = 0777)
{
	if (is_dir($dir)) {
		return true;
	}
	mkdir($dir, $dread, true);
}
function write($file, $content, $method = 'w')
{
	mkdirs(dir($file));
	if (is_file($file) && !is_writable($file)) {
		return false;
	}
	if ($method == "w") {
		return file_put_contents($file, $content);
	}
	$mfile = fopen($file, $method);
	flock($mfile, LOCK_EX);
	$status = fwrite($mfile, $content);
	fclose($mfile);
	return $status;
}
function arr2file($file, $array)
{
	if (is_array($array)) {
		$array = var_export($array, true);
	} else {
		$array = $array;
	}
	write($file, "<?php\r\n" . "return " . $array . ";" . "\r\n?>");
}
function is_mobile()
{
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	$mobile_browser = array("mqqbrowser", "opera mobi", "juc", "iuc", "fennec", "ios", "applewebKit/420", "applewebkit/525", "applewebkit/532", "ipad", "iphone", "ipaq", "ipod", "iemobile", "windows ce", "240x320", "480x640", "acer", "android", "anywhereyougo.com", "asus", "audio", "blackberry", "blazer", "coolpad", "dopod", "etouch", "hitachi", "htc", "huawei", "jbrowser", "lenovo", "lg", "lg-", "lge-", "lge", "mobi", "moto", "nokia", "phone", "samsung", "sony", "symbian", "tablet", "tianyu", "wap", "xda", "xde", "zte");
	$is_mobile = false;
	foreach ($mobile_browser as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}
function is_spider()
{
	$agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	if (stripos($agent, "googlebot") > 0 - 1 || stripos($agent, "mediapartners-google") > 0 - 1) {
		$bot = "Google";
	}
	if (stripos($agent, "baiduspider") > 0 - 1) {
		$bot = "Baidu";
	}
	if (stripos($agent, "360spider") > 0 - 1) {
		$bot = "360搜索";
	}
	if (stripos($agent, "sogou") > 0 - 1) {
		$bot = "Sogou";
	}
	if (stripos($agent, "Yisouspider") > 0 - 1) {
		$bot = "神马";
	}
	if (stripos($agent, "bingbot") > 0 - 1) {
		$bot = "Bing";
	}
	if (stripos($agent, "yahoo") > 0 - 1) {
		$bot = "Yahoo!";
	}
	return $bot ? $bot : false;
}
function content_filter($content, $domain)
{
	preg_match_all("/<a(.*?)href=\"(.*?)\"(.*?)>(.*?)<\\/a>/i", $content, $matches);
	$domain = explode(",", $domain);
	if ($matches) {
		foreach ($matches[2] as $key => $val) {
			if ($val == "/cdn-cgi/l/email-protection") {
				$content = str_replace($val, "#", $content);
			} else {
				if (strpos($val, "http") != 0) {
					$content = str_replace($matches[0][$key], $matches[4][$key], $content);
				} else {
					if (is_array($domain)) {
						$thisdomain = getdomain($val);
						if (!in_array($thisdomain, $domain)) {
							$content = str_replace($matches[0][$key], $matches[4][$key], $content);
						} else {
							$content = str_replace($matches[0][$key], "<a href=\"" . $val . "\" rel=\"external nofollow\">" . $matches[4][$key] . "</a>", $content);
						}
					} else {
						if (!strpos($val, $domain)) {
							$content = str_replace($matches[0][$key], $matches[4][$key], $content);
						} else {
							$content = str_replace($matches[0][$key], "<a href=\"" . $val . "\" rel=\"external nofollow\">" . $matches[4][$key] . "</a>", $content);
						}
					}
				}
			}
		}
	}
	return $content;
}
function getdomain($url)
{
	$urld = parse_url($url);
	$host = explode(".", $urld["host"]);
	return $host[1] . "." . $host[2];
}
function checkadmin()
{
	$setting = F("setting");
	if (session("adminname")) {
		return true;
	}
	return false;
}
function pagelist($mode, $now = 1, $perpage = 2, $totalpage = 100, $dir = NULL, $extra = 'prev,next')
{
	$setting = F("setting");
	if ($mode == "index") {
		$pagerule_index = C("HOME_URL");
		$pagerule = C("HOME_URL") . $setting["seo"]["indexpageurl"];
	} else {
		if ($mode == "list") {
			$domain = C("DATADOMAIN");
			$category = F("category");
			!$dir && ($dir = "default");
			if ($dir == "default") {
				$dir = $category["default"]["dir"];
			}
			if ($dir == "all") {
				$pagerule_index = preg_replace("/\\{ellipsis\\}(.*?)\\{\\/ellipsis\\}/", "", $setting["seo"]["allurl"]);
				$pagerule_index = C("HOME_URL") . $pagerule_index;
				$pagerule = str_replace(array("{ellipsis}", "{/ellipsis}"), "", $setting["seo"]["allurl"]);
			} else {
				$pagerule_index = preg_replace("/\\{ellipsis\\}(.*?)\\{\\/ellipsis\\}/", "", $setting["seo"]["listurl"]);
				$pagerule_index = C("HOME_URL") . str_replace("{dir}", $dir, $pagerule_index);
				$pagerule = str_replace(array("{ellipsis}", "{/ellipsis}", "{dir}"), array("", "", $dir), $setting["seo"]["listurl"]);
			}
			$pagerule = C("HOME_URL") . $pagerule;
		} else {
			if ($mode == "admin") {
				$pagerule_index = $pagerule = U("/admin/index/article/page/{page}");
			} else {
				if ($mode == "admin_spider") {
					if (I("get.domain")) {
						$pagerule_index = $pagerule = U("/admin/index/spider/domain/" . I("get.domain") . "/page/{page}");
					} else {
						$pagerule_index = $pagerule = U("/admin/index/spider/page/{page}");
					}
				} else {
					if ($mode == "admin_searchlog") {
						$pagerule_index = $pagerule = U("/amin/index/searchlog/page/{page}");
					} else {
						if ($mode == "admin_tag") {
							$pagerule_index = $pagerule = U("/amin/index/tags/page/{page}");
						}
					}
				}
			}
		}
	}
	if (strexists($mode, "admin")) {
		$pageclass = " class=\"am-pagination\"";
		$currentclass = "am-active";
	} else {
		$pageclass = "";
		$currentclass = "current";
	}
	$pagehtml = "<ul" . $pageclass . ">";
	if (strpos($extra, "prev") > 0 - 1 && $now > 1) {
		$pageurl = str_replace("{page}", $now - 1, $pagerule);
		$pagehtml .= "<li><a href=\"" . $pageurl . "\" target=\"_self\">&laquo; 上一页</a>";
	}
	if ($perpage > 0) {
		$k = !($now > $perpage) ? 1 : $now - $perpage;
		$i = 0;
		while ($i < $perpage * 2 + 1) {
			if ($i < $totalpage) {
				$pageurl = str_replace("{page}", $k, $k == 1 ? $pagerule_index : $pagerule);
				$pagehtml .= "<li" . ($k == $now ? " class=\"" . $currentclass . "\"" : "") . "><a href=\"" . $pageurl . "\" target=\"_self\">" . $k . "</a></li>";
				$k = $k + 1;
				$i = $i + 1;
			}
		}
	}
	if (strpos($extra, "next") > 0 - 1 && $now < $totalpage) {
		$pageurl = str_replace("{page}", $now + 1 > $totalpage ? $totalpage : $now + 1, $pagerule);
		$pagehtml .= "<li><a href=\"" . $pageurl . "\" target=\"_self\">下一页 &raquo;</a>";
	}
	$pagehtml .= "</ul>";
	return $pagehtml;
}
function clearfile($path)
{
	if (!is_dir($path)) {
		return NULL;
	}
	$op = dir($path);
	while (false != ($item = $op->read())) {
		if ($item == "." || $item == "..") {
			return false;
		}
		if (is_dir($op->path . "/" . $item)) {
			clearfile($op->path . "/" . $item);
			rmdir($op->path . "/" . $item);
		} else {
			unlink($op->path . "/" . $item);
		}
	}
}
function get_extension($file)
{
	return pathinfo($file, PATHINFO_EXTENSION);
}
function verify_license()
{
	return true;
	if (!defined("LICENSE_CODE") || !defined("BASE_HOST") || !defined("AUTH_KEY") || AUTH_KEY != md5(BASE_HOST . LICENSE_CODE)) {
		return false;
	}
	$host = base64_decode(str_rot13(str_replace(array("4ix" . "5Zu9", "Yqg" . "6Y" . "Cf5"), "", base64_decode(LICENSE_CODE))));
	if ("8ab0d81382a0f2cd5daf43d03e21403d19896bce17389a331a17b9d3c97bde9b" != hash("sha256", $host)) {
		return false;
	}
	$nowhost = str_replace(".0rg.pw", "sZ4qNHF5RQb7Vecy", BASE_HOST);
	if (!S("verifytime_" . $nowhost)) {
		S("verifycode_" . $nowhost, NULL);
		$verify = json_decode(base64_decode(file_get_contents("http://vip.0rg.pw/verify/" . md5(BASE_HOST))), true);
		if ($verify["license"]) {
			$encode = file_get_contents(CONF_PATH . str_replace(".0rg.pw", "", BASE_HOST) . "-" . $verify["license"] . ".png");
			$encode = explode("AUTH>", $encode);
			$encode = $encode[1];
			$randchar = substr($encode, 0, 6);
			$encode = str_replace(array($randchar, str_rot13($randchar)), "", $encode);
			$code = json_decode(base64_decode($encode), true);
			if ($code["host"] == BASE_HOST) {
				S("verifytime_" . $nowhost, NOW_TIME, 86400);
				S("verifycode_" . $nowhost, $code["domain"], 86400);
				return true;
			}
			return false;
		}
		return false;
	}
	if (S("verifytime_" . $nowhost) + 86400 > NOW_TIME) {
		if (S("verifycode_" . $nowhost) != md5(BASE_HOST . md5(BASE_HOST))) {
			S("verifycode_" . $nowhost, NULL);
			S("verifytime_" . $nowhost, NULL);
			return false;
		}
		return true;
	}
	S("verifytime_" . $nowhost, NULL);
	S("verifycode_" . $nowhost, NULL);
	return true;
}
function home_check()
{
	return true;
	if (!defined("LICENSE_CODE") || !defined("BASE_HOST")) {
		return false;
	}
	$host = base64_decode(str_rot13(str_replace(array("4ix" . "5Zu9", "Yqg" . "6Y" . "Cf5"), "", base64_decode(LICENSE_CODE))));
	if ("8ab0d81382a0f2cd5daf43d03e21403d19896bce17389a331a17b9d3c97bde9b" != hash("sha256", $host)) {
		return false;
	}
	$nowhost = str_replace(".0rg.pw", "sZ4qNHF5RQb7Vecy", BASE_HOST);
	if (!S("hChecktime_" . $nowhost)) {
		$encode = file_get_contents(CONF_PATH . str_replace(".0rg.pw", "", BASE_HOST) . "-" . md5($nowhost) . ".png");
		$encode = explode("AUTH>", $encode);
		$encode = $encode[1];
		$randchar = substr($encode, 0, 6);
		$encode = str_replace(array($randchar, str_rot13($randchar)), "", $encode);
		$code = json_decode(base64_decode($encode), true);
		if ($code["host"] == BASE_HOST) {
			S("hChecktime_" . $nowhost, NOW_TIME, 86400);
			S("hCheckCode_" . $nowhost, $code["domain"], 86400);
			return true;
		}
		return false;
	}
	if (S("hChecktime_" . $nowhost) + 86400 > NOW_TIME) {
		if (S("hCheckCode_" . $nowhost) != md5(BASE_HOST . md5(BASE_HOST))) {
			S("hCheckCode_" . $nowhost, NULL);
			S("hChecktime_" . $nowhost, NULL);
			return false;
		}
		return true;
	}
	S("hChecktime_" . $nowhost, NULL);
	S("hCheckCode_" . $nowhost, NULL);
	return true;
}
function mobile_adaptation()
{
	$setting = F("setting");
	if ($setting["seo"]["disjump"] > 0) {
		return NULL;
	}
	$uri = substr($_SERVER["REQUEST_URI"], 1);
	if (is_mobile()) {
		$nowhost = $_SERVER["HTTP_HOST"];
		$setting = F("setting");
		if ($nowhost == $setting["seo"]["pcdomain"]) {
			$wapdomain = $setting["seo"]["mobiledomain"];
		}
		if ($wapdomain) {
			$domian_prefix = is_HTTPS() ? "https://" : "http://";
			$newuri = $domian_prefix . $wapdomain . C("HOME_URL") . $uri;
			header("Location:" . $newuri);
		}
	}
}
function http_301($url)
{
	header("HTTP/1.1 301 Moved Permanently");
	Header("Location:" . $url);
	exit(0);
}
function utf8_for_xml($string)
{
	$string = str_replace("&nbsp;", " ", $string);
	return preg_replace("/[^\\x{0009}\\x{000a}\\x{000d}\\x{0020}-\\x{D7FF}\\x{E000}-\\x{FFFD}]+/u", " ", $string);
}
function delhtml($id)
{
	$setting = F("setting");
	$sites = F("sites");
	$domaindir = array();
	$domaindir[] = array("domain" => $setting["seo"]["pcdomain"], "idrule" => $setting["seo"]["idrule"]);
	$domaindir[] = array("domain" => $setting["seo"]["mobiledomain"], "idrule" => $setting["seo"]["idrule"]);
	if (count($sites) > 0) {
		foreach ($sites as $value) {
			$domaindir[] = array("domain" => $value["pcdomain"], "idrule" => $value["idrule"]);
			$domaindir[] = array("domain" => $value["mobiledomain"], "idrule" => $value["idrule"]);
		}
	}
	foreach ($domaindir as $value) {
		if ($id == "index") {
			unlink(APP_PATH . "Html/" . $value["domain"] . "/home.html");
		} else {
			if ($id == "cate") {
				clearfile(APP_PATH . "Html/" . $value["domain"] . "/cate/");
			} else {
				$newid = $id + intval($value["idrule"]);
				$newsubid = floor($newid / 1000);
				unlink(APP_PATH . "Html/" . $value["domain"] . "/" . $newsubid . "/" . $newid . ".html");
			}
		}
	}
}
function delchapter($id, $cid)
{
	$setting = F("setting");
	$sites = F("sites");
	$domaindir = array();
	$domaindir[] = array("domain" => $setting["seo"]["pcdomain"], "idrule" => $setting["seo"]["idrule"]);
	$domaindir[] = array("domain" => $setting["seo"]["mobiledomain"], "idrule" => $setting["seo"]["idrule"]);
	if (count($sites) > 0) {
		foreach ($sites as $value) {
			$domaindir[] = array("domain" => $value["pcdomain"], "idrule" => $value["idrule"]);
			$domaindir[] = array("domain" => $value["mobiledomain"], "idrule" => $value["idrule"]);
		}
	}
	foreach ($domaindir as $value) {
		$newid = $id + intval($value["idrule"]);
		$newsubid = floor($newid / 1000);
		unlink(APP_PATH . "Html/" . $value["domain"] . "/chapter/" . $newsubid . "/" . $newid . "/" . $cid . ".html");
	}
}
function getsel($val, $check, $type = 'selected')
{
	if (!in_array($type, array("selected", "checked", "on"))) {
		return false;
	}
	if (!strexists($check, ",")) {
		return $val == $check ? $type : "";
	}
	return in_array($val, explode(",", $check)) ? $type : "";
}
function basestat()
{
	if (!verify_license()) {
		exit("&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;");
	}
	$article = M("articles");
	$Smeta = M("settingmeta");
	$counts["article"] = $article->Count();
	$today = strtotime(date("Y-m-d"));
	$counts["article_day"] = $article->where("posttime > '" . $today . "'")->Count();
	$counts["articleupdate_day"] = $article->where("updatetime > '" . $today . "'")->Count();
	$counts["spider_day"] = intval($Smeta->where("meta_key='spider_day'")->getField("meta_value"));
	$counts["spider_lastday"] = intval($Smeta->where("meta_key='spider_lastday'")->getField("meta_value"));
	return $counts;
}