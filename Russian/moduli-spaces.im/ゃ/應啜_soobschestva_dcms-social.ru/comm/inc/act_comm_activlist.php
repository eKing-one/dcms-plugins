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
$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Желающие вступить'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

if(isset($_GET['yes']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['yes'])."'"),0)!=0)
{
$activate=mysql_query("SELECT * FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['yes'])."'");
$activate=mysql_fetch_array($activate);
$activate_user=get_user($activate['id_user']);
mysql_query("UPDATE `comm_users` SET `activate` = '1' WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['yes'])."'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$activate_user[id]', '$user[nick] одобрил Вашу заявку на вступление в сообщество [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$activate_user[id]', `id_ank` = '$user[id]', `type` = 'in_comm', `time` = '$time'");
msg("Вступительная заявка $activate_user[nick] одобрена");
}

if(isset($_GET['no']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['no'])."'"),0)!=0)
{
$activate=mysql_query("SELECT * FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['no'])."'");
$activate=mysql_fetch_array($activate);
$activate_user=get_user($activate['id_user']);
mysql_query("DELETE FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' AND `id` = '".intval($_GET['no'])."'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$activate_user[id]', '$user[nick] отклонил Вашу заявку на вступление в сообщество [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");
msg("Вступительная заявка $activate_user[nick] отклонена");
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет желающих\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0' LIMIT $start, $set[p_str]");

while($post=mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank2['id']);
echo "<br/><a href='?act=comm_activlist&id=$comm[id]&yes=$post[id]'>Разрешить</a>$rk<a href='?act=comm_activlist&id=$comm[id]&no=$post[id]'>Запретить</a><br/>\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=comm_activlist&id=$comm[id]&",$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='?act=comm_settings&id=$comm[id]'>В админку</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>