<?
/*
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
$set['title']='Настройки';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if (!$b)
{
echo "<div class='err'>";
echo "У вас нет ребёнка!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
include_once 'inc/set.php';
echo "<a href='set.php?edit'><div class='main2'>";
echo "<img src='img/set.png' alt='Simptom'> Редактировать данные";
echo "</div></a>";
echo "<a href='set.php?foto'><div class='main2'>";
echo "<img src='img/set.png' alt='Simptom'> Сменить фото";
echo "</div></a>";
echo "<a href='set.php?del'><div class='main2'>";
echo "<img src='img/set.png' alt='Simptom'> Бросить ребёнка";
echo "</div></a>";
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>