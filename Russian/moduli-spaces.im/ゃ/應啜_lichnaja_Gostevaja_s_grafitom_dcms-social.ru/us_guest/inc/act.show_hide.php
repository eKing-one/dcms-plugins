<?
only_reg();
$comment = mysql_fetch_array(mysql_query("SELECT * FROM `us_guest_comms` WHERE `id` = '".intval($_GET['comment_id'])."'"));
if (!$comment['id']) {
	$set['title'] = 'Ошибка!';
	include('../../sys/inc/thead.php');
	title();
	aut();
	$err = "Сообщение не найдено!";
	err();
	?>
	<div class="foot">
		<img src="/user/us_guest/images/back.png" alt=""> <a href="/">Назад</a><br />
	</div>
	<?
	include('../../sys/inc/tfoot.php');
	exit();
}
$ank = get_user($comment['id_user_adm']);
$ank2 = get_user($comment['id_user']);
if ($user['group_access'] < 7) {
	$set['title'] = 'Ошибка!';
	include('../../sys/inc/thead.php');
	title();
	aut();
	$err = "У Вас нет прав для редактирования этого сообщения!";
	err();
	?>
	<div class="foot">
		<img src="/user/us_guest/images/back.png" alt=""> <a href="/user/us_guest/?user_id=<? echo $ank['id']?>">Назад</a><br />
	</div>
	<?
	include('../../sys/inc/tfoot.php');
	exit();
}
$set['title'] = ' '.$ank['ncik'];
include('../../sys/inc/thead.php');
title();
aut();
include('inc/guest_enter.php');
if ($_GET['mdp'] == $mdp) {
	if ($comment['hide'] == 0)$hide = 1;
	else $hide = 0;
	mysql_query("UPDATE `us_guest_comms` SET `hide` = '$hide', `hide_user` = '$user[id]' WHERE `id` = '$comment[id]'");
}
header("Location: /user/us_guest/?user_id=$ank[id]");
exit();
?>