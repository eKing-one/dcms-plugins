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
if(isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==1 || isset($user) && !isset($_GET['id']))
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
$set['title']=$ank['nick'].' в сообществах'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `admid` = '$ank[id]' LIMIT 1"),0)!=0)
{
echo'<b>Сообщества пользователя:</b><br/>';
$q1=mysql_query("SELECT * FROM `gruppy` WHERE `admid`='$ank[id]' ORDER BY `time` DESC");
while ($adm_soo = mysql_fetch_assoc($q1))
{
$users=$adm_soo['users']+1;
if($num==1){
echo "<div class='nav1'>\n";
$num=0;
}else{
echo "<div class='nav2'>\n";
$num=1;}
echo'<a href="/gruppy/'.$adm_soo['id'].'">'.$adm_soo['name'].'</a> ('.$users.' участников)<br/>';
echo'<span class="ank_n">Описание:</span> <span class="ank_d">'.output_text($adm_soo['desc']).'</span><br/>';
echo'<span class="ank_n">Дата создания:</span> <span class="ank_d">'.vremja($adm_soo['time']).'</span>';
echo '</div>';
}
}
else
{
echo'<b>У пользователя нет своих сообществ</b><br/>';
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user`='$ank[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo'<div class="msg"><b>Пользователь не состоит ни в одном из сообществ</b></div><br/>';
}
else
{
echo'<b>Пользователь состоит в сообществах:</b><br/>';
$q2=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_user`='$ank[id]' ORDER BY `time` DESC");
while ($gruppy = mysql_fetch_assoc($q2))
{
$s=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$gruppy[id_gruppy]' LIMIT 1"));
echo'<div class="gmenu">';
$users2=$s['users']+1;
echo'<a href="/gruppy/'.$s['id'].'">'.$s['name'].'</a> ('.$users2.' участников)<br/>';
echo'<span class="ank_n">Описание:</span> <span class="ank_d">'.output_text($s['desc']).'</span><br/>';
echo'<span class="ank_n">Дата вступления:</span> <span class="ank_d">'.vremja($gruppy['time']).'</span>';
if($gruppy['mod']==1)echo'<br/><span class="ank_n">Статус:</span> <span class="ank_d">Модератор</span>'; else echo'<br/><span class="ank_n">Статус:</span> <span class="ank_d">Пользователь</span>';
echo'</div>';
}
}
}
else
{
header("Location:index.php");
}
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt=""/> <a href="/gruppy/">Сообщества</a><br/>';
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
?>
