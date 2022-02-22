<?
//Автор: DjBoBaH 
//Сайт: http://my-perm.net 
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Топ дневников'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `diary`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<td class="p_t">';
echo 'Нет дневников';
echo '</td>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `diary` ORDER BY `rating` DESC LIMIT $start, $set[p_str]");
while ($diary = mysql_fetch_assoc($q))
{
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `diary_cat` WHERE `id` = '$diary[id_cat]' LIMIT 1"));
echo '<tr>';
echo '<td class="icon14">';
echo '<img src="/diary/img/diary.png" alt=""/>';
echo '</td>';
echo '<td class="p_t">';
echo '<a href="/diary/'.$diary['name'].'/">'.$diary['name'].'</a> ('.vremja($diary['time']).')';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="p_m" colspan="2">';
$us=get_user($diary['id_user']);
echo '<span class="ank_n">Категория:</span> <a href="/index.php?r='.$razdel['id'].'">'.$razdel['name'].'</a><br/>';
echo '<span class="ank_n">Просмотров:</span> <span class="ank_d">'.$diary['viewings'].'</span> | ';
echo '<span class="ank_n">Рейтинг:</span> <span class="ank_d">'.$diary['rating'].'</span><br/>';
echo '<span class="ank_n">Автор:</span> <a href="/info.php?id='.$us['id'].'" title="Анкета '.$us['nick'].'"><span style="color:'.$us['ncolor'].'">'.$us['nick'].'</span></a>';
echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?",$k_page,$page); // Вывод страниц
echo'<img src="img/back.png" alt=""/> <a href="/diary/">Дневники</a><br/>';
include_once '../sys/inc/tfoot.php';
?>