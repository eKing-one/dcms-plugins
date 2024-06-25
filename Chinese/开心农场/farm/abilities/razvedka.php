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
$set['title']='开心农场 :: 技能 :: 侦察';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['learn']) && $fuser['razvedka']==0)
{
if ($fuser['gems']<35)
{
add_farm_event('你没有足够的钻石来学习这个技能');
}
else
{
dbquery("UPDATE `farm_user` SET `razvedka` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'35' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你用 35 颗钻石成功地学会了 [b] 切割 [/b] 的技能');
}
}

include_once '../inc/str.php';
farm_event();
echo "<div class='rowup'>技能 :: 侦察</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/shield.gif' alt='' /></td><td>技能 <b>侦察</b> 让你有机会看到一只狗在一个被抢劫的农民的栅栏后面。它还提供了确定电流是否在电栅栏中传导的能力。</td></tr></table>";
if ($fuser['razvedka']==0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?learn'>学习技能 <img src='/farm/img/gems.png' alt='' class='rpg' />35</a></span>";
}
else
{
echo "<img src='/img/accept.png' alt='' class='rpg' /> 技能已经学会了";
}
echo "</div>";

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>到技能列表</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的土地</a>";
echo "</div>";
include_once '../../sys/inc/tfoot.php';
?>