<?php

$post = dbarray(dbquery("SELECT * FROM `farm_gr` WHERE `id` = '" . intval($_GET['id']) . "'"));

$semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '$post[semen]'  LIMIT 1"));
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
if ($post['semen'] != 0) {
    if (time() < $post['time']) $time_1 = $day . $hourfield . "时" . $minutefield . "分";
    else $time_1 = 0;
}

if ($post['time'] != NULL) {
    $perc = $semen['time'] / 100;
    $min = $post['time'] - time();
    $otn = $semen['time'] - $min;
    $percents = $otn / $perc;
    $peround = abs(round($percents));

    if ($peround < 25) {
        $stadija = 0;
        $stname = "发芽";
    } elseif (($peround >= 25) and ($peround < 50)) {
        $stadija = 1;
        $stname = "成长";
    } elseif (($poround >= 50) and ($peround < 75)) {
        $stadija = 2;
        $stname = "开花";
    } else {
        $stadija = 3;
        $stname = "结果";
    }

    if ($stadija == 0) {
        $img = "<img src='/farm/phase/0-0.png' alt='' />";
    } else {
        $img = "<img src='/farm/phase/" . $semen['id'] . "-" . $stadija . ".png' alt='' />";
    }
}

if ($post['semen'] != 0) {
    echo "<div class='rowup'><img src='/farm/img/time.png' alt='' /> 该土地种植 " . $name_gr . " 的生长阶段 " . $stname . "";
    if ($post['time'] > time()) {
        $timeost = $post['time'] - time();
        $timediff = $timeost;
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
            if (time() < $post['time']) $time2 = $day . $hourfield . "时" . $minutefield . "分";
            else $time2 = 0;
        }
        echo ", <small>距离成熟 <b>" . $time2 . "</b></small>";
    }
    echo "</div>";
}


echo "<div class='rowdown'>" . $img . "<br />";

if ($post['time'] != NULL) {
    echo "<img src='/farm/progr.php?p=$peround' alt='$peround' /><br />";
}

$sznw = $post['sezon'] + 1;

/*
if ($sznw>$semen['let'])
{
$get = "<b><a href='/farm/gr.php?id=".$int."&amp;get'>Собрать</a></b>";
}
else
{
$get = "<b><a href='/farm/gr.php?id=".$int."&amp;next'>Собрать ($post[sezon] из $semen[let])</a></b>";
}
*/

if ($post['semen'] != 0) {

    if (time() > $post['time'] && $post['kol'] == 0) {

        $pt = rand($semen['rand1'], $semen['rand2']);

        dbquery("UPDATE `farm_gr` SET `kol` = '" . $pt . "' WHERE `id` = '" . $int . "' LIMIT 1");
    }

    echo "</div><div class='rowup'>";
    echo "<table class='post'><tr><td rowspan='2' style='width:48px'>";
    echo "<img src='/farm/img/man.png' alt='' /></td><td>";
    if (time() < $post['time']) {
        $rnd = rand($semen['rand1'], $semen['rand2']);
    } else {
        $rnd = $post['kol'];
    }
    $all = $semen['rand2'];
    if ($post['time'] < time() && $post['time_water'] < time()) {
        $imgwat = "(<img src='/farm/img/nowater.png' alt='' />)";
    } else {
        $imgwat = "";
    }

    $ural = "收获 <b>$rnd</b> 从 <b>$all</b>";
    if ($post['time'] > time()) {
        $ur = "";
    } else {
        $sznw = $post['sezon'] + 1;

        if ($sznw > $semen['let']) {
            $get = "<img src='/farm/img/harvest.png' alt='' /> <a href='/farm/gr.php?id=" . $int . "&amp;get'>收集</a>";
        } else {
            $get = "<img src='/farm/img/harvest.png' alt='' /> <a href='/farm/gr.php?id=" . $int . "&amp;next'>收集</a> (урожай $post[sezon] сезона из $semen[let])";
        }
        $ur = $get;
    }

    if ($fconf['weather'] == 1) {
        $wth = +3;
    }
    if ($fconf['weather'] == 2) {
        $wth = +2;
    }
    if ($fconf['weather'] == 3) {
        if ($fuser['teplica'] == 1) {
            $mes = true;
            $wthr = -1;
            $wth = 0;
        } else {
            $wth = -1;
        }
    }
    if ($fconf['weather'] == 4) {
        if ($fuser['teplica'] == 1) {
            $mes = true;
            $wthr = -3;
            $wth = 0;
        } else {
            $wth = -3;
        }
    }
    if ($fconf['weather'] == 5) {
        $wth = +1;
    }

    if ($fuser['selection'] == 1) {
        $prep = $semen['rand1'] / 100;
        $ums = ceil($prep * 5);
    }
    if ($fuser['selection'] == 2) {
        $prep = $semen['rand1'] / 100;
        $ums = ceil($prep * 10);
    }
    if ($fuser['selection'] == 3) {
        $prep = $semen['rand1'] / 100;
        $ums = ceil($prep * 15);
    }
    if ($fuser['selection'] == 4) {
        $prep = $semen['rand1'] / 100;
        $ums = ceil($prep * 20);
    }
    if ($fuser['selection'] == 5) {
        $prep = $semen['rand1'] / 100;
        $ums = ceil($prep * 25);
    }

    echo "" . $ural . " " . $imgwat . "";
    echo "</td></tr><tr><td>";
    echo "" . $semen['rand2'] . " 定期,";
    echo " " . $wth . " 天气影响";
    if (isset($mes)) {
        echo " (消除了与温室的技能 (" . $wthr . "))";
    }
    if ($fuser['selection'] > 0) {
        echo ", +" . $ums . " 技能选择";
    }
    echo "<br />" . $ur . "";
    echo "</td></tr></table>";
    if ($post['time'] > time()) {
        echo "<hr />";
        echo "<table class='post'><tr><td rowspan='2' style='width:48px'>";
        echo "<img src='/farm/img/woman.png' alt='' /></td><td>";
        echo "<img src='/farm/img/water.png' alt='' /> 浇灌土地铺";
        echo "</td></tr><tr><td>";
        if ($post['time_water'] > time()) {
            $ost = $post['time_water'] - time();

            $timediff = $ost;
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
                if (time() < $post['time']) $timeost = $day . $hourfield . "时" . $minutefield . "分";
                else $timeost = 0;
            }
            echo "土地铺已经浇过水了。下一次浇水是在 " . $timeost . "";
        } else {
            echo "<a href='/farm/gr.php?id=" . $int . "&amp;woter'>浇灌</a>";
        }
        echo "</td></tr></table>";
    }
    if ($post['semen'] != 0 && $post['time'] > time()) {
        echo "<hr /><table class='post'><tr><td rowspan='2' style='width:48px'>";
        echo "<img src='/farm/img/feliz.png' alt='' /></td><td>";
        echo "<img src='/farm/img/fertilize.png' alt='' /> 肥料（一次性效果）</td></tr><tr><td>";
        if ($post['udobr'] == 0) {
            $k2 = dbresult(dbquery("SELECT COUNT(*) FROM `farm_udobr` WHERE `id_user` = '$user[id]'"), 0);
            if ($k2 != 0) {
                $res2 = dbquery("select * from `farm_udobr` WHERE `id_user` = '$user[id]' ");
                echo "<form method='post' action='/farm/gr.php?id=" . $int . "&amp;$passgen' class='formfarm'>\n";
                echo "<select name='udobr'>";
                while ($post2 = dbarray($res2)) {
                    $semen2 = dbarray(dbquery("select * from `farm_udobr_name` WHERE  `id` = '$post2[udobr]'  LIMIT 1"));
                    echo "<option value='" . $post2['id'] . "'>" . $semen2['name'] . " [" . $post2['kol'] . "]</option>";
                }
                echo "</select><br />\n";
                echo "<input type='submit' name='save' value='施肥' />\n";
                echo "</form>\n";
            } else {
                echo '
<select name="cad" disabled="disabled" >
<option value="0" selected="selected">储存中没有化肥</option>
</select><br />';
            }
        } else {
            echo "已经使用了化肥";
        }
        echo "</td></tr></table>";
    }
    echo "</div>";
}

if ($post['semen'] == 0) {
    $k = dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id_user` = '$user[id]'"), 0);
    if ($k != 0) {
        $res = dbquery("select * from `farm_semen` WHERE `id_user` = '$user[id]' ");
        echo "<div class='rowup'>";
        echo "<table class='post'><tr><td rowspan='2' style='width:48px'>";
        echo "<img src='/farm/img/seed.png' alt='' /></td><td>";
        echo "<img src='/farm/img/plant.png' alt='' /> 种植一棵植物";
        echo "</td></tr><tr><td>";
        echo "<form method='post' action='?id=" . $int . "&amp;$passgen' class='formfarm'>\n";
        echo "Семена:<br />\n<select name='sadit'>";


        while ($post = dbarray($res)) {
            $semen = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '$post[semen]'  LIMIT 1"));
            $name_gr = $semen['name'];
            echo "<option value='" . $post['id'] . "'>" . $name_gr . " [" . $post['kol'] . "]</option>";
        }
        echo "</select><br />\n";
        echo "<input type='submit' name='save' value='植物' />\n";
        echo "</form>\n";
    } else {
        echo "<div class='rowup'>";
        echo "<table class='post'><tr><td rowspan='2' style='width:48px'>";
        echo "<img src='/farm/img/seed.png' alt='' /></td><td>";
        echo "<div class='/farm/img/plant.png' alt='' /> 种植一棵植物";
        echo "</td></tr><tr><td>";
        echo '
<select name="cad" disabled="disabled" ><br />
<option value="0" selected="selected">没有库存的种子</option>
</select><br />';
    }
    echo "</td></tr></table>";
    echo "</div>";
}
