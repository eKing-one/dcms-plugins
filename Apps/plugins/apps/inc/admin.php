<?php
only_level(3);

if (isset($_GET['act']) && ($_GET['act'] == 'edit' || $_GET['act'] == 'delete')) {
	$ID = (isset($_GET['id_apps']) ? (int) $_GET['id_apps'] : 0);
	$apps = mysql_fetch_assoc(mysql_query("SELECT * FROM `apps` WHERE `id` = '$ID' LIMIT 1"));
}

if (isset($_GET['act']) && $_GET['act'] == 'delete' && isset($_SESSION['sid']) && isset($_GET['sid']) && $_SESSION['sid'] == $_GET['sid'] && isset($apps['id'])) {
	mysql_query("DELETE FROM `user_apps` WHERE `id_apps` = '$ID'");
	mysql_query("DELETE FROM `apps` WHERE `id` = '$ID' LIMIT 1");
	$_SESSION['message'] = __('Приложение успешно удалено');
	header('Location: ?func=admin');
	exit;
}

if (isset($_POST['name']) && isset($_POST['url']) && isset($_GET['act'])){
	$name = my_esc($_POST['name']);
	$opis = my_esc($_POST['opis']);
	$url = my_esc($_POST['url']);
	$ic_small = my_esc($_POST['icon_small']);
	$ic_big = my_esc($_POST['icon_big']);
	
	if (strlen2($name) > 128) { $err[] = __('Название слишком длинное'); }
	elseif (strlen2($name) < 2) { $err[] = __('Короткое название'); }
	
	if (strlen2($opis) > 512) { $err[] = __('Описание слишком длинное'); }
	elseif (strlen2($opis) < 2) { $err[] = __('Короткое описание'); }
	
	if (strlen2($url) > 128) { $err[] = __('Ссылка слишком длинная'); }
	elseif (strlen2($url) < 2) { $err[] = __('Короткая ссылка'); }
	
	if (!isset($err)){
		if ($_GET['act'] == 'add'){
			mysql_query("INSERT INTO `apps` (`name`, `opis`, `url`, `time`, `icon_small`, `icon_big`) values('$name', '$opis', '$url', '$time', '$ic_small', '$ic_big')");
			$_SESSION['message'] = __('Новое приложение успешно добавлено');			
		} elseif (isset($apps['id']) && $_GET['act'] == 'edit') {
			mysql_query("UPDATE `apps` SET `name` = '$name', `opis` = '$opis', `url` = '$url', `icon_small` = '$ic_small', `icon_big` = '$ic_big' WHERE `id` = '$ID' LIMIT 1");
			$_SESSION['message'] = __('Изменения успешно приняты');			
		}
		header('Location: ?func=admin');
		exit;
	}
}

$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = __('Онлайн игры');
include_once H . 'sys/inc/thead.php';
title();
aut(); 
err();

?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="/plugins/apps/"><?= __('Онлайн игры')?></a> | <a href="?func=admin&amp;act=add"><?= __('Добавить игру')?></a></div>
<?
if (isset($_GET['act'])) {
	
	if ($_GET['act'] == 'edit') {
		?>
		<div class="nav2">
		<b><?= text($apps['name'])?></b><br />
		<?= ($apps['icon_big'] ? '<img src="' . text($apps['icon_big']) . '" style="max-width: 200px;" /><br />' : '')?>
		<?= output_text($apps['opis'])?>
		</div>
		
		<form class="nav2" name="message" action="?func=admin&amp;act=edit&amp;sid=<?= $_SESSION['sid']?>&amp;id_apps=<?= $ID?>" method="post">
		<?= __('Название')?><br />
		<input name="name" type="text" value="<?= text($apps['name'])?>" /><br />

		<?= __('URL игры')?><br />
		<input name="url" type="text" value="<?= text($apps['url'])?>" /><br />
		
		<?= __('Описание игры')?><br />	
		<textarea name="opis" placeholder="<?= __('Опишите основной смысл игры..')?>"><?= text($apps['opis'])?></textarea><br />
		
		<?= __('URL маленькой иконки')?><br />
		<input name="icon_small" type="text" value="<?= text($apps['icon_small'])?>"/><br />
		
		<?= __('URL большой иконки')?><br />
		<input name="icon_big" type="text" value="<?= text($apps['icon_big'])?>" /><br />
		
		<input class="submit" type="submit" value="<?= __('Сохранить')?>" /> 
		</form>
		<?
	} elseif ($_GET['act'] == 'add') {
		
		?>
		<form class="nav2" name="message" action="?func=admin&amp;act=add&amp;sid=<?= $_SESSION['sid']?>" method="post">
		<?= __('Название')?><br />
		<input name="name" type="text" value="" /><br />

		<?= __('URL игры')?><br />
		<input name="url" type="text" value="" /><br />
		
		<?= __('Описание игры')?><br />	
		<textarea name="opis" placeholder="<?= __('Опишите основной смысл игры..')?>"></textarea><br />
		
		<?= __('URL маленькой иконки')?><br />
		<input name="icon_small" type="text" value=""/><br />
		
		<?= __('URL большой иконки')?><br />
		<input name="icon_big" type="text" value="" /><br />
		
		<input class="submit" type="submit" value="<?= __('Добавить')?>" />
		</form>
		
		<div class="mess">
		<span style="color: blue;"><?= __('Пример добавления игры')?>:</span><br />
		<span style="color: gray;"><?= __('Название')?>:</span> <span style="color: green;"><?= __('Крестики нолики')?></span><br />
		<span style="color: gray;"><?= __('URL игры')?>:</span> <span style="color: green;">/plugins/games/xo/</span><br />
		<span style="color: gray;"><?= __('Описание игры')?>:</span> <span style="color: green;"><?= __('Логическая игра между двумя противниками.')?></span><br />
		<span style="color: gray;"><?= __('URL маленькой иконки')?>:</span> <span style="color: green;">/style/icons/xo.gif</span><br />
		<span style="color: gray;"><?= __('URL большой иконки')?>:</span> <span style="color: green;">/style/icons/xo2.gif</span><br />
		</div>
		<?
	}
	
	?><div class="foot"><img src="/style/icons/str2.gif" alt="*"/> <a href="/plugins/apps/?func=admin"><?= __('Управление')?></a></div><?
	
} else {

	$k_post = mysql_result(mysql_query("SELECT COUNT(id) FROM `apps`"), 0);
	$k_page = k_page($k_post,$set['p_str']);
	$page = page($k_page);
	$start = ($set['p_str'] * $page) - $set['p_str'];

	?><table class="post"><?

	if ($k_post == 0)
	{
		?>
		<div class="mess">
		<?= __('В списке нет установленных игр')?>
		</div>
		<?
	}

	$q = mysql_query("SELECT * FROM `apps` ORDER BY `count` DESC LIMIT $start, $set[p_str]");

	while ($apps = mysql_fetch_assoc($q)) {
		
		?>
		<div class="nav2">
		<a href="?func=admin&amp;id_apps=<?= $apps['id']?>&amp;act=edit"><?= ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '')?> <?= text($apps['name'])?></a> [<a href="?func=admin&amp;id_apps=<?= $apps['id']?>&amp;sid=<?= $_SESSION['sid']?>&amp;act=delete"><img src="/style/icons/delete.gif" /> <?= __('удалить')?></a>]
		</div>
		<?
	}
	?>
	</table>

	<?
	if ($k_page > 1) {
		str('?func=admin&amp;', $k_page, $page);
	}
}	
?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="/plugins/apps/"><?= __('Онлайн игры')?></a> | <b><?= __('Управление')?></b></div>	
<?
