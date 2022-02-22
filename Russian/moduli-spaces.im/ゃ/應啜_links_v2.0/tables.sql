CREATE TABLE IF NOT EXISTS `links_niz` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `sname` varchar(32) NOT NULL,
  `url` varchar(32) NOT NULL,
  `icon` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  KEY `icon` (`icon`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `links_niz_user` (
  `id` int(11) NOT NULL auto_increment,
  `id_link` int(11) NOT NULL,
  `id_user` int(11) default NULL,
  `link` varchar(1000) DEFAULT '0',
  `link_name` varchar(1000) DEFAULT '0',
  `icon` varchar(32) default NULL,
  `pos` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `pos` (`pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `user` ADD `show_foot_type` enum('icons','text') DEFAULT 'text';

ALTER TABLE `user` ADD `foot_sit` enum('left','center','right') DEFAULT 'left';

ALTER TABLE `user` ADD `show_foot` enum('on','off') DEFAULT 'on';