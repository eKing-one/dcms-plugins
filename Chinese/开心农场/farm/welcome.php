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
$set['title'] = '开心农场 :: 欢迎光临';
include_once '../sys/inc/thead.php';
title();
aut();

$dog = dbresult(dbquery("SELECT COUNT(*) FROM `farm_dog` WHERE  `id_user` = '" . $user['id'] . "'  LIMIT 1"), 0);
if ($dog == 0) {
    dbquery("INSERT INTO `farm_dog` (`id_user`,`time`) VALUES  ('" . $user['id'] . "','0') ");
}

$post = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE  `id_user` = '" . $user['id'] . "'  LIMIT 1"), 0);
if ($post < 5) {
    dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '" . $user['id'] . "') ");
    dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '" . $user['id'] . "') ");
    dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '" . $user['id'] . "') ");
    dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '" . $user['id'] . "') ");
    dbquery("INSERT INTO `farm_gr` (`kol`,`semen`, `id_user`) VALUES  ( NULL, '0', '" . $user['id'] . "') ");
}

$chk = dbresult(dbquery("SELECT COUNT(*) FROM `farm_user` WHERE `uid` = '" . $user['id'] . "'"), 0);

if ($chk != 0) {
    header("Location: /farm/garden/");
    exit();
}

dbquery("INSERT INTO `farm_user` SET `uid` = '" . $user['id'] . "'");

include_once 'inc/str.php';
farm_event();

echo "<div class='rowup'>";
echo "<center><img src='/farm/img/logo.png' alt='Ферма' /></center><br />&raquo; 移动农场游戏欢迎你！<BR/>》要开始玩，去种子店，买五颗豌豆种子，去你的土地，把所有的种子都种在土地上。然后给苗土地浇水，等待庄稼成熟。你可以在半小时内给苗土地浇水一次，浇水会使植物提前半小时成熟。记住！你土地上的每一个动作都会带走几个健康单位。你现在有一百个。健康和其他信息，以及天气，位于每个页面的顶部单元。成为一个更富裕、更有经验的农民，你可以买新土地、买收割机、学技能等等。祝比赛好运！<br />恭敬地，网站开发人员</div>";
echo "<div class='rowdown'>";
echo "<img src='img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>我的土地</a></div>";

include_once '../sys/inc/tfoot.php';
