CREATE TABLE IF NOT EXISTS `vip_premimum` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL,
  `nomer` varchar(150) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;