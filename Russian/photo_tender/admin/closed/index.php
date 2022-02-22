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

// если только существует 	
	
	if (!empty($act)) {
	
	if ($act['closed'] == 0) {
		
	mysql_query("UPDATE `photo_tender` SET `closed` = '1' WHERE `id` = '".$act['id']."'");
	$_SESSION['message'] = 'Голосование закрыты';
	header("Location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;		
		
	}else{

	mysql_query("UPDATE `photo_tender` SET `closed` = '0' WHERE `id` = '".$act['id']."'");
	$_SESSION['message'] = 'Голосование открыты';
	header("Location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;	

	}		
	
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}


?>