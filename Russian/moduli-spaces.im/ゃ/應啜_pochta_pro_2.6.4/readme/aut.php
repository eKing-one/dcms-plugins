<?php


function aut()
{
global $set;

if ($set['web']==false)
{

global $user;

if (isset($user))
{

$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0' AND `mail`.`msg` <> 'NOWWRTING' AND `mail`.`id_user` <> '0'"),0);
$k_new_fav=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);
 

 

echo "<div class='location'>";


////////////////////////////////////////////////////////////////
//////////////////////ДЛЯ МОДА ПОЧТЫ!!//////////////////////////
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
echo <<<m
<div id="propose" style="display:none;position:fixed;left:0;bottom:0;opacity:0.9">
<span id="uvt" style="position:fixed;left:0;top:220px;padding:10px;background:#F2F4FE;color:navy;width:180px;text-align:center;border:1px solid navy;">
Пустое уведомление.
</span>
</div>
m;
////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
//////////////////////ДЛЯ МОДА ПОЧТЫ!!//////////////////////////
////////////////////////////////////////////////////////////////

$otobr = intval($user['otobr']);
if ($otobr == 1)
{
$l111 = 'Лен';
$j111 = 'Жур';
$m111 = 'Поч';
$k111 = 'Каб';
$d111 = 'Дом';
$help111 = '<font color="green">FAQ</font>';
$g111 = 'Глав';
}
else
{
$l111 = '<img src="/img/lenta.png" />';
$j111 = '<img src="/img/journal.png" />';
$m111 = '<img src="/img/mail.png" />';
$k111 = '<img src="/img/office.png" />';
$d111 = '<img src="/img/home.png" />';
$help111 = '<img src="/img/help.png" />';
$g111 = '<img src="/img/go.png" />';
}

echo "<div class='aut' valign='center' style='text-align: center'>";
if ($user['show_help'] == 1)echo "<a href='/faq.php'>$help111</a> ";
echo "<a href='/info.php'>$d111</a> ";
echo "<a href='/umenu.php'>$k111</a> ";
if ($k_new!=0 && $k_new_fav==0)
echo "<a href='/new_mess.php'>$m111 <span id=\"mail\" style='color:red'><b>+$k_new</b></span></a> ";
else
echo "<a href='/new_mess.php'>$m111<span id=\"mail\" style='color:red'></span></a> ";




#ВОТ########################################################
#Это########################################################
#Должно#####################################################
#БЫТЬ#######################################################

//<span id=\"mail\" style='color:red'><b>+$k_new</b></span>

#вместо#####################################################

//<b>+$k_new</b>

############################################################

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `journal` WHERE `id_kont` = '$user[id]' AND `read` = '0' LIMIT 1"), 0) > 0)
{
echo "<a href='/journal.php'>$j111 <span id=\"journal\" style='color:red'>+".mysql_result(mysql_query("SELECT COUNT(*) FROM `journal` WHERE `id_kont` = '$user[id]' AND `read` = '0' LIMIT 1"), 0)."</span></a> ";
}
else
echo "<a href='/journal.php'>$j111<span id=\"journal\" style='color:red'></span></a> ";

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta` WHERE `id_kont` = '$user[id]' AND `read` = '0' LIMIT 1"), 0) > 0)
{
echo "<a href='/lenta.php'>$l111 <span id=\"lenta\" style='color:red'>+".mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta` WHERE `id_kont` = '$user[id]' AND `read` = '0' LIMIT 1"), 0)."</span></a> ";
}
else
echo "<a href='/lenta.php'>$l111<span id=\"lenta\" style='color:red'></span></a> ";
echo "<a href='/'>$g111</a> ";
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `frends_new` WHERE `to` = '$user[id]' LIMIT 1"), 0) > 0)
{
echo '<br /><a href="/frend_new.php">[Вам предлагают дружить]</a><br />';
}

if ($k_new_fav!=0)
echo "<b><a href='/new_mess.php'><img src='/style/icons/mess_fav.png' alt='$k_new_fav' /> Сообщени".($k_new_fav==1?'е':'я')."</a></b><br />\n";

$k_n_s_zak=mysql_result(mysql_query("SELECT COUNT(`forum_zakl`.`id_them`) FROM `forum_zakl` LEFT JOIN `forum_p` ON `forum_zakl`.`id_them` = `forum_p`.`id_them` AND `forum_p`.`time` > `forum_zakl`.`time` WHERE `forum_zakl`.`id_user` = '$user[id]' AND `forum_p`.`id` IS NOT NULL"),0);
if ($k_n_s_zak>0)
echo "<a href='/zakl.php' title='Новые сообщения в закладках'>Сообщения в закладках ($k_n_s_zak)</a><br />\n";

if ($user['group_access']<2)$zap="`user_read` = '0' AND `user` = '$user[id]'";
else
$zap="`read` = '0'";
$qqq=mysql_query("SELECT `id`, `name` FROM `tickets` WHERE ".$zap." AND `closed` = '0'");
if (mysql_num_rows($qqq)>0){
echo '</div><div class="p_m" align="center" style="text-align: center">';
while ($fff=mysql_fetch_assoc($qqq)){
echo "<a href='/tickets/ticket.php?id=$fff[id]'><font color='red'>".htmlspecialchars($fff['name'])."</font></a><br>";
}
echo "</div>";
}
}
else
{
echo "<div class='aut'><a href='/aut.php'>Вход</a> <a href='/reg.php'>Регистрация</a></div>\n";

}
echo "</div>\n";
}
/*$arr = array('/mail.php', '/new_mess.php', '/aut.php', '/reg.php', '/users.php', '/online.php', '/konts.php', '/info.php');
$momental = 1;
if (!in_array($_SERVER['PHP_SELF'], $arr))
{
msg('Эта страничка не входит в белый список.');
if ($user)header('Location: /konts.php');
else
header('Location: /reg.php');
include_once $_SERVER['DOCUMENT_ROOT'].'/sys/inc/tfoot.php';
exit;
}*/
}



?>