<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';

if (!isset($_GET['id']) || $_GET['id'] == 0)
{
	header('Location: index.php');
	exit;
}

// id видео
$ID = intval($_GET['id']);

$video = mysql_fetch_assoc(mysql_query("SELECT * FROM `video` WHERE `id` = '$ID' LIMIT 1"));

// Если видео не существует

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `video` WHERE `id` = '$ID'"),0) == 0)
{
	header('Location: index.php');
	exit;
}

// Автор видео 

$ank = get_user($video['id_user']);

// Запись просмотра стр с видео

if (!isset($_SESSION['show_' . $video['id']]))
{
	mysql_query("UPDATE `video` SET `count` = '".($video['count']+1)."' WHERE `id` = '$video[id]' LIMIT 1");
	$_SESSION['show_' . $video['id']] = $video['id'];
}


// Заголовок страницы

$set['title']='Лучшее видео Youtube | ' . htmlspecialchars($video['name']);


// Удаляем видео

if (isset($user) && ($user['id'] == $ank['id'] || $user['level'] > 2) && isset($_GET['delete']))
{
	mysql_query("DELETE FROM `video` WHERE `id` = '$ID' LIMIT 1");
	mysql_query("DELETE FROM `video_like` WHERE `id_video` = '$ID'");
	mysql_query("DELETE FROM `video_komm` WHERE `id_video` = '$ID'");
	
	if ($user['id'] != $ank['id'])
	{
		$msg = $user['group_name'] . ' [url=/info.php?id=' . $user['id'] . ']' . $user['nick'] . '[/url] удалил видео: [br][green]' . $video['name'] . '[/green][br][red]Старайтесь больше не нарушать правила нашего сайта![/red]';
		
		mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '".my_esc($msg)."', '$time')");
	}
	
	$_SESSION['message'] = 'Видео успешно удалено';
	header('Location: category.php?id=' . $video['id_category']);
	exit;
}

// Отправка комментариев

if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>512){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `video_komm` WHERE `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0)
{$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){


mysql_query("INSERT INTO `video_komm` (id_user, time, msg, id_video) values('$user[id]', '$time', '".my_esc($msg)."', '$ID')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."',  `rating_tmp` = '".($user['rating_tmp']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['message']='Сообщение успешно добавлено';
header ("Location: video.php?id=" . $video['id']);
}
}


// Удаление поста

if (isset($_GET['post']) && ($user['level'] > 2 || $user['id'] == $video['id_user']))
{
	mysql_query("DELETE FROM `video_komm` WHERE `id` = '" . intval($_GET['post']) . "'");
	$_SESSION['message']='Сообщение успешно удалено';
	header ("Location: video.php?page=" . intval($_GET['page']) . "&id=" . $video['id']);
	exit;
}


// Мне нравится

if (isset($_GET['like']) && ($_GET['like'] == 1 || $_GET['like'] == 0)){

	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `id_user` = '".$user['id']."' AND `id_video` = '".$video['id']."' LIMIT 1"),0)==0){

		mysql_query("INSERT INTO `video_like` (`id_video`, `id_user`, `like`) VALUES ('$video[id]', '$user[id]', '1')");
		$_SESSION['message'] = 'Ваш голос засчитан';
		header("Location: video.php?id=$video[id]&page=".intval($_GET['page']));
		exit;

	}

}


// Шапка
include_once '../../sys/inc/thead.php';
title();
aut(); // форма авторизации
err();

echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $video['id_category'] . '">В категорию</a><br />';
echo '</div>';


echo '<div class="nav2">';

echo '<img src="img/video.png" /> ' . htmlspecialchars($video['name']);

echo '</div>';


echo '<div class="nav1">';

echo '<iframe width="420" height="340" style="max-width:100%;" src="http://www.youtube.com/embed/' . $video['url'] . '?feature=player_detailpage" frameborder="1" allowfullscreen></iframe><br />';
 
echo '<a href="http://www.youtube.com/watch?v=' . $video['url'] . '">' . htmlspecialchars($video['name']) . '</a>'; 
  
echo '</div>';

// Мне нравится

	echo '<div class="main">';
if (isset($user) && $user['id']!=$ank['id']){

	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `id_user` = '".$user['id']."' AND `id_video` = '".$video['id']."' LIMIT 1"),0)==0)
	echo '[<img src="/style/icons/like.gif" alt="*" /> <a href="video.php?id=' . $video['id'] . '&amp;like=1">Нравится</a>] [<a href="video.php?id=' . $video['id'] . '&amp;like=0"><img src="/style/icons/dlike.gif" alt="*" /></a>]<br />';
	else
	echo '[<img src="/style/icons/like.gif" alt="*" /> '.mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `like` = '1' AND `id_video` = '".$video['id']."' LIMIT 1"),0).'] [<img src="/style/icons/dlike.gif" alt="*" /> '.mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `like` = '0' AND `id_video` = '".$video['id']."' LIMIT 1"),0).']';
	

}else{

	echo '[<img src="/style/icons/like.gif" alt="*" /> '.mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `like` = '1' AND `id_video` = '".$video['id']."' LIMIT 1"),0).'] [<img src="/style/icons/dlike.gif" alt="*" /> '.mysql_result(mysql_query("SELECT COUNT(*) FROM `video_like` WHERE `like` = '0' AND `id_video` = '".$video['id']."' LIMIT 1"),0).']';

}

	echo ' <b>Просмотров</b> [' . $video['count'] . ']';
	echo '</div>';
	
// Описание

if ($video['opis'])
{

	echo '<div class="nav2">Описание:<br />';

	echo output_text($video['opis']);

	echo '</div>';

}

// Автор видео 

echo '<div class="nav2">';

	status($ank['id']);

	echo group($ank['id']) . '<a href="/info.php?id=' . $ank['id'] . '">' . $ank['nick'] . '</a> ';
	echo medal($ank['id']) . online($ank['id']) . '<br />';
	echo 'Загружено '.vremja($video['time']) . '<br />';

echo '</div>';


// Выбираем похожие видео по первому слову.

$search = explode(' ', $video['name']);

$sV = mysql_query("SELECT * FROM `video` WHERE
 `name` like '%".mysql_escape_string($search[0])."%' AND
 `name` != '$video[name]' ORDER BY rand() DESC LIMIT 4");

if (mysql_num_rows($sV) > 0)
{
	echo '<div class="nav2">';
	echo '<b>Еще видео...</b>';
	echo '</div>';

	echo '<table><tr>';

	while ($sVideo = mysql_fetch_assoc($sV))
	{
		echo '<td style="position: relative; word-wrap:break-word; float:left; display:block; vertical-align:top; width:20%;"><a href="video.php?id=' . $sVideo['id'] . '">
		<img style="max-width:100%;" src="http://i1.ytimg.com/vi/' . $sVideo['url'] . '/default.jpg" /><br /><font style="font-size:x-small;">' . htmlspecialchars($sVideo['name']) . '</font></a><br /></td>';

	}

	echo '</tr></table>';
}

// Комментарии к видео

	echo '<div class="foot">';
	echo 'Комментарии:';
	echo '</div>'; 


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `video_komm` WHERE `id_video` = '$video[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];



echo '<table class="post">';

if ($k_post==0)
{
	echo '<div class="mess">';
	echo 'Нет сообщений';
	echo '</div>';
}

$q = mysql_query("SELECT * FROM `video_komm`  WHERE `id_video` = '$video[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");



while ($post = mysql_fetch_assoc($q))
{

	$ank2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

	/*-----------зебра-----------*/
	if ($num==0)
	{echo '<div class="nav1">';
	$num=1;
	}elseif ($num==1)
	{echo '<div class="nav2">';
	$num=0;}
	/*---------------------------*/

	
	echo status($ank2['id']);
	


	echo group($ank2['id']) . '<a href="/info.php?id=' . $ank2['id'] . '">' . $ank2['nick'] . '</a>';
	if (isset($user) && $user['id'] != $ank2['id'])echo ' <a href="?id=' . $video['id'] . '&response=' . $ank2['id'] . '">[*]</a> ';
	echo medal($ank['id']) . online($ank['id']) . ' ('.vremja($post['time']) . ')<br />';

	$postBan = mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `razdel` = 'all' AND `post` = '1' AND `id_user` = '$ank2[id]' AND (`time` > '$time' OR `navsegda` = '1')"), 0);

	if ($postBan == 0) // Блок сообщения
	{	
		echo output_text($post['msg']) . '<br />';
		
	}else{
		echo output_text($banMess) . '<br />';
	}

	if (isset($user))
	{
		echo '<div style="text-align:right;">';
		if (user_access('guest_delete')) 
		echo ' <a href="?id=' . $video['id'] . '&post=' . $post['id'] . '&page=' . $page . '"><img src="/style/icons/delete.gif" alt="*"></a>';
		echo '</div>';
	}

	echo '</div>';
}
echo '</table>';

if (isset($user))
{	
	$msg2 = $respons_msg;
	
	echo '<form method="post" name="message" action="?id=' . $video['id'] . '&page=' . $pageEnd . '' . $go_otv . '">';
	if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
	include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
	else
	{
		echo $tPanel;
		echo '<textarea name="msg">' . $msg2 . '</textarea><br />';
	}
	echo '<input value="Отправить" type="submit" />';
	echo '</form>';
}


if ($k_page>1)str('index.php?',$k_page,$page); // Вывод страниц



if (isset($user) && ($user['id'] == $ank['id'] || $user['level'] > 2))
{
	echo '<div class="foot">';
	echo '<img src="/style/icons/delete.gif" alt="*"> <a href="?id=' . $ID . '&amp;delete">Удалить видео</a><br />';
	echo '</div>';
}


echo '<div class="foot">';
echo '<img src="/style/icons/str2.gif" alt="*"> <a href="category.php?id=' . $video['id_category'] . '">В категорию</a><br />';
echo '</div>';


include_once '../../sys/inc/tfoot.php';
?>
