-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2015 年 10 月 12 日 18:25
-- 服务器版本: 5.5.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `app_liuhuiblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `blog_article`
--

CREATE TABLE IF NOT EXISTS `blog_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `status` enum('正常','删除') NOT NULL DEFAULT '正常' COMMENT '文章状态',
  `tag` varchar(30) NOT NULL DEFAULT '',
  `click` mediumint(9) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_cat`
--

CREATE TABLE IF NOT EXISTS `blog_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '分类名称',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章分类' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_city_weather`
--

CREATE TABLE IF NOT EXISTS `blog_city_weather` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `citycode` varchar(20) NOT NULL COMMENT '城市编码',
  `today` date NOT NULL COMMENT '日期',
  `msg` varchar(200) NOT NULL COMMENT '天气',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='城市天气表' AUTO_INCREMENT=691 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_comment`
--

CREATE TABLE IF NOT EXISTS `blog_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '文章id',
  `email` varchar(30) NOT NULL COMMENT '邮箱',
  `personal_site` varchar(100) NOT NULL COMMENT '个人网页',
  `comment` text NOT NULL COMMENT '回复内容',
  `add_time` int(11) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='回复表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_fetion_log`
--

CREATE TABLE IF NOT EXISTS `blog_fetion_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(30) NOT NULL COMMENT '手机号',
  `msg` varchar(200) NOT NULL COMMENT '短信内容',
  `return_val` varchar(255) NOT NULL COMMENT '返回值',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送日志类型0-天气1-新闻',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='飞信发送日志' AUTO_INCREMENT=2006 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_fetion_user`
--

CREATE TABLE IF NOT EXISTS `blog_fetion_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `citycode` varchar(20) NOT NULL COMMENT '城市编码',
  `cityname` varchar(20) NOT NULL COMMENT '城市名称',
  `is_friend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否好友',
  `send_weather` tinyint(1) NOT NULL DEFAULT '1' COMMENT '发送天气信息0-不发送1-发送',
  `send_news` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送新闻信息0-不发送1-发送',
  `send_jizhuanwan` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送脑筋急转弯0-不发送1-发送',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='飞信好友表' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------


--
-- 转存表中的数据 `blog_fetion_user`
--

INSERT INTO `blog_fetion_user` (`id`, `name`, `mobile`, `email`, `citycode`, `cityname`, `is_friend`, `send_weather`, `send_news`, `send_jizhuanwan`, `is_send`, `add_time`) VALUES
(1, '刘辉', '13581578309', '13581578309@139.com', '101010100', '北京', 1, 1, 1, 1, 0, 1395911573),
(2, '爸', '15079460901', '15079460901@139.com', '101240408', '南城', 1, 1, 0, 0, 0, 1396069166),
(3, '赵鑫', '18511866562', '18511866562@139.com', '101010100', '北京', 0, 1, 0, 0, 0, 1430624225);

--
-- 表的结构 `blog_news`
--

CREATE TABLE IF NOT EXISTS `blog_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL COMMENT '新闻内容',
  `today` date NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='新闻' AUTO_INCREMENT=555 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_quick_brain`
--

CREATE TABLE IF NOT EXISTS `blog_quick_brain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `answer` varchar(30) NOT NULL,
  `is_send` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='脑筋急转弯表' AUTO_INCREMENT=500 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_spider`
--

CREATE TABLE IF NOT EXISTS `blog_spider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spider` varchar(20) NOT NULL COMMENT '蜘蛛',
  `source_url` varchar(255) NOT NULL COMMENT '来源',
  `visit_time` datetime NOT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='蜘蛛访问表' AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_user`
--

CREATE TABLE IF NOT EXISTS `blog_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `email` varchar(30) NOT NULL COMMENT '邮箱',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 转存表中的数据 `blog_user`
--

INSERT INTO `blog_user` (`id`, `username`, `password`, `email`, `add_time`) VALUES
(1, 'liuhui', '85fb639a6edebdd5e81d4376473f2c32', 'sunlyliuh@163.com', 0);

--
-- 表的结构 `blog_visit`
--

CREATE TABLE IF NOT EXISTS `blog_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL COMMENT '用户ip',
  `visiturl` varchar(200) NOT NULL DEFAULT '' COMMENT '访问的url',
  `created` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='博客' AUTO_INCREMENT=1887 ;

--
-- 表的结构 `blog_stock`
--

CREATE TABLE IF NOT EXISTS `blog_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '股票名称',
  `code` varchar(10) NOT NULL COMMENT '股票编号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='股票表' AUTO_INCREMENT=1 ;


--
-- 表的结构 `blog_stock_detail`
--

CREATE TABLE IF NOT EXISTS `blog_stock_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT '股票code',
  `current_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当前价格',
  `today_change` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当日变化值',
  `today_change_per` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当日变化百分比',
  `volume` mediumint(8) NOT NULL DEFAULT '0' COMMENT '成交量（单位手）',
  `deal_price` mediumint(8) NOT NULL DEFAULT '0' COMMENT '成交额（单位万元）',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '当前时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='股票抓取明细表' AUTO_INCREMENT=1 ;


--
-- 表的结构 `blog_stock_statis`
--

CREATE TABLE IF NOT EXISTS `blog_stock_statis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT '股票code',
  `current_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当日价格',
  `today_change` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当日变化值',
  `today_change_per` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '当日变化百分比',
  `volume` mediumint(8) NOT NULL DEFAULT '0' COMMENT '成交量（单位手）',
  `deal_price` mediumint(8) NOT NULL DEFAULT '0' COMMENT '成交额（单位万元）',
  `date` date NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='股票每日统计表' AUTO_INCREMENT=1 ;