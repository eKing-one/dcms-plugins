<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

$set['title']='Ваш статус'; // заголовок страницы
include_once 'sys/inc/thead.php';
title();
if (isset($_GET['set']))
{
mysql_query("UPDATE `user` SET `sostoyanie` = '".$_GET['st']."' WHERE `id` = '$user[id]' LIMIT 1");
if (!isset($err))msg('Ваш статус изменен');
}

err();
aut();
if (isset($user)){
echo "<b>Текущий статус: </b>\n";


echo "".online($user['id'])." \n";
if ($user['sostoyanie']==0)echo "[Неизвестно]<br/>\n";
else if ($user['sostoyanie']==1)echo "Готов болтать<br/>\n";
else if ($user['sostoyanie']==2)echo "Не беспокоить<br/>\n";
else if ($user['sostoyanie']==3)echo "Болею<br/>\n";
else if ($user['sostoyanie']==4)echo "Депрессия<br/>\n";
else if ($user['sostoyanie']==5)echo "Думаю<br/>\n";
else if ($user['sostoyanie']==6)echo "Курю<br/>\n";
else if ($user['sostoyanie']==7)echo "Кушаю<br/>\n";
else if ($user['sostoyanie']==8)echo "Люблю<br/>\n";
else if ($user['sostoyanie']==9)echo "Еду<br/>\n";
else if ($user['sostoyanie']==10)echo "Слушаю музыку<br/>\n";
else if ($user['sostoyanie']==11)echo "В ванной<br/>\n";
else if ($user['sostoyanie']==12)echo "В туалете<br/>\n";
else if ($user['sostoyanie']==13)echo "Занимаюсь сексом<br/>\n";
else if ($user['sostoyanie']==14)echo "Летаю<br/>\n";
else if ($user['sostoyanie']==15)echo "Пью пиво<br/>\n";
else if ($user['sostoyanie']==16)echo "Сонный<br/>\n";else if ($user['sostoyanie']==17)echo "Пью кофе<br/>\n";else if ($user['sostoyanie']==18)echo "День рождения<br/>\n";else if ($user['sostoyanie']==19)echo "Грустный<br/>\n";else if ($user['sostoyanie']==20)echo "В настроении<br/>\n";else if ($user['sostoyanie']==21)echo "Злой<br/>\n";else if ($user['sostoyanie']==22)echo "On<br/>\n";



echo "<img src='/sostoyanie/img/1.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=1&amp;set'>Готов болтать</a><br />\n";
echo "<img src='/sostoyanie/img/2.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=2&amp;set'>Не беспокоить</a><br />\n";
echo "<img src='/sostoyanie/img/3.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=3&amp;set'>Болею</a><br />\n";
echo "<img src='/sostoyanie/img/4.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=4&amp;set'>Депрессия</a><br />\n";
echo "<img src='/sostoyanie/img/5.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=5&amp;set'>Думаю</a><br />\n";
echo "<img src='/sostoyanie/img/6.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=6&amp;set'>Курю</a><br />\n";
echo "<img src='/sostoyanie/img/7.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=7&amp;set'>Кушаю</a><br />\n";
echo "<img src='/sostoyanie/img/8.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=8&amp;set'>Люблю</a><br />\n";
echo "<img src='/sostoyanie/img/9.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=9&amp;set'>Еду</a><br />\n";
echo "<img src='/sostoyanie/img/10.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=10&amp;set'>Слушаю музыку</a><br />\n";
echo "<img src='/sostoyanie/img/11.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=11&amp;set'>В ванной</a><br />\n";
echo "<img src='/sostoyanie/img/12.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=12&amp;set'>В туалете</a><br />\n";
echo "<img src='/sostoyanie/img/13.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=13&amp;set'>Занимаюсь сексом</a><br />\n";
echo "<img src='/sostoyanie/img/14.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=14&amp;set'>Летаю</a><br />\n";
echo "<img src='/sostoyanie/img/15.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=15&amp;set'>Пью пиво</a><br />\n";
echo "<img src='/sostoyanie/img/16.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=16&amp;set'>Сонный</a><br />\n";echo "<img src='/sostoyanie/img/17.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=17&amp;set'>Пью кофе</a><br />\n";echo "<img src='/sostoyanie/img/18.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=18&amp;set'>День рождения</a><br />\n";echo "<img src='/sostoyanie/img/19.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=19&amp;set'>Грустный</a><br />\n";echo "<img src='/sostoyanie/img/20.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=20&amp;set'>В настроении</a><br />\n";echo "<img src='/sostoyanie/img/21.png' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=21&amp;set'>Злой</a><br />\n";echo "<img src='/sostoyanie/img/22.gif' alt='' class='icon'/> <a href='?p=sostoyanie&amp;st=22&amp;set'>On</a><br />\n";
}else{echo "Только для пользователей сайта";}echo "<div class='foot'>";echo "<a href='/index.php'>На главную</a>";echo "</div>";
include_once 'sys/inc/tfoot.php';
?>
