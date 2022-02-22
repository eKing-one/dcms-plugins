<?
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
$set['title']='Робот флирта';
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
mysql_query("INSERT INTO `flirt` (`fraza`, `otvet`, `time`) VALUES ('".$fraza."', '".$otvet."', '".$time."')");
msg('Бот стал немного умней');
}
}
err();
echo "<form action=\"?\" method=\"post\">";



echo "Фраза:<br />\n";
echo "<input type='text' name='fraza' /><br />\n";



echo "Ответ бота:<br />\n";
echo "<input type='text' name='otvet' /><br />\n";

echo "<input value=\"Сохранить\" type=\"submit\" name='save' />\n";
echo "</form>\n";


if (user_access('adm_panel_show')){
echo "<div class='foot'>\n";
echo "&laquo;<a href='/adm_panel/'>В админку</a><br />\n";
echo "</div>\n";
}
include_once '../sys/inc/tfoot.php';

?>

