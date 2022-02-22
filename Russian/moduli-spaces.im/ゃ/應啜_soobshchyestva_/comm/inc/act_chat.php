<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak


$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Чат'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)!=0)
{
echo "<div class='menu'>Вы находитесь в Черном списке сообщества!</div>\n";
include_once '../sys/inc/tfoot.php';exit();
exit();
}

if ($comm['chat']==0)
{
echo "Чат сообщества <b>".htmlspecialchars($comm['name'])."</b> закрыт\n";
include_once '../sys/inc/tfoot.php';exit();
exit();
}
if ($comm['chat_rule']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '1'"),0)==0)
{
echo "Это чат сообщества <b>".htmlspecialchars($comm['name']).".</b><br />
Чат доступен только участникам данного сообщества.<br />
<a href='/comm/?act=comm&id=$comm[id]&in'>Вступить в сообщество</a>";
include_once '../sys/inc/tfoot.php';exit();
exit();
}
// Приход в чат, уведомление о этом

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_comm_who` WHERE `id_user` = '$user[id]' AND `id_comm` = '$comm[id]'"),0)==0 && isset($user))
{
mysql_query("DELETE FROM `chat_comm_who` WHERE `id_user` = '$user[id]'");
mysql_query("INSERT INTO `chat_comm_who` (`id_user`, `time`,  `id_comm`) values('$user[id]', '$time', '$comm[id]')");
$message="[b]$user[nick][/b] вош".($user['pol']==1?'eл':'ла')." в чат";
$lpost=mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id_user` = '0' AND `id_comm` = '$comm[id]' ORDER BY `time` DESC LIMIT 1"));
if ($lpost['message']!=$message)mysql_query("INSERT INTO `comm_chat` (`id_user`, `time`, `message`, `id_comm`) values('0', '$time', '$message', '$comm[id]')");
else mysql_query("UPDATE `comm_chat` SET `time` = '$time' WHERE `id` = '$lpost[id]'");
}
elseif(isset($user))mysql_query("UPDATE `chat_comm_who` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `id_comm` = '$comm[id]'");

$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_chat` WHERE `id_comm` = '$comm[id]'"),0);
if ($count_komm > 0)
{
$last_komm = mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id_comm` = '$comm[id]' ORDER BY `time` DESC LIMIT 1"));
$creator_last_komm = get_user($last_komm['id_user']);
}
// кто здесь?



if(isset($_GET['who_there']))
{
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_comm_who` WHERE `id_comm` = '$comm[id]'"), 0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `chat_comm_who` WHERE `id_comm` = '$comm[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo '<td class="p_t">';
echo "В чате никого нет !\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($ank2 = mysql_fetch_array($q))
{
$post=get_user($ank2['id_user']);
echo "   <tr>\n";
echo '<td class="icon48	">';
avatar($post['id']);
echo "</td>";
echo "  <td class='p_t'>\n";
echo "<a href='/info.php?id=$post[id]'>$post[nick]</a> ".online($post['id']);
echo "</td>\n";
echo "   </tr>\n";
}
echo "</table>\n";
echo "<div class='foot'>&raquo; <a href='?act=chat&id=$comm[id]'>В чат</a></div>\n";
if ($k_page>1)str("?act=chat&id=$comm[id]&who_there&",$k_page,$page); // Вывод страниц
include_once '../sys/inc/tfoot.php';
exit();
}

// ответ на сообщение



if(isset($_GET['reply']))
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id` = '".intval($_GET['reply'])."' AND `id_comm` = '$comm[id]' LIMIT 1"));
$ank2=get_user($komm['id_user']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_chat` WHERE `id` = '".intval($_GET['reply'])."' AND `id_comm` = '$comm[id]' LIMIT 1"),0)==0)
{
$err[] = "Сообщение не найдено";
err();
include_once '../sys/inc/tfoot.php';exit();
}
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
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank2['id']);
echo "<br />\n";
echo output_text($komm['message'])."\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'chat'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'chat' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' AND `type` = 'chat' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете писать в чате сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
echo "<form method='post' name='message' action='?act=chat&id=$comm[id]' >\n";
echo "Сообщение (1024 знаков)<br /><textarea name='message' rows='5' cols='17' style='width: 95%' placeholder='Введите свой ответ...'></textarea><br />\n";
echo "<label><input type='checkbox' name='private' value='1' /> Приватно</label><br />\n";
echo "<input type='hidden' name='reply' value='$ank2[id]'>";
echo "<input type='hidden' name='komm_reply' value='$komm[id]'>";
echo "<input value='Отправить' type='submit' name='submited'/> <a href='?act=chat&id=$comm[id]'>Назад</a>\n";
echo "</form>\n";
}
include_once '../sys/inc/tfoot.php';
exit();
}




// редактирование сообщения




if(isset($_GET['edit']))
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id` = '".intval($_GET['edit'])."' AND `id_comm` = '$comm[id]' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_chat` WHERE `id` = '$komm[id]' LIMIT 1"),0)==0)
{
$err[] = "Сообщение не найдено";
err();
include_once '../sys/inc/tfoot.php';
exit();
}
$ank2=get_user($komm['id_user']);
if($user['id']==$ank2['id'] && $komm['time']>time()-600)
{
if(isset($_POST['submited']) && isset($_POST['message']))
{
$message=$_POST['message'];

$mat=antimat($message);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($message)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($message)<1){$err[]='Короткое сообщение';}
$message=my_esc($message);

if(!isset($err))
{
mysql_query("UPDATE `comm_chat` SET `message` = '$message' WHERE `id` = '$komm[id]'");
header("Location:?act=chat&id=$comm[id]");
exit();
}
}

echo "<form method='post' action='' name='message'>\n";
echo "Сообщение (1024 знаков<br/><textarea name='message' rows='5' cols='17' style='width: 95%' placeholder='Введите сообщение...'>".input_value_text($komm['message'])."</textarea><br />\n";
echo "<input type='submit' name='submited' value='Сохранить'/> <a href='?act=chat&if=$comm[id]'>Назад</a><br /></form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}

// очистка kомнаты от сообщений

if(isset($_GET['clean']) && ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'))
{
if(isset($_GET['all']))
{
if(isset($_POST['submited']))
{
mysql_query("DELETE FROM `comm_chat` WHERE `id_comm` = '$comm[id]'");
header("Location:?act=chat&id=$comm[id]");
exit;
}
else
{
echo "<form method='POST'>\n";
echo "Очистить чат от сообщений?<br />\n";
echo "<input type='submit' name='submited' value='Да'>\n";
echo " <a href='?act=chat&id=$comm[id]'>Нет</a>";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
}
else
{
if(isset($_POST['submited']))
{
$ch=intval($_POST['ch']);
$mn=intval($_POST['mn']);
$nt=$ch*$mn*3600;
$nt=$time-$nt;
mysql_query("DELETE FROM `comm_chat` WHERE `time` < '$nt' AND `id_comm` = '$comm[id]'");
header("Location:?act=chat&id=$comm[id]");
exit;
}
else
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/delete.png' /> <a href='?act=chat&id=$comm[id]&clean&all'>Очистить чат полностю</a>\n";
echo "<br/>\n";
echo "</div>\n";
echo "<form method='post' action='?act=chat&=$comm[id]&clean&ok'>\n";
echo "Будут удалены посты, написаные ... тому назад<br />\n";
echo "<input type='text' name='ch' size='3' value='1' />\n";
echo "<select name='mn'>\n";
echo "<option value='1' selected='selected'>Часов</option>\n";
echo "<option value='24'>Дней</option>\n";
echo "<option value='168'>Недель</option>\n";
echo "<option value='744'>Месяцев</option>\n";
echo "</select><br />\n";
echo "<input value='Очистить' type='submit' name='submited' /> <a href='?act=chat&id=$comm[id]'>Назад</a></form>\n";
}
include_once '../sys/inc/tfoot.php';
exit();
}
}


// удалить сообщение

if (($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user') && isset($_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_chat` WHERE `id`='".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]' LIMIT 1"),0)!=0)
{
mysql_query("DELETE FROM `comm_chat` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
header("Location: ?act=chat&id=$comm[id]&$passgen");
}

// отправка сообщения

if (isset($_POST['message']) && isset($user))
{
$message=$_POST['message'];
if (isset($_POST['translit']) && $_POST['translit']==1)$message=translit($message);

$mat=antimat($message);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($message)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($message)<1){$err[]='Короткое сообщение';}
if ($creator_last_komm['id']==$user['id'] && my_esc($message)==$last_komm['message']){$err[]='Ваше сообщение повторяет предыдущее';}
if(!isset($err)){
// для ответа!
if(isset($_POST['reply']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_POST['reply'])."'"),0)!=0)
{
$reply_user=get_user(intval($_POST['reply']));
$komm_reply=mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id_user` = '$reply_user[id]' AND `id` = '".intval($_POST['komm_reply'])."' AND `id_comm` = '$comm[id]'"));
$reply=1;
if(isset($_POST['private']) && $_POST['private']==1)$private=1;else $private=0;
}
mysql_query("INSERT INTO `comm_chat` (`id_user`, `time`, `message`, `id_comm`".(isset($reply)?", `reply`, `reply_msg`, `private`":null).") values('$user[id]', '$time', '".my_esc($message)."', '$comm[id]'".(isset($reply)?", '$reply_user[id]', '$komm_reply[message]', '$private'":null).")");
}
}


if(isset($_GET['mdelete']) && ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'))$mdelete=1;

if(isset($mdelete) && isset($_POST['m_d_okey']))
{

foreach ($_POST as $key => $value)
{
if (preg_match('#^mdelelte_komm_([0-9]*)$#',$key,$kid) && $value='1')
{
if (mysql_result(mysql_query("SELECT * FROM `comm_chat` WHERE `id` = '$kid[1]' AND `id_comm` = '$comm[id]' LIMIT 1"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_chat` WHERE `id` = '$kid[1]' AND `id_comm` = '$comm[id]' LIMIT 1"));
mysql_query("DELETE FROM `comm_chat` WHERE `id` = '$komm[id]' AND `id_comm` = '$comm[id]'");
}
}
}
}
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
echo "<div class='p_m'>\n";
echo "<a href='?act=chat&id=$comm[id]&who_there'>Кто здесь?</a> | <a href='/smiles.php'>Список смайлов</a> | <a href='?act=chat&id=$comm[id]&rand_num=".rand(1000,9999)."'>Обновить</a>\n";
if(isset($mdelete))echo "<br />\n<input type='checkbox' name='check_all' value='1'> Отметить все\n";
echo "</div>\n";
if(isset($user))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'chat'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `type` = 'chat' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' AND `type` = 'chat' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете писать в чате сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
echo "<form method='POST' action='?act=chat&id=$comm[id]'>\n";
echo "<textarea name='message' id='message' rows='5' cols='17' style='width: 95%' placeholder='Введите сообщение...'></textarea>\n";
echo "<br/>\n";
echo "<input type='submit' name='submited' value='Отправить'>\n";
echo "</form>\n";
}
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_chat` WHERE `id_comm` = '$comm[id]' AND (`private` = '1' AND (`id_user` = '$user[id]' OR `reply` = '$user[id]') OR `private` = '0')"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if(isset($mdelete))
{
echo "<form method='post'>\n";
}
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";
}
if ($set['time_chat']!=0 && !isset($mdelete))header("Refresh: $set[time_chat]; url=?act=chat&id=$comm[id]&rand_num=".rand(1000,9999)); // автообновление
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
$q=mysql_query("SELECT * FROM `comm_chat` WHERE `id_comm` = '$comm[id]' AND (`private` = '1' AND (`id_user` = '$user[id]' OR `reply` = '$user[id]') OR `private` = '0') ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48' rowspan='1'>\n";
avatar($ank2['id']);
if(isset($mdelete))echo "<br />\n<center><input type='checkbox' name='mdelelte_komm_$post[id]' value='1'></center>\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>".($ank2['id']==$user['id']?'<span style="color: #209143"><b>':NULL)."$ank2[nick]".($ank2['id']==$user['id']?'</b></span>':NULL)."</a>".online($ank2['id']);
echo " <span style='color:green'>".vremja($post['time'])."</span> ".($post['private']==1?" <span style='color: red;'>[!]</span>":NULL)."<br/>\n";
if($post['reply']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[reply]'"),0))
{
echo "<div id='quote-$post[id]' style='display:none; margin:0; margin-bottom:7px; background-color: #EAEEF4; border: 1px solid #999; color: #666; padding: 6px 5px; -webkit-border-radius: 4px; border-radius: 4px;'>".output_text($post['reply_msg'])."</div>\n";
$ru=get_user($post['reply']);
echo "<a href='#' onclick='javascript:toggle(\"$post[id]\"); return false;'>".($post['private']==1 && ($post['id_user']==$user['id'] || $post['reply']==$user['id'])?'<span style="color: #f30000">':NULL)."$ru[nick]".($post['private']==1 && ($post['id_user']==$user['id'] || $post['reply']==$user['id'])?'</span>':NULL)."</a>, \n";
}
echo ($post['private']==1 && $post['reply']==$user['id']?'<span style="color: #f30000">':NULL).output_text($post['message']).($post['private']==1 && $post['reply']==$user['id']?'</span>':NULL);
echo "\n<br/>\n";
if(isset($user) && $ank2['id']!=0)
{
echo "<a href='?act=chat&id=$comm[id]&reply=$post[id]'>Ответить</a>\n";
}
?>
<span style='float:right'>
<?
if(isset($user) && ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user'))
{
echo " <a href='?act=chat&id=$comm[id]&delete=$post[id]' style='color:#933;'>Удалить</a>\n";
}
if(isset($user) && $user['id']==$ank2['id'] && $post['time']>time()-600)
{
echo (isset($user) && ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user')?$rk:NULL)."<a href='?act=chat&id=$comm[id]&edit=$post[id]' style='color:green;'>Ред</a>\n";
}
?>
</span>
<?
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if(isset($mdelete))echo "<input type='submit' name='m_d_okey' value='Удалить'> <a href='?act=forum&id=$comm[id]&page=$page'>Отмена</a>\n</form>\n";
if ($k_page>1)str("?act=chat&id=$comm[id]".(isset($mdelete)?"&mdelete=1":null)."&rand_num=".rand(1000,9999)."&",$k_page,$page); // Вывод страниц
if($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/delete.png' /> <a href='?act=chat&id=$comm[id]&clean'>Очистить чат</a><br />\n";
echo "<img src='/comm/img/move.png' /> <a href='?act=chat&id=$comm[id]&page=$page&mdelete=start'>Выбрать сообщения</a><br />\n";
echo "</div>\n";
}
echo "<div class='foot'>\n";
echo "&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit();
}
else{header("Location:/comm");exit;}
?>