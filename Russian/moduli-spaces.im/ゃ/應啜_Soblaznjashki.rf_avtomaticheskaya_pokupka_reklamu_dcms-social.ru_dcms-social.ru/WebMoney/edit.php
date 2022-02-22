<?
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #    
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Автоматическая покупка рекламы';
include_once '../sys/inc/thead.php';
title();
aut();
err();
require_once ('../WebMoney/core.php');
require_once ('../WebMoney/head.php');
if(isset($_SESSION['text'])){
 echo $_SESSION['text'];
 unset($_SESSION['text']);   
}
if(!isset($_COOKIE['admin'])){
header('Location:/WebMoney/admin.php');
}else{
if (isset($_POST['send']))
{
$name = $_POST['name']; // Название
$url = $_POST['url']; // URL
$days= $_POST['days']; // Сутки
$color = $_POST['color']; // Цвет
$bold = $_POST['bold']; // Жирность
$stmt = $dbb->prepare("UPDATE `webmoney_rek` set `name`=?, `url`=?, `color`=?, `bold`=? where `id`=?");
$stmt -> execute(array($name, $url, $color, $bold, abs(intval($_GET['id']))));
header('Location:/WebMoney/admin.php');
exit();
}
$rekl = $dbb->query("SELECT * FROM `webmoney_rek` WHERE `id`='".abs(intval($_GET['id']))."'")->fetch();
if($rekl!=true){
echo 'Рекламная площадка не найдена в базе данных <br/><a href=/WebMoney/admin.php>В админку</a>';
exit();
}
echo '<form method="post" action="?id='.$_GET['id'].'">
        <b>Название [5 - 30]</b><br />
	<input type="text" name="name" maxlength="30" style="width:30%;" value="'.$rekl['name'].'"/><br />
	<b>Ссылка [2 - 30] (http://site.ru)</b><br />
        <input type="text" name="url" maxlength="30" style="width:30%;" value="'.$rekl['url'].'"/><br />
        <b>Цвет ссылки</b> <br />
	<input type="radio" name="color" value="null" '.($rekl['color']=='null' ? 'checked="checked"' : null).'/>Без цвета<br />
	<input type="radio" name="color" value="red" '.($rekl['color']=='red' ? 'checked="checked"' : null).'/>Красный<br />
	<input type="radio" name="color" value="blue" '.($rekl['color']=='blue' ? 'checked="checked"' : null).'/>Синий<br />
	<input type="radio" name="color" value="green" '.($rekl['color']=='green' ? 'checked="checked"' : null).'/>Зеленый<br />
	<input type="radio" name="color" value="yellow" '.($rekl['color']=='yellow' ? 'checked="checked"' : null).'/>Желтый<br />
        <b>Жирность</b> <br />
	<input type="radio" name="bold" value="1" '.($rekl['bold']=='1' ? 'checked="checked"' : null).'/>Да<br />
	<input type="radio" name="bold" value="2" '.($rekl['bold']=='2' ? 'checked="checked"' : null).'/>Нет<br />
		<input type="submit" name="send" value="Изменить" /> | <a href=/WebMoney/admin.php>Отмена</a>
</form>';
echo '<a href=/WebMoney/admin.php>В админку</a>';
               }
                         
                             
               
               
               
               
               
               
               
               
               
               
               
               
require_once ('../WebMoney/foot.php');
include_once '../sys/inc/tfoot.php';
?>