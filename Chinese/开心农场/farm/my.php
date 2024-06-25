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
$set['title'] = '开心农场 :: 我的小土地';
include_once '../sys/inc/thead.php';
title();
err();
aut();

include 'inc/str.php';
farm_event();


if (isset($_GET['add_ok'])) add_farm_event('苗土地采购成功');
if (isset($_GET['udobr_ok'])) add_farm_event('植物施肥成功');

if (isset($_GET['sob_ok'])) {
    $semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '" . intval($_SESSION['pid']) . "' "));
    add_farm_event('你成功地收集了 ' . $semen1['name'] . '. 经验 +' . intval($_SESSION['opyt']) . ', 健康 -2.');
    unset($_SESSION['pid']);
}

if (isset($_GET['watok'])) {
    add_farm_event('你成功地给土地浇水了。收获前的时间缩短了 [B] 半小时 [/B]。经验 +1，健康-1。');
}

if (isset($_GET['saditok'])) {
    $semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '" . intval($_SESSION['pid']) . "' "));
    add_farm_event('你成功种植了' . $semen1['name'] . '. 经验 +1, 健康 -1.');
}

if (isset($_GET['add_no'])) {
    add_farm_event('你的等级太低了，或者你没有足够的钱承诺新的土地');
}
if (isset($_GET['gr_add']) && $fuser['gold'] >= 5000) {
    $k_gr = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '$user[id]'"), 0);
    if ($k_gr == 5) {
        $cost = 500;
        $lvl = 0;
    }
    if ($k_gr == 6) {
        $cost = 1000;
        $lvl = 5;
    }
    if ($k_gr == 7) {
        $cost = 1500;
        $lvl = 10;
    }
    if ($k_gr == 8) {
        $cost = 2000;
        $lvl = 15;
    }
    if ($k_gr == 9) {
        $cost = 2500;
        $lvl = 20;
    }
    if ($k_gr == 10) {
        $cost = 3000;
        $lvl = 25;
    }
    if ($k_gr == 11) {
        $cost = 3500;
        $lvl = 30;
    }
    if ($k_gr == 12) {
        $cost = 4000;
        $lvl = 35;
    }
    if ($k_gr == 13) {
        $cost = 4500;
        $lvl = 40;
    }
    if ($k_gr == 14) {
        $cost = 5000;
        $lvl = 45;
    }
    if ($k_gr == 15) {
        $cost = 5500;
        $lvl = 50;
    }

    if (($fuser['gold'] >= $cost) and ($level >= $lvl)) {
        dbquery("UPDATE `farm_user` SET `farm_gold` = " . ($fuser['gold'] - $cost) . " WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("INSERT INTO `farm_gr` (`semen`, `id_user`) VALUES  ( '0', '" . $user['id'] . "') ");
        header('Location: /farm/garden/?add_ok');
    } else {
        header('Location: /farm/garden/?add_no');
    }
}


if (isset($_GET['add'])) {
    $k_gr = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '$user[id]'"), 0);
    if ($k_gr == 5) {
        $cost = 500;
        $lvl = 0;
    }
    if ($k_gr == 6) {
        $cost = 1000;
        $lvl = 5;
    }
    if ($k_gr == 7) {
        $cost = 1500;
        $lvl = 10;
    }
    if ($k_gr == 8) {
        $cost = 2000;
        $lvl = 15;
    }
    if ($k_gr == 9) {
        $cost = 2500;
        $lvl = 20;
    }
    if ($k_gr == 10) {
        $cost = 3000;
        $lvl = 25;
    }
    if ($k_gr == 11) {
        $cost = 3500;
        $lvl = 30;
    }
    if ($k_gr == 12) {
        $cost = 4000;
        $lvl = 35;
    }
    if ($k_gr == 13) {
        $cost = 4500;
        $lvl = 40;
    }
    if ($k_gr == 14) {
        $cost = 5000;
        $lvl = 45;
    }
    if ($k_gr == 15) {
        $cost = 5500;
        $lvl = 50;
    }




    if ($level >= $lvl) {
        echo "<div class='rowup'><img src='/farm/img/garden.png' alt='' /> <a href='/farm/garden/'>你的菜园</a> / <b>购买土地</b></div>";
        echo "<div class='rowdown'>一个土地的成本为： " . $cost . " <br />";
        echo "需要等级: " . $lvl . "</div>";
        echo "<form method='post' action='/farm/garden/?gr_add'>\n";
        echo "<input type='submit' name='save' value='购买' />\n";
        echo "</form>\n";
    } else {
        echo "<div class='rowup'><img src='/farm/img/garden.png' alt='' /> <a href='/farm/garden/'>你的菜园</a> / <b>购买土地</b></div>";
        echo "<b>你不能购买土地。</b><br />";
        echo "<b>农场的等级不够？ </b>添加另一个块土地，你需要更多的<b>经验</b>。<br />";
        echo "<b>没有足够的钱？</b>在论坛上交流，在日记中写笔记和评论别人的东西，在报纸上写文章，在聊天中交流和猜测问题。<br />";
    }
} else {





    $k_post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '" . $user['id'] . "'"), 0);
    $k_page = k_page($k_post, $set['p_str']);
    $page = page($k_page);
    $start = $set['p_str'] * $page - $set['p_str'];

    farm_event();

    echo "<div class='mdlc'><span>你的土地清单[" . $k_post . "]</span><br /></div>";
    echo "<div class='menu'>";

    $res = dbquery("select * from `farm_gr` WHERE `id_user` = '" . $user['id'] . "'  LIMIT $start, $set[p_str];");

    while ($post = dbarray($res)) {
        if ($num == 1) {
            echo "<div class='rowdown'>";
            $num = 0;
        } else {
            echo "<div class='rowup'>";
            $num = 1;
        }


        $semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));
        if ($post['semen'] == 0) {
            $name_gr = '未播种';
        } else {
            $name_gr = $semen['name'];
        }

        $vremja = $post['time'] - time();

        $timediff = $vremja;
        $oneMinute = 60;
        $oneHour = 60 * 60;
        $oneDay = 60 * 60 * 24;
        $dayfield = floor($timediff / $oneDay);
        $hourfield = floor(($timediff - $dayfield * $oneDay) / $oneHour);
        $minutefield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour) / $oneMinute);
        $secondfield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour - $minutefield * $oneMinute));
        if ($dayfield > 0) $day = $dayfield . '天';
        else $day = '';

        if (time() < $post['time']) $time_1 = "(" . $day . $hourfield . "时" . $minutefield . "分)";
        else $time_1 = " (<span class='off'>收集时间到了</span>)";

        if ($post['semen'] != 0 && time() > $post['time'] && $post['kol'] == 0) {
            $pt = rand($semen['rand1'], $semen['rand2']);
            dbquery("UPDATE `farm_gr` SET `kol` = '" . $pt . "' WHERE `id` = '" . $int . "' LIMIT 1");
        }

        if ($post['semen'] == 0) {
            $plcnt = dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id_user` = '" . $user['id'] . "'"), 0);
            if ($plcnt == 0) {
                $act = "仓库里没有种子 <a href='/farm/shop.php'>购买</a>";
            }
            if ($plcnt != 0) {
                $plant = dbarray(dbquery("SELECT * FROM `farm_semen` WHERE `id_user` = '" . $user['id'] . "' ORDER BY id DESC LIMIT 1"));
                $plnew = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $plant['semen'] . "' LIMIT 1"));
                $act = "<img src='/farm/img/plant.png' alt='' class='rpg' /> 播种 <a href='/farm/gr.php?id=" . $post['id'] . "&plantid=" . $plant['id'] . "&posadka'>" . $plnew['name'] . "</a> 或 <a href='/farm/gr.php?id=" . $post['id'] . "'>其它植物</a>";
            }
        }

        if ($post['semen'] != 0) {
            if ($post['time_water'] < time()) {
                $act = "<img src='/farm/img/water.png' alt='' class='rpg' /> <a href='/farm/gr.php?id=$post[id]&water'>浇水</a>";
            }

            if ($post['time'] < time()) {
                $act = "<img src='/farm/img/harvest.png' alt='' class='rpg' /> <a href='/farm/gr/" . $post['id'] . "'>收集</a>";
            }
        }


        if (
            $post['semen'] != 0 &&
            $post['time'] > time() && $post['time_water'] > time()
        ) {
            $wost = $post['time_water'] - time();
            $timediff = $wost;
            $oneMinute = 60;
            $oneHour = 60 * 60;
            $oneDay = 60 * 60 * 24;
            $dayfield = floor($timediff / $oneDay);
            $hourfield = floor(($timediff - $dayfield * $oneDay) / $oneHour);
            $minutefield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour) / $oneMinute);
            $secondfield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour - $minutefield * $oneMinute));
            if ($dayfield > 0) $day = $dayfield . '天';
            else $day = '';
            $time_wost = $day . $hourfield . "时" . $minutefield . "分";
            $act = "<img src='/farm/img/time.png' alt='' class='rpg' /> 浇水前 " . $time_wost . "";
        }

        if ($post['semen'] != 0 && $post['udobr'] == 0 && $post['time_water'] > time()) {
            $act = "<img src='/farm/img/fertilize.png' alt='' class='rpg' /> <a href='/farm/gr/" . $post['id'] . "'>施肥</a>";
        }

        if ($post['semen'] != 0 && $post['udobr'] == 1 && $post['time_water'] > time() && $post['time'] > time() && $post['time'] < $post['time_water']) {
            $act = "<img src='/farm/img/time.png' alt='' class='rpg' /> 收获剩余： " . $time_1 . "";
        }

        echo "<table class='post'><tr><td rowspan='2' style='width:30px'>";
        echo "<img src='/farm/plants/" . $post['semen'] . ".png' height='30' width='30' alt='" . $name_gr . "' /></td><td> <a href='/farm/gr/" . $post['id'] . "'>" . $name_gr . "</a>";
        if ($post['semen'] != 0)
            echo " " . $time_1 . "";
        echo "</td>";
        echo "</tr>";
        echo "<tr><td>";
        echo "" . $act . "";
        echo "</td></tr>";
        echo "</table>";
        echo "</div>";
    }
    echo "</div>";


    if ($k_page > 1) str('?', $k_page, $page);
}

$irnum = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '" . $user['id'] . "' AND `time`>'" . time() . "' AND `time_water`<'" . time() . "'"), 0);
if ($irnum != 0 && $fuser['k_poliv'] != 0) {
    if ($fuser['k_poliv'] == 1) {
        $irtime = 10 * $irnum;
        $irexp = 50 * $irnum;
    }
    if ($fuser['k_poliv'] == 2) {
        $irtime = 5 * $irnum;
        $irexp = 100 * $irnum;
    }
    if ($fuser['k_poliv'] == 3) {
        $irtime = 3 * $irnum;
        $irexp = 150 * $irnum;
    }
    echo "<div class='rowdown'>";
    echo "<img src='/farm/img/irrigation_small.png' alt='' class='rpg' /> <a href='/farm/shop_combine.php?irrigation'>喷洒器</a> (" . $fuser['k_poliv'] . "/3 级, " . $irtime . " 秒, +<img src='/farm/img/exp.png' alt='' />" . $irexp . ")<br />";
    echo "<img src='/farm/img/water.png' alt='' class='rpg' /> <a href='/farm/combine/irrigation.php?start'>给花土地浇水</a>";
    echo "</div>";
}

$plcont = dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id_user` = '" . $user['id'] . "'"), 0);
if ($plcont != 0) {
    $plseed = dbarray(dbquery("SELECT * FROM `farm_semen` WHERE `id_user` = '" . $user['id'] . "' ORDER BY id DESC LIMIT 1"));
    $pln = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $plseed['semen'] . "' LIMIT 1"));
    $act = "<img src='/farm/img/plant.png' alt='' class='rpg' /> 种植 <a href='/farm/gr.php?id=" . $post['id'] . "&plantid=" . $plseed['id'] . "&posadka'>" . $pln['name'] . "</a> 或 <a href='/farm/gr.php?id=" . $post['id'] . "'>其它植物</a>";
}
$snum = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '" . $user['id'] . "' AND `semen` = '0'"), 0);
if ($snum != 0 && $fuser['k_posadka'] != 0) {
    if ($fuser['k_posadka'] == 1) {
        $stime = 10 * $snum;
        $sexp = 50 * $snum;
    }
    if ($fuser['k_posadka'] == 2) {
        $stime = 5 * $snum;
        $sexp = 100 * $snum;
    }
    if ($fuser['k_posadka'] == 3) {
        $stime = 3 * $snum;
        $sexp = 150 * $snum;
    }
    echo "<div class='rowdown'>";
    if ($plcont != 0) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' />  <a href='/farm/shop_combine.php?seeder'>播种机</a> (" . $fuser['k_posadka'] . "/3 级, " . $stime . " 秒, +<img src='/farm/img/exp.png' alt='' />" . $sexp . ")<br />";
        echo "<img src='/farm/img/plant.png' alt='' class='rpg' /> <a href='/farm/combine/seeder.php?id=" . $plseed['id'] . "&start'>播种 " . $pln['name'] . "</a> <img src='/farm/img/plant.png' alt='' class='rpg' /> <a href='/farm/combine/seeder.php?select'>其他</a>";
    } else {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <a href='/farm/combine/seeder.php?select'>用播种机播种 :: 无种子</a> (" . $fuser['k_posadka'] . "/3 级)";
    }
    echo "</div>";
}

echo "<div class='rowup'>";
echo "<img src='/farm/img/shop.png' alt='' class='rpg' /> 商店 <a href='/farm/shop/'>种子</a> | <a href='/farm/shop_udobr.php'>肥料</a> | <a href='/farm/shop_combine.php'>技术人员</a><br />";
echo "<img src='/farm/img/serdechko.png' alt='' class='rpg' /> <a href='/farm/dining'>食堂</a><br />";
echo "<img src='/farm/img/warehouse.png' alt='' class='rpg' /> <a href='/farm/ambar'>谷仓</a> | <a href='/farm/sklad'>仓库</a><br/>";
echo "<img src='/farm/img/pet.gif' alt='' class='rpg' /> <a href='/farm/dog.php'>我的狗</a><br/>";
echo "<img src='/farm/img/exp.png' alt='' class='rpg' /> <a href='/farm/abilities/'>技能和筹码</a><br/>";
echo "<img src='/farm/img/village.png' alt='' class='rpg' /> <a href='/farm/stat.php'>我的统计</a><br/>";
echo "<img src='/farm/img/harvest.png' alt='' class='rpg' /> <a href='/farm/fermers/'>全体农民</a><br/>";

if (!isset($_GET['add'])) {
    echo "<img src='/img/add.png' class='rpg' /> <a href='/farm/garden/?add'>购买土地</a> <a href='/farm/help.php'>[?]</a><br/>";
}

echo "<img src='/farm/img/garden.png' alt='返回' class='rpg' /> <a href='/farm/garden/'>返回</a><br/>";
echo "<img src='/img/back.png' alt='返回' class='rpg' /> <a href='/farm/'>农场首页</a>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
