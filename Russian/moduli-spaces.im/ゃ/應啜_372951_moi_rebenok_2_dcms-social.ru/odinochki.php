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
$set['title']='Родители-Одиночки';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
include_once 'inc/avatar.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `baby` WHERE (`mama` > '0' AND `papa` = '0') OR (`mama` = '0' AND `papa` > '0')"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Пусто";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `baby` WHERE (`mama` > '0' AND `papa` = '0') OR (`mama` = '0' AND `papa` > '0') ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
$za=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_sp` WHERE `id_baby` = '".$post['id']."' AND `id_user` = '".$user['id']."' LIMIT 1"));
$ank_1=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['papa']."' LIMIT 1"));
$ank_2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['mama']."' LIMIT 1"));
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
echo "".ava_baby($post['id'])."";
echo "</center></td>";
echo "<td class='main'>";
echo "<b>Имя ребёнка:</b> ".$post['name']."<br />";
echo "<b>Пол ребёнка:</b> <img src='img/m_".$post['pol'].".png' width='16' alt='Simptom'><br />";
echo "<b>Папа:</b> ";
if ($ank_1['id']==0)
{
echo "Нету! ";
if (!$b && !$za && $user['pol']==1)
{
echo "(<a href='my_baby.php?id=".$post['id']."&amp;rod'>Стать папой</a>)";
}
echo "<br />";
}else{
echo "".online($ank_1['id'])." <a href='/info.php?id=".$ank_1['id']."'>".$ank_1['nick']."</a><br />";
}
echo "<b>Мама:</b> ";
if ($ank_2['id']==0)
{
echo "Нету! ";
if (!$b && !$za && $user['pol']==0)
{
echo "(<a href='my_baby.php?id=".$post['id']."&amp;rod'>Стать мамой</a>)";
}
echo "<br />";
}else{
echo "".online($ank_2['id'])." <a href='/info.php?id=".$ank_2['id']."'>".$ank_2['nick']."</a><br />";
}
echo "<a href='my_baby.php?id=".$post['id']."'>Подробнее...</a><br />";
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