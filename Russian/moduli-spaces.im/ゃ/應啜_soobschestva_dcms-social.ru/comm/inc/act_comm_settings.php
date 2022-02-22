<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak
if($ank['id']==$user['id'] && isset($user))
{
$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Настройки'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
echo "<div class='p_t'><img src='/comm/img/avatar.png'/> <a href='?act=comm_avatar&id=$comm[id]'>Аватар</a></div>\n";
echo "<div class='p_t'><img src='/comm/img/users_invite.png'/> <a href='?act=comm_activlist&id=$comm[id]'>Желающие вступить (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '0'"),0).")</a></div>\n";
echo "<div class='p_t'><img src='/comm/img/settings.png'/> <a href='?act=comm_join&id=$comm[id]'>Доступность</a></div>\n";
echo "<div class='p_t'><img src='/comm/img/edit.png'/> <a href='?act=comm_object&id=$comm[id]'>Основное</a></div>\n";
echo "<div class='p_t'><img src='/comm/img/cat.png'/> <a href='?act=comm_cat&id=$comm[id]'>Изменить категорию</a></div>\n";
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>