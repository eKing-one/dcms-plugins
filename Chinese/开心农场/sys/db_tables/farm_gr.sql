CREATE TABLE `farm_gr` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_user` int(11) NOT NULL,
`semen` int(11) NOT NULL,
`woter` int(11) NOT NULL DEFAULT '0',
`time_water` int(11) DEFAULT NULL COMMENT 'Время полива',
`kol` varchar(1024) DEFAULT NULL COMMENT 'Количество урожая',
`time` varchar(1024) DEFAULT NULL,
`vskop` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'Скопана ли грядка??',
`sezon` int(11) NOT NULL DEFAULT '1' COMMENT 'Сезон растения на грядке',
`udobr` enum('1','0') NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;