<?
if (isset($_POST['password'])) {
	setcookie("us_guest_password_$ank[id]", $_POST['password']);
	if (isset($_POST['password']) && $_POST['password'] == $ank['guestbook_password']) {
		header("Location: ?user_id=$ank[id]&enter=ok");
		exit();
	}
}
if ($ank['guestbook_access'] == 'only_me') {
	if (!($ank['id'] == $user['id'] && isset($user) || $user['group_access'] >= 7)) {
		$err = "Доступ в гостевую открыт только ее владельцу";
		err();
		include('../../sys/inc/tfoot.php');
		exit();
	}
} elseif ($ank['guestbook_access'] == 'friends') {
	if (!($ank['id'] == $user['id'] && isset($user) || $user['group_access'] >= 7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) {
		$err = "Доступ в гостевую открыт только друзьям ее владельца";
		err();
		include('../../sys/inc/tfoot.php');
		exit();
	}
} elseif ($ank['guestbook_access'] == 'pass') {
	if (!(isset($_COOKIE["us_guest_password_$ank[id]"]) && $_COOKIE["us_guest_password_$ank[id]"] == $ank['guestbook_password'] || $ank['id'] == $user['id'] && isset($user) || $user['group_access'] >= 7)) {
		if (isset($_POST['password']) && $_POST['password'] != $ank['guestbook_password'])$err = "Пароль неправильный";
		err();
		?>
		<div class="err">
			Доступ в гостевую открыт только по паролю.<br />
			Введите пароль:
			<form action="" method="POST">
				<input type="password" name="password" value=""><br />
				<input type="submit" name="submited" value="Далее"><br />
			</form>
		</div>
		<?
		include('../../sys/inc/tfoot.php');
		exit();
	}
} elseif ($ank['guestbook_access'] == 'auth') {
	if (!isset($user)) {
		$err = "Доступ в гостевую открыт только авторизированным пользователям";
		err();
		include('../../sys/inc/tfoot.php');
		exit();
	}
}
?>