<?
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE 
	`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`msg` like '%" . $search_text . "%' OR
	`msg` like '%" . translit($search_text) . "%' OR
	`msg` like '%" . retranslit($search_text) . "%'
	"), 0);
	
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];


$q = mysql_query("SELECT * FROM `notes` WHERE 
	`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`msg` like '%" . $search_text . "%' OR
	`msg` like '%" . translit($search_text) . "%' OR
	`msg` like '%" . retranslit($search_text) . "%'
	 LIMIT $start, $set[p_str]");

echo '<table class="post">';

if ($k_post == 0)
{	
	echo '<div class="mess">';	
	echo 'Нет результатов';	
	echo '</div>';
}


while ($post = mysql_fetch_assoc($q))
{
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;

	echo '<img src="/style/icons/dnev.png" alt="*"> ';
	echo '<a href="/plugins/notes/list.php?id=' . $post['id'] . '">' . text($post['name']) . '</a>';
	echo ' <span style="time">(' . vremja($post['time']) . ')</span>';

	$k_n = mysql_result(mysql_query("SELECT COUNT(*) FROM `notes` WHERE `id` = $post[id] AND `time` > '$ftime'",$db), 0);
	if ($k_n != 0)echo ' <img src="/style/icons/new.gif" alt="*">';
	
	echo '</div>';
}

echo '</table>';

// Вывод страниц
if ($k_page > 1)str("?search=people&amp;",$k_page,$page); 
?>