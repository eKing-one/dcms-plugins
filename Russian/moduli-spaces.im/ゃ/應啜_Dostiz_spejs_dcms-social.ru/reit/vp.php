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
$set['title'] = 'Получение награды';
include_once '../sys/inc/thead.php';

title();
aut();
err();
$act = isset ($_GET['act']) ? $_GET['act'] : '';
switch ($act) {

/* ======= zd1 ======= */

case 'zd1' :
$lock_1 = $user['z1_lock'];
if ($lock_1 < 2) {
$pp = $user['z1_p'];
if ($pp > 2) {
mysql_query("UPDATE `user` SET `z1` = '".($user['z1']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.1)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z1_lock` = '".($user['z1_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
echo '<div class="nav1">Задание "<b>Закладочка</b>" выполнено!<br />Рейтинг: <b>+0.1</b></div>';
}else{
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}
}else{
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}
break;
/* ==== end ==== */

case 'zd2' :
$lock_2 = $user['z2_lock'];
if ($lock_2 < 2) {
if (($user['ank_city']==NULL) OR ($user['ank_name']==NULL) OR ($user['ank_d_r']==NULL) OR ($user['ank_m_r']==NULL) OR ($user['ank_g_r']==NULL) OR ($user['ank_o_sebe']==NULL)){
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z2` = '".($user['z2']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.2)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z2_lock` = '".($user['z2_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
echo '<div class="nav1">Задание "<b>А вот и я</b>" выполнено!<br />Рейтинг: <b>+0.2</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}
break;

/* ===== end ==== */

/* zd6 */
case 'zd6' :
$k_fr = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$user[id]' AND `i` = '1'"), 0);
$res = mysql_query("select `frend` from `frends` WHERE `user` = '$user[id]' AND `i` = '1'");
$lock_6 = $user['z6_lock'];
if ($lock_6 < 2) {
if ($k_fr < 5) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z6` = '".($user['z6']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z6_lock` = '".($user['z6_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Хороший друг</b>" выполнено!<br />Рейтинг: <b>+0.3</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;

/* end */


/* zd5 */
case 'zd5' :
$lock_5 = $user['z5_lock'];
if ($lock_5 < 2) {
$foto=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$user[id]'"),0);
if ($foto < 5) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z5` = '".($user['z5']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.2)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z5_lock` = '".($user['z5_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Родина должна знать своих героев</b>" выполнено!<br />Рейтинг: <b>+0.2</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;
/* end */


/* zd9 */
case 'zd9' :
$ft=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id_user` = '$user[id]'"),0);
$lock_9 = $user['z9_lock'];
if ($lock_9 < 2) {
if ($ft < 5) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z9` = '".($user['z9']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z9_lock` = '".($user['z9_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Люблю общаться</b>" выполнено!<br />Рейтинг: <b>+0.3</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;

/* end */

/* zd8 */
case 'zd8' :
$dn=mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE `id_user` = '".$user['id']."'"),0);
$lock_8 = $user['z8_lock'];
if ($lock_8 < 2) {
if ($dn < 5) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z8` = '".($user['z8']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.2)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z8_lock` = '".($user['z8_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Мегаблоггер</b>" выполнено!<br />Рейтинг: <b>+0.2</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;


/* end */ 


/* zd7 */
case 'zd7' :
$cp=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_post` WHERE `id_user` = '$user[id]'"),0);
$lock_7 = $user['z7_lock'];
if ($lock_7 < 2) {
if ($cp < 20) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z7` = '".($user['z7']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z7_lock` = '".($user['z7_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Настоящий чатланин</b>" выполнено!<br />Рейтинг: <b>+0.3</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;

/* end */


/* ====== zd3 */

case 'zd3' :
$lock_3 = $user['z3_lock'];
if ($lock_3 < 2) {

$k_music=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_music` WHERE `id_user` = '$user[id]'"),0);
if ($k_music < 5) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z3` = '".($user['z3']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.2)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z3_lock` = '".($user['z3_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db); echo '<div class="nav1">Задание "<b>Меломан</b>" выполнено!<br />Рейтинг: <b>+0.2</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}

break;

/* end */

/* zd4 */
case 'zd4' :
$lock_4 = $user['z4_lock'];
if ($lock_4 < 2) {
if (($user['ank_mail']==NULL)){
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z4` = '".($user['z4']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `ikey_reit` = '".($user['ikey_reit']+0.5)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z4_lock` = '".($user['z4_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
echo '<div class="nav1">Задание "<b>Гражданин</b>" выполнено!<br />Рейтинг: <b>+0.5</b></div>';
}
}else {
echo '<div class="nav1">Ты уже получал награду за это задание!</div>';
}
break;
/* end */


/* zd10 , наконец-то */
case 'zd10' :
$lock_10 = $user['z10_lock'];
if ($lock_10 < 2) {
echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
$lock_p_1 = $user['z1_lock'];
$lock_p_2 = $user['z2_lock'];
$lock_p_3 = $user['z3_lock'];
$lock_p_4 = $user['z4_lock'];
$lock_p_5 = $user['z5_lock'];
$lock_p_6 = $user['z6_lock'];
$lock_p_7 = $user['z7_lock'];
$lock_p_8 = $user['z8_lock'];
$lock_p_9 = $user['z9_lock'];

if (($lock_p_1 < 2) OR ($lock_p_2 < 2) OR ($lock_p_3 < 2) OR ($lock_p_4 < 2) OR ($lock_p_5 < 2) OR ($lock_p_6 < 2) OR ($lock_p_7 < 2) OR ($lock_p_8 < 2) OR ($lock_p_9 < 2)) {

echo '<div class="nav1">Задание еще не выполнено! Ты не можешь получить награду покуда не выполнишь задание.</div>';
}else{
mysql_query("UPDATE `user` SET `z10` = '".($user['z10']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `money` = '".($user['money']+5)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `z10_lock` = '".($user['z10_lock']+3)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
echo '<div class="nav1">Задание "<b>Заслужил награду</b>" выполнено!<br />Монет: <b>+5</b></div>';
}
}

break;

}
echo '<div class="foot"><font color="red">&bull;</font> <a href="/reit/zadanie.php">В задания</a></div>';
include_once '../sys/inc/tfoot.php';
?>
