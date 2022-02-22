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
$dbb->exec("DELETE FROM `webmoney_rek` where `id`='".abs(intval($_GET['id']))."'");
header('Location:/WebMoney/admin.php');
exit();
}
$rekl = $dbb->query("SELECT * FROM `webmoney_rek` WHERE `id`='".abs(intval($_GET['id']))."'")->fetch();
if($rekl!=true){
echo 'Рекламная площадка не найдена в базе данных <br/><a href=/WebMoney/admin.php>В админку</a>';
exit();
}
echo '<form method="post" action="?id='.$_GET['id'].'">
        <b>Вы действительно хотите удалить рекламу?</b><br />
	<input type="submit" name="send" value="Удалить" /> | <a href=/WebMoney/admin.php>Отмена</a>
</form>';
               }
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
require_once ('../WebMoney/foot.php');
include_once '../sys/inc/tfoot.php';
?>