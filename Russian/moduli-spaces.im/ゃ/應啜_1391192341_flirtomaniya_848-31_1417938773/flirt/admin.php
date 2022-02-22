<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Админка';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header("Location: /flirt/index.php");
exit;
}
$voprosu=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_voprosu`"), 0);
echo "<a href='voprosu.php'><div class='foot'>";
echo "<img src='img/admin.png' alt='Simptom'> Вопросы (".$voprosu.")";
echo "</div></a>";
$dej_flirt=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_flirt`"), 0);
echo "<a href='dej_flirt.php'><div class='foot'>";
echo "<img src='img/admin.png' alt='Simptom'> Дейстия для флиртов (".$dej_flirt.")";
echo "</div></a>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>