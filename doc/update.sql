// 文章增加tag标签
alter table blog_article add column tag varchar(30) not null default '' after status ;

// 飞信发送日志
CREATE TABLE IF NOT EXISTS `blog_fetion_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `msg` varchar(200) NOT NULL COMMENT '短信内容',
  `return_val` varchar(255) NOT NULL COMMENT '返回值',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='飞信发送日志' AUTO_INCREMENT=1 ;

// 飞信好友表
CREATE TABLE IF NOT EXISTS `blog_fetion_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `citycode` varchar(20) NOT NULL COMMENT '城市编码',
  `cityname` varchar(20) NOT NULL COMMENT '城市名称',
  `is_friend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否好友',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='飞信好友表' AUTO_INCREMENT=1 ;

// 当天城市天气表
CREATE TABLE IF NOT EXISTS `blog_city_weather` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `citycode` varchar(20) NOT NULL COMMENT '城市编码',
  `today` date NOT NULL COMMENT '日期',
  `msg` varchar(200) NOT NULL COMMENT '天气',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='城市天气表' AUTO_INCREMENT=1 ;

// 获取新闻表
CREATE TABLE IF NOT EXISTS `blog_news` (
  `id` int(11) NOT NULL auto_increment,
  `content` varchar(255) NOT NULL COMMENT '新闻内容',
  `today` date NOT NULL COMMENT '日期',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻' AUTO_INCREMENT=1 ;

// 好友信息业务字段增加
alter table blog_fetion_user add column `send_weather` tinyint(1) NOT NULL DEFAULT '1' COMMENT '发送天气信息0-不发送1-发送' after is_friend;
alter table blog_fetion_user add column `send_news` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送新闻信息0-不发送1-发送' after send_weather;

// log 增加类型
alter table blog_fetion_log add column `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送日志类型0-天气1-新闻' after return_val;

// 脑经急转弯表
CREATE TABLE IF NOT EXISTS `blog_quick_brain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `answer` varchar(30) NOT NULL,
  `is_send` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑筋急转弯表' AUTO_INCREMENT=1 ;

// blog_fetion_user 增加发送类型
alter table blog_fetion_user add column `send_jizhuanwan` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送脑筋急转弯0-不发送1-发送' after send_news;

股票api
http://hq.sinajs.cn/list=sh600519



ALTER TABLE  `blog_fetion_log` ADD  `title` VARCHAR( 30 ) NOT NULL DEFAULT  '' COMMENT  '标题' AFTER  `mobile`;

--
-- 表的结构 `blog_stock_warning`
--

CREATE TABLE IF NOT EXISTS `blog_stock_warning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT '代码',
  `price_max` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格上限',
  `price_min` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格下限',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='价格预警' AUTO_INCREMENT=4 ;
