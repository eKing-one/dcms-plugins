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
if(isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==1 || isset($user) && !isset($_GET['id']))
{
if(isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"),0)==1)
{
$id=intval($_GET['id']);
$ank=get_user($id);
}
else
{
$ank=get_user($user['id']);
}
$set['title']='Дневники '.$ank['nick'].''; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id_user`='$ank[id]'"),0);
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
else
{
$q=mysql_query("SELECT * FROM `diary` WHERE `id_user`='$ank[id]' ORDER BY `time` DESC");
while ($diary = mysql_fetch_assoc($q))
{
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `diary_cat` WHERE `id` = '$diary[id_cat]' LIMIT 1"));
echo '<tr>';
echo '<td class="icon14">';

echo '</td>';
echo '<td class="p_t">';
echo '<a href="/diary/'.$diary['name'].'/">'.$diary['name'].'</a> ('.vremja($diary['time']).')';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="p_m" colspan="2">';
echo '<span class="ank_n">Категория:</span> <a href="/index.php?r='.$razdel['id'].'">'.$razdel['name'].'</a><br/>';
echo '<span class="ank_n">Просмотров:</span> <span class="ank_d">'.$diary['viewings'].'</span> | ';
echo '<span class="ank_n">Рейтинг:</span> <span class="ank_d">'.$diary['rating'].'</span><br/>';
echo '<span class="ank_n">Метки:</span> <span class="ank_d">'.$diary['tags'].'</span>';
echo '</td>';
echo '</tr>';
}
}
echo '</table>';
echo'<img src="img/back.png" alt=""/> <a href="/diary/">Дневники</a><br/>';
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>