<?php
echo '<div class="nav2">';
$lock_v_1 = $user['z1_lock'];
$lock_v_2 = $user['z2_lock'];
$lock_v_3 = $user['z3_lock'];
$lock_v_4 = $user['z4_lock'];
$lock_v_5 = $user['z5_lock'];
$lock_v_6 = $user['z6_lock'];
$lock_v_7 = $user['z7_lock'];
$lock_v_8 = $user['z8_lock'];
$lock_v_9 = $user['z9_lock'];
$lock_v_10 = $user['z10_lock'];
if ($lock_v_1 > 2) {
echo '<img src="/reit/img/vp/ac_pass_on_tm.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_2 > 2) {
echo '<img src="/reit/img/vp/ac_fill_anketa.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_3 > 2) {
echo '<img src="/reit/img/vp/ac_music_lover.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_4 > 2) {
echo '<img src="/reit/img/vp/ac_citizen.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_5 > 2) {
echo '<img src="/reit/img/vp/ac_add_photos.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_6 > 2) {
echo '<img src="/reit/img/vp/ac_comm_man.png" width="30" height="30" alt="" /> ';
}

if ($lock_v_7 > 2) {
echo '<img src="/reit/img/vp/ac_chatterbox.png" width="30" height="30" alt="" /> ';
}


if ($lock_v_8 > 2) {
echo '<img src="/reit/img/vp/ac_blogger.png" width="30" height="30" alt="" /> ';
}


if ($lock_v_9 > 2) {
echo '<img src="/reit/img/vp/ac_sociable.png" width="30" height="30" alt="" /> ';
}


if ($lock_v_10 > 2) {
echo '<img src="/reit/img/vp/zd10.png" width="30" height="30" alt="" /> ';
}


echo '</div>';
?>
