<?php
/*
Автор: WIZART
Сайт: WizartWM.RU
E-mail автора: bi3apt@gmail.com
*/
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
$set['title']="Установка банд";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_GET['1'])){
$_SESSION['install']=1;
header("location:?$passgen");
}
if (isset($_GET['2'])){
$_SESSION['install']=2;
header("location:?$passgen");
}
if (!isset($_SESSION['install'])){
echo "<div class='mess'>Банды v1.0</div>";
echo "<div class='mess'>Автор: WIZART<br/>Сайт1: <a href='http://krasavo.ru'>Krasavo.RU</a><br/>Сайт2: <a href='http://wizartwm.ru'>WizartWM.RU</a><br/>E-MAIL: bi3apt@gmail.com</div>";
echo "<div class='mess'>Функционал банд:<br/><img src='icons/add.png' alt=''> Создание банды<br/><img src='icons/add.png' alt=''> Вступление в банду<br/><img src='icons/add.png' alt=''> Выход из банды<br/><img src='icons/add.png' alt=''> Мини чат банды<br/><img src='icons/add.png' alt=''> Состав банды<br/><img src='icons/add.png' alt=''> Высший состав банды<br/><img src='icons/add.png' alt=''> Враги банды<br/><img src='icons/add.png' alt=''> Приглашение в банду<br/><img src='icons/add.png' alt=''> Мои приглашения в банду<br/><img src='icons/add.png' alt=''> Поднятие рейтинга банды<br/><img src='icons/add.png' alt=''> Управление бандой<br/><img src='icons/add.png' alt=''> Установка статуса банды<br/><img src='icons/add.png' alt=''> Выбор направления банды(Добрые/Злые)<br/><img src='icons/add.png' alt=''> Настройки вступления в банду (Свободное/По приглашению/Платное/По одобрению высшего состава)<br/><img src='icons/add.png' alt=''> Общак банды.<br/><img src='icons/add.png' alt=''> Раздача общала банды(Баллы/Монеты)<br/><img src='icons/add.png' alt=''> Сбор дивидентов(Раз в сутки)<br/><img src='icons/add.png' alt=''> Блокировка банды<br/><img src='icons/add.png' alt=''> Разблокировка банды<br/><img src='icons/add.png' alt=''> Удаление банды<br/><img src='icons/add.png' alt=''> Установка эмблемы (Накладывается копирайт вашего сайта)<br/><img src='icons/add.png' alt=''> Новости банды<br/><img src='icons/add.png' alt=''> Подтверждние вступления в банду
<br/><img src='icons/add.png' alt=''> Назначение и снятие с должностей состава банды<br/><img src='icons/add.png' alt=''> Добавление пользователей в врагов банды<br/><img src='icons/add.png' alt=''> Список всех банд по рейтингу<br/><img src='icons/add.png' alt=''> Вывод информации на личной странице (В какой банде состоит пользователь)<br/><img src='icons/add.png' alt=''> Вывод информации на в банде (Главарь банды, Направление, Вступление)<br/><img src='icons/add.png' alt=''> Актив банды (Монеты, Баллы, Рейтинг)<br/><img src='icons/add.png' alt=''>А так же администрация сайта сможет управлять бандами<br/><img src='icons/add.png' alt=''>Короче модуль просто супер и будет v2.0
</div>"; 
echo "<a href='?1'><div class='main'><img src='/style/icons/str.gif' alt=''> Перейти к установке</div></a>";
} else if ($_SESSION['install']==1){
echo "<div class='mess'>Банды v1.0 - Установка шаг: 1</div>";
echo "<div class='mess'><img src='icons/add.png' alt=''> Через админку прописываем ссылку на конкурсы <b>/gangs/</b> и счетчик  <b>/gangs/count.php</b><br/><img src='icons/add.png' alt=''> вывести в удобном месте на личной странице вот это <b>include H.'gangs/info.php';</b></div>";
echo "<a href='?2'><div class='main'><img src='/style/icons/str.gif' alt=''> Установка шаг: 2</div></a>";
} else if ($_SESSION['install']==2){
mysql_query("ALTER TABLE `user` ADD `gang` int(11) NOT NULL default'0'");
mysql_query("CREATE TABLE `gangs` (
`id` int(11) NOT NULL auto_increment,
`rating` int(11) NOT NULL default'0',
`money` int(11) NOT NULL default'0',
`balls` int(11) NOT NULL default'0',
`status` varchar(250) NOT NULL,
`name` varchar(50) NOT NULL,
`block` set('0','1') NOT NULL default'0',
`cena` int(11) NOT NULL default'0',
`time` int(11) NOT NULL default'0',
`divident` int(11) NOT NULL,
`closed` set('0','1','2','3') NOT NULL default'0',
`type` set('0','1','2') NOT NULL default'0',
`id_user` int(11) NOT NULL,
PRIMARY KEY  (`id`),
KEY `rating` (`rating`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE `gangs_users` (
`id` int(11) NOT NULL auto_increment,
`id_gang` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`status` set('0','1','2') NOT NULL default'0',
`type` set('0','1') NOT NULL default'0',
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE `gangs_enemies` (
`id` int(11) NOT NULL auto_increment,
`id_gang` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE `gangs_news` (
`id` int(11) NOT NULL auto_increment,
`id_gang` int(11) NOT NULL,
`msg` varchar(500) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE `gangs_minichat` (
`id` int(11) NOT NULL auto_increment,
`id_gang` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`msg` varchar(500) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
mysql_query("CREATE TABLE `gangs_invite` (
`id` int(11) NOT NULL auto_increment,
`id_kont` int(11) NOT NULL,
`id_user` int(11) NOT NULL,
`id_gang` int(11) NOT NULL,
`time` int(11) NOT NULL,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
echo "<div class='mess'>Банды v1.0 - Установка шаг: 2</div>";
$them="Установка банд";
$msg="Модуль банды установлен на сайт  ".$_SERVER['HTTP_HOST']." перейти на сайт: http://".$_SERVER['HTTP_HOST']."";
$adds="From: \"root@$_SERVER[HTTP_HOST]\" <root@$_SERVER[HTTP_HOST]>";
$adds .= "Content-Type: text/html; charset=utf-8";
$email="bi3apt@spaces.ru";
mail($email,'=?utf-8?B?'.base64_encode($them).'?=',$msg,$adds);
echo "<div class='mess'><img src='icons/add.png' alt=''> Модуль банды успешно установлен.<br/><img src='icons/add.png' alt=''> Не забывайте про авторские права.<br/><img src='icons/add.png' alt=''> Не барыжим и не даем друзьям.<br/><img src='icons/add.png' alt=''> Радуемся классному модулю и <font color='red'>не забудьте удалить файл <b>gangs/install.php</b></font></div>";
echo "<a href='index.php'><div class='main'><img src='/style/icons/str.gif' alt=''> Перейти к бандам</div></a>";
}
include_once H.'sys/inc/tfoot.php';
?>