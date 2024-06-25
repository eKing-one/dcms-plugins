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


$set['title'] = '买狗';
include_once '../sys/inc/thead.php';
title();
err();
aut();
$cena = 10000;


include 'inc/str.php';
if ($level >= 5) {
    $dg = dbresult(dbquery("SELECT COUNT(*) FROM `farm_dog` WHERE `time` > '" . time() . "' AND `id_user` = '" . $user['id'] . "'"), 0);

    if (isset($_GET['add_dog']) && $fuser['gold'] >= $cena) {
        $t = time() + 60 * 60 * 24 * 15;
       
        if ($dg != 0) {
            add_farm_event('有一只狗');
        } else {
            dbquery("UPDATE `farm_user` SET `gold` = `gold` - '" . $cena . "' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
            dbquery("UPDATE `farm_dog` SET `time` = '" . $t . "' WHERE `id_user` = '" . $user['id'] . "' LIMIT 1");
            add_farm_event('购买的狗成功');
        }
        
    }
    if (isset($_GET['add_dog']) && $fuser['gold'] <= $cena) {
        add_farm_event('没有足够的钱');
    }

    farm_event();

    echo "<div class='rowup'><center><img src='img/dog.png'></center><br />\n";
    echo "&raquo; 购买一只狗将花费你<img src='/farm/img/money.png' /> " . $cena . "<br />\n";
    echo "&raquo; 这只狗活了 15 天。\n";

    if ($dg != 0) {
        $dog = "有一只狗";
    } else {
        $dog = "没有狗";
    }
    echo "<br />当前: <b>" . $dog . "</b>.";

    if ($dg == 0) {
        echo "<form method='post' action='?add_dog' class='formfarm'>\n";
        echo "<input type='submit' name='save' value='获得一只狗' />";
        echo "</form>\n";
    }

    echo "</div><div class='rowdown'>";
    echo "<img src='/farm/img/garden.png' class='rpg' /> <a href='/farm/garden/'>我的农场</a><br/>";
    echo "&laquo; <a href='index.php'>返回</a><br/>";
    echo "</div>";
} else {
    echo "<div class='err'>你的等级不允许你购买狗。你需要一个高于 5 的等级。</div>";
}
include_once '../sys/inc/tfoot.php';
