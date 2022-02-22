<?php
// Автор: DjBoBaH
// http://my-perm.net
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title']='Дневники - Поиск по меткам'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
err();
$tag=NULL;
if (isset($_SESSION['tag']))$tag=$_SESSION['tag'];
if (isset($_GET['tag']))$tag=esc(urldecode($_GET['tag']));
$_SESSION['tag']=$tag;

$tag=preg_replace("/( ){2,}/"," ",$tag);
$tag=preg_replace("/^( ){1,}|( ){1,}$/","",$tag);



if ($tag!=NULL)
{

$q_tag=str_replace('%','',$tag);
$q_tag=str_replace(' ','%',$q_tag);
$q_tag=str_replace(',','%',$q_tag);
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `tags` like '%".mysql_real_escape_string($q_tag)."%'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo'<div class="post">Извините, по Вашему запросу ничего не найдено</div>';
$q=mysql_query("SELECT * FROM `diary` WHERE `tags` like '%".mysql_real_escape_string($q_tag)."%' ORDER BY `id` DESC LIMIT $start, $set[p_str]");

echo'<table class="post">';

while ($post = mysql_fetch_assoc($q))
{
$us=get_user($post['id_user']);
$post['tags']=str_replace("$tag", "<span style='color:red'>$tag</span>", $post['tags']);
echo'<tr>';
echo'<td class="icon14">';
echo '<img src="/diary/img/diary.png" alt=""/>';
echo'</td>';
echo'<td class="p_t">';
echo'<a href="/diary/'.$post['name'].'/">'.$post['name'].'</a> ('.vremja($post['time']).')';
echo'</td>';
echo'</tr>';
echo'<tr>';
echo'<td class="p_m" colspan="2">';
echo '<span class="ank_n">Просмотров:</span> <span class="ank_d">'.$post['viewings'].'</span> | ';
echo '<span class="ank_n">Рейтинг:</span> <span class="ank_d">'.$post['rating'].'</span><br/>';
echo '<span class="ank_n">Метки:</span> <span class="ank_d">'.$post['tags'].'</span>';
echo'</td>';
echo'</tr>';
}
echo'</table>';
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
}
else
echo'Введите метку для поиска<br/>';
echo'<form method="get" action="?" class="search">';
$tag=stripcslashes(htmlspecialchars($tag));
echo'<input type="text" name="tag" maxlength="64" value="'.$tag.'" /><br/>';
echo'<input type="submit" value="Поиск" />';
echo'</form>';
echo'<div class="foot">';
echo'<a href="/diary/">Дневники</a><br />';
echo'</div>';
include_once '../sys/inc/tfoot.php';
?>