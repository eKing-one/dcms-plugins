CREATE TABLE `farm_semen` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_user` int(11) NOT NULL,
`semen` int(11) NOT NULL,
`kol` int(11) NOT NULL,
`sezon` int(11) NOT NULL DEFAULT '1' COMMENT 'Сезон растения на грядке',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;