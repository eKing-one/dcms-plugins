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
	$dlike = mysql_result(mysql_query("SELECT COUNT(*) FROM `photo_tender_dlike` WHERE `image` = '$act[id]' AND `user` = '$user[id]' LIMIT 1"), 0);

// если только существует 	
	
	if (!empty($act)) {

// если только существует 	
	
	if (!empty($tender)) {

// проверям на блокировку фото

	if ($act['closed'] == 0) {	

// проверям на модерации ли  фото

	if ($act['mod'] == 0) {	
	
// проверяем на открыт ли конкурс

	if ($tender['level'] == 0) {
	
// запрещаем лайкать свои фото 

	if ($act['user'] != $user['id']) {
		
// запрещаем лайкать 2 раза

	if (empty($dlike)) {
		
// если все нормаьно

	mysql_query("INSERT INTO `photo_tender_dlike` (user, image, tender, time) values('$user[id]', '".$act['id']."',".$act['tender'].", '" .$time. "' )");
	mysql_query("UPDATE `photo_tender_user` SET `dlike` = '".($act['dlike'] + 1)."' WHERE `id` = '".$act['id']."' LIMIT 1");
	
	$sys = mysql_fetch_assoc(mysql_query("SELECT data FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));
	$name = htmlspecialchars ($tender['name']);
	$msg = 'Пользователь '.$user['nick'].' поставил дизлайк на ваше фото в фотоконкурсе [url=/photo_tender/view/?id='.$tender['id'].'][blue]'.$name.'[/blue][/url].';
	
	// отправка сообщения
	mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$sys[data]', '$act[user]', '".my_esc($msg)."', '$time')");
	// добавляем в контакты
	if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$sys[data]' AND `id_kont` = '$act[user]'"),0)==0)
	mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$sys[data]', '$act[user]', '$time')");
	// обновление сведений о контакте
	mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$sys[data]' AND `id_kont` = '$act[user]' OR `id_user` = '$act[user]' AND `id_kont` = '$sys[data]'");
	
	
	
	$_SESSION['message'] = 'Дизлайк принят.';
	header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");
	
	}else{ $_SESSION['message'] = 'Вы уже поставили дизлайк на фото.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Вы не можете ставить дизлайк себе.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Конкурс завершен.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Фото на модерации.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Фото было заблокировано.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ header("location: /photo_tender/");}
	}else{ header("location: /photo_tender/");}


?>