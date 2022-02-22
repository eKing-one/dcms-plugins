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
$set['title']='Админка - Добавить вопрос';
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
$vopros=my_esc($_POST['vopros']);
$variant_1=my_esc($_POST['variant_1']);
$variant_2=my_esc($_POST['variant_2']);
$variant_3=my_esc($_POST['variant_3']);
if (strlen2($vopros)<2)
{
$err='Короткий вопрос!';
}
if (strlen2($variant_1)<2)
{
$err='Короткий первый вариант ответа!';
}
if (strlen2($variant_2)<2)
{
$err='Короткий второй вариант ответа!';
}
if (strlen2($variant_3)<2)
{
$err='Короткий третий вариант ответа!';
}
if (!isset($err))
{
mysql_query("INSERT INTO `flirt_voprosu` (`vopros`, `variant_1`, `variant_2`, `variant_3`) VALUES ('".$vopros."',  '".$variant_1."', '".$variant_2."', '".$variant_3."')");
echo "<div class='msg'>";
echo "Вопрос успешно добавлен!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?$passgen'>";
echo "<div class='p_m'>";
echo "<b>Вопрос:</b><br />";
echo "<input type='text' name='vopros' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №1:</b><br />";
echo "<input type='text' name='variant_1' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №2:</b><br />";
echo "<input type='text' name='variant_2' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №3:</b><br />";
echo "<input type='text' name='variant_3' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<input type='submit' name='add' value='Добавить' />";
echo "</div>";
echo "</form>";
echo "<a href='voprosu.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>