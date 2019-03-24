<?php
namespace Org\Util;
class Tag {
	public function relatekw($title, $body, $aid){
		$title = strip_tags($title);
		$body = strip_tags(preg_replace("/\[.+?\]/U", '', $body));
		if(!$title || !$body){
			return '';
		}
		$data = $this->curl_post('http://bosonnlp.com/analysis/key', $body);
		if($data){
			$kws = json_decode($data, true);
			if($kws){
				$return = '';
				foreach($kws as $kw) {
					if(!$kw){
						continue;
					}
					$kw = htmlspecialchars($kw[1]);
					$return .= $kw.',';
					if(substr_count($return, ',') >= 8){
						break;
					}
				}
				$return = substr(htmlspecialchars($return), 0, strlen($return)-1);
			}
		}
		$tagstr = $return ? $this->add_tag($return, $aid) : '';
		return $tagstr;
	}

	private function add_tag($tags, $aid){
		if($tags == '') {
			return;
		}
		$tags = str_replace(array(chr(0xa3).chr(0xac), chr(0xa1).chr(0x41), chr(0xef).chr(0xbc).chr(0x8c)), ',', $tags);
		if(strexists($tags, ',')) {
			$tagarray = array_unique(explode(',', $tags));
		} else {
			$tags = str_replace('　', ' ', $tags);
			$tagarray = array_unique(explode(' ', $tags));
		}
		$tagcount = 0;
		$tags = M('tags');
		$tagdatas = M('tagdatas');
		$PinYin = new \Org\Util\PinYin;
		foreach($tagarray as $tagname) {
			$tagname = trim($tagname);
			if(preg_match('/^([\x7f-\xff_-]|\w|\s){3,20}$/', $tagname)) {
				$result = $tags->where("tagname='$tagname'")->find();
				if($result['id']) {
					$tagid = $result['id'];
					$tagcount = $result['num'];
					$ename = $result['ename'];
				} else {
					$ename = join('', $PinYin->get_all_py($tagname));
					$result_e = $tags->where("ename='$ename'")->find();
					if($result_e['id']){
						$ename .= '_'.getRandChar(4);
					}
					$data = array(
						'tagname' => $tagname,
						'ename' => $ename,
					);
					$tagid = $tags->add($data);
					$tagcount = 0;
				}
				if($tagid) {
					if($aid) {
						$data = array(
							'tid' => $tagid,
							'aid' => $aid
						);
						$tagdatas->add($data);
					}
					$tdata['num'] = $tagcount = $tagcount + 1;
					$tags->where("id='$tagid'")->save($tdata);
					$return .= $tagid.','.$tagname.','.$ename."\t";
				}
				if($tagcount > 10) {
					unset($tagarray);
					break;
				}
			}
		}
		if($return){
			$article = M('articles');
			$data['tags'] = $return;
			$article->where("id='$aid'")->save($data);
		}
		return $return;
	}

	private function curl_post($url, $data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.$data);
		$r = curl_exec($ch);
		curl_close($ch);
		return $r;
	}

}