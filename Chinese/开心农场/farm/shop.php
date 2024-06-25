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
$set['title'] = '开心农场 :: 种子店';
include_once '../sys/inc/thead.php';
title();
err();

@$idpl = intval($_SESSION['plidb']);
$plant = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '$idpl'  LIMIT 1"));

if (isset($_GET['buy_ok'])) {
    add_farm_event('植物种子 ' . $plant['name'] . ' 成功收购');
}

if (isset($_GET['buy_no'])) {
    add_farm_event('没有足够的钱');
}

aut();
include 'inc/str.php';
farm_event();

if (isset($_GET['id'])) {
    include 'inc/shop_info.php';
} else {
    include 'inc/shop_index.php';
}

echo "<div class='rowdown'>";
if (isset($_GET['id'])) echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/shop/'>种子店</a><br/>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的农场</a><br/>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/'>返回</a>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
