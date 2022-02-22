<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak
if($ank['id']==$user['id'] && isset($user))
{
$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Изменить категорию'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_POST['submited']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `id` = '".intval($_POST['cat'])."'"),0)!=0)
{
mysql_query("UPDATE `comm` SET `id_cat` = '".intval($_POST['cat'])."' WHERE `id` = '$comm[id]'");
msg("Изменения сохранены");
$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '".intval($_POST['cat'])."'");
$cat=mysql_fetch_array($cat);
}
else $err[]="Какегория не найдена";
}
err();
echo "<form method='POST'>\n";
echo "<select name='cat'>\n";
$q=mysql_query("SELECT * FROM `comm_cat`");
while($post=mysql_fetch_array($q))
{
echo "<option value='$post[id]'".($post['id']==$cat['id']?" selected='selected'":NULL).">".htmlspecialchars($post['name'])."</option>\n";
}
echo "</select><br/>\n";
echo "<input type='submit' name='submited' value='Изменить'> <a href='?act=comm&id=$comm[id]'>Отмена</a>";
echo "</form>\n";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>