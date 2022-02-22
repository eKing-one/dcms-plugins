CREATE TABLE IF NOT EXISTS `us_guest_comms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT '0',
  `id_user_adm` int(11) DEFAULT '0',
  `time` int(11) DEFAULT NULL,
  `msg` varchar(1024) DEFAULT NULL,
  `hide` enum('0','1') DEFAULT '0',
  `hide_user` int(11) DEFAULT '0',
  `reply_id_user` int(11) DEFAULT '0',
  `reply_id_comment` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `us_guest_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comment` int(11) NOT NULL DEFAULT '0',
  `id_user_adm` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `name` char(50) NOT NULL DEFAULT '0',
  `ras` varchar(50) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `user` ADD `guestbook_password` mediumtext default NULL;

ALTER TABLE `user` ADD `guestbook_access` enum('all','only_me','friends','pass','auth') default 'all';

ALTER TABLE `user` ADD `guestbook_komm` enum('all','only_me','friends') default 'all';