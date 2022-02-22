<?
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

$set['title'] = 'Приглашение в клан';

include_once '../sys/inc/thead.php';
title();
aut();

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));

if (!isset($user) && !isset($_GET['id'])){header("Location: /klan.php?".SID);exit;}
if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0){
echo "<div class='str'>";
echo "Пользователь не найден в базе данных.\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $ank[id] LIMIT 1"));

$act = isset($_GET['act']) ? trim($_GET['act']) : '';

switch ($act) {

case 'my':
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)==0){
$pr=mysql_fetch_array(mysql_query("SELECT * FROM `clan_prig` WHERE `id_kont` = $user[id] LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $pr[id_user] LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$pr[id_clan]' LIMIT 1"));
echo "<div class='str'>";
echo "<b>$ank[nick]</b> приглашает вас вступить в клан <b>$clan[name]</b><br/>\n";
echo "Вы можете: <a href='?act=yes'><b>Вступить</b></a> или <a href='?act=noy'><b>Отказатся</b></a>\n";
echo "</div>"; 
}
else
{
echo "<div class='str'>";
echo "Вы уже состоите в клане\n";
echo "</div>"; 
}
break;

case 'yes':
$pr=mysql_fetch_array(mysql_query("SELECT * FROM `clan_prig` WHERE `id_kont` = $user[id] LIMIT 1"));
mysql_query("INSERT INTO `clan_user` (`id_user`, `id_clan`, `time`) VALUES ('$user[id]', '$pr[id_clan]', '".time()."')");;
mysql_query("DELETE FROM `clan_prig` WHERE `id_kont` = '$user[id]' AND `id_clan` = '$pr[id_clan]' LIMIT 1");

$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $pr[id_user] LIMIT 1"));
$ank2 = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $pr[id_kont] LIMIT 1"));

$msg="[url=/info.php?id=$ank[id]]$ank[nick][/url] приглавсил(а)  в клан: [url=/info.php?id=$ank2[id]]$ank2[nick][/url]";
mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$pr[id_clan]', '$msg', '$time')");
break;

case 'noy':
mysql_query("DELETE FROM `clan_prig` WHERE `id_kont` = '$user[id]' LIMIT 1");
break;

default:
$msg="[url=/klan/prig.php?act=my]Вас пригласили в клан[/url]";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$msg', '$time')");
mysql_query("INSERT INTO `clan_prig` (`id_user`, `id_kont`, `id_clan`) values('$user[id]', '$ank[id]', '$us[id_clan]')");


echo "<div class='str'>";
echo "Приглашение $ank[nick] в клан успешно отпралено<br/>\n";
echo "</div>";
break;
}

echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>