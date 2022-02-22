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
only_reg('/reg.php');
$num=1;
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
if(isset($_GET['rules']))
{
$set['title']=$gruppy['name'].' - Правила сообщества'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div class="p_m">';
echo''.output_text($gruppy['rules']).'';
echo'</div>';
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
$set['title']=$gruppy['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
include_once 'inc/user_act.php';
if(!isset($user) || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$user[id]' AND `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' LIMIT 1"),0)==0 && $user['id']!=$gruppy['admid'])
{
if($gruppy['konf_gruppy']==0 || $gruppy['konf_gruppy']==1)echo'<img src="img/open.png" alt=""/> Открыто для вступления'; elseif($gruppy['konf_gruppy']==2)echo'<img src="img/money.png" alt=""/> Вступление платное'; elseif($gruppy['konf_gruppy']==3)echo'<img src="img/close.png" alt=""/> Закрыто для вступления';
if($gruppy['konf_gruppy']==0)echo'<br/><img src="img/open.png" alt=""/> Открыто для чтения'; else echo'<br/><img src="img/close.png" alt=""/> Закрыто для чтения';
}
echo'<table class="post">';
echo '<tr>';
echo'<td class="icon48" rowspan="2">';
if (is_file(H."gruppy/logo/$gruppy[id].gif"))
echo '<img src="logo/'.$gruppy['id'].'.gif" alt="" />';
elseif (is_file(H."gruppy/logo/$gruppy[id].jpg"))
echo '<img src="logo/'.$gruppy['id'].'.jpg" alt="" />';
elseif (is_file(H."gruppy/logo/$gruppy[id].png"))
echo '<img src="logo/'.$gruppy['id'].'.png" alt="" />';
else
echo '<img src="img/grup.png" alt="" />';
echo'</td>';
echo '<td class="p_m">';
echo'<img src="img/16od.png" alt=""/> <font color="#2DA100"><b><u>'.$gruppy['name'].'</b></u></font><br /><br />'; 
$adm=get_user($gruppy['admid']);
echo'<b><small>Администратор группы:</small></b>';
echo' '.online($adm['id']).' <a href="info.php?s='.$gruppy['id'].'&id='.$adm['id'].'"><span style="color:'.$adm['ncolor'].'"><font color="#2DA100" size="3"><b>'.$adm['nick'].'</b></font></span></a><br />';
echo '<small>Группа создана: <span style="color:#2DA100">['.vremja($gruppy['time']).']</span></small><br />';
echo'</td>';
echo'</tr>';
echo'</table>';
echo "<div class='st_2'>";
echo''.output_text($gruppy['desc']).'<br/>';
echo "</div>";
if($gruppy['conf_news']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_news` WHERE `id_gruppy` = '$gruppy[id]' LIMIT 1"),0)!=0)
{

if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid'])
{
$news=mysql_fetch_array(mysql_query("SELECT * FROM `gruppy_news` WHERE `id_gruppy` = '$gruppy[id]' ORDER BY `time` DESC LIMIT 1"));
echo '<div class="linechat"></div><div class="mess"><img src="/gruppy/img/news.png" alt="" /> <b>'.$news['name'].'</b> ('.vremja($news['time']).')<br />'.output_text($news['mess']).'</div> ';
}
}

$count_forum_t = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id_gruppy` = '$gruppy[id]'"),0);
$count_forum_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_gruppy` = '$gruppy[id]'"),0);
$count_news = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_news` WHERE `id_gruppy` = '$gruppy[id]'"),0);
$count_chat = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_chat` WHERE `id_gruppy` = '$gruppy[id]'"),0);
$count_obmen = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_gruppy` = '$gruppy[id]'"), 0);
$conf = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `time` > '".(time()-86400)."' AND `id_gruppy` = '$gruppy[id]'"), 0);
echo mysql_error();
if($conf==0){
$count_obmen_new=NULL;
}else{
$count_obmen_new='/+'.$conf.'';
}
$count_votes_open = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy` = '$gruppy[id]' AND `time_close`>'$time'"),0);
$count_votes_all = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy` = '$gruppy[id]'"),0);
$count_users = $gruppy['users']+1;
$count_banned = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `activate`='0' AND `invit`='0' AND `ban`>'$time'"),0);
$count_friends = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_friends` WHERE `id_gruppy` = '$gruppy[id]'"),0);
if($gruppy['rules']!=NULL)echo '<div class="p_m"><img src="img/rules.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&rules">Правила сообщества</a></div>';
echo "<div class='main_menu'>\n";
echo'<img src="img/rss.png" alt=""/> <a href="news.php?s='.$gruppy['id'].'">Новости</a> ('.$count_news.')<br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/14od.png" alt=""/> <a href="forum.php?s='.$gruppy['id'].'">Темы </a> ('.$count_forum_t.'/'.$count_forum_p.')<br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/4od.png" alt=""/> <a href="chat.php?s='.$gruppy['id'].'">Мини-чат</a> ('.$count_chat.')<br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/12od.png" alt=""/> <a href="obmen.php?s='.$gruppy['id'].'">Файлы </a> ('.$count_obmen.''.$count_obmen_new.')<br/>';
echo '<font color="#CC0000">&raquo;</font> (картинки, видео, аудио)';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/11od.png" alt=""/> <a href="votes.php?s='.$gruppy['id'].'">Голосования</a> ('.$count_votes_open.'/'.$count_votes_all.')<br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/18od.png" alt=""/> <a href="users.php?s='.$gruppy['id'].'">Участники </a> <font color="#669966">['.$count_users.']</font><br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/19od.png" alt=""/> <a href="banned.php?s='.$gruppy['id'].'">Черный список</a> <font color="#CC0000">['.$count_banned.']</font><br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/15od.png" alt=""/> <a href="friends.php?s='.$gruppy['id'].'">Наши друзья</a> ('.$count_friends.')<br/>';
echo "</div>\n";
echo "<div class='nav2'>\n";
echo'<img src="img/1od.png" alt=""/> <a href="moder.php?s='.$gruppy['id'].'">Модераторы</a><br/>';
echo "</div>\n";


if(isset($_GET['gogroup2']))
{
include 'gogroup.php';
}
if(isset($user))
{
echo "<div class='nav2'>\n";
echo '<font color="#009900"><b>Адрес группы: </b></font>';
echo $_SERVER['SERVER_NAME'].'/gruppy/'.$gruppy['id'].'<br/>';
echo "</div>\n";
echo "<div class='main_menu'>\n";
if($user['id']==$gruppy['admid'])echo'<img src="img/ank.png" alt=""/> <a href="admin.php?s='.$gruppy['id'].'">Изменить настройки</a><br/>';
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' LIMIT 1"),0)==0 && $user['id']!=$gruppy['admid'])
{
if($gruppy['konf_gruppy']==0 || $gruppy['konf_gruppy']==1 || $gruppy['konf_gruppy']==3)
{
echo'<img src="img/open.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&enter">Вступить в группу</a><br/>';
}
elseif($gruppy['konf_gruppy']==2 && $user['balls']>=$gruppy['plata'])
{
echo'<img src="img/open.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&enter">Вступить в группу</a>(<b>'.$gruppy['plata'].' баллов</b>)<br/>';
}
elseif($gruppy['konf_gruppy']==2 && $user['balls']<$gruppy['plata'])
{
echo'<img src="img/close.png" alt=""/> Для вступления необходимо <b>'.$gruppy['plata'].'</b> баллов';
}
}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='1' LIMIT 1"),0)==1)
{
echo'<img src="img/open.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&yes">Принять приглавшение</a><br/>';
echo'<img src="img/close.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&no">Отклонить приглашение</a><br/>';
}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])
{
echo'<img src="img/20od.png" alt=""/> <a href="users.php?s='.$gruppy['id'].'&invite">Пригласить в группу</a><br/>';
if($user['id']!=$gruppy['admid'])echo'<img src="img/13od.png" alt=""/> <a href="index.php?s='.$gruppy['id'].'&exit">Покинуть группу</a><br/>';
}
}
echo'</div>';
echo "<div class='foot'>\n";
echo'<img src="img/back.png" alt=""/> <a href="/gruppy/">Категории</a><img src="img/back.png" alt=""/><a href="/gruppy/?r='.$gruppy['id_cat'].'">'.mysql_result(mysql_query("SELECT `name` FROM `gruppy_cat` WHERE `id` = '$gruppy[id_cat]' LIMIT 1"),0).'</a><br/>';
echo "</div>\n";
}

}
elseif(isset($_GET['r']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_cat` WHERE `id` = '".intval($_GET['r'])."' LIMIT 1"),0)==1)
{
$r=intval($_GET['r']);
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_cat` WHERE `id` = '$r' LIMIT 1"));

if(isset($_GET['new']) && isset($user))
{
$set['title']='Группа - '.$razdel['name'].' - Новое сообщество'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
$limit = 30;
$time_create = 60*60*24*30;
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `admid` = '$user[id]' LIMIT 1"),0)>=$limit && $user['level']<3)
{
echo'Максимальное количество групп на одного человека <b>'.$limit.'</b>, у Вас уже столько имеется<br/>';
}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `admid` = '$user[id]' LIMIT 1"),0)!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `admid` = '$user[id]' LIMIT 1"),0)<$limit && $user['level']<3)
{
$last_soo = mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `admid` = '$user[id]' ORDER BY `time` DESC LIMIT 1"));
$time_new = $last_soo['time']+$time_create;
if($time<$time_new)
{
echo'<div class="err">Нельзя так часто создавать группы. Следующая возможность у Вас будет '.vremja($time_new).'</div>';
}
else
{
include_once 'inc/new_act.php';
include_once 'inc/new_form.php';
}
}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `admid` = '$user[id]' LIMIT 1"),0)==0 || $user['level']>=3)
{
include_once 'inc/new_act.php';
include_once 'inc/new_form.php';
}
err();
echo "<div class='foot'>\n";
echo'<img src="img/back.png" alt=""/> <a href="/gruppy/">Категории</a>/<a href="/gruppy/?r='.$r.'">'.mysql_result(mysql_query("SELECT `name` FROM `gruppy_cat` WHERE `id` = '$r' LIMIT 1"),0).'</a><br/>';
echo "</div>\n";
}
else
{
$set['title']='Группы - '.$razdel['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($_GET['sort']))
{
if($_GET['sort']=='users'){$sort='users'; $por='DESC';}
elseif($_GET['sort']=='open'){$sort='konf_gruppy'; $por='ASC';}
else{$sort='time'; $por='DESC';}
}
else
{
$sort='time'; $por='DESC';
}

if(isset($user) && $user['level']>3 && isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['del'])."' LIMIT 1"),0)==1)
{
$delid=intval($_GET['del']);
$del=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$delid' LIMIT 1"));
$deladm=get_user($del['admid']);
if($user['id']!=$deladm['id'] && $user['level']>$deladm['level'] || $user['id']==$deladm['id'])
{
if(isset($_GET['ok']))
{
mysql_query("DELETE FROM `gruppy_users` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_chat` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_news` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_bl` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_friends` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_friends` WHERE `id_friend`='$delid'");
mysql_query("DELETE FROM `gruppy_votes` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_votes_otvet` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_forums` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_forum_thems` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy` WHERE `id`='$delid'");
mysql_query("DELETE FROM `gruppy_obmen_dir` WHERE `id_gruppy`='$delid'");
$q=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_gruppy`='$gruppy[id]'");
while ($delete = mysql_fetch_assoc($q))
{
unlink(H.'sys/gruppy/obmen/files/'.$delete['id'].'.dat');
}
mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_obmen_komm` WHERE `id_gruppy`='$delid'");
msg('Группа успешно удалена');
}
else
{
echo'<div class="err">Вы уверены, что хотите удалить данную группу?<br/>';
echo'<a href="?r='.$r.'&del='.$delid.'&ok">Да</a> | <a href="?r='.$r.'">Нет</a></div>';
}
}
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id_cat`='$r'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

echo'<div class="mess">';
echo'Сортировать: ';
echo '<img src="img/bookmark_toolbar_5519.png" alt=""/> <a href="?r='.$r.'&sort=users&page='.$page.'">Популярные</a> | <img src="img/bookmark-new_4212.png" alt=""/> <a href="?r='.$r.'&sort=time&page='.$page.'">Новые</a> | <img src="img/lock_large_unlocked_9125.png" alt=""/> <a href="?r='.$r.'&sort=open&page='.$page.'">Открытые</a>';
echo "</div>\n";
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="err">';
echo 'Нет групп в данной категории';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy` WHERE `id_cat`='$r' ORDER BY $sort $por LIMIT $start, $set[p_str]");
while ($comm = mysql_fetch_assoc($q))
{
echo '<tr>';

echo "<div class='nav2'>\n";
if($comm['konf_gruppy']==0 || $comm['konf_gruppy']==1)echo'<img src="img/open.png" alt="open"/> '; elseif($comm['konf_gruppy']==2)echo'<img src="img/money.png" alt="money"/> '; else echo'<img src="img/close.png" alt="close"/> ';
echo '<a href="/gruppy/'.$comm['id'].'">'.$comm['name'].'</a>';
if($comm['ban']!=NULL && $comm['ban']>$time)echo'[BAN]';
$count = $comm['users']+1;
if(isset($_GET['sort']) && $_GET['sort']=='users')echo'(Участников: '.$count.')'; else echo ' ('.vremja($comm['time']).')';
echo "<br/>";
echo '<span class="ank_n">Описание: '.output_text($comm['desc']).'</span><br />';

$admid=get_user($comm['admid']);
if(isset($user) && $user['level']>=3 && $user['id']!=$comm['admid'] && $user['level']>$admid['level'])
{
echo'[<a href="ban.php?s='.$comm['id'].'">Нарушения</a>]';
}
if(isset($user) && $user['level']>3 && ($user['id']!=$comm['admid'] && $user['level']>$admid['level'] || $user['id']==$comm['admid']))
{
echo'[<a href="?r='.$r.'&del='.$comm['id'].'"><img src="img/deletu.png" alt=""/></a>]<br/>';
}

echo '</div>';
echo '</tr>';
}
echo '</table>';
echo "<div class='foot'>\n";
if ($k_page>1)str("?r=$r&sort=$sort&$por&",$k_page,$page); // Вывод страниц
if (isset($user))echo '<img src="img/20od.png" alt=""/> <a href="?r='.$r.'&new">Создать группу</a><br/>';
echo "</div >";
echo '<div class=foot><img src="img/back.png" alt=""/> <a href="/gruppy/">В категории групп</a></div><br/>';
}
}
else
{
$set['title']='Сообщества - Категории'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($user) && $user['level']>2)
{
include_once 'inc/admin_soo_act.php';
}
//echo '<table class="post">';

echo '<div class="mess">';

echo '<font color="#CC0000"><center><b>Категории Групп</b></center></font>';

echo "</div>";
$q=mysql_query("SELECT * FROM `gruppy_cat` ORDER BY `name` ASC");
if (mysql_num_rows($q)==0) {
echo '<tr>';
echo '<div class="err">';
echo 'Нет категорий';
echo '</div>';
echo '</tr>';
}
while ($cat = mysql_fetch_assoc($q))
{
$count = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id_cat`='$cat[id]'"),0);
echo '<tr>';

if($num==1){
echo "<div class='nav2'>\n";
$num=0;
}else{
echo "<div class='nav1'>\n";
$num=1;}
echo '<img src="img/21od.png" alt=""/> ';
echo' <a href="?r='.$cat['id'].'">'.$cat['name'].'</a> ('.$count.')';
echo '<br/>';
if ($cat['desc']!=NULL)
{
echo ''.output_text($cat['desc']).'<br />';
}

if(isset($user) && $user['level']>2)
{
echo' [<a href="?edit='.$cat['id'].'"><img src="img/reply.png" alt=""/></a>][<a href="?del='.$cat['id'].'"><img src="img/action_delete.png" alt=""/></a>]<br/>';
}
echo '</div>';

echo '</tr>';
}
echo '<div class="foot">';
echo '<img src="img/13od.png" alt="" class="icon"/> <a href="top.php">Популярные группы</a><br/>';
echo "</div>\n";

$gruppy = mysql_num_rows(mysql_query("SELECT `id` FROM `gruppy` WHERE `admid` = '$user[id]'"));
echo '<div class="foot">';
{
echo '<img src="img/13od.png" alt="" class="icon"/> <a href="user.php">Мои группы</a> ('.$gruppy.')<br/>';
}
echo "</div>\n";
//echo '</table>';
if(isset($user) && $user['level']>2)
{

include_once 'inc/admin_soo_form.php';

}
err();
}
include_once '../sys/inc/tfoot.php';
?>
