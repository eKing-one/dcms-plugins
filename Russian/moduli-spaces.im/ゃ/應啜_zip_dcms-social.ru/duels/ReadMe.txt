Модуль = Дуэли
Автор = Islamfon
Цена = 199 руб


Залить все таблицы:

CREATE TABLE IF NOT EXISTS `duels` (
  `id` int(11)  NOT NULL AUTO_INCREMENT,
  `id_user1` int(11),
  `id_user2` int(11),
  `foto1` varchar(111)  NOT NULL,
  `foto2` varchar(111)  NOT NULL,
  `golos1` int(11)  NOT NULL,
  `golos2` int(11)  NOT NULL,
  `active` int(11)  NOT NULL,
  `time` int(11)  NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `duels_settings` (
  `golos` int(11)  NOT NULL,
  `pobeda` int(11)  NOT NULL,
  `pobeda2` int(11)  NOT NULL,
  `golosov` int(11)  NOT NULL,
  `invite` int(11)  NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

INSERT INTO `duels_settings` (`golos`, `pobeda`, `pobeda2`, `golosov`, `invite`) VALUES
(0, 0, 0, 5, 5);

CREATE TABLE IF NOT EXISTS `duels_golos` (
  `id_duels` int(11)  NOT NULL,
  `user` int(11)  NOT NULL,
  `id_user` int(11)  NOT NULL ,
  `time` int(11)  NOT NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `duels_invite` (
  `id_duels` int(11)  NOT NULL,
  `id_user` int(11)  NOT NULL,
  `user` int(11)  NOT NULL ,
  `time` int(11)  NOT NULL 
) ENGINE=MyISAM DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

alter table `user` add `duels` int(11) NOT NULL;


