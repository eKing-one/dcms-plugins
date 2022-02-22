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
	
// класс загрузки фото

	include_once '../../../photo_tender/class/image.php';
	
// ID

	define ('id', isset( $_GET['id'] ) ? abs(intval($_GET['id'])) : 0);	
	define ('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
	
	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	

// если только существует 	
	
	if (!empty($act)) {

// проверяем на наличии логотипа

	if ($act['image'] != null) {
	
	@unlink(ROOT."photo_tender/image/".$act['image']."");
	
	mysql_query("UPDATE `photo_tender` SET `image` = '' WHERE `id` = '".$act['id']."' LIMIT 1");

	$_SESSION['message'] = 'Логотип удален.';
	header("location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;

	}else{ $_SESSION['message'] = 'Логотип не был добавлен ранее.';header("location: /photo_tender/admin/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}

?>