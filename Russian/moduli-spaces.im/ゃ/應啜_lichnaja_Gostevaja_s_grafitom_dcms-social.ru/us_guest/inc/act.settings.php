<?
only_reg();
$set['title'] .= " - Настройки";
include('../../sys/inc/thead.php');
title();
aut();
if (isset($_POST['submited']) && $_POST['mdp'] == $mdp) {
	$access = 'all';
	if (in_array($_POST['access'], array('all', 'only_me', 'friends', 'pass', 'auth')))$access = $_POST['access'];
	if ($access != $user['guestbook_access'] && $access != 'all' && $user['balls'] < $balls_set)$err[] = "Для смены доступа нужно $balls_set баллов";
	$pass = NULL;
	if ($access == 'pass') {
		$pass = $_POST['password'];
		if (strlen2(trim($pass)) < 1)$err[]='Введите пароль';
		if (strlen2($pass) > 16)$err[]='Пароль слишком длинный';
	}
	if (!isset($err)) {
		if ($access != $user['guestbook_access'] && $access != 'all')mysql_query("UPDATE `user` SET `balls` = '".($user['balls'] - $balls_set)."' WHERE `id` = '$user[id]'");
		mysql_query("UPDATE `user` SET `guestbook_access` = '$access', `guestbook_password` = '$pass' WHERE `id` = '$user[id]'");
	}
	$komm  ='all';
	if (in_array($_POST['komm'], array('all', 'only_me', 'friends')))$komm = $_POST['komm'];
	mysql_query("UPDATE `user` SET `guestbook_komm` = '$komm' WHERE `id` = '$user[id]'");
	if (!isset($err)) {
		$user['guestbook_password'] = $pass;
		$user['guestbook_access'] = $access;
		$user['guestbook_komm'] = $komm;
		msg("Настройки успешно сохранены");
	}
}
err();
?>
<form method='post' action=''>
	Гостевая доступна:<br />
	<select name='access'>
		<option value='all'<? echo ($user['guestbook_access'] == 'all'?" checked='checked'":null)?>>Всем</option>
		<option value='only_me'<? echo ($user['guestbook_access'] == 'only_me'?" selected='selected'":null)?>/>Только мне</option>
		<option value='friends'<? echo ($user['guestbook_access'] == 'friends'?" selected='selected'":null)?>/>Моим друзьям</option>
		<option value='pass'<? echo ($user['guestbook_access'] == 'pass'?" selected='selected'":null)?>/>Только по паролю:</option>
		<option value='auth'<? echo ($user['guestbook_access'] == 'auth'?" selected='selected'":null)?>/>Только авторизированным</option>
	</select>
	<input name='password' size='10' maxlength='20' type='text' value='<? echo ($user['guestbook_access'] == 'pass'?input_value_text($user['guestbook_password']):null)?>'/><br/>
	* Для смены доступа нужно <? echo $balls_set?> баллов!<br />
	Комментирование разрешено:<br />
	<select name='komm'>
		<option value='all'<? echo ($user['guestbook_komm'] == 'all'?" checked='checked'":null)?>>Всем</option>
		<option value='only_me'<? echo ($user['guestbook_komm'] == 'only_me'?" selected='selected'":null)?>/>Только мне</option>
		<option value='friends'<? echo ($user['guestbook_komm'] == 'friends'?" selected='selected'":null)?>/>Моим друзьям</option>
	</select><br/>
	<input type='hidden' name='mdp' value='<? echo $mdp?>'/>
	<input value='Сохранить' name='submited' type='submit'>
</form>
<div class='foot'>
	<img src='/user/us_guest/images/back.png' alt=''/> <a href='/user/us_guest/?user_id=<? echo $user['id']?>'>Назад</a>
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>