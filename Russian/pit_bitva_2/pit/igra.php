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
$set['title']='Зоомагазин';
include_once '../sys/inc/thead.php';
title();
aut();

include_once 'head.php';

$q_name=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));


if (isset($_GET['set']))
{

if ($user['balls']<$_GET['balls'])echo'<div class=err>Не достаточно баллов</div>';else{
$balls=min(max(@intval($_GET['balls']),200),400);
mysql_query("UPDATE `pit` SET `igra` = '".mysql_escape_string($_GET['id'])."' WHERE `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".mysql_escape_string($user[balls]-$balls)."' WHERE `id` = '$user[id]' LIMIT 1");
if (!isset($err))msg('Ваш питомец изменен');
header("Location: index.php");
}
}





echo'Выберите игру <br />';
echo "<img src='img/igra/1.png' alt='' class='icon'/> <a href='?set&id=1&balls=300'>Выбрать(300баллов)</a><br />\n";
echo "<img src='img/igra/2.png' alt='' class='icon'/> <a href='?set&id=2&balls=200'>Выбрать(200баллов)</a><br />\n";
echo "<img src='img/igra/3.png' alt='' class='icon'/> <a href='?set&id=3&balls=300'>Выбрать(300баллов)</a><br />\n";
echo "<img src='img/igra/4.png' alt='' class='icon'/> <a href='?set&id=4&balls=400'>Выбрать(400баллов)</a><br />\n";
echo "<img src='img/igra/5.png' alt='' class='icon'/> <a href='?set&id=5&balls=200'>Выбрать(200баллов)</a><br />\n";
echo "<img src='img/igra/6.png' alt='' class='icon'/> <a href='?set&id=6&balls=200'>Выбрать(200баллов)</a><br />\n";
echo "<img src='img/igra/7.png' alt='' class='icon'/> <a href='?set&id=7&balls=200'>Выбрать(200баллов)</a><br />\n";
echo "<img src='img/igra/8.png' alt='' class='icon'/> <a href='?set&id=8&balls=300'>Выбрать(300баллов)</a><br />\n";
echo "<img src='img/igra/9.png' alt='' class='icon'/> <a href='?set&id=9&balls=200'>Выбрать(200баллов)</a><br />\n";

echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';
include_once '../sys/inc/tfoot.php';

?>