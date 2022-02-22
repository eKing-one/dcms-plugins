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

$set['title'] = __('Монитор переклички');
include_once H.'sys/inc/thead.php';
title();
aut();

?>
<div class="foot">
<img src="/style/icons/str2.gif" /> <a href="index.php"><?= __('Перекличка')?></a> | <b><?= __('Монитор')?></b>
</div>
<?

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `roller`  WHERE `days` > '1' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page-$set['p_str'];

$q = mysql_query("SELECT * FROM `roller` WHERE `days` > '1' ORDER BY `days` DESC LIMIT $start, $set[p_str]");

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo 'Нет участников';
	echo '</div>';
}

while ($post = mysql_fetch_assoc($q))
{
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;

	echo user::avatar($post['id_user'], 0) . user::nick($post['id_user'], 1, 1, 1) . ' <br />';
	
	echo '<div style="color: #4e4e4e">Дней [' . ($post['days'] - 1) . ' из ' . $set['roller_days'] . ']</div>'; 

	echo '</div>';
}
echo '</table>';

if ($k_page>1)str('?',$k_page,$page); 

?>
<div class="foot">
<img src="/style/icons/str2.gif" /> <a href="index.php"><?= __('Перекличка')?></a> | <b><?= __('Монитор')?></b>
</div>
<?

include_once H.'sys/inc/tfoot.php';
?>