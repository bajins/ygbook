<?php
if (strtoupper(MODULE_NAME) == 'HOME') {
    $catedb = F('category');
    $setting = F('setting');
    $maindomain = C('DATADOMAIN');
    if ($maindomain != 'default' && $maindomain != 'wap') {
        $redomain = C('PCDOMAIN');
    } else {
        $redomain = 'default';
    }
    $tmpdb = F('dataarea/' . $maindomain);
    if (count($tmpdb) <= 0) {
        die;
    }
    $article = M('articles');
    $defaultdir = $catedb['default']['dir'];
    foreach ($tmpdb as $k1 => $v1) {
        if ($v1['open'] != 'yes') {
            continue;
        }
        $cachename = 'dataarea_' . $v1['did'];
        $haslist = S($maindomain . '_' . $cachename, '', array('temp' => TEMP_PATH . 'dataarea/'));
        if (!$haslist) {
            $orderby = in_array(strtolower($v1['orderby']), array('id', 'views', 'weekviews', 'monthviews', 'posttime', 'updatetime')) ? strtolower($v1['orderby']) : 'id';
            $orderway = in_array(strtolower($v1['orderway']), array('desc', 'asc')) ? strtolower($v1['orderway']) : 'desc';
            $hasthumb = in_array(strtolower($v1['hasthumb']), array('yes', 'no')) ? strtolower($v1['hasthumb']) : 'no';
            $hasinfo = in_array(strtolower($v1['hasinfo']), array('yes', 'no')) ? strtolower($v1['hasinfo']) : 'no';
            $isfull = in_array(strtolower($v1['isfull']), array('yes', 'no')) ? strtolower($v1['isfull']) : 'no';
            $limit = intval($v1['limit']) ? intval($v1['limit']) : 10;
            $infolen = intval($v1['infolen']) ? intval($v1['infolen']) : 40;
            $expirehour = intval($v1['expirehour']) ? intval($v1['expirehour']) : 40;
            $dateformat = $v1['dateformat'] ? $v1['dateformat'] : 'Y-m-d H:i:s';
            if ($v1['ids']) {
                $ids = explode(',', $v1['ids']);
                $issingle = count($ids) == 1 ? true : false;
                foreach ($ids as $v3) {
                    $nids .= ($nids ? ',' : '') . "'" . $v3 . "'";
                }
                $where = "a.id in({$nids})";
            } else {
                $v1['cate'] = $v1['cate'] == 'default' ? $defaultdir : $v1['cate'];
                $cate = $v1['cate'] ? $v1['cate'] : 'all';
                $where = $cate != 'all' ? "a.cate='{$cate}'" : '1';
                if ($hasthumb == 'yes') {
                    $where .= ' and a.thumb is not null';
                }
                if ($hasinfo == 'yes') {
                    $where .= ' and a.info is not null';
                }
                if ($isfull == 'yes') {
                    $where .= ' and a.full=1';
                }
            }
            if (strexists($orderby, 'views')) {
                if ($orderby == 'weekviews') {
                    $weekkey = date('W', NOW_TIME);
                    $where .= " and av.weekkey='{$weekkey}'";
                } elseif ($orderby == 'monthviews') {
                    $monthkey = date('m', NOW_TIME);
                    $prev_monthkey = $monthkey - 1;
                    $where .= " and av.monthkey='{$monthkey}'";
                }
                $orderby = 'av.' . $orderby;
            } else {
                $orderby = 'a.' . $orderby;
            }
            $order = $orderby . ' ' . $orderway;
            $nowlist = $article->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->field('a.id,a.title,a.pinyin,a.thumb,a.cate,a.info,a.posttime,a.updatetime,a.lastchapter,a.lastcid,a.author,a.full,av.views,av.weekviews,av.monthviews')->where($where)->order($order)->limit($limit)->select();
            foreach ($nowlist as $k2 => $v2) {
                $v2['subid'] = floor($v2['id'] / 1000);
                $nowlist_new[$k2]['title'] = $v2['title'];
                $nowlist_new[$k2]['rewriteurl'] = reurl('view', $v2, $redomain);
                $nowlist_new[$k2]['cateurl'] = reurl('cate', $v2['cate'], $redomain);
                if ($v2['cate'] == $defaultdir || !$v2['cate']) {
                    $v2['cates'] = 'default';
                } else {
                    $v2['cates'] = $v2['cate'];
                }
                $nowlist_new[$k2]['description'] = $v2['info'] ? mb_substr($v2['info'], 0, $infolen, 'utf-8') : $v2['title'];
                $nowlist_new[$k2]['catename'] = $catedb[$v2['cates']]['name'];
                $nowlist_new[$k2]['catename_short'] = mb_substr($nowlist_new[$k2]['catename'], 0, 2, 'utf-8');
                $nowlist_new[$k2]['thumb'] = showcover($v2['thumb']);
                $nowlist_new[$k2]['posttime'] = date($v2['posttime'], $dateformat);
                $nowlist_new[$k2]['updatetime'] = date($v2['updatetime'], $dateformat);
                $nowlist_new[$k2]['lastchapter'] = $v2['lastchapter'] ? $v2['lastchapter'] : '最新一章';
                $nowlist_new[$k2]['lastchapterurl'] = $v2['lastcid'] ? reurl('chapter', array('id' => $v2['id'], 'cate' => $v2['cate'], 'cid' => $v2['lastcid'], 'pinyin' => $v2['pinyin']), $redomain) : $nowlist_new[$k2]['rewriteurl'];
                $nowlist_new[$k2]['author'] = $v2['author'];
                $nowlist_new[$k2]['views'] = intval($v2['views']);
                $nowlist_new[$k2]['weekviews'] = intval($v2['weekviews']);
                $nowlist_new[$k2]['monthviews'] = intval($v2['monthviews']);
                $nowlist_new[$k2]['status'] = $v2['full'] > 0 ? '完成' : '连载';
                if ($issingle) {
                    $nowlist_new[$k2]['articledb'] = F('view/book/' . $v2['subid'] . '/' . $v2['id']);
                    $nowlist_new[$k2]['newchapter'] = F('view/newchapter/' . $v2['subid'] . '/' . $v2['id']);
                    break;
                }
                unset($k2);
                unset($v2);
            }
            F('dataarea/' . $maindomain . '/' . $cachename, $nowlist_new);
            S($maindomain . '_' . $cachename, NOW_TIME, array('temp' => TEMP_PATH . 'dataarea/', 'expire' => $expirehour * 3600));
        }
        unset($nowlist_new);
        unset($k1);
        unset($v1);
        unset($issingle);
        unset($ids);
        unset($nids);
    }
    unset($cachename);
    cronlog('区块更新任务');
}