<?php
$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = '网络游戏';
include_once H . 'sys/inc/thead.php';
title();
aut();
err();


echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="/plugins/apps/">网络游戏</a> | <b>所有游戏</b></div>';

$k_post = dbresult(dbquery("SELECT COUNT(id) FROM `apps`"), 0);
$k_page = k_page($k_post, $set['p_str']);
$page = page($k_page);
$start = ($set['p_str'] * $page) - $set['p_str'];

echo '<table class="post">';

if ($k_post == 0) {

	echo '<div class="mess">
			列表中没有安装的游戏
		</div>';
}

$q = dbquery("SELECT * FROM `apps` ORDER BY `count` DESC LIMIT $start, $set[p_str]");

while ($apps = dbassoc($q)) {


	echo '<div class="nav2">';
	echo '<a href="?func=details&amp;id_apps=' . $apps['id'] . '">' . ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '') . ' ' . text($apps['name']) . '</a>
			(' . dbresult(dbquery("SELECT COUNT(id_apps) FROM `user_apps` WHERE `id_apps` = '$apps[id]'"), 0) . ' 人)
		</div> ';
}
echo '</table>';

if ($k_page > 1) {
	str('?func=list&amp;', $k_page, $page);
}

if ($user['level'] >= 3) {

	echo '<div class="foot"><img src="/style/icons/str.gif" alt="*" /> <a href="index.php?func=admin">管理</a></div>';
}

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="?">网络游戏</a> | <b>所有游戏</b></div>';
