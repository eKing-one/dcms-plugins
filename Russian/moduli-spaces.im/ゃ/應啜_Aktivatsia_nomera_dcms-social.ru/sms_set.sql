CREATE TABLE `sms_set` (
 `id` int(11) NOT NULL auto_increment,
 `login` varchar(45) NOT NULL,
 `pwd` varchar(45) NOT NULL,
 `pod` varchar(45) NOT NULL,
 PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `sms_set` (`login`, `pwd`, `pod`) VALUES ('login', 'pass', 'site');