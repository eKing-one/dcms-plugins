<?php

###################################################
#   Фотоконкурсы под dcms 6.6.4 и 6.7.7           #
#   Автор: Nort, он же Lns                        #
#   icq: 484014288, сайт: http://inwap.org        #
#                                                 #
#   Вы не имеете права продавать, распростронять, #
#   давать друзьям даный скрипт.                  #
#                                                 #
#   Даная версия являет платной, и купить         #
#   можно только у автора.                        #
###################################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title']= 'Фотоконкурсы';
include_once '../sys/inc/thead.php';

include_once 'inc.php';

title();
aut();
only_reg();

# ===================================================================

echo $div_name;
echo $Inform.' Установка фотоконкурсов<br/>';
echo '</div>';

echo $div_link;
echo 'Таблицы залиты.<br/>';
echo '</div>';

# Конкурсы
mysql_query("CREATE TABLE IF NOT EXISTS `FotoKonkurs` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(128) NOT NULL,
`opis` text NOT NULL,
`date_on` varchar(100) NOT NULL,
`date_on2` varchar(100) NOT NULL,
`date_off` varchar(100) NOT NULL,
`date_off2` varchar(100) NOT NULL,
`date_golos` varchar(100) NOT NULL,
`date_golos2` varchar(100) NOT NULL,
`pol` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

# Участники
mysql_query("CREATE TABLE IF NOT EXISTS `FotoKonkursUser` (
`id` int(11) NOT NULL auto_increment,
`user_id` int(11) NOT NULL,
`konkurs_id` int(11) NOT NULL,
`name` varchar(128) NOT NULL,
`opis` varchar(512) NOT NULL,
`time` int(11) NOT NULL,
`foto` text NOT NULL,
`komm` int(11) NOT NULL,
`status` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

mysql_query("ALTER TABLE `FotoKonkursUser` ADD `rating` int( 11 ) DEFAULT '0' NOT NULL ;");

# Рейтинг
mysql_query("CREATE TABLE IF NOT EXISTS `FotoKonkursRating` (
`id` int(11) auto_increment,
`user_id` int(11) NOT NULL,
`photo_id` int(11) NOT NULL,
`rating` int(11) NOT NULL,
`time` int(11) NOT NULL,
`date` varchar(128) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

# Комментарии
mysql_query("CREATE TABLE IF NOT EXISTS `FotoKonkursComments` (
`id` int(11) auto_increment,
`user_id` int(11) NOT NULL,
`photo_id` int(11) NOT NULL,
`komm` varchar(512) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

# Настройки
mysql_query("CREATE TABLE IF NOT EXISTS `FotoKonkurs_settings` (
`id` int(11) NOT NULL,
`div_name` varchar(24) NOT NULL,
`div_link` varchar(24) NOT NULL,
`div_zebr1` varchar(24) NOT NULL,
`div_zebr2` varchar(24) NOT NULL,
`OnlineUser` int(11) NOT NULL,
`GameStr` int(11) NOT NULL,
`OnlinePrimer` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

mysql_query("INSERT INTO `FotoKonkurs_settings` (`id`, `div_name`, `div_link`, `div_zebr1`, `div_zebr2`, `OnlineUser`, `GameStr`, `OnlinePrimer`) VALUES ('1', 'name', 'link', 'row', 'row1', '300', '5', '25');");

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>