<?
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
$set['title']='趣味农场：：技能：：温室';
include_once '../../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['learn']) && $fuser['teplica']==0)
{
if ($fuser['gems']<35)
{
add_farm_event('Для изучения данного умения у Вас не хватает алмазов');
}
else
{
dbquery("UPDATE `farm_user` SET `teplica` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'35' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы успешно изучили умение [b]Теплица[/b] за 35 алмазов');
}
}

include_once '../inc/str.php';
farm_event();
echo "<div class='rowup'>Умение :: Теплица</div>";
echo "<div class='rowdown'>";
echo "<table class='post'><tr><td><img src='/farm/img/tep.gif' alt='' /></td><td>Умение <b>Теплица</b> устраняет отрицательный эффект погоды</td></tr></table>";
if ($fuser['teplica']==0)
{
echo "<img src='/img/add.png' alt='' class='rpg' /> <span class='underline'><a href='?learn'>Выучить умение за <img src='/farm/img/gems.png' alt='' class='rpg' />35</a></span>";
}
else
{
echo "<img src='/img/accept.png' alt='' class='rpg' /> Умение уже изучено";
}
echo "</div>";

echo "<div class='rowdown'>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/abilities/'>К списку умений</a><br />";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>Мои грядки</a>";
echo "</div>";
include_once '../../sys/inc/tfoot.php';
?>