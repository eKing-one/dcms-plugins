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

$set['title']='Рейтинг игроков';
include '../../sys/inc/thead.php';
title();
aut();

echo "<div class='main_menu'>";

$sort=false;
$p_get=false;
if (isset($_GET['balls'])) {
echo "[Деньги]\n";
$sort="ORDER BY `balls` DESC, `ask` DESC";
$p_get="balls&amp;";
}
else echo "[<a href='?balls'>Деньги</a>]\n";

echo "\n|\n";

if (isset($_GET['ask'])) {
echo "[Ответы]\n";
$sort="ORDER BY `ask` DESC, `balls` DESC";
$p_get="ask&amp;";
}
else echo "[<a href='?ask'>Ответы</a>]\n";

echo "</div>";


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `million_reit`"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `million_reit` $sort LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Рейтинг пуст\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($player = mysql_fetch_assoc($q))
{
$ank=get_user($player['id_user']);
echo "   <tr>\n";

if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($ank['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank[pol].png' alt='' />";
echo "  </td>\n";
}
echo "  <td class='p_t'>\n";
echo "<a href='/info.php?id=$ank[id]'>$ank[nick]</a>".online($ank['id'])."\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";

if (isset($_GET['balls']) || !isset($_GET['ask'])) {
echo "Выиграно: <b><span class='on'>$player[balls]</span></b><br/>\n";
echo "Правильных ответов: <b><span class='on'>$player[ask]</span></b><br/>\n";
}
elseif (isset($_GET['ask'])) {
echo "Правильных ответов: <b><span class='on'>$player[ask]</span></b><br/>\n";
echo "Выиграно: <b><span class='on'>$player[balls]</span></b><br/>\n";
}

echo "  </td>\n";
echo "   </tr>\n";
}

echo "</table>\n";



if ($k_page>1)str("?$p_get",$k_page,$page); // Вывод страниц

echo "<div class='foot'>";
echo "&laquo; <a href='index.php'>В игру</a><br/>\n";
echo "</div>";
include '../../sys/inc/tfoot.php';
exit();
?>