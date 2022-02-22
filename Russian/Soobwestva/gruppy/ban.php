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
$set['title']=$gruppy['name'].' - Нарушения'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['ban']!=NULL && $gruppy['ban']>$time)
{
echo'Сообщество забанено за '.output_text($gruppy['prich']).'<br/>Откроется '.vremja($gruppy['ban']).'<br/>';
$us=get_user($gruppy['ban_us']);
echo'Забанил: <a href="/info.php?id='.$us['id'].'">'.$us['nick'].'</a><br/>';
if(isset($user) && $user['level']>=3 && $user['id']!=$gruppy['admid'])
{
if(isset($_GET['del']))
{
mysql_query("UPDATE `gruppy` SET `ban`='$time' WHERE `id`='$gruppy[id]' LIMIT 1");
msg('Сообщество успешно разбанено');
}
else
{
echo'<a href="?s='.$gruppy['id'].'&del">Разбанить</a><br/>';
}
}
}
else
{
echo'Нет нарушений<br/>';
}
if(isset($user) && $user['level']>=3 && $user['id']!=$gruppy['admid'])
{
if(isset($_GET['ban']))
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
$prich=my_esc($prich);
if(!isset($err))
{
mysql_query("UPDATE `gruppy` SET `ban`='$time_ban' WHERE `id`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `prich`='$prich' WHERE `id`='$gruppy[id]' LIMIT 1");
msg('Сообщество успешно забанено');
}
}
else
{
echo'<form method="post" action="?s='.$gruppy['id'].'&ban">';
echo'На срок<br/>';
echo'<input type="text" name="ban_time" size="3">';
echo'<select name="ban_type">';
echo'<option value="1">Дней</option>';
echo'<option value="0">Часов</option>';
echo'</select><br/>';
echo'Причина(обязательно)<br/>';
echo'<textarea name="prich"></textarea><br/>';
if ($user['set_translit']==1)echo '<label><input type="checkbox" name="translit" value="1"/> Транслит</label><br />';
echo'<input type="submit" value="Отправить"></form><br/>';
}
}
else
{
echo'<a href="?s='.$gruppy['id'].'&ban">Забанить</a><br/>';
}
}
echo'<a href="?s='.$gruppy['id'].'">'.$gruppy['name'].'</a><br/>';
echo '<div class="navi"><img src="img/back.png" alt="" class="icon"/> <a href="index.php">Сообщества</a></div>';
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
