<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Основное'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if($ank['id']==$user['id'] && isset($user))
{
if(isset($_POST['submited']))
{
$comm['name']=$_POST['name'];
$comm['desc']=$_POST['desc'];
$comm['deviz']=$_POST['deviz'];
$comm['rules']=$_POST['rules'];
$comm['interests']=$_POST['interests'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `name` = '".my_esc($comm['name'])."' AND `id` != '$comm[id]'"),0)!=0)$err[]="Сообщество с таким названием уже есть";
elseif(strlen2($comm['name'])>50 || strlen2($comm['name'])<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
else mysql_query("UPDATE `comm` SET `name` = '$comm[name]' WHERE `id` = '$comm[id]'");

if(strlen2($comm['desc'])>512)$err[]="Описание должно быть не больше 512-ти символов";
else mysql_query("UPDATE `comm` SET `desc` = '$comm[desc]' WHERE `id` = '$comm[id]'");

if(strlen2($comm['deviz'])>100)$err[]="Девиз должен быть не больше ста символов";
else mysql_query("UPDATE `comm` SET `deviz` = '$comm[deviz]' WHERE `id` = '$comm[id]'");

if(strlen2($comm['rules'])>1000)$err[]="Правила должны быть не больше 1000 символов";
else mysql_query("UPDATE `comm` SET `rules` = '$comm[rules]' WHERE `id` = '$comm[id]'");

mysql_query("UPDATE `comm` SET `interests` = '$comm[interests]' WHERE `id` = '$comm[id]'");

if(!isset($err))
{
msg("Изменения сохранены");
}
}
err();

echo "<form method='post'>\n";

echo "<span style='color:green'>Имя сообщества:</span><br/>\n";
echo "<input name='name' value='".input_value_text($comm['name'])."' size='17' maxlength='50'/><br/>\n";
echo "<span style='color:green'>Девиз:</span><br/>\n";
echo "<input name='deviz' type='text' size='17' value='".input_value_text($comm['deviz'])."' maxlength='100' style='width:80%'/><br/>\n";
echo "<span style='color:green'>Описание:</span><br/>\n";
echo "<textarea name='desc' rows='2' cols='17' style='width:80%'>".input_value_text($comm['desc'])."</textarea><br/>\n";
echo "<span style='color:green'>Правила:</span><br/>\n";
echo "<textarea name='rules' rows='2' cols='17' style='width:80%'>".input_value_text($comm['rules'])."</textarea><br/>\n";
echo "<span style='color:green'>Интересы:</span><br/>\n";
echo "<textarea name='interests' rows='2' cols='17' style='width:80%'>".input_value_text($comm['interests'])."</textarea><br/>\n";

echo "<input name='submited' type='submit' value='Сохранить'> <a href='?act=comm_settings&id=$comm[id]'>Назад</a></form>";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>