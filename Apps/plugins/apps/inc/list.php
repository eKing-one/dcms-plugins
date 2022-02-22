<?
$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = __('Онлайн игры');
include_once H . 'sys/inc/thead.php';
title();
aut(); 
err();

?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="/plugins/apps/"><?= __('Онлайн игры')?></a> | <b><?= __('Все игры')?></b></div>
<?
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
	<a href="?func=details&amp;id_apps=<?= $apps['id']?>"><?= ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '')?> <?= text($apps['name'])?></a>  
	(<?= mysql_result(mysql_query("SELECT COUNT(id_apps) FROM `user_apps` WHERE `id_apps` = '$apps[id]'"), 0)?> <?= __('чел')?>)
	</div>
	<?
}
?>
</table>
<?
if ($k_page > 1) {
	str('?func=list&amp;', $k_page, $page);
}

if ($user['level'] >= 3)
{
	?>
	<div class="foot"><img src="/style/icons/str.gif" alt="*"/> <a href="index.php?func=admin"><?= __('Управление')?></a></div>
	<?	
}
?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="?"><?= __('Онлайн игры')?></a> | <b><?= __('Все игры')?></b></div>