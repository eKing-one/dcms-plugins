<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Журнал'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($_GET['sort']))
{
$sort = htmlspecialchars($_GET['sort']);
if ($sort == 'sys')$qsort = " AND (`type` = 'in_blist' OR `type` = 'out_blist' OR `type` = 'access')";
elseif ($sort == 'in_comm')$qsort = " AND `type` = 'in_comm'";
elseif ($sort == 'out_comm')$qsort = " AND `type` = 'out_comm'";
else
{
$sort = "all";
$qsort = NULL;
}
}
else
{
$sort = "all";
$qsort = NULL;
}
if(isset($_POST['nick']) && isset($_POST['submited']))
{
$ank2=mysql_query("SELECT * FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."'");
$ank2=mysql_fetch_array($ank2);

if($ank2['id']!=0)
{
header("Location:?act=comm_journal&id=$comm[id]&user=$ank2[id]&sort=$sort");
exit();
}
else $err[]="Пользователь не найден";
}
err();
if (isset($_GET['user']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['user'])."'"),0)!=0)$ank_act=get_user(intval($_GET['user']));

echo "<form method='post' action='?act=comm_journal&id=$comm[id]&sort=$sort'>\n";
echo "<input type='text' name='nick' value=''>\n";
echo "<input type='submit' name='submited' value='Найти'>\n";
if (isset($ank_act))echo "<br />\nАктивность <a href='/info.php?id=$ank_act[id]'>$ank_act[nick]</a> <a href='?act=comm_journal&id=$comm[id]&sort=$sort'><img src='/comm/img/delete.png' /></a>\n";
echo "</form>";

echo "<div class='p_t'>".($sort!='all'?"<a href='?act=comm_journal&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=all'>":NULL)."Все".($sort!='all'?"</a>":NULL)." | ".($sort!='sys'?"<a href='?act=comm_journal&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=sys'>":NULL)."Служебные".($sort!='sys'?"</a>":NULL)." | ".($sort!='in_comm'?"<a href='?act=comm_journal&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=in_comm'>":NULL)."Вступили".($sort!='in_comm'?"</a>":NULL)." | ".($sort!='out_comm'?"<a href='?act=comm_journal&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=out_comm'>":NULL)."Покинули".($sort!='out_comm'?"</a>":NULL)."</div>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_journal` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND (`id_ank` = '$ank_act[id]' OR (`id_user` = '$ank_act[id]' AND `type` != 'access'))":NULL)."$qsort"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет записей\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_journal` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND (`id_ank` = '$ank_act[id]' OR (`id_user` = '$ank_act[id]' AND `type` != 'access'))":NULL)."$qsort ORDER BY `time` DESC LIMIT $start, $set[p_str]");

while($post=mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
$ank3=get_user($post['id_ank']);
if ($post['type']=='in_blist')$t="<a href='?act=comm_journal&id=$comm[id]&user=$ank3[id]'>$ank3[nick]</a> занес в Черный список <a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>\n";
elseif ($post['type']=='out_blist')$t="<a href='?act=comm_journal&id=$comm[id]&user=$ank3[id]'>$ank3[nick]</a> удалил из Черного списка <a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>\n";
elseif ($post['type']=='in_comm')$t="<a href='?act=comm_journal&id=$comm[id]&user=$ank2[id]'>$ank2[nick]</a> вступил в сообщество\n";
elseif ($post['type']=='out_comm')$t="<a href='?act=comm_journal&id=$comm[id]&user=$ank2[id]'>$ank2[nick]</a> покинул сообщество\n";
elseif ($post['type']=='access')
{
if ($post['access']=='user')$access_name="обычным участником";
elseif ($post['access']=='mod')$access_name="модератором";
elseif ($post['access']=='adm')$access_name="администратором";
elseif ($post['access']=='creator')$access_name="создателем";
$t="<a href='?act=comm_journal&id=$comm[id]&user=$ank3[id]'>$ank3[nick]</a> назначил <a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> $access_name\n";
}
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>\n";
echo $t."\n<span style='float: right;'>".vremja($post['time'])."</span>\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=comm_journal&id=$comm[id]&sort=$sort&".(isset($ank_act)?"user=$ank_act[id]&":NULL),$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
?>