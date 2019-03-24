-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-02-24 13:18:00
-- 服务器版本： 5.5.27
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库: `book`
--

-- --------------------------------------------------------

--
-- 表的结构 `yg_articles`
--

CREATE TABLE `yg_articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `pinyin` varchar(50) DEFAULT NULL COMMENT '拼音',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `url` varchar(255) NOT NULL COMMENT '源',
  `info` varchar(500) DEFAULT NULL COMMENT '简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '封面/缩略图',
  `posttime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发表时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `cate` varchar(50) DEFAULT NULL COMMENT '分类',
  `pid` varchar(20) NOT NULL COMMENT '采集节点',
  `tags` varchar(255) DEFAULT NULL COMMENT '标签相关',
  `lastchapter` varchar(255) DEFAULT NULL COMMENT '最新章节',
  `lastcid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最新章节ID',
  `full` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否完本',
  `update` tinyint( 1 ) NOT NULL DEFAULT  '0' COMMENT '强制更新',
  `push` tinyint( 1 ) NOT NULL DEFAULT  '0' COMMENT '推送标记',
  `original` tinyint( 1 ) NOT NULL DEFAULT  '0' COMMENT '原创'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_article_views`
--

CREATE TABLE `yg_article_views` (
  `aid` int(10) UNSIGNED NOT NULL COMMENT '文章ID',
  `weekviews` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '周点击',
  `monthviews` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '月点击',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '总点击',
  `weekkey` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前周数',
  `monthkey` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前月数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_searchlog`
--

CREATE TABLE `yg_searchlog` (
  `id` int(11) UNSIGNED NOT NULL,
  `searchword` varchar(50) DEFAULT NULL,
  `num` mediumint(5) NOT NULL DEFAULT '1',
  `hasresult` tinyint(1) DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_settingmeta`
--

CREATE TABLE `yg_settingmeta` (
  `id` int(10) UNSIGNED NOT NULL,
  `meta_key` varchar(20) NOT NULL,
  `meta_value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_spiderlogs`
--

CREATE TABLE `yg_spiderlogs` (
  `id` int(10) UNSIGNED NOT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `httpurl` varchar(255) NOT NULL,
  `spider` varchar(10) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `dateline` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_tagdatas`
--

CREATE TABLE `yg_tagdatas` (
  `Id` int(11) NOT NULL,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '标签id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `yg_tags`
--

CREATE TABLE `yg_tags` (
  `Id` int(11) NOT NULL,
  `tagname` varchar(255) NOT NULL DEFAULT '',
  `ename` varchar(255) NOT NULL DEFAULT '',
  `num` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

CREATE TABLE `yg_seowords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `ename` varchar(20) NOT NULL COMMENT '索引词',
  `sitename` varchar(100) NOT NULL COMMENT '站名',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `keywords` varchar(100) NOT NULL COMMENT '关键词',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `views` int(10) NOT NULL DEFAULT '1' COMMENT '点击',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ename` (`ename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `yg_article_pickers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `aid` int(10) unsigned NOT NULL COMMENT '小说ID',
  `pid` varchar(50) NOT NULL COMMENT '节点id',
  `url` varchar(200) NOT NULL COMMENT '源网址',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Indexes for table `yg_articles`
--
ALTER TABLE `yg_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updatetime` (`updatetime`),
  ADD KEY `url` (`url`),
  ADD KEY `posttime` (`posttime`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `yg_article_views`
--
ALTER TABLE `yg_article_views`
  ADD UNIQUE KEY `aid` (`aid`),
  ADD KEY `weekviews` (`weekviews`),
  ADD KEY `monthviews` (`monthviews`),
  ADD KEY `views` (`views`);

--
-- Indexes for table `yg_searchlog`
--
ALTER TABLE `yg_searchlog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `searchword` (`searchword`);

--
-- Indexes for table `yg_settingmeta`
--
ALTER TABLE `yg_settingmeta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meta_key` (`meta_key`) USING BTREE;

--
-- Indexes for table `yg_spiderlogs`
--
ALTER TABLE `yg_spiderlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yg_tagdatas`
--
ALTER TABLE `yg_tagdatas`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `yg_tags`
--
ALTER TABLE `yg_tags`
  ADD PRIMARY KEY (`Id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `yg_articles`
--
ALTER TABLE `yg_articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_searchlog`
--
ALTER TABLE `yg_searchlog`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_settingmeta`
--
ALTER TABLE `yg_settingmeta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_spiderlogs`
--
ALTER TABLE `yg_spiderlogs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_tagdatas`
--
ALTER TABLE `yg_tagdatas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_tags`
--
ALTER TABLE `yg_tags`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `yg_settingmeta` (`id`, `meta_key`, `meta_value`) VALUES
(1, 'adminname', 'admin'),
(2, 'adminpwd', '2f297a57a5a743894a'),
(3, 'spider_day', '0'),
(6, 'spider_lastday', '0'),
(4, 'spider_uptime', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;