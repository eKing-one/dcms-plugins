CREATE TABLE `root` (
`id` int(11) NOT NULL auto_increment,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
`bot` set('0','1') NOT NULL default'0',
`msg` varchar(1024) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `root_bot` (
`id` int(11) NOT NULL auto_increment,
`msg` varchar(1024) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;