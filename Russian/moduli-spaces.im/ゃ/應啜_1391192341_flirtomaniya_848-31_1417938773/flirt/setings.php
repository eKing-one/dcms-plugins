<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Настройки';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if (isset($_POST['save']))
{
if (isset($_POST['flirt_pokaz']) && ($_POST['flirt_pokaz']==0 || $_POST['flirt_pokaz']==1))
{
$flirt_pokaz=intval($_POST['flirt_pokaz']);
mysql_query("UPDATE `user` SET `flirt_pokaz` = '".$flirt_pokaz."' WHERE `id` = '".$user['id']."' LIMIT 1");
}else{
$err='Ошибка в поле "Участвовать в флиртомании"!';
}
if (isset($_POST['flirt_izv']) && ($_POST['flirt_izv']==0 || $_POST['flirt_izv']==1))
{
$flirt_izv=intval($_POST['flirt_izv']);
mysql_query("UPDATE `user` SET `flirt_izv` = '".$flirt_izv."' WHERE `id` = '".$user['id']."' LIMIT 1");
}else{
$err='Ошибка в поле "Прислать извещения в почту"!';
}
if (!isset($err))
{
msg('Настройки успешно сохранены!');
}
}
err();
echo "<form method='post' action='?$passgen'>";
echo "<div class='p_m'>";
echo "<b>Участвовать в флиртомании:</b><br />";
echo "<select name='flirt_pokaz'>";
echo "<option value='0'".($user['flirt_pokaz']==0?" selected='selected'":null).">Да</option><br />";
echo "<option value='1'".($user['flirt_pokaz']==1?" selected='selected'":null).">Нет</option><br />";
echo "</select><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Прислать извещения в почту:</b><br />";
echo "<select name='flirt_izv'>";
echo "<option value='0'".($user['flirt_izv']==0?" selected='selected'":null).">Да</option><br />";
echo "<option value='1'".($user['flirt_izv']==1?" selected='selected'":null).">Нет</option><br />";
echo "</select><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<input type='submit' name='save' value='Сохранить' />";
echo "</div>";
echo "</form>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>