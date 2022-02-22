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
if ($user['group_access'] < 7 && $ank['id'] != $user['id']) {
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
	mysql_query("DELETE FROM `us_guest_comms` WHERE `id` = '$comment[id]'");
	$select_files = mysql_query("SELECT * FROM `us_guest_files` WHERE `id_comment` = '$comment[id]' AND `id_user_adm` = '$ank[id]'");
	while ($file = mysql_fetch_array($select_files)) {
		mysql_query("DELETE FROM `us_guest_files` WHERE `id` = '$file[id]'");
		if (is_file(H."user/us_guest/files/$file[id].dat"))unlink(H."user/us_guest/files/$file[id].dat");
		if (is_file(H."user/us_guest/screens/user_$ank[id]_file_$file[id]_small.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_$file[id]_small.png");
		if (is_file(H."user/us_guest/screens/user_$ank[id]_file_$file[id]_big.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_$file[id]_big.png");
	}
}
header("Location: /user/us_guest/?user_id=$ank[id]");
exit();
?>