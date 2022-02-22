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
if (!($ank2['id'] == $user['id'] && $comment['time'] + 600 > time())) {
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
$set['title'] = "Редактирование сообщения";
include('../../sys/inc/thead.php');
title();
aut();
include('inc/guest_enter.php');
if (isset($_POST['submited']) && $_POST['mdp'] == $mdp) {
	$msg = $_POST['msg'];
	if (strlen2(trim($msg)) < 1)$err = 'Введите сообщение';
	elseif (strlen2($msg) > 1024)$err = 'Сообщение слишком длинное';
	else {
		$msg = my_esc($msg);
		mysql_query("UPDATE `us_guest_comms` SET `msg` = '$msg' WHERE `id` = '$comment[id]'");
		header("Location: /user/us_guest/?user_id=$ank[id]");
		exit();
	}
}
err();
if (isset($_GET['delete_file']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_files` WHERE `id` = '".intval($_GET['delete_file'])."' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user[id]' AND `id_comment` = '$comment[id]'"), 0)) {
	mysql_query("DELETE FROM `us_guest_files` WHERE `id` = '".intval($_GET['delete_file'])."'");
	if (is_file(H."user/us_guest/files/".intval($_GET['delete_file']).".dat"))unlink(H."user/us_guest/files/".intval($_GET['delete_file']).".dat");
	if (is_file(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_small.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_small.png");
	if (is_file(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_big.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_big.png");
	header("Location: ?");
	exit();
}
$comment_id_attach = $comment['id'];
$user_id_attach = $ank2['id'];
$div_style_attach = ' style="margin: 4px 0;" class="p_m"';
$delete_attach = 1;
include('inc/files_show.php');
?>
<form action='' name='message' method='POST'>
	<?
	$msg2 = input_value_text($comment['msg']);
	if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
	else {
		?>
		<? echo $tPanel?>Сообщение:<br />
		<textarea name="msg" id="textarea"><? echo input_value_text($comment['msg'])?></textarea><br />
		<?
	}
	?>
	<input type="hidden" name="mdp" value="<? echo $mdp?>">
	<input value="Сохранить" type="submit" name="submited" />
</form>
<div class="foot">
	<img src="/user/us_guest/images/back.png" alt=""> <a href="/user/us_guest/?user_id=<? echo $ank['id']?>">Назад</a><br />
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>