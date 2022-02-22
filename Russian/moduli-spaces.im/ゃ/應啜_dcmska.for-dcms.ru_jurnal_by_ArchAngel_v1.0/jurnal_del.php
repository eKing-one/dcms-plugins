<?php
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

if ($_GET['del'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta` WHERE `id` = '".intval($_GET['del'])."'"),0)==1) {
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `jurnal` WHERE `id` = '".intval($_GET['del'])."' LIMIT 1"));
mysql_query("DELETE FROM `jurnal` WHERE `id` = '$post[id]'");
}

if ($_GET['del_all'])
{
$post=$user[id];
mysql_query("DELETE FROM `jurnal` WHERE `id_kont` = '$post'");
}

header("Location: jurnal.php".SID);
?>
