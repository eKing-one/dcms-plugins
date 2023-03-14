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

$set['title']='趣味农场：：游戏';
include_once '../sys/inc/thead.php';
title();
err();
aut();

$dog = dbresult(dbquery("select count(*) from `farm_dog` WHERE  `id_user` = '$user[id]'  LIMIT 1"),0);
if ($dog==0)
{
dbquery("INSERT INTO `farm_dog` (`id_user`,`time`) VALUES  ($user[id],NULL) ");
}
$post = dbresult(dbquery("select count(*) from `farm_gr` WHERE  `id_user` = '$user[id]'  LIMIT 1"),0);
if ($post<5)
{
dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '".$user['id']."') ");
dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '".$user['id']."') ");
dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '".$user['id']."') ");
dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '".$user['id']."') ");
dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '".$user['id']."') ");
}

include_once 'inc/str.php';
farm_event();

echo "<div class='rowdown'><center><img src='img/logo.png' alt='' /></center></div>";

echo "<div class='mdlc'><span>游戏菜单</span><br /></div><div class='menu'>";

echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的农场</a> (到床边去)<br/>";
echo "<img src='/farm/img/serdechko.png' alt='' class='rpg' /> <a href='/farm/dining'>食堂</a> (补充健康)<br/>";
echo "<img src='/farm/img/money.png' alt='' class='rpg' /> <a href='/farm/exchanger'>交换器</a> (游戏货币兑换商)<br/>";
echo "<img src='/farm/img/pet.gif' alt='' class='rpg' /> <a href='dog.php'>我的狗。</a> (买条狗)<br/>";
echo "<img src='/farm/img/warehouse.png' alt='' class='rpg' /> <a href='/farm/sklad'>我的仓库</a> (种子库)<br/>";
echo "<img src='/farm/img/warehouse.png' alt='' class='rpg' /> <a href='/farm/ambar'>我的谷仓</a> (产品仓库)<br/>";
echo "<img src='/farm/img/shop.png' alt='' class='rpg' /> <a href='/farm/shop/'>种子店</a> (52 种)<br/>";
echo "<img src='/farm/img/village.png' alt='' class='rpg' /> <a href='shop_udobr.php'>肥料商店</a> (5 种)<br/>";
echo "<img src='/farm/img/irrigation.png' alt='' class='rpg' /> <a href='/farm/shop_combine.php'>设备商店</a> (2 种)<br/>";
echo "<img src='/farm/img/harvest.png' alt='' class='rpg' /> <a href='/farm/fermers/'>全体农民</a> (农民名单)<br/>";
echo '</div>';

include_once '../sys/inc/tfoot.php';
?>