<?php

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Покупка силы';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'head.php';


$q_user=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));





$action=htmlspecialchars(trim($_GET['action']));
switch ($action){
default:


echo 'Здарова, '.$user['nick'].'!<br/>';
echo 'У тебя есть <b>'.$user[balls].'</b> балов сайта!<br/>Сила : '.$q_user[sila].' <br /> <font color=red>Поменять их на силу по курсу '.$cena_sila.' баллов = 1 сила </font><br />';
echo '<form action="sila.php?action=change" method="post">';
echo 'Сколько сил будете покупать :<br/>';
echo '<input name="num" type="text" value="" maxlength="3" size="3" />';
echo '<input type="submit" value="Купить"/>';
echo '</form>';
break;
case 'change':
$num=(int)$_POST['num'];
$numm=$num*$cena_sila;
$timer=$time+=60*60*$time_sila;
if(!$num || $num<1){echo 'Пустые параметры!';break;};
if(!$num || $num>$max_sila){echo "Больше $max_sila сил запрешено!";break;};
if($user['balls']<$numm){echo 'У вас нет столько балов!<br /> <a href="/vnim.php">Вернуться назад</a>';break;};
if($q_user['sila']>200){echo 'У вас и так больше 200!<br /> <a href="/vnim.php">Вернуться назад</a>';break;};
if($q_user['sila_time']>time()){echo 'Время перезарядки не прошло!<br />Она закончиться в '.vremja($q_user[sila_time]).'  <br /><a href="index.php">Вернуться назад</a>';break;};
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$numm)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `pit` SET `sila` = '".($q_user['sila']+$num)."' WHERE `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `pit` SET `sila_time` = '".$timer."' WHERE `id_user` = '$user[id]' LIMIT 1");

echo 'Успешно! Шас только через  '.$time_sila.' часов можете купить силу';
break;
};


echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';

include_once '../sys/inc/tfoot.php';

?>