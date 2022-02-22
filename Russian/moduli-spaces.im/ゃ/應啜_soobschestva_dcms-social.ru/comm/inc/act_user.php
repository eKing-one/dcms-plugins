<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$ank=get_user(intval($_GET['id']));

$set['title'] = 'Сообщества '.$ank['nick']; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_user` = '$ank[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет сообществ\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_users` WHERE `id_user` = '$ank[id]' ORDER BY `last_time` DESC LIMIT $start, $set[p_str]");

while($post=mysql_fetch_array($q))
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '$post[id_comm]'");
$comm=mysql_fetch_array($comm);
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/comm.png' />\n";
echo "</td>\n";
$aname=NULL;
if($post['access']=='creator')$aname='создатель';
elseif($post['access']=='adm')$aname='администратор';
elseif($post['access']=='mod')$aname='модератор';
echo "<td class='p_t'>\n";
echo "<a href='?act=comm&id=$comm[id]'>".htmlspecialchars($comm['name'])."</a>\n";
if($aname!=NULL)echo " ($aname)";
echo "<br/>Вступил".($ank['pol']==0?'a':NULL)." ".vremja($post['time']);
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=user&id=$ank[id]&",$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='/info.php?id=$ank[id]'>$ank[nick]</a><br/></div>";
}
else{header("Location:/comm");exit;}
?>