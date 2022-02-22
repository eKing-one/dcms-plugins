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

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	

// GET	
 
	if (isset($_GET['type']) && $_GET['type'] == 'ok')
	{ 	
	$id = abs(intval($_GET['id']));
	$list = abs(intval($_GET['list']));
	
	define ('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
	$image = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender_user` WHERE `id` = '".$id."' LIMIT 1"));	
	
	if (!empty($image)) {
	if ($image['tender'] == $list )	{
		
	@unlink(ROOT."photo_tender/image/user/".$image['image']."");	
		
	mysql_query("DELETE FROM `photo_tender_user` WHERE `id` = '".$id."'");	
	mysql_query("DELETE FROM `photo_tender_golos` WHERE `image` = '".$id."'");
	mysql_query("DELETE FROM `photo_tender_dlike` WHERE `image` = '".$id."'");
	mysql_query("DELETE FROM `photo_tender_like` WHERE `image` = '".$id."'");	
	
	$_SESSION['message'] = 'Фото удалено.';
	header("Location: /photo_tender/admin/del/?id=".$list."");
	exit;	
	}else{
	$_SESSION['message'] = 'Ошибка фото не удалено';
	header("Location: /photo_tender/admin/del/?id=".$list."");
	exit;
	}
	}else{
	$_SESSION['message'] = 'Ошибка фото не существует';
	header("Location: /photo_tender/admin/del/?id=".$list."");
	exit;	
	}	
	}


// если только существует 	
	
	if (!empty($act)) {
	
	$name = htmlspecialchars ($act['name']);

// заголовок страницы

	$set['title'] = 'Фотоконкурсы : '.$name.' : Удаление фото'; 
	
// head	
	include_once '../../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />
	<div class="block_y">
	<div class="st_y"><img src="/photo_tender/ico/info.png" alt="*"> Фото всех участников:</div>
	';

	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender_user` WHERE `tender` = ".$act['id'].""), 0);

	$k_page = k_page($count, $set['p_str']);
	$page = page($k_page);
	$start = $set['p_str'] * $page - $set['p_str'];
	
	$q = mysql_query("SELECT * FROM `photo_tender_user` WHERE `tender` = '".$act['id']."' ORDER BY time DESC LIMIT $start, $set[p_str]");
	while ($tender = mysql_fetch_assoc($q))
	{
	$image = htmlspecialchars ($tender['image']);
	$ank = get_user($tender['user']);
	
	echo'
	<div class="s_y  fot_k">
	<div class="fot_k_2">
	<a href="/photo_tender/photo/view.php?id='.$tender['id'].'">
	<img src="/photo_tender/image/user/'.$image.'" alt="*"></a></div>
	<div class="fot_k_3">
	<span class="fot_k_5">
	<a href="/photo_tender/admin/del/?id='.$tender['id'].'&list='.$act['id'].'&type=ok">
	<img src="/photo_tender/ico/delete.gif" alt="*"></a>
	</span>
	'.group($ank['id']) . user::nick($ank['id']).medal($ank['id']) . online($ank['id']) . '
	'.($tender['closed'] == 1 ? '<span style="color:red;"> - Заблокировано</span>':'').'
	</br>
	
	<img src="/photo_tender/ico/top.png" alt="*">'.$tender['count'].'</span> |
	<img src="/photo_tender/ico/like.gif" alt="*">'.$tender['like'].'</span> |
	<img src="/photo_tender/ico/dlike.gif" alt="*">'.$tender['dlike'].'</span>
	
	</div>
	</div>
	';
		
	}	
	
	if ($count == 0) echo'<div class="s_y">Никто не добавил фотографию!</div>';
	
	
	if ($k_page > 1)str("/photo_tender/admin/del/?id=".$act['id']."&", $k_page, $page); // Вывод страниц	



	echo'
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/admin/view/?id='.$act['id'].'"> Назад</a>
	</div>
	';
	
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}

// foot
	
	include_once '../../../sys/inc/tfoot.php';
?>