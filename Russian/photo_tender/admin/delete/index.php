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
	define ('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
	
	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	
	
	$names = htmlspecialchars ($act['name']);
	$messages = htmlspecialchars ($act['message']);
	
// если только существует 	
	
	if (!empty($act)) {	
	
// Отправка заявки
	
	if (isset($_POST['save']) && isset($user))
	{
	
	// удаляем логотип если он был
	
	if ($act['image'] != null ){
	
	@unlink(ROOT."photo_tender/image/".$act['image']."");
	
	}
	
	// удаем все фото пользователей
	
	// посчет фото
	
	$query = mysql_query("SELECT `image`
    FROM `photo_tender_user`
    WHERE `tender` = '".$act['id']."'");
	
	while ($post = mysql_fetch_assoc($query)){
		
	@unlink(ROOT."photo_tender/image/user/".$post['image']."");	
	
	}
	
	mysql_query("DELETE FROM `photo_tender_user` WHERE `id` = '".$act['id']."'");	
	mysql_query("DELETE FROM `photo_tender_golos` WHERE `image` = '".$act['id']."'");
	mysql_query("DELETE FROM `photo_tender_dlike` WHERE `image` = '".$act['id']."'");
	mysql_query("DELETE FROM `photo_tender_like` WHERE `image` = '".$act['id']."'");	
	
	mysql_query("DELETE FROM `photo_tender` WHERE `id` = '$act[id]'");
	
	$_SESSION['message'] = 'Фотоконкурс успешна удален.';
	header ("Location: /photo_tender/admin/?" . SID);
	exit;
	
	}	
	
	
// заголовок страницы
	
	$set['title'] = 'Фотоконкурсы  : '.$names.' : Удаление'; 
	
// head	
	include_once '../../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'
	<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />
	<div class="block_y">
	<div class="st_y">
	<form action="" method="post">    
	Вы действительно , хотите удалить фотоконкурс <b>'.$names.'</b> ?
	</br>
	<input type="submit" name = "save" value="Да">
	</form>
	</div></div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/admin/view/?id='.$act['id'].'"> Назад</a></div>
	
	';

	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}


// foot
	include_once '../../../sys/inc/tfoot.php';
?>