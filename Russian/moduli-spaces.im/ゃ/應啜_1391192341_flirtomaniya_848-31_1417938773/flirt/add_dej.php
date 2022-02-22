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
$set['title']='Админка - Добавить действие';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header("Location: /flirt/index.php");
exit;
}
if (isset($_POST['add']))
{
$name=my_esc($_POST['name']);
$cena=intval($_POST['cena']);
if (strlen2($name)<2)
{
$err='Короткое название!';
}
if (!$cena|| $cena<1)
{
$err='Укажите стоимость!';
}
if (!isset($err))
{
mysql_query("INSERT INTO `flirt_flirt` (`name`, `cena`) VALUES ('".$name."',  '".$cena."')");
echo "<div class='msg'>";
echo "Действие успешно добавлено!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?$passgen'>";
echo "<div class='p_m'>";
echo "<b>Назание:</b><br />";
echo "<input type='text' name='name' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Стоимость:</b><br />";
echo "<input type='text' name='cena' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<input type='submit' name='add' value='Добавить' />";
echo "</div>";
echo "</form>";
echo "<a href='dej_flirt.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>