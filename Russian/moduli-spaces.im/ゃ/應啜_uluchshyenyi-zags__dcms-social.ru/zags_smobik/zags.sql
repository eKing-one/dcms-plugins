CREATE TABLE IF NOT EXISTS `zags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ank_id` int(11) NOT NULL,
  `user_nick` text NOT NULL,
  `ank_nick` tinytext NOT NULL,
  `key` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;