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
$set['title'] = '开心农场 :: 帮助';
include_once '../sys/inc/thead.php';
title();
err();
aut();

include 'inc/str.php';
farm_event();

if (isset($_GET['weather'])) {
    echo "<div class='rowup'>";

    if ($fconf['weather'] == 1) {
        $uro = "+3";
        $name = "温暖";
    }
    if ($fconf['weather'] == 2) {
        $uro = "+2";
        $name = "多云";
    }
    if ($fconf['weather'] == 3) {
        $uro = "-1";
        $name = "大雨";
        $uron = "-1";
    }
    if ($fconf['weather'] == 4) {
        $uro = "-3";
        $name = "雷雨";
        $uron = "-3";
    }
    if ($fconf['weather'] == 5) {
        $uro = "+1";
        $name = "晴天";
    }

    if ($fuser['teplica'] == 1) {
        if ($uro < 0) {
            $uro = 0;
            $mess = true;
        }
    }

    echo "<img src='/farm/img/garden.png' alt='' class='rpg' />当前天气: <img src='/farm/weather/" . $fconf['weather'] . ".png' alt='' />" . $name . "<br />";
    echo "<img src='/img/add.png' alt='' class='rpg' /> 影响: " . $uro . " ";
    if (isset($mess)) {
        echo "(" . $uron . ") ";
    }
    echo "收获";
    if (isset($mess)) {
        echo "<br /><img src='/img/accept.png' alt='' class='rpg' /> 你已经学习了<b>Teplitsa</b>的技能。天气的负面影响不影响你的收成";
    }
    echo "</div>";
}

if (!isset($_GET['weather'])) {
    echo "<div class='rowup'><b>我什么时候可以再加一张土地？</b></div>";
    echo "<div class='rowdown'>一开始，你有五个免费的土地。后续的比之前的贵 500 黄金。一旦你有了 6 个土地，其余的需要一定的等级。<br/>";
    echo "你可以在设置以下级别后购买土地： <b>5, 10, 15, 20, 25, 30, 35, 40, 45, 50</b>.<br />";
    echo "结果，你最多有<b>十六个</b>土地。<br />";
    echo "现在你的等级: " . $level . ", 黄金 " . $fuser['gold'] . "";
}

echo "</div><div class='rowup'>";
echo "<img src='/img/back.png' alt='返回' class='rpg' /> <a href='/farm/garden/'>我的土地</a><br/>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
