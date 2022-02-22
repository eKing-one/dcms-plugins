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
$set['title']='Настройка фона';
include_once '../sys/inc/thead.php';
title();
if (isset($_POST['save']))
{
if (isset($_POST['fon_info_pokaz']) && ($_POST['fon_info_pokaz']==0 || $_POST['fon_info_pokaz']==1))
{
$user['fon_info_pokaz']=intval($_POST['fon_info_pokaz']);
mysql_query("UPDATE `user` SET `fon_info_pokaz` = '$user[fon_info_pokaz]' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Ошибка!';
if (isset($_POST['fon_anketa_pokaz']) && ($_POST['fon_anketa_pokaz']==0 || $_POST['fon_anketa_pokaz']==1))
{
$user['fon_anketa_pokaz']=intval($_POST['fon_anketa_pokaz']);
mysql_query("UPDATE `user` SET `fon_anketa_pokaz` = '$user[fon_anketa_pokaz]' WHERE `id` = '$user[id]' LIMIT 1");
}
else $err='Ошибка!';
if (!isset($err))msg('Изменения успешно приняты');
}
err();
aut();
echo "<form method='post' action='?$passgen'>\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Фон личной странички виден:<br />\n<select name='fon_info_pokaz'>\n";
echo "<option value='0'".($user['fon_info_pokaz']==0?" selected='selected'":null).">Всем</option>\n";
echo "<option value='1'".($user['fon_info_pokaz']==1?" selected='selected'":null).">Только друзьям</option>\n";
echo "</select><br />\n";
echo '<img src="/fon/ico/ico.png" alt=""/> ';
echo "Фон анкеты виден:<br />\n<select name='fon_anketa_pokaz'>\n";
echo "<option value='0'".($user['fon_anketa_pokaz']==0?" selected='selected'":null).">Всем</option>\n";
echo "<option value='1'".($user['fon_anketa_pokaz']==1?" selected='selected'":null).">Только друзьям</option>\n";
echo "</select><br />\n";
echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form>\n";
echo "<div class='foot'>\n";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
{
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo "<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
}
echo '<img src="/fon/ico/go.gif" alt=""/> ';
echo "<a href='umenu.php'>Мое меню</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>