<?php
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title'] = 'Управление кланом';
include_once '../sys/inc/thead.php';
title();
aut();

$id = intval($_GET['id']);
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' AND `id_clan` = '$id' LIMIT 1"));


if($us['level']==2){

$act = isset($_GET['act']) ? trim($_GET['act']) : '';

switch ($act) {
// Новости клана //
case 'news':

if ( isset($_GET['del']) && is_numeric($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_news` WHERE `id` = '".intval($_GET['del'])."' AND `id_clan` = '$id' LIMIT 1",$db), 0)==1)
{
mysql_query("DELETE FROM `clan_news` WHERE `id` = '".intval($_GET['del'])."' AND `id_clan` = '$id' LIMIT 1");
mysql_query("OPTIMIZE TABLE `clan_news`");
msg('Новость удалена');
}

if (isset($_POST['title']) && isset($_POST['msg']))
{
$title=esc($_POST['title'],1);
$msg=esc($_POST['msg']);

if (strlen2($title)>32){$err='Слишком большой заголовок новости';}
if (strlen2($title)<3){$err='Короткий заголовок';}
if (strlen2($msg)>1024){$err='Содержание новости слишком большое';}
if (strlen2($msg)<2){$err='Новость слишком короткая';}

$msg=mysql_real_escape_string($msg);

if (!isset($err)){
mysql_query("INSERT INTO `clan_news` (`time`, `msg`, `title`, `id_clan`) values('$time', '$msg', '$title', '$id')");
mysql_query("OPTIMIZE TABLE `clan_news`");
msg('Новость добавлена');
}
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_news`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "<div class='rowdown'>\n";
echo "Нет новостей\n";
echo "</div>\n";
}
$q=mysql_query("SELECT * FROM `clan_news` WHERE `id_clan` = '$id' ORDER BY `id` DESC LIMIT $start, $set[p_str]");

while ($post = mysql_fetch_array($q))
{
echo "<div class='rowdown'>\n";
echo "$post[title] (".vremja($post['time']).")<br />\n";
echo trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['msg'])))))))."<br />\n";
echo "<a href=\"?act=news&amp;id=$id&amp;page=$page&amp;del=$post[id]\">Удалить новость</a><br />\n";
echo "</div>\n";
}

if ($k_page>1)str('news.php?',$k_page,$page); // Вывод страниц
echo "<div class='rowup'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=news\">\n";
echo "Заголовок новости:<br />\n<input name=\"title\" size=\"16\" maxlength=\"32\" value=\"\" type=\"text\" /><br />\n";
echo "Текст новости:<br />\n<textarea name=\"msg\" ></textarea><br />\n";
echo "<input value=\"Готово\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

// Логотип клана //
case 'logo':
if (isset($_FILES['file']))
{

if (eregi('\.jpe?g$',$_FILES['file']['name']) && $imgc=@imagecreatefromjpeg($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>80 || imagesy($imgc)>80)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=80; // ширина
$dstH=80; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=80;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=80;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."files/klan/$id.jpg",0777);
@unlink(H."files/klan/$id.jpg");
imagejpeg($screen,H."files/klan/$id.jpg",100);
@chmod(H."files/klan/$id.jpg",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."files/klan/$id.jpg");
}

msg("Логотип успешно установлен");
}
}
echo "<div class='rowdown'>\n"; 
echo "Текущий логотип:<br/>\n";
if(is_file(H.'files/klan/'.$id.'.jpg')){
echo '<img src="/files/klan/'.$id.'.jpg" alt=""/><br/>';
}
else
{
echo 'Не установлен<br/>';
}
echo "</div>\n";
echo "<div class='rowup'>\n"; 
echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"?id=$id&amp;act=logo\">\n";
echo "Выберите логотип:<br />\n";
echo "<input type='file' name='file' accept='image/jpeg' /><br />\n";
if(!is_file(H.'files/klan/'.$id.'.jpg'))echo "<input value=\"Загрузить\" type=\"submit\" />\n";
else echo "<input value=\"Обновить\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

// Иконка клана //
case 'icon':
if (isset($_FILES['file']))
{

if (eregi('\.jpe?g$',$_FILES['file']['name']) && $imgc=@imagecreatefromjpeg($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>12 || imagesy($imgc)>12)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=12; // ширина
$dstH=12; // высота 
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=12;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=12;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."files/klanico/$id.jpg",0777);
@unlink(H."files/klanico/$id.jpg");
imagejpeg($screen,H."files/klanico/$id.jpg",100);
@chmod(H."files/klanico/$id.jpg",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."files/klanico/$id.jpg");
}

msg("Логотип успешно установлен");
}
}
echo "<div class='rowdown'>\n"; 
echo "Текущая иконка:<br/>\n";
if(is_file(H.'files/klanico/'.$id.'.jpg')){
echo '<img src="/files/klanico/'.$id.'.jpg" alt=""/><br/>';
}
else
{
echo 'Не установлена<br/>';
}
echo "</div>\n";
echo "<div class='rowup'>\n"; 
echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"?id=$id&amp;act=icon\">\n";
echo "Выберите иконку:<br />\n";
echo "<input type='file' name='file' accept='image/jpeg' /><br />\n";
if(!is_file(H.'files/klanico/'.$id.'.jpg'))echo "<input value=\"Загрузить\" type=\"submit\" />\n";
else echo "<input value=\"Обновить\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'rules':
if (isset($_POST['msg']))
{
$msg=esc($_POST['msg']);

if (strlen2($msg)>1024){$err='Содержиние правил слишком большое';}
if (strlen2($msg)<2){$err='Правила слишком короткие';}

$msg=mysql_real_escape_string($msg);

if (!isset($err)){
mysql_query("UPDATE `clan` SET `rules` = '".$msg."' WHERE `id` = '$id' LIMIT 1");
msg('Правила изменены');
}
}

$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=rules\">\n";
echo "Текст правил:<br />\n<textarea name=\"msg\" >$clan[rules]</textarea><br />\n";
echo "<input value=\"Готово\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'about':
if (isset($_POST['msg']))
{
$msg=esc($_POST['msg']);

if (strlen2($msg)>1024){$err='Описание слишком большое';}
if (strlen2($msg)<2){$err='Описание слишком короткие';}

$msg=mysql_real_escape_string($msg);

if (!isset($err)){
mysql_query("UPDATE `clan` SET `about` = '".$msg."' WHERE `id` = '$id' LIMIT 1");
msg('Описание изменено');
}
}

$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=about\">\n";
echo "Текст правил:<br />\n<textarea name=\"msg\" >$clan[about]</textarea><br />\n";
echo "<input value=\"Готово\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'bank':
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));

if(!@$_POST['up']){
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=bank\">\n";
echo "Сколько баллов раздать? (по умолчанию все):<br />\n";
echo "<input type=\"text\" name=\"bank\" value=\"$clan[bank]\"/><br />\n";
echo "<input name=\"up\" value=\"Готово\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";
}
else
{
$count = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$id' AND `activaty` = '0'"),0);
$bank = intval($_POST['bank']);
if ($bank>$clan['bank']) $err = 'Недостаточно баллов в банке клана!';

if (!isset($err)){
$balls = intval($bank/$count);
$q = mysql_query("SELECT * FROM `clan_user` WHERE `id_clan` = '$id' AND `activaty` = '0'");
while ($post = mysql_fetch_array($q))
{
mysql_query("UPDATE `user` SET `balls` = balls+'$balls' WHERE `id` = '$post[id_user]' LIMIT 1");
mysql_query("UPDATE `clan` SET `bank` = '".intval($clan['bank']-$bank)."' WHERE `id` = '$id' LIMIT 1");
$msg="Банк был распределён на всех пользователей! Каждому пользователю клана досталось по $balls баллов!";
mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$post[id_user]', '$msg', '$time')");
}
$msg2="Банк был распределён на всех пользователей! Каждому пользователю клана досталось по $balls баллов!";
mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', '$msg2', '$time')");
msg ('Банк успешно распределен');
}
err();
}
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'priz':
if (isset($_POST['pr']))
{
$pr=intval($_POST['pr']);

mysql_query("UPDATE `clan` SET `priz` = '$pr' WHERE `id` = '$id' LIMIT 1");
msg('Изменено');
}

$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=priz\">\n";
echo "Приз за поднятие клана:<br />\n";
echo "<input type=\"text\" name=\"pr\" value=\"$clan[priz]\"/><br />\n";
echo "<input value=\"Готово\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'delpost':

mysql_query("DELETE FROM `clan_chat` WHERE `id` = '".intval($_GET['del'])."' AND `id_clan` = '$id' LIMIT 1");
header("Location: chat.php?".SID);
break;

case 'activate':

if (isset($_GET['yes']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id` = '".intval($_GET['yes'])."' AND `activaty` = '1' LIMIT 1",$db), 0)==1)
{
$uid = intval($_GET['yes']);
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id` = $uid LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $us[id_user] LIMIT 1"));

	$msg="Пользователь [url=/info.php?id=$ank[id]]$ank[nick][/url] вступил(а) в клан!";
	mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', '$msg', '$time')");
	
	$msg2="Вас приняли в клан [b]$clan[name][/b]!";
	mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$ank[id]', '$msg2', '$time')");
	
	mysql_query("UPDATE `clan_user` SET `activaty` = '0' WHERE `id` = '$uid' LIMIT 1");
}

if (isset($_GET['no']))
{
$uid = intval($_GET['no']);
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id` = $uid LIMIT 1"));
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $us[id_user] LIMIT 1"));
	
	$msg2="Вам отказали вовступлении в клан [b]$clan[name][/b]!";
	mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$ank[id]', '$msg2', '$time')");
	
mysql_query("DELETE FROM `clan_user` WHERE `id_clan` = '$id' AND `id_user` = '$ank[id]' LIMIT 1");
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE  `id_clan` = '$id' AND `activaty` = '1'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo "<div class='rowdown'>"; 
echo "Нет новых участников ожидающих активации.\n";
echo "</div>"; 
}

$q = mysql_query("SELECT * FROM `clan_user` WHERE  `id_clan` = '$id' AND `activaty` = '1' ORDER BY `time` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

if($num==1){ echo "<div class='rowup'>";
$num=0;}
else
{echo "<div class='rowdown'>";
$num=1;}



echo "   <tr>\n";
if ($set['set_show_icon']==2){
	avatar($ank['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
	echo "  <td class='icon14'>\n";
	echo "".status($ank['id'])."";
	echo "  </td>\n";
}

echo "  <td class='p_t'>\n";
echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "".medal($ank['id'])." ".online($ank['id'])." <br />";
echo "Рейтинг: <b>$ank[rating]</b><br />\n";
echo "  </td>\n";
echo "   </tr>\n";

echo "[<a href='adminka.php?act=activate&amp;id=$id&amp;yes=$post[id]'>Приянть</a>]\n";
echo "[<a href='adminka.php?act=activate&amp;id=$id&amp;no=$post[id]'>Отказать</a>]\n";
echo'</div>'; 
}

break;


case 'set':
if (isset($_POST['msg']))
{
$msg=esc($_POST['msg']);

if (strlen2($msg)>50){$err='Название слишком большое';}
if (strlen2($msg)<2){$err='Название слишком короткие';}

$msg=mysql_real_escape_string($msg);
$all=intval($_POST['all']);

if (!isset($err)){
mysql_query("UPDATE `clan` SET `name` = '".$msg."' , `all` = '".$all."' WHERE `id` = '$id' LIMIT 1");
msg('Описание изменено');
}
}

$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?id=$id&amp;act=set\">\n";
echo "Название клана:<br />\n<input type='text' name='msg' value='$clan[name]'/><br />\n";
echo "Вступление в клан:<br/>\n<select name='all'>\n";
echo "<option value='0'".($clan['all']==0?" selected='selected'":null).">Свободное</option>\n";
echo "<option value='1'".($clan['all']==1?" selected='selected'":null).">С активацией</option>\n";
echo "</select><br />\n";
echo "<input value=\"Сохранить\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'modyes':
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
$us = intval($_GET['user']);

mysql_query("UPDATE `clan_user` SET `level` = '1' WHERE `id` = '$us' LIMIT 1");

msg ('Права моретора выданы');
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'modno':
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
$us = intval($_GET['user']);

mysql_query("UPDATE `clan_user` SET `level` = '0' WHERE `id` = '$us' LIMIT 1");

msg ('Модератор снят');
echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

case 'balls':
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '".intval($_GET['user'])."' LIMIT 1"));
if (isset($_POST['balls']))
{
$balls=intval($_POST['balls']);
if ($balls > $clan['bank']) {$err='Недостаточно баллов в банке клана';}
else{
mysql_query("UPDATE `user` SET `balls` = '".($ank['balls']+$balls)."' WHERE `id` = '$ank[id]' LIMIT 1");
mysql_query("UPDATE `clan` SET `bank` = '".($clan['bank']-$balls)."' WHERE `id` = '$id' LIMIT 1");
mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', 'Пользователю [url=/info.php?id=$ank[id]]$ank[nick][/url] выдано [b]".$balls."[/b] баллов из банка клана!', '$time')");
mysql_query("INSERT INTO `jurnal` (`id_kont`, `msg`, `time`) values('$ank[id]', 'Вам начислено [b]".$balls."[/b] баллов от администрации клан [b]$clan[name][/b]!', '$time')");
}
}
 err();
echo "<div class='rowdown'>\n"; 
echo "<form method=\"post\" action=\"?act=balls&amp;id=$id&amp;user=$ank[id]\">\n";
echo "Колличесво баллов:<br />\n";
echo "Доступно: $clan[bank]<br />\n";
echo "<input type='text' name='balls' value=''/><br />\n";
echo "<input value=\"Выдать\" type=\"submit\" />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class='str'>";
echo "<a href='/klan/adminka.php?id=$id'>Админка клана</a><br/>\n";
echo "</div>\n";
break;

default:
echo "<div class='rowdown'>\n"; 
echo "<a href='?id=$id&amp;act=news'>Новости</a><br/>\n"; 
echo "<a href='?id=$id&amp;act=logo'>Логотип</a><br/>\n"; 
echo "<a href='?id=$id&amp;act=icon'>Иконка</a><br/>\n"; 
echo "<a href='?id=$id&amp;act=rules'>Правила</a><br/>\n"; 
echo "<a href='?id=$id&amp;act=about'>Описание</a><br/>\n";
echo "<a href='?id=$id&amp;act=activate'>Активация участников</a><br/>\n"; 
echo "<a href='?id=$id&amp;act=bank'>Раздать банк участникам</a><br/>\n";
echo "<a href='?id=$id&amp;act=priz'>Приз за поднятие</a><br/>\n";
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
if($clan['bank']<500000)echo "<a href='updat.php?id=$id'>Обновить банк</a><br/>\n";
echo "<a href='?id=$id&amp;act=set'>Настройки клана</a><br/>\n";
echo "</div>\n";
break;
}
}
else
{
echo "<div class='rowdown'>\n"; 
echo "Вы не админ данного клана!\n";  
echo "</div>\n";
}
echo "<div class='str'>";
echo "<a href='/klan/myklan.php'>Мой клан</a><br/>\n";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>