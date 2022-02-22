<?

if (isset($_GET['act']) && $_GET['act']=='txt')

{

ob_clean();

ob_implicit_flush();

header('Content-Type: text/plain; charset=utf-8', true);



header('Content-Disposition: attachment; filename="'.retranslit($them['name']).'.txt";');

echo "Тема: $them[name] ($forum[name]/$razdel[name])\r\n";

$q=mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' ORDER BY `time` ASC");



//echo "\r\n";

while ($post = mysql_fetch_assoc($q))

{

echo "\r\n";

$ank=get_user($post['id_user']);

echo "$ank[nick] (".date("j M Y в H:i", $post['time']).")\r\n";







if ($post['cit']!=NULL && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '$post[cit]'"),0)==1)

{

$cit=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '$post[cit]' LIMIT 1"));

$ank_c=get_user($cit['id_user']);

echo "--Цитата--\r\n";

echo "$ank_c[nick] (".date("j M Y в H:i", $cit['time'])."):\r\n";

echo trim(br($cit['msg'],"\r\n"))."\r\n";

echo "----------\r\n";

}



echo trim(br($post['msg'],"\r\n"))."\r\n";



}

echo "\r\nИсточник: http://$_SERVER[SERVER_NAME]/forum/$forum[id]/$razdel[id]/$them[id]/\r\n";

exit;

}




if (isset($_GET['like']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like_users` WHERE `id_them` = '$them[id]' AND `id_post` = '".intval($_GET['like'])."' AND `id_user` = '".$user['id']."' "), 0)==0 && isset($user)) {

$id_post = intval($_GET['like']);
$id_ank = intval($_GET['id_ank']);
$get_ank = get_user($id_ank);
$notification=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$get_ank['id']."' LIMIT 1"));

if ($notification['post'] == 1)
mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$id_ank', '$them[id]', '".$id_post."|them_post_like', '$time')");

$print=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_like` WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' LIMIT 1"));
mysql_query("INSERT INTO `forum_like_users` (`id`, `id_them`, `id_post`, `id_user`, `time`) values('', '$them[id]', '$id_post', '".$user['id']."', '$time')");
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like` WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' "), 0)==0 && isset($user)) {
mysql_query("INSERT INTO `forum_like` (`id_them`, `id_post`, `like`) values('$them[id]', '$id_post', '1')");
mysql_query("UPDATE `user` SET `post_like` = '".($get_ank['post_like']+1)."' WHERE `id` = '$id_ank' ");
} else {

mysql_query("UPDATE `forum_like` SET `like` = '".($print['like']+1)."' WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' ");
mysql_query("UPDATE `user` SET `post_like` = '".($get_ank['post_like']+1)."' WHERE `id` = '$id_ank' ");


}
} 
if(isset($_GET['dislike']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like_users` WHERE `id_them` = '$them[id]' AND `id_post` = '".intval($_GET['dislike'])."' AND `id_user` = '".$user['id']."' "), 0)==0 && isset($user)) {

$id_post = intval($_GET['dislike']);
$id_ank = intval($_GET['id_ank']);
$get_ank = get_user($id_ank);

$notification=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$get_ank['id']."' LIMIT 1"));

if ($notification['post'] == 1)
mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$id_ank', '$them[id]', '".$id_post."|them_post_dislike', '$time')");
$print=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_like` WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' LIMIT 1"));
mysql_query("INSERT INTO `forum_like_users` (`id_them`, `id_post`, `id_user`, `time`) values('$them[id]', '$id_post', '".$user['id']."', '$time')");
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like` WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' "), 0)==0 && isset($user)) {
mysql_query("UPDATE `user` SET `post_dislike` = '".($get_ank['post_dislike']+1)."' WHERE `id` = '$id_ank' ");

mysql_query("INSERT INTO `forum_like` (`id_them`, `id_post`, `dislike`) values('$them[id]', '$id_post', '1')");

} else {

mysql_query("UPDATE `forum_like` SET `dislike` = '".($print['dislike']+1)."' WHERE `id_them` = '$them[id]' AND `id_post` = '$id_post' ");
mysql_query("UPDATE `user` SET `post_dislike` = '".($get_ank['post_dislike']+1)."' WHERE `id` = '$id_ank' ");

}
}






if (isset($user) && isset($_GET['f_del']) && is_numeric($_GET['f_del']) && isset($_SESSION['file'][$_GET['f_del']]))

{

@unlink($_SESSION['file'][$_GET['f_del']]['tmp_name']);

}





if (isset($user) && isset($_GET['zakl']) && $_GET['zakl']==1)

{

if(mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_user` = $user[id] AND `id_them` = '$them[id]'"),0)!=0)

{

$err[]="Тема уже есть в ваших закладках";

}

else {

mysql_query("INSERT INTO `forum_zakl` (`id_user`, `time`,  `id_them`, `time_obn`) values('$user[id]', '$time', '$them[id]', '$time')");

msg('Тема добавлена в закладки');

}

}





elseif (isset($user) && isset($_GET['zakl']) && $_GET['zakl']==0)

{

mysql_query("DELETE FROM `forum_zakl` WHERE `id_user` = '$user[id]' AND `id_them` = '$them[id]'");

msg('Тема удалена из закладок');

}





if (isset($user) && isset($_GET['act']) && $_GET['act']=='new' && isset($_FILES['file_f']) && preg_match('#\.#', $_FILES['file_f']['name']) && isset($_POST['file_s']))

{

copy($_FILES['file_f']['tmp_name'], H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp');

chmod(H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp', 0777);



if (isset($_SESSION['file']))$next_f=count($_SESSION['file']);else $next_f=0;





$file=esc(stripcslashes(htmlspecialchars($_FILES['file_f']['name'])));

$_SESSION['file'][$next_f]['name']=preg_replace('#\.[^\.]*$#i', NULL, $file); // имя файла без расширения

$_SESSION['file'][$next_f]['ras']=strtolower(preg_replace('#^.*\.#i', NULL, $file));

$_SESSION['file'][$next_f]['tmp_name']=H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp';

$_SESSION['file'][$next_f]['size']=filesize(H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp');

$_SESSION['file'][$next_f]['type']=$_FILES['file_f']['type'];







}











if (isset($user) && ($them['close']==0  || $them['close']==1 && user_access('forum_post_close')) && isset($_GET['act']) && $_GET['act']=='new' && isset($_POST['msg']) && !isset($_POST['file_s']))

{

$msg=$_POST['msg'];

if (strlen2($msg)<2)$err='Короткое сообщение';

if (strlen2($msg)>1024)$err='Длина сообщения превышает предел в 1024 символа';



$mat=antimat($msg);

if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;



if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0)$err='Ваше сообщение повторяет предыдущее';



if (!isset($err))

{



if (isset($_POST['cit']) && is_numeric($_POST['cit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '".intval($_POST['cit'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1)

$cit=intval($_POST['cit']); else $cit='null';

mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");

mysql_query("UPDATE `forum_zakl` SET `time_obn` = '$time' WHERE `id_them` = '$them[id]'");

mysql_query("INSERT INTO `forum_p` (`id_forum`, `id_razdel`, `id_them`, `id_user`, `msg`, `time`, `cit`) values('$forum[id]', '$razdel[id]', '$them[id]', '$user[id]', '".my_esc($msg)."', '$time', $cit)");



$post_id=mysql_insert_id();





if (isset($_SESSION['file']) && isset($user))

{

for ($i=0; $i<count($_SESSION['file']);$i++)

{

if (isset($_SESSION['file'][$i]) && is_file($_SESSION['file'][$i]['tmp_name']))

{

mysql_query("INSERT INTO `forum_files` (`id_post`, `name`, `ras`, `size`, `type`) values('$post_id', '".$_SESSION['file'][$i]['name']."', '".$_SESSION['file'][$i]['ras']."', '".$_SESSION['file'][$i]['size']."', '".$_SESSION['file'][$i]['type']."')");

$file_id=mysql_insert_id();

copy($_SESSION['file'][$i]['tmp_name'], H.'sys/forum/files/'.$file_id.'.frf');

unlink($_SESSION['file'][$i]['tmp_name']);

}

}

unset($_SESSION['file']);

}



unset($_SESSION['msg']);



	$ank=get_user($them['id_user']); // Определяем автора





		mysql_query("UPDATE `user` SET `rating_tmp` = '".($user['rating_tmp']+1)."' WHERE `id` = '$user[id]' LIMIT 1");

		mysql_query("UPDATE `forum_r` SET `time` = '$time' WHERE `id` = '$razdel[id]' LIMIT 1");

		mysql_query("UPDATE `forum_t` SET `time_create` = '$time' WHERE `id` = '$them[id]' LIMIT 1");

		

/*

====================================

Обсуждения

====================================

*/

$q = mysql_query("SELECT * FROM `frends` WHERE `user` = '".$them['id_user']."' AND `i` = '1'");

while ($f = mysql_fetch_array($q))

{

$a=get_user($f['frend']);

$discSet = mysql_fetch_array(mysql_query("SELECT * FROM `discussions_set` WHERE `id_user` = '".$a['id']."' LIMIT 1")); // Общая настройка обсуждений



if ($f['disc_forum']==1 && $discSet['disc_forum']==1) /* Фильтр рассылки */

{



	// друзьям автора

	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `discussions` WHERE `id_user` = '$a[id]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1"),0)==0)

	{

	if ($them['id_user']!=$a['id'] || $a['id'] != $user['id'])

	mysql_query("INSERT INTO `discussions` (`id_user`, `avtor`, `type`, `time`, `id_sim`, `count`) values('$a[id]', '$them[id_user]', 'them', '$time', '$them[id]', '1')"); 

	}

	else

	{

	$disc = mysql_fetch_array(mysql_query("SELECT * FROM `discussions` WHERE `id_user` = '$a[id_user]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1"));

	if ($them['id_user']!=$a['id'] || $a['id'] != $user['id'])

	mysql_query("UPDATE `discussions` SET `count` = '".($disc['count']+1)."', `time` = '$time' WHERE `id_user` = '$a[id]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1");

	}



}



}



// отправляем автору

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `discussions` WHERE `id_user` = '$them[id_user]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1"),0)==0)

{

if ($them['id_user'] != $user['id'] && $them['id_user'] != $ank_otv['id'])

mysql_query("INSERT INTO `discussions` (`id_user`, `avtor`, `type`, `time`, `id_sim`, `count`) values('$them[id_user]', '$them[id_user]', 'them', '$time', '$them[id]', '1')"); 

}

else

{

$disc = mysql_fetch_array(mysql_query("SELECT * FROM `discussions` WHERE `id_user` = '$them[id_user]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1"));

if ($them['id_user'] != $user['id'] && $them['id_user'] != $ank_otv['id'] )

mysql_query("UPDATE `discussions` SET `count` = '".($disc['count']+1)."', `time` = '$time' WHERE `id_user` = '$them[id_user]' AND `type` = 'them' AND `id_sim` = '$them[id]' LIMIT 1");

}



		

		/*

		==========================

		Уведомления об ответах

		==========================

		*/

		if (isset($user) && $respons==TRUE){

		$notifiacation=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$ank_otv['id']."' LIMIT 1"));

			

			if ($notifiacation['komm'] == 1)

			mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$ank_otv[id]', '$them[id]', 'them_komm', '$time')");

		

		}



		

		

$_SESSION['message'] = 'Сообщение успешно добавлено';

header("Location: ?page=".intval($_GET['page'])."");

exit;

}

}





/*

================================

Модуль жалобы на пользователя

и его сообщение либо контент

в зависимости от раздела

================================

*/

if (isset($_GET['spam']) && isset($user))

{

$mess = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '".intval($_GET['spam'])."' limit 1"));

$spamer = get_user($mess['id_user']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `spamus` WHERE `id_user` = '$user[id]' AND `id_spam` = '$spamer[id]' AND `razdel` = 'forum' AND `spam` = '".$mess['msg']."'"),0)==0)

{

if (isset($_POST['spamus']))

{

if ($mess['id_user']!=$user['id'])

{

$msg=mysql_real_escape_string($_POST['spamus']);



if (strlen2($msg)<3)$err='Укажите подробнее причину жалобы';

if (strlen2($msg)>1512)$err='Длина текста превышает предел в 512 символов';



if(isset($_POST['types'])) $types=intval($_POST['types']);

else $types='0'; 

if (!isset($err))

{

mysql_query("INSERT INTO `spamus` (`id_object`, `id_user`, `msg`, `id_spam`, `time`, `types`, `razdel`, `spam`) values('$them[id]', '$user[id]', '$msg', '$spamer[id]', '$time', '$types', 'forum', '".my_esc($mess['msg'])."')");

$_SESSION['message'] = 'Заявка на рассмотрение отправлена'; 

header("Location: /forum/$forum[id]/$razdel[id]/$them[id]/?spam=$mess[id]&page=$pageEnd");

exit;

}

}

}

}

aut();

err();



if (mysql_result(mysql_query("SELECT COUNT(*) FROM `spamus` WHERE `id_user` = '$user[id]' AND `id_spam` = '$spamer[id]' AND `razdel` = 'forum'"),0)==0)

{

echo "<div class='mess'>Ложная информация может привести к блокировке ника. 

Если вас постоянно достает один человек - пишет всякие гадости, вы можете добавить его в черный список.</div>";

echo "<form class='nav1' method='post' action='/forum/$forum[id]/$razdel[id]/$them[id]/?spam=$mess[id]&amp;page=".intval($_GET['page'])."'>\n";

echo "<b>Пользователь:</b> ";

echo " ".status($spamer['id'])."  ".group($spamer['id'])." <a href=\"/info.php?id=$spamer[id]\">$spamer[nick]</a>\n";

echo "".medal($spamer['id'])." ".online($spamer['id'])." (".vremja($mess['time']).")<br />";

echo "<b>Нарушение:</b> <font color='green'>".output_text($mess['msg'])."</font><br />";

echo "Причина:<br />\n<select name='types'>\n";

echo "<option value='1' selected='selected'>Спам/Реклама</option>\n";

echo "<option value='2' selected='selected'>Мошенничество</option>\n";

echo "<option value='3' selected='selected'>Оскорбление</option>\n";

echo "<option value='0' selected='selected'>Другое</option>\n";

echo "</select><br />\n";

echo "Комментарий:";

echo $tPanel."<textarea name=\"spamus\"></textarea><br />";

echo "<input value=\"Отправить\" type=\"submit\" />\n";

echo "</form>\n";

}else{

echo "<div class='mess'>Жалоба на <font color='green'>$spamer[nick]</font> будет рассмотрена в ближайшее время.</div>";

}



echo "<div class='foot'>\n";

echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?page=".intval($_GET['page'])."'>Назад</a><br />\n";

echo "</div>\n";

include_once '../sys/inc/tfoot.php';

exit;

}









if ($them['close']==1)

	$err = 'Тема закрыта для обсуждения';





if (isset($user) &&  $user['balls']>=50 && $user['rating']>=0 && isset($_GET['id_file'])

&&

mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_files` WHERE `id` = '".intval($_GET['id_file'])."'"), 0)==1

&&

 mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_files_rating` WHERE `id_user` = '$user[id]' AND `id_file` = '".intval($_GET['id_file'])."'"), 0)==0)

{

if (isset($_GET['rating']) && $_GET['rating']=='down')

{

mysql_query("INSERT INTO `forum_files_rating` (`id_user`, `id_file`, `rating`) values('$user[id]', '".intval($_GET['id_file'])."', '-1')");

msg ('Ваш отрицательный отзыв принят');

}

elseif(isset($_GET['rating']) && $_GET['rating']=='up')

{

mysql_query("INSERT INTO `forum_files_rating` (`id_user`, `id_file`, `rating`) values('$user[id]', '".intval($_GET['id_file'])."', '1')");

msg ('Ваш положительный отзыв принят');

}

}



	$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]'"),0);

	$k_page=k_page($k_post,$set['p_str']);

	$page=page($k_page);

	$start=$set['p_str']*$page-$set['p_str'];



$avtor=get_user($them['id_user']);

err();

aut();

echo "<div class='foot'>";

echo '<a href="/forum/'.$forum['id'].'/'.$razdel['id'].'/">'.htmlspecialchars($razdel['name']).'</a> | <b>'.output_text($them['name']).'</b>';

echo "</div>\n";





/*

======================================

Перемещение темы

======================================

*/

if (isset($_GET['act']) && $_GET['act']=='mesto' && (user_access('forum_them_edit') || $ank2['id']==$user['id']))

{

echo "<form method=\"post\" action=\"/forum/$forum[id]/$razdel[id]/$them[id]/?act=mesto&amp;ok\">\n";



echo "<div class='mess'>";

echo "Перемещение темы <b>".output_text($them['name'])."</b>\n";

echo "</div>";



echo "<div class='main'>";

echo "Раздел:<br />\n";

echo "<select name=\"razdel\">\n";



if (user_access('forum_them_edit')){

$q = mysql_query("SELECT * FROM `forum_f` ORDER BY `pos` ASC");

while ($forums = mysql_fetch_assoc($q))

{

echo "<optgroup label='$forums[name]'>\n";

$q2 = mysql_query("SELECT * FROM `forum_r` WHERE `id_forum` = '$forums[id]' ORDER BY `time` DESC");

while ($razdels = mysql_fetch_assoc($q2))

{

echo "<option".($razdel['id']==$razdels['id']?' selected="selected"':null)." value=\"$razdels[id]\">" . htmlspecialchars($razdels['name']) . "</option>\n";

}

echo "</optgroup>\n";

}

}

else

{



$q2 = mysql_query("SELECT * FROM `forum_r` WHERE `id_forum` = '$forum[id]' ORDER BY `time` DESC");

while ($razdels = mysql_fetch_assoc($q2))

{

echo "<option".($razdel['id']==$razdels['id']?' selected="selected"':null)." value='$razdels[id]'>" . htmlspecialchars($razdels['name']) . "</option>\n";

}

}

echo "</select><br />\n";



echo "<input value=\"Переместить\" type=\"submit\" /> \n";

echo "<img src='/style/icons/delete.gif' alt='*'> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>Отмена</a><br />\n";

echo "</form>\n";



echo "</div>";



echo "<div class='foot'>";

echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?'>В тему</a><br />\n";

echo "</div>";





include_once '../sys/inc/tfoot.php';

exit;

}





/*

======================================

Редактирование темы

======================================

*/

if (isset($_GET['act']) && $_GET['act']=='set' && (user_access('forum_them_edit') || $ank2['id']==$user['id']))

{

echo "<form method='post' action='/forum/$forum[id]/$razdel[id]/$them[id]/?act=set&amp;ok'>\n";

echo "<div class='mess'>";

echo "Редактирование темы <b>".output_text($them['name'])."</b>\n";

echo "</div>";



echo "<div class=\"main\">\n";

echo "Название:<br />\n";

echo "<input name='name' type='text' maxlength='32' value='".htmlspecialchars($them['name'])."' /><br />\n";



echo "Сообщение:$tPanel<textarea name=\"msg\">".htmlspecialchars($them['text'])."</textarea><br />\n";



if ($user['level']>0){

if ($them['up']==1)$check=' checked="checked"';else $check=NULL;

echo "<label><input type=\"checkbox\"$check name=\"up\" value=\"1\" /> Всегда наверху</label><br />\n";

}

if ($them['close']==1)$check=' checked="checked"';else $check=NULL;

echo "<label><input type=\"checkbox\"$check name=\"close\" value=\"1\" /> Закрыть</label><br />\n";





if ($ank2['id']!=$user['id']){

echo "<label><input type=\"checkbox\" name=\"autor\" value=\"1\" /> Забрать у автора права</label><br />\n";

}



echo "<input value=\"Изменить\" type=\"submit\" /> \n";

echo "<img src='/style/icons/delete.gif' alt='*'> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/'>Отмена</a><br />\n";

echo "</form>\n";

echo "</div>";



echo "<div class='foot'>";

echo "<img src='/style/icons/str2.gif' alt='*'> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?'>В тему</a><br />\n";

echo "</div>";





include_once '../sys/inc/tfoot.php';

exit;

}





		if (user_access('forum_post_ed') && isset($_GET['del'])) // удаление поста

		{

			mysql_query("DELETE FROM `forum_p` WHERE `id` = '" . intval($_GET['del']) . "' LIMIT 1");

			$_SESSION['message'] = 'Сообщение успешно удалено'; 

			header("Location: /forum/$forum[id]/$razdel[id]/$them[id]/?page=" . intval($_GET['page']) . "");

			exit;

		}





/*

======================================

Удаление темы

======================================

*/

if (isset($_GET['act']) && $_GET['act']=='del' && user_access('forum_them_del') && ($ank2['level']<=$user['level'] || $ank2['id']==$user['id']))

{

echo "<div class=\"mess\">\n";

echo "Подтвердите удаление темы <b>".output_text($them['name'])."</b><br />\n";

echo "</div>\n";



echo "<div class=\"main\">\n";

echo "[<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?act=delete&amp;ok\"><img src='/style/icons/ok.gif' alt='*'> Да</a>] [<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/\"><img src='/style/icons/delete.gif' alt='*'> Нет</a>]<br />\n";

echo "</div>\n";





echo "<div class='foot'>";

echo "<img src='/style/icons/fav.gif' alt='*'> <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?'>В тему</a><br />\n";

echo "</div>";





include_once '../sys/inc/tfoot.php';

exit;

}













if ($page == 1)

{

/*

======================================

Время и содержание темы

======================================

*/

echo "<div class='nav1'><img src='/style/icons/font.png' alt='*' /> Создана: ".vremja($them['time'])."</div>";

echo "<div class='nav2'>".output_text($them['text'])."</div>";







/*

======================================

В закладки

======================================

*/

if (isset($user))

{

echo "<div class='mess'>";

$markinfo=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_them` = '".$them['id']."'"),0);

echo "<img src='/style/icons/fav.gif' alt='*' /> ";

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_them` = '$them[id]' AND `id_user` = '$user[id]'"),0)==0)

echo " <a href=\"?page=$page&amp;zakl=1\" title='Добавить в закладки'>Добавить в закладки</a><br />\n";

else

{

mysql_query("UPDATE `forum_zakl` SET `time` = '".time()."' WHERE `id_them` = '$them[id]' AND `id_user` = '$user[id]'");

echo " <a href=\"?page=$page&amp;zakl=0\" title='Удалить из закладок'>Удалить из закладок</a><br />\n";

}

echo "</div>";

}







/*

======================================

Автор темы

======================================

*/

echo "<div class='main'>Автор: ";

echo " ".group($avtor['id'])."  <a href='/info.php?id=$avtor[id]' title='Анкета $avtor[nick]'>$avtor[nick]</a>";

echo " ".medal($avtor['id'])." ".online($avtor['id'])."";

/*------------Вывод статуса-------------*/

$status=mysql_fetch_assoc(mysql_query("SELECT * FROM `status` WHERE `pokaz` = '1' AND `id_user` = '$avtor[id]' LIMIT 1"));

if ($status['msg'] && $set['st']==1)

{

echo "<div class='st_1'></div>";

echo "<div class='st_2'>";

echo "".output_text($status['msg'])."";

echo "</div>\n";

}

/*---------------------------------------*/

echo "</div>\n";

}



/*

======================================

Кнопки действия с темой

======================================

*/

if (isset($user) && (((!isset($_GET['act']) || $_GET['act']!='post_delete') && (user_access('forum_post_ed') || $ank2['id']==$user['id']))

|| ((user_access('forum_them_edit') || $ank2['id']==$user['id']))

|| (user_access('forum_them_del') || $ank2['id']==$user['id']))){

echo "<div class=\"foot\">\n";



if (user_access('forum_them_edit') || $ank2['id']==$user['id']){

echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=mesto'><img src='/style/forum/inc/perem.png' alt='*'></a> | \n";

echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=set'><img src='/style/forum/inc/izm.png' alt='*'></a>\n";

}

if (user_access('forum_them_del') || $ank2['id']==$user['id']){

echo " | <a href='/forum/$forum[id]/$razdel[id]/$them[id]/?act=del'><img src='/style/forum/inc/udl.png' alt='*'></a>\n";

}

echo "</div>\n";

}









echo "<div class='foot'>Комментарии:</div>";





/*------------сортировка по времени--------------*/

if (isset($user)) 

{

echo "<div id='comments' class='menus'>";

echo "<div class='webmenu'>";

echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=$page&amp;sort=1' class='".($user['sort']==1?'activ':'')."'>Внизу</a>";

echo "</div>"; 

echo "<div class='webmenu'>";

echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/?page=$page&amp;sort=0' class='".($user['sort']==0?'activ':'')."'>Вверху</a>";

echo "</div>"; 

echo "</div>";

}

/*---------------alex-borisi---------------------*/




if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){$lim=NULL;}else $lim=" LIMIT $start, $set[p_str]";

$q=mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' ORDER BY `time` $sort$lim");

if (mysql_num_rows($q)==0) {

echo "<div class='mess'>";

echo "Нет сообщений в теме\n";

echo "</div>";

}



while ($post = mysql_fetch_assoc($q))

{



$ank=get_user($post['id_user']);





/*-----------зебра-----------*/ 

if ($num==0){

	echo '<div class="nav1">';

	$num=1;

}

elseif ($num==1){

	echo '<div class="nav2">';

	$num=0;}

/*---------------------------*/



if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete')

{

echo '<input type="checkbox" name="post_'.$post['id'].'" value="1" />';

}



echo ''.group($ank['id']).' <a href="/info.php?id='.$ank['id'].'" title="Анкета '.$ank['nick'].'">'.$ank['nick'].'</a> ';



	if ($them['close']==0) // если тема закрыта, то скрываем кнопку

	{

		if (isset($user) &&  $user['id']!=$ank['id'] && $ank['id']!=0) // Кроме автора поста и системы

		{

			echo '<a href="/forum/'.$forum['id'].'/'.$razdel['id'].'/'.$them['id'].'/?response='.$ank['id'].'&amp;page='.$page.'" title="Ответить '.$ank['nick'].'">[*]</a> ';

		}

	}

	

echo medal($ank['id']) . online($ank['id']). ' ('.vremja($post['time']).')<br />';





$postBan = mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE (`razdel` = 'all' OR `razdel` = 'forum') AND `post` = '1' AND `id_user` = '$ank[id]' AND (`time` > '$time' OR `navsegda` = '1')"), 0);

if ($postBan == 0) // Блок сообщения

{	

	if ($them['id_user'] == $post['id_user']) // Отмечаем автора темы

		echo '<font color="green">Автор темы</font><br />';	



/*------------Вывод статуса-------------*/

$status=mysql_fetch_assoc(mysql_query("SELECT * FROM `status` WHERE `pokaz` = '1' AND `id_user` = '$ank[id]' LIMIT 1"));

if ($status['id'] && $set['st']==1)

{

echo "<div class='st_1'></div>";

echo "<div class='st_2'>";

echo "".output_text($status['msg'])."";

echo "</div>\n";

}

/*---------------------------------------*/



	# Цитирование поста

	if ($post['cit']!=NULL && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '$post[cit]'"),0)==1)

	{

		$cit=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '$post[cit]' LIMIT 1"));

		$ank_c=get_user($cit['id_user']);

		echo '<div class="cit">

		  <b>'.$ank_c['nick'].' ('.vremja($cit['time']).'):</b><br />

		  '.output_text($cit['msg']).'<br />

		  </div>';

	}

	





		



	echo output_text($post['msg']).'<br />'; // Посты темы

$show = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_like` WHERE `id_them` = '".$them['id']."' AND `id_post` = '".$post['id']."' "));
if (!isset($show['like']) || $show['like'] == NULL)$show['like']=0;
if (!isset($show['dislike']) || $show['dislike'] == NULL)$show['dislike']=0;
echo '<div style="text-align:right;">';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_like_users` WHERE `id_them` = '$them[id]' AND `id_post` = '".$post['id']."' AND `id_user` = '".$user['id']."' "), 0)==0 && $user['id'] != $ank['id'] && $user['date_reg']<time()-10800){
echo "<a href='/forum/".$forum['id']."/".$razdel['id']."/".$them['id']."/?like=".$post['id']."&amp;page=".$page."&amp;id_ank=".$ank['id']."'><img src='/forum/img_rat/like.png'></a> <a href='/forum/us_like.php?id_post=".$post['id']."&amp;id_them=".$them['id']."&amp;liked'>".$show['like']."</a>\n";
echo "<a href='/forum/".$forum['id']."/".$razdel['id']."/".$them['id']."/?dislike=".$post['id']."&amp;page=".$page."&amp;id_ank=".$ank['id']."'><img src='/forum/img_rat/dislike.png'></a> <a href='/forum/us_like.php?id_post=".$post['id']."&amp;id_them=".$them['id']."&amp;disliked'>".$show['dislike']."</a><br />";
} else {
echo "<img src='/forum/img_rat/like.png'> <a href='/forum/us_like.php?id_post=".$post['id']."&amp;id_them=".$them['id']."&amp;liked'>".$show['like']."</a>\n";
echo "<img src='/forum/img_rat/dislike.png'> <a href='/forum/us_like.php?id_post=".$post['id']."&amp;id_them=".$them['id']."&amp;disliked'>".$show['dislike']."</a><br />";
}

echo '</div><br />';
	echo '<table>';
	include H.'/forum/inc/file.php'; // Прекрепленные файлы
	echo '</table>';








}else{

	echo output_text($banMess).'<br />';

}



if (isset($user))

{

echo '<div style="text-align:right;">';



	if ($them['close']==0) // если тема закрыта, то скрываем кнопки

	{

		if ($user['id']!=$ank['id'] && $ank['id']!=0) // Кроме автора поста и системы 

		{

			echo '<a href="/mail.php?id='.$ank['id'].'" title="Личное сообщение '.$ank['nick'].'"  class="link_s"><img src="/style/icons/messagebox.png" alt="*"></a> ';

		}

		if ($ank['id']!=0) // Только для юзеров цитирование

			echo '<a href="/forum/'.$forum['id'].'/'.$razdel['id'].'/'.$them['id'].'/'.$post['id'].'/cit" title="Цитировать '.$ank['nick'].'"  class="link_s"><img src="/style/icons/quote.png" alt="*"></a> ';

	}

	

	if (user_access('forum_post_ed') && ($ank['level']<=$user['level'] || $ank['level']==$user['level'] && $ank['id']==$user['id'])) // Редактирование поста

		echo "<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/edit\" title='Изменить пост $ank[nick]'  class='link_s'><img src='/style/icons/edit.gif' alt='*'> </a> \n";

	elseif ($user['id']==$post['id_user'] && $post['time']>time()-600) // Редактирование поста если не прошло 10 минут

		echo "<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/edit\" title='Изменить мой пост'  class='link_s'><img src='/style/icons/edit.gif' alt='*'> (".($post['time']+600-time())." сек)</a> \n";



		if ($user['id']!=$ank['id'] && $ank['id']!=0) // Кроме автора поста и системы 

		{

			echo "<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?spam=$post[id]&amp;page=$page\" title='Это спам'  class='link_s'><img src='/style/icons/blicon.gif' alt='*' title='Это спам'></a>\n";

		}

		

		if (user_access('forum_post_ed')) // удаление поста

		{

			echo "<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?del=$post[id]&amp;page=$page\" title='Удалить'  class='link_s'><img src='/style/icons/delete.gif' alt='*' title='Удалить'></a>\n";

		}

	

echo '</div>';

}



echo ' </div>';

}








if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){}

elseif ($k_page>1)str("/forum/$forum[id]/$razdel[id]/$them[id]/?",$k_page,$page); // Вывод страниц



if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){}

elseif (isset($user) && ($them['close']==0 || $them['close']==1 && user_access('forum_post_close')))

{



	if (isset($user))

	{

		echo "<div class='foot'>";

		echo 'Новое сообщение:';

		echo "</div>";

	}

	

if ($user['set_files']==1)

echo "<form method='post' name='message' enctype='multipart/form-data' action='/forum/$forum[id]/$razdel[id]/$them[id]/new?page=$page&amp;$passgen&amp;".$go_otv."'>\n";

else

echo "<form method='post' name='message' action='/forum/$forum[id]/$razdel[id]/$them[id]/new?page=$page&amp;$passgen&amp;".$go_otv."'>\n";

if (isset($_POST['msg']) && isset($_POST['file_s']))$msg2=output_text($_POST['msg'],false,true,false,false,false); else $msg2=NULL;





if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))

include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';

else

echo "$tPanel<textarea name=\"msg\">$otvet$msg2</textarea><br />\n";



if ($user['set_files']==1){

if (isset($_SESSION['file']))

{

echo "Прикрепленные файлы:<br />\n";

for ($i=0; $i<count($_SESSION['file']);$i++)

{

if (isset($_SESSION['file'][$i]) && is_file($_SESSION['file'][$i]['tmp_name']))

{

echo "<img src='/style/themes/$set[set_them]/forum/14/file.png' alt='' />\n";

echo $_SESSION['file'][$i]['name'].'.'.$_SESSION['file'][$i]['ras'].' (';

echo size_file($_SESSION['file'][$i]['size']);

echo ") <a href='/forum/$forum[id]/$razdel[id]/$them[id]/d_file$i' title='Удалить из списка'><img src='/style/themes/$set[set_them]/forum/14/del_file.png' alt='' /></a>\n";

echo "<br />\n";

}

}

}



echo "<input name='file_f' type='file' /><br />\n";

echo "<input name='file_s' value='Прикрепить файл' type='submit' /><br />\n";

}



echo '<input name="post" value="Отправить" type="submit" /><br />

	 </form>';

}



?>