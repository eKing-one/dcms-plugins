<?
/**
 * @ PACKAGE  =   DCMS-SOCIAL
 * @ AUTHOR   =   DARIK 
 */
 
	include_once '../../sys/inc/start.php';include_once '../../sys/inc/compress.php';include_once '../../sys/inc/sess.php';
	include_once '../../sys/inc/home.php';include_once '../../sys/inc/settings.php';include_once '../../sys/inc/db_connect.php';
	include_once '../../sys/inc/ipua.php';include_once '../../sys/inc/fnc.php';include_once '../../sys/inc/user.php';

// Только для пользователей

	if (!isset($user))header("location: /index.php?");
	
// ID

	define ('id', isset( $_GET['id'] ) ? abs(intval($_GET['id'])) : 0);	

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	
	
	$name = htmlspecialchars ($act['name']);
		
	$level = ''.($act['level'] == 0 ? 'Активный':'').' '.($act['level'] == 1 ? 'Завершен':'').'';
	
	$image = htmlspecialchars ($act['image']);
	
// если только существует 	
	
	if (!empty($act)) {

// заголовок страницы

	$set['title'] = 'Фото участников с конкурса '.$name.''; 
	
// head	
	include_once '../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

	echo'
	
	<div class="block_y">
	
	<div class="s_y">
	<div class="fot_k_2">
	<img src="/photo_tender/image/'.($image == null ? 'no_image.jpg':''.$image.'' ).'" alt="*">
	</div>
	<div class="fot_k_3">
	<span class="fot_k_4">Конкурс</span>
	<span class="fot_k_6">'.$level.'</span>
	<br>
	'.$name.'<br></div>
	</div>
	<div class="st_y"><img src="/photo_tender/ico/info.png" alt="*"> Фото всех участников:</div>
	';
	
	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender_user` WHERE `tender` = ".$act['id'].""), 0);

	$k_page = k_page($count, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	
	$q = mysql_query("SELECT * FROM `photo_tender_user` WHERE `tender` = '".$act['id']."' ORDER BY count DESC LIMIT $start, $set[p_str]");
	while ($tender = mysql_fetch_assoc($q))
	{
	$image = htmlspecialchars ($tender['image']);
	$ank = get_user($tender['user']);
	
	echo'
	<div class="s_y  fot_k">
	<div class="fot_k_2">
	<a href="/photo_tender/photo/view.php?id='.$tender['id'].'">
	'.($tender['mod'] == 1  && $user['level'] == 0 || $tender['closed'] == 1 && $user['level'] == 0 ? '<img src="/photo_tender/image/no_image.jpg" alt="*">':'<img src="/photo_tender/image/user/'.$image.'" alt="*">').'
	</a></div>
	<div class="fot_k_3">
	'.($tender['lider'] > 0 ? '
	<span class="fot_k_5">
	<img src="/photo_tender/ico/
	'.($tender['lider'] == 1 ? 'win':'').'
	'.($tender['lider'] == 2 ? 'win2':'').'
	'.($tender['lider'] == 3 ? 'win3':'').'.png" alt="*">
	</span>
	':'').'
	'.group($ank['id']) . user::nick($ank['id']).medal($ank['id']) . online($ank['id']) . '
	'.($tender['closed'] == 1 ? '<span style="color:red;"> - Заблокировано</span>':'').'
	'.($tender['mod'] == 1 ? '<span style="color:red;"> - На модерации</span>':'').'
	</br>
	
	<img src="/photo_tender/ico/top.png" alt="*">'.$tender['count'].'</span> |
	<img src="/photo_tender/ico/like.gif" alt="*">'.$tender['like'].'</span> |
	<img src="/photo_tender/ico/dlike.gif" alt="*">'.$tender['dlike'].'</span>
	
	</div>
	'.($tender['mod'] == 0 ? '
	'.($tender['closed'] == 0 ? '
	'.($act['level'] == 0 ? '
	'.($user['id'] != $tender['user'] ? '
	</br>
	<div class="st2_y">
	<a href="/photo_tender/photo/golos.php?id='.$tender['id'].'"> <img src="/photo_tender/ico/top.png" alt="*"> Проголосовать</a> |
	<a href="/photo_tender/photo/like.php?id='.$tender['id'].'"> <img src="/photo_tender/ico/like.gif" alt="*"> Лайк</a> |
	<a href="/photo_tender/photo/dlike.php?id='.$tender['id'].'"> <img src="/photo_tender/ico/dlike.gif" alt="*"> Дизлайк</a>
	</div>
	':'').'':'').'':'').'':'').'
	</div>
	';
		
	}	
	
	if ($count == 0) echo'<div class="s_y">Никто не добавил фотографию! Будьте первыми ;)</div>';
	
	if ($k_page > 1)str("/photo_tender/photo/list.php?id=".$act['id']."&", $k_page, $page); // Вывод страниц	
	
	echo'
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/view/?id='.$act['id'].'"> Назад</a></div>
	';
	
	
	}else{ header("location: /photo_tender/?");}

// foot
	
	include_once '../../sys/inc/tfoot.php';
?>