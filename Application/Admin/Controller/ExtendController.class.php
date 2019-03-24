<?php //decode by 小猪php解密 QQ:2338208446 http://www.xzjiemi.com/?>
<?php namespace Admin\Controller;
use Admin\Controller\BaseController;
use Think\Controller;
class ExtendController extends AdminController
{
	public function category()
	{
		$xzv_10 = $this->setting;
		$xzv_40 = $this->category;
		$xzv_21 = F('pick');
		$xzv_90 = I('param.');
		$xzv_132 = $xzv_90['action'];
		if ($xzv_132 == 'save') {
			$xzv_40['default']['dir'] = $xzv_90['ddir'];
			$xzv_40['default']['name'] = $xzv_90['dname'];
			$xzv_40['default']['order'] = $xzv_90['dorder'];
			foreach ($xzv_90['copen'] as $xzv_95 => $xzv_136) {
				$xzv_110 = $xzv_90['cdir'][$xzv_95];
				$xzv_131 = $xzv_90['cname'][$xzv_95];
				$xzv_103 = intval($xzv_90['corder'][$xzv_95]);
				if (!$xzv_136 || !$xzv_110 || !$xzv_131 || $xzv_136 == 'deleted') {
					unset($xzv_40[$xzv_110]);
				}
				else {
					$xzv_40[$xzv_110]['open'] = $xzv_136;
					$xzv_40[$xzv_110]['name'] = $xzv_131;
					$xzv_40[$xzv_110]['dir'] = $xzv_110;
					$xzv_40[$xzv_110]['order'] = $xzv_103;
				}
			}
			$xzv_126 = 0;
			foreach ($xzv_40 as $xzv_24 => $xzv_92) {
				if (!$xzv_92) {
					unset($xzv_40[$xzv_24]);
				}
				$xzv_126++;
			}
			if (is_array($xzv_90['ndir'])) {
				foreach ($xzv_90['ndir'] as $xzv_95 => $xzv_136) {
					if ($xzv_136 && $xzv_40[$xzv_136]['name'] != '') {
						$this->error('已存在相同dir的栏目！');
					}
					if (!$xzv_136 || !$xzv_90['nname'][$xzv_95]) {
						unset($xzv_40[$xzv_136]);
					}
					else {
						$xzv_40[$xzv_136] = array(
							'open' => $xzv_90['nopen'][$xzv_95],
							'name' => $xzv_90['nname'][$xzv_95],
							'dir' => $xzv_136,
							'order' => intval($xzv_90['norder'][$xzv_95])
						);
					}
				}
			}
			$xzv_31 = array(
				'direction' => 'SORT_DESC',
				'field' => 'order',
			);
			$xzv_147 = array();
			foreach ($xzv_40 AS $xzv_23 => $xzv_91) {
				foreach ($xzv_91 AS $xzv_24 => $xzv_92) {
					$xzv_147[$xzv_24][$xzv_23] = $xzv_92;
				}
			}
			if ($xzv_31['direction']) {
				array_multisort($xzv_147[$xzv_31['field']], constant($xzv_31['direction']),
					$xzv_40);
			}
			F('category', $xzv_40);
			$this->success('栏目更新成功！');
		}
		elseif ($xzv_132 == 'edit'){
			$xzv_110 = $xzv_90['dir'];
			if (I('post.do') == 'save') {
				$xzv_150 = $xzv_90['dir'] == 'default' ? 'default' : $xzv_90['newdir'];
				$xzv_40[$xzv_150] = array(
					'open' => $xzv_90['open'],
					'name' => $xzv_40[$xzv_110]['name'],
					'dir' => $xzv_90['newdir'],
					'order' => $xzv_40[$xzv_110]['order'],
					'listtitle' => $xzv_90['listtitle'],
					'listkw' => $xzv_90['listkw'],
					'listdes' => $xzv_90['listdes'],
					'viewtitle' => $xzv_90['viewtitle'],
					'viewkw' => $xzv_90['viewkw'],
					'viewdes' => $xzv_90['viewdes'],
					'chaptertitle' => $xzv_90['chaptertitle'],
					'chapterkw' => $xzv_90['chapterkw'],
					'chapterdes' => $xzv_90['chapterdes'],
				);
				F('category', $xzv_40);
				$this->success('栏目设置更新成功！');
			}
			else {
				$this->assign('action', $xzv_132);
				$this->assign('category', $xzv_40);
				$this->assign('dir', $xzv_110);
				$this->display();
			}
		}
		else {
			$this->assign('category', $xzv_40);
			$this->assign('setting', $xzv_10);
			$this->display();
		}
	}

	public function chapter()
	{
		$xzv_41 = I('param.action');
		$xzv_88 = I('param.id', '', 'intval');
		$xzv_151 = I('param.cid', '', 'intval');
		if (!$xzv_88) {
			$this->error('小说ID有误');
		}
		$xzv_18 = floor($xzv_88/1000);
		$this->assign('action', $xzv_41);
		$this->assign('id', $xzv_88);
		$this->assign('cid', $xzv_151);
		$xzv_45 = F('view/chapter/'.$xzv_18.'/'.$xzv_88);
		$xzv_146 = M('articles')->where('id=%d', $xzv_88)->find();
		if (!$xzv_45) {
			if ($xzv_146['original'] == 1) {
				F('view/chapter/'.$xzv_18.'/'.$xzv_88, array());
				$xzv_43 = F('view/book/'.$xzv_18.'/'.$xzv_88);
				if (!$xzv_43) {
					$this->error('请先编辑小说，填写完善简介信息后才能增加章节', U('article', array(
						'action' => 'edit',
						'id' => $xzv_88
					)));
				}
			}
			else {
				$this->error('该小说没有章节列表');
			}
		}
		if ($xzv_41 == 'list') {
			$this->assign('articledb', $xzv_146);
			$this->assign('chapterlist', $xzv_45);
			$this->display();
		}
		elseif ($xzv_41 == 'edit'){
			$xzv_146 = F('view/book/'.$xzv_18.'/'.$xzv_88);
			$xzv_42 = F('view/chaptercont/'.$xzv_18.'/'.$xzv_88.'/'.$xzv_151);
			if (I('post.step') == 'save') {
				$xzv_78 = $xzv_42['title'];
				$xzv_42['title'] = I('post.title');
				$xzv_42['content'] = I('post.content', '', 'htmlspecialchars_decode');
				$xzv_42['link'] = I('post.link');
				if (!$xzv_42['nextcid']) {
					$xzv_42['rewriteurl'] = reurl('chapter', array(
						'id' => $xzv_88,
						'cate' => $xzv_146['cate'],
						'cid' => $xzv_151
					));
					$xzv_42['nextcid'] = $xzv_151 < count($xzv_45)-1 ? $xzv_151+1 : -1;
					$xzv_42['prevcid'] = $xzv_151 > 0 ? $xzv_151-1 : -1;
				}
				F('view/chaptercont/'.$xzv_18.'/'.$xzv_88.'/'.$xzv_151, $xzv_42);
				if ($xzv_78 != $xzv_42['title']) {
					$xzv_45 = F('view/chapter/'.$xzv_18.'/'.$xzv_88);
					$xzv_106 = F('view/newchapter/'.$xzv_18.'/'.$xzv_88);
					if (is_array($xzv_45[$xzv_151])) {
						$xzv_45[$xzv_151]['title'] = $xzv_42['title'];
						$xzv_45[$xzv_151]['link'] = $xzv_42['link'];
						F('view/chapter/'.$xzv_18.'/'.$xzv_88, $xzv_45);
					}
					if (is_array($xzv_106[$xzv_151])) {
						$xzv_106[$xzv_151]['title'] = $xzv_42['title'];
						$xzv_106[$xzv_151]['link'] = $xzv_42['link'];
						F('view/newchapter/'.$xzv_18.'/'.$xzv_88, $xzv_106);
					}
				}
				delchapter($xzv_88, $xzv_151);
				delhtml($xzv_88);
				$this->success('章节更新成功！');
			}
			else {
				$this->assign('articledb', $xzv_146);
				$this->assign('chapter', $xzv_42);
				$this->display();
			}
		}
		elseif ($xzv_41 == 'delete'){
			unset($xzv_45[$xzv_151]);
			delchapter($xzv_88, $xzv_151);
			delhtml($xzv_88);
			$xzv_74 = DATA_PATH.'view/chaptercont/'.$xzv_18.'/'.$xzv_88;
			clearfile($xzv_74);
			F('view/chapter/'.$xzv_18.'/'.$xzv_88, $xzv_45);
			$this->success('章节删除成功！');
		}
		elseif ($xzv_41 == 'add'){
			if (I('post.step') == 'save') {
				$xzv_151 = count($xzv_45);
				$xzv_42['title'] = I('post.title');
				$xzv_42['content'] = I('post.content', '', 'htmlspecialchars_decode');
				$xzv_42['link'] = I('post.link');
				$xzv_42['rewriteurl'] = reurl('chapter', array(
					'id' => $xzv_88,
					'cate' => $xzv_146['cate'],
					'cid' => $xzv_151
				));
				$xzv_42['nextcid'] = -1;
				$xzv_42['prevcid'] = $xzv_151-1;
				F('view/chaptercont/'.$xzv_18.'/'.$xzv_88.'/'.$xzv_151, $xzv_42);
				$xzv_45[] = array(
					'link' => '',
					'title' => $xzv_42['title'],
					'id' => $xzv_88,
					'cid' => $xzv_151,
					'cate' => $xzv_146['cate']
				);
				F('view/chapter/'.$xzv_18.'/'.$xzv_88, $xzv_45);
				foreach (array_reverse($xzv_45, true) as $xzv_85 => $xzv_48) {
					$xzv_84[$xzv_85] = $xzv_53[$xzv_111] = $xzv_48;
					$xzv_111++;
					if ($xzv_111 > 11) {
						break;
					}
				}
				if (count($xzv_84) > 0) {
					F('view/newchapter/'.$xzv_18.'/'.$xzv_88, $xzv_84);
				}
				$xzv_146 = F('view/book/'.$xzv_18.'/'.$xzv_88);
				$xzv_146['lastchapter'] = $xzv_42['title'];
				$xzv_146['lastcid'] = $xzv_151;
				F('view/book/'.$xzv_18.'/'.$xzv_88, $xzv_146);
				M('articles')->where('id=%d', $xzv_88)->setField(array(
					'lastchapter' => $xzv_42['title'],
					'lastcid' => $xzv_151
				));
				delhtml($xzv_88);
				$this->success('章节新增成功！');
			}
			else {
				$xzv_146 = F('view/book/'.$xzv_18.'/'.$xzv_88);
				$this->assign('articledb', $xzv_146);
				$this->display();
			}
		}
	}

	public function dataarea()
	{
		$xzv_77 = I('param.action');
		$xzv_19 = $this->dataarea;
		$xzv_82 = I('param.');
		$xzv_50 = I('param.domain', 'default');
		$this->assign('action', $xzv_77);
		$this->assign('nowdomain', $xzv_50);
		if ($xzv_77 == 'edit') {
			$xzv_149 = I('param.did');
			if (!$xzv_50) {
				$this->error('没有指定区块domain');
			}
			$xzv_19 = F('dataarea/'.$xzv_50);
			if (I('post.do') == 'save') {
				$xzv_19[$xzv_149] = array(
					'open' => $xzv_82['open'],
					'did' => $xzv_149,
					'cate' => $xzv_82['cate'],
					'ids' => $xzv_82['ids'],
					'orderby' => $xzv_82['orderby'],
					'orderway' => $xzv_82['orderway'],
					'hasthumb' => $xzv_82['hasthumb'],
					'hasinfo' => $xzv_82['hasinfo'],
					'isfull' => $xzv_82['isfull'],
					'limit' => intval($xzv_82['limit']),
					'infolen' => intval($xzv_82['infolen']),
					'dateformat' => $xzv_82['dateformat'],
					'expirehour' => intval($xzv_82['expirehour']),
				);
				F('dataarea/'.$xzv_50, $xzv_19);
				$this->success('区块设置更新成功！', U('dataarea', array(
					'domain' => $xzv_50
				)));
				die;
			}
			else {
				$xzv_38 = <<<EOT
<foreach name="dataarea_list.{$xzv_149}
			" item="v">
<li>
封面：<a href="{&#36;v.rewriteurl}"><img src="{&#36;v.thumb}" /></a><br>
书名：<a href="{&#36;v.rewriteurl}">{&#36;v.title}</a><br>
作者：{&#36;v.author}<br>
分类：<a href="{&#36;v.cateurl}">{&#36;v.catename}</a><br>
发表时间：{&#36;v.posttime}<br>
更新时间：{&#36;v.updatetime}<br>
人气：{&#36;v.views}<br>
连载/完成：{&#36;v.status}<br>
周人气：{&#36;v.weekviews}<br>
月人气：{&#36;v.monthviews}<br>
简介：{&#36;v.description}<br>
最新章节:<a href="{&#36;v.lastchapterurl}">{&#36;v.lastchapter}</a>
</li>
</foreach>
EOT;
			$this->assign('did', $xzv_149);
			$this->assign('democode', $xzv_38);
			$this->assign('dataarea', $xzv_19);
			$this->assign('category', $this->category);
		}
		$this->display();
	}

	elseif ($xzv_77 == 'save'){
		if (!$xzv_50) {
			$this->error('没有指定区块domain');
		}
		$xzv_26 = F('dataarea/'.$xzv_50);
		foreach ($xzv_82['copen'] as $xzv_81 => $xzv_83) {
			$xzv_149 = $xzv_82['cdid'][$xzv_81];
			if (!$xzv_83 || $xzv_83 == 'deleted') {
				unset($xzv_26[$xzv_149]);
			}
			else {
				$xzv_26[$xzv_149]['open'] = $xzv_83;
				$xzv_26[$xzv_149]['did'] = $xzv_149;
			}
		}
		foreach ($xzv_26 as $xzv_30 => $xzv_4) {
			if (!$xzv_4) {
				unset($xzv_26[$xzv_30]);
			}
		}
		if (is_array($xzv_82['ndid'])) {
			foreach ($xzv_82['ndid'] as $xzv_81 => $xzv_83) {
				if ($xzv_83 && $xzv_26[$xzv_83]['did'] != '') {
					$this->error('已存在相同id的区块！');
				}
				if (!$xzv_83) {
					unset($xzv_26[$xzv_83]);
				}
				else {
					$xzv_26[$xzv_83] = array(
						'open' => $xzv_82['nopen'][$xzv_81],
						'did' => $xzv_83
					);
				}
			}
		}
		F('dataarea/'.$xzv_50, $xzv_26);
		$this->success('数据区块更新成功！');
		die;
	}
	elseif ($xzv_77 == 'initialize'){
		if (!$xzv_50) {
			$this->error('没有指定区块domain');
		}
		if ($xzv_50 == 'default' || $xzv_50 == 'wap') {
			$xzv_123 = 'default';
		}
		$xzv_19 = F('dataarea/'.$xzv_50);
		if (!$xzv_123 || !$xzv_19) {
			$this->error('没有找到主domain或无区块数据');
		}
		$xzv_122 = M('articles');
		foreach ($xzv_19 as $xzv_30 => $xzv_4) {
			if ($xzv_4['open'] != 'yes') {
				continue;
			}
			$xzv_86 = 'dataarea_'.$xzv_4['did'];
			$xzv_3 = in_array(strtolower($xzv_4['orderby']), array(
				'id',
				'views',
				'weekviews',
				'monthviews',
				'posttime',
				'updatetime'
			)) ? strtolower($xzv_4['orderby']) : 'id';
			$xzv_125 = in_array(strtolower($xzv_4['orderway']), array(
				'desc',
				'asc'
			)) ? strtolower($xzv_4['orderway']) : 'desc';
			$xzv_36 = in_array(strtolower($xzv_4['hasthumb']), array(
				'yes',
				'no'
			)) ? strtolower($xzv_4['hasthumb']) : 'no';
			$xzv_80 = in_array(strtolower($xzv_4['hasinfo']), array(
				'yes',
				'no'
			)) ? strtolower($xzv_4['hasinfo']) : 'no';
			$xzv_79 = in_array(strtolower($xzv_4['isfull']), array(
				'yes',
				'no'
			)) ? strtolower($xzv_4['isfull']) : 'no';
			$xzv_8 = intval($xzv_4['limit']) ? intval($xzv_4['limit']) : 10;
			$xzv_73 = intval($xzv_4['infolen']) ? intval($xzv_4['infolen']) : 40;
			$xzv_114 = intval($xzv_4['expirehour']) ? intval($xzv_4['expirehour']) : 40;
			$xzv_75 = $xzv_4['dateformat'] ? $xzv_4['dateformat'] : 'Y-m-d H:i:s';
			if ($xzv_4['ids']) {
				$xzv_76 = explode(',', $xzv_4['ids']);
				$xzv_145 = count($xzv_76) == 1 ? true : false;
				$xzv_76 = "'".implode("','", $xzv_76)."'";
				$xzv_87 = "a.id in($xzv_76)";
			}
			else {
				$xzv_4['cate'] = $xzv_4['cate'] == 'default' ? $this->defaultdir : $xzv_4['cate'];
				$xzv_118 = $xzv_4['cate'] ? $xzv_4['cate'] : 'all';
				$xzv_87 = $xzv_118 != 'all' ? "a.cate='$xzv_118'" : '1';
				if ($xzv_36 == 'yes') {
					$xzv_87 .= ' and a.thumb is not null';
				}
				if ($xzv_80 == 'yes') {
					$xzv_87 .= ' and a.info is not null';
				}
				if ($xzv_79 == 'yes') {
					$xzv_87 .= ' and a.full=1';
				}
			}
			if (strexists($xzv_3, 'views')) {
				if ($xzv_3 == 'weekviews') {
					$xzv_89 = date('W', NOW_TIME);
					$xzv_87 .= " and av.weekkey='$xzv_89'";
				}
				elseif ($xzv_3 == 'monthviews'){
					$xzv_117 = date('n', NOW_TIME);
					$xzv_87 .= " and av.monthkey='$xzv_117'";
				}
				$xzv_3 = 'av.'.$xzv_3;
			}
			else {
				$xzv_3 = 'a.'.$xzv_3;
			}
			$xzv_35 = $xzv_3.' '.$xzv_125;
			$xzv_13 = $xzv_122->alias('a')->join('LEFT JOIN '.C('DB_PREFIX')
				. 'article_views av ON a.id=av.aid')->field('a.id,a.title,a.thumb,a.cate,a.info,a.posttime,a.updatetime,a.lastchapter,a.lastcid,a.author,a.full,av.views,av.weekviews,av.monthviews')->where($xzv_87)->order($xzv_35)->limit(0,
				$xzv_8)->select();
			$xzv_28 = $this->category;
			foreach ($xzv_13 as $xzv_134 => $xzv_93) {
				$xzv_93['subid'] = floor($xzv_93['id']/1000);
				$xzv_124[$xzv_134]['title'] = $xzv_93['title'];
				$xzv_124[$xzv_134]['rewriteurl'] = reurl('view', $xzv_93,
					$xzv_123);
				$xzv_124[$xzv_134]['cateurl'] = reurl('cate', $xzv_93['cate'],
					$xzv_123);
				if ($xzv_93['cate'] == $this->defaultdir || !$xzv_93['cate']) {
					$xzv_135 = 'default';
				}
				else {
					$xzv_135 = $xzv_93['cate'];
				}
				$xzv_124[$xzv_134]['description'] = $xzv_93['info'] ? mb_substr($xzv_93['info'],
					0, $xzv_73, 'utf-8') : $xzv_93['title'];
				$xzv_124[$xzv_134]['catename'] = $xzv_28[$xzv_135]['name'];
				$xzv_124[$xzv_134]['catename_short'] = mb_substr($xzv_124[$xzv_134]['catename'],
					0, 2, 'utf-8');
				$xzv_124[$xzv_134]['thumb'] = showcover($xzv_93['thumb']);
				$xzv_124[$xzv_134]['posttime'] = date($xzv_93['posttime'],
					$xzv_75);
				$xzv_124[$xzv_134]['updatetime'] = date($xzv_93['updatetime'],
					$xzv_75);
				$xzv_124[$xzv_134]['lastchapter'] = $xzv_93['lastchapter'] ? $xzv_93['lastchapter'] : '最新一章';
				$xzv_124[$xzv_134]['lastchapterurl'] = $xzv_93['lastcid'] ? reurl('chapter',
					array(
					'id' => $xzv_93['id'],
					'cate' => $xzv_93['cate'],
					'cid' => $xzv_93['lastcid']
				), $xzv_123) : $xzv_124[$xzv_30]['rewriteurl'];
				$xzv_124[$xzv_134]['author'] = $xzv_93['author'];
				$xzv_124[$xzv_134]['views'] = intval($xzv_93['views']);
				$xzv_124[$xzv_134]['weekviews'] = intval($xzv_93['weekviews']);
				$xzv_124[$xzv_134]['monthviews'] = intval($xzv_93['monthviews']);
				$xzv_124[$xzv_134]['status'] = $xzv_93['full'] > 0 ? '完成' : '连载';
				if ($xzv_145) {
					$xzv_124[$xzv_134]['articledb'] = F('view/book/'.$xzv_93['subid']
						. '/'.$xzv_93['id']);
					$xzv_124[$xzv_134]['newchapter'] = F('view/newchapter/'
						. $xzv_93['subid'].'/'.$xzv_93['id']);
					$xzv_145 = false;
					break;
				}
			}
			S($xzv_123.'_'.$xzv_86, NOW_TIME, array(
				'temp' => TEMP_PATH.'dataarea/',
				'expire' => $xzv_114 * 3600
			));
			F('dataarea/'.$xzv_50.'/'.$xzv_86, $xzv_124);
			unset($xzv_124);
		}
		$this->success('数据更新成功！', U('dataarea'));
		die;
	}
	elseif ($xzv_77 == 'export'){
		if (!$xzv_50) {
			$this->error('只允许指定域名');
		}
		$xzv_128 = F('dataarea/'.$xzv_50);
		if (!$xzv_128) {
			$this->error('无相关数据');
		}
		$xzv_12 = base64_encode(serialize($xzv_128));
		$this->assign('action', $xzv_77);
		$this->assign('exportcode', $xzv_12);
		$this->display();
	}
	elseif ($xzv_77 == 'import'){
		if (!$xzv_50) {
			$this->error('只允许指定域名');
		}
		if (I('post.do') == 'save') {
			$xzv_60 = unserialize(base64_decode(I('post.code')));
			if ($xzv_50) {
				F('dataarea/'.$xzv_50, $xzv_60);
				$this->success('站点：'.$xzv_50.'数据导入成功！', U('dataarea'));
			}
			else {
				$this->error('数据导入失败！');
			}
		}
		else {
			$this->assign('nowdomain', $xzv_50);
			$this->assign('action', $xzv_77);
			$this->display();
		}
	}
	else {
		$xzv_19 = $xzv_50 ? F('dataarea/'.$xzv_50) : null;
		$this->assign('dataarea', $xzv_19);
		$this->display();
	}
}

public function tags()
{
	$xzv_143 = I('param.action');
	if (!$xzv_143) {
		$xzv_11 = 50;
		$xzv_54 = I('get.p', 1, 'intval');
		$xzv_72 = M('tags');
		$xzv_120 = $xzv_72->limit(($xzv_54-1) * $xzv_11, $xzv_11)->order('num desc')->select();
		foreach ($xzv_120 as $xzv_133 => $xzv_112) {
			$xzv_120[$xzv_133]['tagurl'] = reurl('tag', $xzv_112);
		}
		$xzv_55 = $xzv_72->Count();
		$xzv_27 = pagelist_thinkphp($xzv_55, $xzv_11);
		$this->assign('taglist', $xzv_120);
		$this->assign('tagnum', $xzv_55);
		$this->assign('pagehtml', $xzv_27);
	}
	elseif ($xzv_143 == 'view'){
		$xzv_116 = I('get.tid', '', 'intval');
		$xzv_14 = M('tagdatas');
		$xzv_17 = M('articles');
		$xzv_129 = $xzv_14->where("tid='$xzv_116'")->select();
		$xzv_113 = $xzv_14->where("tid='$xzv_116'")->Count();
		foreach ($xzv_129 as $xzv_133 => $xzv_112) {
			$xzv_148 = $xzv_148 ? $xzv_148.','.$xzv_112['aid'] : $xzv_112['aid'];
		}
		$xzv_33['id'] = array('in', $xzv_148);
		$xzv_16 = $xzv_17->where($xzv_33)->order('id desc')->limit(50)->select();
		foreach ($xzv_16 as $xzv_133 => $xzv_112) {
			$xzv_119 = $xzv_112['cate'] == $this->defaultdir ? 'default' : $xzv_112['cate'];
			$xzv_16[$xzv_133]['catename'] = $this->category[$xzv_119]['name'];
			$xzv_16[$xzv_133]['posttime'] = date('Y-m-d H:i', $xzv_112['posttime']);
			$xzv_16[$xzv_133]['rewriteurl'] = reurl('view', $xzv_112);
		}
		$this->assign('arclist', $xzv_16);
		$this->assign('arcnum', $xzv_113);
	}
	$this->assign('action', $xzv_143);
	$this->display();
}

public function spider()
{
	$xzv_115 = I('param.action');
	if ($xzv_115 == 'clearall') {
		$xzv_15 = 'TRUNCATE `'.C('DB_PREFIX').'spiderlogs`';
		M()->execute($xzv_15);
		$this->success('蜘蛛历史记录已清空！', 'Spider');
	}
	else {
		$xzv_32 = I('param.domain');
		$xzv_67 = M('spiderlogs');
		$xzv_121 = M('settingmeta');
		$xzv_68 = $xzv_32 ? "domain='$xzv_32'" : '1';
		$xzv_9 = intval($xzv_121->where("meta_key='spider_day'")->getField('meta_value'));
		$xzv_140 = intval($xzv_121->where("meta_key='spider_lastday'")->getField('meta_value'));
		$xzv_2 = 100;
		$xzv_1 = I('get.p', 1, 'intval');
		$xzv_0 = $xzv_67->where($xzv_68)->order('id desc')->limit(($xzv_1-1) * $xzv_2,
			$xzv_2)->select();
		$xzv_29 = $xzv_67->where($xzv_68)->Count();
		$xzv_141 = pagelist_thinkphp($xzv_29, $xzv_2);
		$xzv_70 = strtotime(date('Y-m-d'));
		$xzv_59['google'] = $xzv_67->where("spider='Google' and dateline > '$xzv_70' and "
			. $xzv_68)->Count();
		$xzv_59['baidu'] = $xzv_67->where("spider='Baidu' and dateline > '$xzv_70' and "
			. $xzv_68)->Count();
		$xzv_59['360'] = $xzv_67->where("spider='360搜索' and dateline > '$xzv_70' and "
			. $xzv_68)->Count();
		$xzv_59['sogou'] = $xzv_67->where("spider='Sogou' and dateline > '$xzv_70' and "
			. $xzv_68)->Count();
		$xzv_59['shenma'] = $xzv_67->where("spider='神马' and dateline > '$xzv_70' and "
			. $xzv_68)->Count();
		$this->assign('setting', $this->setting);
		$this->assign('nowdomain', $xzv_32);
		$this->assign('spider_day', $xzv_9);
		$this->assign('spider_lastday', $xzv_140);
		$this->assign('spiderloglist', $xzv_0);
		$this->assign('spider_stat', $xzv_59);
		$this->assign('pagehtml', $xzv_141);
		$this->display();
	}
}

public function cache()
{
	$xzv_7 = I('param.');
	$xzv_144 = $xzv_7['action'];
	if ($xzv_144 == 'clear') {
		if (in_array($xzv_7['id'], array(
			'system',
			'index',
			'list',
			'article',
			'pick',
			'sitemap',
			'seodata'
		))) {
			switch ($xzv_7['id']) {
			case 'system':
				unlink(RUNTIME_PATH.'common~runtime.php');
				break;
			case 'index':
				$xzv_61 = $xzv_6 ? TEMP_PATH.$xzv_6.'/index/' : TEMP_PATH
					. 'index/';
				clearfile($xzv_61);
				delhtml('index');
				break;
			case 'list':
				$xzv_61 = $xzv_6 ? TEMP_PATH.$xzv_6.'/cate/' : TEMP_PATH
					. 'cate/';
				clearfile($xzv_61);
				delhtml('cate');
				break;
			case 'article':
				$xzv_49 = $xzv_7['aid'];
				$xzv_52 = floor($xzv_49/1000);
				if (!$xzv_49){
					$this->error('操作有误！');
				}
				delhtml($xzv_49);
				F('view/book/'.$xzv_52.'/'.$xzv_49, null);
				F('view/chapter/'.$xzv_52.'/'.$xzv_49, null);
				F('view/newchapter/'.$xzv_52.'/'.$xzv_49, null);
				S('chaptercache_'.$xzv_49, null);
				$xzv_61 = DATA_PATH.'view/chaptercont/'.$xzv_52.'/'.$xzv_49;
				clearfile($xzv_61);
				break;
			case 'pick':
				unlink(RUNTIME_PATH.'~crons.php');
				break;
			case 'sitemap':
				$xzv_99 = $xzv_6 ? TEMP_PATH.$xzv_6 : TEMP_PATH;
				S('mapinfo', null, array('temp' => $xzv_99));
				break;
			case 'seodata':
				$xzv_99 = $xzv_6 ? TEMP_PATH.$xzv_6 : TEMP_PATH;
				S('seodata', null, array('temp' => $xzv_99));
				break;
			default:
				break;
			}
			$this->success('缓存清理成功！');
		}
		else {
			$this->error('操作有误！');
		}
	}
	else {
		$this->display();
	}
}

public function searchlog()
{
	$xzv_62 = M('searchlog');
	$xzv_97 = 100;
	$xzv_96 = I('get.p', 1, 'intval');
	$xzv_51 = $xzv_62->order('hasresult asc,num desc,id desc')->limit(($xzv_96-1) * $xzv_97,
		$xzv_97)->select();
	$xzv_142 = $xzv_62->Count();
	$xzv_94 = pagelist_thinkphp($xzv_142, $xzv_97);
	$this->assign('searchloglist', $xzv_51);
	$this->assign('pagehtml', $xzv_94);
	$this->display();
}

public function seowords()
{
	$xzv_63 = I('param.');
	$xzv_138 = $xzv_63['action'];
	$this->assign('action', $xzv_138);
	$xzv_139 = M('seowords');
	if ($xzv_138 == 'add' || $xzv_138 == 'edit') {
		if ($xzv_138 == 'edit') {
			$xzv_5 = intval($xzv_63['id']);
			$xzv_107 = $xzv_139->where('id=%d', $xzv_5)->find();
		}
		$this->assign('seodb', $xzv_107);
		if ($xzv_63['do'] == 'save') {
			$xzv_64 = $xzv_63['ename'];
			if ($xzv_138 == 'add') {
				$xzv_39 = array();
				$xzv_46 = explode('
', $xzv_63['sitename']);
				$xzv_66 = explode('
', $xzv_63['ename']);
				if (count($xzv_46) != count($xzv_66)) {
					$this->error('站点名称数量和ename数量不一致，必须一一对应');
				}
				if (count($xzv_46) > 0) {
					foreach ($xzv_46 as $xzv_44 => $xzv_152) {
						if ($xzv_152) {
							$xzv_47 = trim($xzv_66[$xzv_44]);
							if (!preg_match('/^[0-9a-zA-Z]{2,50}$/', $xzv_47)) {
								$this->error('ename为“'.$xzv_47.'”只能包含字母和数字，不支持汉字和符号');
							}
							$xzv_105 = $xzv_139->where("ename='%s'", $xzv_47)->find();
							if ($xzv_105) {
								$this->error('已存在“'.$xzv_47.'”的关键词');
							}
							$xzv_39[] = array(
								'sitename' => $xzv_152,
								'ename' => $xzv_47,
								'title' => str_replace('{name}', $xzv_152,
									$xzv_63['title']),
								'keywords' => str_replace('{name}', $xzv_152,
									$xzv_63['keywords']),
								'description' => str_replace('{name}', $xzv_152,
									$xzv_63['description']),
							);
						}
					}
				}
				else {
					$xzv_39[] = array(
						'sitename' => $xzv_63['sitename'],
						'ename' => $xzv_63['ename'],
						'title' => str_replace('{name}', $xzv_63['sitename'],
							$xzv_63['title']),
						'keywords' => str_replace('{name}', $xzv_63['sitename'],
							$xzv_63['keywords']),
						'description' => str_replace('{name}', $xzv_63['sitename'],
							$xzv_63['description']),
					);
				}
				$xzv_139->addAll($xzv_39);
			}
			else {
				$xzv_137 = array(
					'sitename' => $xzv_63['sitename'],
					'ename' => $xzv_63['ename'],
					'title' => str_replace('{name}', $xzv_63['sitename'],
						$xzv_63['title']),
					'keywords' => str_replace('{name}', $xzv_63['sitename'],
						$xzv_63['keywords']),
					'description' => str_replace('{name}', $xzv_63['sitename'],
						$xzv_63['description']),
				);
				$xzv_105 = $xzv_139->where('id=%d', $xzv_63['id'])->find();
				if (!$xzv_105) {
					$this->error('出错了，关键词不存在');
				}
				$xzv_139->where('id=%d', $xzv_63['id'])->save($xzv_137);
			}
			F('seowords', null);
			$this->success('操作成功', U('seowords'));
		}
		else {
			$this->display();
		}
	}
	elseif ($xzv_138 == 'del'){
		$xzv_5 = I('get.id', '', 'intval');
		$xzv_139->where('id=%d', $xzv_5)->delete();
		$this->success('删除成功', U('seowords'));
	}
	else {
		$xzv_65 = 100;
		$xzv_22 = I('get.p', 1, 'intval');
		$xzv_104 = $xzv_139->order('views desc,id desc')->limit(($xzv_22-1) * $xzv_65,
			$xzv_65)->select();
		$xzv_153 = $xzv_139->Count();
		$xzv_69 = pagelist_thinkphp($xzv_153, $xzv_65);
		$this->assign('seowordlist', $xzv_104);
		$this->assign('pagehtml', $xzv_69);
		$this->display();
	}
}

public function advertise()
{
	$xzv_25 = I('action');
	$xzv_34 = I('post.');
	$xzv_109 = F('advertise');
	$xzv_127 = F('advertise_extend');
	if (!$xzv_25) {
		$this->assign('advcode', $xzv_109);
		$this->assign('advcode_extend', $xzv_127);
		$this->display();
	}
	else {
		foreach ($xzv_34['id'] as $xzv_108 => $xzv_71) {
			if (!$xzv_71) {
				continue;
			}
			$xzv_109[$xzv_71] = array(
				'id' => $xzv_71,
				'title' => $xzv_34['title'][$xzv_108],
				'code' => htmlspecialchars_decode($xzv_34['code'][$xzv_108]),
				'code_wap' => htmlspecialchars_decode($xzv_34['code_wap'][$xzv_108])
			);
		}
		if (is_array($xzv_34['eid'])) {
			foreach ($xzv_34['eid'] as $xzv_102 => $xzv_37) {
				if (!$xzv_37) {
					continue;
				}
				if ($xzv_34['edel'][$xzv_102]) {
					unset($xzv_127[$xzv_37]);
					continue;
				}
				$xzv_127[$xzv_37] = array(
					'id' => $xzv_37,
					'title' => $xzv_34['etitle'][$xzv_102],
					'code' => htmlspecialchars_decode($xzv_34['ecode'][$xzv_102]),
					'code_wap' => htmlspecialchars_decode($xzv_34['ecode_wap'][$xzv_102])
				);
			}
		}
		if (is_array($xzv_34['nid'])) {
			foreach ($xzv_34['nid'] as $xzv_101 => $xzv_58) {
				if (!$xzv_58) {
					continue;
				}
				if ($xzv_127['extend_'.$xzv_58]) {
					$this->error('已存在名为：extend_'.$xzv_58.'的广告位');
				}
				$xzv_127['extend_'.$xzv_58] = array(
					'id' => 'extend_'.$xzv_58,
					'title' => $xzv_34['ntitle'][$xzv_101],
					'code' => htmlspecialchars_decode($xzv_34['ncode'][$xzv_101]),
					'code_wap' => htmlspecialchars_decode($xzv_34['ncode_wap'][$xzv_101])
				);
			}
		}
		F('advertise', $xzv_109);
		F('advertise_extend', $xzv_127);
		$this->success('操作成功');
	}
}

public function pickers()
{
	$xzv_57 = M('article_pickers');
	$xzv_20 = 100;
	$xzv_56 = I('get.p', 1, 'intval');
	$xzv_100 = $xzv_57->alias('p')->join(C('DB_PREFIX').'articles a ON a.id = p.aid')->order('p.id desc')->limit(($xzv_56-1) * $xzv_20,
		$xzv_20)->field('p.url,p.pid,a.title,p.updatetime,p.id')->select();
	$xzv_130 = $xzv_57->Count();
	$xzv_98 = pagelist_thinkphp($xzv_130, $xzv_20);
	$this->assign('pickerlist', $xzv_100);
	$this->assign('pagehtml', $xzv_98);
	$this->display();
}
}
