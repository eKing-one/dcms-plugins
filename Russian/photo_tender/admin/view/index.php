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

// если только существует 	
	
	if (!empty($act)) {

// заголовок страницы

	$set['title'] = 'Фотоконкурсы'; 
	
// head	
	include_once '../../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// view

	$name = htmlspecialchars ($act['name']);
	$image = htmlspecialchars ($act['image']);
	$message = output_text ($act['message']);
	
	$win1 = '
	'.($act['balls1']== 0 ? '':''.$act['balls1'].' балл.').' 
	'.($act['money1']== 0 ? '':''.$act['money1'].' монет.').'
	'.($act['rating1']== 0 ? '':''.$act['rating1'].' % рейтинга.').'
	'.($act['lider1']== 0 ? '':''.$act['lider1'].' дн. услуги лидер сайта.').'
	'.($act['plus1']== 0 ? '':''.$act['plus1'].' дн. услуги оценка 5+.').'
	';
	$win2 = '
	'.($act['balls2']== 0 ? '':''.$act['balls2'].' балл.').' 
	'.($act['money2']== 0 ? '':''.$act['money2'].' монет.').'
	'.($act['rating2']== 0 ? '':''.$act['rating2'].' % рейтинга.').'
	'.($act['lider2']== 0 ? '':''.$act['lider2'].' дн. услуги лидер сайта.').'
	'.($act['plus2']== 0 ? '':''.$act['plus2'].' дн. услуги оценка 5+.').'
	';
	$win3 = '
	'.($act['balls3']== 0 ? '':''.$act['balls3'].' балл.').' 
	'.($act['money3']== 0 ? '':''.$act['money3'].' монет.').'
	'.($act['rating3']== 0 ? '':''.$act['rating3'].' % рейтинга.').'
	'.($act['lider3']== 0 ? '':''.$act['lider3'].' дн. услуги лидер сайта.').'
	'.($act['plus3']== 0 ? '':''.$act['plus3'].' дн. услуги оценка 5+.').'
	';
	
	$win4 = '
	'.($act['balls4']== 0 ? '':''.$act['balls4'].' балл.').' 
	'.($act['money4']== 0 ? '':''.$act['money4'].' монет.').'
	'.($act['rating4']== 0 ? '':''.$act['rating4'].' % рейтинга.').'
	'.($act['lider4']== 0 ? '':''.$act['lider4'].' дн. услуги лидер сайта.').'
	'.($act['plus4']== 0 ? '':''.$act['plus4'].' дн. услуги оценка 5+.').'
	';
	
	$win5 = '
	'.($act['balls5']== 0 ? '':''.$act['balls5'].' балл.').' 
	'.($act['money5']== 0 ? '':''.$act['money5'].' монет.').'
	'.($act['rating5']== 0 ? '':''.$act['rating5'].' % рейтинга.').'
	'.($act['lider5']== 0 ? '':''.$act['lider5'].' дн. услуги лидер сайта.').'
	'.($act['plus5']== 0 ? '':''.$act['plus5'].' дн. услуги оценка 5+.').'
	';
	
	$sex = ''.($act['sex'] == 1 ? 'Все':'').' '.($act['sex'] == 2 ? 'Парни':'').' '.($act['sex'] == 3 ? 'Девушки':'').'';
	$level = ''.($act['level'] == 0 ? 'Активный':'').' '.($act['level'] == 1 ? 'Завершен':'').'';
	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender_user` WHERE `tender` = ".$act['id']." AND `mod` = '1'"), 0);
	
	
	echo'
	<div class="block_y">
	<div class="s_y">
	<div class="fot_k_2">
	<img src="/photo_tender/image/'.($image == null ? 'no_image.jpg':''.$image.'' ).'" alt="*">
	</div>
	<div class="fot_k_3">
	<span class="fot_k_4">Конкурс</span>
	<span class="fot_k_6">'.$level.'</span>
	<br>
	'.$name.'<br></div>
	</div>
	
	<div class="st_y"><img src="/photo_tender/ico/question-shield.png" alt="*"> Информация о фотоконкурсе</div>
	
	<div class="s_y  fot_k">
	
	<img src="/photo_tender/ico/info.png" alt="*"> <b>Описание: </b></br> '.$message.'</br>
	<img src="/photo_tender/ico/trophy.png" alt="*"> <b>Призы: </b></br>
	<img src="/photo_tender/ico/win.png" alt="*"> <b>Место:</b> '.$win1.'</br>
	<img src="/photo_tender/ico/win2.png" alt="*"> <b>Место:</b> '.$win2.' </br>
	<img src="/photo_tender/ico/win3.png" alt="*"> <b>Место:</b>  '.$win3.'</br>
	
	<img src="/photo_tender/ico/games.gif" alt="*"> <b>За участие: </b> '.$win4.'</br>
	<img src="/photo_tender/ico/coins-plus.png" alt="*"> <b>За голосование: </b> '.$win5.'</br>
	
	<img src="/photo_tender/ico/people.gif" alt="*"> <b>1 пользователь:  </b> '.$act['golos'].' голос.</br>
	<img src="/photo_tender/ico/druzya.png" alt="*"> <b>Максимум участников:  </b> '.$act['max'].' пользовател.</br>
	<img src="/photo_tender/ico/users.png" alt="*"> <b>Кто может принимать участие:  </b> '.$sex.'</br>
	
	<img src="/photo_tender/ico/time.png" alt="*"> <b>Конкурс начался:  </b> ' . vremja($act['time']) . '.</br>
	<img src="/photo_tender/ico/alarm-clock.png" alt="*"> '.($act['level'] == 1 ? '<b>Конкурс завершен.</b>':'<b>Конкурс закончится:  </b> ' . vremja($act['time_end']) . '.').'</br>
	
	</div>
	
	<div class="st2_y">
	'.($image == null ? '
	<img src="/photo_tender/ico/who.png" alt="*"> <a href="/photo_tender/admin/photo/?id='.$act['id'].'"> Добавить логотип</a><br>':'
	<img src="/photo_tender/ico/infor.png" alt="*"> <a href="/photo_tender/admin/photo/delete.php?id='.$act['id'].'"> Удалить логотип</a><br>
	' ).'
	<img src="/photo_tender/ico/edit.gif" alt="*"> <a href="/photo_tender/admin/edit/?id='.$act['id'].'"> Редактировать конкурс</a><br>
	<img src="/photo_tender/ico/delete.png" alt="*"> <a href="/photo_tender/admin/delete/?id='.$act['id'].'"> Удалить конкурс</a><br>
	<img src="/photo_tender/ico/'.($act['level'] == 0 ? 'end':'info').'.png" alt="*"> <a href="/photo_tender/admin/clos/?id='.$act['id'].'"> '.($act['level'] == 0 ? 'Завершить':'Активировать').' конкурс</a><br>
	<div style ="border-bottom: 2px solid #51af6e;"></div>
	
	<img src="/photo_tender/ico/info.png" alt="*"> <a href="/photo_tender/admin/mod/?id='.$act['id'].'"> Модерация фото</a> ('.$count.')<br>
	<img src="/photo_tender/ico/infor.png" alt="*"> <a href="/photo_tender/admin/block/?id='.$act['id'].'"> Заблокировать фото</a><br>
	<img src="/photo_tender/ico/cross_light.png" alt="*"> <a href="/photo_tender/admin/closed/?id='.$act['id'].'"> '.($act['closed'] == 0 ? 'Закрыть':'Открыть').' голосование</a><br>
	<img src="/photo_tender/ico/delete.gif" alt="*"> <a href="/photo_tender/admin/del/?id='.$act['id'].'"> Удалить фото</a><br>
	<div style ="border-bottom: 2px solid #51af6e;"></div>
	
	<img src="/photo_tender/ico/top.png" alt="*"> <a href="/photo_tender/admin/null/?id='.$act['id'].'"> Сбросить голоса</a><br>
	<img src="/photo_tender/ico/like.gif" alt="*"> <a href="/photo_tender/admin/like/?id='.$act['id'].'"> Сбросить лайки</a><br>
	<img src="/photo_tender/ico/dlike.gif" alt="*"> <a href="/photo_tender/admin/dlike/?id='.$act['id'].'"> Сбросить дизлайки</a><br>

	</div>
	<div class="block_y"> 
	Внимание при принудительном завершение конкурса , бонусы будут отправлены сразу ,а не по истечения срока. 
	</div>
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/'.($act['level'] == 0 ? 'info':'end').'.png" alt="*"> <a href="/photo_tender/admin/list/?id='.$act['level'].'">'.($act['level'] == 0 ? ' Активные фотоконкурсы':' Завершенные фотоконкурсы').'</a></div>
	<div class="block_y"><img src="/photo_tender/ico/settings.png" alt="*"> <a href="/photo_tender/admin/"> Админка </a>
	</div>
	';
	
	
	}else{ $_SESSION['message'] = 'Ошибка конкурс не существует.'; header("location: /photo_tender/admin/?");}

// foot
	
	include_once '../../../sys/inc/tfoot.php';
?>