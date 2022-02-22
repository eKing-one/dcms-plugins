<?
if($user['level']>=3)
{
// $ust->access('comm_add_cat')
$set['title'] = 'Сообщества - Добавление категории'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `name` = '$name'"),0)!=0)$err[]="Такая категория уже есть";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
$name=my_esc($name);
$desc=my_esc($desc);
if (!isset($err))
{
$pos=mysql_result(mysql_query("SELECT MAX(`pos`) FROM `comm_cat`"),0)+1;
mysql_query("INSERT INTO `comm_cat` (`name`, `desc`, `pos`) VALUES ('$name', '$desc', '$pos')");
header("Location:/comm");
exit;
}
}
err();
echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value=''><br/>\n";
echo "Описание:<br/>\n";
echo "<textarea name='desc'></textarea><br/>\n";
echo "<input type='submit' name='submited' value='Добавить'> <a href='/comm'>Назад</a>\n";
echo "</form>\n";
}
else {header("Location:/comm");exit;}
?>