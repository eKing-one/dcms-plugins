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
$set['title']='开心农场 :: 技能 :: 温室';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['learn']) && $fuser['teplica']==0)
{
if ($fuser['gems']<35)
{
add_farm_event('你没有足够的钻石来学习这个技能');
}
else
{
dbquery("UPDATE `farm_user` SET `teplica` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'35' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功学习了 [b] 温室 [/b] 技能，价值 35 钻石');
}
}

include_once '../inc/str.php';
farm_event();
echo "<div class='rowup'>技能 :: 温室</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/tep.gif' alt='' /></td><td>技巧 <b>温室</b> 消除了天气的不利影响</td></tr></table>";
if ($fuser['teplica']==0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?learn'>学习技能 <img src='/farm/img/gems.png' alt='' class='rpg' />35</a></span>";
}
else
{
echo "<img src='/img/accept.png' alt='' class='rpg' /> 已经学会的技能";
}
echo "</div>";

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>对技能列表</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的土地</a>";
echo "</div>";
include_once '../../sys/inc/tfoot.php';
?>