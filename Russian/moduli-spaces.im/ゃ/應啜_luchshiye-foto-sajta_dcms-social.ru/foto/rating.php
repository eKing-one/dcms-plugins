<?php
/* 
   Мод: Лучшие фото
   Автор: Optimuses
   Цена: 50 wmr
*/

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

only_reg(); 
$set['title']='Лучшие фото';
include_once '../sys/inc/thead.php';

title();
aut(); // форма авторизации

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` "),0);
	$k_page = k_page($k_post,$set['p_str']);
	$page = page($k_page);
	$start = $set['p_str']*$page-$set['p_str'];

if ($k_post == 0)
	{
		echo '<div class="mess">';
		echo 'Рейтинг пустой';
		echo '</div>';
	}



	$q = mysql_query("SELECT * FROM `gallery_foto` ORDER BY `rating` DESC LIMIT $start, $set[p_str]");

	while ($post = mysql_fetch_assoc($q))
	{
		$ank2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[id_user]' LIMIT 1"));

		
		echo '<div class="' . ($num % 2 ? "nav1" : "nav2") . '">';
		$num++;

if($num==1)echo "<span style='float:right'><img src='/style/icons/1.png'></span>";
if($num==2)echo "<span style='float:right'><img src='/style/icons/2.png'></span>";
if($num==3)echo "<span style='float:right'><img src='/style/icons/3.png'></span>";

if ($webbrowser == 'web' && $w > 128)
		{
			echo "<a href='/foto/foto0/$post[id].$post[ras]' title='Скачать оригинал'><img style='max-width:90%' src='/foto/foto640/$post[id].$post[ras]'/></a>";
			}
		else
		{
			echo "<a href='/foto/foto0/$post[id].$post[ras]' title='Скачать оригинал'><img src='/foto/foto128/$post[id].$post[ras]'/></a>";
		}

echo "</br>Рейтинг: $post[rating]<br/>";
echo '<a href="/foto/' . $ank2['id'] . '/">Посмотреть все фотографии</a><br />';
		echo group($ank2['id']) . user::nick($ank2['id']);
	

		echo medal($ank2['id']) . online($ank2['id']) . ' (' . vremja($post['time']) . ')<br />';
		
		echo '</div>';
	}

	if ($k_page > 1)str('?',$k_page,$page); // Вывод страниц



include_once '../sys/inc/tfoot.php';

?>