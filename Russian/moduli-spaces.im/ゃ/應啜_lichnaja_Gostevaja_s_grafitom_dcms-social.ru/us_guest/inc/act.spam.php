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
$set['title'] = "Ответ на сообщение";
include('../../sys/inc/thead.php');
title();
aut();
include('inc/guest_enter.php');
if(isset($_POST['submited']) && $_POST['mdp'] == $mdp) {
	$prich = $_POST['prich'];
	if(strlen2(trim($prich)) < 1)$err[]='Укажите причину';
	if(strlen2($prich)>500)$err[]='Причина слишком длинная';
	if(!isset($err)) {
		$msg = "Пользователь ".$user['nick']." подал жалобу на пользователя ".$ank2['nick']." ([url=/user/us_guest/reply/".$comment['id']."]Просмотреть сообщение[/url])
		Причина жалобы: $prich";
		$q = mysql_query("SELECT * FROM `user` WHERE `level` = '2' OR `level` = '3' OR `level` = '4' ORDER BY `date_last` DESC");
		while ($cmpl = mysql_fetch_array($q)) {
			mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$cmpl[id]', '".my_esc($msg)."', '".time()."')");
		}
		msg('Сообщение отправлено на расмотрение Администраци');
	}
}
err();
?>
<form method='post' action=''>
	Причина жалобы:<br />
	<textarea name='prich'></textarea><br />
	Вы действительно хотите отправить жалобу Администрации?<br />
	<input type="hidden" name="mdp" value="<? echo $mdp?>">
	<input type='submit' name='submited' value='Отправить'>
</form>
<div class='foot'>
	<img src='/user/us_guest/images/back.png' alt=''/> <a href='/user/us_guest/?user_id=<? echo $ank['id']?>'>Назад</a>
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>