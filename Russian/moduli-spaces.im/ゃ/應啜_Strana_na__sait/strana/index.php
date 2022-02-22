<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Редактирование Страны'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
err();
aut();
/////////////////////Запись в профиль
if (isset($_POST['strana_icon']) && isset($_POST['strana_text']) && isset($user))
{
$msg=my_esc($_POST['strana_text']);
$icon=intval($_POST['strana_icon']);
if (strlen2($msg)>70){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<1){$err[]='Короткое сообщение';}
elseif(!isset($err)){
mysql_query("UPDATE `user` SET `strana_icon` = '".$icon."' WHERE `id` = '".$user['id']."' LIMIT 1");
mysql_query("UPDATE `user` SET `strana_text` = '".$msg."' WHERE `id` = '".$user['id']."' LIMIT 1");
msg('Страна добавлена ');}}
//////////////////////////////////////////////
echo'<form action="?" method="post">';
echo'<input type="radio" name="strana_icon" value="1"/><img src="/strana/icon/1.gif" /> ';
echo'<input type="radio" name="strana_icon" value="2"/><img src="/strana/icon/2.gif" /> ';
echo'<input type="radio" name="strana_icon" value="3"/><img src="/strana/icon/3.gif" />';
echo' <input type="radio" name="strana_icon" value="4"/><img src="/strana/icon/4.gif" /> ';
echo' <input type="radio" name="strana_icon" value="5"/><img src="/strana/icon/5.gif" /> ';
echo' <input type="radio" name="strana_icon" value="6"/><img src="/strana/icon/6.gif" /> <br />';
echo' <input type="radio" name="strana_icon" value="7"/><img src="/strana/icon/7.gif" /> ';
echo' <input type="radio" name="strana_icon" value="8"/><img src="/strana/icon/8.gif" /> ';
echo' <input type="radio" name="strana_icon" value="9"/><img src="/strana/icon/9.gif" /> ';
echo' <input type="radio" name="strana_icon" value="10"/><img src="/strana/icon/10.gif" /> ';
echo' <input type="radio" name="strana_icon" value="11"/><img src="/strana/icon/11.gif" /> ';
echo' <input type="radio" name="strana_icon" value="12"/><img src="/strana/icon/12.gif" /> <br />';
echo' <input type="radio" name="strana_icon" value="13"/><img src="/strana/icon/13.gif" /> ';
echo' <input type="radio" name="strana_icon" value="14"/><img src="/strana/icon/14.gif" /> ';
echo' <input type="radio" name="strana_icon" value="15"/><img src="/strana/icon/15.gif" /> <br />';
 echo'Ваша страна: <input type="text" name="strana_text" maxlength="70" value="'.$user['strana_text'].'"/>  <br />';
  echo'<input type="submit" value="Добавить"/>';
include_once '../sys/inc/tfoot.php'; ////////////Niz
?>