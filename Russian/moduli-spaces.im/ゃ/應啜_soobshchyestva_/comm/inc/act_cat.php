<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'");
$cat=mysql_fetch_array($cat);

$set['title'] = 'Сообщества - '.htmlspecialchars($cat['name']); // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($_GET['sort']))
{
$sort=htmlspecialchars($_GET['sort']);
if ($sort=='new')
{
$qsort = "time";
}
elseif ($sort=='open')
{
$qsort = "visits";
}
else
{
$sort = 'visits';
$qsort = "visits";
}
}
else
{
$sort = 'visits';
$qsort = "visits";
}
echo "<div class='p_t'>\n";
echo ($sort!='visits'?"<a href='?act=cat&id=$cat[id]&sort=visits'>":NULL)."Популярные".($sort!='visits'?"</a>":NULL)." | ".($sort!='new'?"<a href='?act=cat&id=$cat[id]&sort=new'>":NULL)."Новые".($sort!='new'?"</a>":NULL)." | ".($sort!='open'?"<a href='?act=cat&id=$cat[id]&sort=open'>":NULL)."Открытые".($sort!='open'?"</a>":NULL);
echo "</div>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id_cat` = '$cat[id]'".($sort=='open'?" AND `read_rule` = '1'":NULL).""),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
$q=mysql_query("SELECT * FROM `comm` WHERE `id_cat` = '$cat[id]'".($sort=='open'?" AND `read_rule` = '1'":NULL)." ORDER BY `$qsort` DESC LIMIT $start, $set[p_str]");
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет сообществ\n";
echo "</td>\n";
echo "</tr>\n";
}
while($post=mysql_fetch_array($q))
{
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/comm.png' />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<img src='/comm/img/comm_".($post['read_rule']==1?"open":"closed").".png' /> \n";
echo "<a href='?act=comm&id=$post[id]'>".htmlspecialchars($post['name'])."</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$post[id]' AND `invite` = '0' AND `activate` = '1'"),0).")<br/>".($post['desc']!=NULL?output_text($post['desc']).'<br/>':NULL);
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=cat&id=$cat[id]&sort=$sort&",$k_page,$page); // Вывод страниц

if(isset($user))echo "<div class='foot'><img src='/comm/img/add.png'/> <a href='?act=add_comm&id=$cat[id]'>Создать сообщество</a><br/></div>\n";
echo "<div class='foot'>&raquo; <a href='/comm/'>Категории</a><br/></div>";
}
else {header("Location: ?");exit();}
?>