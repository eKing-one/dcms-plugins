<?
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_files` WHERE 
	(`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`opis` like '%" . $search_text . "%' OR
	`opis` like '%" . translit($search_text) . "%' OR
	`opis` like '%" . retranslit($search_text) . "%') AND (`ras` = 'mp3')
	"), 0);
	
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];


$q = mysql_query("SELECT * FROM `obmennik_files` WHERE 
	(`name` like '%" . $search_text . "%' OR
	`name` like '%" . translit($search_text) . "%' OR
	`name` like '%" . retranslit($search_text) . "%' OR
	`opis` like '%" . $search_text . "%' OR
	`opis` like '%" . translit($search_text) . "%' OR
	`opis` like '%" . retranslit($search_text) . "%') AND (`ras` = 'mp3')
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
	$k_p = mysql_result(mysql_query("SELECT COUNT(*) FROM `obmennik_komm` WHERE `id_file` = '$post[id]'"),0);
	$ras = $post['ras'];

	$file = H . "sys/obmen/files/$post[id].dat";
	$name = $post['name'];
	$size = $post['size'];
	$dir_id = mysql_fetch_array(mysql_query("SELECT * FROM `obmennik_dir` WHERE `id` = '$post[id_dir]' LIMIT 1"));
	
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;

	include H.'obmen/inc/icon48.php';
	
	if (is_file(H.'style/themes/' . $set['set_them'] . '/loads/14/' . $ras . '.png'))
	echo "<img src='/style/themes/$set[set_them]/loads/14/$ras.png' alt='$ras' /> \n";
	else 
	echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' /> \n";
	
	if ($set['echo_rassh'] == 1)
	$ras = $post['ras'];
	else 
	$ras = NULL;
	echo '<a href="/obmen' . $dir_id['dir'] . $post['id'] . '.' . $post['ras'] . '?showinfo"><b>' . text($post['name']) . '.' . $ras . '</b></a> (' . size_file($post['size']) . ')<br />';
	
	echo '<a href="/obmen' . $dir_id['dir'] . $post['id'] . '.'.$post['ras'] . '?showinfo&amp;komm">Комментарии</a> (' . $k_p . ')<br />';
	
	echo '</div>';
}

echo '</table>';

// Вывод страниц
if ($k_page > 1)str("?search=obmen&amp;",$k_page,$page); 
?>