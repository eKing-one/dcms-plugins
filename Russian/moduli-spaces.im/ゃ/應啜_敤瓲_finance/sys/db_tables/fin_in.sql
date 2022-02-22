DROP TABLE IF EXISTS `fin_in`;
CREATE TABLE `fin_in` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `oper` varchar(255) NOT NULL,
  `cena` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=cp1251;