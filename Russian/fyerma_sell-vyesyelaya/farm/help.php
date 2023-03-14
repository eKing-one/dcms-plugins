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
only_reg();
$set['title']='Весёлая ферма :: Помощь';
include_once '../sys/inc/thead.php';
title();
err();
aut();

include 'inc/str.php';
farm_event();

if (isset($_GET['weather']))
{
echo "<div class='rowup'>";

if ($conf['weather']==1)
{
$uro="+3";
$name="温暖";
}
if ($conf['weather']==2)
{
$uro="+2";
$name="多云";
}
if ($conf['weather']==3)
{
$uro="-1";
$name="大雨";
$uron="-1";
}
if ($conf['weather']==4)
{
$uro="-3";
$name="雷雨";
$uron="-3";
}
if ($conf['weather']==5)
{
$uro="+1";
$name="晴天";
}

if ($fuser['teplica']==1)
{
if ($uro<0)
{
$uro=0;
$mess=true;
}
}

echo "<img src='/farm/img/garden.png' alt='' class='rpg' />当前天气: <img src='/farm/weather/".$conf['weather'].".png' alt='' />".$name."<br />";
echo "<img src='/img/add.png' alt='' class='rpg' /> 影响: ".$uro." ";
if (isset($mess))
{
echo "(".$uron.") ";
}
echo "к урожаю";
if (isset($mess))
{
echo "<br /><img src='/img/accept.png' alt='' class='rpg' /> 你已经学习了<b>Teplitsa</b>的技能。天气的负面影响不影响你的收成";
}
echo "</div>";
}

if (!isset($_GET['weather']))
{
echo "<div class='rowup'><b>我什么时候可以再加一张床？</b></div>";
echo "<div class='rowdown'>一开始，你有五个免费的床。后续的比之前的贵 500 黄金。一旦你有了 6 个床，其余的需要一定的水平。<br/>"；
Echo"你可以在设置以下级别后购买床： <b>5, 10, 15, 20, 25, 30, 35, 40, 45, 50</b>.<br />";
echo "结果，你最多有<b>十六个</b>床。<br />";
echo "现在你的水平: ".$level.", 黄金 ".$fuser['gold']."";
}

echo "</div><div class='rowup'>";
echo "<img src='/img/back.png' alt='Назад' class='rpg' /> <a href='/farm/garden/'>我的床</a><br/>";
echo "</div>";

include_once '../sys/inc/tfoot.php';

?>