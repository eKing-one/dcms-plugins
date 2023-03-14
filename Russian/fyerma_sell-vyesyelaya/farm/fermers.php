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
$set['title']='欢乐农场 :: 农民';
include_once '../sys/inc/thead.php';
title();
aut();

include_once 'inc/str.php';
farm_event();

$k_post=dbresult(dbquery("SELECT COUNT(*) FROM `farm_user`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];


if ($k_post==0)
{
echo "<div class='err'>";
echo "Нет результатов\n";
echo "</div>";
}

$q=dbquery("SELECT * FROM `farm_user` ORDER BY `exp` DESC LIMIT $start, $set[p_str]");
while ($ank = dbarray($q))
{

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

include "inc/fermer_level.php";

$fank=dbarray(dbquery("SELECT * FROM `user` WHERE `id` = '".$ank['uid']."' LIMIT 1"));

$cntgr=dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '".$ank['uid']."'"), 0);

if ($ank['uid']!=$user['id'])
{
echo "".online($ank['uid'])." ";
echo "<a href='/farm/fermers_gr.php?id=".$fank['id']."'>".$fank['nick']."</a> (".$cntgr.")";
}
else
{
echo "".online($fank['id'])." ";
echo "".$fank['nick']." (".$cntgr.")";
}

$dg = dbresult(dbquery("SELECT COUNT(*) FROM `farm_dog` WHERE `time` > '".time()."' AND `id_user` = '".$fank['id']."'"), 0);
if ($dg!=0)
{
$dog = ", собака есть";
}
else
{
$dog = ", собаки нет";
}

$afuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$fank['id']."' LIMIT 1"));
if ($afuser['zabor_time']>time())
{
$zabor=", электрозабор активен";
}
else
{
$zabor=", электрозабор не активен";
}

if ($fuser['razvedka']==0)
{
$dog = "";
$zabor = "";
}

echo "<br />Уровень ".$level."".$dog."".$zabor."";
echo "</div>";
unset($level);
unset($dog);
unset($cntgr);
unset($zabor);
}


if ($k_page>1)str("?",$k_page,$page); // Вывод страниц

echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>На ферму</a>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
?>