CREATE TABLE `farm_udobr_name` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(1024) NOT NULL,
`cena` varchar(1024) NOT NULL,
`time` varchar(1024) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `farm_udobr_name` VALUES (1, '钾肥', '50', '3600');
INSERT INTO `farm_udobr_name` VALUES (2, '石灰', '200', '14400');
INSERT INTO `farm_udobr_name` VALUES (3, '堆肥', '500', '18000');
INSERT INTO `farm_udobr_name` VALUES (4, '氮肥', '1000', '36000');
INSERT INTO `farm_udobr_name` VALUES (5, '磷肥', '3000', '54000');

