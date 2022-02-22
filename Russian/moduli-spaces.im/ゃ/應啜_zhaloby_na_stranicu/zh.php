<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
include_once 'sys/inc/thead.php';
$set['title']='Жалоба';
title();
err();
aut();
if (!$user)
{
msg("Вы не авторизованы!");
include_once 'sys/inc/tfoot.php';
exit;
}
if (!$_GET['url'] && !$_POST['url'])
{
msg("Жалуетесь ни на что!");
include_once 'sys/inc/tfoot.php';
exit;
}
if (!$_POST['p'] || !$_POST['url']){
echo '<div class="p_t"><form action="?" method="POST">Причина жалобы:<br /><input type="text" name="p" /><input type="hidden" name="url" value="'.htmlspecialchars($_GET['url']).'" /><br /><input type="submit" value="Жалоба" /></form></div>';
}
else
{
$q=mysql_query("SELECT `id` FROM `user` WHERE `group_access` > '2'");
while ($f=mysql_fetch_assoc($q))
{
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$f[id]', '[url=/info.php?id=".$user['id']."]".$user['nick']."[/url] пожаловался на [url=".my_esc($_POST['url'])."]страницу[/url], по причине: ".my_esc($_POST['p'])."!', '$time')");
}
msg("Жалоба отправлена!");
}
include_once 'sys/inc/tfoot.php';
?>