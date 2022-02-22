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
$gang = mysql_fetch_assoc(mysql_query("SELECT * FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0 && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_user` = '".$user['id']."' AND`id_gang` = '".$gang['id']."' LIMIT 1"),0)!=0 && $user['gang']>0){header("Location: index.php?".SID);exit;}
$set['title']="Приглашение в банду ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if (!isset($user) && $gang['id']!=$user['gang'])header("location:gang.php?id=$gang[id]");
if (isset($_POST['ok'])){
$nick=my_esc($_POST['nick']);
$invite = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `nick` = '$nick' LIMIT 1"));
$ank=get_user($invite['id']);
if ($user['nick']==$ank['nick'])$err[]="Зачем вам приглашать самого себя?";
else if ($ank['gang']==$gang['id'])$err[]="Этот пользователя уже состоит в этой банде";
else if ($ank['gang']!=0)$err[]="Этот пользователь уже является участником какой-то банды";
else if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0)$err[]="Такого пользователя не существует";
else if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_enemies` WHERE `id_user` = '$ank[id]' AND `id_gang` = '$gang[id]' LIMIT 1"),0)!=0)$err[]="Этот пользователь находится в списке врагов этой банды.";
else if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_invite` WHERE `id_user` = '$ank[id]' AND `id_gang` = '$gang[id]' LIMIT 1"),0)!=0)$err[]="Этот пользователь уже приглашен.";
if (!isset($err)){
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] приглашает вас в банду [url=/gangs/my_invite.php]".my_esc($gang['name'])."[/url]', '$time')");
mysql_query("INSERT INTO `gangs_invite` (`id_user`, `id_kont`, `id_gang`, `time`) values('$user[id]', '$ank[id]', '$gang[id]', '$time')");
echo "<div class='mess'>Пользователю ".htmlspecialchars($nick)." успешно отправлено приглашение.</div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]&$passgen'>Пригласить еще</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
err();
}
echo "<form method='post' action='?id=$gang[id]&$passgen'>
<b>Введите ник пользователя которого хотите пригласить в банду:</b><br/><input type='text' name='nick' style='width:95%'>
<input type='submit' name='ok' value='Пригласить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>