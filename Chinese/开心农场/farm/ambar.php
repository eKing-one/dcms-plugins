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
$set['title'] = '开心农场 :: 仓库';
include_once '../sys/inc/thead.php';
title();
err();
@$int = intval($_GET['id']);
$fuser = dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '" . $user['id'] . "' LIMIT 1"));

if (isset($_GET['sell_all'])) {
    dbquery("DELETE FROM `farm_ambar` WHERE `id_user`= '" . $user['id'] . "'");
    $sumd = intval($_SESSION['sum']);
    dbquery("UPDATE `farm_user` SET `gold` = '" . ($fuser['gold'] + $sumd) . "' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
    add_farm_event('所有的庄稼都卖了 ' . intval($_SESSION['sum']) . ' 黄金');
    unset($_SESSION['sum']);

    header("Location: /farm/ambar");
    exit;
}

if (isset($_GET['sell_ok'])) {
    $semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '" . intval($_SESSION['plid']) . "'  LIMIT 1"));

    add_farm_event('植物产量 ' . $semen['name'] . ' 卖了。收到 ' . intval($_SESSION['dohod']) . ' 黄金.');
}

aut();

include 'inc/str.php';

if (isset($_GET['id'])) {
    $post = dbarray(dbquery("select * from `farm_ambar` WHERE `id`= '" . intval($_GET['id']) . "' LIMIT 1"));
    $semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));

    unset($_SESSION['plid']);

    $_SESSION['plid'] = $post['semen'];

    $dohod = $post['kol'] * $semen['dohod'];


    unset($_SESSION['dohod']);
    $_SESSION['dohod'] = $dohod;
    if (isset($_GET['sell'])) {
        $dohod = intval($_SESSION['dohod']);
        dbquery("UPDATE `farm_user` SET `gold` = '" . ($fuser['gold'] + $dohod) . "' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("DELETE FROM `farm_ambar` WHERE `id` = " . intval($_GET['id']) . " ");
        header('Location: /farm/ambar?sell_ok');
    }

    farm_event();

    echo "<img src='/farm/bush/" . $post['semen'] . ".png' alt=''><br/> <b>" . $semen['name'] . "</b><br/>";
    echo "&raquo; 收获量: <b>" . $post['kol'] . "</b> <br/>";
    echo "&raquo; 单价: <b>" . $semen['dohod'] . "</b> <br/>";

    echo "&raquo; 总收入: <b>" . $dohod . "</b> <br/>";

    echo "<form method='post' action='?id=" . $int . "&amp;sell'>\n";
    echo "<input type='submit' name='save' value='出售' />\n";
    echo "</form>\n";
    echo "<div class='rowup'>";
    echo "&laquo; <a href='/farm/ambar'>仓库</a><br/>";
    echo "</div>";
} else {

    farm_event();

    $k_post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_ambar` WHERE `id_user` = '" . $user['id'] . "'"), 0);
    $k_page = k_page($k_post, $set['p_str']);
    $page = page($k_page);
    $start = $set['p_str'] * $page - $set['p_str'];


    if ($k_post == 0) {
        echo "<div class='err'>仓库里没有货物</div>";
    }

    if ($k_post != 0) {
        $rssum = dbquery("SELECT * FROM `farm_ambar` WHERE `id_user` = '" . $user['id'] . "'");
        $_SESSION['sum'] = 0;

        while ($item = dbarray($rssum)) {
            $plsum = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $item['semen'] . "' LIMIT 1"));
            $plussum = $plsum['dohod'] * $item['kol'];
            $_SESSION['sum'] = $plussum + $_SESSION['sum'];
        }

        echo "<div class='rowdown'>";
        echo "<img src='/img/add.png' alt='' class='rpg' /> <a href='/farm/ambar?sell_all'>把一切都卖了 " . intval($_SESSION['sum']) . " 黄金</a></div>";
    }


    $res = dbquery("select * from `farm_ambar` WHERE `id_user` = '" . $user['id'] . "' LIMIT $start, $set[p_str];");

    while ($post = dbarray($res)) {
        if ($num == 1) {
            echo "<div class='rowdown'>";
            $num = 0;
        } else {
            echo "<div class='rowup'>";
            $num = 1;
        }

        $semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));

        echo "<img src='/farm/bush/" . $post['semen'] . ".png' height='20' width='20'><b></b> <a href='?id=$post[id]'>" . $semen['name'] . "</a> [" . $post['kol'] . "] ";
        echo "(<a href='?id=" . $post['id'] . "&amp;sell'><span class='off'>出售</span></a>)</div>";
    }


    if ($k_page > 1) str('?', $k_page, $page); // Вывод страниц
}
echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' /> <a href='/farm/garden/'>返回</a><br/>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
