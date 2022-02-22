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
/* Бан пользователя */ 

if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `razdel` = 'guest' AND `id_user` = '$user[id]' AND (`time` > '$time' OR `view` = '0' OR `navsegda` = '1')"), 0)!=0)
{header('Location: /ban.php?'.SID);exit;}

$poster=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '".intval($_GET['id_them'])."' AND `id` = '".intval($_GET['id_post'])."' "));
$set['title']=''.htmlspecialchars($poster['msg']).''; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

$id_post = intval($_GET['id_post']);
$id_them = intval($_GET['id_them']);
if (isset($_GET['liked'])){
$stat = '`like`'; 
$stop = 'like';
$count = '+1';
}
if (isset($_GET['disliked'])){
$stat = '`dislike`'; 
$stop = 'dislike';
$count = '-1';
}
$break = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_like` WHERE `id_them` = '$id_them' AND `id_post` = '$id_post' "));
if (!isset($_GET['liked']) && !isset($_GET['disliked']) || $break[$stop] == 0) {
header("Location: index.php");exit;
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like_users` WHERE `id_them` = '$id_them' AND `id_post` = '$id_post' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `forum_like_users` WHERE `id_them` = '$id_them' AND `id_post` = '$id_post' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет никого, кто проголосовал за пост\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($anketa = mysql_fetch_array($q))
{
echo "   <tr>\n";
$ank = get_user($anketa['id_user']);

if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($guest['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "".status($ank['id'])."";
echo "  </td>\n";
}



echo "  <td class='p_t'>\n";
echo "<a href='/info.php?id=$ank[id]'>$ank[nick]</a> ".$count."\n";
echo "  ".medal($ank['id'])." ".online($ank['id'])."<br />\n";
echo vremja($anketa['time']);
echo "   </td>\n";
echo "   </tr>\n";
}

echo "</table>\n";


if ($k_page>1)str("?",$k_page,$page); // Вывод страниц




include_once '../sys/inc/tfoot.php';
?>