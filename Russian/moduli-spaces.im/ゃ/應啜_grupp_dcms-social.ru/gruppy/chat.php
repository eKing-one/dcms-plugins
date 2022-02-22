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
$num=1;
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
$set['title']=$gruppy['name'].' - Мини-чат'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid'] || isset($user) && $user['level']>0)
{
if(isset($user) && $user['id']==$gruppy['admid'] && isset($_GET['del']))
{
if($_GET['del']=='all'){mysql_query("DELETE FROM `gruppy_chat` WHERE `id_gruppy`='$gruppy[id]'"); msg('Чат успешно очищен');}
elseif(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_chat` WHERE `id` = '".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_chat` WHERE `id`='".intval($_GET['del'])."' LIMIT 1");
msg('Сообщение успешно удалено');
}
}
if (isset($_POST['msg']) && isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_chat` WHERE `id_gruppy`='$gruppy[id]' AND `id_user` = '$user[id]' AND `mess` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

mysql_query("INSERT INTO `gruppy_chat` (`id_gruppy`, `id_user`, `mess`, `time`) values('$gruppy[id]', '$user[id]', '".my_esc($msg)."', '$time')");
msg('Сообщение успешно добавлено');
}
}
if (isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
{
echo '<form method="post" name="message" action="?s='.$gruppy['id'].'&'.$passgen.'">';
echo "<div class='textmes'>\n";
echo 'Сообщение:<br/>';
echo $tPanel. '<textarea name="msg"></textarea><br/>';

if ($user['set_translit']==1)echo '<label><input type="checkbox" name="translit" value="1"/> Транслит</label><br />';
echo '<input value="Добавить" type="submit" />';
echo '</form>';
echo "</div>\n";
}
else
echo 'Вы не можете писать сообщение в мини-чате данной группы<br/>';
err();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_chat` WHERE `id_gruppy`='$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo '<tr>';
echo '<div class="msg">';
echo 'Нет сообщений';
echo '</div>';
echo '</tr>';
}

$q=mysql_query("SELECT * FROM `gruppy_chat` WHERE `id_gruppy`='$gruppy[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank=get_user($post['id_user']);
echo '<tr>';
if($num==1){
echo "<div class='nav1'>\n";
$num=0;
}else{
echo "<div class='nav2'>\n";
$num=1;}
echo online($ank['id']).' <a href="info.php?s='.$gruppy['id'].'&id='.$ank['id'].'">'.$ank['nick'].'</a><br /> ';
echo output_text($post['mess']).'<br />';
if (isset($user) && $user['id']==$gruppy['admid'])
echo '<div style="text-align: right;"><img src="/gruppy/img/clock.png" alt="" class="icon"/><font color="#afb0a3"> '.vremja($post['time']).'</font> <a href="?s='.$gruppy['id'].'&del='.$post['id'].'"><img src="/style/icons/delete.gif" alt="" class="icon"/></a><br/>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</tr>';

}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
if (isset($user) && $user['id']==$gruppy['admid'])echo '<div class="foot"><img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&del=all"><b>Очистить чат</b></a></div>';
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a>';
echo "</div>\n";
}
else
{
echo'<div class="msg">Вам недоступен просмотр чата данного сообщества</div>';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
