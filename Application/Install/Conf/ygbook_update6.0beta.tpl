ALTER TABLE  `yg_articles` ADD  `update` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT '强制更新';
ALTER TABLE  `yg_articles` ADD  `push` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT '推送标记';
ALTER TABLE  `yg_articles` ADD  `original` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT '原创';

DROP TABLE IF EXISTS `yg_seowords`;
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

DROP TABLE IF EXISTS `yg_article_pickers`;
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