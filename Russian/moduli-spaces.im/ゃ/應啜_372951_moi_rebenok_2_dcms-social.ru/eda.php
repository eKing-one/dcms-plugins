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
$set['title']='Покормить';
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
include_once 'inc/timer.php';
include_once 'inc/eda.php';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_shop_eda`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby_shop_eda` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14' style='width:10%'><center>";
echo "<img src='shop_eda/".$post['id'].".png' alt='Simptom'>";
echo "</center></td>";
echo "<td class='main'>";
echo "<img src='img/shop.png' alt='Simptom'> ".$post['name']."<br />";
echo "<img src='img/cena.png' alt='Simptom'> ".$post['cena']." баллов<br />";
echo "<img src='img/health.png' alt='Simptom'> +".$post['health']."% здоровья и сытости<br />";
echo "<a href='?id=".$post['id']."'><img src='img/eda.png' alt='Simptom'> Купить и покормить</a>";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?eda&amp;',$k_page,$page);
}
echo "<a href='my_baby.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>