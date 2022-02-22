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
	
// класс загрузки фото

	include_once '../../photo_tender/class/image.php';
	
// ID

	define ('id', isset( $_GET['id'] ) ? abs(intval($_GET['id'])) : 0);	
	define ('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');

// класс загрузки фото

	include_once (ROOT.'photo_tender/class/image.php');	
	$image = new image();	

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	

	$tender = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender_user` WHERE `tender` = '".$act['id']."' AND `user` = '".$user['id']."' LIMIT 1"));	

	$max = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender_user` WHERE `tender` = '".$act['id']."'"), 0);
	

// если только существует 	
	
	if (!empty($act)) {

// проверяем на наличии фото

	if (empty($tender)) {
		
// проверяем на открыт ли конкурс

	if ($act['level'] == 0) {
		
// проверяем на можно ли участвовать

	if ($max < $act['max']) {	
		
// проверям кто может участвовать

	$sex = ''.($act['sex'] == 2 ? 1:'').''.($act['sex'] == 3 ? 0:'').'';	
	
	if ($sex == $user['pol'] || $act['sex'] == 1) {		
	
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
	$name_image = 'id'.$user['id'].'_'.$act['id'].'_'.$key.'.'.$type.'';


	$image->load($_FILES['file']['tmp_name']);	
	$image->save(ROOT.'photo_tender/image/user/'.$name_image.'');
	
	
	// Бонус
	
	$sys = mysql_fetch_assoc(mysql_query("SELECT data FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));
	$balls = $act['balls4'];
	$money = $act['money4'];
	$rating = $act['rating4'];
	$plus5 = $act['plus4'];
	$liders = $act['lider4'];
	
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
	$msg = 'Я участвую в фотоконкурсе '.$name.'.';
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `id_user` = '$user[id]'"), 0)==0)
	{
	mysql_query("INSERT INTO `liders` (`id_user`, `stav`, `msg`, `time`, `time_p`) values('".$user['id']."', '".$liders."', '".$msg."', '".$time_liders."', '".$time."')");
	}else{
	mysql_query("UPDATE `liders` SET `time` = '".$time_liders."', `time_p` = '".$time."', `msg` = '".$msg."', `stav` = '".$liders."' WHERE `id_user` = '".$user['id']."'");
	}
	}
	
	// отправка о бонусе на почту
		
	$msg = 'Благодарим за участие в фотоконкурсе [url=/photo_tender/view/?id='.$act['id'].'][blue]'.$name.'[/blue][/url]
	Бонус за участие: '.($balls > 0 ? '[green]'.$balls.'[/green] балл.':'').' '.($money > 0 ? '[green]'.$money.'[/green] монет.':'').' '.($rating > 0 ? '[green]'.$rating.'[/green] % рейтинга.':'').' '.($plus5 > 0 ? '[green]'.$plus5.'[/green] дн. услуги Оценка 5+.':'').' '.($liders > 0 ? '[green]'.$liders.'[/green] дн. услуги Лидер сайта.':'').'
	';
	
	// отправка сообщения
	mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$sys[data]', '$user[id]', '".my_esc($msg)."', '$time')");
	// добавляем в контакты
	if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$sys[data]' AND `id_kont` = '$user[id]'"),0)==0)
	mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$sys[data]', '$user[id]', '$time')");
	// обновление сведений о контакте
	mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$sys[data]' AND `id_kont` = '$user[id]' OR `id_user` = '$user[id]' AND `id_kont` = '$sys[data]'");
	
	
	//
	
	mysql_query("INSERT INTO `photo_tender_user` (`mod`, `user`, `tender`, `image`, `time`) values('".$act['mod']."', '$user[id]', '".$act['id']."', '" .$name_image . "', '" .$time. "')");
	$id = mysql_insert_id();
	$_SESSION['message'] = 'Фото добавлено.';
	header("location: /photo_tender/photo/view.php?id=".$id."");
	exit;

	
	}else{ $err = 'Изображение слишком маленькое, загружайте файл не меньше 40x40 (у вас '.$width.'x'.$height.')';}
	}else{ $err = 'Выбранное вами файл имеет размер более 20мб.';}
	}else{ $err = 'Недопустимый тип файла.';}
	}else{ $err = 'Не выбран файл.';}
	}


// заголовок страницы

	$set['title'] = 'Фотоконкурсы : '.$name.' : Добавление фото '; 
	
// head	
	include_once '../../sys/inc/thead.php';
	
	title();
	aut();
	err();


// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// добавление логотипа

	echo'
	<div class="block_y">
	'.($act['mod'] == 1 ? '
	<div class="s_y  fot_k">
	<span style="color:red;">
	После загрузки фото будет отправлена на модерацию.
	</span>
	</div>
	':'').'
	<div class="st_y">
	<form method="post" enctype="multipart/form-data"> 
	<input name="file" type="file" accept="image/jpeg, image/png, image/gif"/>
	</br>
	<input class="submit" type="submit" name="upload" value="Загрузить">
	</form>
	</div>
	<div class="s_y  fot_k">
	Максимальный размер файла 20 Мб.</br>
	<span style="color:red;">
	Внимание перед загрузкой прочтите правила загрузки файлов.
	</span>
	</div>
	
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/view/?id='.$act['id'].'"> Назад</a></div>
	
	';
	
	}else{ $_SESSION['message'] = 'Запрещено участвовать.';header("location: /photo_tender/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Уже участвуют максимальное количество.';header("location: /photo_tender/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Конкурс завершен.';header("location: /photo_tender/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Фото уже добавлено.';header("location: /photo_tender/view/?id=".$act['id']."?");exit;}
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/?");exit;}

// foot
	
	include_once '../../sys/inc/tfoot.php';
?>