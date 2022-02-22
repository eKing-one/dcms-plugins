<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
only_reg();
include_once '../sys/inc/thead.php';
$set['title']='Онлайн видео';
title();
$d = $_GET['d'];
aut();
////////  Создаем кейсы
$id=intval($_GET['id']);

switch ($d) {
    case 'razd':
{
if ($user['level'] > 4) {
echo '<div class="p_t"><form action="index.php?d=add" method="POST">
Введите название раздела:<br />
<input type="text" name="kat" value=""><br />
<input type="submit" value="Добавить раздел">
</form></div>';
}
else {echo 'Вы не имеета права создавать раздел';}
}
break;
//////////////////////////     
case 'add':  

$kat=mysql_real_escape_string($_POST['kat']);

if (empty($_POST['kat'])) { 
echo '<div class="err">ОШИБКА!<br />Вы не ввели название раздела!<br /><a href="index.php?d=razd">Назад</a></div>';}
else {
$kat=mysql_real_escape_string($_POST['kat']);

mysql_query("INSERT INTO `videos_cat` (`name`) VALUES ('".$kat."')");
echo '<div class="foot">Раздел успешно создан. <br /><a href="index.php">К разделам</a></div>'; }
break;
////////////////////////
case 'del':  

if ($user['level'] > 4) {
echo '<div class="err">Вы уверены?!<br /><a href="index.php?id='.$id.'&d=del_ok">Да</a> | <a href="index.php">Нет</a></div>';}
else {echo 'Вы не имеета права удалить раздел';}

break;
///////////////////////// 
case 'del_ok':  

if ($user['level'] > 4) {
mysql_query("DELETE FROM `videos_cat` WHERE `id` = '".$id."'");
echo '<div class="foot">Раздел успешно удален. <br /><a href="index.php">К разделам</a></div>'; }
break;
////////////////////////
case 'ren':  
$kat=mysql_real_escape_string($_POST['kat']);
$q=mysql_query("SELECT * FROM `videos_cat` WHERE `id` = '".$id."'");
while ($res = mysql_fetch_assoc($q)) {
if ($user['level'] > 4) {
echo '<div class="p_t"><form action="index.php?id='.$res['id'].'&d=ren_ok" method="POST">
Введите название:<br />
<input type="text" name="kat" value="'.$res['name'].'"><br />
<input type="submit" value="Изменить">
</form></div>';
} 
}
break;
/////////////////////////
case 'ren_ok':  

if ($user['level'] > 4) {
$kat=mysql_real_escape_string($_POST['kat']);

mysql_query("UPDATE `videos_cat` SET `name` = '" . $kat . "' WHERE `id`= '".$id."'");

echo '<div class="foot">Раздел успешно переименован. <br /><a href="index.php">К разделам</a></div>'; }
break;
default:
/// Создание раздела
if ($user['level'] > 4) {echo '<div class="title"><a href="index.php?d=razd">Создать раздел</a></div>';}
echo '<div class="foot"><img src ="img/search.png"><a href="search.php">Поиск видео</a></div>';
echo '<div class="foot"><img src ="img/new.png"><a href="new.php">Новые</a> | <a href="my.php">Мои видео</a></div>';

/// Вывод разделов
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `videos_cat`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo '<div class="err">Нет разделов</div>';
}

$q=mysql_query("SELECT * FROM `videos_cat` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($res = mysql_fetch_assoc($q)) {

echo '<div class="rekl">';
echo '<img src="img/folder.png"></img><a href="razdel.php?id='.$res['id'].'"> '.$res['name'].'</a>';
if ($user['level'] > 4) {echo '<a href="index.php?id='.$res['id'].'&d=del"> <img src="img/del.png"></a><a href="index.php?id='.$res['id'].'&d=ren"> <img src="img/settings.png"></a>';     
}
echo '</div>';
}
}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц


err();
include_once '../sys/inc/tfoot.php';
?>