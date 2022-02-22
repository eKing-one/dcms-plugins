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
$set['title']='Флирт';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
include_once 'inc/avatar.php';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_fl` WHERE `id_ank` = '".$user['id']."'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Путо!";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `flirt_fl` WHERE `id_ank` = '".$user['id']."' ORDER BY `time` ASC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['id_user']."' LIMIT 1"));
$flirt=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_flirt` WHERE `id` = '".$post['id_flirt']."' LIMIT 1"));
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
mini_ava_flirt($ank['id']);
echo "</center></td>";
echo "<td class='p_m'>";
echo "".status($ank['id'])." ";
echo "<a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a> ";
echo "".online($ank['id'])."<br />";
echo "<img src='img/time.png' alt='Simptom'> ".vremja($post['time'])."<br />";
echo "<img src='img/flirt.png' alt='Simptom'> <b>Действие:</b><br />";
echo "".output_text($flirt['name'])."<br />";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str("?",$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>