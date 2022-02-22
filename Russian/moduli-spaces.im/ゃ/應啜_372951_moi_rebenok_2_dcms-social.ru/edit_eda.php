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
$set['title']='Редактировать еду';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header('Location: index.php');
exit;
}
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$dd=mysql_fetch_assoc(mysql_query("SELECT * FROM `baby_shop_eda` WHERE `id` = '".$it."' LIMIT 1"));
}
if (!$dd || $dd['id']==0)
{
echo "<div class='err'>";
echo "Еда не найдена!";
echo "</div>";
echo "<a href='shop.php?eda'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_POST['save']))
{
if (strlen2($_POST['name'])<=2)
{
$err='Короткое название!';
}
if (strlen2($_POST['name'])>=100)
{
$err='Длинное название!';
}
$name=my_esc($_POST['name']);
if (isset($_POST['cena']) && is_numeric($_POST['cena']) && $_POST['cena']>=1 && $_POST['cena']!=NULL)
{
$cena=intval($_POST['cena']);
}else{
$err='Ошибка в поле "Цена"!';
}
if (isset($_POST['health']) && is_numeric($_POST['health']) && $_POST['health']>=1 && $_POST['health']!=NULL)
{
$health=intval($_POST['health']);
}else{
$err='Ошибка в поле "Здоровье и сытость"!';
}
if (!isset($err))
{
mysql_query("UPDATE `baby_shop_eda` SET `name` = '".$name."' WHERE `id` = '".$dd['id']."' LIMIT 1");
mysql_query("UPDATE `baby_shop_eda` SET `cena` = '".$cena."' WHERE `id` = '".$dd['id']."' LIMIT 1");
mysql_query("UPDATE `baby_shop_eda` SET `health` = '".$health."' WHERE `id` = '".$dd['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Еда успешно сохранена!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?id=".$dd['id']."'>";
echo "<b>Название:</b><br />";
echo "<input type='text' name='name' value='".$dd['name']."' maxlength='100' /><br />";
echo "<b>Цена:</b><br />";
echo "<input type='text' name='cena' value='".$dd['cena']."' maxlength='5' /><br />";
echo "<b>+ здоровье и сытость:</b><br />";
echo "<input type='text' name='health' value='".$dd['health']."' maxlength='5' /><br />";
echo "<input type='submit' name='save' value='Сохранить' />";
echo "</form>";
echo "<a href='shop.php?eda'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>