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
$set['title']="Вступление в банду ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if (!isset($user)){
$err[]="Эта функция доступна только авторизированым пользователям";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_enemies` WHERE `id_user` = '$user[id]' AND `id_gang` = '$gang[id]' LIMIT 1"),0)!=0){
$err[]="Вы не можете вступить в эту банду так как находитесь в списке врагов этой банды.";
err();

echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($user['gang']==$gang['id']){
$err[]="Вы уже входите в состав этой банды";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($user['gang']!=0){
$err[]="Вы уже состоите в одной из банд.";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($gang['closed']==2){
$err[]="Вступление в эту банду доступно только по приглашению одного из ее участников.";
err();
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
if ($gang['closed']==1 || $gang['closed']==3)echo "<div class='mess'>".($gang['closed']==1?"Вступление в эту банду является платным, цена вступления составляет ".$gang['cena']." Монет ":"Вступление в эту банду доступно по подтверждению, после отправления вашей заявки на вступление кто-то из высшего состава должен будет подтвердить или отклонить вашу заявку.")."</div>";
if (isset($_POST['ok'])){
if ($gang['cena']>$user['money'] && $gang['closed']==1)$err[]="У вас недостаточно монет для вступления в эту банду.";
if (!isset($err)){
if ($gang['closed']==0){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `type`, `time`) values('".$gang['id']."', '".$user['id']."', '0', '".$time."')");
mysql_query("UPDATE `user` SET `gang` = '".$gang['id']."' WHERE `id` ='".$user['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] вступил(а) в банду.', '".$time."')");
}
if ($gang['closed']==1){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `time`) values('".$gang['id']."', '".$user['id']."','".$time."')");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-$gang['cena'])."',`gang` = '".$gang['id']."' WHERE `id` ='".$user['id']."'");
mysql_query("UPDATE `gangs` SET `money` = '".($gang['money']+$gang['cena'])."' WHERE `id` ='".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] вступил(а) в банду.', '".$time."')");
}
if ($gang['closed']==3){
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `time`,`type`) values('".$gang['id']."', '".$user['id']."', '".$time."','1')");
}
header("location:gang.php?id=$gang[id]");
}
err();
}
echo "<form method='post' action='?id=$gang[id]&$passgen'>
<b>Вы действительно хотите вступить в эту  банду?</b><br/><input type='submit' name='ok' value='Да, я хочу вступить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
?>