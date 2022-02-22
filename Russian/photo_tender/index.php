<?
/**
 * @ PACKAGE  =   DCMS-SOCIAL
 * @ AUTHOR   =   DARIK 
 */

	include_once '../sys/inc/start.php';include_once '../sys/inc/compress.php';include_once '../sys/inc/sess.php';
	include_once '../sys/inc/home.php';include_once '../sys/inc/settings.php';include_once '../sys/inc/db_connect.php';
	include_once '../sys/inc/ipua.php';include_once '../sys/inc/fnc.php';include_once '../sys/inc/user.php';
	
// Только для пользователей

	if (!isset($user))header("location: /index.php?");

// Только для пользователей

	if (!isset($user))header("location: /index.php?");
	
// заголовок страницы
	$set['title'] = 'Фотоконкурсы'; 
	
// head	
	include_once '../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';	
	
	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender` WHERE `level` = 0"), 0);
	$count_end = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender` WHERE `level` = 1"), 0);
	
	echo'
	<div class="block_y">
	<div class="st_y">
	<img src="ico/info.png" alt="*"> <a href="/photo_tender/list/?id=0"> Активные фотоконкурсы ('.$count.')</a></div>';
	

	$q = mysql_query("SELECT * FROM `photo_tender` WHERE `level` = 0  ORDER BY time DESC LIMIT 3");
	while ($act = mysql_fetch_assoc($q))
	{
	$name = htmlspecialchars ($act['name']);
	$image = htmlspecialchars ($act['image']);
	
	echo'
	<div class="s_y">
	<a href="/photo_tender/view/?id='.$act['id'].'">
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

	echo '
	<div class="st_y">
	<img src="ico/end.png" alt="*"> <a href="/photo_tender/list/?id=1"> Завершенные фотоконкурсы ('.$count_end.')</a></div>
	</div>
	';
	if ($user['level'] > 0) echo'<div class="block_y"><img src="/photo_tender/ico/settings.png" alt="*"> <a href="/photo_tender/admin/"> Админка </a></div>';

	include_once '../sys/inc/tfoot.php';
?>