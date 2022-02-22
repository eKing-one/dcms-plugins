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

// класс загрузки фото

	include_once (ROOT.'photo_tender/class/image.php');	
	$image = new image();	

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	

// если только существует 	
	
	if (!empty($act)) {

// проверяем на наличии логотипа

	if ($act['image'] == null) {
	
	$name = htmlspecialchars ($act['name']);

// загрузка 	
	
	if (isset($_POST['upload'])) {	

	if (!empty($_FILES['file']['name'])) {

	$type = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
	$images = array('gif', 'jpg', 'jpeg', 'png');	

	if (in_array($type, $images)) {

	$size = $_FILES['file']['size'];
	
    if ($size < 20971521) {	

	$info = getimagesize($_FILES['file']['tmp_name']); 
    $width = $info[0];
    $height = $info[1];
	
	if ($width >= 40 || $height >=40 ) {

	$key = rand(1000000000, 10000000000);
	$name_image = ''.$act['id'].'_'.$key.'.'.$type.'';


	$image->load($_FILES['file']['tmp_name']);	
	$image->save(ROOT.'photo_tender/image/'.$name_image.'');
	
	mysql_query("UPDATE `photo_tender` SET `image` = '".$name_image."' WHERE `id` = '".$act['id']."' LIMIT 1");

	$_SESSION['message'] = 'Логотип добавлен.';
	header("location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;

	}else{ $err = 'Изображение слишком маленькое, загружайте файл не меньше 40x40 (у вас '.$width.'x'.$height.')';}
	}else{ $err = 'Выбранное вами файл имеет размер более 20мб.';}
	}else{ $err = 'Недопустимый тип файла.';}
	}else{ $err = 'Не выбран файл.';}
	}


// заголовок страницы

	$set['title'] = 'Фотоконкурсы : '.$name.' : Загрузка логотипа'; 
	
// head	
	include_once '../../../sys/inc/thead.php';
	
	title();
	aut();
	err();


// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// добавление логотипа

	echo'
	<div class="block_y">
	<div class="st_y">
	<form method="post" enctype="multipart/form-data"> 
	<input name="file" type="file" accept="image/jpeg, image/png, image/gif"/>
	</br>
	<input class="submit" type="submit" name="upload" value="Загрузить">
	</form>
	</div>
	<div class="s_y  fot_k">
	Максимальный размер файла 20 Мб.</div>
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/admin/view/?id='.$act['id'].'"> Назад</a></div>
	
	';
	
	}else{ $_SESSION['message'] = 'Логотип был уже добавлен.';header("location: /photo_tender/admin/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}

// foot
	
	include_once '../../../sys/inc/tfoot.php';
?>