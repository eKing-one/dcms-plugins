

ALTER TABLE `user` ADD `post_like` int(11) NOT NULL default '0', ADD `post_dislike` int(11) NOT NULL default '0';
ALTER TABLE `notification` MODIFY `type` varchar(48) NOT NULL;

ALTER TABLE `notification_set` ADD `post` int(11) NOT NULL default '1';
CREATE TABLE `forum_like` (
  `id` int(11) NOT NULL auto_increment,
   `id_them` int(11) NOT NULL default '0',
  `id_post` int(11) NOT NULL default '0',
`like` int(11) NOT NULL default '0',
`dislike` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
CREATE TABLE `forum_like_users` (
  `id` int(11) NOT NULL auto_increment,
  `id_them` int(11) NOT NULL default '0',
`id_post` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
