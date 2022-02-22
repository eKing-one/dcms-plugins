<?
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE 
	`id` <=> '" . $search_text . "' OR  
	`nick` like '%" . $search_text . "%' OR
	(`ank_icq` <=> '" . $search_text . "' AND `ank_icq` != '0') OR 
	(`ank_city` = '" . $search_text . "') OR 
	(`ip` <=> '" . ip2long($search_text) . "' AND `ip` != NULL) OR 
	`ank_mail` <=> '" . $search_text . "'
	"), 0);
	
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];


$q = mysql_query("SELECT * FROM `user` WHERE 
	`id` <=> '" . $search_text . "' OR  
	`nick` like '%" . $search_text . "%' OR
	(`ank_icq` <=> '" . $search_text . "' AND `ank_icq` != '0') OR 
	(`ank_city` = '" . $search_text . "') OR 
	(`ip` <=> '" . ip2long($search_text) . "' AND `ip` != NULL) OR 
	`ank_mail` <=> '" . $search_text . "'
	 ORDER BY `date_last` DESC LIMIT $start, $set[p_str]");

echo '<table class="post">';

if ($k_post == 0)
{	
	echo '<div class="mess">';	
	echo 'Нет результатов';	
	echo '</div>';
}


while ($ank = mysql_fetch_assoc($q))
{
	// Лесенка
	echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
	$num++;

	echo avatar($ank['id']) . group($ank['id']) . user::nick($ank['id']) ;
	
	echo medal($ank['id']) . online($ank['id']) . ' <br />';
	
	if (long2ip($ank['ip']) == $search_text)
	echo 'Найден по IP: <font style="color: ' . $color . ';">' . long2ip($ank['ip']) . '</font><br />';
	
	if ($ank['id'] == $search_text)
	echo 'Найден по ID: <font style="color: ' . $color . ';">' . intval($ank['id']) . '</font><br />';
	
	if ($ank['nick'] == $search_text)
	echo 'Найден по нику: <font style="color: ' . $color . ';">' . user::nick($ank['id'], 0) . '</font><br />';
	
	if ($ank['ank_city'] == $search_text)
	echo 'Найден по городу: <font style="color: ' . $color . ';">' . text($ank['ank_city']) . '</font><br />';
	
	if ($ank['ank_mail'] == $search_text)
	echo 'Найден по E-Mail: <font style="color: ' . $color . ';">' . text($ank['ank_mail']) . '</font><br />';
	
	echo '</div>';
}

echo '</table>';

// Вывод страниц
if ($k_page > 1)str("?search=people&amp;",$k_page,$page); 
?>