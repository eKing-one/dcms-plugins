<?php

/* ==========================
------ Автор Dark_AKC -------
--- Барыги будут морально ---
-------- ИЗНАСИЛОВАНЫ -------
===========================*/

include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';


if(isset($_GET['admin']) && $_GET['admin'] == 'del' && isset($user['id']) && user_access('adm_mysql') ){ // Удаляем фсе накуй =)))
	mysql_query("TRUNCATE`lenta_site`");
}


if(isset($_GET['admin']) && isset($user['id']) && user_access('adm_mysql')){
	$set['title']='Настройки отображения';
	include_once '../../sys/inc/thead.php';
	title();
	aut(); // форма авторизации

	$lenta_set = mysql_fetch_assoc(mysql_query("SELECT * FROM `lenta_site_set`"));
	if(isset($_POST['reg']) or isset($_POST['forum_komm']) or isset($_POST['guest']) or isset($_POST['news_komm']) or isset($_POST['note']) or isset($_POST['note_komm'])){
		
		$reg = intval($_POST['reg']);
		$forum_komm = intval($_POST['forum_komm']);
		$guest = intval($_POST['guest']);
		$news_komm = intval($_POST['news_komm']);
		$note = intval($_POST['note']);
		$note_komm = intval($_POST['note_komm']);

		mysql_query("UPDATE `lenta_site_set` SET `reg` = '$reg', `forum_komm` = '$forum_komm', `guest` = '$guest', `news_komm` = '$news_komm', `note` = '$note',`note_komm` = '$note_komm' WHERE `id` = '1'");
		
		
		header('Location: index.php?admin');
		$_SESSION['messege'] = 'Настройки сохранены';
	}
	
	
	echo "<form method='post' class='mess'>
		Новые регистрации<br/>
		<input name='reg' type='radio' ".($lenta_set['reg']==1?' checked="checked"':null)." value='1' />Да
		<input name='reg' type='radio' ".($lenta_set['reg']==0?' checked="checked"':null)." value='0' />Нет
		
		<br/>Сообщения форума<br/>
		<input name='forum_komm' type='radio' ".($lenta_set['forum_komm']==1?' checked="checked"':null)." value='1' />Да
		<input name='forum_komm' type='radio' ".($lenta_set['forum_komm']==0?' checked="checked"':null)." value='0' />Нет
		
		<br/>Сообщения гостевой<br/>
		<input name='guest' type='radio' ".($lenta_set['guest']==1?' checked="checked"':null)." value='1' />Да
		<input name='guest' type='radio' ".($lenta_set['guest']==0?' checked="checked"':null)." value='0' />Нет
		
		<br/>Сообщения к новостям<br/>
		<input name='news_komm' type='radio' ".($lenta_set['news_komm']==1?' checked="checked"':null)." value='1' />Да
		<input name='news_komm' type='radio' ".($lenta_set['news_komm']==0?' checked="checked"':null)." value='0' />Нет
		
		<br/>Новые дневники<br/>
		<input name='note' type='radio' ".($lenta_set['note']==1?' checked="checked"':null)." value='1' />Да
		<input name='note' type='radio' ".($lenta_set['note']==0?' checked="checked"':null)." value='0' />Нет
		
		<br/>Сообщения в дневниках<br/>
		<input name='note_komm' type='radio' ".($lenta_set['note_komm']==1?' checked="checked"':null)." value='1' />Да
		<input name='note_komm' type='radio' ".($lenta_set['note_komm']==0?' checked="checked"':null)." value='0' />Нет
	
		<br/><input type='submit' name='submit' value='Сохранить'/> [<img src='/style/icons/delete.gif'/> <a href='?admin=del'>Удалить всю ленту</a>]
	</form>";
	
	
	
	echo '<div class="foot"><img src="/style/icons/str2.gif"/> <a href="index.php">Назад</a></div>';
	include_once H.'sys/inc/tfoot.php';
	exit();
}


$set['title']='Лента сайта';
include_once '../../sys/inc/thead.php';
title();
aut(); // форма авторизации


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `lenta_site`"),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str'] * $page - $set['p_str'];

if ($k_post == 0) echo '<div class="mess">Нет новых событий</div>';

$q = mysql_query("SELECT * FROM `lenta_site` ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while($lenta = mysql_fetch_assoc($q)){
	$avtor = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$lenta[id_user]' "));

	/*-----------зебра-----------*/
	if ($num==0){
		echo '<div class="nav1">';
		$num=1;
	}elseif ($num==1){
		echo '<div class="nav2">';
		$num=0;
	}
	/*---------------------------*/

	echo group($avtor['id']).' <a href="/info.php?id='.$avtor['id'].'">'.$avtor['nick'].'</a>';
	echo medal($avtor['id']) . online($avtor['id']) ;

	echo htmlspecialchars($lenta['opis']).' <a href="/'.$lenta['link'].'">'.htmlspecialchars($lenta['title']).'</a><br/>'.vremja($lenta['time']);

	echo '</div>';
} 


 if ($k_page>1)str('index.php?',$k_page,$page); 

 if (user_access('adm_mysql')) echo '<div class="foot"><img src="/style/icons/str.gif"/> <a href="?admin">Настройки</a></div>';

include_once H.'sys/inc/tfoot.php';
?>
