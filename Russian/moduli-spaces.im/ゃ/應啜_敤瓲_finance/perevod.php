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
only_reg();
$set['title']='Перевод VM'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();


$usearch=NULL;
if (isset($_SESSION['usearch']))$usearch=$_SESSION['usearch'];
if (isset($_POST['usearch']))$usearch=$_POST['usearch'];

if ($usearch==NULL)
unset($_SESSION['usearch']);
else
$_SESSION['usearch']=$usearch;
$usearch=ereg_replace("( ){1,}","",$usearch);



$balls=NULL;
if (isset($_SESSION['balls']))$balls=$_SESSION['balls'];
if (isset($_POST['balls']))$balls=$_POST['balls'];

if ($balls==NULL)
unset($_SESSION['balls']);
else
$_SESSION['balls']=$balls;
$balls=ereg_replace("( ){1,}","",$balls);


if (isset($_GET['go']))
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `nick` like '%".mysql_escape_string($usearch)."%'"));

if($ank==0)
{
msg ('Пользователь не найден');
}
else
{
if (isset($user) & $user['balls']<=$balls)
{
msg ('У Вас не достаточно денег');
}
else
{

mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$balls)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `balls` = '".($ank['balls']+$balls)."' WHERE `id` = '$ank[id]' LIMIT 1");
mysql_query("INSERT INTO `fin_oper` (`user`, `oper`, `time`, `cena`) values('$user[id]', 'Отправлено $balls VM для $ank[nick]', '$time', '$balls')",$db);
mysql_query("INSERT INTO `fin_in` (`user`, `oper`, `time`, `cena`) values('$ank[id]', 'Получено $balls VM от $user[nick]', '$time', '$balls')",$db);

$msgrat="На ваш счет поступил перевод [url=http://vtakte.kz/finance/in.php]Подробнее[/url]";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$msgrat', '$time')");

msg ('Перевод денег успешно завершено');
}
}
include_once '../sys/inc/tfoot.php';
}
else
{ 

echo "<form method=\"post\" action=\"perevod.php?go\">";
echo "Ник получателя<br/><input type=\"text\" name=\"usearch\" maxlength=\"16\" value=\"$usearch\" /><br/>\n";
echo "Количество денег:<br/>";
echo "<input type=\"text\" name=\"balls\" value=\"\"/><br />\r\n";
echo "<input type=\"submit\" value=\"Найти\" />";
echo "</form>\n";


include_once '../sys/inc/tfoot.php';}
?>