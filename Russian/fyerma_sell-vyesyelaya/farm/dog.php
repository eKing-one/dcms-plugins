<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';


$set['title']='买狗';
include_once '../sys/inc/thead.php';
title();
err();
aut();
$cena=10000;


include 'inc/str.php';
if($level>=5){
if(isset($_GET['add_dog']) && $user['farm_gold']>=$cena){
$t=time()+60*60*24*15;
dbquery("UPDATE `user` SET `farm_gold` = `farm_gold` - '".$cena."' WHERE `id` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_dog` SET `time` = '".$t."' WHERE `id_user` = '".$user['id']."' LIMIT 1");
add_farm_event('Собака приобретена');
}
if(isset($_GET['add_dog']) && $user['farm_gold']<=$cena)
{
add_farm_event('Не хватает денег');
}

farm_event();

echo "<div class='rowup'><center><img src='img/dog.png'></center><br />\n";
echo "&raquo; Покупка собаки обойдется вам в <img src='/farm/img/money.png' /> ".$cena."<br />\n";
echo "&raquo; Собака живет 15 дней.\n";
$dg = dbresult(dbquery("SELECT COUNT(*) FROM `farm_dog` WHERE `time` > '".time()."' AND `id_user` = '".$user['id']."'"),0);
if ($dg!=0)
{
$dog = "собака есть";
}
else
{
$dog = "собаки нет";
}
echo "<br />Текущий статус: <b>".$dog."</b>.";

if ($dg==0)
{
echo "<form method='post' action='?add_dog' class='formfarm'>\n";
echo "<input type='submit' name='save' value='Приобрести собаку' />";
echo "</form>\n";
}

echo "</div><div class='rowdown'>";
echo "<img src='/farm/img/garden.png' class='rpg' /> <a href='/farm/garden/'>Моя ферма</a><br/>";
echo "&laquo; <a href='index.php'>Назад</a><br/>";
echo "</div>";
}
else
{
echo "<div class='err'>Ваш уровень не позволяет вам приобрести собаку. Нужен уровень больше 5.</div>";
}
include_once '../sys/inc/tfoot.php';

?>