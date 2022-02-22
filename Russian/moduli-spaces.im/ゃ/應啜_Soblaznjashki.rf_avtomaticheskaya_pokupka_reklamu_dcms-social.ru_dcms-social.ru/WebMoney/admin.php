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
if (isset($_POST['send']))
{
if(isset($_POST['pass']) && $_POST['pass']==$pass){
setcookie('admin', 'admin', time() + 60*20, '/');
$_SESSION['text']='Авторизация успешна!';
}else{
$_SESSION['text']='Пароль не введен или введен не верно!';
}
header('Location:?');
exit();
}

echo '<form method="post" action="?">
        <b>Введите пароль</b><br />
	<input type="text" name="pass" maxlength="30" style="width:30%;"/><br />
	<input type="submit" name="send" value="Войти" />
</form>';
}else{
	$res = $dbb->query("SELECT * FROM `webmoney_rek` where `date_last`>'".time()."'");
while ($row = $res->fetch(PDO::FETCH_BOTH)){	
echo 'Название: '.htmlspecialchars($row['name']).'<br/>';
echo 'URL: '.htmlspecialchars($row['url']).'<br/>';
echo 'Цвет: '; 
echo $row['color'] == 'null' ? 'без цвета' : null;
echo $row['color'] == 'red' ? '<font color="red">красный</font>' : NULL;
echo $row['color'] == 'blue' ? '<font color="blue">синий</font>' : NULL;
echo $row['color'] == 'green' ? '<font color="green">зеленый</font>' : NULL;
echo $row['color'] == 'yellow' ? '<font color="yellow">желтый</font>' : NULL;       
echo '<br/>';
echo 'Жирность: ';
echo $row['bold'] == 1 ? '<b>да</b>' : 'нет';echo '<br/>';
echo $row['date_last']>time() ? '<font
color="green">Активна до '.date("d/m H:i:s", abs(intval
($row['date_last']))).'</font>' : '<font color="red">не
активна</font>';echo '<br/>';

echo '[<a href=/WebMoney/edit.php?id='.$row['id'].'>Редактировать</a> | <a href=/WebMoney/delete.php?id='.$row['id'].'>Удалить</a>]';echo '<hr/>';
               }
               }
               
               
               
               
               
               
               
               
               
               
               
               
               
               
require_once ('../WebMoney/foot.php');
include_once '../sys/inc/tfoot.php';
?>