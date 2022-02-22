<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Учасники'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_GET['user']) && $ank['id']==$user['id'] && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0' AND `id` = '".intval($_GET['user'])."'"),0)!=0)
{

$post=mysql_query("SELECT * FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0' AND `id` = '".intval($_GET['user'])."'");
$post=mysql_fetch_array($post);
$as=$post['access'];
$ank2=get_user($post['id_user']);
if($ank2['id']!=$ank['id'])
{
if(isset($_GET['delete']))
{
if(isset($_GET['ok']))
{
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `type` = 'out_comm', `time` = '$time'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank2[id]', ', '$user[nick] исключил Вас из сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");
mysql_query("DELETE FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]' AND `activate` = '1'");
header("Location:?act=comm_users&id=$comm[id]");
exit();
}
echo "Вы действительно хотите удалить обитателя <a href='/info.php?id=$ank2[id]'><span style='color:#79358c'><b>$ank2[nick]</b></span></a> из сообщества \"".htmlspecialchars($comm['name'])."\"?<br/><a href='?act=comm_users&id=$comm[id]'>Нет</a>&nbsp;&nbsp;&nbsp;<a href='?act=comm_users&id=$comm[id]&user=$post[id]&delete=1&ok'>Да</a>".(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank[id]'"),0)==0?"&nbsp;&nbsp;&nbsp;<a href='?act=blacklist&id=$comm[id]&add=$post[id]'>В черный список</a>":NULL);
include_once '../sys/inc/tfoot.php';
exit();
}
if(isset($_POST['submited']))
{
$post['access']=htmlspecialchars($_POST['access']);
if($post['access']=='delete')
{
header("Location:?act=comm_users&id=$comm[id]&user=$post[id]&delete=1");
exit();
}
elseif($post['access']=='creator')
{
$ank2=get_user($post['id_user']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id_user` = '$ank2[id]'"),0)>0)
{
$err[]="Даный пользователь имеет уже сообщество";
err();
include_once '../sys/inc/tfoot.php';
exit;
}
$new_time=time()+10800;
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_readmin` WHERE `id_comm` = '$comm[id]' LIMIT 1"),0)==0)
{
mysql_query("INSERT INTO `comm_readmin` (`id_comm`, `id_user`, `time`) values ('$comm[id]', '$ank2[id]', '$new_time')");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank2[id]', '$user[nick] предлогает стать создателем сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url]. Вы принимаете предложение? [url=/comm/?act=readmin&id=$comm[id]&yes]Да[/url] [url=/comm/?act=readmin&id=$comm[id]&no]Нет[/url]', '$time')");
msg("Обитателю $ank2[nick] отправлено предложение стать создателем сообщества \"".htmlspecialchars($comm['name'])."\". Предложение действительно в течении 3-х часов");
}
else $err[]="Вы уже отправили предложение ранее. Дождитесь пока ваше предложение рассмотрят";
}
else
{
if($as!=$post['access'])
{
if ($as=='mod')$las='модератора';else $las='администратора';
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `id_ank` = '$user[id]', `type` = 'access', `time` = '$time', `access` = '$post[access]'");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '".$ank2['id']."', '".$user['nick']." ".($post['access']!='user'?"назначил Вас ".($post['access']=='mod'?"модератором":"администратором"):"снял Вас с должности ".$las."")." сообщества [url=/comm/?act=comm&id=".$comm['id']."]".htmlspecialchars($comm['name'])."[/url].', '".$time."')");
mysql_query("UPDATE `comm_users` SET `access` = '$post[access]' WHERE `id` = '$post[id]'");
}
msg("Изменения сохранены");
}
}
err();
echo "<form method='POST'>\n";
echo "<input type='radio' name='access' value='user'".($post['access']=='user'?" checked='checked'":NULL)."/>Обычный участник<br/>\n";
echo "<input type='radio' name='access' value='mod'".($post['access']=='mod'?" checked='checked'":NULL)." />Модератор<br/>\n";
echo "<input type='radio' name='access' value='adm'".($post['access']=='adm'?" checked='checked'":NULL)." />Администратор<br/>\n";
echo "<input type='radio' name='access' value='creator'".($post['access']=='creator'?" checked='checked'":NULL)." />Создатель<br/>\n";
echo "<input type='radio' name='access' value='delete' /><span style='color:red'>Удалить</span><br/>\n";
echo "<input type='submit' name='submited' value='Сохранить'/> <a href='?act=comm_users&id=$comm[id]'>Назад</a>\n";
echo "</form>\n";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}
$sort=1;
$qsort=NULL;
if(isset($_GET['sort']) && $_GET['sort']==2)
{
$sort=2;
$qsort=" AND `access` != 'user'";
}
echo ($sort==1?"<a href='?act=comm_users&id=$comm[id]&sort=2'>":NULL)."Руководство".($sort==1?"</a>":NULL)." | ".($sort==2?"<a href='?act=comm_users&id=$comm[id]&sort=1'>":NULL)."Все".($sort==2?"</a>":NULL);
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'$qsort"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет учасников\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'$qsort LIMIT $start, $set[p_str]");

while($post=mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48' rowspan='1'>\n";
avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>".online($ank2['id']);
echo " [".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$ank2[id]'"),0)."]";
$aname=NULL;
if($post['access']=='creator')$aname='создатель';
elseif($post['access']=='adm')$aname='администратор';
elseif($post['access']=='mod')$aname='модератор';
if($aname!=NULL)echo " ($aname)";
echo ($ank['id']==$user['id'] && isset($user) && $ank2['id']!=$user['id']?"<span style='float:right'><a href='?act=comm_users&id=$comm[id]&user=$post[id]'><img src='/comm/img/edit.png'/></a> <a href='?act=comm_users&id=$comm[id]&user=$post[id]&delete=1'><img src='/comm/img/delete.png'/></a></span>":NULL);
echo "<br/>Вступил".($ank2['pol']==0?'a':NULL)." ".vremja($post['time']);
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=comm_users&id=$comm[id]&sort=$sort&",$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
?>