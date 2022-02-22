CREATE TABLE `bot_cron` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `time_start` int(15) default NULL,
  `time_end` int(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;