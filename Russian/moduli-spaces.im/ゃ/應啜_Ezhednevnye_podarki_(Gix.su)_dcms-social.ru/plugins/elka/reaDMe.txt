В /index.php после строчки include_once 'sys/inc/main_menu.php'; прописать код:

if(isset($user)) {
echo "<div class='nav2'><img src='/style/icons/uslugi.gif'> <a href='/plugins/elka/'>Ежедневные подарки!</a></div>";
}

Распаковать в корене и выполнить запросы в бд, которые ниже:

alter table `user` add `time_gift` int(11) not null;

CREATE TABLE `gift_free` (
`id` int(11) NOT NULL auto_increment,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
`type` text(1024) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

Если возникли проблемы с установкой, пишите мне в аську: 656770803
