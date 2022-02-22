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
$set['title']='ДетДом';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
include_once 'inc/det_dom.php';
include_once 'inc/avatar.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby` WHERE `mama` = '0' AND `papa` = '0'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby` WHERE `mama` = '0' AND `papa` = '0' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
echo "".ava_baby($post['id'])."";
echo "</center></td>";
echo "<td class='main'>";
echo "<b>Имя ребёнка:</b> ".$post['name']."<br />";
echo "<b>Пол ребёнка:</b> <img src='img/m_".$post['pol'].".png' width='16' alt='Simptom'><br />";
if (!$b)
{
echo "<a href='?id=".$post['id']."&amp;my'><div class='main2'><center>";
if ($user['pol']==0)
{
echo "Стать мамой";
}else{
echo "Стать папой";
}
echo "</center></div></a>";
}
if ($user['level']>=4)
{
echo "<a href='?id=".$post['id']."&amp;del'><div class='main2'><center>";
echo "Удалить";
echo "</center></div></a>";
}
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?',$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>