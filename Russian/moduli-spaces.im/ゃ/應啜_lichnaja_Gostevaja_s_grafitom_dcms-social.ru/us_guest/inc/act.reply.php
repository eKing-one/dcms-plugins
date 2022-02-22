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
if (isset($user)) {
	if ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) {
		?>
		<div class="nav1">
			<? echo group($ank2['id'])?> <a href="/info.php?id=<? echo $nak2['id']?>"><? echo $ank2['nick']?></a> <? echo online($ank2['id'])?> :<br />
			<? echo output_text($comment['msg'])?><br />
		</div>
		<form action='/user/us_guest/?user_id=<? echo $ank['id']?>' name='message' method='POST' enctype='multipart/form-data'>
			<?
			if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
			else {
				?>
				<? echo $tPanel?>Сообщение:<br />
				<textarea name="msg" id="textarea"><? echo input_value_text($_SESSION[$sess_var]['msg'])?></textarea><br />
				<?
			}
			?>
			<!--Файл (<? echo size_file(2*1048576)?>):<br />
			<input name='file' type='file'/><br />-->
			<input type="hidden" name="reply_id_user" value="<? echo $ank2['id']?>">
			<input type="hidden" name="reply_id_comment" value="<? echo $comment['id']?>">
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
?>
<div class="foot">
	<img src="/user/us_guest/images/back.png" alt=""> <a href="/user/us_guest/?user_id=<? echo $ank['id']?>">Назад</a><br />
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>