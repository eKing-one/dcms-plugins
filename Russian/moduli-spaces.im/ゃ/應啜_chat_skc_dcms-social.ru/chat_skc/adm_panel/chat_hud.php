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
only_level();

include_once '../sys/inc/thead.php';
title();

aut();

if (isset($_GET['save']))
{

$vopros=mysql_real_escape_string($_POST['vopros']);
$otvet=mysql_real_escape_string($_POST['otvet']);

if (strlen2($vopros)<3)$err='Слишком короткое слово';
if (strlen2($otvet)<3)$err='Слишком короткое слово';
if (strlen2($vopros)>320)$err='Слишком днинное слово';
if (strlen2($otvet)>32)$err='Слишком днинное слово';


if (mysql_result(mysql_query("SELECT COUNT(*) FROM `hud_vopros` WHERE `otvet` = '$otvet' LIMIT 1"),0)!=0){$err='такое слово уже есть в базе';}

if (!isset($err)){

$vopr = '[img]'.$vopros.'[/img]';
mysql_query("INSERT INTO `hud_vopros` (`vopros`, `otvet`) values('$vopr', '$otvet')");
header('Location: ?ok');
}
}

if(isset($_GET['ok']))msg('Слово добавлено в базу');
err();	
echo "<form method=\"post\" action=\"?save\">\n";
echo "URL адрес картинки <br />\n";
echo "<input name=\"vopros\" type=\"text\" maxlength='320' value='' /><br />\n";
echo "Ответ с маленькой буквы без пробелов<br/>";
echo "<input name=\"otvet\" type=\"text\" maxlength='32' value='' /><br />\n";


echo "<input value=\"Добавить\" type=\"submit\" /><br />\n";
echo "</form>\n";

include_once '../sys/inc/tfoot.php';
?>
