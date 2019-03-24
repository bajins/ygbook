<?php
namespace Org\Util;
require './vendor/qiniu/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Think\Model;
class Qiniu {
	public $accessKey;
	public $secretKey;
	public $bucket;
	public function __construct(){
		$setting = F('setting');
		$this->accessKey = $setting['extra']['qiniu_accesskey'];
		$this->secretKey = $setting['extra']['qiniu_secretkey'];
		$this->bucket = $setting['extra']['qiniu_bucket'];
	}

	public function to_qiniu($url){
		if(!$url){
			return null;
		}
		$auth = new Auth($this->accessKey, $this->secretKey);
		$bucketMgr = new BucketManager($auth);
		$ext = get_extension($url);
		if(!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))){
			$ext = 'jpg';
		}
		$item = $bucketMgr->fetch($url, $this->bucket, date('Ymd').'/'.NOW_TIME.mt_rand(1000, 9999).'.'.$ext);
		return $item[0]['key'] ? $item[0]['key'] : null;
	}
}