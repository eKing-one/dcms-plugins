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
	
	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	
	
	$names = htmlspecialchars ($act['name']);
	$messages = htmlspecialchars ($act['message']);
	
// если только существует 	
	
	if (!empty($act)) {	
	
// Отправка заявки
	
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

	if(!isset($err))
	{
	
	mysql_query("UPDATE `photo_tender` SET `user` = '".$user['id']."',
	`time` = '".$time."',
	`name` = '".$name."',
	`message` = '".$message."',
	`balls1` = '".$balls1."',
	`balls2` = '".$balls2."',
	`balls3` = '".$balls3."',
	`balls4` = 	'".$balls4."',
	`balls5` = '".$balls5."',
	`money1` = '".$money1."',
	`money2` = '".$money2."',
	`money3` = '".$money3."',
	`money4` = '".$money4."',
	`money5` = '".$money5."',
	`rating1` = '".$rating1."',
	`rating2` = '".$rating2."',
	`rating3` = '".$rating3."',
	`rating4` = '".$rating4."',
	`rating5` = '".$rating5."',
	`lider1` = '".$lider1."',
	`lider2` = '".$lider2."',
	`lider3` = '".$lider3."',
	`lider4` = '".$lider4."',
	`lider5` = '".$lider5."',
	`plus1` = '".$plus1."',
	`plus2` = '".$plus2."',
	`plus3` = '".$plus3."',
	`plus4` = '".$plus4."',
	`plus5` = '".$plus5."',
	`golos` = '".$golos."',
	`time_end` = '".$time_ends."',
	`time_end_key` = '".$time_end."',
	`max` = '".$max."',
	`mod` = '".$mod."',
	`sex` = '".$sex."' WHERE `id` = '$act[id]' LIMIT 1");
	
	$_SESSION['message'] = 'Фотоконкурс успешна изменен.';
	header ("Location: /photo_tender/admin/view/?id=".$act['id']."" . SID);
	exit;
	}
	}	
	
	
// заголовок страницы
	
	$set['title'] = 'Фотоконкурсы  : '.$names.' : Редактирование'; 
	
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
	<input type="text" name="name"  value="'.$names.'"><br> 
	
	<b>Описание (max 1000):</b><br> 
	<textarea name="message">'.$messages.'</textarea><br> 
	
	<b>За первое место:</b><br> 
	<div class="s_y">
	<input type="text" name="balls1" value="'.$act['balls1'].'" style="width: 10%;"> Баллов.
	<input type="text" name="money1" value="'.$act['money1'].'" style="width: 10%;"> Монет.
	<input type="text" name="rating1" value="'.$act['rating1'].'" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider1">
	<option selected="'.$act['lider1'].'" value="'.$act['lider1'].'" >'.$act['lider1'].'<option>
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
	<option selected="'.$act['plus1'].'" value="'.$act['plus1'].'" >'.$act['plus1'].'<option>
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
	<input type="text" name="balls2" value="'.$act['balls2'].'" style="width: 10%;"> Баллов.
	<input type="text" name="money2" value="'.$act['money2'].'" style="width: 10%;"> Монет.
	<input type="text" name="rating2" value="'.$act['rating2'].'" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider2">
	<option selected="'.$act['lider2'].'" value="'.$act['lider2'].'" >'.$act['lider2'].'<option>
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
	<option selected="'.$act['plus2'].'" value="'.$act['plus2'].'" >'.$act['plus2'].'<option>
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
	<input type="text" name="balls3" value="'.$act['balls3'].'" style="width: 10%;"> Баллов.
	<input type="text" name="money3" value="'.$act['money3'].'" style="width: 10%;"> Монет.
	<input type="text" name="rating3" value="'.$act['rating3'].'" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider3">
	<option selected="'.$act['lider3'].'" value="'.$act['lider3'].'" >'.$act['lider3'].'<option>
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
	<option selected="'.$act['plus3'].'" value="'.$act['plus3'].'" >'.$act['plus3'].'<option>
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
	<input type="text" name="balls4" value="'.$act['balls4'].'" style="width: 10%;"> Баллов.
	<input type="text" name="money4" value="'.$act['money4'].'" style="width: 10%;"> Монет.
	<input type="text" name="rating4" value="'.$act['rating4'].'" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider4">
	<option selected="'.$act['lider4'].'" value="'.$act['lider4'].'" >'.$act['lider4'].'<option>
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
	<option selected="'.$act['plus4'].'" value="'.$act['plus4'].'" >'.$act['plus4'].'<option>
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
	<input type="text" name="balls5" value="'.$act['balls5'].'" style="width: 10%;"> Баллов.
	<input type="text" name="money5" value="'.$act['money5'].'" style="width: 10%;"> Монет.
	<input type="text" name="rating5" value="'.$act['rating5'].'" style="width: 10%;"> Рейтинга. </br>
	Лидер сайта:
	<select name="lider5">
	<option selected="'.$act['lider5'].'" value="'.$act['lider5'].'" >'.$act['lider5'].'<option>
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
	<option selected="'.$act['plus5'].'" value="'.$act['plus5'].'" >'.$act['plus5'].'<option>
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
	<input type="text" name="golos" value="'.$act['golos'].'" style="width: 10%;"> 
	<br> 
	
	<b>Cколько будет длиться фотоконкурс:</b><br> 
	<select name="time_end">
	<option selected="'.$act['time_end_key'].'" value="'.$act['time_end_key'].'" >'.$act['time_end_key'].'<option>
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
	<input type="text" name="max" value="'.$act['max'].'" style="width: 10%;"> 
	<br> 
	
	<b>Кто может принимать участие:</b><br> 
	<select name="sex">
	<option selected="'.$act['sex'].'" value="'.$act['sex'].'" >'.($act['sex'] == 1 ? 'Все':'').''.($act['sex'] == 2 ? 'Парни':'').''.($act['sex'] == 3 ? 'Девушки':'').'<option>
	<option value="1">Все</option>
	<option value="2">Парни</option>
	<option value="3">Девушки</option>
	</select>
	</br>
	
	<b>Модерация фото:</b><br> 
	<select name="mod">
	<option selected="'.$act['mod'].'" value="'.$act['mod'].'" >'.($act['mod'] == 1 ? 'Включена':'Выключена').'<option>
	<option value="0">Выключена</option>
	<option value="1">Включена</option>
	</select>
	</br>

	<input type="submit" name = "save" value="Далее">
	
	
	</form>
	</div></div>
	<div class="block_y">
	<img src="/photo_tender/ico/arr_back.png" alt="*"> <a href="/photo_tender/admin/view/?id='.$act['id'].'"> Назад</a></div>
	
	';

	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.';header("location: /photo_tender/admin/?");exit;}


// foot
	include_once '../../../sys/inc/tfoot.php';
?>