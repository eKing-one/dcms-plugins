<?

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
err();

echo "<div class='main'>\n";
echo '<span style="color:red">*
Ссылки на порно, варез сайты, а также те, которые могут причинить какой либо вред телефонам, компьютерам посетителей (сайты с вирусами, вредоносными кодами) будут удалятся незамедлительно без возврата средств!</span></a></div>';
##############################
#  Модуль покупки рекламы    #
#Автор Sakamsky99 (Sakamsky) #
#Скрипт запрещено продавать, #
#дарить, кидать в пабл!      #
##############################
require_once ('../WebMoney/core.php');
require_once ('../WebMoney/head.php');
?>
<body width="500px;">
<form method="post" action="/WebMoney/rek.php">
<b>Название [5 - 30]</b><br />
<input type="text" name="name" maxlength="30" style="width:30%;"/><br />
<b>Ссылка [2 - 30] (http://site.ru)</b><br />
<input type="text" name="url" maxlength="30" style="width:30%;" value="http://"/><br />
<b>Длительность рекламы</b> <br />
<input type="text" name="days" style="width:10%;"/><br />
<b>Цвет ссылки</b> <br />
<input type="radio" name="color" value="null" checked="checked"/>Без цвета<br />
<input type="radio" name="color" value="red" />Красный<br />
<input type="radio" name="color" value="blue" />Синий<br />
<input type="radio" name="color" value="green" />Зеленый<br />
<input type="radio" name="color" value="yellow" />Желтый<br />
<b>Жирность</b> <br />
<input type="radio" name="bold" value="1" />Да<br />
<input type="radio" name="bold" value="2" checked="checked"/>Нет<br />
Способ оплаты: Webmoney Merchant<br/>
Цена размещения рекламы за сутки: <?=$zena?> руб.<br/>
Добавление цвета в день: <?=$zwet?> руб.<br/>
Добавление жирности в день: <?=$b?> руб.<br/>
<input type='submit' value='Купить' />
</form>
<?
require_once ('../WebMoney/foot.php');

include_once '../sys/inc/tfoot.php';
?>
