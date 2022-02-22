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
$set['title']='Жалоба на страницу';
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($user) && isset($_POST['ok'])){
$msg=my_esc($_POST['msg']);
$link=my_esc($_POST['link']);
$prich=my_esc($_POST['prich']);
if (strlen2($prich)==0)$err[]="Не верная причина";
if (strlen2($link)<3)$err[]="Не правильная ссылка";
if (strlen2($link)>64)$err[]="Длинная ссылка";
if (strlen2($msg)<3)$err[]="Короткое сообщение, подробнее поищите жалобу.";
if (strlen2($msg)>1024)$err[]="Длинное сообщение. Пишите меньше в описание жалобы.";
if (!isset($err)){
mysql_query("INSERT INTO `jaloby` (`id_user`, `prich`, `msg`, `time`, `link`) values('$user[id]', '".mysql_real_escape_string($prich)."', '$msg', '$time', '".$link."')");
msg('Жалоба успешно отправлена ');
include_once '../sys/inc/tfoot.php';
}
}
msg("<font color='red'>Внимание!!!</font> За ложную жалобу ваш ник может быть заблокирован!<br/><font color='red'>!!!</font>Подробнее опишите жалобу чтобы администрации было проще разобратся.");
echo "<form method='post' action='?$passgen'>\n";
echo "<b>Ссылка: </b><br/><input type='text' name='link' value='".htmlspecialchars($_SERVER['HTTP_REFERER'])."' style='width:90%' readonly='readonly'><br/>";
echo "<b>Причина:</b><br/><select name='prich' style='width:90%'>";
echo "<option name='СПАМ / реклама'>СПАМ / реклама</option>";
echo "<option name='Мат или оскарбление'>Мат или оскарбление</option>";
echo "<option name='Мошенничество'>Мошенничество</option>";
echo "<option name='Ошибка на сайте'>Ошибка на сайте</option>";
echo "<option name='Другая причина'>Другая причина</option>";
echo "</select><br/>";
echo "<b>Описание жалобы :</b><br/><textarea name='msg'></textarea>";
echo "<input type='submit' name='ok' value='Отправить жалобу'><a href='". htmlspecialchars($_SERVER['HTTP_REFERER'])."'>Отмена</a></form>";
echo "$link";
include_once '../sys/inc/tfoot.php';
?>