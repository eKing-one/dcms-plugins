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
$guser= mysql_fetch_array(mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang` = '$gang[id]' AND `id_user` = '".$user['id']."'"));
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==0){header("Location: index.php?".SID);exit;}
if ($user['level']>1 || $gang['id_user']){
if (isset($_GET['bank'])){
$set['title']="Общак банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_GET['divident']) && $gang['divident']<$time){
$balls=$gang['rating']*3;
mysql_query("UPDATE `gangs` SET `balls` = '".($gang['balls']+$balls)."', `divident` = '".($time+86400)."' WHERE `id` ='".$gang['id']."'");
echo "<div class='mess'> Вы успешно собрали дивиденты за сутки. В общак банды начисленно <b>$balls баллов</b></div>";
}
if (isset($_POST['balls_ok'])){
$count_users=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'"),0);
$balls_bank=intval($_POST['balls']);
$balls=intval($balls_bank/$count_users);
if ($balls_bank<$count_users)$err[]="В банде баллов меньше чем пользователей";
if ($gang['balls']<$balls_bank)$err[]="В банде недостаточно баллов";
if (!isset($err)){
mysql_query("UPDATE `gangs` SET `balls` = '".($gang['balls']-$balls_bank)."' WHERE `id` = '$gang[id]' LIMIT 1");
$q=mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
mysql_query("UPDATE `user` SET `balls` = '".($ank['balls']+$balls)."' WHERE `id` = '$ank[id]' LIMIT 1");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', 'Привет $ank[nick] , главарь банды [url=/gangs/gang.php?id=$gang[id]]".my_esc($gang['name'])."[/url] разделил баллы из общака банды. Вам досталось $balls баллов. ', '$time')");
}
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] разделил баллы из общака банды. Каждому участнику банды досталось по $balls баллов.', '".$time."')");
msg("Баллы с общака банды успешно разделены между составом банды");
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
err();
}
if (isset($_POST['money_ok'])){
$count_users=mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'"),0);
$money_bank=intval($_POST['money']);
$money=intval($money_bank/$count_users);
if ($money_bank<$count_users)$err[]="В банде монет меньше чем пользователей";
if ($gang['money']<$money_bank)$err[]="В банде недостаточно монет";
if (!isset($err)){
mysql_query("UPDATE `gangs` SET `money` = '".($gang['money']-$money_bank)."' WHERE `id` = '$gang[id]' LIMIT 1");
$q=mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
mysql_query("UPDATE `user` SET `money` = '".($ank['money']+$money)."' WHERE `id` = '$ank[id]' LIMIT 1");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', 'Привет $ank[nick] , главарь банды [url=/gangs/gang.php?id=$gang[id]]".my_esc($gang['name'])."[/url] разделил монеты из общака банды. Вам досталось $money монет. ', '$time')");
}
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] разделил монеты из общака банды. Каждому участнику банды досталось по $money монет.', '".$time."')");
msg("Монеты с общака банды успешно разделены между составом банды");
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
err();
}
if ($gang['divident']<$time)echo "<a href='?id=$gang[id]&bank&divident'><div class='main'><img src='icons/money.png' alt=''>  Собрать дивиденты</div></a>";
echo "<form method='post' action='?id=$gang[id]&bank&$passgen'>
<b>Баллы банды:</b><br/><input type='text' name='balls' value='".$gang['balls']."' style='width:95%'>
<input type='submit' name='balls_ok' value='Раздать составу банды' style='width:97%'></form>";
echo "<form method='post' action='?id=$gang[id]&bank&$passgen'>
<b>Монеты банды:</b><br/><input type='text' name='money' value='".$gang['money']."' style='width:95%'>
<input type='submit' name='money_ok' value='Раздать составу банды' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']==2){
if (isset($_GET['type'])){
$set['title']="Направление банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$type=intval($_POST['type']);
mysql_query("UPDATE `gangs` SET `type` = '".$type."' WHERE `id` ='".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] изменил(а) направление банды.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&type&$passgen'>
<b>Направление банды:</b><select name='type' style='width:97%'><option value='1'".($gang['type']==1?" selected='selected'":null).">Добрые</option><option value='2'".($gang['type']==2?" selected='selected'":null).">Злые</option></select>
<input type='submit' name='ok' value='Изменить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']>=1){
if (isset($_GET['closed'])){
$set['title']="Вступление в банду ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$closed=intval($_POST['closed']);
$cena=intval($_POST['cena']);
mysql_query("UPDATE `gangs` SET `closed` = '".$closed."', `cena` = '".$cena."' WHERE `id` ='".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] изменил(а) настройки вступления в банду.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&closed&$passgen'>
<b>Вступление в банду:</b><select name='closed' style='width:97%'><option value='0'".($gang['closed']==0?" selected='selected'":null).">Свободное</option><option value='1'".($gang['closed']==1?" selected='selected'":null).">Платное</option><option value='2'".($gang['closed']==2?" selected='selected'":null).">По приглашению</option><option value='3'".($gang['closed']==3?" selected='selected'":null).">По подтверждению</option></select>
<b>Цена за вступление если оно платное (в монетах):</b><br/><input type='text' name='cena' value='".$gang['cena']."' style='width:95%'>
<input type='submit' name='ok' value='Изменить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']>0){
if (isset($_GET['emblem'])){
$set['title']="Эмблема банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok']) && $imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name']))){
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x>2000 || $img_y>2000)$err[]="Размер изображения превышает ограничения в 2000*2000";
if ($img_x<200 || $img_y<200)$err[]="Размер изображения должен быть не менее чем 200*200";
if (!isset($err)){
if ($img_x==$img_y){
$dstW=40;
$dstH=40;
}
elseif ($img_x>$img_y){
$prop=$img_x/$img_y;
$dstW=40;
$dstH=ceil($dstW/$prop);
}else{
$prop=$img_y/$img_x;
$dstH=40;
$dstW=ceil($dstH/$prop);
}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagejpeg($screen,H."gangs/emblem/mini/$gang[id].png",90);
@chmod(H."gangs/emblem/mini/$gang[id].png",0777);
imagedestroy($screen);
if ($img_x==$img_y){
$dstW=200;
$dstH=200;
}
elseif ($img_x>$img_y){
$prop=$img_x/$img_y;
$dstW=200;
$dstH=ceil($dstW/$prop);
}else{
$prop=$img_y/$img_x;
$dstH=200;
$dstW=ceil($dstH/$prop);
}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
$screen=img_copyright($screen);
imagejpeg($screen,H."gangs/emblem/norm/$gang[id].png",90);
@chmod(H."gangs/emblem/norm/$gang[id].png",0777);
imagedestroy($screen);
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] изменил(а) эмблему банды.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
err();
}
if(isset($_GET['del']) && is_file(H.'gangs/emblem/norm/'.$gang['id'].'.png')){
unlink(H.'gangs/emblem/norm/'.$gang['id'].'.png');
unlink(H.'gangs/emblem/mini/'.$gang['id'].'.png');
header("location: gang.php?id=$gang[id]");
}
if(is_file(H.'gangs/emblem/norm/'.$gang['id'].'.png'))echo "<div class='nav2'><b><img src='emblem/norm/$gang[id].png' alt=''><br/><a href='?id=$gang[id]&emblem&del' style='color:red'>Удалить эмблему</a></div>";
echo "<form method='post' action='?id=$gang[id]&emblem&$passgen' enctype='multipart/form-data'>
<b>Новая эмблема  :</b><br/><input type='file' name='file' style='width:97%'><br/>
<input type='submit' name='ok' value='Выгрузить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']>1){
if (isset($_GET['name'])){
$set['title']="Название банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$name=my_esc($_POST['name']);
mysql_query("UPDATE `gangs` SET `name` = '".$name."' WHERE `id` ='".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] изменил(а) название банды.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&name&$passgen'>
<b>Название банды:</b><br/><input type='text' name='name' value='".htmlspecialchars($gang['name'])."' style='width:95%'>
<input type='submit' name='ok' value='Изменить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']>0){
if (isset($_GET['status'])){
$set['title']="Статус банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$status=my_esc($_POST['status']);
mysql_query("UPDATE `gangs` SET `status` = '".$status."' WHERE `id` ='".$gang['id']."'");
mysql_query("INSERT INTO `gangs_news` (`id_gang`, `msg`, `time`) values('".$gang['id']."', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] изменил(а) статус банды.', '".$time."')");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&status&$passgen'>
<b>Статус банды: </b><br/><textarea name='status' style='width:95%;'>".htmlspecialchars($gang['status'])."</textarea>
<input type='submit' name='ok' value='Изменить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if ($user['level']>1 || $guser['status']>1){
if (isset($_GET['del'])){
$set['title']="Удаление банды ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok'])){
$q=mysql_query("SELECT * FROM `gangs_users` WHERE `id_gang`='".$gang['id']."' AND `type`='0'");
while ($post = mysql_fetch_array($q)){
mysql_query("UPDATE `user` SET `gang` = '0' WHERE `gang` ='".$post['id_user']."'");
}
mysql_query("DELETE FROM `gangs` WHERE `id` ='".$gang['id']."'");
mysql_query("DELETE FROM `gangs_users` WHERE `id_gang` ='".$gang['id']."'");
mysql_query("DELETE FROM `gangs_news` WHERE `id_gang` ='".$gang['id']."'");
mysql_query("DELETE FROM `gangs_minichat` WHERE `id_gang` ='".$gang['id']."'");
mysql_query("DELETE FROM `gangs_invite` WHERE `id_gang` ='".$gang['id']."'");
mysql_query("DELETE FROM `gangs_enemies` WHERE `id_gang` ='".$gang['id']."'");
header("location:gang.php?id=$gang[id]");
}
echo "<form method='post' action='?id=$gang[id]&del&$passgen'>
<b>Вы действительно хотите удалить банду?</b>
<input type='submit' name='ok' value='Да, удалить' style='width:97%'></form>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='?id=$gang[id]'> Вернуться в настройки</a></div>";
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
include_once H.'sys/inc/tfoot.php';
}
}
if(isset($_GET['noblock']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".$gang['id']."' AND `block` = '1'"),0)!=0 && isset($user) && $user['level']>1){
mysql_query("UPDATE `gangs` SET `block` = '0' WHERE `id` = '".$gang['id']."' LIMIT 1");
header("location:gang.php?id=$gang[id]");
}
if(isset($_GET['block']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gangs` WHERE `id` = '".$gang['id']."' AND `block` = '0'"),0)!=0 && isset($user) && $user['level']>1){
mysql_query("UPDATE `gangs` SET `block` = '1' WHERE `id` = '".$gang['id']."' LIMIT 1");
header("location:gang.php?id=$gang[id]");
}
$set['title']="Управление бандой ".htmlspecialchars($gang['name'])."";
include_once H.'sys/inc/thead.php';
title();
aut();

if ($gang['block']==1){
echo "<div class='err'>Эта банда заблокирована!</div>";
if ($user['level']<1)include_once H.'sys/inc/tfoot.php';
}
if ($guser['status']==2 || $user['level']>1)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&bank'>Общак банды</a></div>";
if ($user['level']>1 || $guser['status']>0){
if ($guser['status']==2 || $user['level']>1)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&type'>Направление банды</a></div>";
if ($guser['status']>=1 || $user['level']>1)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&closed'>Вступление в банду</a></div>";
echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&emblem'>Эмблема банды</a></div>";
echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&status'>Статус банды</a></div>";
if ($guser['status']==2 || $user['level']>1)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&name'>Название банды</a></div>";
if ($guser['status']==2 || $user['level']>1)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&del'>Удалить банду</a></div>";
if ($user['level']>1){
if ($gang['block']==0)echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&block'>Заблокироватью банду</a></div>";
else echo "<div class='main'><img src='/style/icons/str.gif' alt=''> <a href='?id=$gang[id]&noblock'>Разблокировать банду</a></div>";
}
echo "<div class='foot'><img src='/style/icons/str2.gif' alt=''> <a href='gang.php?id=$gang[id]'> В банду ".htmlspecialchars($gang['name'])."</a></div>";
} else header("location:index.php");
include_once H.'sys/inc/tfoot.php';
?>