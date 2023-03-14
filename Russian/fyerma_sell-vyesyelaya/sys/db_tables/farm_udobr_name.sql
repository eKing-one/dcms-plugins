CREATE TABLE `farm_udobr_name` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(1024) NOT NULL,
`cena` varchar(1024) NOT NULL,
`time` varchar(1024) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `farm_udobr_name` VALUES (1, 'Курник', '50', '3600');
INSERT INTO `farm_udobr_name` VALUES (2, 'Торф', '200', '14400');
INSERT INTO `farm_udobr_name` VALUES (3, 'Компост', '500', '18000');
INSERT INTO `farm_udobr_name` VALUES (4, 'Азот', '1000', '36000');
INSERT INTO `farm_udobr_name` VALUES (5, 'Коровяк', '3000', '54000');

