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
$set['title']=$gruppy['name'].' - Забаненные'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
{
if(isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
if(isset($_GET['ban']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '".intval($_GET['ban'])."' AND `activate`='0' AND `invit`='0' LIMIT 1"),0)==1 && $_GET['ban']!=$user['id'])
{
if(isset($_POST['ban_time']) && is_numeric($_POST['ban_time']) && isset($_POST['ban_type']) && ($_POST['ban_type']==0 || $_POST['ban_type']==1) && isset($_POST['prich']) && $_POST['prich']!=NULL)
{
$ban_time = intval($_POST['ban_time']);
$ban_type = intval($_POST['ban_type']);
if($ban_type==0)$time_ban=$time+60*60*$ban_time; else $time_ban=$time+60*60*24*$ban_time;
$prich=$_POST['prich'];
if (isset($_POST['translit']) && $_POST['translit']==1)$prich=translit($prich);

$mat=antimat($prich);
if ($mat)$err[]='В тексте причины обнаружен мат: '.$mat;

if (strlen2($prich)>1024){$err[]='Причина слишком длинная';}
elseif (strlen2($prich)<2){$err[]='Короткая причина';}
if(!isset($err))
{
mysql_query("UPDATE `gruppy_users` SET `ban`='$time_ban' WHERE `id_user`='".intval($_GET['ban'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy_users` SET `prich`='".my_esc($prich)."' WHERE `id_user`='".intval($_GET['ban'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
msg('Юзер успешно забанен');
}
}
else
{
$ban=intval($_GET['ban']);
$us=get_user($ban);
echo'Забанить юзера '.$us['nick'].'<br/>';
echo'<form method="post" action="?s='.$gruppy['id'].'&ban='.$ban.'">';
echo'На срок<br/>';
echo'<input type="text" name="ban_time" size="3">';
echo'<select name="ban_type">';
echo'<option value="1">Дней</option>';
echo'<option value="0">Часов</option>';
echo'</select><br/>';
echo'Причина (обязательно)<br/>';
echo'<textarea name="prich"></textarea><br/>';
if ($user['set_translit']==1)echo '<label><input type="checkbox" name="translit" value="1"/> Транслит</label><br />';
echo'<input type="submit" value="Отправить"></form><br/>';
}
}
err();
if(isset($_GET['del']) && $user['id']==$gruppy['admid'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '".intval($_GET['del'])."' AND `activate`='0' AND `invit`='0' AND `ban`>'$time' LIMIT 1"),0)==1)
{
mysql_query("UPDATE `gruppy_users` SET `ban`='$time' WHERE `id_user`='".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy_users` SET `prich`='' WHERE `id_user`='".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
msg('Участник успешно разбанен');
}
}
if(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$user[id]' AND `activate`='0' AND `invit`='0' AND `ban`>'$time' LIMIT 1"),0)==1)
{
$us_ban=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' LIMIT 1"));
echo'Вы забанены в данном сообществе до за '.output_text($us_ban['prich']).'<br/>';
echo'Вернетесь  '.vremja($us_ban['ban']).'<br/>';
if($gruppy['rules']!=NULL)
{
echo'<b>А пока можете пока почитать правила сообщества</b><br/>';
echo'<div class="menu">'.output_text($gruppy['rules']).'</div>';
}
}
else
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' AND `ban`>'$time'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<td class="p_t">';
echo 'Забаненных нет';
echo '</td>';
echo '</tr>';
}

$q=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' AND `ban`>'$time' ORDER BY `ban` DESC LIMIT $start, $set[p_str]");
while ($ank = mysql_fetch_assoc($q))
{
$us=get_user($ank['id_user']);
echo '<tr>';
echo '<td class="icon14">';
echo '<img src="/style/themes/'.$set['set_them'].'/user/'.$us['pol'].'.png" alt="" />';
echo '</td>';
echo '<td class="p_t">';
echo '<a href="info.php?s='.$gruppy['id'].'&id='.$us['id'].'"><span style="color:'.$us['color'].'">'.$us['nick'].'</span></a> '.online($us['id']).'';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="p_m" colspan="2">';
echo '<span class="ank_n">Забанен до:</span> <span class="ank_d">'.vremja($ank['ban']).'</span><br/>';
echo '<span class="ank_n">Причина:</span> <span class="ank_d">'.output_text($ank['prich']).'</span>';
if(isset($user) && $user['id']==$gruppy['admid'])echo'</br/><a href="?s='.$gruppy['id'].'&del='.$us['id'].'">Разбанить</a>';
echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
echo '<div class="navi"><img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
}
else
{
echo'Вам недоступен просмотр забаненных участников данного сообщества';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
