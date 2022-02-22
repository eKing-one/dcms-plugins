<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (isset($_GET['edit']))
{
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
mysql_query("UPDATE `baby` SET `name` = '".$name."' WHERE `id` = '".$b['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `pol` = '".$pol."' WHERE `id` = '".$b['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "Данные успешно сохранены!";
echo "</div>";
}
}
err();
echo "<form method='post' name='message' action='?id=".$b['id']."&amp;edit'>";
echo "<b>Имя ребёнка:</b><br />";
echo "<input type='text' name='name' value='".$b['name']."' maxlength='100' /><br />";
echo "<b>Пол ребёнка:</b><br />";
echo "<select name='pol'>";
echo "<option value='0'".($b['pol']==0?" selected='selected'":null).">Девочка</option><br />";
echo "<option value='1'".($b['pol']==1?" selected='selected'":null).">Мальчик</option><br />";
echo "</select><br />";
echo "<input type='submit' name='save' value='Готово' />";
echo "</form>";
echo "<a href='sets.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['foto']))
{
if (isset($_POST['save']))
{
if (!isset($_FILES['foto']))
{
$err='Выберите фото!';
}else{
if (preg_match('#\.jpe?g$#i',$_FILES['foto']['name']) || preg_match('#\.gif$#i',$_FILES['foto']['name']) || preg_match('#\.png$#i',$_FILES['foto']['name']))
{
copy($_FILES['foto']['tmp_name'], H."baby/avatar/".$b['id'].".png");
@chmod(H."baby/avatar/".$b['id'].".png" , 0777);
echo "<div class='msg'>";
echo "<b>Фото выгружено!</b>";
echo "</div>";
}else{
$err='Неверный формат файла!';
}
}
}
if (isset($_GET['del']) && is_file("avatar/".$b['id'].".png"))
{
unlink(H."baby/avatar/".$b['id'].".png");
echo "<div class='msg'>";
echo "<b>Фото удалено!</b>";
echo "</div>";
}
err();
include_once 'inc/avatar.php';
echo "<div class='main'><center>";
echo "".ava_baby($b['id'])."";
echo "</center></div>";
if (is_file("avatar/".$b['id'].".png"))
{
echo "<a href='?id=".$b['id']."&amp;foto&amp;del'><div class='main2'><center>";
echo "Удалить фото";
echo "</center></div></a>";
}
echo "<form method='post' enctype='multipart/form-data' action='?id=".$b['id']."&amp;foto'>";
echo "<b>Фотография ребёнка:</b><br />";
echo "<input name='foto' accept='image/*,image/png,image/gif,image/jpg' type='file' /><br />";
echo "<input type='submit' name='save' value='Сменить' />";
echo "</form>";
echo "<a href='sets.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_GET['del']))
{
if (isset($_GET['yes']))
{
mysql_query("UPDATE `baby` SET `mama` = '0' WHERE `id` = '".$b['id']."' LIMIT 1");
mysql_query("UPDATE `baby` SET `papa` = '0' WHERE `id` = '".$b['id']."' LIMIT 1");
echo "<div class='msg'>";
echo "<b>Теперь у них нет ребенка!</b>";
echo "</div>";
echo "<a href='index.php'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
}else{
echo "<div class='err'>";
echo "<b>Вы уверены что хотите отобрать этого ребёнка?</b>";
echo "</div>";
echo "<a href='?id=".$b['id']."&amp;del&amp;yes'><div class='main2'><center>";
echo "Да, отобрать";
echo "</center></div></a>";
echo "<a href='sets.php?id=".$b['id']."'><div class='foot'>";
echo "<img src='img/home.png' alt='Simptom'> Назад";
echo "</div></a>";
}
include_once '../sys/inc/tfoot.php';
exit;
}
?>