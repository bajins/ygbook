<?php //decode by 小猪php解密 QQ:2338208446 http://www.xzjiemi.com/?>
<?php namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller
{
	public $setting;
	public $category;
	public $dataarea;
	public $defaultdir;
	public function __construct()
	{
		parent::__construct();
		$xzv_2 = strtolower(ACTION_NAME);
		if (!in_array($xzv_2, array(
			'login',
			'logout'
		))) {
			if (!checkadmin()) {
				$xzv_1 = str_replace(array(
					'extend',
					'Extend'
				), 'index', U('login'));
				header('Location: '.$xzv_1);
				die;
			}
			if (in_array(strtolower(ACTION_NAME), array(
				'article',
				'dataarea',
				'index',
				'searchlog',
				'advertise',
				'spider'
			))) {
				/* if (!verify_license()) {
					die('&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;');
				} */
			}
		}
		$this->setting = F('setting');
		$this->category = F('category');
		$this->dataarea = F('dataarea');
		$this->defaultdir = $this->category['default']['dir'];
		$xzv_3[$xzv_2] = ' class="am-active"';
		if (in_array(strtolower($xzv_2), array(
			'category',
			'dataarea',
			'searchlog',
			'tags',
			'advertise',
			'seowords',
			'advertise',
			'pickers'
		))) {
			$xzv_0 = 'am-in';
			$this->assign('amin', $xzv_0);
		}
		$this->assign('active', $xzv_3);
	}
}

