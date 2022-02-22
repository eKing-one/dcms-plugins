CREATE TABLE IF NOT EXISTS `rassilka_send` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time` int(10) NOT NULL,
  `msg` text NOT NULL,
  `group_access` int(2) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `user` add column `new_news_read` int(3) default '0';