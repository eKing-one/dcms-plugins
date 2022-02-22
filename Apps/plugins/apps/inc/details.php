<?php
only_reg();
$ID = (isset($_GET['id_apps']) ? (int) $_GET['id_apps'] : 0);

$apps = dbassoc(dbquery("SELECT * FROM `apps` WHERE `id` = '$ID' LIMIT 1"));

if (!$apps['id'] || $ID == 0) {
	header('Location: ?func=list');
	exit;
}

if (isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid']) {
	if (isset($_GET['act']) && $_GET['act'] == 'delete') {
		dbquery("DELETE FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID' LIMIT 1");
		dbquery("UPDATE `apps` SET `count` = `count` - '1' WHERE `id` = '$ID' LIMIT 1");
		$_SESSION['message'] = '应用程序已从你的列表中删除';
		header('Location: ?func=list&user=' . $user['id']);
		exit;
	}
}

if (isset($_GET['enter']) && isset($user) && isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid'] && dbresult(dbquery("SELECT COUNT(*) FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID'"), 0) == 0) {
	dbquery("INSERT INTO `user_apps` (`id_user`, `id_apps`, `time`) values('$user[id]', '$ID', '$time')");
	dbquery("UPDATE `apps` SET `count` = `count` + '1' WHERE `id` = '$ID' LIMIT 1");
	header('Location: ' . text($apps['url']));
	exit;
} elseif (isset($user) && dbresult(dbquery("SELECT COUNT(*) FROM `user_apps` WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID'"), 0) == 1) {
	dbquery("UPDATE `user_apps` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `id_apps` = '$ID' LIMIT 1");
	header('Location: ' . text($apps['url']));
	exit;
}


$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = text($apps['name']);
include_once H . 'sys/inc/thead.php';
title();
aut();
err();

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="/plugins/apps/">网络游戏</a> | <b>' . text($apps['name']) . '</b></div>';
echo '<div class="nav2">
	<b>' . text($apps['name']) . '</b><br />';
echo '' . ($apps['icon_big'] ? '<img src="' . text($apps['icon_big']) . '" style="max-width: 200px;" /><br />' : '') . '
	' . output_text($apps['opis']) . '';
echo '</div>';

if (isset($user)) {
	echo '<div class="nav1">
		<a href="?func=details&amp;id_apps=' . $apps['id'] . '&amp;sid=' . $_SESSION['sid'] . '&amp;enter"><img src="/style/icons/str.gif" /> 参加比赛</a>
	</div>';
}

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="/plugins/apps/">网络游戏</a> | <b>' . text($apps['name']) . '</b></div>';
