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
$set['title']='Все питомцы';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'head.php';


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `pit`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo '<div class="menu">Нет питомцев!</div>';
$q = mysql_query("SELECT * FROM `pit` ORDER BY `sila` DESC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q))
{


$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='".$f['id_user']."'"));
echo '<table class="post"><tr><td class="icon14">';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$ank[id]'"),0) != '0')echo '<img src="img/'.$f[pit].'.png" alt="" class="icon"/></td>';
echo '<td class="p_m">';
if ($f['name']!=NULL)echo "<img src='icon/pit.png'> <a href='index.php?id=$f[id_user]'>$f[name]</a> <br />";else echo"<br /><img src='icon/pit.png'> <a href='index.php?id=$f[id_user]'>без имени</a><br/>";
echo "Сила: $f[sila]<br /> Хозяин: <a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "</td>";
echo "</tr>";
echo '</table>';
}
echo "<div class='foot'>";
if ($k_page>1)str("vse.php?",$k_page,$page); // Вывод страниц
echo "</div>";

echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';
include_once '../sys/inc/tfoot.php';

?>