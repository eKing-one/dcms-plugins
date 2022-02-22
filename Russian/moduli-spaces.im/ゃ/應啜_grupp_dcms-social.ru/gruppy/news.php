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
$set['title']=$gruppy['name'].' - Новости'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']  || isset($user) && $user['level']>0)
{
if(isset($user) && $user['id']==$gruppy['admid'])
{
if(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_news` WHERE `id` = '".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_news` WHERE `id`='".intval($_GET['del'])."' LIMIT 1");
msg('Новость успешно удалена');
}
if(isset($_POST['name']) && isset($_POST['mess']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (strlen2($name)<3)$err[]='Короткий заголовок новости';
if (strlen2($name)>32)$err[]='Заголовок новости не должен быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В заголовке обнаружен мат: '.$mat;
$name=my_esc($name);

$mess = esc(stripcslashes(htmlspecialchars($_POST['mess'])));
if (strlen2($mess)<3)$err[]='Короткое сообщение';
if (strlen2($mess)>1024)$err[]='Сообщение не должно быть длиннее 1024 символов';
$mat=antimat($mess);
if ($mat)$err[]='В описании раздела обнаружен мат: '.$mat;
$mess=my_esc($mess);
if(!isset($err))
{
mysql_query("INSERT INTO `gruppy_news` (`id_gruppy`, `name`, `mess`, `time`) values ('$gruppy[id]', '$name', '$mess', '$time')");
if($gruppy['konf_news']==1)
{
$news_us=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0'");
while ($new = mysql_fetch_array($news_us))
{
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$new[id_user]', '$name
$mess
---
Новость сообщества [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
}
}
msg('Новость успешно добавлена');
}
}
err();
if(isset($_GET['add']))
{
echo'<form method="post" action="?s='.$gruppy['id'].'">';
echo'Заголовок<br/>';
echo'<input type="text" name="name"><br/>';
echo'Сообщение<br/>';
echo'<textarea name="mess"></textarea><br/>';
echo'<input type="submit" value="Добавить"></form><br/>';
}
else
{
echo "<div class='nav2'>\n";
echo '<img src="img/20od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&add"><b><u> Добавить новость</b></u></a><br/>';
echo "</div>\n";
}
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_news` WHERE `id_gruppy`='$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="err">';
echo 'Новостей пока нет';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy_news` WHERE `id_gruppy`='$gruppy[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($news = mysql_fetch_assoc($q))
{
echo '<tr>';
if($num==1){
echo "<div class='mess'>\n";
$num=0;
}else{
echo "<div class='mess'>\n";
$num=1;}
echo '<font color=\"#009900\" size=\"4\"><b>'.$news['name'].'</b></font> ('.vremja($news['time']).')';
if(isset($user) && $user['id']==$gruppy['admid'])echo ' [<a href="?s='.$gruppy['id'].'&del='.$news['id'].'"><img src="img/deletu.png" alt="" class="icon"/></a>]<br />';
echo '<b>['.output_text($news['mess']).']</b>';
echo '</div>';

echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo "</div>\n";
}
else
{
echo'Вам недоступен просмотр новостей данного сообщества';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
