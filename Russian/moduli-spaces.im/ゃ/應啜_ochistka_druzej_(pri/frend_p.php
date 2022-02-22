<?
# МОД ДРУЖБЫ ДЛЯ DCMS 6.x
# SimiX aka iNFERNO
# 4759229

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

$set['title'] = 'Призраки';
include_once 'sys/inc/thead.php';
title();
aut();
if (user_access('forum_razd_edit')){
$ank['id'] = $user['id'];
if (isset($_GET['id']))$ank['id'] = intval($_GET['id']);
$q = mysql_query("SELECT * FROM `user` WHERE `id` = $ank[id] LIMIT 1");
if (mysql_num_rows($q)==0){header("Location: index.php?".SID);exit;}

$ank = mysql_fetch_array($q);

if ($ank['id']==$user['id'])
{
	echo '<div class="navig">Призраки</div>';
}
if(isset($_GET['del']) && $_GET['del']=='all'){
$z = mysql_query("SELECT * FROM `frends` WHERE `frend` ORDER BY time DESC");
while ($p = mysql_fetch_array($z))
{
$b = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$p[frend]' LIMIT 1"));

if ($p[frend]!=$b[id]){

mysql_query("DELETE FROM `frends` WHERE `frend` = '$p[frend]'");
msg ('Призрак удален');
}

}
}
$q = mysql_query("SELECT * FROM `frends` WHERE `frend` ORDER BY time DESC");
while ($f = mysql_fetch_array($q))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));

if ($f[frend]!=$a[id]){
echo "$f[frend] - ";
}

}

echo "<center><div class=\"aut\"><a href='?del=all'>Очистка</a></div></center>\n";
echo '</table>'."\n";

echo "</div>";}

include_once 'sys/inc/tfoot.php';
?>