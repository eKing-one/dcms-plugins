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
	
	if ($act['level'] == 0) {
		
	
	// бонус
	
	// подсчет тех кто выйграл
	
	
	$query = mysql_query("SELECT `user`,`count`,`id`
    FROM `photo_tender_user`
    WHERE `tender` = '".$act['id']."'
    ORDER BY `count` DESC,
	`like` DESC LIMIT 3");
  
	$num = 0;

	while ($post = mysql_fetch_assoc($query)){
	
	$num++;
	
	if ($post['count'] > 0) {
	
	// отправляем бонус если выйграл пользователь

	$sys = mysql_fetch_assoc(mysql_query("SELECT data FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));
	$name = htmlspecialchars ($act['name']);
	$balls = $act['balls'.$num.''];
	$money = $act['money'.$num.''];
	$rating = $act['rating'.$num.''];
	$plus5 = $act['plus'.$num.''];
	$liders = $act['lider'.$num.''];
	$author = get_user($post['user']);	

	mysql_query("UPDATE `photo_tender_user` SET `lider` = '".$num."' WHERE `id` = '".$post['id']."'");
	
	if ($money > 0)mysql_query("UPDATE `user` SET `money` = '" . ($author['money']+$money) . "' WHERE `id` = '" . $author['id']. "' LIMIT 1");
	if ($balls > 0)mysql_query("UPDATE `user` SET `balls` = '" . ($author['balls']+$balls) . "' WHERE `id` = '" . $author['id']. "' LIMIT 1");
	if ($rating > 0)mysql_query("UPDATE `user` SET `rating` = '" . ($author['rating']+$rating) . "' WHERE `id` = '" . $author['id']. "' LIMIT 1");
	
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
	mysql_query("UPDATE `user_set` SET `ocenka` = '".$time_plu5."' WHERE `id_user` = '" . $author['id']. "'");	
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
	$msg = 'Я занял '.$num.' место в фотоконкурсе '.$name.'.';
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `id_user` = '".$author['id']."'"), 0)==0)
	{
	mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`, `time_p`) values('".$author['id']."', '".$liders."', '".$msg."', '".$time_liders."', '".$time."')");
	}else{
	mysql_query("UPDATE `liders` SET `time` = '".$time_liders."', `time_p` = '".$time."', `msg` = '".$msg."', `stav` = '".$liders."' WHERE `id_user` = '".$author['id']."'");
	}
	}
	
	// отправка о бонусе на почту
		
	$msg = 'Благодарим за участие в фотоконкурсе [url=/photo_tender/view/?id='.$act['id'].'][blue]'.$name.'[/blue][/url]
	Вы заняли  '.$num.' место и получаете бонус: '.($balls > 0 ? '[green]'.$balls.'[/green] балл.':'').' '.($money > 0 ? '[green]'.$money.'[/green] монет.':'').' '.($rating > 0 ? '[green]'.$rating.'[/green] % рейтинга.':'').' '.($plus5 > 0 ? '[green]'.$plus5.'[/green] дн. услуги Оценка 5+.':'').' '.($liders > 0 ? '[green]'.$liders.'[/green] дн. услуги Лидер сайта.':'').'
	';
	
	// отправка сообщения
	mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$sys[data]', '$author[id]', '".my_esc($msg)."', '$time')");
	// добавляем в контакты
	if ($author['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$sys[data]' AND `id_kont` = '$author[id]'"),0)==0)
	mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$sys[data]', '$author[id]', '$time')");
	// обновление сведений о контакте
	mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$sys[data]' AND `id_kont` = '$author[id]' OR `id_user` = '$user[id]' AND `id_kont` = '$sys[data]'");
	
	
	}
	}
	
	mysql_query("UPDATE `photo_tender` SET `level` = '1' WHERE `id` = '".$act['id']."'");
	$_SESSION['message'] = 'Конкурс завершен';
	header("Location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;
	}else{

	mysql_query("UPDATE `photo_tender` SET `level` = '0' WHERE `id` = '".$act['id']."'");
	$_SESSION['message'] = 'Конкурс активен';
	header("Location: /photo_tender/admin/view/?id=".$act['id']."");
	exit;	

	}		
	
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}


?>