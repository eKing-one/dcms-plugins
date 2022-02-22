<?php
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

only_reg();

$set['title'] = 'Мой журнал &amp; '.$_SERVER['HTTP_HOST'];
include_once 'sys/inc/thead.php';

title();
aut();

echo "<div class='foot'>";
echo "<a href='jurnal.php'>Все</a>|";
if($_GET[type]!='forum'){
echo "<a href='jurnal.php?type=forum'>Форум</a>|";
}
else {
echo "Форум|";
}
if($_GET[type]!='blog'){
echo "<a href='jurnal.php?type=blog'>Дневник</a>|";
}
else {
echo "Дневник|";
}
if($_GET[type]!='foto'){
echo "<a href='jurnal.php?type=foto'>Фото</a>|";
}
else {
echo "Фото|";
}

echo "</div>";

echo '<div class="p_m">';

if($_GET[type]=='forum') {

mysql_query("UPDATE `jurnal` SET `read` = '1' WHERE `id_kont` = '".$user['id']."' AND `type` = 'forum' AND `read` = '0'");
echo '<table class="post">';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'forum' AND `id_kont` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

if($k_post==0){
	echo '<div class="p_m">Нет новых событий!</div>';
}

$q=mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'forum' AND `id_kont` = '".$user['id']."' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
	echo '<tr><td class="icon" rowspan="2"></td><td class="p_t">&nbsp;'.vremja($post['time']).'</td></tr><tr>';
echo '<td class="p_m">'.output_text($post['msg']).'</td></tr>';
}

echo '</table>';

if($k_page>1){
	str("jurnal.php?id=".$ank['id']."&amp;",$k_page,$page);
}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
elseif($_GET[type]=='foto') {

mysql_query("UPDATE `jurnal` SET `read` = '1' WHERE `id_kont` = '".$user['id']."' AND `type` = 'foto' AND `read` = '0'");
echo '<table class="post">';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'foto' AND `id_kont` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

if($k_post==0){
	echo '<div class="p_m">Нет новых событий!</div>';
}

$q=mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'foto' AND `id_kont` = '".$user['id']."' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
	echo '<tr><td class="icon" rowspan="2"></td><td class="p_t">&nbsp;'.vremja($post['time']).'</td></tr><tr>';
echo '<td class="p_m">'.output_text($post['msg']).'</td></tr>';
}

echo '</table>';

if($k_page>1){
	str("jurnal.php?id=".$ank['id']."&amp;",$k_page,$page);
}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

elseif($_GET[type]=='blog') {

mysql_query("UPDATE `jurnal` SET `read` = '1' WHERE `id_kont` = '".$user['id']."' AND `type` = 'blog' AND `read` = '0'");
echo '<table class="post">';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'blog' AND `id_kont` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

if($k_post==0){
	echo '<div class="p_m">Нет новых событий!</div>';
}

$q=mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `type` = 'blog' AND `id_kont` = '".$user['id']."' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
	echo '<tr><td class="icon" rowspan="2"></td><td class="p_t">&nbsp;'.vremja($post['time']).'</td></tr><tr>';
echo '<td class="p_m">'.output_text($post['msg']).'</td></tr>';
}

echo '</table>';

if($k_page>1){
	str("jurnal.php?id=".$ank['id']."&amp;",$k_page,$page);
}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////
else
{
mysql_query("UPDATE `jurnal` SET `read` = '1' WHERE `id_kont` = '".$user['id']."' AND `read` = '0'");
echo '<table class="post">';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `id_user` = '0' AND `id_kont` = '".$user['id']."'"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

if($k_post==0){
	echo '<div class="p_m">Нет новых событий!</div>';
}




$q=mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `id_kont` = '".$user['id']."' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
	echo '<tr><td class="icon" rowspan="2"></td><td class="p_t">&nbsp;'.vremja($post['time']).'</td></tr><tr>';
echo '<td class="p_m">'.output_text($post['msg']).'</td></tr>';
}

echo '</table>';

if($k_page>1){
	str("jurnal.php?id=".$ank['id']."&amp;",$k_page,$page);
}

}
echo "&nbsp;<a href='jurnal_del.php?del_all=$user[id]'>Очистить журнал</a>";
echo '<div class="foot">&nbsp;<a href="info.php?id='.$user['id'].'">Моя страничка</a></div>';


echo '</div>';
include_once 'sys/inc/tfoot.php';
?>
