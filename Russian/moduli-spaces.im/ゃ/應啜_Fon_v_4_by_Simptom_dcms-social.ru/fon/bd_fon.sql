CREATE TABLE IF NOT EXISTS `fon_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `fon_id` int(10) NOT NULL DEFAULT '0',
  `time` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `fon_anketa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `fon_id` int(10) NOT NULL DEFAULT '0',
  `time` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `user` ADD `fon_info_zapret` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_anketa_zapret` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_info_vibor` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_anketa_vibor` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_info_pokaz` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_anketa_pokaz` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_vugr_zapret` enum('0','1') NOT NULL default '0';
ALTER TABLE `user` ADD `fon_vugr_zapret2` enum('0','1') NOT NULL default '0';