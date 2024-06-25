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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /farm/garden/");
    exit;
}

$int = intval($_GET['id']);
$set['title'] = '开心农场 :: 土地';
include_once '../sys/inc/thead.php';
title();
err();
aut();

$ank = dbarray(dbquery("SELECT * FROM `user` WHERE `id`= '" . $int . "' LIMIT 1"));
$afuser = dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '" . $ank['id'] . "' LIMIT 1"));

if ($_GET['id'] == $user['id']) {
    header('Location: /farm/garden/');
}

include 'inc/str.php';

if (isset($_GET['vor'])) {

    $gr = dbarray(dbquery("SELECT * FROM `farm_gr` WHERE `id` = '" . intval($_GET['vor']) . "' "));

    $semen = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $gr['semen'] . "' "));

    $qi = dbassoc(dbquery("SELECT * FROM `farm_vor` WHERE `id_user` = '" . $user['id'] . "'  AND `gr` = '" . intval($_GET['vor']) . "' LIMIT 1"));

    if ($qi['time'] < time()) {

        $rand1 = floor($semen['rand1'] / 100);
        $rand = $rand1 * 5;
        if ($rand < 1) {
            $rand = 1;
        }

        $dog = dbarray(dbquery("SELECT * FROM `farm_dog` WHERE `id_user` = '" . $ank['id'] . "' "));
        if ($dog['time'] < time()) {
            $t = time() + 86400;

            $middle = floor($semen['rand1'] / 2);
            if ($gr['kol'] > $middle) {
                dbquery("UPDATE `farm_gr` SET `kol` = `kol`-'$rand' WHERE `id` = '" . intval($_GET['vor']) . "' LIMIT 1");
                dbquery("INSERT INTO `farm_ambar` (`kol` , `semen`, `id_user`) VALUES  ('" . $rand . "', '" . $gr['semen'] . "', '" . $user['id'] . "') ");
                dbquery("INSERT INTO `farm_vor` (`id_user` , `gr`, `time`) VALUES  ('" . $user['id'] . "', '" . $_GET['vor'] . "', '" . $t . "') ");
                dbquery("UPDATE `farm_user` SET `exp` = `exp`- '10' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");

                add_farm_event('成功地偷了 ' . sklon_after_number("$rand", "果实", "果实", "果实", 1) . ' 植物 ' . $semen['name'] . '');
            } else {
                add_farm_event('你不能偷任何东西，因为它有一半的作物。');
            }
        } else {

            $randp = $fuser['gold'] / 100;
            $rand = $rand * 3;

            dbquery("UPDATE `farm_user` SET `gold` = `gold`-'$rand' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
            dbquery("UPDATE `farm_user` SET `xp` = `xp`-'3' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
            dbquery("UPDATE `farm_user` SET `gold` = `gold`+'$rand' WHERE `uid` = '" . $ank['id'] . "' LIMIT 1");

            add_farm_event('当他们试图偷窃时，一只农民的狗袭击了他们。你在逃跑时失去了 ' . sklon_after_number("$rand", "硬币", "硬币", "硬币", 1) . ' (3%) 3 健康单位');
        }
    } else {
        add_farm_event('你已经偷了这个地方。不能再从这片土地上偷东西了。');
    }
}

farm_event();

if ($afuser['zabor_time'] > time()) {
    $prep = $fuser['xp'] / 100;
    $health = floor($prep * 3);
    dbquery("UPDATE `farm_user` SET `xp` = `xp`-'$health' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
    add_farm_event('你受到了严重的电击。减少 -' . $health . ' 健康 (3%)');
    header("Location: /farm/fermers/");
    exit();
}

$k_post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '" . $int . "'"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];

if ($fuser['razvedka'] == 1) {
    $dog = dbarray(dbquery("SELECT * FROM `farm_dog` WHERE `id_user` = '" . $ank['id'] . "' "));

    if ($dog['time'] > time()) {
        echo "<div class='mdlc'><span>开发商</span><br /></div><div class='menu'>";
        echo "<center><img src='/farm/img/dog.png' alt='' /></center><br />";
        echo "<img src='/farm/img/pet.png' alt='' class='rpg' /> 农场 " . $ank['nick'] . " 一只邪恶的狗看守着。小心！";
        echo "</div>";
    } else {
        echo "<div class='mdlc'><span>开发商</span><br /></div><div class='menu'>";
        echo "<img src='/farm/img/pet_hide.png' alt='' class='rpg' /> 情报显示花园里没有狗。";
        echo "</div>";
    }
}

echo "<div class='mdlc'><span>土地 [" . $k_post . "]</span><br /></div><div class='menu'>";

if ($k_post == 0) {
    echo "<div class='err'>没有土地</div>";
}

$res2 = dbquery("SELECT * FROM `farm_gr` WHERE `id_user` =  '" . $int . "' LIMIT $start, $set[p_str];");
while ($post2 = dbarray($res2)) {
    $semen2 = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $post2['semen'] . "' "));
    if ($post2['kol'] < 1) {
        $rndp = rand($semen2['rand1'], $semen2['rand2']);
        dbquery("UPDATE `farm_gr` SET `kol` = '" . $rndp . "' WHERE `id` = '" . $post2['id'] . "' LIMIT 1");
    }
}

$res = dbquery("SELECT * FROM `farm_gr` WHERE `id_user` =  '" . $int . "' LIMIT $start, $set[p_str];");
while ($post = dbarray($res)) {
    $semen = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));
    if ($num == 1) {
        echo "<div class='rowdown'>";
        $num = 0;
    } else {
        echo "<div class='rowup'>";
        $num = 1;
    }

    echo "<table class='post'><tr>";

    if ($post['semen'] == 0) {
        $name_gr = '未播种';
    } else {
        $name_gr = $semen['name'];
    }

    if ($post['time'] > time()) {
        echo "<td style='width:30px' rowspan='2'>";
    } else {
        echo "<td style='width:30px'>";
    }

    echo "<img src='/farm/plants/" . $post['semen'] . ".png' height='30' width='30' alt='' /></td><td>" . $name_gr . "";

    if ($post['semen'] != 0 && $post['time'] < time()) {
        echo " [" . $post['kol'] . "] ";

        $qi = dbarray(dbquery("SELECT * FROM `farm_vor` WHERE `id_user` = '" . $user['id'] . "'  AND `gr` = '" . $post['id'] . "' LIMIT 1"));

        if ($qi['time'] < time()) {
            echo "<a href='?id=" . $int . "&amp;vor=" . $post['id'] . "'>[偷菜]</a>";
        }
    } else {

        if ($post['time'] > time()) {

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
            if ($post['semen'] != 0) {
                if (time() < $post['time']) $time_1 = $day . $hourfield . "时" . $minutefield . "分";
                else $time_1 = 0;
            }
        }

        if ($post['semen'] != 0) echo " [生长]";

        if ($post['time'] > time()) {
            echo "</td></tr><tr><td><img src='/farm/img/time.png' alt='' class='rpg' /> 未成熟 " . $time_1 . "</td></tr>";
        } else {
            echo "</td></tr>";
        }
    }

    echo "</table></div>";
}

echo "</div>";

if ($k_page > 1) str('?', $k_page, $page);

echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的农场</a><br/>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/fermers/'>返回</a><br/>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
