<?php

// При необходимости меняем шапку
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
mysql_query("DELETE FROM `comm_users` WHERE `time` < '".($time-(3600*3))."' AND `invite` = '1'");
mysql_query("DELETE FROM `comm_users_ban` WHERE `time_ban` < '$time'");
mysql_query("DELETE FROM `soo_visits` WHERE `time` < '".mktime(0,0,0)."'");
mysql_query("DELETE FROM `chat_comm_who` WHERE `time` < '".($time-120)."'");

$rk = ' | ';
$mcomms=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id_user` = '$user[id]'"),0); // Кол.-во сообществ обитатетеля
if(isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_visits` WHERE `id_comm` = '".intval($_GET['id'])."' AND `id_user` = '$user[id]'"),0)==0)mysql_query("INSERT INTO `comm_visits` SET `id_comm` = '".intval($_GET['id'])."', `id_user` = '$user[id]', `time` = '$time'");
$count_visits=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_visits` WHERE `id_comm` = '".intval($_GET['id'])."'"),0);
mysql_query("UPDATE `comm` SET `visits` = '$count_visits' WHERE `id` = '".intval($_GET['id'])."'");
@$uinc=mysql_fetch_array(mysql_query("SELECT * FROM `comm_users` WHERE `id_user` = '$user[id]' AND `id_comm` = '".intval($_GET['id'])."'"));
@mysql_query("UPDATE `comm_users` SET `last_time` = '$time' WHERE `id` = '$uinc[id]'");
}

if (isset($_GET['act'])) {$act = my_esc($_GET['act']);} else {$act = 'index';} 
if (in_array($act, array('add_cat', 'edit_cat', 'delete_cat', 'cat', 'add_comm', 'comm', 'comm_users', 'comm_settings', 'comm_avatar', 'comm_join', 'comm_object', 'comm_info', 'comm_cat', 'comm_activlist', 'readmin', 'user', 'blist', 'invite', 'comm_journal', 'comm_users_ban', 'forum', 'chat', 'files')))include_once 'inc/act_'.$act.'.php';
else include_once 'inc/act_index.php';
include_once "../sys/inc/tfoot.php";
?>