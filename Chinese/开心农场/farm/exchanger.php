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
$set['title'] = '开心农场 :: 交易所';
include_once '../sys/inc/thead.php';
title();
aut();

if (isset($_GET['gems_exchange']) && isset($_POST['gems']) && is_numeric($_POST['gems']) && $_POST['gems'] > 0) {
    $gemsp = intval($_POST['gems']);
    $needb = $gemsp * 1000;
    if ($user['balls'] < $needb) {
        add_farm_event('你没有足够的分数兑换钻石。');
    } else {
        dbquery("UPDATE `user` SET `balls` = `balls`-'$needb' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `gems` = `gems`+'$gemsp' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('您已成功获得' . $gemsp . ' 钻石，花费' . $needb . ' 网站积分');
    }
}

if (isset($_GET['gold_exchange']) && isset($_POST['glob']) && is_numeric($_POST['glob']) && $_POST['glob'] > 0) {
    $glob = intval($_POST['glob']);
    $gemsp = $glob * 10;
    if ($user['balls'] < $balls) {
        add_farm_event('你没有足够的分数兑换金币。');
    } else {
        dbquery("UPDATE `user` SET `balls` = `balls`-'$gemsp' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
        dbquery("UPDATE `farm_user` SET `gold` = `gold`+'$glob' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
        add_farm_event('您已成功获得 ' . $glob . ' 金币，花费 ' . $gemsp . ' 网站积分');
    }
}

include_once 'inc/str.php';
farm_event();

if (isset($_GET['gems'])) {
    echo "<div class='rowup'>";
    echo "&raquo; 农场帐户: <img src='/farm/img/gems.png' alt='' class='rpg' />" . sklon_after_number("$fuser[gems]", "钻石", "钻石", "钻石", 1) . "<br />";
    echo "&raquo; 网站帐户: <img src='/farm/img/money.png' alt='' class='rpg' />" . sklon_after_number("$user[balls]", "积分", "积分", "积分", 1) . "点<br />";
    echo "&raquo; 1 颗钻石价值 1000点 网站积分<br />";
    echo "<form action='?gems_exchange' method='post'>";
    echo "&raquo; 输入要兑换的钻石数量:<br />";
    echo "<input type='text' name='gems' value='10' /><br /><input type='submit' value='交易' /></form>";
    echo "</div>";
}
if (isset($_GET['gold'])) {
    echo "<div class='rowup'>";
    echo "&raquo; 农场帐户: <img src='/farm/img/money.png' alt='' class='rpg' />" . sklon_after_number("$fuser[gold]", "金币", "金币", "金币", 1) . "<br />";
    echo "&raquo; 网站帐户: <img src='/farm/img/money.png' alt='' class='rpg' />" . sklon_after_number("$user[balls]", "积分", "积分", "积分", 1) . "点<br />";
    echo "&raquo; 1 枚金币将价值 10点 网站积分<br />";
    echo "<form action='?gold_exchange' method='post'>";
    echo "&raquo; 输入要兑换的金币点数:<br />";
    echo "<input type='text' name='gold' value='10' /><br /><input type='submit' value='交易' /></form>";
    echo "</div>";
}

echo "<div class='rowdown'>";
echo "<img src='/farm/img/gems.png' alt='' class='rpg' /> <a href='?gems'>网站积分兑换钻石</a> (1000:1)";
echo "</div>";
echo "<div class='rowup'>";
echo "<img src='/farm/img/money.png' alt='' class='rpg' /> <a href='?gold'>网站积分兑换成硬币</a> (10:1)";
echo "</div>";
include_once '../sys/inc/tfoot.php';
