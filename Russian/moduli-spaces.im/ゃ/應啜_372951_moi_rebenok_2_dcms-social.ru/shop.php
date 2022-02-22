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
$set['title']='Управление магазином';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header('Location: index.php');
exit;
}
include_once 'inc/shop.php';
$igrushki=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_igrushki`"), 0);
echo "<a href='?igrushki'><div class='main2'>";
echo "<img src='img/shop.png' width='16' alt='Simptom'> Игрушки (".$igrushki.")";
echo "</div></a>";
$eda=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_eda`"), 0);
echo "<a href='?eda'><div class='main2'>";
echo "<img src='img/shop.png' width='16' alt='Simptom'> Еда (".$eda.")";
echo "</div></a>";
$skazki=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_skazki`"), 0);
echo "<a href='?skazki'><div class='main2'>";
echo "<img src='img/shop.png' width='16' alt='Simptom'> Книги (".$skazki.")";
echo "</div></a>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>