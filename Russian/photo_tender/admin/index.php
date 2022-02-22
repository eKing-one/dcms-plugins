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

// только для админов

	if ($user['level'] == 0)header("location: /index.php?");
	
// заголовок страницы
	
	$set['title'] = 'Фотоконкурсы : admin_panel'; 
	
// head	
	include_once '../../sys/inc/thead.php';
	
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
	<img src="/photo_tender/ico/info.png" alt="*"> <a href="/photo_tender/admin/list/?id=0"> Активные фотоконкурсы ('.$count.')</a></div>
	<div class="st_y">
	<img src="/photo_tender/ico/end.png" alt="*"> <a href="/photo_tender/admin/list/?id=1"> Завершенные фотоконкурсы ('.$count_end.')</a></div>
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/who.png" alt="*"> <a href="/photo_tender/admin/add/"> Создать новый фотоконкурс </a></div>
	<div class="block_y">
	<img src="/photo_tender/ico/settings.png" alt="*"> <a href="/photo_tender/admin/settings/"> Настройки </a></div>
	';
	


// foot
	include_once '../../sys/inc/tfoot.php';
?>