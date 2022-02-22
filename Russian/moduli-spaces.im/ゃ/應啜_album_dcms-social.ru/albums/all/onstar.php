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

$set['title']='Популярные фотографии'; // заголовок страницы
include_once '../../sys/inc/thead.php';
title();

err();
aut();

switch ((isset($_GET['r'])) ? htmlspecialchars($_GET['r']) : null){
default:$rr=null;$s=0;break;
case '1':$rr='and `time` > '.($time-86400);$s=1;break;
case '2':$rr='and `time` > '.($time-604800);$s=2;break;
case '3':$rr='and `time` > '.($time-2592000);$s=3;break;
}

echo '<div class="p_m">Сортировка за: ';
if ($s==1)echo 'сутки | ';else echo '<a href="?r=1">сутки</a> | ';
if ($s==2)echo 'неделю | ';else echo '<a href="?r=2">неделю</a> | ';
if ($s==3)echo 'месяц | ';else echo '<a href="?r=3">месяц</a> | ';
if(!$s)echo 'все время';else echo '<a href="?">все время</a>';
echo '</div>';


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto` where `rating`>'0'  ".$rr." "),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table style='width:100%;'>";
if ($k_post==0)echo "<tr><td class='p_t'>Нет фотографий</td></tr>";

$q=mysql_query("SELECT * FROM `albums_foto` where `rating`>'0'  ".$rr." ORDER BY `rating` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("select `id`,`nick`,`pol` from `user` where `id`='".$post['id_u']."' limit 1"));

echo "<tr><td class='icon14'><img src='/albums/pictures/size50/".$post['id'].".jpg'/></td><td class='p_t'>";

echo "<a href='/albums/?im=picture&user=$ank[id]&dir=$post[id_album]&id=".$post['id']."'>$post[name]</a></td> </tr><tr>";
echo "<td class='p_m' colspan='2'><b>Добавлена:</b> ".vremja($post['time'])."<br />
<b>Рейтинг:</b> ".$post['rating']."<br />
<b>Автор:</b> <a href='/info.php?id=$ank[id]'>$ank[nick]</a><br /></td></tr>";

}
echo "</table>";




if ($k_page>1)str('?'.(isset($_GET['r'])?'r='.$_GET['r'].'&amp;':null).'',$k_page,$page); // Вывод страниц

if (isset($user))echo '<div class="foot">&raquo;<a href="/albums/?id='.$user['id'].'">Мои альбомы</a><br /></div>';
echo '<div class="foot">&laquo;<a href="/albums/all/">Вернуться</a><br /></div>';
include_once '../../sys/inc/tfoot.php';
?>