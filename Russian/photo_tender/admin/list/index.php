<?
/**
 * @ PACKAGE  =   DCMS-SOCIAL
 * @ AUTHOR   =   DARIK 
 */
 
	include_once '../../../sys/inc/start.php';include_once '../../../sys/inc/compress.php';include_once '../../../sys/inc/sess.php';
	include_once '../../../sys/inc/home.php';include_once '../../../sys/inc/settings.php';include_once '../../../sys/inc/db_connect.php';
	include_once '../../../sys/inc/ipua.php';include_once '../../../sys/inc/fnc.php';include_once '../../../sys/inc/user.php';

// Только для пользователей

	if (!isset($user))header("location: /index.php?");
	
// только для админов

	if ($user['level'] == 0)header("location: /index.php?");		
	
// ID

	define ('id', isset( $_GET['id'] ) ? abs(intval($_GET['id'])) : 0);	

// if id

	if (id == 0 || id == 1 ){

// заголовок страницы

	$set['title'] = 'Фотоконкурсы'; 
	
// head	
	include_once '../../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// list

	$list =  array('0','1');
	$text = array('Активные фотоконкурсы',' Завершенные фотоконкурсы');
	
	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender` WHERE `level` = '".$list[id]."'"), 0);
	
	echo'
	<div class="block_y">
	<div class="st_y">
	<img src="/photo_tender/ico/info.png" alt="*"> '.$text[id].' ('.$count.')</div>';
	
	$k_page = k_page($count, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	
	$q = mysql_query("SELECT * FROM `photo_tender` WHERE `level` = '".$list[id]."' ORDER BY time DESC LIMIT $start, $set[p_str]");
	while ($act = mysql_fetch_assoc($q))
	{
	
	$name = htmlspecialchars ($act['name']);
	$image = htmlspecialchars ($act['image']);
	
	echo'
	<div class="s_y">
	<a href="/photo_tender/admin/view/?id='.$act['id'].'">
	<div class="fot_k_2">
	<img src="/photo_tender/image/'.($image == null ? 'no_image.jpg':''.$image.'' ).'" alt="*">
	</div>
	<div class="fot_k_3">
	<span class="fot_k_4">Конкурс</span><br>
	'.$name.'<br></div>
	</a>
	</div>
	';
	
	}
	
	if ($count == 0) echo'<div class="s_y">Нет '.($list[id] == 0 ? 'активных':'завершеных').' фотоконкурсов!</div>';
	
	echo '
	</div>
	<div class="block_y"><img src="/photo_tender/ico/settings.png" alt="*"> <a href="/photo_tender/admin/"> Админка </a>
	</div>';
	
	if ($k_page > 1)str("/photo_tender/admin/list/?id=".(id == 0 ? '0':'1')."&", $k_page, $page); // Вывод страниц	
	
	
	}else{ $_SESSION['message'] = 'Ошибка категория не существует.';header("location: /photo_tender/admin/?");}

// foot
	
	include_once '../../../sys/inc/tfoot.php';
?>