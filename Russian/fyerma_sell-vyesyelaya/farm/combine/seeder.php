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
$set['title']='播种机：：种子选择';
include_once '../../sys/inc/thead.php';
title();
err();

include_once '../inc/str.php';
farm_event();

if (isset($_GET['select']))
{
$k_post=dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id_user` = '".$user['id']."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
add_farm_event('你仓库里没有种子。你被转到种子店了');
header("Location: /farm/shop/");
exit();
}

$res = dbquery("select * from `farm_semen` WHERE `id_user` = '".$user['id']."' LIMIT $start, $set[p_str];");

while ($post = dbarray($res)){
if ($num==1)
{
echo "<div class='rowdown'>";
$num=0;
}
else
{
echo "<div class='rowup'>";
$num=1;
}

$semen=dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '".$post['semen']."'  LIMIT 1")); 
echo "<img src='/farm/plants/".$post['semen'].".png' height='12' width='12'> <a href='/farm/combine/seeder.php?id=".$post['id']."&start'>".$semen['name']."</a> (".$post['kol']." шт.)";
echo "</div>";
}
if ($k_page>1)str('?',$k_page,$page);

echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>取消</a>";
echo "</div>";
include_once H.'sys/inc/tfoot.php';
exit();
}


if (isset($_GET['start']) && isset($_GET['id']) && is_numeric($_GET['id']))
{

$id=abs(intval($_GET['id']));

$chk=dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id` = '".$id."' AND `id_user` = '".$user['id']."'"), 0);

if ($chk==0)
{
add_farm_event('这些种子不是你的，或者它们不存在');
header("Location: /farm/garden/");
exit();
}

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

$post=dbarray(dbquery("SELECT * FROM `farm_semen` WHERE `id` = '".$id."' LIMIT 1"));
$plant=dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '".$post['semen']."'  LIMIT 1"));
$irnum=dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '".$user['id']."' AND `semen` = '0'"), 0);

if ($post['kol']<$irnum)
{
add_farm_event('没有足够的种子');
header("Location: /farm/garden/");
exit();
}

if ($fuser['k_posadka']!=0)
{
$irnum=dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '".$user['id']."' AND `semen` = '0'"), 0);
if ($irnum!=0)
{
if ($fuser['k_posadka']==1)
{
$irtime=10*$irnum;
$irexp=50*$irnum;
}
if ($fuser['k_posadka']==2)
{
$irtime=5*$irnum;
$irexp=100*$irnum;
}
if ($fuser['k_posadka']==3)
{
$irtime=3*$irnum;
$irexp=150*$irnum;
}
$nwtime=time()+$irtime;

dbquery("UPDATE `farm_user` SET `posadka` = `posadka`+'$irnum' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `exp` = `exp`+'$irexp' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `k_posadka_time` = '".$nwtime."' WHERE `uid` = '".$user['id']."' LIMIT 1");

$query=dbquery("SELECT * FROM `farm_gr` WHERE `id_user` = '".$user['id']."' AND `semen` = '0'");
while ($gr=dbarray($query))
{
$tmna=time()+$plant['time'];
dbquery("UPDATE `farm_gr` SET `time` = '".$tmna."' WHERE `id` = '".$gr['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `semen` = '".$post['semen']."' WHERE `id` = '".$gr['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = '".$gr['id']."' LIMIT 1");
if ($post['kol']>=2)
{
dbquery("UPDATE `farm_semen` SET `kol` = `kol`-'1' WHERE `id` = ".$id." LIMIT 1");
}
else
{
dbquery("DELETE FROM `farm_semen` WHERE `id` = ".$id."");
}
}

add_farm_event('成功种植 '.$irnum.' 植物 '.$plant['name'].'. 支出 '.$irtime.' 秒，收到 '.$irexp.' 经验');

}
else
{
add_farm_event('没有空闲的床');
}

}
else
{
add_farm_event('你没有播种机');
}
}
if (!isset($_GET['select']))
{
header("Location: /farm/garden/");
exit();
}
?>