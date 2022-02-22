<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
only_reg();
$set['title']='Журнал операций';
include_once '../sys/inc/thead.php';
title();
aut();
###############################
if(isset($_GET['del']))
{
if(isset($_GET['ok']))
{
mysql_query("DELETE FROM `money` WHERE `user`='".$user['id']."'");
msg('Журнал операций очищен');
}
else
{
echo "<div class='err'><center>Вы уверены что хотите очистить Журнал операций? <a href='?del&amp;ok'>Да</a> / <a href='?'>Нет</a></center></div>\n";
}
}
################################

$col=mysql_num_rows(mysql_query("SELECT * FROM `money` WHERE `user`='$user[id]'"));
echo "<div class='title'>";
echo '<b>Всего операций: <font color="red">'.$col.'</font></b><br />';
echo "</div>";
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `money` WHERE `user` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0){echo "<div class='title'>Нет операций</div>\n";}
$q=mysql_query("SELECT * FROM `money` WHERE `user` = '$user[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");

while($r=mysql_fetch_array($q)){
echo "<div class='title'>";
if($r['mp']==0)$mp='<font color="red">- '.htmlspecialchars(stripslashes($r['money'])).'</font>';
if($r['mp']==1)$mp='<font color="green">+ '.htmlspecialchars(stripslashes($r['money'])).'</font>';
echo ''.$mp.' <font color="indigo"><b>балов</b></font><br />';
echo '<font color="green">'.htmlspecialchars(stripslashes($r['usl'])).'</font> <b>('.date('d.y/H:i',$r['time']).')</b><br />';
echo "</div>";
}
if ($k_page>1)str('?',$k_page,$page); // Вывод страниц
echo "<div class='foot'>";
echo "<img src='/images/kor.gif'/> <a href='?del'> <b>Очистить список операций</b></a><br />\n";
include_once '../sys/inc/tfoot.php';
?>