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
	$golos = mysql_result(mysql_query("SELECT COUNT(*) FROM `photo_tender_golos` WHERE `image` = '$act[id]' AND `user` = '$user[id]' LIMIT 1"), 0);

// если только существует 	
	
	if (!empty($act)) {

// если только существует 	
	
	if (!empty($tender)) {

// проверяем сколько раз можно голосовать

	if ($golos < $tender['golos']) {
	
// проверяем открыто ли голосование

	if ($tender['closed'] == 0) {
	
// проверям на блокировку фото

	if ($act['closed'] == 0) {	
	
// проверям на модерацию фото

	if ($act['mod'] == 0) {		
	
// проверяем на открыт ли конкурс

	if ($tender['level'] == 0) {
	
// запрещаем голосовать за себя

	if ($act['user'] != $user['id']) {
		
// запрещаем голосовать 2 раза

	if (empty($golos)) {
		
// Бонус
	
	$sys = mysql_fetch_assoc(mysql_query("SELECT data FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));
	$name = htmlspecialchars ($tender['name']);
	$balls = $tender['balls5'];
	$money = $tender['money5'];
	$rating = $tender['rating5'];
	$plus5 = $tender['plus5'];
	$liders = $tender['lider5'];
	
	if ($money > 0)mysql_query("UPDATE `user` SET `money` = '" . ($user['money']+$money) . "' WHERE `id` = '" . $user['id']. "' LIMIT 1");
	if ($balls > 0)mysql_query("UPDATE `user` SET `balls` = '" . ($user['balls']+$balls) . "' WHERE `id` = '" . $user['id']. "' LIMIT 1");
	if ($rating > 0)mysql_query("UPDATE `user` SET `rating` = '" . ($user['rating']+$rating) . "' WHERE `id` = '" . $user['id']. "' LIMIT 1");
	
	if ($plus5 > 0){
	if ($plus5 == 1 ) $time_plu5 = $time+86400;
	if ($plus5 == 2 ) $time_plu5 = $time+172800;
	if ($plus5 == 3 ) $time_plu5 = $time+259200;
	if ($plus5 == 4 ) $time_plu5 = $time+345600;
	if ($plus5 == 5 ) $time_plu5 = $time+432000;
	if ($plus5 == 6 ) $time_plu5 = $time+518400;
	if ($plus5 == 7 ) $time_plu5 = $time+604800;
	if ($plus5 == 8 ) $time_plu5 = $time+691200;
	if ($plus5 == 9 ) $time_plu5 = $time+777600;
	if ($plus5 == 10 )$time_plu5 = $time+864000;
	mysql_query("UPDATE `user_set` SET `ocenka` = '".$time_plu5."' WHERE `id_user` = '" . $user['id']. "'");	
	}
	
	if ($liders > 0){
	if ($liders == 1 ) $time_liders = $time+86400;
	if ($liders == 2 ) $time_liders = $time+172800;
	if ($liders == 3 ) $time_liders = $time+259200;
	if ($liders == 4 ) $time_liders = $time+345600;
	if ($liders == 5 ) $time_liders = $time+432000;
	if ($liders == 6 ) $time_liders = $time+518400;
	if ($liders == 7 ) $time_liders = $time+604800;
	if ($liders == 8 ) $time_liders = $time+691200;
	if ($liders == 9 ) $time_liders = $time+777600;
	if ($liders == 10 ) $time_liders = $time+864000;
	$msg = 'Я голосую в фотоконкурсе '.$name.'.';
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `id_user` = '$user[id]'"), 0)==0)
	{
	mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`, `time_p`) values('".$user['id']."', '".$liders."', '".$msg."', '".$time_liders."', '".$time."')");
	}else{
	mysql_query("UPDATE `liders` SET `time` = '".$time_liders."', `time_p` = '".$time."', `msg` = '".$msg."', `stav` = '".$liders."' WHERE `id_user` = '".$user['id']."'");
	}
	}
	
	// отправка о бонусе на почту
		
	$msg = 'Благодарим за голосование в фотоконкурсе [url=/photo_tender/view/?id='.$tender['id'].'][blue]'.$name.'[/blue][/url]
	Бонус за голосование: '.($balls > 0 ? '[green]'.$balls.'[/green] балл.':'').' '.($money > 0 ? '[green]'.$money.'[/green] монет.':'').' '.($rating > 0 ? '[green]'.$rating.'[/green] % рейтинга.':'').' '.($plus5 > 0 ? '[green]'.$plus5.'[/green] дн. услуги Оценка 5+.':'').' '.($liders > 0 ? '[green]'.$liders.'[/green] дн. услуги Лидер сайта.':'').'
	';
	
	// отправка сообщения
	mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$sys[data]', '$user[id]', '".my_esc($msg)."', '$time')");
	// добавляем в контакты
	if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$sys[data]' AND `id_kont` = '$user[id]'"),0)==0)
	mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$sys[data]', '$user[id]', '$time')");
	// обновление сведений о контакте
	mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$sys[data]' AND `id_kont` = '$user[id]' OR `id_user` = '$user[id]' AND `id_kont` = '$sys[data]'");
	
	// отправка на почту о голосование
	
	$msg2 = 'Пользователь '.$user['nick'].' проголосовал за ваше фото в фотоконкурсе [url=/photo_tender/view/?id='.$tender['id'].'][blue]'.$name.'[/blue][/url].';
	
	// отправка сообщения
	mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$sys[data]', '$act[user]', '".my_esc($msg2)."', '$time')");
	// добавляем в контакты
	if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$sys[data]' AND `id_kont` = '$act[user]'"),0)==0)
	mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$sys[data]', '$act[user]', '$time')");
	// обновление сведений о контакте
	mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$sys[data]' AND `id_kont` = '$act[user]' OR `id_user` = '$act[user]' AND `id_kont` = '$sys[data]'");
	


// если все нормаьно

	mysql_query("INSERT INTO `photo_tender_golos` (user, image, tender, time) values('$user[id]', '".$act['id']."',".$act['tender'].", '" .$time. "' )");
	mysql_query("UPDATE `photo_tender_user` SET `count` = '".($act['count'] + 1)."' WHERE `id` = '".$act['id']."' LIMIT 1");
	
	$_SESSION['message'] = 'Голос принят.';
	header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");
	
	}else{ $_SESSION['message'] = 'Вы не уже проголовали.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Вы не можете голосовать за себя.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Конкурс завершен.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Фото на модерации.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Фото было заблокировано.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Голосование закрыты в этом конкурсе.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ $_SESSION['message'] = 'Вы привысили лимит голосования в этом конкурсе.'; header("location: /photo_tender/photo/list.php?id=".$tender['id']."?");}
	}else{ header("location: /photo_tender/");}
	}else{ header("location: /photo_tender/");}


?>