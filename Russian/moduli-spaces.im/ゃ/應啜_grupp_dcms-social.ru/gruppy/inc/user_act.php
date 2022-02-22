<?
if(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' LIMIT 1"),0)==0 && $user['id']!=$gruppy['admid'])
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' LIMIT 1"),0)==0)
{
if(isset($_GET['enter']) && $gruppy['konf_gruppy']==0 || isset($_GET['enter']) && $gruppy['konf_gruppy']==1)
{
mysql_query("INSERT INTO `gruppy_users` (`id_gruppy`, `id_user`, `time`) values ('$gruppy[id]', '$user[id]', '$time')");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']+1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
$q_f = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `lenta_foto` = '1' AND `i` = '1'");
while ($f = mysql_fetch_array($q_f))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));
mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$a[id]', 'Вступил в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
}
msg('Вы успешно вступили в соо');
}
elseif(isset($_GET['enter']) && $gruppy['konf_gruppy']==2)
{
if($user['balls']>=$gruppy['plata'])
{
mysql_query("INSERT INTO `gruppy_users` (`id_gruppy`, `id_user`, `time`) values ('$gruppy[id]', '$user[id]', '$time')");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']+1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+$gruppy['plata'])."' WHERE `id` = '$gruppy[admid]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$gruppy['plata'])."' WHERE `id` = '$user[id]' LIMIT 1");
$q_f = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `lenta_foto` = '1' AND `i` = '1'");
while ($f = mysql_fetch_array($q_f))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));
mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$a[id]', 'Вступил в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
}
msg('Вы успешно вступили в соо');
}
else
{
$err[]='У вас не хватает баллов для вступления в соо';
}
}
elseif(isset($_GET['enter']) && $gruppy['konf_gruppy']==3)
{
mysql_query("INSERT INTO `gruppy_users` (`id_gruppy`, `id_user`, `activate`, `time`) values ('$gruppy[id]', '$user[id]', '1', '$time')");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$gruppy[admid]', '$user[nick] просится в соо [url=/gruppy/$gruppy[id]]$gruppy[name][/url] Активировать или отклонить заявку можно в [url=/gruppy/admin.php?s=$gruppy[id]]Управлении сообществом[/url]', '$time')");
$q_f = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `lenta_foto` = '1' AND `i` = '1'");
while ($f = mysql_fetch_array($q_f))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));
mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$a[id]', 'Вступил в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
}
msg('Заявка на вступление отправлена админу соо');
}
}
elseif(isset($_GET['enter']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' LIMIT 1"),0)!=0)
{
msg('Вы находитесь в черном списке данной группы и не можете в нее вступить');
}
}
elseif(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='1' LIMIT 1"),0)==1)
{
if(isset($_GET['yes']))
{
mysql_query("UPDATE `gruppy_users` SET `invit`='0' WHERE `id_user`='$user[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy_users` SET `time`='$time' WHERE `id_user`='$user[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']+1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
msg('Приглашение принято');
}
elseif(isset($_GET['no']))
{
mysql_query("DELETE FROM `gruppy_users` WHERE `id_user`='$user[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
msg('Приглашение отклонено');
}
}
elseif(isset($user) && isset($_GET['exit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_users` WHERE `id_user`='$user[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']-1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
msg('Вы успешно покинули сообщество');
}
err();
?>