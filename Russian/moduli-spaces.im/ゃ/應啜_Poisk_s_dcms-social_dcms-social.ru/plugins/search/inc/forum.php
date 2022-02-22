<?
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE 
	`name` like '%" . $search_text . "%' OR
	`text` like '%" . $search_text . "%'
	"), 0);
	
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];


$q = mysql_query("SELECT * FROM `forum_t` WHERE 
	`name` like '%" . $search_text . "%' OR
	`text` like '%" . $search_text . "%'
	 LIMIT $start, $set[p_str]");

echo '<table class="post">';

if ($k_post == 0)
{	
	echo '<div class="mess">';	
	echo 'Нет результатов';	
	echo '</div>';
}


while ($them = mysql_fetch_assoc($q))
{
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;
	
	// Определение подфорума
	$forum = mysql_fetch_array(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '$them[id_forum]' LIMIT 1"));
	
	// Определение раздела
	$razdel = mysql_fetch_array(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '$them[id_razdel]' LIMIT 1"));
	
	// Иконка темы
	echo '<img src="/style/themes/' . $set['set_them'] . '/forum/14/them_' . $them['up'] . $them['close'] . '.png" alt="" /> ';
	
	// Ссылка на тему
	echo '<a href="/forum/' . $forum['id'] . '/' . $razdel['id'] . '/' . $them['id'] . '/">' . text($them['name']) . '</a> 
	<a href="/forum/' . $forum['id'] . '/' . $razdel['id'] . '/' . $them['id'] . '/?page=' . $pageEnd . '">
	(' . mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_them` = '$them[id]'"),0) . ')</a><br/>';
	
	// Подфорум и раздел
	echo '<a href="/forum/' . $forum['id'] . '/">' . text($forum['name']) . '</a> &gt; <a href="/forum/' . $forum['id'] . '/' . $razdel['id'] . '/">' . text($razdel['name']) . '</a><br />';
	
	// Автор темы
	$ank = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $them[id_user] LIMIT 1"));
	echo 'Автор: <a href="/info.php?id=' . $ank['id'] . '">' . $ank['nick'] . '</a> (' . vremja($them['time_create']) . ')<br />';

	// Последний пост 
	$post = mysql_fetch_array(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_razdel` = '$razdel[id]' AND `id_forum` = '$forum[id]' ORDER BY `time` DESC LIMIT 1"));
	
	// Автор последнего поста
	$ank2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
	echo 'Посл.: <a href="/info.php?id=' . $ank2['id'] . '">' . $ank2['nick'] . '</a> (' . vremja($post['time']) . ')<br />';
	
	echo '</div>';
}

echo '</table>';

// Вывод страниц
if ($k_page > 1)str("?search=forum&amp;",$k_page,$page); 
?>