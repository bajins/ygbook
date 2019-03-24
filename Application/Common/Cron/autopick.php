<?php
if(strtoupper(MODULE_NAME) == 'HOME'){
	if(is_spider() != 'Baidu'){
		if(mt_rand(1,5) == 1){
			pickrun('list', NULL, 'index');
		} else {
			pickrun('list', NULL, NULL);
		}
		cronlog('自动采集任务');
	}
}