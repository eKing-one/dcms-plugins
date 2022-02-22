<?
only_reg();
$_SESSION['sid'] = mt_rand(000, 999);
$set['title'] = __('Мои игры');
include_once H . 'sys/inc/thead.php';
title();
aut(); 
err();

?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="?func=list"><?= __('Все игры')?></a> | <b><?= __('Мои игры')?></b></div>
<?
$k_post = mysql_result(mysql_query("SELECT COUNT(id_apps) FROM `user_apps`"), 0);
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

$q = mysql_query("SELECT * FROM `user_apps` WHERE `id_user` = '$user[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");

while ($post = mysql_fetch_assoc($q)) {
	$apps = mysql_fetch_assoc(mysql_query("SELECT * FROM `apps` WHERE `id` = '$post[id_apps]' LIMIT 1"));
	?>
	<div class="nav2">
	<a href="?func=details&amp;id_apps=<?= $apps['id']?>"><?= ($apps['icon_small'] ? '<img src="' . text($apps['icon_small']) . '" />' : '')?> <?= text($apps['name'])?></a> 
	<a href="?func=details&amp;id_apps=<?= $apps['id']?>&amp;sid=<?= $_SESSION['sid']?>&amp;act=delete"><img src="/style/icons/delete.gif" /></a> 
	</div>
	<?
}
?>
</table>

<?
if ($k_page > 1) {
	str('?func=user&amp;', $k_page, $page);
}
?>
<div class="foot"><img src="/style/icons/games.png" alt="*"/> <a href="?func=list"><?= __('Все игры')?></a> | <b><?= __('Мои игры')?></b></div>