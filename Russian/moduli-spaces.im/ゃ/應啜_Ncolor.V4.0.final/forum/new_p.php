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


$set['title']='Форум - новое в темах';
include_once '../sys/inc/thead.php';
title();
aut(); // форма авторизации


$adm_add=NULL;
$adm_add2=NULL;
if (!isset($user) || $user['level']==0){
$q222=mysql_query("SELECT * FROM `forum_f` WHERE `adm` = '1'");
while ($adm_f = mysql_fetch_assoc($q222))
{
$adm_add[]="`id_forum` <> '$adm_f[id]'";
}
if (sizeof($adm_add)!=0)
$adm_add2=' WHERE'.implode(' AND ', $adm_add);
}


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t`$adm_add2"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `forum_t`$adm_add2 ORDER BY `time` DESC LIMIT $start, $set[p_str]");



if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет тем\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_assoc($q))
{


$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));
//$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$post[id_them]' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));


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


echo "  <td class='p_t'>\n";
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>$them[name]</a> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0).")</a>\n";
echo "  </td>\n";
echo "   </tr>\n";


echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";


echo "<a href='/forum/$forum[id]/'>$forum[name]</a> &gt; <a href='/forum/$forum[id]/$razdel[id]/'>$razdel[name]</a><br />\n";

$post1=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post1[id_user] LIMIT 1"));
echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>\n";

echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "(".vremja($them['time_create']).")<br />\n";

$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>\n";

echo GradientText("$ank2[nick]", "$ank2[ncolor]", "$ank2[ncolor2]");
echo "</a>\n";
echo "(".vremja($post2['time']).")<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц




echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"new_p.php\">Новые темы</a><br />\n";
echo "&laquo;<a href=\"index.php\" title=\"Вернуться к подфорумам\">Форум</a><br />\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
?>