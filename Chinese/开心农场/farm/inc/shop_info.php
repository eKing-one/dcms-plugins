<?php
echo "<div class='rowup'><img src='/farm/img/garden.png' alt='' /> <a href='/farm/garden/'>你的菜园</a> / <b>植物信息</b></div>";
$int = intval($_GET['id']);
$notis = dbresult(dbquery("SELECT COUNT(*) FROM `farm_plant` WHERE  `id` = '$int'"), 0);
if ($notis == 0) {
    echo "<div class='err'>没有这样的植物</div>";
    echo "<img src='/img/back.png' alt='' /> <a href='shop.php'>返回</a>";
    include_once '../sys/inc/tfoot.php';
    exit;
}
$post = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '$int'  LIMIT 1"));

if (isset($_POST['opis']) && $_POST['opis'] != NULL) {
    $opis = $_POST['opis']; //过滤代码
    dbquery("UPDATE `farm_plant` SET `opis` = '$opis' WHERE `id` = '$post[id]' LIMIT 1");
}


$timediff = $post['time'];
$oneMinute = 60;
$oneHour = 60 * 60;
$oneDay = 60 * 60 * 24;
$dayfield = floor($timediff / $oneDay);
$hourfield = floor(($timediff - $dayfield * $oneDay) / $oneHour);
$minutefield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour) / $oneMinute);
$secondfield = floor(($timediff - $dayfield * $oneDay - $hourfield * $oneHour - $minutefield * $oneMinute));
if ($dayfield > 0) $day = $dayfield . '天';
else $day = '';
if ($minutefield > 0) $minutefield = $minutefield . "分";
else
    $minutefield = '';
$time_1 = $day . $hourfield . "时" . $minutefield;

echo "<div class='rowdown'>";
echo "<center><img src='/farm/shopimg/$post[id].jpeg' alt='$post[name]' /></center><br />";

echo "&raquo; <b>$post[name]</b><br />";
if ($post['opis'] != NULL) {
    echo "&raquo; <b>" . esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['opis'])))))))) . "</b><br />";
}
echo "&raquo; 所需级别: <b>$post[level]</b><br />";
echo "&raquo; 费用: <b>" . $post['cena'] . "</b> 黄金<br/>";
echo "&raquo; 成熟: <b>" . $time_1 . "</b> <br/>";
echo "&raquo; 收获: от <b>" . $post['rand1'] . "</b> шт. до <b>" . $post['rand2'] . "</b> шт.<br/>";
$allxp = $post['xp'] * $post['rand1'];
echo "&raquo; 增进你的健康: <b>" . $post['xp'] . "</b> за 1 шт.(<b>$allxp</b>)<br/>";
$costall = $post['dohod'] * $post['rand1'];
echo "&raquo; 每单位价格: <b>$post[dohod]</b> за 1 шт.(<b>" . $costall . "</b>)<br/>";
$allopyt = $post['oput'] * $post['rand1'];
echo "&raquo; 每个单位的经验: <b> " . $post['oput'] . "</b> за 1 шт.(<b>$allopyt</b>)<br/>";
if ($post['let'] == 1) {
    echo "&raquo; 一年生植物，<b>只结一次</b>果实";
}

if ($post['let'] == 2) {
    echo "&raquo; 两年生植物，结<b>两次</b>果实";
}

if ($post['let'] > 2) {
    echo "&raquo; 多年生植物，会结出果实 <b>$post[let]</b> раз";
}


if ($post['opis'] == NULL && $user['level'] == 4) {
    echo "<form action='/farm/shop.php?id=" . $int . "&amp;$passgen' method='post'>";
    echo "<input type='text' maxlenght='1024' size='20' name='opis' /><br />";
    echo "<input type='submit' value='OK' />";
    echo "</form>";
}

echo "</div>";
$iplus = $post['id'] + 1;
$iminus = $post['id'] - 1;
echo "<div class='rowup'>";
echo "<a href='/farm/shop/plant/$iminus'>&laquo; 上一个</a> | <a href='/farm/shop/plant/$iplus'>下一个&raquo;</a>";
echo "</div>";

if ($level >= $post['level']) {
    echo "<div class='rowdown'><form method='post' action='/farm/shop.php?id=" . $int . "&amp;$passgen'>\n";
    echo "购买（数量）:<br />\n";

    echo "<input type='text' name='kupit' size='4'/> <input type='submit' name='save' value='购买' />";
    echo "</form></div>\n";
} else {
    echo '<div class="err">这种植物可以从 ' . $post['level'] . ' 等级.</div>';
}
@$kupit = intval($_POST['kupit']);
$kup = $post['cena'] * $kupit;
if (isset($kupit) && $fuser['gold'] >= $kup && $kupit > 0) {
    dbquery("INSERT INTO `farm_semen` (`kol` , `semen`, `id_user`) VALUES  ('" . $kupit . "', '" . $int . "', '" . $user['id'] . "') ");
    dbquery("UPDATE `farm_user` SET `gold` = `gold`- $kup WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
    $_SESSION['plidb'] = $post['id'];
    header('Location: /farm/shop/?buy_ok');
}else if (isset($kupit) && strlen2($kupit) == 0 || isset($kupit) && $kupit < 1) echo "<div class='err'>未填写的字段</div>";

else if (isset($kupit) && $fuser['gold'] < $kup) {
    $_SESSION['plidb'] = $post['id'];
    header('Location: /farm/shop/?buy_no');
}
