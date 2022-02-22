<?php
include_once 'sys/inc/start.php'; 
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php'; 
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
@error_reporting(E_ALL);
$mailid = intval($_GET['mailid']);
$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0' AND `mail`.`msg` <> 'NOWWRTING' AND `mail`.`id_user` <> '0'"),0);
$knew = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `mail` WHERE `id_user` = '$mailid' AND `id_kont` = '$user[id]' AND `read` = '0' AND `msg` <> 'NOWWRTING'"),0);
$k_new = $k_new - $knew;
if (($k_new!=0) && ($_SERVER['PHP_SELF'] != '/new_mess.php') && ($_SESSION['msg'] != $k_new))
{
echo " <b>+$k_new</b>";
$_SESSION['needtoplay'] = 1;
}
if (($k_new!=0) && ($_SERVER['PHP_SELF'] != '/new_mess.php') && ($_SESSION['msg'] == $k_new))
{
echo " <b>+$k_new</b>";
}
if ($k_new>0)
{
echo '<script type="text/javascript">play();</script>';
$q = mysql_query("SELECT * FROM `mail` WHERE `read` = '0' AND `id_kont` = '$user[id]' AND `msg` <> 'NOWWRTING' ORDER BY `time` DESC LIMIT 1");
$f = mysql_fetch_assoc($q);
$ms = output_text(substr($f['msg'], 0, 128));
$usr = get_user($f['id_user']);
echo <<<sc
<script type="text/javascript">
var lolka = function()
{
document.getElementById('propose').style.display = "block";
document.getElementById('uvt').innerHTML = "<b><font color='green'>Новое SMS!</font> <a href='/mail.php?id=$usr[id]'><font color='red'>$usr[nick]</a></font></b>:<br />$ms";
document.title = "НОВОЕ СООБЩЕНИЕ!";
setTimeout(function() {
				document.getElementById('propose').style.display = "none";
				document.title = "$set[title]";
			}, 6000);
}
lolka();
</script>
sc;
}
$_SESSION['msg'] = $k_new;
?>