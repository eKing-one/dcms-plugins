<?
if($user['level']>=3 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
// $ust->access('comm_edit_cat')
$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'");
$cat=mysql_fetch_array($cat);
$set['title'] = 'Сообщества - Редактирование категории'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `name` = '$name' AND `id` != '$cat[id]'"),0)!=0)$err[]="Такая категория уже есть";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
$name=my_esc($name);
$desc=my_esc($desc);
if (!isset($err))
{
mysql_query("UPDATE `comm_cat` SET `name` = '$name', `desc` = '$desc' WHERE `id` = '$cat[id]'");
header("Location:/comm");
exit;
}
}
err();

echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value='".input_value_text($cat['name'])."'><br/>\n";
echo "Описание:<br/>\n";
echo "<textarea name='desc'>".input_value_text($cat['desc'])."</textarea><br/>\n";
echo "<input type='submit' name='submited' value='Сохранить'> <a href='/comm'>Назад</a>\n";
echo "</form>\n";
}
else{header("Location:/comm");exit;}
?>