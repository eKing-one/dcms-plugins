<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')$skp = NULL; else $skp = " `sk` = '0' AND";

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Форум'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)!=0)
{
echo "<div class='menu'>Вы находитесь в Черном списке сообщества!</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}

if ($comm['forum']==0)
{
echo "Форум сообщества <b>".htmlspecialchars($comm['name'])."</b> закрыт\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if ($comm['read_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
echo "Это форум сообщества <b>".htmlspecialchars($comm['name']).".</b><br />
Форум доступен только участникам данного сообщества.<br />
<a href='/comm/?act=comm&id=$comm[id]&in'>Вступить в сообщество</a>";
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['search']))
{
$qsearch=NULL;
if (isset($_SESSION['qsearchf']))$qsearch=$_SESSION['qsearchf'];
if (isset($_POST['qsearch']))$qsearch=$_POST['qsearch'];
$_SESSION['qsearchf']=$qsearch;

$qsearch=preg_replace("#( ){2,}#"," ",$qsearch);
$qsearch=preg_replace("#^( ){1,}|( ){1,}$#","",$qsearch);
$q_search=str_replace('%','',$qsearch);
$q_search=str_replace(' ','%',$q_search);
?>
<div class='menu'>
	Поиск в форуме
</div>
<?
if ($qsearch!=NULL)
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND (`name` like '%".mysql_escape_string($q_search)."%' OR `msg` like '%".mysql_escape_string($q_search)."%')"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if (!$k_post)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Ничего не найдено\n";
echo "</td>\n";
echo "</tr>\n";
}
$q = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND (`name` like '%".mysql_escape_string($q_search)."%' OR `msg` like '%".mysql_escape_string($q_search)."%') ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$creator=get_user($post['id_user']);
$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$post[id]'"),0);
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/topic".($post['pos']>0?"_up":NULL).".png' />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=forum&id=$comm[id]&cat_show=$post[id_cat]&topic_show=$post[id]'>".htmlspecialchars($post['name'])."</a> ($count_komm)<br />\n";
echo "<a href='/info.php?id=$creator[id]'>$creator[nick]</a> (".vremja($post['time']).")";
if ($count_komm > 0)
{
$last_komm = mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$post[id]' ORDER BY `time` DESC LIMIT 1"));
$creator_last_komm = get_user($last_komm['id_user']);
echo "/<a href='/info.php?id=$creator_last_komm[id]'>$creator_last_komm[nick]</a> (".vremja($last_komm['time']).")\n";
}
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=forum&id=$comm[id]&search=1&",$k_page,$page); // Вывод страниц
}
echo "<form method='POST' class='foot' action='?act=forum&id=$comm[id]&search=1'><input type='text' placeholder='Введите пару слов для поиска...' name='qsearch' value='".input_value_text($qsearch)."' style='width: 80%' /> <input type='submit' value='Поиск' /></form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['cat_show']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['cat_show'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'"),0)!=0)
{
$fcat=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['cat_show'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'");
$fcat=mysql_fetch_array($fcat);

if (isset($_GET['topic_show']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['topic_show'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'"),0)!=0)
{
$topic=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['topic_show'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'");
$topic=mysql_fetch_array($topic);
$creator=get_user($topic['id_user']);
$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]'"),0);
if ($count_komm > 0)
{
$last_komm = mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' ORDER BY `time` DESC LIMIT 1"));
$creator_last_komm = get_user($last_komm['id_user']);
}
if(isset($_GET['mdelete']) && ($ank['id']==$user['id'] || $uinc['access']=='adm'))$mdelete=1;

if(isset($mdelete) && isset($_POST['m_d_okey']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^mdelelte_komm_([0-9]*)$#',$key,$kid) && $value='1')
{
if (mysql_result(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$kid[1]' LIMIT 1"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$kid[1]' LIMIT 1"));
mysql_query("DELETE FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$komm[id]'");
}
}
}
}

if(isset($mdelete) && isset($_POST['m_sk_okey']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^mdelelte_komm_([0-9]*)$#',$key,$kid) && $value='1')
{
if (mysql_result(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$kid[1]' LIMIT 1"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$kid[1]' LIMIT 1"));
mysql_query("UPDATE `comm_forum_komm` SET `sk` = '".($komm['sk']==0?1:0)."', `sk_user` = '$user[id]' WHERE `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$komm[id]'");
}
}
}
}
if(isset($_GET['reply']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '".intval($_GET['reply'])."'"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '".intval($_GET['reply'])."'"));
$ank2=get_user($komm['id_user']);
echo "<table class='post'>\n";
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo avatar($ank2['id']);
if ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user' && $ank2['id']!=$user['id'])
{
echo "<br />\n";
echo "<center><a href='?act=comm_users_ban&id=$comm[id]&add=$ank2[id]&type=chat&object=$komm[id]'>Бан</a></center>\n";
}
echo "</td>\n";
echo "<td class='p_t'>";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank['id']);
echo "<br />\n";
echo output_text($komm['msg'])."\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
if (isset($user))
{
if ($comm['write_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
echo "<div class='menu'>Чтобы писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>, Вам нужно быть участником данного сообщества.<br />
<a href='/comm/?act=comm&id=$comm[id]&in'>Вступить в сообщество</a></div>";
}
else
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' AND `type` = 'forum' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
if ($topic['locked']==0 || $topic['locked']==1 && ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user'))
{
echo "<form method='post' name='message' action='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите свой ответ...'></textarea><br />\n";
echo "<input type='hidden' name='reply' value='$ank2[id]'>";
echo "<input type='hidden' name='komm_reply' value='$komm[id]'>";
echo "<br/><input value=\"Отправить\" type=\"submit\" /> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>Назад</a>\n";
echo "</form>\n";
}
else echo "<div class='menu'>Тема закрыта для обсуждения</div>\n";
}
}
}
include_once '../sys/inc/tfoot.php';
exit;
}

if(isset($_GET['edit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '".intval($_GET['edit'])."'"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '".intval($_GET['edit'])."'"));
$ank2=get_user($komm['id_user']);
if(isset($user) && ($user['id']==$ank2['id'] && $komm['time']>time()-600))
{

if(isset($_POST['msg']))
{
$msg=$_POST['msg'];

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($msg)<1){$err[]='Короткое сообщение';}
if(!isset($err))
{
mysql_query("UPDATE `comm_forum_komm` SET `msg` = '".my_esc($msg)."' WHERE `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id` = '$komm[id]'");
header("Location: ?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]");
}
}

err();
echo "<form method='post' name='message' action='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&edit=$komm[id]'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите комментарий...'>".input_value_text($komm['msg'])."</textarea><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" /> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>Назад</a>\n";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit;
}
}

if (isset($_POST['msg']) && isset($user) && ($comm['write_rule']==1 || $comm['write_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum'"),0)==0 && ($topic['locked']==0 || $topic['locked']==1 && ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user')))
{
$msg=$_POST['msg'];
if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($msg)<1){$err[]='Короткое сообщение';}
if ($creator_last_komm['id']==$user['id'] && my_esc($msg)==$last_komm['msg']){$err[]='Ваше сообщение повторяет предыдущее';}
if(!isset($err)){
if(isset($_POST['reply']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_POST['reply'])."'"),0)!=0)
{
$reply_user=get_user(intval($_POST['reply']));
$komm_reply=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id_user` = '$reply_user[id]' AND `id` = '".intval($_POST['komm_reply'])."'"));
$reply=1;
}
$q3=NULL;$qq=mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]'");while($ppost=mysql_fetch_array($qq)){$a=get_user($ppost['id_user']);if($a){$array=explode(";", $q3);foreach ($array as $key => $value){if($value==$a['id'])$g=1;}if(!isset($g))$q3="".($q3!=NULL?"$q3;":null)."$a[id]";if(isset($g))unset($g);}}
$array=explode(";", $q3);foreach ($array as $key => $value){
$a=get_user($value);
if($value!=NULL && $a)
{
$k=mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' AND `id_user` = '$a[id]' ORDER BY `id` DESC LIMIT 1"));
if($a['id']!=$ank['id'] && $user['id']!=$a['id'])
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `msg` = '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$a[id]'"),0)==0)mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$a[id]', '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");else mysql_query("UPDATE `jurnal SET `time` = '$time' WHERE `msg` = '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$a[id]'");
}
}
}
if ($ank['id']!=$user['id'])if(mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `msg` = '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$ank[id]'"),0)==0)mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");else mysql_query("UPDATE `jurnal SET `time` = '$time' WHERE `msg` = '$user[nick] оставил [url=/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]]комментарий в этой теме[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$ank[id]'");

mysql_query("INSERT INTO `comm_forum_komm` (`id_comm`, `id_user`, `id_topic`, `time`, `msg`".(isset($reply)?", `id_reply`, `reply_msg`":null).") values('$comm[id]', '$user[id]', '$topic[id]', '$time', '".my_esc($msg)."'".(isset($reply)?", '$reply_user[id]', '$komm_reply[msg]'":null).")");
header("Location: ?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]");

}
}

if (($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user') && isset($_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' LIMIT 1"),0)!=0)
{
mysql_query("DELETE FROM `comm_forum_komm` WHERE$skp `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' LIMIT 1");
header("Location: ?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]");
}

echo "<div class='title' style='text-align: left;'>\n";
echo "<a href='/info.php?id=$creator[id]'>$creator[nick]</a> (".vremja($topic['time']).")\n";
echo "</div>\n";
echo "<div class='p_m'>\n";
echo "<b>".htmlspecialchars($topic['name'])."</b>\n";
if (isset($user) && ($ank['id']==$user['id'] || $uinc && $uinc['access']!='user' || $user['id']==$creator['id'] && $topic['time']>time()-600))echo "<span style='float: right;'><a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&moderate=edit_topic&topic=$topic[id]'><img src='/comm/img/edit.png'/></a>".($ank['id']==$user['id']  || $uinc['access']=='adm'?" <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&moderate=delete_topic&topic=$topic[id]'><img src='/comm/img/delete.png'/></a>":NULL)."</span>\n";
echo "<br />\n";
echo output_text($topic['msg']);
echo "</div>\n";
echo "<div class='menu'>\n";
if (isset($_GET['moderate']) && $_GET['moderate']=='topic_replace' && ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'))
{
if(isset($_POST['submited']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_POST['cat'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'"),0)!=0)
{
if (intval($_POST['cat'])!=$fcat['id'])mysql_query("INSERT INTO `comm_forum_komm` (`id_comm`, `id_user`, `id_topic`, `time`, `msg`) values('$comm[id]', '0', '$topic[id]', '$time', 'А вот и я! Тему сюда переместил модератор $user[nick] из раздела [b]".htmlspecialchars($fcat['name'])."[/b].')");
mysql_query("UPDATE `comm_forum` SET `id_cat` = '".intval($_POST['cat'])."' WHERE `id` = '$topic[id]' AND `type` = 'topic' AND `id_comm` = '$comm[id]'");
header("Location: ?act=forum&id=$comm[id]&cat_show=".intval($_POST['cat'])."&topic_show=$topic[id]");
exit();
}
else $err[]="Какегория не найдена";
}
err();

echo "<form method='POST' class='menu'>\n";
echo "Выберите раздел:<br />";
echo "<select name='cat'>\n";
$q = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' ORDER BY `pos` ASC");
while ($post = mysql_fetch_array($q))
{
echo "<option value='$post[id]'".($post['id']==$fcat['id']?" selected='selected'":NULL).">".htmlspecialchars($post['name'])."</option>\n";
}
echo "</select><br />\n";
echo "<input type='submit' name='submited' value='Переместить' /> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>Отмена</a><br />\n";
echo "</form>\n";
}
else echo "<img src='/comm/img/forum.png' /> Раздел: ".htmlspecialchars($fcat['name'])." ".($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'?" <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&moderate=topic_replace'>[изменить]</a>":NULL)."<br />";
echo "<img src='/comm/img/message.png' /> Комментариев: $count_komm\n";
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
  <script>
$(document).ready(function(){
	$("input[name='check_all']").click( function() {
		if($(this).is(':checked')){
			$("input[name^='mdelelte_komm']").each(function() { $(this).attr('checked', true); });
		} else {
			$("input[name^='mdelelte_komm']").each(function() { $(this).attr('checked', false); });
		}

	});
});
  </script>
<?
if(isset($mdelete))echo "<br />\n<input type='checkbox' name='check_all' value='1'> Отметить все\n";
echo "</div>\n";

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if(isset($mdelete))
{
echo "<form method='post'>\n";
}
if (!$k_post)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет комментариев\n";
echo "</td>\n";
echo "</tr>\n";
}
?>
<script>
	function toggle(id) {
		var quote = document.getElementById('quote-' + id);
		var state = quote.style.display;
			if(state == 'none') {
				quote.style.display = 'block';
			} else {
				quote.style.display = 'none';
			}
	}
</script>
<?
$q = mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
if(isset($mdelete))echo "<br />\n<center><input type='checkbox' name='mdelelte_komm_$post[id]' value='1'></center>\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>".online($ank2['id']);
echo " (".vremja($post['time']).")\n";
if ($ank2['id']==$creator['id'])echo "<span style='float: right;'>Автор</span>\n";
echo "<br />\n";
if($post['id_reply']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[id_reply]'"),0))echo "<div id='quote-$post[id]' style='display:none; margin:0; margin-bottom:7px; background-color: #EAEEF4; border: 1px solid #999; color: #666; padding: 6px 5px; -webkit-border-radius: 4px; border-radius: 4px;'>".output_text($post['reply_msg'])."</div>\n";
if($post['sk']==1 && $post['sk_user']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[sk_user]'"),0))
{
$sku=get_user($post['sk_user']);
echo "<font color='red'>Скрыл".($sku['pol']==0?'a':null)." $sku[nick]</font><br/>";
}
if($post['id_reply']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[id_reply]'"),0))
{
$ru=get_user($post['id_reply']);
echo "<a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]' onclick='javascript:toggle(\"$post[id]\"); return false;'>$ru[nick]</a>, ";
}
echo output_text($post['msg']);
echo "<br />\n";
if ($ank2['id']!=0)echo "<a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&reply=$post[id]'>Ответить</a>\n";
?>
<span style='float:right'>
<?
if ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user')echo " <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&delete=$post[id]'>Удалить</a>\n";
if(isset($user) && $user['id']==$ank2['id'] && $post['time']>time()-600)
{
echo ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user'?$rk:NULL)."<a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&edit=$post[id]' style='color:green;'>Ред</a>\n";
}
?>
</span>
<?
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if(isset($mdelete))echo "Выбранные: <input type='submit' name='m_d_okey' value='Удалить'> <input type='submit' name='m_sk_okey' value='Скрыть/Показать'> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&page=$page'>Отмена</a>\n\n</form>\n";
if ($k_page>1)str("?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]".(isset($mdelete)?"&mdelete=1":null)."&",$k_page,$page); // Вывод страниц
err();
if (isset($user))
{
if ($comm['write_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
echo "<div class='menu'>Чтобы писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>, Вам нужно быть участником данного сообщества.<br />
<a href='/comm/?act=comm&id=$comm[id]&in'>Вступить в сообщество</a></div>";
}
else
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' AND `type` = 'forum' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
if ($topic['locked']==0 || $topic['locked']==1 && ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user'))
{
echo "<form method='POST'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите комментарий...'></textarea><br />\n";
echo "<input type='submit' name='submited' value='Добавить' />\n";
echo "</form>\n";
}
else echo "<div class='menu'>Тема закрыта для обсуждения</div>\n";
}
}
}
else echo "<div class='menu'><img src='/comm/img/add.png' /> <a href='/aut.php'>Добавить комментарий</a></div>\n";
if($ank['id']==$user['id'] || $uinc['access']=='adm')echo "<div class='foot'><img src='/comm/img/move.png' /> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]&page=$page&mdelete=start'>Выбрать комментарии</a><br /></div>\n";
echo "<div class='foot'>\n";
echo "&raquo; <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]'>Список тем</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}

if (isset($_GET['moderate']) && $_GET['moderate']=='delete_topic')
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['topic'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'"),0)!=0)
{
if (isset($user) && ($ank['id']==$user['id'] || $uinc['access']=='adm'))
{
$topic=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['topic'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'");
$topic=mysql_fetch_array($topic);
if(isset($_POST['submited']))
{
mysql_query("DELETE FROM `comm_forum` WHERE `id` = '$topic[id]' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'");
mysql_query("DELETE FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$topic[id]' LIMIT 1");
header("Location:/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]");
exit;
}
echo "<form method='POST'>\n";
echo "Подтвердите удаление темы<br/>\n";
echo "<input type='submit' name='submited' value='Удалить'> <a href='/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>Отмена</a>\n";
echo "</form>\n";
}
else echo "<div class='menu'>У Вас нет прав для удаления тем в данном сообществе</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}

if (isset($_GET['moderate']) && $_GET['moderate']=='edit_topic')
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['topic'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'"),0)!=0)
{
$topic=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['topic'])."' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'");
$topic=mysql_fetch_array($topic);
$creator = get_user($topic['id_user']);
if (isset($user) && ($ank['id']==$user['id'] || $uinc && $uinc['access']!='user' || $user['id']==$creator['id'] && $topic['time']>time()-600))
{
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['msg']))
{
$name=$_POST['name'];
$msg=$_POST['msg'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `name` = '$name' AND `id` != '$topic[id]' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'"),0)!=0)$err[]="Тема с таким названием уже есть в этом разделе";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>1000 || strlen2($name)<3)$err[]="Сообщение должно быть не меньше 3-х и не больше 10000 символов";
$name=my_esc($name);
$msg=my_esc($msg);
if ($ank['id']==$user['id'] || $uinc && $uinc['access']!='user')
{
$pos=intval($_POST['pos']);
if (in_array($pos, array(0,1,2,3,4,5,6,7,8,9,10)))$pos = $pos; else $pos = 0;
if (isset($_POST['locked']) && $_POST['locked']==1)$locked=1; else $locked=0;
if ($locked!=$topic['locked'])
{
$locked_user = $user['id'];
$locked_time = $time;
}
}
else
{
$locked_user = $topic['locked_user'];
$locked_time = $topic['locked_time'];
$pos = $topic['pos'];
$locked = $topic['locked'];
}
if (!isset($err))
{
if ($locked!=$topic['locked'])mysql_query("INSERT INTO `comm_forum_komm` (`id_comm`, `id_user`, `id_topic`, `time`, `msg`) values('$comm[id]', '0', '$topic[id]', '$time', 'А вот и я! Тему ".($locked==1?"закрыл":"открыл")." модератор $user[nick].')");
mysql_query("UPDATE `comm_forum` SET `name` = '$name', `msg` = '$msg', `locked` = '$locked', `locked_user` = '$locked_user', `locked_time` = '$locked_time', `last_user` = '$user[id]', `last_time` = '$time', `pos` = '$pos' WHERE `id` = '$topic[id]' AND `type` = 'topic' AND `id_comm` = '$comm[id]' AND `id_cat` = '$fcat[id]'");
header("Location:/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]");
exit;
}
}
err();

echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value='".input_value_text($topic['name'])."'><br/>\n";
echo "Сообщение:<br/>\n";
echo "<textarea name='msg'>".input_value_text($topic['msg'])."</textarea><br/>\n";
if ($ank['id']==$user['id'] || $uinc && $uinc['access']!='user')
{
echo "Уровень (0-10): \n";
echo "<input style='width: 5%' type='text' name='pos' value='$topic[pos]' /><br />\n";
echo "<input type='checkbox' name='locked' value='1'".($topic['locked']==1?" checked='checked'":NULL)." /> Закрыть<br />\n";
}
echo "<input type='submit' name='submited' value='Сохранить'> <a href='/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$topic[id]'>Назад</a>\n";
echo "</form>\n";
}
else echo "<div class='menu'>У Вас нет прав для редактирования тем в данном сообществе</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}

if (isset($_GET['moderate']) && $_GET['moderate']=='add_topic')
{
if (isset($user))
{
if ($comm['write_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
echo "<div class='menu'>Чтобы писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>, Вам нужно быть участником данного сообщества.<br />
<a href='/comm/?act=comm&id=$comm[id]&in'>Вступить в сообщество</a></div>";
include_once '../sys/inc/tfoot.php';
exit();
}
if ((!$uinc || $uinc['access']=='user') && $user['time_comm_topic']>$time)
{
echo "<div class='menu'>Можно создавать только одну тему в 10 минут</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['msg']))
{
$name=$_POST['name'];
$msg=$_POST['msg'];
//if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `name` = '$name' AND `id_cat` = '$fcat[id]'"),0)!=0)$err[]="Такой раздел уже есть";
	if(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($msg)>10000)$err[]="Сообщение должно быть не больше 10000 символов";
$name=my_esc($name);
$msg=my_esc($msg);
if (!isset($err))
{
if (!$uinc || $uinc['access']=='user')mysql_query("UPDATE `user` SET `time_comm_topic` = '".($time+600)."' WHERE `id` = '$user[id]'");
mysql_query("INSERT INTO `comm_forum` (`id_comm`, `id_user`, `id_cat`, `type`, `name`, `msg`, `time`) VALUES ('$comm[id]', '$user[id]', '$fcat[id]', 'topic', '$name', '$msg', '$time')");
header("Location:/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=".mysql_insert_id());
exit;
}
}
err();
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'forum' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' AND `type` = 'forum' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете писать в форуме сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value=''><br/>\n";
echo "Сообщение:<br/>\n";
echo "<textarea name='msg'></textarea><br/>\n";
echo "<input type='submit' name='submited' value='Создать'> <a href='/comm/?act=forum&id=$comm[id]&cat_show=$fcat[id]'>Назад</a>\n";
echo "</form>\n";
}
include_once '../sys/inc/tfoot.php';
exit();
}
}
?>
<div class='menu'>
	Раздел: <?php echo htmlspecialchars($fcat['name']);?>
</div>
<?
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `id_cat` = '$fcat[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if (!$k_post)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет тем\n";
echo "</td>\n";
echo "</tr>\n";
}
$q = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `id_cat` = '$fcat[id]' ORDER BY `pos` DESC, `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$creator=get_user($post['id_user']);
$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$post[id]'"),0);
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/topic".($post['pos']>0?"_up":NULL).".png' />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&topic_show=$post[id]'>".htmlspecialchars($post['name'])."</a> ($count_komm)<br />\n";
echo "<a href='/info.php?id=$creator[id]'>$creator[nick]</a> (".vremja($post['time']).")";
if ($count_komm > 0)
{
$last_komm = mysql_fetch_array(mysql_query("SELECT * FROM `comm_forum_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_topic` = '$post[id]' ORDER BY `time` DESC LIMIT 1"));
$creator_last_komm = get_user($last_komm['id_user']);
echo "/<a href='/info.php?id=$creator_last_komm[id]'>$creator_last_komm[nick]</a> (".vremja($last_komm['time']).")\n";
}
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=forum&id=$comm[id]&cat_show=$fcat[id]&",$k_page,$page); // Вывод страниц
if (isset($user) && ($comm['write_rule']==1 || $comm['write_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)!=0))
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/add.png' /> <a href='?act=forum&id=$comm[id]&cat_show=$fcat[id]&moderate=add_topic'>Создать тему</a><br />\n";
echo "</div>\n";
}
echo "<div class='foot'>\n";
echo "&raquo; <a href='?act=forum&id=$comm[id]'>Список разделов</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}




if ($ank['id']==$user['id'] && isset($user))
{
if (isset($_GET['moderate']) && $_GET['moderate']=='delete_cat')
{
if($ank['id']==$user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['cat'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'"),0)!=0)
{
$fcat=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['cat'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'");
$fcat=mysql_fetch_array($fcat);
$count_topics = mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_cat` = '$fcat[id]' AND `type` = 'topic' AND `id_comm` = '$comm[id]'"),0);
if ($count_topics > 0)
{
echo "Вы не сможете удалить раздел, пока в нем находится хоть одна тема!\n";
}
else
{
if(isset($_POST['submited']))
{
mysql_query("DELETE FROM `comm_forum` WHERE `id` = '$fcat[id]' AND `type` = 'cat' AND `id_comm` = '$comm[id]'");
$q = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `id_cat` = '$fcat[id]'");
while ($post = mysql_fetch_array($q))
{
mysql_query("DELETE FROM `comm_forum` WHERE `id` = '$post[id]' AND `type` = 'topic'");
mysql_query("DELETE FROM `comm_forum_komm` WHERE `id_comm` = '$comm[id]' AND `id_topic` = '$post[id]' LIMIT 1");
}
header("Location:/comm/?act=forum&id=$comm[id]");
exit;
}
echo "<form method='POST'>\n";
echo "Подтвердите удаление раздела<br/>\n";
echo "<input type='submit' name='submited' value='Удалить'> <a href='/comm/?act=forum&id=$comm[id]'>Отмена</a>\n";
echo "</form>\n";
}
include_once '../sys/inc/tfoot.php';
exit();
}
}

if (isset($_GET['moderate']) && $_GET['moderate']=='edit_cat')
{
if($ank['id']==$user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id` = '".intval($_GET['cat'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'"),0)!=0)
{
$fcat=mysql_query("SELECT * FROM `comm_forum` WHERE `id` = '".intval($_GET['cat'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]'");
$fcat=mysql_fetch_array($fcat);
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `name` = '$name' AND `id` != '$fcat[id]' AND `type` = 'cat' AND `id_comm` = '$comm[id]'"),0)!=0)$err[]="Такая категория уже есть";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
$name=my_esc($name);
$desc=my_esc($desc);
if (!isset($err))
{
mysql_query("UPDATE `comm_forum` SET `name` = '$name', `desc` = '$desc' WHERE `id` = '$fcat[id]' AND `type` = 'cat' AND `id_comm` = '$comm[id]'");
header("Location:/comm/?act=forum&id=$comm[id]");
exit;
}
}
err();

echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value='".input_value_text($fcat['name'])."'><br/>\n";
echo "Описание:<br/>\n";
echo "<textarea name='desc'>".input_value_text($fcat['desc'])."</textarea><br/>\n";
echo "<input type='submit' name='submited' value='Сохранить'> <a href='/comm/?act=forum&id=$comm[id]'>Назад</a>\n";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}
if (isset($_GET['moderate']) && $_GET['moderate']=='add_cat')
{
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' AND `name` = '$name'"),0)!=0)$err[]="Такой раздел уже есть";
elseif(strlen2($name)>50 || strlen2($name)<3)$err[]="Название должно быть не меньше 3-х и не больше 50-ти символов";
elseif(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
$name=my_esc($name);
$desc=my_esc($desc);
if (!isset($err))
{
$pos=mysql_result(mysql_query("SELECT MAX(`pos`) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat'"),0)+1;
mysql_query("INSERT INTO `comm_forum` (`id_comm`, `type`, `name`, `desc`, `pos`) VALUES ('$comm[id]', 'cat', '$name', '$desc', '$pos')");
header("Location:/comm/?act=forum&id=$comm[id]");
exit;
}
}
err();
echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value=''><br/>\n";
echo "Описание:<br/>\n";
echo "<textarea name='desc'></textarea><br/>\n";
echo "<input type='submit' name='submited' value='Добавить'> <a href='/comm/?act=forum&id=$comm[id]'>Назад</a>\n";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['up']))
{
$up=mysql_fetch_assoc(mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' AND `id` = '".intval($_GET['up'])."' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' AND `pos` < '$up[pos]' LIMIT 1"),0)!=0)
{
mysql_query("UPDATE `comm_forum` SET `pos` = '".($up['pos'])."' WHERE `pos` = '".($up['pos']-1)."' AND `type` = 'cat' AND `id_comm` = '$comm[id]' LIMIT 1");
mysql_query("UPDATE `comm_forum` SET `pos` = '".($up['pos']-1)."' WHERE `id` = '".intval($_GET['up'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]' LIMIT 1");
}
}
elseif (isset($_GET['down']))
{
$down=mysql_fetch_assoc(mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' AND `id` = '".intval($_GET['down'])."' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' AND `pos` > '$down[pos]' LIMIT 1"),0)!=0)
{
mysql_query("UPDATE `comm_forum` SET `pos` = '".($down['pos'])."' WHERE `pos` = '".($down['pos']+1)."' AND `type` = 'cat' AND `id_comm` = '$comm[id]' LIMIT 1");
mysql_query("UPDATE `comm_forum` SET `pos` = '".($down['pos']+1)."' WHERE `id` = '".intval($_GET['down'])."' AND `type` = 'cat' AND `id_comm` = '$comm[id]' LIMIT 1");
}
}
}
?>
<!--

 качественные моды от Killer
 делаю моды любой сложности на DCMS 6, 7
 Благодарность:  R408800828608

 -->
<div class='menu'>
	<img src='/comm/img/search.png' /> <a href='?act=forum&id=<?php echo $comm['id'];?>&search=1'>Поиск по форуму</a><br>
</div>
<?
$q = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'cat' ORDER BY `pos` ASC");

echo "<table class='post'>\n";
if (!mysql_num_rows($q))
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет разделов\n";
echo "</td>\n";
echo "</tr>\n";
}
while ($post = mysql_fetch_array($q))
{
$count_topics=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `id_cat` = '$post[id]'"),0);
$count_topics_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `time` > '".($time-(3600*24))."' AND `id_cat` = '$post[id]'"),0);
$count_topics_show=$count_topics.($count_topics_new>0?"/+$count_topics_new":NULL);
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/comm/img/forum.png' />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=forum&id=$comm[id]&cat_show=$post[id]'>".htmlspecialchars($post['name'])."</a> ($count_topics_show)\n";
if ($ank['id']==$user['id'])
{
echo "<span style='float: right;'>\n";
if(isset($_GET['moderate']))echo "<a href='?act=forum&id=$comm[id]&moderate&up=$post[id]'><img src='/comm/img/up.png' alt='o'></a> <a href='?act=forum&id=$comm[id]&moderate&down=$post[id]'><img src='/comm/img/down.png' alt='o'></a>\n";
echo " <a href='?act=forum&id=$comm[id]&moderate=edit_cat&cat=$post[id]'><img src='/comm/img/edit.png'/></a> <a href='?act=forum&id=$comm[id]&moderate=delete_cat&cat=$post[id]'><img src='/comm/img/delete.png'/></a>\n";
echo "</span>\n";
}
echo ($post['desc']!=NULL?"<br/>\n".output_text($post['desc']).'<br/>':NULL);
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($ank['id']==$user['id'] && isset($user))
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/add.png' /> <a href='?act=forum&id=$comm[id]&moderate=add_cat'>Добавить раздел</a><br />\n";
echo "<img src='/chat/img/configure.png' /> ".(isset($_GET['moderate'])?"<a href='?act=forum&id=$comm[id]'>Отмена</a>":"<a href='?act=forum&id=$comm[id]&moderate'>Управление</a>")."<br />\n";
echo "</div>\n";
}
echo "<div class='foot'>\n";
echo "&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";
}
else{header("Location:/comm");exit;}
?>