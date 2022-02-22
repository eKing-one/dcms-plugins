<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$dej=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_flirt` WHERE `id` = '".$it."' LIMIT 1"));
if (!$dej || $dej['id']==0)
{
echo "<div class='err'>";
echo "Ошибка!";
echo "</div>";
echo "<a href='dej_flirt.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['edit']))
{
if (isset($_POST['save']))
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
mysql_query("UPDATE `flirt_flirt` SET `name` = '".$name."' WHERE `id` = '".$dej['id']."' LIMIT 1");
mysql_query("UPDATE `flirt_flirt` SET `cena` = '".$cena."' WHERE `id` = '".$dej['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Действие успешно сохранено!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?id=".$dej['id']."&amp;edit'>";
echo "<div class='p_m'>";
echo "<b>Назание:</b><br />";
echo "<input type='text' name='name' value='".$dej['name']."' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Стоимость:</b><br />";
echo "<input type='text' name='cena' value='".$dej['cena']."' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<input type='submit' name='save' value='Сохранить' />";
echo "</div>";
echo "</form>";
echo "<a href='dej_fl.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['del']))
{
if (isset($_GET['yes']))
{
mysql_query("DELETE FROM `flirt_flirt` WHERE `id` = '".$dej['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Действие успешно удалено!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены то хотите удалить это действие?";
echo "</div>";
echo "<a href='?id=".$dej['id']."&amp;del&amp;yes'><div class='foot'><center>";
echo "Да, удалить";
echo "</center></div></a>";
}
echo "<a href='dej_flirt.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
}
?>