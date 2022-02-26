<?php
# МОЙ ПИТОМЕЦ
# KAZAMA
# 383991000
error_reporting(0);
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Зоомагазин';
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'head.php';


$q_name=mysql_fetch_array(mysql_query("SELECT * FROM `pit` WHERE `id_user`='".$user['id']."'"));


if (isset($_GET['set']))
{

if ($user['balls']<$_GET['balls'])echo'<div class=err>Не достаточно баллов</div>';else{
$balls=min(max(@intval($_GET['balls']),100),500);
if (isset($user)&& mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_name[id_user]'"),0) == '0')
mysql_query("INSERT INTO `pit`(`id_user`,`pit`, `time`) VALUES('".$user['id']."','".mysql_escape_string($_GET['id'])."', '$time')");
else
mysql_query("UPDATE `pit` SET `pit` = '".mysql_escape_string($_GET['id'])."' WHERE `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".mysql_escape_string($user[balls]-$balls)."' WHERE `id` = '$user[id]' LIMIT 1");
if (!isset($err))msg('Ваш питомец изменен');
}
}
if (isset($_GET['vibran']))echo'<div class=msg>Ваш питомец изменен</div>';

if (isset($_POST['save'])&&isset($_GET['name'])){
$name=mysql_escape_string(esc(stripcslashes(htmlspecialchars($_POST['name']))));
if (isset($user)&& mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_name[id_user]'"),0) == '0')echo "<div class=err>У вас ешё питомца нету!</div>";
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `name` = '$name'"),0)!=0)echo "<div class=err>Такое имя уже есть!</div>";
else{
mysql_query("UPDATE `pit` SET `name` = '$name' WHERE `id_user` = '$user[id]' LIMIT 1");
if (!isset($err))msg('Имя питомца успешно изменено');
header("Location: pit.php?vibran");
}
}





$action=htmlspecialchars(trim($_GET['vibor']));
switch ($action){
default:
if (isset($user)&& mysql_result(mysql_query("SELECT COUNT(*) FROM `pit` WHERE `id_user` = '$q_name[id_user]'"),0) != '0'){

echo "<form method='post' action='?name'>\n";
echo "Имя питомца:<br />\n<input type='text' name='name' value='$q_name[name]' maxlength='15' /><br />\n";
echo "<input type='submit' name='save' value='Изменить' />\n";
echo "</form>\n";
}
echo'Выберите класс <br />';
echo "<img src='icon/pit.png' alt='' class='icon'/><a href='?vibor=1'>Обычные</a>(100баллов)<br />\n";
echo "<img src='icon/pit.png' alt='' class='icon'/><a href='?vibor=2'>Интересные</a>(200баллов)<br />\n";
echo "<img src='icon/pit.png' alt='' class='icon'/><a href='?vibor=3'>Альтернативные</a>(300баллов)<br />\n";
echo "<img src='icon/pit.png' alt='' class='icon'/><a href='?vibor=4'>Элитные</a>(500баллов)<br />\n";
echo "<img src='icon/pit.png' alt='' class='icon'/><a href='?vibor=5'>Праздничные</a>(400баллов)<br />\n";
break;
case '1':
echo "<img src='img/52.png' alt='' class='icon'/> <a href='?set&id=52&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/51.png' alt='' class='icon'/> <a href='?set&id=51&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/50.png' alt='' class='icon'/> <a href='?set&id=50&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/49.png' alt='' class='icon'/> <a href='?set&id=49&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/1.png' alt='' class='icon'/> <a href='?set&id=1&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/2.png' alt='' class='icon'/> <a href='?set&id=2&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/3.png' alt='' class='icon'/> <a href='?set&id=3&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/4.png' alt='' class='icon'/> <a href='?set&id=4&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/5.png' alt='' class='icon'/> <a href='?set&id=5&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/6.png' alt='' class='icon'/> <a href='?set&id=6&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/7.png' alt='' class='icon'/> <a href='?set&id=7&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/9.png' alt='' class='icon'/> <a href='?set&id=9&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/10.png' alt='' class='icon'/> <a href='?set&id=10&balls=100'>Выбрать зверька</a><br />\n";
echo "<img src='img/11.png' alt='' class='icon'/> <a href='?set&id=11&balls=100'>Выбрать зверька</a><br />\n";
break;
case '2':
echo "<img src='img/54.png' alt='' class='icon'/> <a href='?set&id=54&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/53.png' alt='' class='icon'/> <a href='?set&id=53&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/12.png' alt='' class='icon'/> <a href='?set&id=12&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/13.png' alt='' class='icon'/> <a href='?set&id=13&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/14.png' alt='' class='icon'/> <a href='?set&id=14&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/15.png' alt='' class='icon'/> <a href='?set&id=15&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/16.png' alt='' class='icon'/> <a href='?set&id=16&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/17.png' alt='' class='icon'/> <a href='?set&id=17&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/18.png' alt='' class='icon'/> <a href='?set&id=18&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/19.png' alt='' class='icon'/> <a href='?set&id=19&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/20.png' alt='' class='icon'/> <a href='?set&id=20&balls=200'>Выбрать зверька</a><br />\n";
echo "<img src='img/21.png' alt='' class='icon'/> <a href='?set&id=21&balls=200'>Выбрать зверька</a><br />\n";
break;
case '3':
echo "<img src='img/55.png' alt='' class='icon'/> <a href='?set&id=55&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/23.png' alt='' class='icon'/> <a href='?set&id=23&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/24.png' alt='' class='icon'/> <a href='?set&id=24&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/25.png' alt='' class='icon'/> <a href='?set&id=25&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/26.png' alt='' class='icon'/> <a href='?set&id=26&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/27.png' alt='' class='icon'/> <a href='?set&id=27&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/28.png' alt='' class='icon'/> <a href='?set&id=28&balls=300'>Выбрать зверька</a><br />\n";
echo "<img src='img/29.png' alt='' class='icon'/> <a href='?set&id=29&balls=300'>Выбрать зверька</a><br />\n";
break;
case '4':
echo "<img src='img/56.png' alt='' class='icon'/> <a href='?set&id=56&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/30.png' alt='' class='icon'/> <a href='?set&id=30&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/31.png' alt='' class='icon'/> <a href='?set&id=31&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/32.png' alt='' class='icon'/> <a href='?set&id=32&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/33.png' alt='' class='icon'/> <a href='?set&id=33&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/34.png' alt='' class='icon'/> <a href='?set&id=34&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/35.png' alt='' class='icon'/> <a href='?set&id=35&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/36.png' alt='' class='icon'/> <a href='?set&id=36&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/38.png' alt='' class='icon'/> <a href='?set&id=38&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/39.png' alt='' class='icon'/> <a href='?set&id=39&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/40.png' alt='' class='icon'/> <a href='?set&id=40&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/41.png' alt='' class='icon'/> <a href='?set&id=41&balls=500'>Выбрать зверька</a><br />\n";
break;
case '5':
echo "<img src='img/57.png' alt='' class='icon'/> <a href='?set&id=57&balls=500'>Выбрать зверька</a><br />\n";
echo "<img src='img/42.png' alt='' class='icon'/> <a href='?set&id=42&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/43.png' alt='' class='icon'/> <a href='?set&id=43&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/44.png' alt='' class='icon'/> <a href='?set&id=44&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/45.png' alt='' class='icon'/> <a href='?set&id=45&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/46.png' alt='' class='icon'/> <a href='?set&id=46&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/47.png' alt='' class='icon'/> <a href='?set&id=47&balls=400'>Выбрать зверька</a><br />\n";
echo "<img src='img/48.png' alt='' class='icon'/> <a href='?set&id=48&balls=400'>Выбрать зверька</a><br />\n";
break;
};
echo '<div class="msg"><a href="index.php?">В игру</a></div><br/> Зоздатель игры <a href="http://vent.besaba.com">У нас весело</a>';
include_once '../sys/inc/tfoot.php';

?>