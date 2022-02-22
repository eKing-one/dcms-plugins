<?
/*
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
$set['title']='Завести Ребёнка';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
$b=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby` WHERE `mama` = '".$user['id']."' OR `papa` = '".$user['id']."' LIMIT 1"));
if ($b)
{
echo "<div class='err'>";
echo "У вас уже есть ребёнок!";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_POST['save']))
{
if (strlen2($_POST['name'])<=2)
{
$err='Короткое имя!';
}
if (strlen2($_POST['name'])>=100)
{
$err='Длинное имя!';
}
$name=my_esc($_POST['name']);
if (isset($_POST['pol']) && ($_POST['pol']==0 || $_POST['pol']==1))
{
$pol=intval($_POST['pol']);
}else{
$err='Ошибка в поле "Пол ребёнка"!';
}
if (!isset($err))
{
if ($user['pol']==0)
{
mysql_query("INSERT INTO `baby` (`name`, `pol`, `mama`, `health`, `happy`, `eda`, `health_time`, `happy_time`, `eda_time`, `time`) VALUES ('".$name."', '".$pol."', '".$user['id']."', '100', '100', '100', '".time()."', '".time()."', '".time()."', '".time()."')");
}else{
mysql_query("INSERT INTO `baby` (`name`, `pol`, `papa`, `health`, `happy`, `eda`, `health_time`, `happy_time`, `eda_time`, `time`) VALUES ('".$name."', '".$pol."', '".$user['id']."', '100', '100', '100', '".time()."', '".time()."', '".time()."', '".time()."')");
}
echo "<div class='msg'>";
echo "Поздравляем, теперь у вас есть ребёнок!";
echo "</div>";
echo "<a href='my_baby.php'><div class='main2'>";
echo "<img src='img/m_1.png' width='16' alt='Simptom'> Мой Ребёнок";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
}
err();
echo "<form method='post' name='message' action='?".$passgen."'>";
echo "<b>Имя ребёнка:</b><br />";
echo "<input type='text' name='name' maxlength='100' /><br />";
echo "<b>Пол ребёнка:</b><br />";
echo "<select name='pol'>";
echo "<option value='0'>Девочка</option><br />";
echo "<option value='1'>Мальчик</option><br />";
echo "</select><br />";
echo "<input type='submit' name='save' value='Готово' />";
echo "</form>";
echo "<a href='det_dom.php'><div class='main2'>";
echo "<img src='img/home.png' alt='Simptom'> Взять с детдома";
echo "</div></a>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>