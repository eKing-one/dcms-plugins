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
$set['title'] = '开心农场 :: 技术商店';
include_once '../sys/inc/thead.php';
title();
aut();

$fuser = dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '" . $user['id'] . "' LIMIT 1"));

if (isset($_GET['seeder_up'])) {
    if ($fuser['k_posadka'] == 0 && $fuser['gems'] >= 100) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'100' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_posadka` = '1' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你已经成功购买了1级[b]播种机[/b]。花了 100 颗钻石');
    }
    if ($fuser['k_posadka'] == 0 && $fuser['gems'] < 100) {
        $cntt = 100 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 钻石购买[b]播种机[/b]1级');
    }

    if ($fuser['k_posadka'] == 1 && $fuser['gems'] >= 30) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_posadka` = '2' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你成功地将 [B] 播种机 [/B] 升级到二级。花了 30 颗钻石');
    }
    if ($fuser['k_posadka'] == 1 && $fuser['gems'] < 30) {
        $cntt = 30 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 将[B] 播种机 [/B]  升级为2级');
    }

    if ($fuser['k_posadka'] == 2 && $fuser['gems'] >= 50) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'50' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_posadka` = '3' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你成功地将 [B] 播种机 [/B] 升级到了3级。花了 50 颗钻石');
    }
    if ($fuser['k_posadka'] == 2 && $fuser['gems'] < 50) {
        $cntt = 50 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 将[B] 播种机 [/B]  升级到3级的钻石');
    }
    header("Location: /farm/shop_combine.php?seeder");
    exit();
}


if (isset($_GET['irrigation_up'])) {
    if ($fuser['k_poliv'] == 0 && $fuser['gems'] >= 100) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'100' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_poliv` = '1' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你已经成功购买了1级[b]喷洒器[/b]。花了 100 颗钻石');
    }
    if ($fuser['k_poliv'] == 0 && $fuser['gems'] < 100) {
        $cntt = 100 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 用于购买第一级喷洒器的钻石');
    }

    if ($fuser['k_poliv'] == 1 && $fuser['gems'] >= 30) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'30' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_poliv` = '2' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你已经成功地把[b]喷洒器[/b]升级到2级了。花了 30 颗钻石');
    }
    if ($fuser['k_poliv'] == 1 && $fuser['gems'] < 30) {
        $cntt = 30 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 将[b]喷洒器[/b] 升级为2级');
    }

    if ($fuser['k_poliv'] == 2 && $fuser['gems'] >= 50) {
        dbquery("UPDATE `farm_user` SET `gems` = `gems`-'50' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `k_poliv` = '3' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('你已经成功地将[b]喷洒器[/b]升级到了3级。花了 50 颗钻石');
    }
    if ($fuser['k_poliv'] == 2 && $fuser['gems'] < 50) {
        $cntt = 50 - $fuser['gems'];
        add_farm_event('你不够。' . $cntt . ' 将喷洒器 [/B] 升级到3级');
    }
    header("Location: /farm/shop_combine.php?irrigation");
    exit();
}



include_once H . '/farm/inc/str.php';
farm_event();


if (isset($_GET['seeder'])) {
    echo "<div class='rowup'>";
    echo "<table class='post'><tr><td><img src='/farm/img/seeder_big.png' alt='' /></td><td>";
    echo "用套式方法自动播种的机器。减轻农民的劳动——一键播种所有苗土地</td></tr></table>";
    echo "仅用于在所有苗土地上播种一种种子。<br />";

    if ($fuser['k_posadka'] == 1) {
        echo "在这个等级上，它一次在所有地块上播种，每土地花费 10 秒钟，并产生额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />50K 使用经验<br />";
    }
    if ($fuser['k_posadka'] == 2) {
        echo "在这个等级上，它一次在所有地块上播种，每土地花费 5 秒钟，并产生额外的<img src='/farm/img/exp.png' alt='' class='rpg' />在其上使用时有 100K 的经验<br />";
    }
    if ($fuser['k_posadka'] == 3) {
        echo "在这个等级上，它一次在所有地块上播种，每土地花费 3 秒，并产生额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />150 在其上使用时的经验<br />";
    }


    if ($fuser['k_posadka'] == 0) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>买1级播种机换<img src='/farm/img/gems.png' alt='' class='rpg' />100</a></span><br />";
        echo "将一次在所有苗土地上播种，在每个地块上花费 10 秒钟，并给出<img src='/farm/img/exp.png' alt='' class='rpg' />50 经验";
    }

    if ($fuser['k_posadka'] == 1) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>把播种机提高到2级<img src='/farm/img/gems.png' alt='' class='rpg' />30</a></span><br />";
        echo "将种子一次播种在所有的菜田上，花5秒在每片土地上，给予 <img src='/farm/img/exp.png' alt='' class='rpg' />100 经验";
    }

    if ($fuser['k_posadka'] == 2) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?seeder_up'>把播种机提高到3级 <img src='/farm/img/gems.png' alt='' class='rpg' />50</a></span><br />";
        echo "将种子一次播种在所有的菜田上，花3秒在每片土地上，给予 <img src='/farm/img/exp.png' alt='' class='rpg' />150 经验";
    }

    if ($fuser['k_posadka'] == 3) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'>三级播种机</span> (最高等级)<br />";
        echo "在这个等级上，种子一次播种在所有的土地，花3秒每一块，给予 <img src='/farm/img/exp.png' alt='' class='rpg' />150 经验";
    }
    echo '</div>';
}

if (isset($_GET['irrigation'])) {

    echo "<div class='rowup'>";
    echo "<table class='post'><tr><td><img src='/farm/img/irrigation_big.png' alt='' /></td><td>";
    echo "用于菜园植物灌溉机械化的灌溉系统。减轻了农民的劳动——一键浇灌所有的苗土地</td></tr></table>";
    echo "每次浇水前激活<br />";

    if ($fuser['k_poliv'] == 1) {
        echo "在这个等级上，它一次浇灌所有地块，每土地花费 10 秒，并额外提供 <img src='/farm/img/exp.png' alt='' class='rpg' />50K 使用经验<br />";
    }
    if ($fuser['k_poliv'] == 2) {
        echo "在这个等级上，它一次浇灌所有地块，每土地用 5 秒钟，并给出额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />在其上使用时有 100K 的经验<br />";
    }
    if ($fuser['k_poliv'] == 3) {
        echo "在这个等级上，它一次浇灌所有地块，每块土地花费 3 秒，并提供额外的 <img src='/farm/img/exp.png' alt='' class='rpg' />150 在其上使用时的经验<br />";
    }


    if ($fuser['k_poliv'] == 0) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>购买1级喷洒器 <img src='/farm/img/gems.png' alt='' class='rpg' />100</a></span><br />";
        echo "每次浇水，每块地用 10 秒钟，给 <img src='/farm/img/exp.png' alt='' class='rpg' />50 经验";
    }

    if ($fuser['k_poliv'] == 1) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>将喷洒器升至2级 <img src='/farm/img/gems.png' alt='' class='rpg' />30</a></span><br />";
        echo "每次浇水，每块地用 5 秒钟，给 <img src='/farm/img/exp.png' alt='' class='rpg' />100 经验";
    }

    if ($fuser['k_poliv'] == 2) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'><a href='?irrigation_up'>将喷洒器升至3级 <img src='/farm/img/gems.png' alt='' class='rpg' />50</a></span><br />";
        echo "每次浇水，每块地用 3 秒钟，给出 <img src='/farm/img/exp.png' alt='' class='rpg' />150 经验";
    }

    if ($fuser['k_poliv'] == 3) {
        echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <span class='underline'>三级喷洒器</span>（最高等级）<br />";
        echo "在这个等级上，它一次浇灌所有的土地，每块用 3 秒，给出 <img src='/farm/img/exp.png' alt='' class='rpg' />150 经验";
    }

    echo "</div>";
}

if (!isset($_GET['irrigation'])) {
    echo "<div class='rowdown'><img src='/farm/img/irrigation_small.png' alt='' class='rpg' /> <a href='?irrigation'>喷洒器</a>";
    if ($fuser['k_poliv'] != 0) {
        echo " (" . $fuser['k_poliv'] . ")";
    }
    echo "</div>";
}
if (!isset($_GET['seeder'])) {
    echo "<div class='rowdown'>";
    echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> <a href='?seeder'>播种机</a>";
    if ($fuser['k_posadka'] != 0) {
        echo " (" . $fuser['k_posadka'] . ")";
    }
    echo "</div>";
}
echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的土地</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
