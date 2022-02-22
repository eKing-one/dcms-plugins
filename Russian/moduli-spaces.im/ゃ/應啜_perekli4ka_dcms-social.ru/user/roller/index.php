<?
include_once $_SERVER['DOCUMENT_ROOT'].'/sys/inc/home.php';
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
only_reg();

if ($set['roller_status'] == 0 || !isset($roller)) {
	header('Location: /index.php');
	exit;
}

if (isset($_GET['roller']) && $roller['time'] < $time && $set['roller_days'] >= $roller['days']) {
	mysql_query("UPDATE `roller` SET `time` = '" . ($ftime + $roller_num) . "', `days` = `days` + '1' WHERE `id_user` = '$user[id]' LIMIT 1");
	
	if ($roller['days'] + 1 > $set['roller_days']){
		
		mysql_query("UPDATE `user` SET `balls` = `balls` + '" . $set['roller_balls'] . "', `money` = `money` + '" . $set['roller_money'] . "', `rating` = `rating` + '" . $set['roller_rating'] . "' WHERE `id` = '$user[id]' LIMIT 1");
		
		// Сообщение о выплатах
		$msg = 'Уважаемый [b]' . user::nick($user['id'], 0) . '[/b] вы отмечались [b]' . $set['roller_days'] . ' дн' . ($set['roller_days'] == 3 ? 'я' : 'ей') . '[/b] подряд, и в награду получаете ' . ($set['roller_balls'] > 0 ? ' [blue]' . $set['roller_balls'] . ' баллов[/blue] ' : '') .
	($set['roller_money'] > 0 ? ' [red]' . $set['roller_money'] . ' ' . $sMonet[0] . '[/red] ' : '') .
	($set['roller_rating'] > 0 ? ' [green]' . $set['roller_rating'] . '% рейтинга[/green] ' : '') . '. :) ';
		
		// Отправляем
		mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$user[id]', '".my_esc($msg)."', '$time')");
		
	}
	
	$_SESSION['message'] = __('Перекличка прошла успешно');
	header('Location: ?');
	exit;
}
	
$set['title'] = __('Перекличка');
include_once H.'sys/inc/thead.php';

title();
aut();

?>
<div class="foot">
<?= user::avatar($user['id'], 2) . user::nick($user['id'])?> | <b><?= __('Перекличка')?></b>
</div>

<style>
a.roller {
	padding: 3px;
	margin: 1px;
	background-color: #31b300;
	border: 1px solid #1c6204;
	border-radius: 5px;
	-moz-border-radius: 5px;
	color:#ffffff;
	
}
</style>

<div class="mess" style="min-height: 60px;">
<img src="_images/start.png" style="height: 50px; float: left;" /> <b><?= user::nick($user['id'], 0)?></b>, 
заходи на сайт <b><?= $set['roller_days']?> дн<?= ($set['roller_days'] == 3 ? 'я' : 'ей')?></b> подряд, 
тебе будет открываться эта страничка, жми на кнопку "Отметится", отметившись у каждого дня, ты получиш вознаграждение.
</div>

<?
for ($i = 1; $set['roller_days'] >= $i; $i++) {
	?>
	<div class="<?= ($i % 2 ? "nav1" : "nav2")?>">
	<img src="_images/<?= ($roller['days'] > $i ? $i : 0)?>.png" style="height: 40px;" /> 
	
	<b style="<?= ($roller['days'] > $i ? 'color: green;' : 'color: gray;')?>"><?= $i?> день</b> 
	
	<?= ($roller['days'] > $i ? '<span class="on"> <b>&radic;</b> Готово</span>' : '')?>
	
	<?= ($roller['days'] < $i ? '<span class="off"> <b>&times;</b> в ожидании...</span> ' : '')?>
	
	<?= ($roller['days'] == $i && $roller['time'] > $time ? '<span style="color: blue;"> <b>&sim;</b> отметка завтра..</span> ' : '')?>
	
	<?= ($roller['days'] == $i && $roller['time'] < $time ? ' <a class="roller" href="?roller">Отметится</a> ' : '')?>

	</div>
	<?
}
?>
<div class="mess" style="min-height: 60px;">
<img src="_images/finish.png" style="height: 50px; float: left;" /> 
<?
if ($set['roller_days'] >= $roller['days']) {
	echo 'Вознаграждение по окончании переклички:<br />' 
	. ($set['roller_balls'] > 0 ? ' [<b style="color: blue;">' . $set['roller_balls'] . ' баллов</b>] ' : '') .
	($set['roller_money'] > 0 ? ' [<b style="color: red;">' . $set['roller_money'] . ' ' . $sMonet[0] . '</b>] ' : '') .
	($set['roller_rating'] > 0 ? ' [<b style="color: green;">' . $set['roller_rating'] . '% рейтинга</b>] ' : '');
} elseif ($set['roller_days'] < $roller['days']) {
	echo 'Вы успешно закончили перекличку, и получили ' 
	. ($set['roller_balls'] > 0 ? ' [<b style="color: blue;">' . $set['roller_balls'] . ' баллов</b>] ' : '') .
	($set['roller_money'] > 0 ? ' [<b style="color: red;">' . $set['roller_money'] . ' ' . $sMonet[0] . '</b>] ' : '') .
	($set['roller_rating'] > 0 ? ' [<b style="color: green;">' . $set['roller_rating'] . '% рейтинга</b>] ' : '');
}
?>
</div>

<div class="foot">
<img src="_images/users.png" /> <a href="top.php">Участники</a> (<?= mysql_result(mysql_query("SELECT COUNT(*) FROM `roller`  WHERE `days` > '1' "),0)?>)
</div>

<div class="foot">
<?= user::avatar($user['id'], 2) . user::nick($user['id'])?> | <b><?= __('Перекличка')?></b>
</div>
<?

include_once H.'sys/inc/tfoot.php';
?>