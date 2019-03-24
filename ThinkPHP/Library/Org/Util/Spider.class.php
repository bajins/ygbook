<?php
namespace Org\Util;
class Spider {
	public $setting;

	public function checkspider(){
		if(!S('spider_daytask')){
			$Slogs = M('spiderlogs');
			$deltime = NOW_TIME - 7*24*3600;
			$Slogs->where("dateline<'$deltime'")->delete();
			S('spider_daytask', NOW_TIME, 24*3600);
		}
		$bot = is_spider();
		if($bot && in_array(ACTION_NAME, array('index', 'showlist', 'view', 'updatecache'))){
			$this->addlogs($bot);
		}
	}
	public function addlogs($bot){
		$Smeta = M('settingmeta');
		if(!S('spider_addlogs')){
			$Slogs = M('spiderlogs');
 		    $ip = get_client_ip();
 	    	if(!$_SERVER["HTTP_X_REWRITE_URL"]){
 	    		$_SERVER["HTTP_X_REWRITE_URL"] = $_SERVER["REQUEST_URI"];
     		}
     		$localurl = (is_HTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . @$_SERVER["HTTP_X_REWRITE_URL"];
     		$data = array(
 	    		'domain' => $_SERVER['HTTP_HOST'],
 	    		'httpurl' => $localurl,
 	    		'spider' => $bot,
 	    		'ip' => $ip,
 	    		'dateline' => NOW_TIME
 	    	);
 	    	$Slogs->add($data);
     		S('spider_addlogs', NOW_TIME, 120);//2分钟
		}
		$spider_uptime = intval($Smeta->where("meta_key='spider_uptime'")->getField('meta_value'));
		
		$tdtime = strtotime(date('Y-m-d'));
		if($spider_uptime > $tdtime){
			$Smeta->where("meta_key='spider_day'")->setInc('meta_value', 1);
			$Smeta->where("meta_key='spider_uptime'")->setField('meta_value', NOW_TIME);
		} else {
			$spider_day = intval($Smeta->where("meta_key='spider_day'")->getField('meta_value'));
			$Smeta->where("meta_key='spider_lastday'")->setField('meta_value', $spider_day);
			$Smeta->where("meta_key='spider_uptime'")->setField('meta_value', NOW_TIME);
			$Smeta->where("meta_key='spider_day'")->setField('meta_value', 1);
		}
	}
}