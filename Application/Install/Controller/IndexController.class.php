<?php //https://www.woytu.com/?>
<?php namespace Install\Controller;
use Think\Controller;
class IndexController extends Controller
{
	public function _initialize()
	{
		if (file_exists(CONF_PATH.'install.lock')) {
			$this->error('无法重复安装，请删除/Application/Common/Conf/install.lock才能再次安装！');
		}
	}

	public function index()
	{
		$this->assign('title', '欢迎使用 - YGBOOK安装程序');
		$this->display();
	}

	public function check()
	{
		/* if (!verify_license()) {
			die('&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;');
		} */
		$xzv_2 = '服务器环境检测 - YGBOOK安装程序';
		$xzv_9['os'] = PHP_OS;
		$xzv_9['web_server'] = $_SERVER['SERVER_SOFTWARE'];
		$xzv_9['php_ver'] = PHP_VERSION;
		$xzv_9['mysql_ver'] = extension_loaded('mysql') ? 'mysql' : (extension_loaded('mysqli') ? 'mysqli' : '否');
		$xzv_9['zlib'] = function_exists('gzclose') ? '是' : '否';
		$xzv_9['timezone'] = function_exists('date_default_timezone_get') ? date_default_timezone_get() : '无需设置';
		$xzv_9['socket'] = function_exists('fsockopen') ? '是' : '否';
		$xzv_9['gd'] = extension_loaded('gd') ? '是' : '否';
		$xzv_14 = array(
			RUNTIME_PATH,
			TEMP_PATH,
			TEMP_PATH.'cate/',
			TEMP_PATH.'chaptercache/',
			TEMP_PATH.'dataarea/',
			TEMP_PATH.'index/',
			TEMP_PATH.'tags/',
			TEMP_PATH.'wap/',
			TEMP_PATH.'wap/cate/',
			TEMP_PATH.'wap/index/',
			TEMP_PATH.'wap/tags/',
			DATA_PATH,
			DATA_PATH.'dataarea/',
			DATA_PATH.'view/book/',
			DATA_PATH.'view/chapter/',
			DATA_PATH.'view/chaptercont/',
			DATA_PATH.'view/newchapter/',
			CACHE_PATH,
			LOG_PATH,
			CONF_PATH
		);
		foreach ($xzv_14 as $xzv_22) {
			$xzv_6 = $xzv_22;
			if (!file_exists($xzv_6)) {
				mkdir($xzv_6, 0777);
			}
			$xzv_19 = new \Org\Util\Install;
			$xzv_10 = $xzv_19->check_writeable($xzv_6);
			if ($xzv_10 == '1') {
				$xzv_18 = "<b class='write'>可写</b>";
			}
			elseif ($xzv_10 == '0'){
				$xzv_18 = "<b class='noWrite'>不可写</b>";
				$xzv_17 = true;
			}
			elseif ($xzv_10 == '2'){
				$xzv_18 = "<b class='noWrite'>不存在</b>";
				$xzv_17 = true;
			}
			$xzv_26[] = array(
				'dir' => $xzv_22,
				'if_write' => $xzv_18
			);
		}
		$this->assign('title', $xzv_2);
		$this->assign('sys_info', $xzv_9);
		$this->assign('writeable', $xzv_26);
		$this->assign('no_write', $xzv_17);
		$this->display();
	}

	public function setting()
	{
		$xzv_21 = '基本设置 - YGBOOK安装程序';
		$this->assign('title', $xzv_21);
		$this->display();
	}

	public function install()
	{
		/* if (!verify_license()) {
			die('&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;');
		} */
		$xzv_25 = I('post.dbhost');
		$xzv_23 = I('post.dbport');
		$xzv_3 = I('post.dbuser');
		$xzv_11 = I('post.dbpass');
		$xzv_16 = I('post.dbname');
		$xzv_20 = I('post.prefix');
		$xzv_7 = I('post.username');
		$xzv_5 = I('post.password');
		$xzv_13 = I('post.password_confirm');
		if (!$xzv_25 || !$xzv_3 || !$xzv_11 || !$xzv_16 || !$xzv_20 || !$xzv_7
			|| !$xzv_5) {
			$this->error('配置信息填写不完整，请重新填写！', U('index/setting'));
		}
		if ($xzv_5 != $xzv_13) {
			$this->error('两次后台密码输入不一致，请重新输入！', U('index/setting'));
		}
		$xzv_4 = mysqli_connect($xzv_25, $xzv_3, $xzv_11, $xzv_16, $xzv_23);
		if (!$xzv_4) {
			$this->error('数据库连接失败! 请检查连接参数。', U('index/setting'));
		}
		$xzv_0 = file_get_contents(APP_PATH.'Install/Conf/database.tpl');
		$xzv_8 = str_replace(array(
			'{dbhost}',
			'{dbname}',
			'{dbuser}',
			'{dbpwd}',
			'{dbport}',
			'{dbpre}'
		), array(
			$xzv_25,
			$xzv_16,
			$xzv_3,
			$xzv_11,
			$xzv_23,
			$xzv_20
		), $xzv_0);
		file_put_contents(CONF_PATH.'database.php', $xzv_8);
		mysqli_query($xzv_4, "CREATE DATABASE IF NOT EXISTS `$xzv_16` default charset utf8 COLLATE utf8_general_ci");
		mysqli_select_db($xzv_4, $xzv_16);
		$xzv_15 = file_get_contents(APP_PATH.'Install/Conf/ygbook_install.tpl');
		$xzv_15 = preg_replace('/yg_/Ums', "$xzv_20", $xzv_15);
		$xzv_24 = new \Org\Util\Install;
		$xzv_24->sql_execute($xzv_15, $xzv_4);
		$xzv_1 = substr(md5($xzv_5), 4, 18);
		$xzv_12 = "UPDATE {$xzv_20}

	settingmeta SET meta_value = '$xzv_7' WHERE meta_key = 'adminname';"
		. '
'."UPDATE {$xzv_20}

settingmeta SET meta_value = '$xzv_1' WHERE meta_key = 'adminpwd';";
$xzv_24->sql_execute($xzv_12, $xzv_4);
file_put_contents(CONF_PATH.'install.lock', NOW_TIME);
$this->success('安装成功，1秒后转向后台登录页面！', U('/admin'));
}
}
