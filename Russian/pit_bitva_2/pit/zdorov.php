<?php
# МОД МОЙ ПИТОМЕЦ
# KAZAMA
# 383991000
error_reporting(0);
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Покупка здоровие';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'head.php';


$q_name=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));


if (isset($_GET['set']))
{
$zdorov=min(max(@intval($_GET['id']),10),80);
$balls=min(max(@intval($_GET['balls']),80),500);

$timer=$time+=60*60*3;
if($q_name['zdorov']>200)echo "<div class=err>У вас и так больше 200!</div>";
elseif($q_name['zdorov_time']>time())echo "<div class=err>3 часа не прошло с последней покупки</div>";
elseif ($user['balls']<$_GET['balls'])echo'<div class=err>Не достаточно баллов</div>';else{
mysql_query("UPDATE `pit` SET `zdorov` = '".mysql_escape_string($q_name[zdorov]+$zdorov)."' WHERE `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `pit` SET `zdorov_time` = '$timer' WHERE `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".mysql_escape_string($user[balls]-$balls)."' WHERE `id` = '$user[id]' LIMIT 1");
msg ("Успешно! Шас только через  3 часа можете купить здоровье");
}
}





echo'Выберите :';
echo '<table class="post">';
echo "<tr><td class='icon14'><img src='img/zdorov/1.png' alt='' class='icon'/></td> <td class='p_m'><a href='?set&id=80&balls=500'>Купить </a>+80 здоровья <br/>500 баллов</td></tr>\n";
echo "<tr><td class='icon14'><img src='img/zdorov/2.png' alt='' class='icon'/></td> <td class='p_m'><a href='?set&id=50&balls=300'>Купить </a>+50 здоровья <br/>300 баллов</td></tr>\n";
echo "<tr><td class='icon14'><img src='img/zdorov/3.png' alt='' class='icon'/></td> <td class='p_m'><a href='?set&id=25&balls=200'>Купить </a>+25 здоровья <br/>200 баллов</td></tr>\n";
echo "<tr><td class='icon14'><img src='img/zdorov/4.png' alt='' class='icon'/></td> <td class='p_m'><a href='?set&id=10&balls=80'>Купить </a>+10 здоровья <br/>80 баллов</td></tr>\n";

echo '</table>';
echo '<div class="msg"><a href="index.php?">В игру</a></div>';

include_once '../sys/inc/tfoot.php';

?>