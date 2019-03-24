<?php
namespace Admin\Controller;

use Admin\Controller\BaseController;
use Think\Controller;
class IndexController extends AdminController
{
    public function index()
    {
        $xzv_134 = file_get_contents(CONF_PATH . 'ver.txt');
        if (I('action') == 'getupdate') {
            $xzv_57 = file_get_contents('http://vip.0rg.pw/version/' . NOW_TIME);
            $xzv_88 = json_decode($xzv_57, true);
            $xzv_86 = $xzv_88['ver'];
            if ($xzv_86 && $xzv_86 != $xzv_134) {
                echo htmlspecialchars_trans($xzv_88['url'], 'pick') . '#from=' . $xzv_86 . '&to=' . $xzv_134;
                die;
            } else {
                die;
            }
        }
        $xzv_68 = basestat();
        $xzv_118 = array('ver' => $xzv_134, 'os' => explode(' ', php_uname()), 'soft' => $_SERVER['SERVER_SOFTWARE'], 'php' => PHP_VERSION);
        $this->assign('counts', $xzv_68);
        $this->assign('serverinfo', $xzv_118);
        $this->display();
    }
    public function logout()
    {
        session('adminname', null);
        session('adminpwd', null);
        $this->success('成功退出，1秒后转向登录页面！', U('login'));
    }
    public function login()
    {
        if (!checkadmin()) {
            $xzv_69 = I('param.');
            $xzv_18 = $xzv_69['action'];
            if ($xzv_18 == 'login') {
                $xzv_55 = F('setting');
                if (strlen($xzv_69['adminname']) > 30 || strlen($xzv_69['password']) > 30) {
                    $this->error('用户名或密码不能超过30位！');
                }
                if (!$xzv_69['password'] || !$xzv_69['adminname']) {
                    $this->error('用户名或密码不能为空！');
                }
                $xzv_128 = M('settingmeta');
                $xzv_99 = $xzv_128->where("meta_key='adminname'")->getField('meta_value');
                $xzv_23 = $xzv_128->where("meta_key='adminpwd'")->getField('meta_value');
                if (substr(md5($xzv_69['password']), 4, 18) == $xzv_23 && $xzv_69['adminname'] == $xzv_99) {
                    session('adminname', $xzv_69['adminname']);
                    session('adminpwd', substr(md5($xzv_69['password']), 4, 18));
                    $this->success('成功登录，1秒后转向管理主页！', U('index'));
                } else {
                    $this->error('用户名或密码错误!!!');
                }
            } else {
                C('LAYOUT_ON', false);
                $this->display();
            }
        } else {
            $this->redirect(U('index'));
        }
    }
    public function setting()
    {
        $xzv_130 = $this->setting;
        $xzv_56 = htmlspecialchars_trans(F('flink'), 'decode');
        $xzv_93 = htmlspecialchars_trans(F('flink_wap'), 'decode');
        $this->assign('flink', $xzv_56);
        $this->assign('flink_wap', $xzv_93);
        $xzv_51 = I('param.');
        $xzv_54 = $xzv_51['action'];
        if (!$xzv_54) {
            $xzv_52 = M('settingmeta');
            $xzv_95 = $xzv_52->where("meta_key='adminname'")->getField('meta_value');
            $xzv_130['seo']['weburl'] = $xzv_130['seo']['weburl'] ? $xzv_130['seo']['weburl'] : '/';
            $xzv_130['seo']['core_filter'] = htmlspecialchars_trans($xzv_130['seo']['core_filter'], 'decode');
            $xzv_130['seo']['repick_sign'] = htmlspecialchars_trans($xzv_130['seo']['repick_sign'], 'decode');
            $this->assign('setting', $xzv_130);
            $this->assign('adminname', $xzv_95);
            $this->display();
        } elseif ($xzv_54 == 'admin') {
            if (!$xzv_51['password'] || !$xzv_51['adminname']) {
                $this->error('用户名或密码不能为空！');
            }
            $xzv_52 = M('settingmeta');
            $xzv_95 = $xzv_51['adminname'];
            $xzv_2 = substr(md5($xzv_51['password']), 4, 18);
            $xzv_52->where("meta_key='adminname'")->setField('meta_value', $xzv_95);
            $xzv_52->where("meta_key='adminpwd'")->setField('meta_value', $xzv_2);
            session('adminname', null);
            session('adminpwd', null);
            $this->success('管理员信息更新成功，请重新登录！', U('login'));
        } elseif ($xzv_54 == 'flink') {
            $xzv_87 = htmlspecialchars_trans($xzv_51['flink'], 'encode');
            F('flink', $xzv_87);
            $xzv_1 = htmlspecialchars_trans($xzv_51['flink_wap'], 'encode');
            F('flink_wap', $xzv_1);
            $this->success('友情链接更新成功！');
        } elseif ($xzv_54 == 'save') {
            $xzv_51['statcode'] = I('post.statcode', '', 'htmlspecialchars_decode');
            $xzv_51['core_filter'] = htmlspecialchars_trans(I('post.core_filter', '', 'htmlspecialchars_decode'));
            $xzv_51['repick_sign'] = htmlspecialchars_trans(I('post.repick_sign', '', 'htmlspecialchars_decode'));
            $xzv_130['seo'] = array('debug' => $xzv_51['debug'], 'webdir' => $xzv_51['webdir'], 'pcdomain' => $xzv_51['pcdomain'], 'mobiledomain' => $xzv_51['mobiledomain'], 'pctheme' => $xzv_51['pctheme'], 'waptheme' => $xzv_51['waptheme'], 'webname' => $xzv_51['webname'], 'disjump' => $xzv_51['disjump'], 'piclocal_type' => $xzv_51['piclocal_type'], 'indextitle' => $xzv_51['indextitle'], 'indexkw' => $xzv_51['indexkw'], 'indexdes' => $xzv_51['indexdes'], 'sitemap_url' => $xzv_51['sitemap_url'], 'virtviews' => $xzv_51['virtviews'], 'advertise' => $xzv_51['advertise'], 'searchlimit' => intval($xzv_51['searchlimit']), 'tag_url' => $xzv_51['tag_url'], 'tagtitle' => $xzv_51['tagtitle'], 'tagkw' => $xzv_51['tagkw'], 'tagdes' => $xzv_51['tagdes'], 'author_url' => $xzv_51['author_url'], 'authortitle' => $xzv_51['authortitle'], 'authorkw' => $xzv_51['authorkw'], 'authordes' => $xzv_51['authordes'], 'seoword_url' => $xzv_51['seoword_url'], 'listtitle' => $xzv_51['listtitle'], 'listkw' => $xzv_51['listkw'], 'listdes' => $xzv_51['listdes'], 'listurl' => $xzv_51['listurl'], 'viewtitle' => $xzv_51['viewtitle'], 'viewkw' => $xzv_51['viewkw'], 'viewdes' => $xzv_51['viewdes'], 'viewurl' => $xzv_51['viewurl'], 'idrule' => intval($xzv_51['idrule']), 'chaptertitle' => $xzv_51['chaptertitle'], 'chapterkw' => $xzv_51['chapterkw'], 'chapterdes' => $xzv_51['chapterdes'], 'chapterurl' => $xzv_51['chapterurl'], 'cidrule' => intval($xzv_51['cidrule']), 'chapterload' => $xzv_51['chapterload'], 'chapterlimit' => intval($xzv_51['chapterlimit']), 'toptitle' => $xzv_51['toptitle'], 'topurl' => $xzv_51['topurl'], 'fulltitle' => $xzv_51['fulltitle'], 'fullurl' => $xzv_51['fullurl'], 'alltitle' => $xzv_51['alltitle'], 'allurl' => $xzv_51['allurl'], 'core_filter' => $xzv_51['core_filter'], 'repick_sign' => $xzv_51['repick_sign'], 'blackiplist' => $xzv_51['blackiplist'], 'blackbooklist' => $xzv_51['blackbooklist'], 'lazyload' => $xzv_51['lazyload'], 'znsearch' => $xzv_51['znsearch'], 'znsid' => $xzv_51['znsid'], 'pushapi' => $xzv_51['pushapi'], 'statcode' => $xzv_51['statcode']);
            if ($xzv_51['debug'] > 0) {
                cookie('ygbook_debug', 1);
            } else {
                cookie('ygbook_debug', null);
            }
            F('setting', $xzv_130);
            $this->success('参数更新成功！');
        } elseif ($xzv_54 == 'extra') {
            $xzv_130['extra'] = array('listcachetime' => intval($xzv_51['listcachetime']), 'chaptercachetime' => intval($xzv_51['chaptercachetime']), 'wlist_domain' => $xzv_51['wlist_domain'], 'html_option' => implode($xzv_51['html_option'], ','), 'pick_proxy' => $xzv_51['pick_proxy'], 'qiniu_bucket' => $xzv_51['qiniu_bucket'], 'qiniu_accesskey' => $xzv_51['qiniu_accesskey'], 'qiniu_secretkey' => $xzv_51['qiniu_secretkey'], 'qiniu_domian' => $xzv_51['qiniu_domian']);
            F('setting', $xzv_130);
            $this->success('参数更新成功！');
        } elseif ($xzv_54 == 'test_proxy') {
            header('Content-Type:text/html;charset=utf-8');
            G('begin');
            $xzv_85 = I('proxy');
            $xzv_6 = new \Org\Util\Caiji($xzv_85);
            $xzv_15 = $xzv_6->get_url('http://1212.ip138.com/ic.asp', true);
            G('end');
            $xzv_6 = new \Org\Util\Caiji();
            $xzv_96 = $xzv_6->get_url('http://1212.ip138.com/ic.asp', true);
            $xzv_97 = G('begin', 'end');
            echo '使用代理：' . $xzv_85 . '，响应时间: ' . $xzv_97 . 's，如结果不同，则表明代理成功' . '






真实IP：' . g2u($xzv_96) . '






代理IP：' . g2u($xzv_15);
        } elseif ($xzv_54 == 'showpush') {
            header('Content-Type:text/html;charset=utf-8');
            if (!$this->setting['seo']['pushapi']) {
                die('请填写百度主动推送API');
            }
            $xzv_143 = (is_HTTPS() ? 'https://' : 'http://') . $this->setting['seo']['pcdomain'];
            $xzv_90 = htmlspecialchars_trans($this->setting['seo']['pushapi'], 'pick');
            $xzv_62 = push_curl($xzv_90, $xzv_143);
            $xzv_53 = json_decode($xzv_62, true);
            if ($xzv_53['success']) {
                echo '该功能会占用推送名额，请勿频繁使用！！！<br><br><br>今日推送余额：' . $xzv_53['remain'] . '，详情：' . $xzv_62;
            } else {
                echo '该功能会占用推送名额，请勿频繁使用！！！<br><br><br>推送出错，可能超额，或者api不对，详情：' . $xzv_62;
            }
        }
    }
    public function pick()
    {
        $xzv_114 = M('articles');
        $xzv_78 = $this->category;
        $xzv_60 = F('pick');
        $xzv_149 = I('param.');
        $xzv_117 = $xzv_149['action'];
        if ($xzv_117 == 'edit') {
            $xzv_126 = $xzv_149['name'];
            $xzv_60[$xzv_126]['urlreplace'] = htmlspecialchars_trans($xzv_60[$xzv_126]['urlreplace'], 'decode');
            $xzv_60[$xzv_126]['list_selector_prefilter'] = htmlspecialchars_trans($xzv_60[$xzv_126]['list_selector_prefilter'], 'decode');
            $xzv_60[$xzv_126]['list_url_extra'] = htmlspecialchars_trans($xzv_60[$xzv_126]['list_url_extra'], 'decode');
            $xzv_60[$xzv_126]['view_selector_prefilter'] = htmlspecialchars_trans($xzv_60[$xzv_126]['view_selector_prefilter'], 'decode');
            $xzv_60[$xzv_126]['chapter_selector_prefilter'] = htmlspecialchars_trans($xzv_60[$xzv_126]['chapter_selector_prefilter'], 'decode');
            $xzv_60[$xzv_126]['chapter_regx'] = htmlspecialchars_trans($xzv_60[$xzv_126]['chapter_regx'], 'decode');
            $xzv_60[$xzv_126]['chaptercont_prefilter'] = htmlspecialchars_trans($xzv_60[$xzv_126]['chaptercont_prefilter'], 'decode');
            $this->assign('name', $xzv_126);
            $this->assign('pick', $xzv_60);
            if (I('post.do') == 'save') {
                $xzv_149['urlreplace'] = htmlspecialchars_trans(I('post.urlreplace', '', 'htmlspecialchars_decode'));
                $xzv_149['list_selector_prefilter'] = htmlspecialchars_trans(I('post.list_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_149['list_url_extra'] = htmlspecialchars_trans($xzv_149['list_url_extra']);
                $xzv_149['view_selector_prefilter'] = htmlspecialchars_trans(I('post.view_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_149['chapter_selector_prefilter'] = htmlspecialchars_trans(I('post.chapter_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_149['chapter_regx'] = htmlspecialchars_trans(I('post.chapter_regx', '', 'htmlspecialchars_decode'));
                $xzv_149['chaptercont_prefilter'] = htmlspecialchars_trans(I('post.chaptercont_prefilter', '', 'htmlspecialchars_decode'));
                foreach ($xzv_149['list_cate'] as $xzv_59 => $xzv_50) {
                    if ($xzv_50 && $xzv_149['list_cate'][$xzv_59]['list_ocate']) {
                        $xzv_106 = $xzv_149['list_maxpage'][$xzv_59] ? $xzv_149['list_maxpage'][$xzv_59] : 1;
                        $xzv_133['k_' . $xzv_50] = array('cate' => $xzv_50, 'ocate' => $xzv_149['list_ocate'][$xzv_59], 'maxpage' => $xzv_106);
                    }
                }
                $xzv_60[$xzv_126] = array('open' => $xzv_149['open'], 'breakpick' => $xzv_149['breakpick'], 'proxy' => $xzv_149['proxy'], 'piclocal' => $xzv_149['piclocal'], 'picattr' => $xzv_149['picattr'], 'name' => $xzv_126, 'cate' => $xzv_149['cate'], 'domain' => $xzv_149['domain'], 'urlreplace' => $xzv_149['urlreplace'], 'charset' => $xzv_149['charset'], 'list_url' => $xzv_149['list_url'], 'list_url_extra' => $xzv_149['list_url_extra'], 'list_page' => $xzv_149['list_page'], 'list_cate' => $xzv_133, 'list_maxpage' => $xzv_149['list_maxpage'], 'nothumb_sign' => $xzv_149['nothumb_sign'], 'list_selector_prefilter' => $xzv_149['list_selector_prefilter'], 'list_selector' => $xzv_149['list_selector'], 'list_title_selector' => $xzv_149['list_title_selector'], 'list_thumb_selector' => $xzv_149['list_thumb_selector'], 'list_author_selector' => $xzv_149['list_author_selector'], 'view_selector_prefilter' => $xzv_149['view_selector_prefilter'], 'viewtitle_selector' => $xzv_149['viewtitle_selector'], 'viewauthor_selector' => $xzv_149['viewauthor_selector'], 'viewcate_selector' => $xzv_149['viewcate_selector'], 'view_selector' => $xzv_149['view_selector'], 'viewthumb_selector' => $xzv_149['viewthumb_selector'], 'isfull_sign' => $xzv_149['isfull_sign'], 'viewchapter_selector' => $xzv_149['viewchapter_selector'], 'chapter_selector_prefilter' => $xzv_149['chapter_selector_prefilter'], 'chapterarea_selector' => $xzv_149['chapterarea_selector'], 'chapter_regx' => $xzv_149['chapter_regx'], 'chapter_order' => $xzv_149['chapter_order'], 'chapter_ordernum' => $xzv_149['chapter_ordernum'], 'chaptercont_prefilter' => $xzv_149['chaptercont_prefilter'], 'chaptercont_selector' => $xzv_149['chaptercont_selector'], 'chaptercont_pagesign' => $xzv_149['chaptercont_pagesign'], 'chaptercont_page' => $xzv_149['chaptercont_page'], 'chaptercont_par' => $xzv_149['chaptercont_par']);
                F('pick', $xzv_60);
                $this->success('采集点参数更新成功！', U('pick'));
            } else {
                $this->assign('category', $xzv_78);
                $this->assign('action', $xzv_117);
                $this->display();
            }
        } elseif ($xzv_117 == 'test') {
            $xzv_103 = I('param.name');
            $xzv_102 = I('param.step', '1', 'intval');
            if ($xzv_102 == 1) {
                $xzv_108 = pickrun('list', $xzv_103, 'test');
            } elseif ($xzv_102 == 2) {
                $xzv_140 = I('param.articleurl');
                $xzv_109 = pickrun('content', $xzv_103, $xzv_140);
            } elseif ($xzv_102 == 3) {
                $xzv_139 = I('param.chapterurl');
                $xzv_138 = pickrun('chapter', $xzv_103, $xzv_139);
                $xzv_9 = fillurl($xzv_139, $xzv_138['chapterlist'][0]['link']);
                $xzv_138 = serialize($xzv_138);
                $this->assign('curl', $xzv_9);
            } else {
                $xzv_139 = I('param.chapterurl');
                $xzv_138 = pickrun('chaptercontent', $xzv_103, $xzv_139);
                $xzv_79 = $xzv_138['content'];
                $this->assign('chaptercont', $xzv_79);
            }
            $this->assign('name', $xzv_103);
            $this->assign('listdata', $xzv_108);
            $this->assign('articledb', $xzv_109);
            $this->assign('chapterdb', $xzv_138);
            $this->assign('action', $xzv_117);
            $this->assign('step', $xzv_102);
            $this->display();
        } elseif ($xzv_117 == 'export') {
            $xzv_103 = I('param.name');
            $xzv_147 = $xzv_60[$xzv_103];
            $xzv_36 = base64_encode(serialize($xzv_147));
            $this->assign('action', $xzv_117);
            $this->assign('exportcode', $xzv_36);
            $this->assign('pname', $xzv_103);
            $this->display();
        } elseif ($xzv_117 == 'import') {
            $xzv_8 = I('param.code');
            $xzv_29 = I('param.pickname');
            $xzv_28 = I('param.cate');
            if (I('param.do') == 'save') {
                if (!$xzv_8) {
                    $this->error('请输入规则代码后再进行导入，否则会导致规则清空！');
                }
                $xzv_30 = unserialize(base64_decode($xzv_8));
                $xzv_30['name'] = $xzv_103 = $xzv_29 ? $xzv_29 : ($xzv_60[$xzv_30['name']] ? $xzv_30['name'] . '_' . mt_rand(100, 999) : $xzv_30['name']);
                $xzv_30['cate'] = $xzv_28;
                $xzv_60[$xzv_103] = $xzv_30;
                F('pick', $xzv_60);
                $this->success('采集规则' . $xzv_103 . '导入成功！', U('pick'));
            } else {
                $this->assign('pick', $xzv_60);
                $this->assign('category', $xzv_78);
                $this->assign('action', $xzv_117);
                $this->display();
            }
        } elseif ($xzv_117 == 'save') {
            foreach ($xzv_149['popen'] as $xzv_111 => $xzv_112) {
                $xzv_28 = $xzv_149['pcate'][$xzv_111];
                $xzv_126 = $xzv_149['pname'][$xzv_111];
                if (!$xzv_112 || !$xzv_28 || !$xzv_126 || $xzv_112 == 'deleted') {
                    unset($xzv_60[$xzv_126]);
                } else {
                    $xzv_60[$xzv_126]['open'] = $xzv_112;
                    $xzv_60[$xzv_126]['name'] = $xzv_126;
                    $xzv_60[$xzv_126]['cate'] = $xzv_149['pcate'][$xzv_111];
                }
            }
            $xzv_113 = 0;
            foreach ($xzv_60 as $xzv_59 => $xzv_50) {
                if (!$xzv_149['popen'][$xzv_113] || !$xzv_50) {
                    unset($xzv_60[$xzv_59]);
                }
                $xzv_113++;
            }
            if (is_array($xzv_149['nname'])) {
                foreach ($xzv_149['nname'] as $xzv_111 => $xzv_112) {
                    if ($xzv_112 && $xzv_60[$xzv_112]['cate'] != '') {
                        $this->error('已存在相同名称的采集点！');
                    }
                    if (!$xzv_112 || !$xzv_149['ncate'][$xzv_111]) {
                        unset($xzv_60[$xzv_112]);
                    } else {
                        $xzv_60[$xzv_112] = array('open' => $xzv_149['nopen'][$xzv_111], 'name' => $xzv_112, 'cate' => $xzv_149['ncate'][$xzv_111]);
                    }
                }
            }
            F('pick', $xzv_60);
            $this->success('参数更新成功！');
        } elseif ($xzv_117 == 'runpick') {
            $xzv_10 = I('param.id');
            $xzv_107 = I('get.nownum', '', 'intval');
            $xzv_74 = I('get.maxnum', '', 'intval');
            if ($xzv_107 > 0 && $xzv_107 >= $xzv_74) {
                $this->success('批量采集完毕！', U('pick'));
                die;
            }
            if ($xzv_107 > 0) {
                foreach ($xzv_60 as $xzv_59 => $xzv_50) {
                    if ($xzv_50['open'] == 'yes') {
                        $xzv_11[] = $xzv_50;
                    }
                }
                $xzv_147 = unique_array($xzv_11, 1, false);
                $xzv_147 = $xzv_147[0];
                $xzv_10 = $xzv_147['name'];
            }
            $xzv_76 = pickrun('list', $xzv_10, 'admin');
            $xzv_82['status'] = 'success_' . $xzv_76;
            if ($xzv_107) {
                $this->success('第' . $xzv_107 . '次采集: 节点' . $xzv_10 . '，页码' . $xzv_76 . '，即将进行下一次！', U('pick', array('action' => 'runpick', 'oid' => $xzv_10, 'nownum' => $xzv_107 + 1, 'maxnum' => $xzv_74)));
                die;
            } else {
                die(json_encode($xzv_82));
            }
        } elseif ($xzv_117 == 'batchpick') {
            $xzv_137 = I('get.oid', 0, 'intval');
            $xzv_114 = M('articles');
            $xzv_101 = "lastcid = 0 and id>'{$xzv_137}'";
            $xzv_32 = $xzv_114->where($xzv_101)->order('id asc')->find();
            $xzv_10 = $xzv_32['id'];
            if (!$xzv_10) {
                $this->success('更新完成！', U('pick'));
                die;
            }
            $xzv_136 = floor($xzv_10 / 1000);
            $xzv_109 = F('view/book/' . $xzv_136 . '/' . $xzv_10);
            if (!isset($xzv_109['content']) || !$xzv_109['thumb']) {
                $this->pickinfo($xzv_32);
            } else {
                $xzv_33 = F('pick');
                $xzv_26 = $xzv_33[$xzv_32['pid']];
                if ($xzv_109['thumb'] && $xzv_26['piclocal'] == 'yes' && substr($xzv_109['thumb'], 0, 9) != '/uploads/') {
                    $xzv_82['thumb'] = deimg($xzv_109['thumb'], $xzv_10, $xzv_109['url'], true, true);
                }
                $xzv_109['thumb'] = $xzv_82['thumb'] ? $xzv_82['thumb'] : ($xzv_109['thumb'] ? $xzv_109['thumb'] : '/Public/images/nocover.jpg');
                $xzv_114->where("id = '{$xzv_10}'")->save($xzv_82);
                F('view/book/' . $xzv_136 . '/' . $xzv_10, $xzv_109);
            }
            $this->success('ID为' . $xzv_10 . '的文章信息更新成功，即将更新下一篇！', U('pick', array('action' => 'batchpick', 'oid' => $xzv_10)));
        } elseif ($xzv_117 == 'multitoggle') {
            $xzv_126 = I('get.name');
            $xzv_84 = I('get.toname');
            if (!$xzv_126 || !$xzv_84) {
                $this->error('节点选择有误');
            }
            $xzv_137 = I('get.oid', 0, 'intval');
            $xzv_32 = $xzv_114->where("pid='%s' and id > %d", $xzv_126, $xzv_137)->order('id asc')->find();
            $xzv_10 = $xzv_32['id'];
            if (!$xzv_10) {
                $this->success('转换完成！', U('pick'));
                die;
            }
            $xzv_24 = M('article_pickers')->where("aid='%d' and pid='%s'", $xzv_10, $xzv_84)->find();
            if ($xzv_24['url']) {
                M('articles')->where('id=%d', $xzv_10)->setField(array('pid' => $xzv_84, 'url' => $xzv_24['url'], 'update' => 1));
                delhtml($xzv_10);
                $this->success('ID为' . $xzv_10 . '的文章转换成功，即将转换下一篇！', U('pick', array('action' => 'multitoggle', 'oid' => $xzv_10, 'name' => $xzv_126, 'toname' => $xzv_84)));
            } else {
                $this->success('ID为' . $xzv_10 . '的文章无对应节点记录，已忽略，您可稍后转换至其他节点！', U('pick', array('action' => 'multitoggle', 'oid' => $xzv_10, 'name' => $xzv_126, 'toname' => $xzv_84)));
            }
        } elseif ($xzv_117 == 'singletoggle') {
            $xzv_10 = I('get.id', 0, 'intval');
            if (!$xzv_10) {
                $this->error('ID有误');
            }
            $xzv_84 = I('get.toname');
            $xzv_24 = M('article_pickers')->where("aid='%d' and pid='%s'", $xzv_10, $xzv_84)->find();
            if ($xzv_24['url']) {
                M('articles')->where('id=%d', $xzv_10)->setField(array('pid' => $xzv_84, 'url' => $xzv_24['url'], 'update' => 1));
                $this->success('转换成功！', U('article'));
            } else {
                $this->error('该节点无此小说记录，请更换其他节点');
            }
        } elseif ($xzv_117 == 'signpick') {
            $xzv_105 = I('post.sign_pid');
            $xzv_115 = I('post.sign_url');
            $xzv_49 = I('post.sign_ids');
            $xzv_121 = I('post.sign_cate');
            if (!$xzv_49) {
                $xzv_135 = F('signpick');
                $xzv_13 = I('get.maxcount');
                $xzv_123 = I('get.nowindex');
                $xzv_141 = $xzv_135['ids'][$xzv_123];
                if (!$xzv_141) {
                    F('signpick', null);
                    $this->success('采集完毕！', U('pick'));
                    die;
                }
                $xzv_136 = floor($xzv_141 / 1000);
                $xzv_25 = str_replace(array('[id]', '[subid]'), array($xzv_141, $xzv_136), $xzv_135['url']);
                $xzv_28 = $this->category[$xzv_135['cate']]['dir'];
                if (!M('articles')->where("url='%s'", $xzv_25)->find()) {
                    $xzv_82 = array('title' => '[title]', 'url' => $xzv_25, 'cate' => $xzv_135['cate'], 'pid' => $xzv_135['pid'], 'posttime' => NOW_TIME, 'updatetime' => NOW_TIME);
                    $xzv_82['id'] = M('articles')->add($xzv_82);
                    $this->pickinfo($xzv_82, 'signpick');
                    $xzv_72 = '节点:' . $xzv_135['pid'] . '，小说ID:' . $xzv_141 . '采集完毕';
                } else {
                    $xzv_72 = '节点:' . $xzv_135['pid'] . '，小说ID:' . $xzv_141 . '已存在，跳过采集';
                }
                $this->success($xzv_72 . '，转入下一本采集', U('pick', array('action' => 'signpick', 'nowindex' => $xzv_123 + 1, 'maxcount' => $xzv_13)));
            } else {
                if (!$xzv_105 || !$xzv_60[$xzv_105]) {
                    $this->error('节点ID有误，请重新尝试！');
                }
                $xzv_135['pid'] = $xzv_105;
                if (!$xzv_115) {
                    $this->error('URL有误，请重新尝试！');
                }
                if (!strexists($xzv_115, $xzv_60[$xzv_105]['domain'])) {
                    $this->error('URL必须包含指定节点的域名！');
                }
                if (strexists($xzv_49, '-')) {
                    list($xzv_125, $xzv_71) = explode('-', $xzv_49);
                    if ($xzv_125 >= $xzv_71 || $xzv_71 - $xzv_125 > 5000) {
                        $this->error('文章ID有误，请重新尝试！每次批量采集数量不能超过5000');
                    }
                    for ($xzv_113 = $xzv_125; $xzv_113 <= $xzv_71; $xzv_113++) {
                        $xzv_135['ids'][] = $xzv_113;
                    }
                } elseif (strexists($xzv_49, ',')) {
                    $xzv_135['ids'] = explode(',', $xzv_49);
                } else {
                    $xzv_135['ids'][0] = $xzv_49;
                }
                $xzv_135['url'] = $xzv_115;
                $xzv_135['cate'] = $xzv_121;
                F('signpick', $xzv_135);
                $xzv_13 = count($xzv_135['ids']);
                $this->success('初始化完毕，开始采集！', U('pick', array('action' => 'signpick', 'nowindex' => 0, 'maxcount' => $xzv_13)));
            }
        } elseif ($xzv_117 == 'picker_list') {
            $xzv_10 = I('get.id', '', 'intval');
            if (!$xzv_10) {
                $xzv_76['status'] = 'error';
                $this->ajaxReturn($xzv_76);
            }
            $xzv_76['status'] = 'success';
            $xzv_76['picker_list'] = M('article_pickers')->where('aid=%d', $xzv_10)->select();
            $this->ajaxReturn($xzv_76);
        } else {
            $this->assign('pick', $xzv_60);
            $this->assign('category', $xzv_78);
            $this->display();
        }
    }
    public function article()
    {
        $xzv_127 = M('articles');
        $xzv_142 = I('param.action');
        $xzv_66 = F('pick');
        if (!$xzv_142) {
            $xzv_122 = 50;
            $xzv_31 = I('get.p', 1, 'intval');
            $xzv_148 = I('get.cate');
            $xzv_83 = I('get.picker');
            $xzv_48 = $xzv_148 == 'default' ? $this->defaultdir : $xzv_148;
            $xzv_70 = I('param.q');
            $_GET['q'] = $xzv_70;
            $xzv_119 = I('get.order', 'id');
            if (!in_array($xzv_119, array('id', 'views', 'monthviews', 'weekviews', 'posttime', 'updatetime', 'full', 'original', 'push'))) {
                $this->error('请选择正确排序！');
            }
            foreach ($this->category as $xzv_44 => $xzv_75) {
                $xzv_34 .= "'" . $xzv_75['dir'] . "',";
            }
            if ($xzv_148) {
                if ($xzv_148 == 'nocover') {
                    $xzv_120 = "a.thumb like '%nocover%'";
                } elseif ($xzv_148 == 'nocate') {
                    $xzv_120 = 'a.cate not in (' . $xzv_34 . "'default')";
                } else {
                    $xzv_120 = "a.cate='{$xzv_48}'";
                }
            }
            if ($xzv_83) {
                $xzv_120 = "a.pid='{$xzv_83}'";
            }
            if (strexists($xzv_119, 'views')) {
                if ($xzv_119 == 'weekviews') {
                    $xzv_12 = date('W', NOW_TIME);
                    $xzv_120 .= ($xzv_120 ? 'and ' : '') . " av.weekkey='{$xzv_12}'";
                } elseif ($xzv_119 == 'monthviews') {
                    $xzv_132 = date('n', NOW_TIME);
                    $xzv_120 .= ($xzv_120 ? 'and ' : '') . " av.monthkey='{$xzv_132}'";
                }
                $xzv_7 = 'av.' . $xzv_119 . ' desc';
            } else {
                $xzv_7 = 'a.' . $xzv_119 . ' desc';
            }
            if ($xzv_70) {
                $xzv_120 .= ($xzv_120 ? 'and ' : '') . "(a.title like '%{$xzv_70}%' OR a.author like '%{$xzv_70}%')";
            }
            $xzv_80 = $xzv_127->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_120)->order($xzv_7)->limit(($xzv_31 - 1) * $xzv_122, $xzv_122)->field('a.*,av.views,av.monthviews,av.weekviews')->select();
            foreach ($xzv_80 as $xzv_44 => $xzv_75) {
                $xzv_45 = $xzv_75['cate'] == $this->defaultdir ? 'default' : $xzv_75['cate'];
                $xzv_80[$xzv_44]['catename'] = $this->category[$xzv_45]['name'];
                $xzv_80[$xzv_44]['posttime'] = date('Y-m-d H:i', $xzv_80[$xzv_44]['posttime']);
                $xzv_80[$xzv_44]['views'] = intval($xzv_75['views']);
                $xzv_80[$xzv_44]['monthviews'] = intval($xzv_75['monthviews']);
                $xzv_80[$xzv_44]['weekviews'] = intval($xzv_75['weekviews']);
                unset($xzv_45);
            }
            $xzv_46 = $xzv_127->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_120)->Count();
            $xzv_146 = pagelist_thinkphp($xzv_46, $xzv_122);
            $this->assign('articlelist', $xzv_80);
            $this->assign('cate', $xzv_148);
            $this->assign('picker', $xzv_83);
            $this->assign('q', $xzv_70);
            $this->assign('pagehtml', $xzv_146);
            $this->assign('orderby', $xzv_119);
        } elseif ($xzv_142 == 'edit' || $xzv_142 == 'add') {
            $xzv_67 = I('param.id', '', 'intval');
            if (I('post.do') != 'save') {
                if ($xzv_142 == 'edit') {
                    if (!$xzv_67) {
                        $this->error('数据读取出错！');
                    }
                    $xzv_47 = $xzv_127->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("id='{$xzv_67}'")->find();
                    $xzv_144 = floor($xzv_67 / 1000);
                    $xzv_145 = F('view/book/' . $xzv_144 . '/' . $xzv_67);
                    $xzv_47['description'] = $xzv_145['description'];
                    $xzv_47['content'] = $xzv_145['content'];
                    $xzv_47['seotitle'] = $xzv_145['seotitle'];
                    $xzv_47['seokeyword'] = $xzv_145['seokeyword'];
                    $xzv_47['seodescription'] = $xzv_145['seodescription'];
                } else {
                    $xzv_47 = null;
                }
                $this->assign('articledb', $xzv_47);
            } else {
                if (!I('post.title', '', 'htmlspecialchars')) {
                    $this->error('文章标题不能为空！');
                }
                $xzv_65 = I('post.pid');
                $xzv_148 = I('post.cate', '', 'htmlspecialchars');
                $xzv_42 = I('post.thumb', '', 'htmlspecialchars');
                $xzv_98 = array('title' => I('post.title', '', 'htmlspecialchars'), 'cate' => $xzv_148, 'thumb' => $xzv_42, 'pid' => $xzv_65, 'full' => I('post.full', '', 'intval'), 'update' => I('post.update', '', 'intval'), 'original' => I('post.original', '', 'intval'), 'posttime' => NOW_TIME, 'updatetime' => NOW_TIME);
                $xzv_41 = array('views' => I('post.views', '', 'intval'), 'weekviews' => I('post.views', '', 'intval'), 'monthviews' => I('post.views', '', 'intval'));
                if ($xzv_142 == 'add') {
                    $xzv_98['author'] = I('post.author', '', 'htmlspecialchars');
                    $xzv_98['url'] = I('post.url');
                    $xzv_64 = $xzv_127->where("url = '{$xzv_98['url']}'")->find();
                    if (!$xzv_64['id']) {
                        $xzv_61 = $xzv_66[$xzv_65];
                        $xzv_91 = $xzv_127->where("title = '{$xzv_98['title']}'")->find();
                        if ($xzv_91['id'] && $xzv_91['author'] == $xzv_98['author']) {
                            $this->error('已存在同名同作者的文章！');
                        }
                        $xzv_81 = $xzv_61['piclocal'] == 'yes' ? true : false;
                        $xzv_67 = $xzv_127->add($xzv_98);
                        $xzv_73['thumb'] = deimg($xzv_42, $xzv_67, $xzv_98['url'], true, $xzv_81);
                        $xzv_127->where("id='{$xzv_67}'")->save($xzv_73);
                        $xzv_41['aid'] = $xzv_67;
                        if ($xzv_41['views'] > 0) {
                            M('article_views')->add($xzv_41);
                        }
                        if ($xzv_98['original'] == 1) {
                            pushapi($xzv_67);
                        }
                        $this->success('文章添加成功！', U('article'));
                    } else {
                        $this->error('文章已存在，请勿重复添加！如是原创文章，请填写任意不可能重复的url');
                    }
                } else {
                    $xzv_37 = $xzv_127->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("id='{$xzv_67}'")->find();
                    $xzv_98['url'] = I('post.url');
                    $xzv_98['author'] = I('post.author', '', 'htmlspecialchars');
                    $xzv_98['info'] = mb_substr(cleanHtml(I('post.description', '', 'htmlspecialchars')), 0, 120, 'utf-8');
                    $xzv_61 = $xzv_66[$xzv_65];
                    $xzv_81 = $xzv_61['piclocal'] == 'yes' ? true : false;
                    if (strexists($xzv_42, '://') && $xzv_81) {
                        $xzv_98['thumb'] = deimg($xzv_42, $xzv_67, $xzv_98['url'], true, $xzv_81);
                    }
                    $xzv_127->where("id='{$xzv_67}'")->save($xzv_98);
                    if (!M('article_views')->where("aid='{$xzv_67}'")->find()) {
                        $xzv_41['aid'] = $xzv_67;
                        M('article_views')->add($xzv_41);
                    } else {
                        M('article_views')->where("aid='{$xzv_67}'")->save($xzv_41);
                    }
                    $xzv_144 = floor($xzv_67 / 1000);
                    $xzv_145 = F('view/book/' . $xzv_144 . '/' . $xzv_67);
                    if ($xzv_37['url'] != $xzv_98['url']) {
                        S('chaptercache_' . $xzv_67, null);
                        F('view/book/' . $xzv_144 . '/' . $xzv_67, null);
                        F('view/chapter/' . $xzv_144 . '/' . $xzv_67, null);
                        F('view/newchapter/' . $xzv_144 . '/' . $xzv_67, null);
                    } else {
                        $xzv_47 = array_merge($xzv_145, $xzv_98);
                        if (!I('post.description', '', 'htmlspecialchars')) {
                            $xzv_47['description'] = mb_substr(cleanHtml(I('post.content', '', 'htmlspecialchars_decode')), 0, 120, 'utf-8');
                        } else {
                            $xzv_47['description'] = mb_substr(cleanHtml(I('post.description', '', 'htmlspecialchars')), 0, 120, 'utf-8');
                        }
                        $xzv_47['content'] = I('post.content', '', 'htmlspecialchars_decode');
                        if ($xzv_145['chapterurl'] == $xzv_145['url'] || !$xzv_145['chapterurl']) {
                            $xzv_47['chapterurl'] = $xzv_98['url'];
                        }
                        $xzv_47['title'] = $xzv_98['title'];
                        $xzv_47['url'] = $xzv_98['url'];
                        $xzv_47['full'] = $xzv_98['full'];
                        $xzv_47['original'] = $xzv_98['original'];
                        $xzv_47['cate'] = $xzv_148;
                        $xzv_47['catename'] = $xzv_148 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_148]['name'];
                        $xzv_47['time'] = date('Y-m-d H:i:s', NOW_TIME);
                        $xzv_47['views'] = I('post.views', 1, 'intval');
                        $xzv_47['author'] = $xzv_98['author'];
                        $xzv_144 = floor($xzv_67 / 1000);
                        $xzv_47['thumb'] = showcover($xzv_98['thumb']);
                        if (strlen(I('post.seotitle')) > 0) {
                            $xzv_47['seotitle'] = I('post.seotitle');
                            $xzv_47['seokeyword'] = I('post.seokeyword');
                            $xzv_47['seodescription'] = I('post.seodescription');
                        }
                        F('view/book/' . $xzv_144 . '/' . $xzv_67, $xzv_47);
                    }
                    delhtml($xzv_67);
                    $this->success('文章更新成功！', U('article'));
                }
                die;
            }
        } elseif ($xzv_142 == 'del') {
            $xzv_67 = I('get.id', '', 'intval');
            !$xzv_67 && $this->error('出错啦！');
            $xzv_127->where("id='{$xzv_67}'")->delete();
            $xzv_144 = floor($xzv_67 / 1000);
            delhtml($xzv_67);
            F('view/book/' . $xzv_144 . '/' . $xzv_67, null);
            F('view/chapter/' . $xzv_144 . '/' . $xzv_67, null);
            F('view/newchapter/' . $xzv_144 . '/' . $xzv_67, null);
            S('chaptercache_' . $xzv_67, null);
            $xzv_0 = DATA_PATH . 'view/chaptercont/' . $xzv_144 . '/' . $xzv_67;
            clearfile($xzv_0);
            $this->success('文章删除成功！');
            die;
        } elseif ($xzv_142 == 'settype') {
            $xzv_67 = I('get.id', '', 'intval');
            !$xzv_67 && $this->error('出错啦！');
            $xzv_89 = I('get.type');
            $xzv_144 = floor($xzv_67 / 1000);
            $xzv_3 = $xzv_127->where("id='{$xzv_67}'")->find();
            if (!$xzv_3) {
                $this->error('小说不存在！');
            }
            switch ($xzv_89) {
                case 'cfull':
                    if ($xzv_3['full'] == 1) {
                        $xzv_98['full'] = 0;
                        $xzv_5 = '文章已调整为连载';
                    } else {
                        $xzv_98['full'] = 1;
                        $xzv_5 = '文章已调整为完结';
                    }
                    $xzv_127->where("id='{$xzv_67}'")->save($xzv_98);
                    $xzv_47 = F('view/book/' . $xzv_144 . '/' . $xzv_67);
                    $xzv_47['full'] = $xzv_98['full'];
                    F('view/book/' . $xzv_144 . '/' . $xzv_67, $xzv_47);
                    break;
                case 'update':
                    if ($xzv_3['update'] == 1) {
                        $xzv_98['update'] = 0;
                        $xzv_5 = '文章将在下次阅读时强制重新采集';
                    } else {
                        $xzv_98['update'] = 1;
                        $xzv_5 = '文章已取消强制采集';
                    }
                    $xzv_127->where("id='{$xzv_67}'")->save($xzv_98);
                    break;
                case 'original':
                    if ($xzv_3['original'] == 1) {
                        $xzv_98['original'] = 0;
                        $xzv_5 = '文章已调整为采集类型，将自动采集更新';
                    } else {
                        $xzv_98['original'] = 1;
                        $xzv_5 = '文章已调整为原创类型，将不再自动更新';
                    }
                    $xzv_127->where("id='{$xzv_67}'")->save($xzv_98);
                    break;
                default:
                    break;
            }
            $this->success($xzv_5);
            die;
        } elseif ($xzv_142 == 'multi-delete') {
            $xzv_77 = implode(',', I('post.ids'));
            $xzv_92['id'] = array('in', $xzv_77);
            $xzv_127->where($xzv_92)->delete();
            foreach (I('post.ids') as $xzv_67) {
                $xzv_144 = floor($xzv_67 / 1000);
                delhtml($xzv_67);
                F('view/book/' . $xzv_144 . '/' . $xzv_67, null);
                F('view/chapter/' . $xzv_144 . '/' . $xzv_67, null);
                F('view/newchapter/' . $xzv_144 . '/' . $xzv_67, null);
                S('chaptercache_' . $xzv_67, null);
                $xzv_0 = DATA_PATH . 'view/chaptercont/' . $xzv_144 . '/' . $xzv_67;
                clearfile($xzv_0);
            }
            $this->success('文章已删除！');
            die;
        } elseif ($xzv_142 == 'multi-move') {
            $xzv_77 = implode(',', I('post.ids'));
            $xzv_4 = I('post.newfid');
            if (!$xzv_4) {
                $this->error('请选择正确的栏目');
            }
            foreach (I('post.ids') as $xzv_67) {
                $xzv_144 = floor($xzv_67 / 1000);
                $xzv_47 = F('view/book/' . $xzv_144 . '/' . $xzv_67);
                if ($xzv_47) {
                    $xzv_47['cate'] = $xzv_4;
                    $xzv_47['catename'] = $this->category[$xzv_4]['name'];
                    F('view/book/' . $xzv_144 . '/' . $xzv_67, $xzv_47);
                }
            }
            $xzv_92['id'] = array('in', $xzv_77);
            $xzv_127->where($xzv_92)->setField('cate', $xzv_4);
            $this->success('文章移动成功！');
            die;
        } elseif ($xzv_142 == 'push') {
            $xzv_67 = I('get.id');
            !$xzv_67 && $this->error('出错啦！');
            if (!$this->setting['seo']['pushapi']) {
                $this->error('请填写百度主动推送API');
            }
            $xzv_14 = pushapi($xzv_67, true);
            if ($xzv_14['status']) {
                $this->success('文章推送成功！');
                die;
            } else {
                $this->error('文章推送失败！' . json_encode($xzv_14['info']));
                die;
            }
        }
        $this->assign('action', $xzv_142);
        $this->assign('category', $this->category);
        $this->assign('cate', $xzv_148);
        $this->assign('pick', $xzv_66);
        $this->display();
    }
    protected function pickinfo($xzv_43, $xzv_94 = 'batchpick')
    {
        $xzv_150 = $xzv_43;
        $xzv_39 = $xzv_150['id'];
        $xzv_116 = floor($xzv_39 / 1000);
        if ($xzv_150['original'] == 1) {
            return;
        }
        $xzv_129 = $xzv_150['cate'];
        $xzv_40 = new \Org\Util\Pick();
        $xzv_20 = $xzv_40->pickcont($xzv_150['url'], $xzv_150['pid']);
        if ($xzv_94 == 'signpick') {
            if (!$xzv_20['title']) {
                M('articles')->delete($xzv_39);
                return;
            } else {
                $xzv_38['title'] = $xzv_20['title'];
            }
        }
        $xzv_35 = F('pick');
        $xzv_17 = $xzv_35[$xzv_150['pid']];
        if ($xzv_20['thumb']) {
            if (strexists($xzv_20['thumb'], $xzv_17['nothumb_sign']) || !$xzv_20['thumb'] && !$xzv_150['thumb']) {
                $xzv_38['thumb'] = '/Public/images/nocover.jpg';
            } else {
                $xzv_16 = $xzv_17['piclocal'] == 'yes' ? true : false;
                $xzv_38['thumb'] = deimg($xzv_20['thumb'], $xzv_39, $xzv_150['url'], true, $xzv_16);
            }
        }
        if ($xzv_20['cate']) {
            $xzv_38['cate'] = $xzv_129 = $xzv_20['cate'];
        }
        $xzv_150['title'] = $xzv_20['title'];
        $xzv_150['content'] = $xzv_20['content'];
        $xzv_38['full'] = $xzv_150['full'] = $xzv_20['isfull'] == 'full' ? 1 : 0;
        $xzv_38['info'] = $xzv_150['description'] = mb_substr(cleanHtml($xzv_150['content']), 0, 120, 'utf-8');
        $xzv_150['keyword'] = $xzv_20['keyword'];
        $xzv_150['cate'] = $xzv_129;
        $xzv_150['catename'] = $xzv_129 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_129]['name'];
        $xzv_150['time'] = date('Y-m-d H:i:s', $xzv_150['updatetime'] ? $xzv_150['updatetime'] : NOW_TIME);
        if ($xzv_20['author']) {
            $xzv_38['author'] = $xzv_150['author'] = $xzv_20['author'];
        }
        if ($xzv_20['thumb']) {
            if (strexists($xzv_20['thumb'], $xzv_17['nothumb_sign']) || !$xzv_20['thumb'] && !$xzv_150['thumb']) {
                $xzv_38['thumb'] = '/Public/images/nocover.jpg';
            } else {
                $xzv_16 = $xzv_17['piclocal'] == 'yes' ? true : false;
                $xzv_38['thumb'] = deimg($xzv_20['thumb'], $xzv_39, $xzv_150['url'], true, $xzv_16);
            }
        }
        if ($this->setting['seo']['piclocal_type'] == 'tocdn') {
            if ($xzv_150['thumb'] && $xzv_17['piclocal'] == 'yes' && strexists($xzv_150['thumb'], '://')) {
                $xzv_38['thumb'] = deimg($xzv_150['thumb'], $xzv_39, $xzv_150['url'], true, true);
            }
        } else {
            if ($xzv_150['thumb'] && $xzv_17['piclocal'] == 'yes' && substr($xzv_150['thumb'], 0, 9) != '/uploads/') {
                $xzv_38['thumb'] = deimg($xzv_150['thumb'], $xzv_39, $xzv_150['url'], true, true);
            }
        }
        $xzv_150['thumb'] = $xzv_38['thumb'] ? $xzv_38['thumb'] : ($xzv_150['thumb'] ? $xzv_150['thumb'] : '/Public/images/nocover.jpg');
        $xzv_150['chapterurl'] = $xzv_20['chapterurl'];
        if ($xzv_20['chapterdb']) {
            $xzv_100 = $xzv_20['chapterdb'];
        } else {
            $xzv_100 = $xzv_40->pickchapter($xzv_150['chapterurl'], $xzv_150['pid']);
        }
        $xzv_19 = $xzv_100['chapterlist'];
        if ($xzv_100) {
            $xzv_38['lastchapter'] = $xzv_150['lastchapter'] = $xzv_100['lastchapter']['title'];
            $xzv_38['lastcid'] = $xzv_150['lastcid'] = $xzv_100['lastchapter']['cid'];
            foreach ($xzv_19 as $xzv_58 => $xzv_124) {
                $xzv_19[$xzv_58]['id'] = $xzv_39;
                $xzv_19[$xzv_58]['cid'] = $xzv_58;
                $xzv_19[$xzv_58]['cate'] = $xzv_150['cate'];
                $xzv_19[$xzv_58]['title'] = trim($xzv_124['title']);
            }
            $xzv_63 = 0;
            foreach (array_reverse($xzv_19, true) as $xzv_58 => $xzv_124) {
                $xzv_104[$xzv_63] = $xzv_124;
                $xzv_63++;
                if ($xzv_63 > 11) {
                    break;
                }
            }
            F('view/chapter/' . $xzv_116 . '/' . $xzv_39, $xzv_19);
            F('view/newchapter/' . $xzv_116 . '/' . $xzv_39, $xzv_104);
            S('chaptercache_' . $xzv_39, NOW_TIME, array('temp' => TEMP_PATH . 'chaptercache/' . $xzv_116 . '/', 'expire' => $this->setting['extra']['chaptercachetime']));
        }
        if ($xzv_38['lastchapter']) {
            $xzv_150['updatetime'] = $xzv_38['updatetime'] = NOW_TIME;
            $xzv_150['time'] = date('Y-m-d H:i:s', NOW_TIME);
        }
        M('articles')->where("id = '{$xzv_39}'")->save($xzv_38);
        $xzv_27 = M('article_views')->where('aid = %d', $xzv_39)->find();
        list($xzv_22, $xzv_131) = explode(',', $this->setting['seo']['virtviews']);
        if (!$xzv_22 || !$xzv_131 || $xzv_131 < $xzv_22) {
            $xzv_22 = 1;
            $xzv_131 = 3;
        }
        $xzv_21 = mt_rand($xzv_22, $xzv_131);
        if (!$xzv_27['aid']) {
            $xzv_150['weekviews'] = $xzv_150['monthviews'] = $xzv_150['views'] = $xzv_21;
            $xzv_110 = array('aid' => $xzv_39, 'weekviews' => $xzv_21, 'monthviews' => $xzv_21, 'views' => $xzv_21, 'weekkey' => date('W', NOW_TIME), 'monthkey' => date('n', NOW_TIME));
            M('article_views')->add($xzv_110);
        }
        F('view/book/' . $xzv_116 . '/' . $xzv_39, $xzv_150);
        pushapi($xzv_39);
    }
}