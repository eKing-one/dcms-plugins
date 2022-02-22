CREATE TABLE `my_guest` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `newid` enum('0','1') NOT NULL default '0',
  `new` enum('0','1') NOT NULL default '1',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;