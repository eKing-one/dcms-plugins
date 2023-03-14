<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

only_reg();

$reit=mysql_fetch_assoc(mysql_query("SELECT * FROM `million_reit` WHERE `id_user` = '$user[id]' LIMIT 1"));

if (isset($_POST['ok'])) {
if (isset($_POST['balls'])) {
if ($_POST['balls']<0) $err[]='Не верная сума баллов';
elseif ($_POST['balls']>$reit['balls_score']/10) $err[]='На ващем счету нет столько срецтв';
if (!isset($err)) {
mysql_query("UPDATE `million_reit` SET `balls_score` = '".($reit['balls_score']-intval($_POST['balls'])*10)."' WHERE `id` = '$reit[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+intval($_POST['balls']))."' WHERE `id` = '$user[id]' LIMIT 1");
$msg='Вам начислено '.intval($_POST['balls']).' баллов';
}
}
elseif (isset($_POST['rub'])) {
if ($_POST['rub']<0) $err[]='Не верная сума рублей';
elseif ($_POST['rub']>$reit['balls_score']) $err[]='На ващем счету нет столько срецтв';
if (!isset($err)) {
$balls=$_POST['rub']/10;
$balls=(int)$balls;
mysql_query("UPDATE `million_reit` SET `balls_score` = '".($reit['balls_score']-$balls*10)."' WHERE `id` = '$reit[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+$balls)."' WHERE `id` = '$user[id]' LIMIT 1");
$msg='Вам начислено '.($user['balls']+$balls).' баллов';
}
}
$reit=mysql_fetch_assoc(mysql_query("SELECT * FROM `million_reit` WHERE `id_user` = '$user[id]' LIMIT 1"));
}

$set['title']='Обналичить деньги';
include '../../sys/inc/thead.php';
title();
err();
if (isset($msg)) msg($msg);
aut();

msg('Вам доступно '.$reit['balls_score'].' рублей');

echo "<div class='main_menu'>";
echo "Курс обмена равен 10 рублей/1 балл";
echo "</div>";

echo "<form class='p_m' method='post' action=''>";
echo "Баллы:<br/>\n";
echo "<input type='text' name='balls' value='".($reit['balls_score']/10)."'/><br/>\n";
echo "<input type='submit' name='ok' value='Снять'/>\n";
echo "</form>";

echo "<form class='p_m' method='post' action=''>";
echo "Рубли:<br/>\n";
echo "<input type='text' name='rub' value='$reit[balls_score]'/><br/>\n";
echo "<input type='submit' name='ok' value='Снять'/>\n";
echo "</form>";

echo "<div class='foot'>";
echo "&laquo; <a href='index.php'>В игру</a><br/>\n";
echo "</div>";
include '../../sys/inc/tfoot.php';
exit();
?>