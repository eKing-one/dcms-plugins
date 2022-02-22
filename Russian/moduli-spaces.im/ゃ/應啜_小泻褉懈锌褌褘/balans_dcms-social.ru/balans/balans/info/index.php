<?
////Делал для социал я Kent///
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/adm_check.php';
include_once '../../sys/inc/user.php';

$set['title']='Пополнить счет';
include_once '../../sys/inc/thead.php';
title();
if (!isset($user))
header("location: /index.php?");

err();
aut();
echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a><br />\n";
echo "</div>\n";

echo "<div class='nav1'>\n";
echo "<b>Личный счет:</b><br />
- <b><font color='red'>$user[balls]</font></b> баллов.<br />
- <b><font color='green'>$user[money]</font></b> $sMonet[0]";
echo "</div>\n";

echo "<div class='nav2'>\n";
echo "<div class='foot'><img src='/style/icons/lider.gif'/><a href='/mail.php?id='>Что такое монеты?И на что их можно потратить? :) »</a></div>";

echo "</div>\n";
echo "<div class='nav2'>\n";
echo " <b>Пополнение Монет через:<br></b>";
echo "</div>\n";
echo "<div class='nav2'>\n";
echo '<a href="/balans/info/webmаn.php"><img src="/balans/img/webmoney.png"></a><a href="file.php"><img src="/balans/img/mob.png"></a>';
echo "</div>\n";





echo '<div class="nav1">';
echo "<b>А также,Монеты вы можете получить,учавствуя в различных конкурсах ;)</b><br />";
echo '</div>';








echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'>$user[nick]</a><br />\n";
echo "</div>\n";

include_once '../../sys/inc/tfoot.php';
?>