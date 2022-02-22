<?
only_reg();
$ID = (isset($_GET['id_apps']) ? (int) $_GET['id_apps'] : 0);

$apps = mysql_fetch_assoc(mysql_query("SELECT * FROM `apps` WHERE `id` = '$ID' LIMIT 1"));

if (!$apps['id'] || $ID == 0) {
	header('Location: ?func=list');
	exit;
}

if (isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid']) {
	if (isset($_GET['act']) && $_GET['act'] == 'delete') {
		mysql_query("DELETE FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID' LIMIT 1");
		mysql_query("UPDATE `apps` SET `count` = `count` - '1' WHERE `id` = '$ID' LIMIT 1");
		$_SESSION['message'] = __('Приложение удалено из вашего списка');
		header('Location: ?func=list&user=' . $user['id']);
		exit;
	}
}

if (isset($_GET['enter']) && isset($user) && isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID'"),0) == 0) {
	mysql_query("INSERT INTO `user_apps` (`id_user`, `id_apps`, `time`) values('$user[id]', '$ID', '$time')");
	mysql_query("UPDATE `apps` SET `count` = `count` + '1' WHERE `id` = '$ID' LIMIT 1");
	header('Location: ' . text($apps['url']));
	exit;
} elseif (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID'"),0) == 1){
	mysql_query("UPDATE `user_apps` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID' LIMIT 1");
	header('Location: ' . text($apps['url']));
	exit;
}


$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = text($apps['name']);
include_once H . 'sys/inc/thead.php';
title();
aut(); 
err();

?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="/plugins/apps/"><?= __('Онлайн игры')?></a> | <b><?= text($apps['name'])?></b></div>

<div class="nav2">
<b><?= text($apps['name'])?></b><br />
<?= ($apps['icon_big'] ? '<img src="' . text($apps['icon_big']) . '" style="max-width: 200px;" /><br />' : '')?>
<?= output_text($apps['opis'])?>
</div>

<? if (isset($user)) { ?>
<div class="nav1">
<a href="?func=details&amp;id_apps=<?= $apps['id']?>&amp;sid=<?= $_SESSION['sid']?>&amp;enter"><img src="/style/icons/str.gif" /> <?= __('Войти в игру')?></a>
</div>
<? } ?>

<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="/plugins/apps/"><?= __('Онлайн игры')?></a> | <b><?= text($apps['name'])?></b></div>