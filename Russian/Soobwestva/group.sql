CREATE TABLE `gruppy_cat` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(32) NOT NULL,
`desc` varchar(100) default NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy` (
`id` int(11) NOT NULL auto_increment,
`id_cat` int(11) NOT NULL,
`name` varchar(32) NOT NULL,
`desc` varchar(100) NOT NULL,
`rules` varchar(1024) default NULL,
`admid` int(11) NOT NULL,
`konf_gruppy` set('0', '1', '2', '3') NOT NULL default '0',
`plata` int(11) default NULL,
`konf_news` set('0', '1') NOT NULL default '0',
`conf_news` set('0', '1') NOT NULL default '0',
`users` int(11) NOT NULL default '0',
`ban` int(11) NOT NULL,
`prich` varchar(1024) default NULL,
`ban_us` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_users` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`activate` set('0', '1') NOT NULL default '0',
`invit` set('0', '1') NOT NULL default '0',
`mod` set('0', '1') NOT NULL default '0',
`invit_us` int(11) default NULL,
`ban` int(11) default NULL,
`prich` varchar(1024) default NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_bl` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_friends` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_friend` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_forums` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`name` varchar(30) NOT NULL,
`desc` varchar(100) default NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_forum_thems` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(32) NOT NULL,
`id_gruppy` int(11) NOT NULL,
`id_forum` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`up` set('0','1') NOT NULL default '0',
`close` set('0','1') NOT NULL default '0',
`time` int(11) NOT NULL,
`time_create` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_forum_mess` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_them` int(11) NOT NULL,
`id_forum` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`cit` int(11) default NULL,
`mess` varchar(10000) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_chat` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`mess` varchar(1024) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_news` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`name` varchar(32) NOT NULL,
`mess` varchar(1024) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_votes` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`vote` varchar(1024) NOT NULL,
`otvet_1` varchar(32) NOT NULL,
`otvet_2` varchar(32) NOT NULL,
`otvet_3` varchar(32) default NULL,
`otvet_4` varchar(32) default NULL,
`otvet_5` varchar(32) default NULL,
`otvet_6` varchar(32) default NULL,
`otvet_7` varchar(32) default NULL,
`otvet_8` varchar(32) default NULL,
`otvet_9` varchar(32) default NULL,
`otvet_10` varchar(32) default NULL,
`time_create` int(11) NOT NULL,
`time_close` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_votes_otvet` (
`id` int(11) NOT NULL auto_increment,
`id_gruppy` int(11) NOT NULL,
`id_vote` int(11) NOT NULL,
`id_otvet` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_obmen_dir` (
  `id` int(11) NOT NULL auto_increment,
  `id_gruppy` int(11) NOT NULL,
  `num` int(11) default '0',
  `name` varchar(64) NOT NULL,
  `ras` varchar(128) NOT NULL,
  `maxfilesize` int(11) NOT NULL,
  `dir` varchar(512) NOT NULL default '/',
  `dir_osn` varchar(512) default '/',
  `upload` set('1','0') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `num` (`num`),
  KEY `dir` (`dir`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_obmen_files` (
  `id` int(11) NOT NULL auto_increment,
  `id_gruppy` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_dir` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `ras` varchar(36) NOT NULL,
  `type` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `time_last` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `k_loads` int(11) default '0',
  `opis` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `gruppy_obmen_komm` (
  `id` int(11) NOT NULL auto_increment,
  `id_gruppy` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `msg` varchar(1024) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_file` (`id_file`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
