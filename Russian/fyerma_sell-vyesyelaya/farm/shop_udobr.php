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
$set['title']='欢乐农场：：肥料店';
include_once '../sys/inc/thead.php';
title();
err();
$udid=intval($_SESSION['udid']);
$post = dbarray(dbquery("select * from `farm_udobr_name` WHERE  `id` = '".$udid."'  LIMIT 1")); 

if(isset($_GET['buy_ok']))add_farm_event('你成功地购买了肥料 '.$udid['name'].'');
if(isset($_GET['buy_no']))add_farm_event('你没有足够的钱做这个手术');
aut();
include 'inc/str.php';
farm_event();

if(isset($_GET['id'])){
include 'inc/shop_udobr_info.php';
}else{
include 'inc/shop_udobr_index.php';
}

echo "<div class='rowdown'>";
if(isset($_GET['id']))echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/shop_udobr.php'>肥料商店</a><br />";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的农场。</a><br />";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/'>后退</a>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
?>