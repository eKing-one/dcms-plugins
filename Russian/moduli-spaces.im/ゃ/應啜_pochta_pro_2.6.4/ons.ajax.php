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
$seconds = ($time - $ank['date_last']);
$sda = $seconds.' секунд';
if ($seconds > 60)$sda = round($seconds/60).' минут';
if ($seconds > 3600)$sda = round($seconds/3600).' часов';
if ($seconds < 3)$sda = '';
if (mysql_num_rows(mysql_query("SELECT `id` FROM `mail` WHERE `id_user` = '$mailid' AND `id_kont` = '$user[id]' AND `msg` = 'NOWWRTING' AND `time` > '".(time()-4)."'")) > 0)$sss = '<img src="/typing.bmp" width="11"> <b><font color="green">Пишет..</font></b>';
else
$sss = '';
if (($ank['date_last'] + 180) > time())$ons = '<b><span style="color:green">Онлайн<br />'.$sda.'</span></b><br />'.$sss.'<br /><a style="border:0px" onclick="document.cookie = \'comp=0; path=/;\';document.location.href=document.location.href;"><img src="/phone.png" height="27"></a>';
else
$ons = '<b><span style="color:red">Оффлайн</span><br /><span style="color:gray">был '.$sda.' назад</span></b><br />'.$sss.'<br /><a href="?id='.$mailid.'&changestatus" style="border:0"><img src="/phone.png" height="27"></a>';
echo $ons;