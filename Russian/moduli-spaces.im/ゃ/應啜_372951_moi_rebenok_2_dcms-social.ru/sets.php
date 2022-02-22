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
if ($user['level']<=3)
{
header('Location: index.php');
exit;
}
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `id` = '".$it."' LIMIT 1"));
}
if (!isset($_GET['id']) || !$b || $b['id']==0)
{
echo "<div class='err'>";
echo "Ребёнок не найден!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
include_once 'inc/sets.php';
echo "<a href='sets.php?id=".$b['id']."&amp;edit'><div class='main2'>";
echo "<img src='img/adm.png' alt='Simptom'> Редактировать данные";
echo "</div></a>";
echo "<a href='sets.php?id=".$b['id']."&amp;foto'><div class='main2'>";
echo "<img src='img/adm.png' alt='Simptom'> Сменить фото";
echo "</div></a>";
echo "<a href='sets.php?id=".$b['id']."&amp;del'><div class='main2'>";
echo "<img src='img/adm.png' alt='Simptom'> Отобрать ребёнка";
echo "</div></a>";
echo "<a href='my_baby.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>