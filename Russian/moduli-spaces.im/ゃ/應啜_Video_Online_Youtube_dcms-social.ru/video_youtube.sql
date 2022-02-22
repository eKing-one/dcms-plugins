CREATE TABLE `video_category` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(315) default NULL,
  `icon` varchar(64) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `video` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `opis` varchar(1024) default NULL,
  `url` varchar(11) default NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `video_komm` (
  `id` int(11) NOT NULL auto_increment,
  `msg` varchar(512) default NULL,
  `id_user` int(11) NOT NULL,
  `id_video` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `video_like` (
  `id_video` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `like` int(11) DEFAULT '0',
  KEY `id_video` (`id_video`,`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `menu` (`id`, `type`, `name`, `url`, `counter`, `pos`, `icon`) VALUES
(20, 'link', 'Видео Youtube', '/plugins/video/', 'plugins/video/count.php', 7, 'youtube.png');