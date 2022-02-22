<?
err();
aut();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `forum_t` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' ORDER BY `up` DESC,`time` DESC  LIMIT $start, $set[p_str]");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет тем в разделе \"$razdel[name]\"\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_assoc($q))
{

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

$post1=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=get_user($post1['id_user']);

echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>\n";

echo GradientText("$ank[nick]", "$ank[ncolor]", "$ank[ncolor2]");
echo "</a>\n";
echo "(".vremja($them['time_create']).")<br />\n";


$post2=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=get_user($post2['id_user']);


echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>\n";

echo GradientText("$ank2[nick]", "$ank2[ncolor]", "$ank2[ncolor2]");
echo "</a>\n";
echo "(".vremja($post2['time']).")<br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";
if ($k_page>1)str("/forum/$forum[id]/$razdel[id]/?",$k_page,$page); // Вывод страниц

?>