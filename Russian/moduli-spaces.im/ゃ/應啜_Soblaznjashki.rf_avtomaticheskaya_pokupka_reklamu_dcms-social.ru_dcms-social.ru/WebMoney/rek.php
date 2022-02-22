<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
$banpage=true;
include_once '../sys/inc/user.php';
$set['title']='Автоматическая покупка рекламы';
include_once '../sys/inc/thead.php';

title();
aut();
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
require_once ('../WebMoney/core.php');
require_once ('../WebMoney/head.php');
if(!empty($_POST['name']) && !empty($_POST['url']) && !empty($_POST['days']) && !empty($_POST['color']) && !empty($_POST['bold'])){
if (!preg_match("/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu", $_POST['url'])){
echo 'Недопустимый формат URL<br/>';
echo '<a href=/WebMoney/>Назад</a>';
require_once ('../WebMoney/foot.php');
exit();
}
$name=htmlspecialchars($_POST['name']);
$url=htmlspecialchars($_POST['url']);
$days=htmlspecialchars($_POST['days']);
if(!empty($_POST['color']) && $_POST['color']!='null'){
$color=htmlspecialchars($_POST['color']);
$colors=$days*$zwet;
}else{
$color='null';
$colors=0;}
if(!empty($_POST['bold']) && $_POST['bold']==1){
$bold=htmlspecialchars($_POST['bold']);
$bolds=$days*$b;
}else{
$bold='null';
$bolds=0;}
$summa=($days*$zena)+$bolds+$colors;

echo '<div class="nav1">';
echo 'Покупка рекламы на '.$days.' суток(и) на сумму '.$summa.' wmr<br/>';
echo 'Название: '.$name.'<br/>';
echo 'URL: '.$url.'<br/>';
echo 'Цвет: ';
echo $_POST['color'] == 'null' ? 'без цвета' : NULL;
echo $_POST['color'] == 'red' ? '<font color="red">красный</font>' : NULL;
echo $_POST['color'] == 'blue' ? '<font color="blue">синий</font>' : NULL;
echo $_POST['color'] == 'green' ? '<font color="green">зеленый</font>' : NULL;
echo $_POST['color'] == 'yellow' ? '<font color="yellow">желтый</font>' : NULL;
echo '<br/>';
echo 'Жирность: ';
echo $_POST['bold'] == 1 ? '<b>да</b>' : 'нет';

?>
<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?echo$summa;?>">
<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?=base64_encode('Покупка рекламы :: '.$url.' :: '.$days.' суток')?>">
<input type="hidden" name="LMI_PAYEE_PURSE" value="ваш кошелек">
<input type="hidden" name="name" value="<?=$name?>">
<input type="hidden" name="url" value="<?=$url?>">
<input type="hidden" name="days" value="<?=$days?>">
<input type="hidden" name="color" value="<?=$color?>">
<input type="hidden" name="bold" value="<?=$bold?>">
<input type="submit" value="Продолжить">
</form>
</div>
<?
}else{
echo '<div class="nav2">Не выбран и/или не введен один из параметров.<br/>Проверьте правильноcть заполнения всех данных.<br/><a href=/WebMoney/>Назад</a></div>';}

include_once '../sys/inc/tfoot.php';
require_once ('../WebMoney/foot.php');

?>
