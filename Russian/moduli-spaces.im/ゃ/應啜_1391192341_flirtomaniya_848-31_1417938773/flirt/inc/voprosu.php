<?
/*
Автор скрипта: Simptom
Сайт поддержки: http://s-klub.ru
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['id']))
{
$it=intval($_GET['id']);
$vop=mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt_voprosu` WHERE `id` = '".$it."' LIMIT 1"));
if (!$vop || $vop['id']==0)
{
echo "<div class='err'>";
echo "Ошибка!";
echo "</div>";
echo "<a href='voprosu.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['edit']))
{
if (isset($_POST['save']))
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
mysql_query("UPDATE `flirt_voprosu` SET `vopros` = '".$vopros."' WHERE `id` = '".$vop['id']."' LIMIT 1");
mysql_query("UPDATE `flirt_voprosu` SET `variant_1` = '".$variant_1."' WHERE `id` = '".$vop['id']."' LIMIT 1");
mysql_query("UPDATE `flirt_voprosu` SET `variant_2` = '".$variant_2."' WHERE `id` = '".$vop['id']."' LIMIT 1");
mysql_query("UPDATE `flirt_voprosu` SET `variant_3` = '".$variant_3."' WHERE `id` = '".$vop['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Вопрос успешно сохранен!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?id=".$vop['id']."&amp;edit'>";
echo "<div class='p_m'>";
echo "<b>Вопрос:</b><br />";
echo "<input type='text' name='vopros' value='".$vop['vopros']."' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №1:</b><br />";
echo "<input type='text' name='variant_1' value='".$vop['variant_1']."' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №2:</b><br />";
echo "<input type='text' name='variant_2' value='".$vop['variant_2']."' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<b>Вариант ответа №3:</b><br />";
echo "<input type='text' name='variant_3' value='".$vop['variant_3']."' maxlength='500' /><br />";
echo "</div>";
echo "<div class='p_m'>";
echo "<input type='submit' name='save' value='Сохрнить' />";
echo "</div>";
echo "</form>";
echo "<a href='voprosu.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['del']))
{
if (isset($_GET['yes']))
{
mysql_query("DELETE FROM `flirt_voprosu` WHERE `id` = '".$vop['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Вопрос успешно удален!";
echo "</div>";
}else{
echo "<div class='err'>";
echo "Вы уверены то хотите удалить этот вопрос?";
echo "</div>";
echo "<a href='?id=".$vop['id']."&amp;del&amp;yes'><div class='foot'><center>";
echo "Да, удалить";
echo "</center></div></a>";
}
echo "<a href='voprosu.php'><div class='foot'>";
echo "<img src='img/obnov.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
}
?>