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
	
	$sys = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender_sys` WHERE `id` = 1 LIMIT 1"));	
	
// Отправка 
	
	if (isset($_POST['save']) && isset($user))
	{
	// Фильтрация XD || определение переменных
	
	$name = htmlspecialchars( $_POST['name'] );
	$message = htmlspecialchars( $_POST['message'] );
	
	$balls1 = abs(intval( $_POST['balls1'] ));
	$balls2 = abs(intval( $_POST['balls2'] ));
	$balls3 = abs(intval( $_POST['balls3'] ));
	$balls4 = abs(intval( $_POST['balls4'] ));
	$balls5 = abs(intval( $_POST['balls5'] ));
	
	$money1 = abs(intval( $_POST['money1'] ));
	$money2 = abs(intval( $_POST['money2'] ));
	$money3 = abs(intval( $_POST['money3'] ));
	$money4 = abs(intval( $_POST['money4'] ));
	$money5 = abs(intval( $_POST['money5'] ));
	
	$rating1 = abs(intval( $_POST['rating1'] ));
	$rating2 = abs(intval( $_POST['rating2'] ));
	$rating3 = abs(intval( $_POST['rating3'] ));
	$rating4 = abs(intval( $_POST['rating4'] ));
	$rating5 = abs(intval( $_POST['rating5'] ));
	
	$lider1 = abs(intval( $_POST['lider1'] ));
	$lider2 = abs(intval( $_POST['lider2'] ));
	$lider3 = abs(intval( $_POST['lider3'] ));
	$lider4 = abs(intval( $_POST['lider4'] ));
	$lider5 = abs(intval( $_POST['lider5'] ));
	
	$plus1 = abs(intval( $_POST['plus1'] ));
	$plus2 = abs(intval( $_POST['plus2'] ));
	$plus3 = abs(intval( $_POST['plus3'] ));
	$plus4 = abs(intval( $_POST['plus4'] ));
	$plus5 = abs(intval( $_POST['plus5'] ));
	
	$golos = abs(intval( $_POST['golos'] ));
	$time_end = abs(intval( $_POST['time_end'] ));
	$max = abs(intval( $_POST['max'] ));
	$sex = abs(intval( $_POST['sex'] ));
	$mod = abs(intval( $_POST['mod'] ));
	
	if ($time_end == 1 ) $time_ends = $time+86400;
	if ($time_end == 2 ) $time_ends = $time+172800;
	if ($time_end == 3 ) $time_ends = $time+259200;
	if ($time_end == 4 ) $time_ends = $time+345600;
	if ($time_end == 5 ) $time_ends = $time+432000;
	if ($time_end == 6 ) $time_ends = $time+518400;
	if ($time_end == 7 ) $time_ends = $time+604800;
	if ($time_end == 8 ) $time_ends = $time+691200;
	if ($time_end == 9 ) $time_ends = $time+777600;
	if ($time_end == 10 ) $time_ends = $time+864000;
	
	
	// ввиды ошибок
	
	if (strlen2($name) > 501){ $err = 'Название слишком длинная'; }
	if (strlen2($name) < 3){ $err = 'Название слишком короткое'; }
	if (strlen2($message) > 10001){ $err = 'Описание слишком длинное'; }
	if (strlen2($message) < 3){ $err = 'Описание слишком короткое'; }

	if ($lider1 > 10) $err = 'Не верно указано дней в лидер сайта за первое место';	
	if ($lider2 > 10) $err = 'Не верно указано дней в лидер сайта за второе место';	
	if ($lider3 > 10) $err = 'Не верно указано дней в лидер сайта за третье место';	
	if ($lider4 > 10) $err = 'Не верно указано дней в лидер сайта за участие';	
	if ($lider5 > 10) $err = 'Не верно указано дней в лидер сайта за голосование';	
	
	
	if ($plus1 > 10) $err = 'Не верно указано дней в оценки 5+ за первое место';
	if ($plus2 > 10) $err = 'Не верно указано дней в оценки 5+ за второе место';
	if ($plus3 > 10) $err = 'Не верно указано дней в оценки 5+ за третье место';
	if ($plus4 > 10) $err = 'Не верно указано дней в оценки 5+ за участие';
	if ($plus5 > 10) $err = 'Не верно указано дней в оценки 5+ за голосование';
	
	
	if ($time_end > 10) $err = 'Не верно указано дней в времени фотоконкурса';
	if ($time_end == 0) $err = 'Не верно указано дней в времени фотоконкурса';
	if ($max == 0) $err = 'Не верно указано участики фотоконкурса';
	if ($sex == 0)$err = 'Не верно указано кто может принимать участие';
	if ($sex > 3)$err = 'Не верно указано кто может принимать участие';
	if ($mod > 1)$err = 'Не верно указано модерация фото';
	
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `photo_tender` WHERE `name` = '$name' LIMIT 1"),0) != 0)
	{
	$err = 'Вы уже создавали такое название фотоконкурса ранее.';
	}
	if(!isset($err))
	{
	
	mysql_query("INSERT INTO `photo_tender` 
	(`user`,`time`,`name`,`message`,`balls1`,`balls2`,`balls3`,`balls4`,`balls5`,`money1`,`money2`,
	`money3`,`money4`,`money5`,`rating1`,`rating2`,`rating3`,`rating4`,`rating5`,`lider1`,`lider2`,`lider3`,
	`lider4`,`lider5`,`plus1`,`plus2`,`plus3`,`plus4`,`plus5`,`golos`,`time_end`,`time_end_key`,`max`,`sex`,`mod`)
	values('".$user['id']."','".$time."','".$name."','".$message."','".$balls1."','".$balls2."','".$balls3."',
	'".$balls4."','".$balls5."','".$money1."','".$money2."',
	'".$money3."','".$money4."','".$money5."','".$rating1."','".$rating2."','".$rating3."','".$rating4."',
	'".$rating5."','".$lider1."','".$lider2."','".$lider3."',
	'".$lider4."','".$lider5."','".$plus1."','".$plus2."','".$plus3."','".$plus4."','".$plus5."',
	'".$golos."','".$time_ends."','".$time_end."','".$max."','".$sex."','".$mod."')");

	$id = mysql_insert_id();	
	
	// отправка в гостевую
	
	if ($sys['type'] == 0){
	
	$mes = $sys['messages'].' [url=/photo_tender/view/?id='.$id.'][blue]'.$name.'[/blue][/url]';	
		
	mysql_query("INSERT INTO `guest` (id_user, time, msg) values('$user[id]', '$time', '" . my_esc($mes) . "')");	
		
	}
	
	// отправка в новости
	
	if ($sys['news'] == 0){
	
	$win1 = ''.($balls1 == 0 ? '':''.$balls1.' балл.').' '.($money1 == 0 ? '':''.$money1.' монет.').''.($rating1 == 0 ? '':''.$rating1.' % рейтинга.').''.($lider1 == 0 ? '':''.$lider1.' дн. услуги лидер сайта.').''.($plus1 == 0 ? '':''.$plus1.' дн. услуги оценка 5+.').'';
	$win2 = ''.($balls2 == 0 ? '':''.$balls2.' балл.').' '.($money2 == 0 ? '':''.$money2.' монет.').''.($rating2 == 0 ? '':''.$rating2.' % рейтинга.').''.($lider2 == 0 ? '':''.$lider2.' дн. услуги лидер сайта.').''.($plus2 == 0 ? '':''.$plus2.' дн. услуги оценка 5+.').'';
	$win3 = ''.($balls3 == 0 ? '':''.$balls3.' балл.').' '.($money3 == 0 ? '':''.$money3.' монет.').''.($rating3 == 0 ? '':''.$rating3.' % рейтинга.').''.($lider3 == 0 ? '':''.$lider3.' дн. услуги лидер сайта.').''.($plus3 == 0 ? '':''.$plus3.' дн. услуги оценка 5+.').'';
	$win4 = ''.($balls4 == 0 ? '':''.$balls4.' балл.').' '.($money4 == 0 ? '':''.$money4.' монет.').''.($rating4 == 0 ? '':''.$rating4.' % рейтинга.').''.($lider4 == 0 ? '':''.$lider4.' дн. услуги лидер сайта.').''.($plus4 == 0 ? '':''.$plus4.' дн. услуги оценка 5+.').'';
	$win5 = ''.($balls5 == 0 ? '':''.$balls5.' балл.').' '.($money5 == 0 ? '':''.$money5.' монет.').''.($rating5 == 0 ? '':''.$rating5.' % рейтинга.').''.($lider5 == 0 ? '':''.$lider5.' дн. услуги лидер сайта.').''.($plus5 == 0 ? '':''.$plus5.' дн. услуги оценка 5+.').'';
	$mes = $sys['msg'].'
	[green]Призы[/green]:
	1.Место - '.$win1.'
	2.Место - '.$win2.'
	3.Место - '.$win3.'
	За участие - '.$win4.'
	За голосование - '.$win5.'
	[url=/photo_tender/view/?id='.$id.'][blue]'.$name.'[/blue][/url]';	
	mysql_query("INSERT INTO `news` (id_user, time, title, msg)values('$user[id]', '$time', '$sys[title]', '" . my_esc($mes) . "')");	
		
	}
	
	$_SESSION['message'] = 'Фотоконкурс успешна создан , не забудьте добавить логотип!';
	header ("Location: /photo_tender/admin/view/?id=".$id."" . SID);
	exit;
	}
	}	
	
	
// заголовок страницы
	
	$set['title'] = 'Фотоконкурсы  : admin_panel : создание'; 
	
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
	
	<b>Название (max 500):</b><br> 
	<input type="text" name="name"  value=""><br> 
	
	<b>Описание (max 1000):</b><br> 
	<textarea name="message"></textarea><br> 
	
	<b>За первое место:</b><br> 
	<div class="s_y">
	<input type="text" name="balls1" value="" style="width: 10%;"> Баллов.
	<input type="text" name="money1" value="" style="width: 10%;"> Монет.
	<input type="text" name="rating1" value="" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider1">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней. </br>
	Оценка 5+:
	<select name="plus1">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</div>
	
	<b>За второе место:</b><br> 
	<div class="s_y">
	<input type="text" name="balls2" value="" style="width: 10%;"> Баллов.
	<input type="text" name="money2" value="" style="width: 10%;"> Монет.
	<input type="text" name="rating2" value="" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider2">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней. </br>
	Оценка 5+:
	<select name="plus2">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</div>
	
	<b>За третье место:</b><br> 
	<div class="s_y">
	<input type="text" name="balls3" value="" style="width: 10%;"> Баллов.
	<input type="text" name="money3" value="" style="width: 10%;"> Монет.
	<input type="text" name="rating3" value="" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider3">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней. </br>
	Оценка 5+:
	<select name="plus3">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</div>
	
	<b>За участие:</b><br> 
	<div class="s_y">
	<input type="text" name="balls4" value="" style="width: 10%;"> Баллов.
	<input type="text" name="money4" value="" style="width: 10%;"> Монет.
	<input type="text" name="rating4" value="" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider4">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней. </br>
	Оценка 5+:
	<select name="plus4">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</div>
	
	
	<b>За голосование:</b><br> 
	<div class="s_y">
	<input type="text" name="balls5" value="" style="width: 10%;"> Баллов.
	<input type="text" name="money5" value="" style="width: 10%;"> Монет.
	<input type="text" name="rating5" value="" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider5">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней. </br>
	Оценка 5+:
	<select name="plus5">
	<option value="0">0</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</div>
	
	<b>Сколько фото может голосовать один пользователь:</b><br> 
	<input type="text" name="golos" value="" style="width: 10%;"> 
	<br> 
	
	<b>Cколько будет длиться фотоконкурс:</b><br> 
	<select name="time_end">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select>
	дней.
	</br>
	
	<b>Сколько максимум участников:</b><br> 
	<input type="text" name="max" value="" style="width: 10%;"> 
	<br> 
	
	<b>Кто может принимать участие:</b><br> 
	<select name="sex">
	<option value="1">Все</option>
	<option value="2">Парни</option>
	<option value="3">Девушки</option>
	</select>
	</br>
	
	<b>Модерация фото:</b><br> 
	<select name="mod">
	<option value="0">Выключена</option>
	<option value="1">Включена</option>
	</select>
	</br>
	
	<input type="submit" name = "save" value="Далее">
	
	
	</form>
	</div></div>
	<div class="block_y">
	<img src="/photo_tender/ico/settings.png" alt="*"> <a href="/photo_tender/admin/"> Админка </a></div>
	';


// foot
	include_once '../../../sys/inc/tfoot.php';
?>