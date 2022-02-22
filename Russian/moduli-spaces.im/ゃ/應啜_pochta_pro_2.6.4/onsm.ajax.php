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
@error_reporting(E_ALL);
$ank = get_user($_GET['getid']);
$mailid = intval($_GET['mailid']);
if (mysql_num_rows(mysql_query("SELECT `id` FROM `mail` WHERE `id_user` = '$mailid' AND `id_kont` = '$user[id]' AND `msg` = 'NOWWRTING' AND `time` > '".(time()-4)."'")) > 0)$sss = '<img src="/typing.bmp" width="11"> <b><font color="green">Пишет..</font></b>';
else
$sss = '';
echo $sss;