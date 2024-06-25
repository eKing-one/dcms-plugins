<?php
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
only_reg();

if (isset($_GET['start'])) {

    $fuser = dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '" . $user['id'] . "' LIMIT 1"));

    if ($fuser['k_poliv'] != 0) {
        $irnum = dbresult(dbquery("SELECT COUNT(*) FROM `farm_gr` WHERE `id_user` = '" . $user['id'] . "' AND `time` > '" . time() . "' AND `time_water` < '" . time() . "'"), 0);
        if ($irnum != 0) {
            if ($fuser['k_poliv'] == 1) {
                $irtime = 10 * $irnum;
                $irexp = 50 * $irnum;
            }
            if ($fuser['k_poliv'] == 2) {
                $irtime = 5 * $irnum;
                $irexp = 100 * $irnum;
            }
            if ($fuser['k_poliv'] == 3) {
                $irtime = 3 * $irnum;
                $irexp = 150 * $irnum;
            }
            $nwtime = time() + $irtime;

            dbquery("UPDATE `farm_user` SET `poliv` = `poliv`+'$irnum' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
            dbquery("UPDATE `farm_user` SET `exp` = `exp`+'$irexp' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");
            dbquery("UPDATE `farm_user` SET `k_poliv_time` = '$nwtime' WHERE `uid` = '" . $user['id'] . "' LIMIT 1");

            $query = dbquery("SELECT * FROM `farm_gr` WHERE `id_user` = '" . $user['id'] . "' AND `time` > '" . time() . "' AND `time_water` < '" . time() . "'");
            while ($gr = dbarray($query)) {
                dbquery("UPDATE `farm_gr` SET `time` = `time`-'1800' WHERE `id` = '" . $gr['id'] . "' LIMIT 1");
                $tmna = time() + 1800;
                dbquery("UPDATE `farm_gr` SET `time_water`='" . $tmna . "' WHERE `id` = '" . $gr['id'] . "' LIMIT 1");
            }

            add_farm_event('成功灌溉 ' . $irnum . ' 土地。支出 ' . $irtime . ' 秒，收到 ' . $irexp . ' 经验');
        } else {
            add_farm_event('没有需要浇水的苗土地');
        }
    } else {
        add_farm_event('你没有喷头');
    }
}
header("Location: /farm/garden/");
exit();
