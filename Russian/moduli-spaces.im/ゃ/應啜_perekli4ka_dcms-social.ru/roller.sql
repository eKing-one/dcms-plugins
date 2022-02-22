CREATE TABLE `roller` (
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `days` int(11) default '0',
  PRIMARY KEY  (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;