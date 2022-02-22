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
$set['title']='Моя лента';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
include_once 'inc/avatar.php';
include_once 'inc/my_lenta_act.php';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt_lenta` WHERE `id_ank` = '".$user['id']."' AND `otvet` = '0'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='err'>";
echo "Лента пуста!";
echo "</div>";
}
$q=mysql_query("SELECT * FROM `flirt_lenta` WHERE `id_ank` = '".$user['id']."' AND `otvet` = '0' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$post['id_user']."' LIMIT 1"));
$vopros=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_voprosu` WHERE `id` = '".$post['id_vopros']."' LIMIT 1"));
echo "<table style='width:100%' cellspacing='1' cellpadding='1'><tr>";
echo "<td class='icon14'><center>";
mini_ava_flirt($ank['id']);
echo "</center></td>";
echo "<td class='p_m'>";
echo "<span style='float : right;'>";
echo "<img src='img/time.png' alt='Simptom'> ".vremja($post['time'])."";
echo "</span>";
echo "<a href='/info.php?id=".$ank['id']."'>".$ank['nick']."</a> ".online($ank['id'])."<br />";
echo "<b>Вопрос:</b><br />";
echo "".output_text($vopros['vopros'])."<br />";
echo "<b>Выберите верный ответ:</b><br />";
echo "<a href='?vopros=".$post['id']."&amp;otvet=1'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_1'])."";
echo "</a><br />";
echo "<a href='?vopros=".$post['id']."&amp;otvet=2'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_2'])."";
echo "</a><br />";
echo "<a href='?vopros=".$post['id']."&amp;otvet=3'>";
echo "<img src='img/vop.png' alt='Simptom'> ".output_text($vopros['variant_3'])."";
echo "</a><br />";
echo "</td>";
echo "</tr></table>";
}
if ($k_page>1)
{
str('?',$k_page,$page);
}
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>