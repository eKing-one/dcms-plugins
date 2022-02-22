<?
$ank = get_user(intval(@$_GET['user_id']));
if (!@$ank['id']) {
	$set['title'] = 'Ошибка!';
	include('../../sys/inc/thead.php');
	title();
	aut();
	$err[] = 'Пользователь не найден';
	err();
	?>
	<div class="foot">
		<img src="/user/us_guest/images/back.png" alt="Назад"> <a href="/">Назад</a><br />
	</div>
	<?
	include('../../sys/inc/tfoot.php');
	exit();
}
$count_attached_files = mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_files` WHERE `id_comment` = '0' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user[id]'"), 0);
$sess_var = "us_guest_mess_new_".$ank['id'];
$set['title'] = ' '.$ank['nick'];
include('../../sys/inc/thead.php');
title();
aut();
include('inc/guest_enter.php');
if (isset($_POST['attach_files']) && isset($user) && ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) && $_POST['mdp'] == $mdp) {
	$msg = $_POST['msg'];
	$_SESSION[$sess_var]['msg'] = $msg;
	header("Location: /user/us_guest/attach/$ank[id]/files");
	exit();
}
if (isset($_POST['attach_graf']) && isset($user) && ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) && $_POST['mdp'] == $mdp) {
	$msg = $_POST['msg'];
	$_SESSION[$sess_var]['msg'] = $msg;
	header("Location: /user/us_guest/attach/$ank[id]/graf");
	exit();
}
if (isset($_POST['msg']) && isset($user) && ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) && $_POST['mdp'] == $mdp) {
	$msg = $_POST['msg'];
	$_SESSION[$sess_var]['msg'] = $msg;
	if (strlen2($msg) > 1024)$err[]='Сообщение слишком длинное';
	if (strlen2(trim($msg)) < 1)$err[]='Короткое сообщение';
	if (isset($_FILES['file']) && is_file($_FILES['file']['tmp_name'])) {
		$file = $_FILES['file'];
		$fst_name = $file['name'];
		$fst_name = ereg_replace('(#|\?)', NULL, $fst_name);
		$ras = eregi_replace('^.*\.', NULL, $fst_name); // расширение без имени файла
		$name = eregi_replace('\.[^\.]*$', NULL, $fst_name); // имя файла без расширения
		if (!$name)$err[] = 'Неверное название файла';
		if (filesize($file['tmp_name']) > 10*1048576)$err[]='Размер файла превышает установленные ограничения';
		$name = my_esc($name);
		$ras = my_esc($ras);
	}
	$msg = my_esc($msg);
	if (!isset($err)) {
		mysql_query("INSERT INTO `us_guest_comms` (`id_user`, `id_user_adm`, `time`, `msg`) values('$user[id]', '$ank[id]', '".time()."', '$msg')");
		$id = mysql_insert_id();
		if (isset($_POST['reply_id_user']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_POST['reply_id_user'])."'"), 0) && isset($_POST['reply_id_comment']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_comms` WHERE `id` = '".intval($_POST['reply_id_comment'])."' AND `id_user_adm` = '$ank[id]'"), 0)) {
			$rus = get_user(intval($_POST['reply_id_user']));
			$reply_comment = mysql_fetch_array(mysql_query("SELECT * FROM `us_guest_comms` WHERE `id` = '".intval($_POST['reply_id_comment'])."' AND `id_user_adm` = '$ank[id]'"));
			mysql_query("UPDATE `us_guest_comms` SET `reply_id_user` = '$rus[id]', `reply_id_comment` = '$reply_comment[id]' WHERE `id` = '$id'");
		}
		if ($user['id']!=$ank['id']) {
			if ($user['pol']==1)$pol='оставил'; else $pol='оставила';
			$jmsg="[url=/info.php?user_id=".$user['id']."]".$user['nick']."[/url] ".$pol." комментарий в вашей [url=/guestbook/?user_id=".$ank['id']."]гостевой книге[/url]";
			mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$jmsg', '$time')");
		}
		$select_files = mysql_query("SELECT * FROM `us_guest_files` WHERE `id_comment` = '0' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user[id]'");
		while ($file = mysql_fetch_array($select_files)) {
			mysql_query("UPDATE `us_guest_files` SET `id_comment` = '$id' WHERE `id` = '$file[id]'");
		}
		$jurnal_msg = my_esc("[url=/info.php?user_id=".$user['id']."]".$user['nick']."[/url] оставил".(!$user['pol']?"а":null)." сообщение в [url=/user/us_guest/?user_id=".$ank['id']."]гостевой ".$ank['nick']."[/url]");
		$all_users_commed = array();
		$q = mysql_query("SELECT * FROM `us_guest_comms` WHERE `id_user_adm` = '$ank[id]'");
		while ($comment = mysql_fetch_array($q)) {
			if (!in_array($comment['id_user'], $all_users_commed) && $comment['id_user'] != $ank['id'] && $comment['id_user'] != $user['id']) {
				$all_users_commed[] = $comment['id_user'];
				@$journal_row = mysql_fetch_array(mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `id_kont` = '$comment[id_user]' AND `msg` = '$jurnal_msg'"));
				if (!$journal_row['id'])mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$comment[id_user]', '$jurnal_msg', '".time()."')");
				else {
					mysql_query("UPDATE `jurnal` SET `read` = '0', `time` = '".time()."' WHERE `id` = '$journal_row[id]'");
				}
			}
		}
		if ($user['id'] != $ank['id']) {
			$jurnal_msg = my_esc("[url=/info.php?user_id=".$user['id']."]".$user['nick']."[/url] оставил".(!$user['pol']?"а":null)." сообщение в [url=/user/us_guest/?user_id=".$ank['id']."]Вашей гостевой[/url]");
			@$journal_row = mysql_fetch_array(mysql_query("SELECT * FROM `jurnal` WHERE `id_user` = '0' AND `id_kont` = '$ank[id]' AND `msg` = '$jurnal_msg'"));
			if (!$journal_row['id'])mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$jurnal_msg', '".time()."')");
			else {
				mysql_query("UPDATE `jurnal` SET `read` = '0', `time` = '".time()."' WHERE `id` = '$journal_row[id]'");
			}
		}
		unset($_SESSION[$sess_var]);
	}
}
err();
if (isset($user)) {
	if ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) {
		if (!isset($_SESSION[$sess_var])) {
			$_SESSION[$sess_var] = array(
				'msg' => null
			);
		}
		?>
		<form action='' name='message' method='POST' enctype='multipart/form-data'>
			<?
			if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
			else {
				?>
				<? echo $tPanel?>Сообщение:<br />
				<textarea name="msg" id="textarea"><? echo input_value_text($_SESSION[$sess_var]['msg'])?></textarea><br />
				<?
			}
			?>
			Прикрепить: <input type="submit" value="Файлы<? echo ($count_attached_files?' ('.$count_attached_files.'/'.$max_attach_files.')':null)?>" name="attach_files" style="background: transparent; border-radius: 4px; cursor: pointer; padding: 4px; font-size: 14px; color: green;"><? if ($count_attached_files < $max_attach_files && $webbrowser) { ?> | <input type="submit" value="Граффити" name="attach_graf" style="background: transparent; border-radius: 4px; cursor: pointer; padding: 4px; font-size: 14px; color: green;"><? } ?><br />
			<!--Файл (<? echo size_file(10*1048576)?>):<br />
			<input name='file' type='file'/><br />-->
			<input type="hidden" name="mdp" value="<? echo $mdp?>">
			<input value="Отправить" type="submit" name="submited" />
		</form>
		<?
	} else {
		?>
		<div class="err">
			Автор ограничил круг лиц, которые могут оставлять сообщения!
		</div>
		<?
	}
} else {
	?>
	<div class="msg">
		<img src="/user/us_guest/images/add.png" alt=""> <a href="/aut.php">Добавить сообщение</a><br />
	</div>
	<?
}
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_comms` WHERE `id_user_adm` = '$ank[id]'".($user['group_access'] < 7?" AND `hide` = '0'":null)), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];
$r = ' | ';
if (!$k_post) {
	?>
	<div class="mess">
		Нет сообщений
	</div>
	<?
}
$screen_work = array();
$q = mysql_query("SELECT * FROM `us_guest_comms` WHERE `id_user_adm` = '$ank[id]'".($user['group_access']<7?" AND `hide` = '0'":null)." ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)) {
	$ank2 = get_user($post['id_user']);
	if (isset($num) && !$num) {
		echo '<div class="nav1" style="overflow: hidden;">';
		$num = 1;
	} else {
		echo '<div class="nav2" style="overflow: hidden;">';
		$num = 0;
	}
	?>
		<? echo group($ank2['id'])?> <a href="/info.php?user_id=<? echo $ank2['id']?>"><? echo $ank2['nick']?></a> <? echo online($ank2['id'])?><br/>
		<?
		if ($post['hide']) {
			$hu = get_user($post['hide_user']);
			?>
			<span style="color: red;">Скрыл <? echo $hu['nick']?></span><br/>
			<?
		}
		if ($post['reply_id_user']) {
			$ru = get_user($post['reply_id_user']);
			echo $ru['nick']?>, <?
		}
		echo output_text($post['msg']);
		$comment_id_attach = $post['id'];
		$user_id_attach = $ank2['id'];
		$delete_attach = 0;
		include('inc/files_show.php');
		?>
		<div style='font-size: small;'>
			<?
			if (isset($user) && $ank2['id']!=$user['id']) {
				?>
				<a href="/user/us_guest/reply/<? echo $post['id']?>">Ответить</a><? echo $r;
			}
			if ($user['group_access']>=7) {
				?>
				<a href='/user/us_guest/sh/<? echo $post['id']?>/<? echo $mdp?>'><? echo (!$post['hide']?"Скрыть":"Показать")?></a>
				<?
				echo $r;
			}
			if (($user['group_access']>=7 || $user['id']==$ank['id']) && isset($user)) {
				?>
				<a href='/user/us_guest/delete/<? echo $post['id']?>/<? echo $mdp?>'>Удалить</a><? echo $r;
			}
			if ($ank2['id'] == $user['id'] && $post['time'] + 600 > time()) {
				?>
				<a href='/user/us_guest/edit/<? echo $post['id']?>'>Ред</a><? echo $r;
			}
			?>
			<a href='/user/us_guest/spam/<? echo $post['id']?>'>Жалоба</a>
			<?
		?></div>
	</div><?
}
if ($k_page>1)str("?user_id=$ank[id]&", $k_page, $page); // Вывод страниц
?>
<div class="foot">
	<?
	if (isset($user) && $user['id'] == $ank['id']) {
		?><img src="/user/us_guest/images/settings.png" alt=""> <a href="/user/us_guest/settings">Настройки гостевой</a><br />
		<?
	}
	?>
	<img src="/user/us_guest/images/back.png" alt=""> <a href="/info.php?id=<? echo $ank['id']?>">Страничка <? echo $ank['nick']?></a><br />
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>