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

$set['title'] = 'Снегопад';

include_once '../sys/inc/thead.php';
title();
aut();

include_once 'inc1.tpl';


if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);
$ank = get_user($ank['id']);


$act = isset ($_GET['act']) ? $_GET['act'] : '';
switch ($act) {

case 'pay':
	
     if ($user['balls'] >= 100) {
$sneg_s = "".intval($_POST['sneg_s'])."";
mysql_query("UPDATE `user` SET `sneg_s` = ".$sneg_s." WHERE `id` = '$ank[id]' LIMIT 1",$db);
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-100)."' WHERE `id` = '$user[id]' LIMIT 1",$db);
$time_ss = 10800;
$tim_sneg1 = time()+$time_ss;
mysql_query("UPDATE `user` SET `time_sneg` = ".$tim_sneg1." WHERE `id` = '$ank[id]' LIMIT 1",$db);
$msg = 'Пользователь '.$user['nick'].' заказал для Вас снегопад. Теперь в течении трех часов на каждой странице сайта будет идти снег. Не забудьте поблагодарить!';
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$ank[id]', '$ank[id]', '".my_esc($msg)."', '$time')");
$_SESSION['message'] = 'Успешно!';
header ("Location: index.php?id=$ank[id]" . SID);
exit;
	 }else{
$_SESSION['message'] = '<b><font color="darkred">Недостаточно баллов!!!</font></b>';
header ("Location: index.php?id=$ank[id]" . SID);

	 }



 break;







default:
if ($user['id'] == $ank['id']) {
echo "<table class='nav1'><tr><td width='30'><img src='/sneg/img/santa.png' alt='' /></td>";
echo "<td><font color='blue'>ХО-ХО-ХО</font>, <font color='darkgreen'>$user[nick]</font>!<br />Самому себе нельзя заказать снег.<br />Попроси друзей!<br /><font color='chocolate'>Счастливогоe нового года!!!</font>";
echo '</td></tr></table>';
}else{
echo "<table class='nav1'><tr><td width='30'><img src='/sneg/img/santa.png' alt='' /></td>";
echo "<td><font color='blue'>ХО-ХО-ХО</font>, <font color='darkgreen'>$user[nick]</font>!<br />В предверии нового года нужно радовать подарками своих друзей, знакомых!<br />И у тебя есть такая возможность. Ты можешь заказать снегопад для пользователя <b>$ank[nick]</b>.<br />После заказа у пользователя на каждой странице, <u>в течении 3-ех часов</u>, будут кружить прекрастные снежинки, как сдесь.<br />Уверен, $ank[nick] ответит тем же!<br /><font color='chocolate'>Счастливогоe нового года!!!</font>";

echo '</td></tr></table>';

if (time() < $ank['time_sneg']) {
	include_once 'time.php';
	echo "<div class='nav2'><img src='/sneg/img/icon.png' alt='' />У пользователя уже есть активный снегопад, который закончиться через";
     echo ' '.otime($ank["time_sneg"]-time()).'</div>';
 }else{


echo "<div class='nav2'><img src='/sneg/img/q.png' alt='' /> Стоимость всего 100 баллов!</div>";
echo "<div class='nav1'><img src='/sneg/img/icon.png' alt='' />Варианты снежинок:<br />1.<img src='/sneg/img/1.gif' alt='' /> || 2.<img src='/sneg/img/2.gif' alt='' /> || 3.<img src='/sneg/img/3.gif' alt='' /> || 4.<img src='/sneg/img/4.gif' alt='' /></div>";

echo '<form method="post" name="message" action="index.php?id='.$ank['id'].'&act=pay"><img src="/sneg/img/icon.png" alt="" /> Какую снежинку заказать?';

echo " = <select name='sneg_s'><option value='1'>Первая</option><option value='2'>Вторая</option><option value='3'>Третья</option><option value='4'>Четвертая</option></select> - \n";
echo '<input value="Заказать" type="submit" />';
echo '</form>';
}
}

}









include_once '../sys/inc/tfoot.php';
?>