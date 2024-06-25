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
$set['title'] = '开心农场 :: 食堂';
include_once '../sys/inc/thead.php';
title();
aut();

@$int = intval($_GET['id']);

$fuser = dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '" . $user['id'] . "' LIMIT 1"));

if (isset($_GET['eat_all'])) {
    dbquery("DELETE FROM `farm_ambar` WHERE `id_user`= '" . $user['id'] . "'");
    $sumd = intval($_SESSION['sum']);
    dbquery("UPDATE `farm_user` SET `xp` = '" . ($fuser['xp'] + $sumd) . "' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
    add_farm_event('你都吃过了。添加 ' . intval($_SESSION['sum']) . ' 健康');
    unset($_SESSION['sum']);

    header("Location: /farm/dining");
    exit;
}

if (isset($_GET['eat_ok'])) {
    $semen = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '" . intval($_SESSION['plid']) . "'  LIMIT 1"));

    add_farm_event('你已经成功地吃了 ' . $semen['name'] . '. 取自 ' . intval($_SESSION['xp']) . ' 健康');
}


include 'inc/str.php';

if (isset($_GET['id'])) {
    $check = dbresult(dbquery("SELECT COUNT(*) FROM `farm_ambar` WHERE `id` = '" . $int . "'"), 0);
    if ($check == 0) {
        header("Location: /farm/dining");
        exit();
    }
    $post = dbarray(dbquery("SELECT * FROM `farm_ambar` WHERE `id`= '" . intval($_GET['id']) . "' LIMIT 1"));
    $semen = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));

    unset($_SESSION['plid']);

    $_SESSION['plid'] = $post['semen'];

    $xp = $post['kol'] * $semen['xp'];


    unset($_SESSION['xp']);
    $_SESSION['xp'] = $xp;
    if (isset($_GET['eat'])) {
        $xp = intval($_SESSION['xp']);
        dbquery("UPDATE `farm_user` SET `xp` = '" . ($fuser['xp'] + $xp) . "' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        dbquery("DELETE FROM `farm_ambar` WHERE `id` = " . intval($_GET['id']) . " ");
        header('Location: /farm/dining?eat_ok');
    }

    farm_event();

    echo "<div class='rowdown'><center><img src='/farm/bush/" . $post['semen'] . ".png' alt=''></center><br/> <b>" . $semen['name'] . "</b><br/>";
    echo "&raquo; 收获的数量: <b>" . $post['kol'] . "</b> <br/>";
    echo "&raquo; 每个单位的健康状况: <b>" . $semen['xp'] . "</b> <br/>";

    echo "&raquo; 健康总量: <b>" . $xp . "</b></div>";

    echo "<form method='post' action='?id=" . $int . "&amp;eat'>\n";
    echo "<input type='submit' name='save' value='查阅' />\n";
    echo "</form>\n";

    echo "<div class='rowup'>";
    echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/dining'>饭厅</a>";
    echo "</div>";
} else {

    farm_event();

    $k_post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_ambar` WHERE `id_user` = '" . $user['id'] . "'"), 0);
    $k_page = k_page($k_post, $set['p_str']);
    $page = page($k_page);
    $start = $set['p_str'] * $page - $set['p_str'];


    if ($k_post == 0) {
        echo "<div class='err'>谷仓里没有货物</div>";
    }

    if ($k_post != 0) {
        $rssum = dbquery("SELECT * FROM `farm_ambar` WHERE `id_user` = '" . $user['id'] . "'");
        $_SESSION['sum'] = 0;

        while ($item = dbarray($rssum)) {
            $plsum = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '" . $item['semen'] . "' LIMIT 1"));
            $plussum = $plsum['xp'] * $item['kol'];
            $_SESSION['sum'] = $plussum + $_SESSION['sum'];
        }

        echo "<div class='rowdown'>";
        echo "<img src='/img/add.png' alt='' class='rpg' /> <a href='/farm/dining?eat_all'>把它全部吃完。添加 " . intval($_SESSION['sum']) . " 健康</a></div>";
    }


    $res = dbquery("SELECT * FROM `farm_ambar` WHERE `id_user` = '" . $user['id'] . "' LIMIT $start, $set[p_str];");

    while ($post = dbarray($res)) {
        if ($num == 1) {
            echo "<div class='rowdown'>";
            $num = 0;
        } else {
            echo "<div class='rowup'>";
            $num = 1;
        }

        $semen = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '" . $post['semen'] . "'  LIMIT 1"));

        echo "<img src='/farm/bush/" . $post['semen'] . ".png' height='20' width='20'><b></b> <a href='?id=$post[id]'>" . $semen['name'] . "</a> [" . $post['kol'] . "] ";
        echo "(<a href='?id=" . $post['id'] . "&amp;eat'>查阅</a>)</div>";
    }


    if ($k_page > 1) str('?', $k_page, $page); // Вывод страниц
}
echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' /> <a href='/farm/garden/'>返回</a><br/>";
echo "</div>";

include_once '../sys/inc/tfoot.php';
