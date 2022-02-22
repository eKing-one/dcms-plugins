CREATE TABLE IF NOT EXISTS `cms_garem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_garem` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_garem_zapret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `user_set` ADD `cost` int(11) NOT NULL;
ALTER TABLE `user_set` ADD `garem_uk` int(11) NOT NULL DEFAULT '0';

INSERT INTO `menu` (`id`, `type`, `name`, `url`, `counter`, `pos`, `icon`) VALUES
(0, 'link', 'Гаремы рейтинг', '/plugins/user_garem/top/', '', 0, 'topg.png');