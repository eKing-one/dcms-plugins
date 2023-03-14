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
$set['title']='Весёлая ферма :: Магазин техники';
include_once '../sys/inc/thead.php';
title();
aut();

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

if (isset($_GET['seeder_up']))
{
if ($fuser['k_posadka']==0 && $fuser['gems']>=100)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'100' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_posadka` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功购买了[b]一级播种机 [/b]。花了 100 颗钻石');
}
if ($fuser['k_posadka']==0 && $fuser['gems']<100)
{
$cntt=100-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 购买钻石[b]播种机[/b]一级');
}

if ($fuser['k_posadka']==1 && $fuser['gems']>=30)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_posadka` = '2' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你成功地将 [B] 播种机 [/B] 升级到二级。花了 30 颗钻石');
}
if ($fuser['k_posadka']==1 && $fuser['gems']<30)
{
$cntt=30-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 将[B] 播种机 [/B]  升级为二级');
}

if ($fuser['k_posadka']==2 && $fuser['gems']>=50)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'50' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_posadka` = '3' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你成功地将 [B] 播种机 [/B] 升级到了第三级。花了 50 颗钻石');
}
if ($fuser['k_posadka']==2 && $fuser['gems']<50)
{
$cntt=50-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 将[B] 播种机 [/B]  升级到第三级的钻石');
}
header("Location: /farm/shop_combine.php?seeder");
exit();
}


if (isset($_GET['irrigation_up']))
{
if ($fuser['k_poliv']==0 && $fuser['gems']>=100)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'100' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_poliv` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功购买了一级喷洒器。花了 100 颗钻石');
}
if ($fuser['k_poliv']==0 && $fuser['gems']<100)
{
$cntt=100-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 用于购买第一级喷洒器的钻石');
}

if ($fuser['k_poliv']==1 && $fuser['gems']>=30)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_poliv` = '2' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功地把喷水器升级到二级了。花了 30 颗钻石');
}
if ($fuser['k_poliv']==1 && $fuser['gems']<30)
{
$cntt=30-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 将喷头 [/B] 升级为二级');
}

if ($fuser['k_poliv']==2 && $fuser['gems']>=50)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`-'50' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_poliv` = '3' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('你已经成功地将喷水器升级到了第三级。花了 50 颗钻石');
}
if ($fuser['k_poliv']==2 && $fuser['gems']<50)
{
$cntt=50-$fuser['gems'];
add_farm_event('你不够。'.$cntt.' 将喷头 [/B] 升级到第三级');
}
header("Location: /farm/shop_combine.php?irrigation");
exit();
}



include_once H.'/farm/inc/str.php';
farm_event();


if (isset($_GET['seeder']))
{
echo "<div class='rowup'>";
echo "<table class='post'><tr><td><img src='/farm/img/seeder_big.png' alt='' /></td><td>";
echo "用套式方法自动播种的机器。减轻农民的劳动——一键播种所有苗床</td></tr></table>";
echo "仅用于在所有苗床上播种一种种子。<br />";

if ($fuser['k_posadka']==1)
{
echo "在这个水平上，它一次在所有地块上播种，每床花费 10 秒钟，并产生额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />50K 使用经验<br />";
}
if ($fuser['k_posadka']==2)
{
echo "在这个水平上，它一次在所有地块上播种，每床花费 5 秒钟，并产生额外的<img src='/farm/img/exp.png' alt='' class='rpg' />在其上使用时有 100K 的经验<br />";
}
if ($fuser['k_posadka']==3)
{
echo "在这个水平上，它一次在所有地块上播种，每床花费 3 秒，并产生额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />150 在其上使用时的经验<br />";
}


if ($fuser['k_posadka']==0)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>买一级播种机换<img src='/farm/img/gems.png' alt='' class='rpg' />100</a></span><br />";
echo "将一次在所有苗床上播种，在每个地块上花费 10 秒钟，并给出<img src='/farm/img/exp.png' alt='' class='rpg' />50 转体验";
}

if ($fuser['k_posadka']==1)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>Улучшить сеялку до второго уровня за <img src='/farm/img/gems.png' alt='' class='rpg' />30</a></span><br />";
echo "Будет садить семена на все грядки за раз, расходовать 5 секунд на каждый участок и давать <img src='/farm/img/exp.png' alt='' class='rpg' />100 к опыту";
}

if ($fuser['k_posadka']==2)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>Улучшить сеялку до третьего уровня за <img src='/farm/img/gems.png' alt='' class='rpg' />50</a></span><br />";
echo "Будет садить семена на все грядки за раз, расходовать 3 секунды на каждый участок и давать <img src='/farm/img/exp.png' alt='' class='rpg' />150 к опыту";
}

if ($fuser['k_posadka']==3)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'>Сеялка третьего уровня</span> (максимальный уровень)<br />";
echo "На данном уровне садит семена на все грядки за раз, расходует по 3 секунды на каждый участок и даёт <img src='/farm/img/exp.png' alt='' class='rpg' />150 к опыту";
}
echo '</div>';
}

if (isset($_GET['irrigation']))
{

echo "<div class='rowup'>";
echo "<table class='post'><tr><td><img src='/farm/img/irrigation_big.png' alt='' /></td><td>";
echo "用于菜园植物灌溉机械化的灌溉系统。减轻了农民的劳动——一键浇灌所有的苗床</td></tr></table>";
echo "每次浇水前激活<br />";

if ($fuser['k_poliv']==1)
{
echo "在这个水平上，它一次浇灌所有地块，每床花费 10 秒，并额外提供 <img src='/farm/img/exp.png' alt='' class='rpg' />50K 使用经验<br />";
}
if ($fuser['k_poliv']==2)
{
echo "在这个水平上，它一次浇灌所有地块，每床用 5 秒钟，并给出额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />在其上使用时有 100K 的经验<br />";
}
if ($fuser['k_poliv']==3)
{
echo "在这个水平上，它一次浇灌所有地块，每块床花费 3 秒，并提供额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />150 在其上使用时的经验<br />";
}


if ($fuser['k_poliv']==0)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>购买一级喷头 <img src='/farm/img/gems.png' alt='' class='rpg' />100</a></span><br />";
echo "每次浇水，每块地用 10 秒钟，给 <img src='/farm/img/exp.png' alt='' class='rpg' />50 转体验";
}

if ($fuser['k_poliv']==1)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>提前将喷头提升至二级 <img src='/farm/img/gems.png' alt='' class='rpg' />30</a></span><br />";
echo "每次浇水，每块地用 5 秒钟，给 <img src='/farm/img/exp.png' alt='' class='rpg' />100 к опыту";
}

if ($fuser['k_poliv']==2)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>提前将喷头提升至三级 <img src='/farm/img/gems.png' alt='' class='rpg' />50</a></span><br />";
echo "每次浇水，每块地用 3 秒钟，给出 <img src='/farm/img/exp.png' alt='' class='rpg' />150 к опыту";
}

if ($fuser['k_poliv']==3)
{
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'>三级洒水器</span>（最高液位）<br />";
echo "在这个水平上，它一次浇灌所有的苗床，每块用 3 秒，给出 <img src='/farm/img/exp.png' alt='' class='rpg' />150 к опыту";
}

echo "</div>";
}

if (!isset($_GET['irrigation']))
{
echo "<div class='rowdown'><img src='/farm/img/irrigation_small.png' alt='' class='rpg' /> <a href='?irrigation'>喷洒器</a>";
if ($fuser['k_poliv']!=0)
{
echo " (".$fuser['k_poliv'].")";
}
echo "</div>";
}
if (!isset($_GET['seeder']))
{
echo "<div class='rowdown'>";
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <a href='?seeder'>播种机</a>";
if ($fuser['k_posadka']!=0)
{
echo " (".$fuser['k_posadka'].")";
}
echo "</div>";
}
echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的床</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
?>