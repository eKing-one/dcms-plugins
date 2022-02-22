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
$set['title']='Дневники - Поиск'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
err();

$search=NULL;
if (isset($_SESSION['search']))$search=$_SESSION['search'];
if (isset($_POST['search']))$search=$_POST['search'];
$_SESSION['search']=$search;

$search=preg_replace("/( ){2,}/"," ",$search);
$search=preg_replace("/^( ){1,}|( ){1,}$/","",$search);

$where=NULL;
if (isset($_SESSION['where']))$where=$_SESSION['where'];
if (isset($_POST['where']) && ($_POST['where']==1 || $_POST['where']==2))$where=intval($_POST['where']);
$_SESSION['where']=$where;

if (isset($_GET['go']) && $search!=NULL && $where!=NULL)
{
$q_search=str_replace('%','',$search);
$q_search=str_replace(' ','%',$q_search);
if($where==1)$where1='name'; else $where1='msg';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `$where1` like '%".mysql_escape_string($q_search)."%'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo'<div class="post">Извините, по Вашему запросу ничего не найдено</div>';
$q=mysql_query("SELECT * FROM `diary` WHERE `$where1` like '%".mysql_escape_string($q_search)."%' ORDER BY `id` DESC LIMIT $start, $set[p_str]");

echo'<table class="post">';

while ($post = mysql_fetch_assoc($q))
{
$us=get_user($post['id_user']);
$tag = "";
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
if ($k_page>1)str("?go&",$k_page,$page); // Вывод страниц
}
else
echo'Введите текст для поиска<br/>';

echo'<form method="post" action="?go" class="search">';
$search=stripcslashes(htmlspecialchars($search));
echo'<input type="text" name="search" maxlength="64" value="'.$search.'" /><br/>';
echo 'Искать<br/>';
echo '<select name="where">';
echo '<option value="2"'.($where==2?' selected="selected"':null).'>В тексте</option>';
echo '<option value="1"'.($where==1?' selected="selected"':null).'>В названии</option>';
echo '</select><br/>';
echo'<input type="submit" value="Поиск" />';
echo'</form>';
echo'<div class="foot">';
echo'<a href="/diary/">Дневники</a><br />';
echo'</div>';
include_once '../sys/inc/tfoot.php';
?>