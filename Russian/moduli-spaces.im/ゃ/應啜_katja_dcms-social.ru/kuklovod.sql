DROP TABLE IF EXISTS `katja`;

CREATE TABLE `katja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `bot` set('0','1') NOT NULL DEFAULT '0',
  `msg` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

INSERT INTO katja VALUES("1","3","1394304382","0","ошог");
INSERT INTO katja VALUES("2","3","1394304382","1","Прикольно :-)");
INSERT INTO katja VALUES("3","7","1394309832","0","иди ты");
INSERT INTO katja VALUES("4","7","1394309832","1","Понятно :)");
INSERT INTO katja VALUES("5","7","1394310250","0",".язык.");
INSERT INTO katja VALUES("6","7","1394310250","1","Понятно :)");
INSERT INTO katja VALUES("7","7","1394310260","0","ппц");
INSERT INTO katja VALUES("8","7","1394310260","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("9","8","1394313837","0","Привет давай познакомимся");
INSERT INTO katja VALUES("10","8","1394313837","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("11","8","1394313921","0","Да с тобой тоже:)");
INSERT INTO katja VALUES("12","8","1394313921","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("13","11","1394318208","0","приветик");
INSERT INTO katja VALUES("14","11","1394318208","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("15","11","1394318245","0","как делишки? чем занимаешься?");
INSERT INTO katja VALUES("16","11","1394318245","1","Прикольно :-)");
INSERT INTO katja VALUES("17","11","1394318265","0","секс");
INSERT INTO katja VALUES("18","11","1394318265","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("19","11","1394318285","0","хочешь секса");
INSERT INTO katja VALUES("20","11","1394318285","1","Прикольно :-)");
INSERT INTO katja VALUES("21","11","1394318347","0","а где здесь есть секс");
INSERT INTO katja VALUES("22","11","1394318347","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("23","11","1394318372","0","ето всё хуйня");
INSERT INTO katja VALUES("24","11","1394318372","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("25","11","1394318396","0","я хочу секса");
INSERT INTO katja VALUES("26","11","1394318396","1","Понятно :)");
INSERT INTO katja VALUES("27","11","1394318434","0","секса хочу очень");
INSERT INTO katja VALUES("28","11","1394318434","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("29","11","1394318454","0","помоги");
INSERT INTO katja VALUES("30","11","1394318454","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("31","13","1394320250","0","Привет)))");
INSERT INTO katja VALUES("32","13","1394320250","1","Прикольно :-)");
INSERT INTO katja VALUES("33","16","1394341840","0","Привет");
INSERT INTO katja VALUES("34","16","1394341840","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("35","16","1394341855","0","Что");
INSERT INTO katja VALUES("36","16","1394341855","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("37","16","1394341881","0","Не понял");
INSERT INTO katja VALUES("38","16","1394341881","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("39","20","1394348367","0","[url=http://yoursmileys.ru/g-flower1.php?page=7][img]http://yoursmileys.ru/gsmile/flower1/g40067.gif[/img][/url]");
INSERT INTO katja VALUES("40","20","1394348367","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("41","22","1394351302","0","Привет");
INSERT INTO katja VALUES("42","22","1394351302","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("43","15","1394359805","0","привет!");
INSERT INTO katja VALUES("44","15","1394359805","1","Понятно :)");
INSERT INTO katja VALUES("45","15","1394359822","0","как дела?");
INSERT INTO katja VALUES("46","15","1394359822","1","Даже не знаю что ответить ;-)");
INSERT INTO katja VALUES("47","15","1394359838","0","почему?");
INSERT INTO katja VALUES("48","15","1394359838","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("49","7","1394364937","0","ку");
INSERT INTO katja VALUES("50","7","1394364937","1","С тобой так интересно  :-*");
INSERT INTO katja VALUES("51","7","1394364957","0","мда.лол.");
INSERT INTO katja VALUES("52","7","1394364957","1","Прикольно :-)");



DROP TABLE IF EXISTS `katja_bot`;

CREATE TABLE `katja_bot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(1024) NOT NULL,
  `otvet` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




