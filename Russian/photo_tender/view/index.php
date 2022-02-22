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

	$act = mysql_fetch_assoc(mysql_query("SELECT * FROM `photo_tender` WHERE `id` = '".id."' LIMIT 1"));	

// если только существует 	
	
	if (!empty($act)) {

// заголовок страницы

	$set['title'] = 'Фотоконкурсы'; 
	
// head	
	include_once '../../sys/inc/thead.php';
	
	title();
	aut();
	err();

// style

	echo'<link rel="stylesheet" href="/photo_tender/style.css" type="text/css" />';

// view

	$name = htmlspecialchars ($act['name']);
	$image = htmlspecialchars ($act['image']);
	$level = ''.($act['level'] == 0 ? 'Активный':'').' '.($act['level'] == 1 ? 'Завершен':'').'';
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
	
	$count = mysql_result(mysql_query("SELECT COUNT(id) FROM `photo_tender_user` WHERE `tender` = ".$act['id'].""), 0);
	

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
	<img src="/photo_tender/ico/who.png" alt="*"> <a href="/photo_tender/photo/?id='.$act['id'].'"> Добавить фото</a><br>
	<img src="/photo_tender/ico/info.png" alt="*"> <a href="/photo_tender/photo/list.php?id='.$act['id'].'"> Фото участников ('.$count.')</a><br>
	
	</div>
	';
	
	$co = mysql_result(mysql_query("SELECT COUNT FROM `photo_tender_user` WHERE `tender` = ".$act['id']." AND `lider` > '0'"), 0);
	
	if ($co > 0)echo'<div class="st_y"><img src="/photo_tender/ico/trophy.png" alt="*"> Победители:</div>';
	
	$query = mysql_query("SELECT *
    FROM `photo_tender_user`
    WHERE `tender` = '".$act['id']."'
    ORDER BY `count` DESC,
	`like` DESC LIMIT 3");
	
	while ($tender = mysql_fetch_assoc($query))
	{
	$image = htmlspecialchars ($tender['image']);
	$ank = get_user($tender['user']);
	
	echo'
	'.($tender['lider'] > 0 ? '
	<div class="s_y  fot_k">
	<div class="fot_k_2">
	<a href="/photo_tender/photo/view.php?id='.$tender['id'].'">
	<img src="/photo_tender/image/user/'.$image.'" alt="*"></a></div>
	<div class="fot_k_3">
	<span class="fot_k_5">
	<img src="/photo_tender/ico/
	'.($tender['lider'] == 1 ? 'win':'').'
	'.($tender['lider'] == 2 ? 'win2':'').'
	'.($tender['lider'] == 3 ? 'win3':'').'.png" alt="*">
	</span>
	'.group($ank['id']) . user::nick($ank['id']).medal($ank['id']) . online($ank['id']) . '
	'.($tender['closed'] == 1 ? '<span style="color:red;"> - Заблокировано</span>':'').'
	</br>
	
	<img src="/photo_tender/ico/top.png" alt="*">'.$tender['count'].'</span> |
	<img src="/photo_tender/ico/like.gif" alt="*">'.$tender['like'].'</span> |
	<img src="/photo_tender/ico/dlike.gif" alt="*">'.$tender['dlike'].'</span>
	
	</div>
	</div>':'').'
	';
		
	}	
	
	echo'
	</div>
	<div class="block_y">
	<img src="/photo_tender/ico/fk.png" alt="*"> <a href="/photo_tender/"> Фотоконкурсы </a></div>
	';
	
	
	}else{ header("location: /photo_tender/?");}

// foot
	
	include_once '../../sys/inc/tfoot.php';
?>