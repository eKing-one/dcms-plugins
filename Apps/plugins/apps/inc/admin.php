<?php
only_level(3);

if (isset($_GET['act']) && ($_GET['act'] == 'edit' || $_GET['act'] == 'delete')) {
	$ID = (isset($_GET['id_apps']) ? (int) $_GET['id_apps'] : 0);
	$apps = dbassoc(dbquery("SELECT * FROM `apps` WHERE `id` = '$ID' LIMIT 1"));
}

if (isset($_GET['act']) && $_GET['act'] == 'delete' && isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid'] && isset($apps['id'])) {
	dbquery("DELETE FROM `user_apps` WHERE `id_apps` = '$ID'");
	dbquery("DELETE FROM `apps` WHERE `id` = '$ID' LIMIT 1");
	$_SESSION['message'] = '应用程序已成功卸载';
	header('Location: ?func=admin');
	exit;
}

if (isset($_POST['name']) && isset($_POST['url']) && isset($_GET['act'])) {
	$name = my_esc($_POST['name']);
	$opis = my_esc($_POST['opis']);
	$url = my_esc($_POST['url']);
	$ic_small = my_esc($_POST['icon_small']);
	$ic_big = my_esc($_POST['icon_big']);

	if (strlen2($name) > 128) {
		$err[] = '标题太长';
	} elseif (strlen2($name) < 2) {
		$err[] = '短标题';
	}

	if (strlen2($opis) > 512) {
		$err[] = '描述太长';
	} elseif (strlen2($opis) < 2) {
		$err[] = '简短的描述';
	}

	if (strlen2($url) > 128) {
		$err[] = '链接过长';
	} elseif (strlen2($url) < 2) {
		$err[] = '短链路';
	}

	if (!isset($err)) {
		if ($_GET['act'] == 'add') {
			dbquery("INSERT INTO `apps` (`name`, `opis`, `url`, `time`, `icon_small`, `icon_big`) values('$name', '$opis', '$url', '$time', '$ic_small', '$ic_big')");
			$_SESSION['message'] = '成功添加新应用程序';
		} elseif (isset($apps['id']) && $_GET['act'] == 'edit') {
			dbquery("UPDATE `apps` SET `name` = '$name', `opis` = '$opis', `url` = '$url', `icon_small` = '$ic_small', `icon_big` = '$ic_big' WHERE `id` = '$ID' LIMIT 1");
			$_SESSION['message'] = '已成功接受更改';
		}
		header('Location: ?func=admin');
		exit;
	}
}

$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = '网络游戏';
include_once H . 'sys/inc/thead.php';
title();
aut();
err();

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="/plugins/apps/">网络游戏</a> | <a href="?func=admin&amp;act=add">添加游戏</a></div>';

if (isset($_GET['act'])) {

	if ($_GET['act'] == 'edit') {

		echo '<div class="nav2">';
		echo '<b>' . text($apps['name']) . '</b><br />';
		echo ($apps['icon_big'] ? '<img src="' . text($apps['icon_big']) . '" style="max-width: 200px;" /><br />' : '') . output_text($apps['opis']);
		echo '</div>';

		echo '<form class="nav2" name="message" action="?func=admin&amp;act=edit&amp;sid=' . $_SESSION['sid'] . '&amp;id_apps=<' . $ID . '" method="post">
标题<br />
<input name="name" type="text" value="' . text($apps['name']) . '" /><br />

游戏网址<br />
<input name="url" type="text" value="' . text($apps['url']) . '" /><br />

游戏描述<br />
<textarea name="opis" placeholder="描述游戏的基本含义。">' . text($apps['opis']) . '</textarea><br />

小图标的网址<br />
<input name="icon_small" type="text" value="' . text($apps['icon_small']) . '" /><br />

大图标 URL<br />
<input name="icon_big" type="text" value="' . text($apps['icon_big']) . '" /><br />

<input class="submit" type="submit" value="保存" />
</form>';
	} elseif ($_GET['act'] == 'add') {


		echo '<form class="nav2" name="message" action="?func=admin&amp;act=add&amp;sid=' . $_SESSION['sid'] . '" method="post">
标题<br />
<input name="name" type="text" value="" /><br />

游戏网址<br />
<input name="url" type="text" value="" /><br />

游戏描述<br />
<textarea name="opis" placeholder="描述游戏的基本含义.."></textarea><br />

小图标的网址<br />
<input name="icon_small" type="text" value="" /><br />

大图标 URL<br />
<input name="icon_big" type="text" value="" /><br />

<input class="submit" type="submit" value="增加" />
</form>';

		echo '<div class="mess">
			<span style="color: blue;">添加游戏示例：</span><br />
			<span style="color: gray;">标题:</span> <span style="color: green;">文字</span><br />
			<span style="color: gray;">游戏网址:</span> <span style="color: green;">/plugins/games/xo/</span><br />
			<span style="color: gray;">游戏描述:</span> <span style="color: green;">两个对手之间的逻辑博弈。</span><br />
			<span style="color: gray;">小图标的网址:</span> <span style="color: green;">/style/icons/xo.gif</span><br />
			<span style="color: gray;">大图标 URL:</span> <span style="color: green;">/style/icons/xo2.gif</span><br />
			</div>';
	}

	echo '<div class="foot"><img src="/style/icons/str2.gif" alt="*" /> <a href="/plugins/apps/?func=admin">管理</a></div>';
} else {

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


		echo '<div class="nav2">
<a href="?func=admin&amp;id_apps=' . $apps['id'] . '&amp;act=edit">' . ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '') . ' ' . text($apps['name']) . '</a> [<a href="?func=admin&amp;id_apps=' . $apps['id'] . '&amp;sid=' . $_SESSION['sid'] . '&amp;act=delete"><img src="/style/icons/delete.gif" /> 删除</a>]
</div>';
	}

	echo '</table>';


	if ($k_page > 1) {
		str('?func=admin&amp;', $k_page, $page);
	}
}

echo '<div class="foot"><img src="/style/icons/games.png" alt="*" /> <a href="/plugins/apps/">网络游戏</a> | <b>管理</b></div>';
