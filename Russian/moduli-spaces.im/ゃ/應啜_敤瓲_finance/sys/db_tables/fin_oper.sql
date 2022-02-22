DROP TABLE IF EXISTS `fin_oper`;
CREATE TABLE `fin_oper` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `oper` varchar(255) NOT NULL,
  `cena` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=225 DEFAULT CHARSET=cp1251;