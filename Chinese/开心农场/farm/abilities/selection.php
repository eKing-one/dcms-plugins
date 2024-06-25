<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
only_reg();
$set['title']='开心农场 :: 技能 :: 育种';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['selection_up']))
{
if ($fuser['selection']==0 && $fuser['gems']>=8)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'8' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你成功地完成了第一期育种技能培训课程。花了 8 颗钻石');
}
if ($fuser['selection']==0 && $fuser['gems']<8)
{
$cntt=8-$fuser['gems'];
add_farm_event('你不够。 '.$cntt.' 一级技能 [B] 选择 [/B]');
}

if ($fuser['selection']==1 && $fuser['gems']>=12)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'12' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '2' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功完成了选择技能的第二个课程。花费 12 个钻石');
}
if ($fuser['selection']==1 && $fuser['gems']<12)
{
$cntt=12-$fuser['gems'];
add_farm_event('你没有足够的'.$cntt.' 学习 [b] 选择 [/b]2 级技能的钻石');
}

if ($fuser['selection']==2 && $fuser['gems']>=15)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'15' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '3' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功完成了技能选择中的第三个课程。花费了 15 颗钻石');
}
if ($fuser['selection']==2 && $fuser['gems']<15)
{
$cntt=15-$fuser['gems'];
add_farm_event('你没有足够的'.$cntt.' 学习 [b] 选择 [/b]3 级技能的钻石');
}

if ($fuser['selection']==3 && $fuser['gems']>=20)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'20' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '4' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功完成了选择技能的第四课。花费了 20 颗钻石');
}
if ($fuser['selection']==3 && $fuser['gems']<20)
{
$cntt=20-$fuser['gems'];
add_farm_event('你没有足够的'.$cntt.'学习 [b] 选择 [/b] 技能的钻石，最高可达四级');
}

if ($fuser['selection']==4 && $fuser['gems']>=30)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `selection` = '5' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功完成了选择技能的第五个课程。花费了 30 颗钻石');
}
if ($fuser['selection']==4 && $fuser['gems']<30)
{
$cntt=30-$fuser['gems'];
add_farm_event('你没有足够的'.$cntt.' 学习 [b] 选择 [/b] 技能的钻石，最高可达五级');
}
}

include_once H.'/farm/inc/str.php';
farm_event();

echo "<div class='rowup'>技能 :: 饲养</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/plus.gif' alt='' /></td><td>";
echo "增加收获时的产量</td></tr></table>";

if ($fuser['selection']==0)
{
echo "<img src='/img/deletemail.gif' alt='' class='rpg' /> 这是你还没有学会的技能";
}

if ($fuser['selection']==1)
{
$nameur="一级";
$cost="12";
}

if ($fuser['selection']==2)
{
$nameur="二级";
$cost="15";
}

if ($fuser['selection']==3)
{
$nameur="第三次";
$cost="20";
}

if ($fuser['selection']==4)
{
$nameur="第四次";
$cost="30";
}

if ($fuser['selection']==5)
{
$nameur="第五次（最多）";
}

if ($fuser['selection']!=0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> 你对该技能的熟练程度 - <span class='underline'>".$nameur."</span>";
}

echo "<br />";
echo "&raquo; 第 1 级--收益率 +5% (<img src='/farm/img/gems.png' alt='' class='rpg' />8)<br />&raquo; 2 级--收益率 +10% (<img src='/farm/img/gems.png' alt='' class='rpg' />12)<br />&raquo; 3 级--收益率 +15% (<img src='/farm/img/gems.png' alt='' class='rpg' />15)<br />&raquo; 4 级 - 产量 +20% (<img src='/farm/img/gems.png' alt='' class='rpg' />20)<br />&raquo; 第 5 级 - 产量增+ 25% (<img src='/farm/img/gems.png' alt='' class='rpg' />30)<br />";

if ($fuser['selection']==0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?selection_up'>升级 <img src='/farm/img/gems.png' alt='' class='rpg' />8</a></span><br />";
}

if ($fuser['selection']>0 && $fuser['selection']<5)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?selection_up'>提高技能等级，为 <img src='/farm/img/gems.png' alt='' class='rpg' />".$cost."</a></span><br />";
}

if ($fuser['selection']==5)
{
echo "<img src='/img/accept.png' alt='' class='rpg' /> <span class='underline'>你已经完成了这项技能的所有训练</span><br />";
}

echo '</div>';

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>对技能列表</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的土地</a>";
echo "</div>";

include_once '../../sys/inc/tfoot.php';
?>