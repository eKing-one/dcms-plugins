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
only_reg();
$set['title']='快乐农场：：我的统计数据';
include_once '../sys/inc/thead.php';
title();
aut();

include_once 'inc/str.php';
farm_event();

echo "<div class='mdlc'><span>我的统计数据。</span><br /></div><div class='menu'>";
echo "<img src='/img/rosette.png' alt='' class='rpg' /> 你的水平。: ".$level."<br />";
echo "<img src='/farm/img/money.png' alt='' class='rpg' /> 货币: ".$fuser['gold']."<br />";
echo "<img src='/farm/img/gems.png' alt='' class='rpg' /> 钻石: ".$fuser['gems']."<br />";
echo "<img src='/farm/img/exp.png' alt='' class='rpg' /> 经验: ".$fuser['exp']."<br />";
$grc=dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '".$user['id']."'"), 0);
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> 床: ".$grc."<br />";
echo "<img src='/farm/img/serdechko.png' alt='' class='rpg' /> 卫生单位: ".$fuser['xp']."<br />";
echo "<img src='/farm/img/water.png' alt='' class='rpg' /> 波利托苗床: ".$fuser['poliv']."<br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> 种植的植物: ".$fuser['posadka']."<br />";
echo "<img src='/farm/img/fertilize.png' alt='' class='rpg' /> 给植物施肥: ".$fuser['udobrenie']."";
echo "</div>";

if ($fuser['k_poliv']>0 || $fuser['k_posadka']>0)
{
echo "<div class='mdlc'><span>技术</span><br /></div><div class='menu'>";
if ($fuser['k_poliv']>0)
{
echo "<img src='/farm/img/irrigation_small.png' alt='' class='rpg' /> <a href='/farm/shop_combine.php?irrigation'>喷洒器</a> (".$fuser['k_poliv']."/3 yp.)";
}
if ($fuser['k_posadka']>0)
{
echo "<br /><img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <a href='/farm/shop_combine.php?seeder'>播种机</a> (".$fuser['k_posadka']."/3 yp.)";
}
echo "</div>";
}

if ($fuser['selection']>0 || $fuser['zabor_time']>time() || $fuser['teplica']==1 || $fuser['razvedka']==1)
{
echo "<div class='mdlc'><span>技能和设备</span><br /></div><div class='menu'>";
if ($fuser['selection']>0)
{
echo "<img src='/farm/img/plus.gif' alt='' class='rpg' /> 能力选择 - ".$fuser['selection']." 电平<br />";
}
if ($fuser['teplica']==1)
{
echo "<img src='/farm/img/tep.gif' alt='' class='rpg' /> 技能大棚<br />";
}
if ($fuser['razvedka']==1)
{
echo "<img src='/farm/img/shield.gif' alt='' class='rpg' /> 侦察能力<br />";
}

if ($fuser['zabor_time']>time())
{
$vremja=$fuser['zabor_time']-time();

$timediff=$vremja;
$oneMinute=60; 
$oneHour=60*60; 
$oneDay=60*60*24; 
$dayfield=floor($timediff/$oneDay); 
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour); 
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute); 
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute)); 
if($dayfield>0)$day=$dayfield.'д. ';
$time_1=$day.$hourfield."ч. ".$minutefield."м.";

echo "<img src='/farm/img/electro.png' alt='' class='rpg' /> 电栅栏还在活动 <img src='/farm/img/time.png' alt='' class='rpg' />".$time_1."";
}
echo "</div>";
}

echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的农场</a></div>";
include_once '../sys/inc/tfoot.php';
?>