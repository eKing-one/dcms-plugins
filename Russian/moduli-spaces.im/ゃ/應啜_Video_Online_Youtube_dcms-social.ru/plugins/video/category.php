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

$ID = intval($_GET['id']); 

$category = mysql_fetch_assoc(mysql_query("SELECT * FROM `video_category` WHERE `id` = '$ID'"));

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `video_category` WHERE `id` = '$ID'"),0) == 0)
{
	header('Location: index.php');
	exit;
}


	echo '<div class="foot"><img src="/style/icons/str2.gif" alt="*" /> <a href="index.php">Лучшее видео Youtube</a> | <b>' . htmlspecialchars($category['name']) . '</b></div>';


$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `video` WHERE `id_category` = '$ID' "),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];


$q = mysql_query("SELECT * FROM `video` WHERE `id_category` = '$ID' ORDER BY `id` DESC LIMIT $start, $set[p_str]");

echo '<table class="post">';

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo 'В категории нет видео';
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

	echo '<a href="video.php?id=' . $post['id'] . '"><img src="http://i1.ytimg.com/vi/' . $post['url'] . '/default.jpg" /><br /><img src="img/video.png" /> ' . htmlspecialchars($post['name']) . '</a><br />';

	echo 'Просмотров [' . $post['count'] . '] ';


	
	echo '</div>';
}
echo '</table>';

if ($k_page>1)str('?id=' . $ID . '&',$k_page,$page); // Вывод страниц

if (isset($user)){
	
	echo '<div class="foot"><img src="/style/icons/str.gif" alt="*" /> <a href="create.php?category=' . $ID . '&amp;new=video">Добавить видео</a></div>';

}
	
if (isset($user) && $user['level'] > 2){
	
	echo '<div class="foot"><img src="/style/icons/str.gif" alt="*" /> <a href="create.php?id=' . $ID . '&amp;edit=category">Параметры категории</a></div>';
	echo '<div class="foot"><img src="/style/icons/str.gif" alt="*" /> <a href="create.php?id=' . $ID . '&amp;cat=delete">Удалить категорию</a></div>';

}
	echo '<div class="foot"><img src="/style/icons/str2.gif" alt="*" /> <a href="index.php">Лучшее видео Youtube</a> | <b>' . htmlspecialchars($category['name']) . '</b></div>';

include_once '../../sys/inc/tfoot.php';
?>
