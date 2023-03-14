CREATE TABLE `farm_conf` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weather` enum('1','2','3','4','5') DEFAULT '1',
`time_weather` int(11) NOT NULL DEFAULT '0' COMMENT 'Время до следующего обновления погоды',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO `farm_conf` VALUES (3, '3', 1327857813);