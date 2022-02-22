Скрипт -> Видео Онлайн
Автор  -> islamsoft
Цена -> 250 wmr

Установка проста

1. Заливаем таблицы 

CREATE TABLE IF NOT EXISTS `videos_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `kod` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `videos_komm` (
`id` int(11) NOT NULL auto_increment,
`id_videos` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
`msg` varchar(1024) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `videos_views` (
`id_videos` int(11) NOT NULL,
`id_user` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `videos_like` (
`id_videos` int(11) NOT NULL,
`id_user` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


2. На главной странице вводим код:

$v_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `videos`",$db), 0);
$v_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `videos` WHERE `time` > '".(time()-86400)."'",$db), 0);
if ($v_n==0)$v_n=NULL;
else $v_n='/+'.$v_n;
$v_v = "$v_p$v_n";
echo '<div class="main_menu">
<a href="/videos/"><img src="/videos/img/video.png" alt="" />
Видео онлайн ('.$v_v .')</a></div>';