CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `opis` varchar(512) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `icon_small` varchar(128) DEFAULT NULL,
  `icon_big` varchar(128) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_apps` (
  `id_user` int(11) NOT NULL,
  `id_apps` varchar(100) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;