<?php
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title'] = 'Управление кланом';
include_once '../sys/inc/thead.php';
title();
aut();

$id = intval($_GET['id']);
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' AND `id_clan` = '$id' LIMIT 1"));


if($us['level']==1){

$act = isset($_GET['act']) ? trim($_GET['act']) : '';

switch ($act) {

case 'delpost':

mysql_query("DELETE FROM `clan_chat` WHERE `id` = '".intval($_GET['del'])."' AND `id_clan` = '$id' LIMIT 1");
header("Location: chat.php?".SID);
break;

case 'activate':

if (isset($_GET['yes']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id` = '".intval($_GET['yes'])."' AND `activaty` = '1' LIMIT 1",$db), 0)==1)
{
$uid = intval($_GET['yes']);
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id` = $uid LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $us[id_user] LIMIT 1"));

	$msg="Пользователь [url=/info.php?id=$ank[id]]$ank[nick][/url] вступил(а) в клан!";
	mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', '$msg', '$time')");
	
	$msg2="Вас приняли в клан [b]$clan[name][/b]!";
	mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$ank[id]', '$msg2', '$time')");
	
	mysql_query("UPDATE `clan_user` SET `activaty` = '0' WHERE `id` = '$uid' LIMIT 1");
}

if (isset($_GET['no']))
{
$uid = intval($_GET['no']);
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id` = $uid LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $us[id_user] LIMIT 1"));
	
	$msg2="Вам отказали вовступлении в клан [b]$clan[name][/b]!";
	mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$ank[id]', '$msg2', '$time')");
	
mysql_query("DELETE FROM `clan_user` WHERE `id_clan` = '$id' AND `id_user` = '$ank[id]' LIMIT 1");
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE  `id_clan` = '$id' AND `activaty` = '1'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo "<div class='rowdown'>"; 
echo "Нет новых участников ожидающих активации.\n";
echo "</div>"; 
}

$q = mysql_query("SELECT * FROM `clan_user` WHERE  `id_clan` = '$id' AND `activaty` = '1' ORDER BY `time` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

if($num==1){ echo "<div class='rowup'>";
$num=0;}
else
{echo "<div class='rowdown'>";
$num=1;}



echo "".online($ank['id'])." ";
echo " <a href='/info.php?id=$ank[id]'><span style=\"color:$ank[ncolor]\">$ank[nick]</span></a> ";
echo "<br/>";
echo "Рейтинг: <b>$ank[rating]</b><br />\n";

echo "[<a href='?act=activate&amp;id=$id&amp;yes=$post[id]'>Приянть</a>]\n";
echo "[<a href='?act=activate&amp;id=$id&amp;no=$post[id]'>Отказать</a>]\n";
echo'</div>'; 
}
break;

default:
echo "<div class='rowdown'>\n"; 
echo "<a href='?id=$id&amp;act=activate'>Активация участников</a><br/>\n"; 
echo "</div>\n";
break;
}
}
else
{
echo "<div class='rowdown'>\n"; 
echo "Вы не модератор данного клана!\n";  
echo "</div>\n";
}
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>