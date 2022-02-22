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
$set['title']='Удалить книгу';
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
$dd=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_shop_skazki` WHERE `id` = '".$it."' LIMIT 1"));
}
if (!$dd || $dd['id']==0)
{
echo "<div class='err'>";
echo "Игрушка не найдена!";
echo "</div>";
echo "<a href='shop.php?skazki'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['yes']))
{
mysql_query("DELETE FROM `baby_shop_skazki` WHERE `id` = '".$dd['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Книга успешно удалена!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены что хотите удалить книгу ".$dd['name']."?";
echo "</div>";
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='main2'><center>";
echo "<a href='?id=".$dd['id']."&amp;yes'><img src='img/yes.png' alt='Simptom'> Да</a>";
echo "</center></td>";
echo "<td class='main2'><center>";
echo "<a href='shop.php?igrushki'><img src='img/no.png' alt='Simptom'> Нет</a>";
echo "</center></td>";
echo "</tr></table>";
}
echo "<a href='shop.php?skazki'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>