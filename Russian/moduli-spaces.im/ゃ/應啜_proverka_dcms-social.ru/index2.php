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
if(isset($_GET['add']) && isset($user) && $user['level']>2){
$set['title']="Создание нового правила";
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_GET['ok']) && isset($_POST['ok'])){
$name=my_esc($_POST['name']);
$text=my_esc($_POST['text']);
if (strlen2($name)<3)$err="Короткое название правила.";
if (strlen2($name)>30)$err="Длинное название правила.";
if (strlen2($text)>2000)$err="Слишком длинное правило.";
if (strlen2($text)<5)$err="Слишком короткое правило";
if (!isset($err)){
$post=mysql_result(mysql_query("SELECT MAX(`post`) FROM `pravila`"), 0)+1;
mysql_query("INSERT INTO `pravila` (`name`, `text`, `post`) values('".mysql_real_escape_string($name)."', '$text', '$post')");
if (!empty($_POST['news'])){
mysql_query("INSERT INTO `news` (`time`, `msg`, `title`, `main_time`, `link`) values('$time', '".mysql_real_escape_string($text)."', 'Новое правило на $_SEVER[HTTP_HOST]', '1440', '/pravila')");
}
msg("Правило успешно создано");
}
}
err();
echo "<form method='post' action='?add&ok'>";
echo "Название правила:<br/>";
echo "<input type='text' name='name' value=''><br/>";
echo "Текст правила:<br/>";
echo "<textarea name='text'></textarea><br/>";
echo "<label><input type=\"checkbox\" name=\"news\" value=\"1\" /> <b>Рассказать в новостях</b></label><br/>\n";
echo "<input type='submit' name='ok' value='Добавить'><br/>";
echo "</form>";
echo "<a href='?'><div class='foot'><img src='/style/icons/str2.gif'/> Вернутся назад</div></a>";
include_once '../sys/inc/tfoot.php';
}
elseif(isset($_GET['red']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `pravila` WHERE `id` = '".intval($_GET['red'])."'"),0)!=0 && isset($user) && $user['level']>2){
$red=mysql_fetch_array(mysql_query("SELECT * FROM `pravila` WHERE `id` = '".intval($_GET['red'])."'"));
if ($red['id']==0)header("location:/index.php?");
$set[title]="Изменение правила";
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_POST['ok'])){
$name=my_esc($_POST['name']);
$text=my_esc($_POST['text']);
if (strlen2($name)<3)$err="Короткое название правила.";
if (strlen2($name)>30)$err="Длинное название правила.";
if (strlen2($text)>2000)$err="Длинной текст правила.";
if (strlen2($text)<5)$err="Короткий текст правила.";
if (!isset($err)){
mysql_query("UPDATE `pravila` SET `name` = '".mysql_real_escape_string($name)."', `text` = '$text' WHERE `id` = '$red[id]'");
msg("Правило успешно изменено!");
}
}
err();
echo "<form method='post' action='?red=$red[id]&$passgen'>";
echo "Название правила:<br/>";
echo "<input type='text' name='name' value='".htmlspecialchars($red['name'])."' style='widht:95%'><br/>";
echo "Текст правила:<br/>";
echo "<textarea name='text' style='widht:95%'>".htmlspecialchars($red['text'])."</textarea><br/>";
echo "<input type='submit' name='ok' value='Сохранить'><br/></form>";
echo "<a href='?'><div class='foot'><img src='/style/icons/str2.gif'/> Вернутся назад</div></a>";
include_once '../sys/inc/tfoot.php';
}
elseif(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `pravila` WHERE `id` = '".intval($_GET['del'])."'"),0)!=0 && isset($user) && $user['level']>2){
$del=mysql_fetch_array(mysql_query("SELECT * FROM `pravila` WHERE `id` = '".intval($_GET['del'])."'"));
if ($del['id']==0)header("location:/index.php?");
$set['title']="Удаление - ".htmlspecialchars($del['name'])."";
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_GET['ok'])){
mysql_query("DELETE FROM `pravila` WHERE `id` = '$del[id]'");
header("location:?");
}
echo "<div class='nav2'>Вы действительно хотите удалить правило:<br/><b>".htmlspecialchars($del['name'])." ?</b></div>";
echo "<table><td style='vertical-align:top;border:1px solid skyblue;background:whitesmoke;widht:20%'><a style='padding:5px;display:block;text-align:center;'href='?del=$del[id]&ok'>Удалить</a></td>";
echo "<td style='vertical-align:top;border:1px solid skyblue;background:whitesmoke;widht:20%'><a style='padding:5px;display:block;text-align:center;'href='?'>Назад</a></td></table>";
include_once '../sys/inc/tfoot.php';
}
$set['title']="Правила $_SERVER[HTTP_HOST]";
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($_GET['up']) && isset($user) && $user['level']>2){
$up=mysql_fetch_assoc(mysql_query("SELECT * FROM `pravila` WHERE `id` = '".intval($_GET['up'])."' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `pravila` WHERE `post` < '$up[post]' LIMIT 1"),0)!=0){
mysql_query("UPDATE `pravila` SET `post` = '".($up['post'])."' WHERE `post` = '".($up['post']-1)."' LIMIT 1");
mysql_query("UPDATE `pravila` SET `post` = '".($up['post']-1)."' WHERE `id` = '".intval($_GET['up'])."' LIMIT 1");
}
}
elseif (isset($_GET['down']) && isset($user) && $user['level']>2){
$down=mysql_fetch_assoc(mysql_query("SELECT * FROM `pravila` WHERE `id` = '".intval($_GET['down'])."' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `pravila` WHERE `post` > '$down[post]' LIMIT 1"),0)!=0){
mysql_query("UPDATE `pravila` SET `post` = '".($down['post'])."' WHERE `post` = '".($down['post']+1)."' LIMIT 1");
mysql_query("UPDATE `pravila` SET `post` = '".($down['post']+1)."' WHERE `id` = '".intval($_GET['down'])."' LIMIT 1");
}
}
if(isset($user) && $user['level']>2)echo "<a href='?add'><div class='post'><img src='img/add.png' alt='add'> Добавить правило</div></a><a href='?".(!isset($_GET['moderate'])?"moderate":null)."'><div class='post'><img src='img/set.png' alt='set'> Управление правилами</div></a>";
$q=mysql_query("SELECT * FROM `pravila` ORDER BY `post` DESC");
if(mysql_num_rows($q)==0)echo "Правила $_SERVER[HTTP_HOST] еще не написаны администратором.";
$i=1;
while($post=mysql_fetch_array($q)){
echo "<a href='prav.php?id=$post[id]'><div class='gmenu'><img src='img/p.png' alt='!'>  ".htmlspecialchars($post['name'])."";
if(isset($_GET['moderate']) && isset($user) && $user['level']>2)echo "<br><a href='?moderate&up=$post[id]'><img src='img/up.png' alt='up'></a><a href='?moderate&down=$post[id]'><img src='img/down.png' alt='down'></a><a href='?red=$post[id]'><img src='img/set.png' alt='set'></a><a href='?del=$post[id]'><img src='img/del.png' alt='del'></a>";
echo "</div></a>";
$i++;
}
include_once '../sys/inc/tfoot.php';
?>
