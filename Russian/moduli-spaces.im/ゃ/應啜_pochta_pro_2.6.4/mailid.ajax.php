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
if (!isset($_REQUEST['mailid']))die("Нет ID.");
$set['p_str'] = intval($_COOKIE['colvokomm']);
$mailid = $_REQUEST['mailid'];
if ($_COOKIE['colvokomm'] < 5)
{
echo '<script type="text/javascript">document.cookie = \'colvokomm=5; path=/;\';</script>';
}
mysql_query("DELETE FROM `mail` WHERE `msg` = 'NOWWRTING' AND `time` < '".(time()-10)."'");
if (!isset($mailid))die("Нет ID.");
$ank = get_user($mailid);
$id = $mailid;
$_GET['id'] = $mailid;
$_GET['page'];
echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";
}
$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0' AND `mail`.`msg` <> 'NOWWRTING' AND `mail`.`id_user` <> '0'"),0);
  
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
/*else
{
echo <<<sc
<script type="text/javascript">
document.getElementById('propose').style.display = "none";
document.getElementById('uv').innerHTML = "Уведомлений нет";
document.getElementById('uvt').innerHTML = "Нет активных уведомлений";
document.title = "$set[title]";
</script>
sc;
}*/
$_SESSION['msg'] = $k_new;
if (isset($_GET['del']))
{
$q = mysql_fetch_assoc(mysql_query("SELECT * FROM `mail` WHERE `id` = '".my_esc($_GET['del'])."'"));
if ($q['id_kont'] == $user['id'])$oloj = 'kont';
if ($q['id_user'] == $user['id'])$oloj = 'user';
if ($oloj)mysql_query("UPDATE `mail` SET `vidno$oloj` = '0' WHERE `id` = '".my_esc($_GET['del'])."'");
mysql_query("OPTIMIZE TABLE `mail`");
}
$q=mysql_query("SELECT * FROM `mail` WHERE ((`id_user` = '$user[id]' AND `id_kont` = '$ank[id]') OR (`id_user` = '$ank[id]' AND `id_kont` = '$user[id]'))  AND `msg` <> 'NOWWRTING' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
$lolka = $post['vidnokont'];
$lolka2 = $post['vidnouser'];
if ((($lolka == 1) && ($ank2['id'] != $user['id'])) || (($lolka2 == 1) && ($ank2['id'] == $user['id'])))
{
echo "  <td class='p_t' width='10' >\n"; //style='background:#ddffdd'
if ($ank2)
{
if ($ank2['id'] != $user['id'])
echo "<a href=\"/info.php?id=$ank2[id]\">$ank2[nick]</a>\n";
else
echo "<a href=\"/info.php?id=$ank2[id]\"><b><font color='green'>$ank2[nick]</font></b></a>\n";
}
else
echo "[DELETED] (+$kont[count])\n";
echo "  </td>\n";

if ($post['read']==0)echo "  <td class='p_m' style='background:#fff3f3'>\n";//
else
echo "  <td class='p_m' >\n";//style='background:#e8ffff'
echo output_text($post['msg'])."\n";
echo "<div style='text-align: right;color:navy;float:right'>".vremja($post['time'])." <a href='?id=$mailid&del=$post[id]'>[<font color='red'>X</font>]</a></div>\n";
echo "  </td>\n";
echo "   </tr>\n";


}
}
echo "</table>\n";
if ($k_page>1)str("mail.php?id=$ank[id]&amp;",$k_page,$page); // Вывод страниц
$new = mysql_num_rows(mysql_query("SELECT `id` FROM `mail` WHERE `id_kont` = '$user[id]' AND `id_user` = '$mailid' AND `read` = '0' AND `msg` <> 'NOWWRTING'"));
$_SESSION['msg'] = $_SESSION['msg'] - $new;
if (($k_new!=0)  && ($_SESSION['msg'] != $k_new))
{
echo '<script type="text/javascript">play();</script>';
}
mysql_query("UPDATE `mail` SET `read` = '1' WHERE `id_kont` = '$user[id]' AND `id_user` = '$id'");
