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
$set['title']='Добавить еду';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/user.php';
if ($user['level']<=3)
{
header('Location: index.php');
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
mysql_query("INSERT INTO `baby_shop_eda` (`name`, `cena`, `health`) VALUES ('".$name."', '".$cena."', '".$health."')");
$ii=mysql_insert_id();
if (preg_match('#\.jpe?g$#i',$_FILES['foto']['name']) || preg_match('#\.gif$#i',$_FILES['foto']['name']) || preg_match('#\.png$#i',$_FILES['foto']['name']))
{
copy($_FILES['foto']['tmp_name'], H."baby/shop_eda/".$ii.".png");
@chmod(H."baby/shop_eda/".$ii.".png" , 0777);
}
echo "<div class='msg'>";
echo "Еда успешно добавлена!";
echo "</div>";
}
}
err();
echo "<form method='post' enctype='multipart/form-data' action='?".$passgen."'>";
echo "<b>Фотография:</b><br />";
echo "<input name='foto' accept='image/*,image/png,image/gif,image/jpg' type='file' /><br />";
echo "<b>Название:</b><br />";
echo "<input type='text' name='name' maxlength='100' /><br />";
echo "<b>Цена:</b><br />";
echo "<input type='text' name='cena' maxlength='5' /><br />";
echo "<b>+ здоровья и сытости:</b><br />";
echo "<input type='text' name='health' maxlength='5' /><br />";
echo "<input type='submit' name='save' value='Добавить' />";
echo "</form>";
echo "<a href='shop.php?eda'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
?>