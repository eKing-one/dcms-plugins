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

$set['title']='Лучшее видео Youtube';

include_once '../../sys/inc/thead.php';
title();
aut(); // форма авторизации


	echo '<div class="foot"><img src="/style/icons/str2.gif" alt="*" /> <b>Лучшее видео Youtube</b></div>';


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `video_category`"),0);

$q = mysql_query("SELECT * FROM `video_category` ORDER BY `id` DESC");

echo '<table class="post">';

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo 'Категорий нет';
	echo '</div>';
}

while ($post = mysql_fetch_assoc($q))
{
	
	/*-----------зебра-----------*/
	if ($num==0)
	{echo '<div class="nav1">';
	$num=1;
	}elseif ($num==1)
	{echo '<div class="nav2">';
	$num=0;}
	/*---------------------------*/
	
	// Cчетчик видео
	$count['video'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `video` WHERE `id_category` = '$post[id]'"), 0);
	
	// Cчетчик новых видео
	$count['new_video'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `video` WHERE `id_category` = '$post[id]' AND `time` > '$ftime'"), 0);

	echo '<img src="img/dir.gif" /> <a href="category.php?id=' . $post['id'] . '">' . htmlspecialchars($post['name']) . '</a> (' . $count['video'] . ')<br />';

	echo '</div>';
}
echo '</table>';

if (isset($user)){
	
	echo '<div class="foot"><img src="/style/icons/str.gif" alt="*" /> <a href="create.php?new=category">Создать категорию</a></div>';

	
}
	echo '<div class="foot"><img src="/style/icons/str2.gif" alt="*" /> <b>Лучшее видео Youtube</b></div>';

include_once '../../sys/inc/tfoot.php';
?>
