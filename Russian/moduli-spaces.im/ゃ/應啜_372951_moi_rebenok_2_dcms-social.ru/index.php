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
$set['title']='Мой Ребёнок';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if ($b)
{
$ank_1=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$b['papa']."' LIMIT 1"));
$ank_2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$b['mama']."' LIMIT 1"));
include_once 'inc/avatar.php';
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
echo "".ava_baby($b['id'])."";
echo "</center></td>";
echo "<td class='main'>";
echo "<b>Имя ребёнка:</b> ".$b['name']."<br />";
echo "<b>Пол ребёнка:</b> <img src='img/m_".$b['pol'].".png' width='16' alt='Simptom'><br />";
echo "<b>Папа:</b> ";
if ($ank_1['id']==0)
{
echo "Нету!<br />";
}else{
echo "".online($ank_1['id'])." <a href='/info.php?id=".$ank_1['id']."'>".$ank_1['nick']."</a><br />";
}
echo "<b>Мама:</b> ";
if ($ank_2['id']==0)
{
echo "Нету!<br />";
}else{
echo "".online($ank_2['id'])." <a href='/info.php?id=".$ank_2['id']."'>".$ank_2['nick']."</a><br />";
}
echo "</td>";
echo "</tr></table>";
include_once 'inc/uv.php';
echo "<a href='my_baby.php'><div class='main2'>";
echo "<img src='img/m_".$b['pol'].".png' width='16' alt='Simptom'> Мой Ребёнок";
echo "</div></a>";
$zayavki=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_sp` WHERE `id_baby` = '".$b['id']."'"), 0);
echo "<a href='zayavki.php'><div class='main2'>";
echo "<img src='img/zayavki.png' alt='Simptom'> Заявки (".$zayavki.")";
echo "</div></a>";
}else{
echo "<center><img src='img/bab.png' alt='Simptom'></center>";
echo "<a href='new_baby.php'><div class='main2'>";
echo "<img src='img/m_1.png' width='16' alt='Simptom'> Завести Ребёнка";
echo "</div></a>";
}
$chat=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby_chat`"), 0);
echo "<a href='chat.php'><div class='main2'>";
echo "<img src='img/chat.png' alt='Simptom'> Собрание родителей  (".$chat.")";
echo "</div></a>";
$odinochki=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby` WHERE (`mama` > '0' AND `papa` = '0') OR (`mama` = '0' AND `papa` > '0')"), 0);
echo "<a href='odinochki.php'><div class='main2'>";
echo "<img src='img/odinochki.png' alt='Simptom'> Родители-Одиночки (".$odinochki.")";
echo "</div></a>";
echo "<a href='top.php'><div class='main2'>";
echo "<img src='img/top.png' alt='Simptom'> Топ детей";
echo "</div></a>";
$det_dom=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby` WHERE `mama` = '0' AND `papa` = '0'"), 0);
echo "<a href='det_dom.php'><div class='main2'>";
echo "<img src='img/home.png' alt='Simptom'> ДетДом (".$det_dom.")";
echo "</div></a>";
if ($user['level']>=4)
{
echo "<a href='shop.php'><div class='main2'>";
echo "<img src='img/shop.png' width='16' alt='Simptom'> Управление магазином";
echo "</div></a>";
}
include_once '../sys/inc/tfoot.php';
?>