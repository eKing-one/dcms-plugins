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


$set['title']= 'Мой черный список'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

if (!isset($user)) {
header("Location: index.php");exit;
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `black_list` WHERE `id_user` = '".$user['id']."' "), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `black_list` WHERE `id_user` = '".$user['id']."' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "В вашем черном списке пусто\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($anketa = mysql_fetch_array($q))
{
echo "<tr>\n";
$ank = get_user($anketa['id_black_user']);

if ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo status($ank['id']);
echo "  </td>\n";
}


echo "  <td class='p_t'>\n";

echo "<a href='/info.php?id=$ank[id]'>$ank[nick]</a>\n";
echo "  ".medal($ank['id'])." ".online($ank['id'])."<br />\n";
echo vremja($anketa['time']);
echo "   </td>\n";
echo "   </tr>\n";
}

echo "</table>\n";


if ($k_page>1)str("?",$k_page,$page); // Вывод страниц




include_once '../sys/inc/tfoot.php';
?>