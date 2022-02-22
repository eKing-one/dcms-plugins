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

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender_user` WHERE `id` = '".id."' LIMIT 1"));	
	$tender = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".$act['tender']."' LIMIT 1"));	

	$image = htmlspecialchars ($act['image']);
	$ank = get_user($act['user']);
	
// если только существует 	
	
	if (!empty($act)) {

// заголовок страницы

	$set['title'] = 'Фото с конкурса '.$tender['name'].''; 
	
// head	
	include_once '../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// view

	echo'
	<div class="block_y">
	<div class="s_y">
	'.($act['mod'] == 1  && $user['level'] == 0 || $act['closed'] == 1 && $user['level'] == 0 ? '<img src="/photo_tender/image/no_image.jpg" height="100" width="100">':'<a href="/photo_tender/image/user/'.$image.'"><img src="/photo_tender/image/user/'.$image.'" height="200" width="200"></a>').'
	'.($act['closed'] == 1 ? '<span style="color:red;"> - Заблокировано</span>':'').'
	'.($act['mod'] == 1 ? '<span style="color:red;"> - На модерации</span>':'').'
	</div>
	<div class="st_y">
	<img src="/photo_tender/ico/user.png" alt="*"> Добавил: '.group($ank['id']) . user::nick($ank['id']).medal($ank['id']) . online($ank['id']) . ' 
	<br> 
	<img src="/photo_tender/ico/time.png" alt="*"> Добавлено: ' . vremja($act['time']) . '
	<br> 
	<img src="/photo_tender/ico/top.png" alt="*"> Проголосовали: ' . $act['count'] . '
	<br> 
	<img src="/photo_tender/ico/like.gif" alt="*"> Лайков: ' . $act['like'] . '
	<br> 
	<img src="/photo_tender/ico/dlike.gif" alt="*"> Дизлайков: ' . $act['dlike'] . '
	</div>
	
	
	'.($act['mod'] == 0 ? '
	'.($act['closed'] == 0 ? '
	'.($tender['level'] == 0 ? '
	'.($user['id'] != $act['user'] ? '
	</br>
	<div class="st2_y">
	<a href="/photo_tender/photo/golos.php?id='.$act['id'].'"> <img src="/photo_tender/ico/top.png" alt="*"> Проголосовать</a> |
	<a href="/photo_tender/photo/like.php?id='.$act['id'].'"> <img src="/photo_tender/ico/like.gif" alt="*"> Лайк</a> |
	<a href="/photo_tender/photo/dlike.php?id='.$act['id'].'"> <img src="/photo_tender/ico/dlike.gif" alt="*"> Дизлайк</a>
	</div>
	':'').'':'').'':'').'':'').'
	
	
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/photo/list.php?id='.$tender['id'].'"> Назад</a></div>
	';
	
	
	}else{ header("location: /photo_tender/?");}

// foot
	
	include_once '../../sys/inc/tfoot.php';
?>