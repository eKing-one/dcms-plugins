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

include_once '../sys/inc/thead.php';



$set['title']='Открытые дуэли';
title();
aut();






?>

<style>
.vpravo{float:right;}
</style>

<?
if(isset($_GET['duels'])) {
echo '<div class="main">Дуэль <b>№ '.intval($_GET['duels']).'</b>';
if($user['level']>4){echo '<span class="vpravo"> <a href="?duel_delete='.intval($_GET['duels']).'"> Удалить</a></span>';}
echo '</div>';

$duels=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duels'])."' "));

if($duels['active'] != "2"){
header("Location: index.php?".SID); }

$ank = get_user($duels['id_user1']);

if($duels['id_user1'] != $user['id']) { $pr = '<img src="img/wait.png"> <a href="?duel='.$duels['id'].'&uch='.md5($duels['time']).'"><b>Принять участие</b></a>'; }
else { $pr = '<img src="img/wait.png"> <a href="invite.php?duel='.$duels['id'].'&token='.md5($duels['time']).'"><b>Пригласить пользователей</b></a>';}

echo'<div class="mess"><span class="vpravo"><a href="/info.php?id='.$ank['id'].' "><span class="mess"><img src="img/user.png"> '.$ank['nick'].'</span></a></span>
<a href="images/'.$duels['foto1'].'"><img src="images/'.$duels['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$pr.'<br/></div>';

}else{
echo '<div class="main">Открытые дуэли:</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `duels` WHERE `active` = '2' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0)
{
echo '<div class="err">Нет открытых дуэлей!</div>';
}

$q=mysql_query("SELECT * FROM `duels` WHERE `active` = '2' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($res = mysql_fetch_assoc($q)) {

$ank = get_user($res['id_user1']);
echo '<div class="main">Дуэль <b>№ '.$res['id'].'</b>';
if($user['level']>4){echo '<span class="vpravo"> <a href="?duel_delete='.$res['id'].'"> Удалить</a></span>';}
echo '</div>';

if($res['id_user1'] != $user['id']) { $pr = '<img src="img/wait.png"> <a href="?duel='.$res['id'].'&uch='.md5($res['time']).'"><b>Принять участие</b></a>'; }
else { $pr = '<img src="img/wait.png"> <a href="invite.php?duel='.$res['id'].'&token='.md5($res['time']).'"><b>Пригласить пользователей</b></a>';}

echo'<div class="mess"><span class="vpravo"><a href="/info.php?id='.$ank['id'].' "><span class="mess"><img src="img/user.png"> '.$ank['nick'].'</span></a></span>
<a href="images/'.$res['foto1'].'"><img src="images/'.$res['foto1'].'"  alt="*" class="image" align="left" height="50" width="50" style="margin: -7px 8px 1px 0px;"/>
</a><br/>'.$pr.'<br/></div>';




}

if(isset($_GET['duel']) and isset($_GET['uch'])) {

$duel=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel'])."' "));

if($duel['active'] != "2"){
header("Location: index.php?".SID); }
else {
header("Location: add_photo.php?duel=".intval($_GET['duel'])."".SID); }



}

if(isset($_GET['duel_delete']) and $user['level'] > 4) {

$del=mysql_fetch_assoc(mysql_query("SELECT * FROM `duels` WHERE `id` = '".intval($_GET['duel_delete'])."' "));

$path = 'images/'.$del['foto1'];
if (file_exists($path)) unlink($path);

mysql_query("DELETE FROM `duels` WHERE `id` = '".intval($_GET['duel_delete'])."' ");
mysql_query("DELETE FROM `duels_invite` WHERE `id_duels` = '".intval($_GET['duel_delete'])."' ");




header("Location: waiting.php".SID); 
}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц

}
echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';


err();
include_once '../sys/inc/tfoot.php';
?>