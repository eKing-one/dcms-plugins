<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Нарушители'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if (isset($_GET['sort']))
{
$sort = htmlspecialchars($_GET['sort']);
if ($sort == 'chat')$qsort = " AND `type` = 'chat'";
else
{
$sort = "forum";
$qsort = " AND `type` = 'forum'";
}
}
else
{
$sort = "forum";
$qsort = " AND `type` = 'forum'";
}
if(isset($_POST['nick']) && isset($_POST['submited']))
{
$ank2=mysql_query("SELECT * FROM `user` WHERE `nick` = '".my_esc($_POST['nick'])."'");
$ank2=mysql_fetch_array($ank2);

if($ank2['id']!=0)
{
header("Location:?act=comm_users_ban&id=$comm[id]&user=$ank2[id]&sort=$sort");
exit();
}
else $err[]="Пользователь не найден";
}
err();
if (isset($_GET['add']) && $uinc &&  $uinc['access']!='user')
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['add'])."'"),0)==0)
{
$err[] = 'Пользователь не найден';
err();
include_once '../sys/inc/tfoot.php';
exit();
}
$ainc=mysql_fetch_array(mysql_query("SELECT * FROM `comm_users` WHERE `id_user` = '".intval($_GET['add'])."' AND `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0);
$ank2=get_user(intval($_GET['add']));
if ($ainc['access']!='user')
{
echo "<div class='menu'>Вы не можете банить администрацию сообщества!</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['type']) && $_GET['type']=='chat')
{
$type = 'chat';
$type_name = 'Чат';
}
else
{
$type = 'forum';
$type_name = 'Форум';
}

if (isset($_POST['submited']))
{
$time_ban = intval($_POST['time_ban']);
if ($ank['id']==$user['id'] || $uinc['access']=='adm')
{
if (in_array($time_ban,array(3600, 10800, 86400, 432000)))$time_ban = $time_ban; else $time_ban = 3600;
}
else
{
if (in_array($time_ban,array(3600, 10800, 86400)))$time_ban = $time_ban; else $time_ban = 3600;
}
$time_ban = $time + $time_ban;
$msg = $_POST['msg'];
if (strlen2($msg)<1)$err[] = "Введите комментарий";
if (strlen2($msg)>512)$err[] = "Комментарий должен быть не больше 512-ти символов";
$reason = intval($_POST['reason']);
if (in_array($reason, array(1, 2, 3, 4, 5, 6)))$reason = $reason; else $reason = 1;
if (!isset($err))
{
mysql_query("INSERT INTO `comm_users_ban` SET `id_comm` = '$comm[id]', `id_user` = '$ank2[id]', `id_ank` = '$user[id]', `time_ban` = '$time_ban', `time` = '$time', `msg` = '".my_esc($msg)."', `reason` = '$reason', `type` = '$type'");
header("Location: ?act=comm_users_ban&id=$comm[id]");
exit();
}
}

err();
echo "<form method='post'>\n";
echo "Пользователь: \n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank2['id']);
?>
	<br/>
Раздел: <?php echo $type_name;?><br/>
Бан на:
	<select name="time_ban"><br/>
		<option value="3600">1</option>
		<option value="10800">3</option>
		<option value="86400">24</option>
		<? if ($ank['id']==$user['id'] || $uinc['access']=='adm') { ?><option value="432000">120</option><? } ?>
	</select><br/>
Причина:<br/>
	<input type="radio" name="reason" value="1" checked="checked"> Грубость и оскорбления<br/>
	<input type="radio" name="reason" value="2"> Нецензурная лексика<br/>
	<input type="radio" name="reason" value="3"> Реклама<br/>
	<input type="radio" name="reason" value="4"> Пропаганда ненависти<br/>
	<input type="radio" name="reason" value="5"> Флуд<br/>
	<input type="radio" name="reason" value="6"> Иное<br/>
Комментарий:<br/>
	<textarea name="msg" rows="3" cols="17" style="width:95%"></textarea>
	<input type="submit" name="submited" value="Забанить"/> <? echo "<a href='?act=comm_users_ban&id=$comm[id]'>Назад</a>";?><br/>
</form>
<?
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}

if (isset($_GET['ban_info']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id` = '".intval($_GET['ban_info'])."' AND `id_comm` = '$comm[id]'"),0)!=0)
{
$ban=mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id` = '".intval($_GET['ban_info'])."' AND `id_comm` = '$comm[id]'"));
$ban_user=get_user($ban['id_user']);
$ban_user_give=get_user($ban['id_ank']);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
if ($ban['reason']==2)$reason_name = 'Нецензурная лексика';
if ($ban['reason']==3)$reason_name = 'Реклама';
if ($ban['reason']==4)$reason_name = 'Пропаганда ненависти';
if ($ban['reason']==5)$reason_name = 'Флуд';
if ($ban['reason']==6)$reason_name = 'Иное';
else $reason_name = 'Грубость и оскорбления';
?>
	<div class="p_t"><span style="color:red;">Бан активен</span></div>
	<div class="p_t">Обитатель: <?php echo "<a href='/info.php?id=$ban_user[id]'>$ban_user[nick]</a> ".online($ban_user['id']);?></div>
	<div class="p_t">Сообщество: <a href="?act=comm&id=<?php echo $comm['id'];?>"><?php echo htmlspecialchars($comm['name']);?></a></div>
	<div class="p_t">Дата:</b> <?php echo vremja($ban['time']);?></div>
	<div class="p_t">Модератор: <?php echo "<a href='/info.php?id=$ban_user_give[id]'>$ban_user_give[nick]</a> ".online($ban_user_give['id']);?></div>
	<div class="p_t">Время: <?php echo $time_ban;?> ч.</div>
	<div class="p_t">Причина: <?php echo $reason_name;?></div>
	<div class="p_t">Комментарий: <?php echo output_text($ban['msg']);?></div>
	<? if ($ank['id']==$user['id'] || $uinc['access']=='adm')
	{
	?><div class="p_t"><img src="/comm/img/delete.png" /> <a href='?act=comm_users_ban&id=<?php echo $comm['id'];?>&delete=<?php echo $ban['id'];?>'>удалить нарушение</a></div>
	<?
	}
	?>
<?
echo "<div class='foot'>&raquo; <a href='?act=comm_users_ban&id=$comm[id]'>Назад</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}

if (isset($_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]'"),0)!=0 && ($ank['id']==$user['id'] || $uinc['access']=='adm'))
{
$ban=mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]'"));
$ban_user=get_user($ban['id_user']);
if (isset($_POST['submited']))
{
mysql_query("DELETE FROM `comm_users_ban` WHERE `id` = '$ban[id]' AND `id_comm` = '$comm[id]'");
msg("Бан успешно удален");
}
else
{
echo "<form method='post'>\n";
echo "Подтвердите удаление бана <a href='/info.php?id=$ban_user[id]'>$ban_user[nick]</a><br />\n";
echo "<input type='submit' name='submited' value='Удалить' /> <a href='?act=comm_users_ban&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."'>Отмена</a>";
echo "</form>\n";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}
if (isset($_GET['user']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['user'])."'"),0)!=0)$ank_act=get_user(intval($_GET['user']));

echo "<form method='post' action='?act=comm_users_ban&id=$comm[id]&sort=$sort'>\n";
echo "<input type='text' name='nick' value=''>\n";
echo "<input type='submit' name='submited' value='Найти'>\n";
if (isset($ank_act))echo "<br />\nНарушения <a href='/info.php?id=$ank_act[id]'>$ank_act[nick]</a> <a href='?act=comm_users_ban&id=$comm[id]&sort=$sort'><img src='/comm/img/delete.png' /></a>\n";
echo "</form>";

echo "<div class='p_t'>".($sort!='forum'?"<a href='?act=comm_users_ban&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=forum'>":NULL)."Форум (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND `id_user` = '$ank_act[id]'":NULL)." AND `type` = 'forum'"),0).")".($sort!='forum'?"</a>":NULL)." | ".($sort!='chat'?"<a href='?act=comm_users_ban&id=$comm[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."&sort=chat'>":NULL)."Чат (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND `id_user` = '$ank_act[id]'":NULL)." AND `type` = 'chat'"),0).")".($sort!='chat'?"</a>":NULL)."</div>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND `id_user` = '$ank_act[id]'":NULL)."$qsort"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет нарушений\n";
echo "</td>\n";
echo "</tr>\n";
}
$q=mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]'".(isset($ank_act)?" AND `id_user` = '$ank_act[id]'":NULL)."$qsort ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while($post=mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
$ank3=get_user($post['id_ank']);
$t = "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> забанен модератором <a href='/info.php?id=$ank3[id]'>$ank3[nick]</a> на ".(($post['time_ban']-$post['time'])/3600)." ч.\n<br />\n<a href='?act=comm_users_ban&id=$comm[id]&ban_info=$post[id]'>Подробнее >></a>";
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>\n";
echo $t."\n<span style='float: right;'>".vremja($post['time']).(isset($user) && $ank['id']==$user['id'] || $uinc['access']=='adm'?" <a href='?act=comm_users_ban&id=$comm[id]&delete=$post[id]".(isset($ank_act)?"&user=$ank_act[id]":NULL)."'><img src='/comm/img/delete.png' /></a>":NULL)."</span>\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if ($k_page>1)str("?act=comm_users_ban&id=$comm[id]&sort=$sort&".(isset($ank_act)?"user=$ank_act[id]&":NULL),$k_page,$page); // Вывод страниц

echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>\n";
}
else{header("Location:/comm");exit;}
?>