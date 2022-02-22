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
if($gruppy['konf_gruppy']==0 || isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']  || $user['level']>0))
{
if(isset($_GET['id']) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '".intval($_GET['id'])."' AND `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' LIMIT 1"),0)==1 || htmlspecialchars($_GET['id'])==$gruppy['admid']) || isset($user) && !isset($_GET['id']) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$user[id]' AND `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
{
if(isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==1)
{
$id=intval($_GET['id']);
$ank=get_user($id);
}
else
{
$ank=get_user($user['id']);
}
$ank_soo = mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_users` WHERE `id_user`='$ank[id]' LIMIT 1"));
$set['title']=$gruppy['name'].' - '.$ank['nick']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($_GET['del']) && $user['id']!=$ank['id'] && $user['id']==$gruppy['admid'])
{
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$ank[id]', 'Вас выгнали из группы :-( [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
mysql_query("DELETE FROM `gruppy_chat` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_forum_thems` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_users` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
$q=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='$ank[id]'");
while ($del = mysql_fetch_assoc($q))
{
unlink(H.'sys/gruppy/obmen/files/'.$del['id'].'.dat');
mysql_query("DELETE FROM `gruppy_obmen_komm` WHERE `id_gruppy`='$gruppy[id]' AND `id_file`='$del[id]'");
}
mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_votes_otvet` WHERE `id_user`='$ank[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']-1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
msg('Пользователь успешно выгнан из группы');
}
echo'<span style="color:'.$ank['ncolor'].'">'.$ank['nick'].'</span><br/>';
avatar($ank['id']);
if($ank['id']!=$gruppy['admid'] && $ank_soo['ban']!=NULL && $ank_soo['ban']>$time)
{
echo'<b>Участник забанен до '.vremja($ank_soo['ban']).' за '.output_text($ank_soo['prich']).'</b><br/>';
}
echo'<div class="menu">';
if($ank['id']==$gruppy['admid'])
{
if($ank['pol']==0)echo'Создала группу: '.vremja($gruppy['time']).'<br/>'; else echo'Создал группу: '.vremja($gruppy['time']).'<br/>';
}
else
{
if($ank['pol']==0)echo'Вступила в группу: '.vremja($ank_soo['time']).'<br/>'; else echo'Вступил в группу: '.vremja($ank_soo['time']).'<br/>';
}
if($ank_soo['invit_us']!=NULL && $ank_soo['invit_us']!=0)
{
$ank_invit = get_user($ank_soo['invit_us']);
if($ank_invit['pol']==0)
{
echo'Пригласила: ';
}
else
{
echo'Пригласил: ';
}
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `id_user` = '$ank_invit[id]' LIMIT 1"),0)==1)echo'<a href="info.php?s='.$gruppy['id'].'&id='.$ank_invit['id'].'">'.$ank_invit['nick'].'</a><br/>'; else echo'<a href="/info.php?id='.$ank_invit['id'].'">'.$ank_invit['nick'].'</a><br/>';
}
echo'<a href="/info.php?id='.htmlspecialchars($ank['id']).'">Личная страница</a><br/>';
echo'Тем на форуме: '.mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$ank[id]'"),0).'<br/>';
echo'Сообщений на форуме: '.mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$ank[id]'"),0).'<br/>';
echo'Сообщений в мини-чате: '.mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_chat` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$ank[id]'"),0).'<br/>';
if(isset($user) && $ank['id']!=$user['id'] && $ank['id']!=$gruppy['admid'] && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1))
{
echo'<a href="banned.php?s='.$gruppy['id'].'&ban='.$ank['id'].'">Забанить</a><br/>';
if($user['id']==$gruppy['admid'])
{
echo'<a href="?s='.$gruppy['id'].'&id='.$ank['id'].'&del">Выгнать из соо</a><br/>';
echo'<a href="admin.php?s='.$gruppy['id'].'&bl&uz='.$ank['id'].'">Добавить в BlackList</a><br/>';
}
}
echo'</div>';
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
header("Location:index.php?s=$gruppy[id]");
}
}
else
{
echo'Вам недоступен просмотр анкеты участников данной группы';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
