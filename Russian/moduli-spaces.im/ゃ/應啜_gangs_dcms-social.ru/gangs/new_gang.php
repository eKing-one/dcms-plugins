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
$set['title']="Новая банда";
include_once H.'sys/inc/thead.php';
title();
aut();
if (isset($_POST['ok']) && $imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name']))){
$name=my_esc($_POST['name']);
$typ=intval($_POST['type']);
if (strlen2($name)<3)$err[]="Короткое название банды";
if ($typ==0)$err[]="Вы не выбрали направление банды";
if ($user['money']<10)$err[]="У вас недостаточно монет для создания банды";
if ($user['gang']!=0)$err[]="Вы уже состоите в банде";
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x>2000 || $img_y>2000)$err[]="Размер изображения превышает ограничения в 2000*2000";
if ($img_x<200 || $img_y<200)$err[]="Размер изображения должен быть не менее чем 200*200";
if (!isset($err)){
mysql_query("INSERT INTO `gangs` (`name`, `type`, `id_user`, `time`) values('".$name."', '".$typ."', '".$user['id']."','".$time."')");
$id=mysql_insert_id();
mysql_query("INSERT INTO `gangs_users` (`id_gang`, `id_user`, `status`, `time`) values('".$id."', '".$user['id']."', '2','".$time."')");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-10)."', `gang` = '".$id."' WHERE `id` ='".$user['id']."'");
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
imagejpeg($screen,H."gangs/emblem/mini/$id.png",90);
@chmod(H."gangs/emblem/mini/$id.png",0777);
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
imagejpeg($screen,H."gangs/emblem/norm/$id.png",90);
@chmod(H."gangs/emblem/norm/$id.png",0777);
imagedestroy($screen);
header("location: set_gang.php?id=".$id."");
}
err();
}
echo "<div class='mess'>* Стоимость создания банды 10 монет.<br/>* Максимально можно состоять в одной банде.</div>";
echo "<form method='post' action='?$passgen' enctype='multipart/form-data'>
<b>Название банды:</b><br/><input type='text' name='name' style='width:95%'>
<b>Эмблема:</b><br/><input type='file' name='file' style='width:97%'>
<b>Направление банды:</b><br/><select name='type' style='width:97%'><option value='0'>Выберите направление</option><option value='1'>Добрые</option><option value='2'>Злые</option></select>
<input type='submit' name='ok' style='width:97%' value='Создать банду'></form>";
include_once H.'sys/inc/tfoot.php';
?>