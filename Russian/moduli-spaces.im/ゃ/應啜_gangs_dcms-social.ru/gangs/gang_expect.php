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
$guser= mysql_fetch_array(mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang` = '$gang[id]' AND `id_user` = '".$user['id']."'"));
$set['title']="Желают вступить в банду ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if ($guser['status']<1)header("location:gang.php?id=$gang[id]");
if (isset($_GET['vb']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['vb'])."'"),0)!=0 && $guser['status']!=0){
mysql_query("DELETE FROM `gangs_users` WHERE `id_user` ='".intval($_GET['vb'])."' AND `id_gang` = '".$gang['id']."'");
mysql_query("INSERT INTO `gangs_enemies` (`id_gang`, `id_user`, `time`) values('".$gang['id']."', '".intval($_GET['vb'])."','".$time."')");
msg("Участник успешно успешно добавлен в Враги банды");
}
if (isset($_GET['yes']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['yes'])."'"),0)!=0 && $guser['status']!=0){
mysql_query("UPDATE `user` SET `gang` = '$gang[id]' WHERE `id` ='".intval($_GET['yes'])."'");
mysql_query("UPDATE `gangs_users` SET `type` = '0' WHERE `id_user` ='".intval($_GET['yes'])."'");
$yes=get_user($_GET['yes']);
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$yes['id']."]".$yes['nick']."[/url] вступил(а) в банду.', '".$time."')");
msg("Заявка успешно принята");
}
if (isset($_GET['no']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".intval($_GET['no'])."'"),0)!=0 && $guser['status']!=0){
mysql_query("DELETE FROM `gangs_users` WHERE `id_user` ='".intval($_GET['vb'])."' AND `id_gang` = '".$gang['id']."'");
msg("Заявка успешно отклонена");
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='1'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='mess'>Пока нет желающих вступить в эту банду</div>";
$q=mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='1' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
if ($num==0){echo "<div class='nav1'>";	$num=1;}elseif ($num==1){echo "<div class='nav2'>";	$num=0;}
echo status($ank['id']) , group($ank['id']);
echo " <a href='/info.php?id=$ank[id]'>$ank[nick]</a> \n";
echo "".medal($ank['id'])." ".online($ank['id'])."<br />";
if ($guser['status']>0)echo "<a href='?id=$gang[id]&yes=$ank[id]'> Принять</a> | <a href='?id=$gang[id]&no=$ank[id]'> Отклонить </a> | <a href='?id=$gang[id]&vb=$ank[id]'> В ВБ </a>";
echo "</div>";
}
if ($k_page>1)str("?id=$gang[id]&",$k_page,$page);
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>