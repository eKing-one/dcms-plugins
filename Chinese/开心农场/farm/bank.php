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

$set['title'] = '开心农场 :: 银行'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
err();
aut();
include 'inc/str.php';

if (isset($user)) {
    @$action = htmlspecialchars(trim($_GET['action']));

    switch ($action) {

        default:
            echo '你好, ' . $user['nick'] . '! ';
            echo '你的账户有:<br/>';
            echo '网站积分: ' . $user['money'] . '<br/>';
            echo '游戏金币: ' . $fuser['gold'] . '<br/>';
            echo '10000 比 1 的汇率<br/>';
            echo '<form action="bank.php?action=change" method="post">';
            echo '你想换多少硬币:<br/>';
            echo '<input name="num" type="text" value=""/><br/>';
            echo '<input type="submit" value="交易"/>';
            echo '</form>';
            echo '&raquo; <a href="index.php">返回</a><br/>';
            break;

        case 'change':
            $num = intval($_POST['num']);
            if (!$num || $num < 1) {
                echo '参数不对！';
            }
            if ($fuser['gold'] < ($num * 1000)) {
                echo '你没有那么多游戏金币！ <br>&raquo;<a href="bank.php"> 回到银行</a><br/>';
            }else{
                $baks = $num * 1000;
                dbquery("UPDATE `user` SET `money` = `money`+'$num' WHERE `id` = '" . $user['id'] . "' LIMIT 1");
                dbquery("UPDATE `farm_user` SET `gold` = `gold`-'$baks' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
                add_farm_event('您已成功获得' . $num . ' 网站硬币，花费' . $baks . ' 游戏金币');
                echo "兑换成功！";
                echo '<br/>&raquo; <a href="bank.php">回到银行</a><br/>';
            }

            break;
    }
    echo "<div class='foot'>";
    echo "&raquo; <a href='my.php'>我的农场</a><br/>";
    echo "&laquo; <a href='index.php'>返回</a><br/>";
    echo "</div>";
} else {
    echo '<div class="msg">农场仅供授权用户使用！</div>';
}
include_once '../sys/inc/tfoot.php';
