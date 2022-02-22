<?
/*
*autor: romanvht
*e-mail: romanvht@spaces.ru
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';
user_access('adm_panel_show',null,'/index.php?'.SID);
adm_check();
$set['title']='Учим бота!';
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($_POST['save'])){

$fraza=mysql_real_escape_string(htmlspecialchars($_POST['fraza']));
$otvet=mysql_real_escape_string(htmlspecialchars($_POST['otvet']));
if(strlen2($fraza)<2)$err="Слишком короткая фраза";
if(strlen2($fraza)>100)$err="Слишком длинная фраза";
if(strlen2($otvet)<2)$err="Слишком короткий ответ";
if(strlen2($otvet)>100)$err="Слишком длинный ответ";
if(!isset($err)){
mysql_query("INSERT INTO `bot` (`fraza`, `otvet`, `time`) VALUES ('".$fraza."', '".$otvet."', '".$time."')");
msg('Бот стал немного умней');
}
}
err();

echo "<div class='page_foot'>";
echo "<form action=\"?\" method=\"post\">";



echo "Фраза/Слово:<br />\n";
echo "<input type='text' name='fraza' /><br />\n";



echo "Ответ бота:<br />\n";
echo "<input type='text' name='otvet' /><br />\n";

echo "<input value=\"Сохранить\" type=\"submit\" name='save' />\n";
echo "</form>\n";
echo "</div>\n";

switch($_GET[add]){
default:
echo "<div class='page_foot'>";
echo "<form action=\"?add=bot_d\" method=\"post\">";
echo "Случайная фраза бота:<br />\n";
echo "<input type='text' name='bot_d' /><br />\n";
echo "<input value=\"Сохранить\" type=\"submit\" name='save' />\n";
echo "</form>\n";
echo "</div>\n";
break;

case 'bot_d':
echo "<div class='page_foot'>";
$bot_d=mysql_real_escape_string(htmlspecialchars($_POST['bot_d']));
mysql_query("INSERT INTO `bot_d` (`post`) VALUES ('".$bot_d."')");
echo 'Случайная фраза добавлена в мозг бота';
echo "&laquo; <a href='?'>Назад</a><br />\n";
echo "</div>\n";
break;
}
if (user_access('adm_panel_show')){
echo "<div class='guser_aut'>\n";
echo "&laquo; <a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";
}
include_once '../sys/inc/tfoot.php';

?>
