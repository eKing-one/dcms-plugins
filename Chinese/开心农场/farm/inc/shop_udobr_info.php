<?php

$int = intval(htmlspecialchars($_GET['id']));
$query = dbquery("select * from `farm_udobr_name` WHERE  `id` = '$int'  LIMIT 1");
$chk = dbrows($query);
if ($chk == 0) {
    header("Location: /farm/shop_udobr.php");
    exit();
}
$post = dbarray($query);

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
else $minutefield = '';
$time_1 = $day . $hourfield . "时" . $minutefield;

echo "<div class='rowdown'><img src='/farm/udobr/" . $post['id'] . ".png' alt=''><br />&raquo; <b>" . $post['name'] . "</b>";
echo "<br />&raquo; 价格: <b> " . $post['cena'] . "</b>";
echo "<br />&raquo; 减少<b> " . $time_1 . "</b> 植物生长时间";

echo "</div><form method='post' action='?id=" . $int . "&amp;$passgen'>\n";
echo "&raquo; 数量:<br />\n";

echo "<input type='text' name='kupit' size='4'/><input type='submit' name='save' value='购买' />";
echo "</form>\n";
@$kupit = intval($_POST['kupit']);
$kup = $post['cena'] * $kupit;
if (isset($kupit) && $fuser['gold'] >= $kup && $kupit > 0) {
    dbquery("INSERT INTO `farm_udobr` (`kol` , `udobr`, `id_user`) VALUES  ('" . $kupit . "', '" . $int . "', '" . $user['id'] . "') ");
    dbquery("UPDATE `farm_user` SET `gold` = `gold`- $kup WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
    $_SESSION['udid'] = $post['id'];
    header('Location: shop_udobr.php?buy_ok');
}
if (isset($kupit) && strlen2($kupit) == 0 || isset($kupit) && $kupit < 1) echo "<div class='err'>空格还没满！</div>";

if (isset($kupit) && $fuser['gold'] < $kup) {
    $_SESSION['udid'] = $post['id'];
    header('Location: shop.php?buy_no');
}
