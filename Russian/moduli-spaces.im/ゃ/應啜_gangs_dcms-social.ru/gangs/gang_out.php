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
$set['title']="Выход из банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (!isset($user)){
$err[]="Эта функция доступна только авторизированым пользователям";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($user['id']==$gang['id_user']){
$err[]="Главарь не может покинуть свою банду";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($user['gang']!=$gang['id']){
$err[]="Вы не входите в состав этой банды.";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if (isset($_POST['ok'])){
mysql_query("UPDATE `user` SET `gang` = '0' WHERE `id` ='".$user['id']."'");
mysql_query("DELETE FROM `gangs_users` WHERE `id_user` ='".$user['id']."' AND `id_gang` = '".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['nick']."]".$user['nick']."[/url] покинул(а) банду.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&$passgen'>
<b>Вы действительно хотите выйти из этой банды?</b><br/><input type='submit' name='ok' value='Да, выйти' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>