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

if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
$set['title']=$gruppy['name'].' - Новые темы на форуме'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']  || isset($user) && $user['level']>0)
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id_gruppy`='$s'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id_gruppy`='$s' ORDER BY `time_create` DESC  LIMIT $start, $set[p_str]");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <div class='p_t'>\n";
echo "Нет тем\n";
echo "  </div>\n";
echo "   </tr>\n";
}
while ($them = mysql_fetch_assoc($q))
{


$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '$them[id_forum]' AND `id_gruppy`='$s' LIMIT 1"));
//$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '$post[id_them]' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));


echo "   <tr>\n";
if ($num==1){
echo "<div class='nav2'>\n";
$num=0;
}else{
echo "<div class='nav1'>\n";
$num=1;}
echo "<a href='forum.php?s=$s&id_forum=$forum[id]&id_them=$them[id]'>$them[name]</a> <a href='forum.php?s=$s&id_forum=$forum[id]&id_them=$them[id]&page=end'>(".mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_forum` = '$forum[id]' AND `id_gruppy`='$s' AND `id_them` = '$them[id]'"),0).")</a>\n";
echo "<a href='forum.php?s=$s'>Форум</a> &gt; <a href='forum.php?s=$s&id_forum=$forum[id]'>$forum[name]</a><br />\n";

$post1=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy`='$s' ORDER BY `time` ASC LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post1[id_user] LIMIT 1"));
echo "Автор: <a href='/info.php?id=$ank[id]' title='Анкета \"$ank[nick]\"'>$ank[nick]</a> <font color=\"#ff0000\" size=\"2\">(".vremja($them['time_create']).")</font><br />\n";

$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy`='$s' ORDER BY `time` DESC LIMIT 1"));
$ank2=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "Посл.: <a href='/info.php?id=$ank2[id]' title='Анкета \"$ank2[nick]\"'>$ank2[nick]</a> <font color=\"#ff0000\" size=\"2\">(".vremja($post['time']).")</font><br />\n";
echo "</div>\n";

echo "   </tr>\n";

}
echo "</table>\n";
if ($k_page>1)str("?s=$s&",$k_page,$page); // Вывод страниц




echo "<div class=\"foot\">\n";
echo "&raquo;<a href='new_p.php?s=$s'><u>Темы с новыми сообщениями</u></a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href=\"index.php?s=$s\" title=\"Вернуться в соо\">В сообщество</a><br />\n";
echo "</div>\n";
}
else
{
echo'Вам недоступен просмотр новых тем данного сообщества';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
