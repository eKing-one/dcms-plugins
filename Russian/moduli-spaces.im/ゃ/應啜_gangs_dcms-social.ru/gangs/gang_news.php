<?php
/*
Автор: WIZART
e-mail: bi3apt@gmail.com
icq: 617878613
Сайт: WizartWM.RU
*/
include_once '../sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0){header("Location: index.php?".SID);exit;}
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
$set['title']="Новости банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_news` WHERE `id_gang`='".$gang['id']."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>Пока нет ни одной новости</div>";
$q=mysql_query("SELECT * FROM `gangs_news` WHERE `id_gang`='".$gang['id']."' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}

echo "<img src='icons/news.png' alt=''> <b>".vremja($post['time'])."</b><br/>";
echo output_text($post['msg']);
echo "</div>";
}
if ($k_page>1)str("?id=$gang[id]&",$k_page,$page);
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>