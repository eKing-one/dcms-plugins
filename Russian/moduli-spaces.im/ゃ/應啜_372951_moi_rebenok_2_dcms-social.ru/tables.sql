CREATE TABLE IF NOT EXISTS `baby` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `pol` int(11) NOT NULL default '0',
  `papa` int(11) NOT NULL default '0',
  `mama` int(11) NOT NULL default '0',
  `health` int(11) NOT NULL default '0',
  `happy` int(11) NOT NULL default '0',
  `eda` int(11) NOT NULL default '0',
  `iq` int(11) NOT NULL default '0',
  `progulka` int(11) NOT NULL default '0',
  `play` int(11) NOT NULL default '0',
  `skazka` int(11) NOT NULL default '0',
  `health_time` int(11) NOT NULL default '0',
  `happy_time` int(11) NOT NULL default '0',
  `eda_time` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `baby_chat` (
  `id` int(11) NOT NULL auto_increment,
  `id_user` int(11) NOT NULL default '0',
  `msg` varchar(1024) default NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `baby_sp` (
  `id` int(11) NOT NULL auto_increment,
  `id_baby` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `baby_shop_igrushki` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(1024) default NULL,
  `cena` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `baby_shop_igrushki` (`id`, `name`, `cena`) VALUES
(1, 'Футбольный мяч', 50),
(2, 'Маска', 50),
(3, 'Компьютер', 50),
(4, 'PSP', 50);

CREATE TABLE IF NOT EXISTS `baby_shop_eda` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(1024) default NULL,
  `cena` int(11) NOT NULL default '0',
  `health` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `baby_shop_eda` (`id`, `name`, `cena`, `health`) VALUES
(1, 'Яишница', 20, 5),
(2, 'Гамбургер', 20, 5),
(3, 'Тортик', 20, 5),
(4, 'Тосты', 20, 5);

CREATE TABLE IF NOT EXISTS `baby_shop_skazki` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(1024) default NULL,
  `cena` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `baby_shop_skazki` (`id`, `name`, `cena`) VALUES
(1, 'Волшебные сказки', 20),
(2, 'Учимся считать', 50),
(3, 'Букварь', 80),
(4, 'Сборник советских сказок', 100),
(5, 'Энциклопедия', 200);