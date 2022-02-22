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
if(isset($_GET['r']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_cat` WHERE `id` = '".intval($_GET['r'])."' LIMIT 1"),0)==1)
{
$r=intval($_GET['r']);
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `diary_cat` WHERE `id` = '$r' LIMIT 1"));
if(isset($user) && isset($_GET['new']))
{
$set['title']='Дневники - '.$razdel['name'].' - Новый'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
include_once 'inc/new_act.php';
err();
include_once 'inc/new_form.php';
}
else
{
$set['title']='Дневники - '.$razdel['name']; // заголовок страницы
if($razdel['desc']!=NULL)$set['meta_description']=''.$razdel['desc'].'';
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($user) && $user['level']>2 && isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id` = '".intval($_GET['del'])."' LIMIT 1"),0)!=0)
{
$del=mysql_fetch_assoc(mysql_query("SELECT * FROM `diary` WHERE `id`='".intval($_GET['del'])."' LIMIT 1"));
$avtor=get_user($del['id_user']);
if($user['id']==$avtor['id'] || $user['level']>$avtor['level'])
{
$images=mysql_query("SELECT * FROM `diary_images` WHERE `id_diary`='$del[id]'");
while ($delete = mysql_fetch_assoc($images))
{
unlink(H.'diary/images/48/'.$delete['id'].'.'.$delete['ras'].'');
unlink(H.'diary/images/128/'.$delete['id'].'.'.$delete['ras'].'');
unlink(H.'diary/images/640/'.$delete['id'].'.'.$delete['ras'].'');
unlink(H.'diary/images/'.$delete['id'].'.'.$delete['ras'].'');
}
mysql_query("DELETE FROM `diary_rating` WHERE `id_diary`='$del[id]'");
mysql_query("DELETE FROM `diary_images` WHERE `id_diary`='$del[id]'");
mysql_query("DELETE FROM `diary_komm` WHERE `id_diary`='$del[id]'");
mysql_query("DELETE FROM `diary` WHERE `id`='$del[id]'");
msg('Дневник успешно удален');
}
else $err[]='Не хватает прав для удаления дневника';
}
if(isset($_GET['sort']))
{
if($_GET['sort']=='viewings'){$sort='viewings';}
elseif($_GET['sort']=='rating'){$sort='rating';}
else{$sort='time';}
}
else
{
$sort='time';
}

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id_cat`='$r'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

echo'<div class="foot">';
echo'Сортировать:<br/> <a href="?r='.$r.'&sort=rating&page='.$page.'">Рейтинг</a> <a href="?r='.$r.'&sort=time&page='.$page.'">Новые</a> <a href="?r='.$r.'&sort=viewings&page='.$page.'">Просмотры</a></div>';

echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<td class="p_t">';
echo 'Нет дневников в данной категории';
echo '</td>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `diary` WHERE `id_cat`='$r' ORDER BY $sort DESC LIMIT $start, $set[p_str]");
while ($diary = mysql_fetch_assoc($q))
{
$us=get_user($diary['id_user']);
echo '<tr>';
echo '<td class="icon14">';
echo '<img src="img/diary.png" alt=""/>';
echo '</td>';
echo '<td class="p_t">';
echo '<a href="/diary/'.$diary['name'].'/">'.$diary['name'].'</a>';
echo' ('.vremja($diary['time']).')';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="p_m" colspan="2">';
echo '<span class="ank_n">Просмотров:</span> <span class="ank_d">'.$diary['viewings'].'</span> | ';
echo '<span class="ank_n">Рейтинг:</span> <span class="ank_d">'.$diary['rating'].'</span><br/>';
echo '<span class="ank_n">Автор:</span> <a href="/info.php?id='.$us['id'].'" title="Анкета '.$us['nick'].'"><span style="color:'.$us['ncolor'].'">'.$us['nick'].'</span></a>';
if(isset($user) && $user['level']>2 && ($user['id']==$us['id'] || $user['level']>$us['level']))
{
echo'<br/><a href="?r='.$r.'&sort='.$sort.'&page='.$page.'&del='.$diary['id'].'" title="Удалить дневник"><span style="color:red">Удалить дневник</span></a><br/>';
}
echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?r=$r&sort=$sort&",$k_page,$page); // Вывод страниц
if(isset($user))echo'<img src="img/add.png" alt=""/> <a href="?r='.$r.'&new">Создать дневник</a><br/>';
}
echo'<img src="img/back.png" alt=""/> <a href="/diary/">Дневники</a><br/>';
}
else
{
$set['title']='Дневники - Категории'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
///////////////////////// вставка
$new =mysql_result(mysql_query("SELECT count(*) FROM `diary` WHERE `time`>'".$time."'-86400"),0);
$new = ($new>0) ? '<span style="color:#ff0000">+'.$new.'</span>' : '';
echo'<img src = "icon/new.png" alt="Здесь ваша иконка!"> <a href="new.php"> Новинки</a> '.$new.'<br/>';
/////////////////////////конец вставки
if(isset($user) && $user['level']>2)
{
include_once 'inc/admin_act.php';
}
echo '<div class="p_m">';
echo '<img src="img/top.png" alt=""/> <a href="top.php" title="Популярные дневники">Топ дневников</a><br/>';
echo '<img src="img/search.png" alt=""/> <a href="search.php" title="Искать дневники">Поиск дневников</a>/<a href="tags.php" title="Поиск дневников по меткам">Метки</a>';
echo '</div>';
echo '<table class="post">';
$q2=mysql_query("SELECT * FROM `diary_cat` ORDER BY `name` ASC");
if (mysql_num_rows($q2)==0) {
echo '<tr>';
echo '<td class="p_t">';
echo 'Нет категорий';
echo '</td>';
echo '</tr>';
}
while ($cat = mysql_fetch_assoc($q2))
{
$count = mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id_cat`='$cat[id]'"),0);
echo '<tr>';
echo '<td class="icon14">';
echo '<img src="img/cat.png" alt=""/>';
echo '</td>';
echo '<td class="p_t">';
echo'<a href="?r='.$cat['id'].'">'.$cat['name'].'</a> ('.$count.')';
if(isset($user) && $user['level']>2)
{
echo' [<a href="?edit='.$cat['id'].'">*</a>][<a href="?del='.$cat['id'].'">x</a>]';
}
echo '</td>';
echo '</tr>';
if ($cat['desc']!=NULL)
{
echo '<tr>';
echo '<td class="p_m" colspan="2">';
echo ''.output_text($cat['desc']).''; 
echo '</td>';
echo '</tr>';
}
}
echo '</table>';
if(isset($user) && $user['level']>2)
{
include_once 'inc/admin_form.php';
}
}
include_once '../sys/inc/tfoot.php';
?>