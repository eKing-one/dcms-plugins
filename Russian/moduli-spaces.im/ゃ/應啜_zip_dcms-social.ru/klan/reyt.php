<?

include_once '../sys/inc/start.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));
$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));

$set['title'] = 'Уровень клана '.$clan['name'];
include_once '../sys/inc/thead.php';

title();
aut();


$act = isset($_GET['act']) ? trim($_GET['act']) : '';

switch ($act) {

case 'ok':
if (isset($user) && $user['balls']>=10000 && mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)!=0){

if ($clan['bank']>$clan['priz']){
mysql_query("UPDATE `clan` SET `level` = '".($clan['level']+1)."' WHERE `id` = '$clan[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-10000)."', `balls` = '".($user['balls']+$clan['priz'])."' WHERE `id` = '$user[id]' LIMIT 1");
if ($clan['priz']>0){
mysql_query("UPDATE `clan` SET `bank` = '".($clan['bank']-$clan['priz'])."' WHERE `id` = '$clan[id]' LIMIT 1");
$msg="Вам начислен поощрительный приз за поднятие клана [b]$clan[name][/b] в размере [b]$clan[priz][/b] баллов";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('$clan[user]', '$user[id]', '$msg', '$time')");
}

$msg="Пользователь [url=/info.php?id=$user[id]]$user[nick][/url] пополнил рейтинг клана на 5 %!";
mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$clan[id]', '$msg', '$time')");
msg('Вы успешно подняли клан на 5%');
}
else
{
echo "<div class='str'>";
echo "Извините но поднять клан временно не возможно.<br/> Дождитесь пополнения банка клана и повторите операцию.<br/>\n";
echo "</div>";
}
}
else
{
echo "<div class='str'>";
echo "У вас недостаточно баллов или вы не состоите в клане!!!<br/>\n";
echo "<a href='/sms/'>Купить Баллы</a><br/>\n";
echo "</div>"; 
}
break;

default:

echo "<div class='str'>";
echo "Поднятие уровня клана стоит 10000 баллов!<br/>\n";
if ($user['balls']>=10000)
{
echo "<a href='?act=ok'>Поднять уровень на 5%</a><br/>\n";
}
else
{
echo "У вас недостаточно баллов!!!<br/>\n";
echo "<a href='/sms/'>Купить Баллы</a><br/>\n";
}
echo "</div>";
break;
}

include_once '../sys/inc/tfoot.php'
?>