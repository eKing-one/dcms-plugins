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

$slovo=mysql_real_escape_string($_POST['slovo']);

if (strlen2($slovo)<3)$err='Слишком короткое слово';
if (strlen2($slovo)>32)$err='Слишком днинное слово';
if (!preg_match("#^([A-zА-я0-9\-\_\(\)\ ])+$#ui",$slovo))$err='В слове присутствуют запрещенные символы';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_anagrama` WHERE `vopros` = '$slovo' LIMIT 1"),0)!=0){$err='такое слово уже есть в базе';}

if (!isset($err)){

mysql_query("INSERT INTO `chat_anagrama` (`vopros`, `otvet`) values('$slovo', '$slovo')");
header('Location: /adm_panel/anagrama.php?ok');
}
}

if(isset($_GET['ok']))msg('Слово добавлено в базу');
err();	
echo "<form method=\"post\" action=\"?save\">\n";
echo "Слово<br />\n";
echo "<input name=\"slovo\" type=\"text\" maxlength='32' value='' /><br />\n";

echo "<input value=\"Добавить\" type=\"submit\" /><br />\n";
echo "</form>\n";

include_once '../sys/inc/tfoot.php';
?>