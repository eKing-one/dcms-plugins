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

if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);
$ank = get_user($ank['id']);

if(!$ank){header("Location: /index.php?".SID);exit;}if ($ank['id']==0)
{
	$ank = get_user($ank['id']);
	$set['title'] = $ank['nick'].' - достижения '; // заголовок страницы
	include_once '../sys/inc/thead.php';
	title();
	aut();
	echo "<span class=\"status\">$ank[group_name]</span><br />\n";if ($ank['ank_o_sebe']!=NULL)echo "<span class=\"ank_n\">О себе:</span> <span class=\"ank_d\">$ank[ank_o_sebe]</span><br />\n";
	if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
	echo "<div class='foot'>&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n</div>\n";include_once '../sys/inc/tfoot.php';
	exit;
}
/* =========================================== */
/* Верх */

	$ank = get_user($ank['id']);
	$set['title'] = 'Достижения - '.$ank['nick'].' '; // заголовок страницы
	include_once '../sys/inc/thead.php';
	title();
	aut();


/* ==== function ===== */

function back() {
echo '<div class="overfl_hid t-bg2 light_border_bottom">
<a class="t-block_item t-light_link t-link_no_underline overfl_hid t-padd_left  " href="/reit/zadanie.php"><span class="t-block_item stnd_padd t-bg_arrow_prev"><span class="t-padd_left"> Назад </span></span></a>
</div>';
}
/* END */

/* =============================================== */

/* Инклудим дополнительные стили */

include_once '../reit/style.php';
include_once '../reit/style1.php';
include_once '../reit/style2.php';

/* END */
/* =============================================== */
$act = isset ($_GET['act']) ? $_GET['act'] : '';
switch ($act) {

/* =============================================== */


/* Z10 */
case 'z10' :

echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';

$lock_10 = $user['z10_lock'];
if ($lock_10 < 2) {
echo '<img src="/reit/img/zd/zd10.png" alt="">
<br>
<b> Заслужил награду </b>
</div>
<div class="m">
Для получения награды, необходимо выполнить все пункты достижений.</a>';


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


}else{
echo '<br /><br /><a href="/reit/vp.php?act=zd10" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 5 монет</a>';
}
}else{
echo '<img src="img/zd/zd10.png" alt=""><br>
<b class="service_item"> Название: </b> Получить награду</b> (+5 монет) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Получить награду за выполнение всех пунктов достижений.';
}
echo '</div></div>';
back();
echo '</div>';

break;



/* END  Фух, ипать..... закончил наконец-то */

/* z4 */

case 'z4' :
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_4 = $user['z4_lock'];
if ($lock_4 < 2) {
echo '<div class="list_item">
<div class="list_item">
<img src="/reit/img/zd/ac_citizen.png" alt="">
<br>
<b> Гражданин </b>
</div>
<div class="m">
Для получения награды, необходимо указать свой em@il. <br><a href="/user/info/edit.php?set=mail"> Заполнить em@il</a>';
$lock_4 = $user['z4_lock'];
if ($lock_4 < 2) {
if (($user['ank_mail']==NULL)) {
}else{
echo '<br /><br /><a href="/reit/vp.php?act=zd4" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.5 рейтинга</a>';
}

echo '</div>';
}
echo '</div>';
}else{
echo '<img src="img/zd/ac_citizen.png" alt=""><br>
<b class="service_item"> Название: </b> Гражданин (+0.5 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Указать свой электронный ящик.
</div>';

}
echo '</div></div>';
back();

break;

/* end */

/* =========== z6 ====== */
case 'z6' :
$k_fr = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '$ank[id]' AND `i` = '1'"), 0);
$res = mysql_query("select `frend` from `frends` WHERE `user` = '$ank[id]' AND `i` = '1'");
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_6 = $user['z6_lock'];
if ($lock_6 < 2) {
echo '
<img src="/reit/img/zd/ac_comm_man.png" alt="">
<br>
<b> Хороший друг</b>
</div>
<div class="m">Для получения награды, необходимо добавить в друзья 5 или более человек.';
$lock_6 = $user['z6_lock'];
if ($lock_6 < 2) {
if ($k_fr >= 5) {
echo '<br /><br /><a href="/reit/vp.php?act=zd6" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.3 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_comm_man.png" alt=""><br>
<b class="service_item"> Название: </b> Хороший друг</b> (+0.3 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Добавить в друзья 5 или более пользователей.';
}
echo '</div></div>';
back();
echo '</div>';
break;


/* end */


/* ====== z7 ====== */
case 'z7' :
$cp=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_post` WHERE `id_user` = '$user[id]'"),0);
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_7 = $user['z7_lock'];
if ($lock_7 < 2) { 
echo '
<img src="/reit/img/zd/ac_chatterbox.png" alt="">
<br>
<b> Настоящий чатланин</b>
</div>
<div class="m">Для получения награды, необходимо написать минимум 20 сообщений в чат.<br /><a href="/chat/">Перейти в чат</a>';
$lock_7 = $user['z7_lock'];
if ($lock_7 < 2) {
if ($cp >= 20) {
echo '<br /><br /><a href="/reit/vp.php?act=zd7" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.3 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_chatterbox.png" alt=""><br>
<b class="service_item"> Название: </b> Настоящий чатланин</b> (+0.3 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Добавить 20 или более сообщений в чат.';
}
echo '</div></div>';
back();
echo '</div>';

break;



/* end */


/* zd9 */
case 'z9' :
$ft=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id_user` = '$user[id]'"),0);
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_9 = $user['z9_lock'];
if ($lock_9 < 2) { 
echo '
<img src="/reit/img/zd/ac_sociable.png" alt="">
<br>
<b> Люблю общаться</b>
</div>
<div class="m">Создать минимум 5 тем на форуме.<br /><a href="/forum/">Перейти на форум</a>';
$lock_9 = $user['z9_lock'];
if ($lock_9 < 2) {
if ($ft >= 5) {
echo '<br /><br /><a href="/reit/vp.php?act=zd9" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.3 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_sociable.png" alt=""><br>
<b class="service_item"> Название: </b> Люблю общаться </b> (+0.3 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Создать минимум 5 тем на форуме.';
}
echo '</div></div>';
back();
echo '</div>';

break;



/* end */


/* ====== z8 ====== */
case 'z8' :
$dn=mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE `id_user` = '".$user['id']."'"),0);
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_8 = $user['z8_lock'];
if ($lock_8 < 2) { 
echo '
<img src="/reit/img/zd/ac_blogger.png" alt="">
<br>
<b> Мегаблоггер</b>
</div>
<div class="m">Создать минимум 5 дневников.<br /><a href="/plugins/notes/user.php?id='.$user['id'].'">Мои дневники</a>';
$lock_8 = $user['z8_lock'];
if ($lock_8 < 2) {
if ($dn >= 5) {
echo '<br /><br /><a href="/reit/vp.php?act=zd8" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.2 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_blogger.png" alt=""><br>
<b class="service_item"> Название: </b> Мегаблоггер</b> (+0.3 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Создать минимум 5 дневников.';
}
echo '</div></div>';
back();
echo '</div>';

break;



/* end */







/* z5 */
case 'z5';
$foto=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id_user` = '$user[id]'"),0);

echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_5 = $user['z5_lock'];
if ($lock_5 < 2) { 
echo '
<img src="/reit/img/zd/ac_add_photos.png" alt="">
<br>
<b> Родина должного знать своих героев</b>
</div>
<div class="m">Для получения награды, необходимо добавить 5 или более своих фото.<br /><a href="/foto/'.$user['id'].'/">Добавить фотографии</a>';
$lock_5 = $user['z5_lock'];
if ($lock_5 < 2) {
if ($foto >= 5) {
echo '<br /><br /><a href="/reit/vp.php?act=zd5" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.2 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_add_photos.png" alt=""><br>
<b class="service_item"> Название: </b> Родина должна знать своих героев</b> (+0.2 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Добавить 5 или более своих фотографий.';
}
echo '</div></div>';
back();
echo '</div>';

break;


/* end */


/* ======== z3 ======= */
case 'z3' :
$k_music=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_music` WHERE `id_user` = '$user[id]'"),0);
echo '<div class="main_zd">';
echo '<div class="list_item">
<div class="list_item">';
$lock_3 = $user['z3_lock'];
if ($lock_3 < 2) { ///если НЕ выполнено
echo '
<img src="/reit/img/zd/ac_music_lover.png" alt="">
<br>
<b> Меломан </b>
</div>
<div class="m">Для выполнения задания вам нужно добавить минимум 5 треков к себе в музыку. Сделать это можно нажав на значок <img src="/reit/img/play.png" alt="" /> в выбранном треке. <br /><a href="/obmen/"> В Зону Обмена</a>';
$lock_3 = $user['z3_lock'];
if ($lock_3 < 2) {
if ($k_music >= 5) {
echo '<br /><br /><a href="/reit/vp.php?act=zd3" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.2 рейтинга</a>';
}
}
}else{
echo '<img src="img/zd/ac_music_lover.png" alt=""><br>
<b class="service_item"> Название: </b> Меломан</b> (+0.2 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Добавить 5 треков к себе в музыку.';
}
echo '</div></div>';
back();
echo '</div>';

break;




/* ======== end ====== */



/* ============ z2 ========= */
case 'z2':
echo '<div class="main_zd">';
$lock_2 = $user['z2_lock'];
if ($lock_2 < 2) {

echo '<div class="list_item">
<div class="list_item">
<img src="/reit/img/zd/ac_fill_anketa.png" alt="">
<br>
<b> А вот и я </b>
</div>
<div class="m">
Для получения награды, необходимо заполнить основные поля анкеты. <br><a href="/user/info/edit.php"> Редактировать анкету </a>';


$lock_2 = $user['z2_lock'];
if ($lock_2 < 2) {
if (($user['ank_city']==NULL) OR ($user['ank_name']==NULL) OR ($user['ank_d_r']==NULL) OR ($user['ank_m_r']==NULL) OR ($user['ank_g_r']==NULL) OR ($user['ank_o_sebe']==NULL)){
}else{
echo '<br /><br /><a href="/reit/vp.php?act=zd2" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.2 рейтинга</a>';
}

echo '</div>';
}
}else{
echo '<div class="main_zd">';
echo '<div class="list_item">
<img src="img/zd/ac_fill_anketa.png" alt=""><br>
<b class="service_item"> Название: </b> А вот и я (+0.2 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Заполнить основные поля анкеты.
</div>';

}
echo '</div></div>';
back();
break;
/* ======= end ======== */



/* ====== z1 ====== */

case 'z1':
echo '<div class="main_zd">';
$lock1 = $user['z1_lock'];
if ($lock1 > 2) {
echo '<div class="list_item">
<img src="img/zd/ac_pass_on_tm.png" alt=""><br>
<b class="service_item"> Название: </b> Закладочка (+0.1 к рейтингу) <br>
<b class="service_item"> Статус: </b> Выполнено <br>
<b class="service_item"> Описание: </b> Создать закладку для входа
</div>';
}else{
echo '
<div class="list_item">';
echo '<div class="list_item">
<img src="/reit/img/zd/ac_pass_on_tm.png" alt="">
<br>
<b> Закладочка</b>
</div>
<div class="m">
Чтобы сделать закладку, перейдите на <a href="/reit/zd.php?act=zd1"><font color="green">эту</font></b></a> страницу и создайте закладку, после добавьте ту ссылку, которая получилась, в закладки вашего браузера. <br> По этой закладке вы сможете заходить на наш сайт без ввода пароля. <br> Чтобы получить 0.1 рейтинга, просто вернитесь на эту страницу после того как выполните задание.';
$lock_1 = $user['z1_lock'];
if ($lock_1 < 2) {
$p = $user['z1_p'];
if ($p > 2) {
echo '<br /><br /><a href="/reit/vp.php?act=zd1" style="border: 2px dotted navy; padding: 2px; text-decoration: none; color: black;">Получить 0.1 рейтинга</a>';
}
echo '</div>';
}



}
echo '
</div>';
back();
break;




/* =============================================== */
default:


echo '<div class="bottom_link_block">
<span class="m"> Рейтинг: '.$ank['ikey_reit'].'</span> <a href="/reit/faq_reit.php"><img src="/reit/img/about.gif" alt="" /></a></div>';

/* Выводим задания */

echo '<div class="light_border_bottom bottom_pad">';
$z1 = $ank['z1'];
if ($z1 < 2) {

/* ЗАДАНИЕ #1 */
echo '<a href="?act=z1" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_pass_on_tm.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Закладочка</b>
<small class="light_grey"> (Рейтинг +0.1) </small>
<br>
<span class="light_grey"> Создать закладку для входа </span>
</div>
</div>
</a>
';

}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z1" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_pass_on_tm.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Закладочка</b>
<small> (Рейтинг +0.1) </small>
<br>
<span> Создать закладку для входа </span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #1 */

/* ЗАДАНИЕ #2 */ 
$z2 = $ank['z2'];
if ($z2 < 2) {

echo '<a href="?act=z2" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_fill_anketa.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> А вот и я </b>
<small class="light_grey"> (Рейтинг +0.2) </small>
<br>
<span class="light_grey"> Заполнить основные поля анкеты
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z2" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_fill_anketa.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> А вот и я</b>
<small> (Рейтинг +0.2) </small>
<br>
<span> Заполнить основные поля анкеты</span>
</div>
</div>
</a>';
echo '</div>';
}

/* КОНЕЦ ЗАДАНИЯ #2 */


/* ===== ЗАДАНИЕ #3 ==== */
$z3 = $ank['z3'];
if ($z3 < 2) {
echo '<a href="?act=z3" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_music_lover.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Меломан </b>
<small class="light_grey"> (Рейтинг +0.2) </small>
<br>
<span class="light_grey"> Сохранить к себе 5 музыкальных треков
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z3" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_music_lover.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Меломан</b>
<small> (Рейтинг +0.2) </small>
<br>
<span> Сохранить к себе 5 музыкальных треков </span>
</div>
</div>
</a>';
echo '</div>';
}

/* КОНЕЦ ЗАДАНИЯ #3 */


/* ЗАДАНИЕ #4 */

$z4 = $ank['z4'];
if ($z4 < 2) {
echo '<a href="?act=z4" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_citizen.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Гражданин </b>
<small class="light_grey"> (Рейтинг +0.5) </small>
<br>
<span class="light_grey"> Указать свой em@il в анкете</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z4" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_citizen.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Гражданин</b>
<small> (Рейтинг +0.5) </small>
<br>
<span>  Указать свой em@i в анкете</span>
</div>
</div>
</a>';
echo '</div>';
}


/* КОНЕЦ ЗАДАНИЯ #4 */

/* ЗАДАНИЕ #5 */

$z5 = $ank['z5'];
if ($z5 < 2) {
echo '<a href="?act=z5" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_add_photos.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Родина должна знать своих героев </b>
<small class="light_grey"> (Рейтинг +0.5) </small>
<br>
<span class="light_grey"> Добавить 5 своих фотографий</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z5" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_add_photos.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Родина должна знать своих героев </b>
<small> (Рейтинг +0.5) </small>
<br>
<span>  Добавить 5 своих фотографий</span>
</div>
</div>
</a>';
echo '</div>';
}

/* КОНЕЦ ЗАДАНИЯ #5 */


/* ЗАДАНИЕ #6 */

$z6 = $ank['z6'];
if ($z6 < 2) {
echo '<a href="?act=z6" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_comm_man.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Хороший друг</b>
<small class="light_grey"> (Рейтинг +0.3) </small>
<br>
<span class="light_grey"> Добавить в друзья как минимум 5 человек</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z6" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_comm_man.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Хороший друг</b>
<small> (Рейтинг +0.3) </small>
<br>
<span>  Добавить в друзья как минимум 5 человек</span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #6 */


/* ЗАДАНИЕ #7 */

$z7 = $ank['z7'];
if ($z7 < 2) {
echo '<a href="?act=z7" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_chatterbox.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Настоящий чатланин </b>
<small class="light_grey"> (Рейтинг +0.3) </small>
<br>
<span class="light_grey"> Написать минимум 20 сообщений в чат</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z7" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_chatterbox.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Настоящий чатланин </b>
<small> (Рейтинг +0.3) </small>
<br>
<span> Написать минимум 20 сообщений в чат</span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #7 */

/* ЗАДАНИЕ #8 */

$z8 = $ank['z8'];
if ($z8 < 2) {
echo '<a href="?act=z8" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_blogger.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Мегаблоггер</b>
<small class="light_grey"> (Рейтинг +0.2) </small>
<br>
<span class="light_grey"> Написать минимум 5 дневников</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z8" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_blogger.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Мегаблоггер</b>
<small> (Рейтинг +0.2) </small>
<br>
<span> Написать минимум 20 сообщений в чат</span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #8 */


/* ЗАДАНИЕ #9 */

$z9 = $ank['z9'];
if ($z9 < 2) {
echo '<a href="?act=z9" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/ac_sociable.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Люблю общаться</b>
<small class="light_grey"> (Рейтинг +0.3) </small>
<br>
<span class="light_grey"> Создать минимум 5 тем на форуме</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z9" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/ac_sociable.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Люблю общаться </b>
<small> (Рейтинг +0.3) </small>
<br>
<span> Создать минимум 5 тем на форуме</span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #9 */


/* ЗАДАНИЕ #10 */

$z10 = $ank['z10'];
if ($z10 < 2) {
echo '<a href="?act=z10" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="att_it mt_0 oh">
<img src="/reit/img/zadanie/zd10.png" alt="" class="left dot_pic">
<div class="oh">
<b class="t-link_item_hover light_grey"> Заслужил награду</b>
<small class="light_grey"> (+5 монет) </small>
<br>
<span class="light_grey"> Получить 5 монет, за то выполнили все пункты выше.</span>
</div>
</div>
</a>';
}else{
echo '<div class="light_border_bottom bottom_pad">
<a href="?act=z10" class="stnd_padd pdb t-block_item t-link_no_underline_block">
<div class="pos_block round_corners oh">
<img src="img/vp/zd10.png" alt="" class="left dot_pic">
<div class="oh">
<img src="img/check.png" alt="" class="right">
<b class="t-strong_item t-link_item_hover"> Заслужил награду</b>
<small> (+5 монет) </small>
<br>
<span> Получить 5 монет, за то что выполнили все пункты выше. </span>
</div>
</div>
</a>';
echo '</div>';
}
/* КОНЕЦ ЗАДАНИЯ #10 */




/* =============================================== */
}
/* =============================================== */




include_once '../sys/inc/tfoot.php';
?>
