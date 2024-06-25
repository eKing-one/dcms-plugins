CREATE TABLE `farm_conf` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weather` enum('1','2','3','4','5') DEFAULT '1',
`time_weather` int(11) NOT NULL DEFAULT '0' COMMENT '下次天气更新前的时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `farm_conf` VALUES (1, '3', 1327857813);