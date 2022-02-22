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
	
// 

	$sys = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));	
	
	$messages = htmlspecialchars ($sys['messages']);
	$title = htmlspecialchars ($sys['title']);
	$msg = htmlspecialchars ($sys['msg']);
	
// Отправка заявки
	
	if (isset($_POST['data']) && isset($user))
	{
	$data = abs(intval($_POST['data']));
	$messages = htmlspecialchars ($_POST['messages']);
	$title = htmlspecialchars ($_POST['title']);
	$msg = htmlspecialchars ($_POST['msg']);
	$type = abs(intval($_POST['type']));
	$news = abs(intval($_POST['news']));
	if (strlen2($messages) > 1001){ $err[] = 'Текст в гостевую слишком длинный'; }
	if (strlen2($title) > 33){ $err[] = 'Название новости слишком длинное'; }
	if (strlen2($msg) > 10025){ $err[] = 'Описание новости слишком длинное'; }
	if ($type > 1 ){ $err = 'Не верно указано пунк уведомления в гостевую';	}
	if ($news > 1 ){$err = 'Не верно указано пунк уведомления в новости';}	
	if(!isset($err))
	{
	mysql_query("UPDATE `photo_tender_sys` SET 
	`data` = '".$data."',
	`messages` = '".$messages."',
	`title` = '".$title."',
	`msg` = '".$msg."',
	`news` = '".$news."',
	`type` = '".$type."' WHERE `id` = '1' LIMIT 1");
	$_SESSION['message'] = 'Изменение прошли успешно';
	header ("Location: /photo_tender/admin/settings/" . SID);
	exit;
	}
	}	
	
	
// заголовок страницы
	$set['title'] = 'Фотоконкурсы : admin_panel : Системные настройки'; 
	
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
	<div class="block_y"> 
	<b>ID системы:</b><br> 
	<input type="text" name="data"  value="'.$sys['data'].'"></br>
	Уведомление в гостевую о новых конкурсах:</br>
	<select name="type">
	<option value="0"'.($sys['type'] == 0 ? 'selected="0"':'').'>Включены</option>
	<option value="1"'.($sys['type'] == 1 ? 'selected="1"':'').'>Выключены</option>
	</select>
	</br>
	Cоздание новости о новых конкурсах:</br>
	<select name="news">
	<option value="0"'.($sys['news'] == 0 ? 'selected="0"':'').'>Включены</option>
	<option value="1"'.($sys['news'] == 1 ? 'selected="1"':'').'>Выключены</option>
	</select>
	</br>
	<b>Текс уведомления о новых конкурсах в гостевую (max 1000):</b><br> 
	<textarea name="messages">'.$messages.'</textarea><br> 
	</br>
	<b>Название новости (max 32):</b><br> 
	<textarea name="title">'.$title.'</textarea><br> 
	</br>
	<b>Описание новости (max 10024):</b><br> 
	<textarea name="msg">'.$msg.'</textarea><br> 
	<input type="submit" value="Изменить">
	</div>   
	</form></div>
	<div class="s_y">
	ID системы нужен для рассылки сообщений пользователям о новых дествий,
	ID не может быть пустым. </br>
	В тексте уведомления не нужно указавать ссылку и названия конкурса она добавится автоматически после вашего текста. </br>
	В Описание новости не нужно указывать призы , автоматически добавиться )  
	</div>
	<div class="st2_y">
	Пример уведомления в гостевую:</br>
	'.$messages.' <a href=""> Текст конкурса</a>
	</div>
	<div class="st2_y">
	Пример уведомления в новости:</br>
	Название новости: '.$title.'</br>
	Описание: '.$msg.'</br>
	Призы:</br>
	1. Место - 10 монет</br>
	2. Место - 5 монет</br>
	3. Место - 2 монеты</br>
	<a href=""> Текст конкурса</a>
	</div>
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*">
	<a href="/photo_tender/admin/"> Назад</a></div>
	';


// foot
	include_once '../../../sys/inc/tfoot.php';
?>