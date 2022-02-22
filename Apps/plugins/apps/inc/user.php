<?php
only_reg();
$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = '我的游戏';
include_once H . 'sys/inc/thead.php';
title();
aut();
err();
echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="?func=list">所有游戏</a> | <b>我的游戏。</b></div>';

$k_post = dbresult(dbquery("SELECT COUNT(id_apps) FROM `user_apps`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = ($set['p_str'] * $page) - $set['p_str'];

echo '<table class="post">';

if ($k_post == 0) {

	echo '<div class="mess">
			列表中没有安装的游戏
		</div>';
}

$q = dbquery("SELECT * FROM `user_apps` WHERE `id_user` = '$user[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");

while ($post = dbassoc($q)) {
	$apps = dbassoc(dbquery("SELECT * FROM `apps` WHERE `id` = '$post[id_apps]' LIMIT 1"));

	echo '<div class="nav2">
			<a href="?func=details&amp;id_apps=' . $apps['id'] . '">' . ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '') . ' ' . text($apps['name']) . '</a>
			<a href="?func=details&amp;id_apps=' . $apps['id'] . '&amp;sid=' . $_SESSION['sid'] . '&amp;act=delete"><img src="/style/icons/delete.gif" /></a>
		</div>';
}

echo '</table>';


if ($k_page > 1) {
	str('?func=user&amp;', $k_page, $page);
}

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="?func=list">所有游戏</a> | <b>我的游戏</b></div>';
