<?
err();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' ORDER BY `up` DESC,`time` DESC  LIMIT $start, $set[p_str]");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <div class='err'>\n";
echo "Нет тем в форуме \"$forum[name]\"\n";
echo "</div>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_assoc($q))
{
if($num==1){
echo "<div class='nav2'>\n";
$num=0;
}else{
echo "<div class='nav1'>\n";
$num=1;}
echo "<b><a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]'>$them[name]</a> <a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' AND `id_them` = '$them[id]'"),0).")</a></b>\n";
$post1=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_gruppy` = '$gruppy[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` ASC LIMIT 1"));
$ank=get_user($post1['id_user']);
echo "<br/>";
echo "<u>Автор</u>: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>$ank[nick]</a> <font color=\"#ff0000\" size=\"2\">(".vremja($them['time_create']).")</font><br />\n";

$post2=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_gruppy` = '$gruppy[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
$ank2=get_user($post2['id_user']);

echo "<u>Посл.</u>: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>$ank2[nick]</a> <font color=\"#ff0000\" size=\"2\">(".vremja($post2['time']).")</font><br />\n";
echo "</div>\n";

echo "</tr>\n";

}
echo "</table>\n";
if ($k_page>1)str("?s=$gruppy[id]&id_forum=$forum[id]&",$k_page,$page); // Вывод страниц

?>
