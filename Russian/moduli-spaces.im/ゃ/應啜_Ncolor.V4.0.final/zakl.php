<?
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

$set['title']='Закладки';
include_once 'sys/inc/thead.php';
title();
aut(); // форма авторизации


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_user` = '$user[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет закладок\n";
echo "  </td>\n";
echo "   </tr>\n";
}
$q=mysql_query("SELECT * FROM `forum_zakl` WHERE `id_user` = '$user[id]' ORDER BY `time_obn` DESC LIMIT $start, $set[p_str]");
while ($zakl = mysql_fetch_assoc($q))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id` = '$zakl[id_them]' LIMIT 1"),0)==1)
{

$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$zakl[id_them]' LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));
echo "   <tr>\n";



if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/48/them_$them[up]$them[close].png' />";
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/14/them_$them[up]$them[close].png' alt='' />";
echo "  </td>\n";
}


$k_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0);
$k_n_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]' AND `time` > '$zakl[time]'"),0);

$page_z=k_page($k_p-$k_n_p+1,$set['p_str']);
if ($k_n_p==0)$k_n_p=NULL;else $k_n_p="/+$k_n_p";





echo "  <td class='p_t'>\n";
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=$page_z'>$them[name] ($k_p$k_n_p)</a>\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));
echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>\n";

echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "(".vremja($them['time_create']).")<br />\n";

$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `id` DESC LIMIT 1"));
$ank2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>\n";

echo GradientText("$ank2[nick]", "$ank2[ncolor]", "$ank2[ncolor2]");
echo "</a>\n";
echo "(".vremja($post2['time']).")<br />\n";
echo "  </td>\n";
echo "   </tr>\n";
}}
echo "</table>\n";
echo "<div class='foot'>\n";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
echo "&laquo;<a href='umenu.php'>Мое меню</a><br />\n";
echo "</div>\n";
include_once 'sys/inc/tfoot.php';
?>
