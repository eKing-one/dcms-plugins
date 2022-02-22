<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'");
$cat=mysql_fetch_array($cat);
$set['title'] = 'Сообщества - Создание сообщества'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if ($mcomms<4)
{
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `name` = '$name'"),0)!=0)$err[]="Сообщество с таким названием уже есть";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
$name=my_esc($_POST['name']);
$desc=my_esc($_POST['desc']);
if (!isset($err))
{
mysql_query("INSERT INTO `comm` (`name`, `desc`, `id_cat`, `id_user`, `time`) VALUES ('$name', '$desc', '$cat[id]', '$user[id]', '".time()."')");
$comm_id=mysql_insert_id();
mysql_query("INSERT INTO `comm_users` (`id_comm`, `id_user`, `time`, `activate`, `access`) VALUES ('$comm_id', '$user[id]', '".time()."', '1', 'creator')");
header("Location:/comm/?act=cat&id=$cat[id]");
exit;
}
}
err();
echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value=''><br/>\n";
echo "Описание:<br/>\n";
echo "<textarea name='desc'></textarea><br/>\n";
echo "<input type='submit' name='submited' value='Создать'> <a href='/comm/?act=cat&id=$cat[id]'>Назад</a>\n";
echo "</form>\n";
}
else
{
echo "<div class='menu'>Вы уже создали максимальное количество сообществ!</div>";
}
}
else{header("Location:/comm");exit;}
?>