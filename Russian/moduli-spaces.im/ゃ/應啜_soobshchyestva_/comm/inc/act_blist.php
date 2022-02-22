<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak
if($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')
{
$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Черный список'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_GET['add']))
{
$ank2=get_user(intval($_GET['add']));
if($ank2['id']!=0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]'"),0)==0)
{
mysql_query("INSERT INTO `comm_blist` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]'");
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `id_ank` = '$user[id]', `type` = 'in_blist', `time` = '$time'");
}
else $err[]="Пользователь уже находится в Черном списке сообщества";
}
else $err[]="Пользователь не найден";
}

if(isset($_GET['delete']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]'"),0)!=0)
{
$ank2=get_user(mysql_result(mysql_query("SELECT `id_user` FROM `comm_blist` WHERE `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]'"),0));
mysql_query("DELETE FROM `comm_blist` WHERE `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]'");
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `id_ank` = '$user[id]', `type` = 'out_blist', `time` = '$time'");
}
else $err[]="Пользователь уже находится в Черном списке сообщества";
}
if(isset($_POST['nick']) && isset($_POST['submited']))
{
$ank2=mysql_query("SELECT * FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."'");
$ank2=mysql_fetch_array($ank2);

if($ank2['id']!=0)
{
header("Location:?act=blist&id=$comm[id]&add=$ank2[id]");
exit();
}
else $err[]="Пользователь не найден";
}
err();

echo "<form method='post'><input type='text' name='nick' value=''><input type='submit' name='submited' value='Добавить'></form>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет пользователей\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_blist` WHERE `id_comm` = '$comm[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");

while($post=mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank2['id']);
echo "<span style='float:right'><a href='?act=blist&id=$comm[id]&delete=$post[id]'><img src='/comm/img/delete.png'/></a></span>\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=blist&id=$comm[id]&",$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='?act=comm_settings&id=$comm[id]'>В админку</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>