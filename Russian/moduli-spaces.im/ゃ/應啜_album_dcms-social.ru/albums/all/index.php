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

$set['title']='Фотоальбомы'; // заголовок страницы
include_once '../../sys/inc/thead.php';
title();

err();
aut();


echo " <div class='p_m'><img src='/albums/new.png'  /> <a href='onrait.php'><b>Новые фотографии</b></a></div>";
echo " <div class='p_m'><img src='/albums/poloj.png'  /> <a href='onstar.php'><b>Популярные фотографии</b></a></div>";


echo " <div class='menu_razd'>Лучшее фото</div><div class='p_m'><table style='width:100%' cellspacing='0' cellpadding='0'>";
$q=mysql_query("SELECT * FROM `albums_foto` ORDER BY `rating` DESC LIMIT 2");
while ($bfoto = mysql_fetch_assoc($q))echo "<td style='width: 50%'><center><img class='show_foto' src='/albums/pictures/size128/$bfoto[id].jpg' width='100px' height='100px' alt='$bfoto[name]' /><br />Рейтинг: $bfoto[rating]<br /></center></td>";
echo "</table></div>";


$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `albums` where `osn`='0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table style='width:100%;'>";
if ($k_post==0)echo "<tr><td class='p_t'>Нет фотоальбомов</td></tr>";




$q=mysql_query("SELECT * FROM `albums` where `osn`='0' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank=mysql_fetch_assoc(mysql_query("select `id`,`nick`,`pol` from `user` where `id`='".$post['id_u']."' limit 1"));

echo "   <tr>  <td class='icon14'>\n";
$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `albums_foto` WHERE `id_album` = '$post[id]' ORDER BY RAND()"));
if ($foto==null)echo "<img src='/albums/all/0.png' alt='Нет фото' />";
else echo "<img src='/albums/pictures/size50/$foto[id].jpg' alt='Фото_$foto[id]' />";
echo "  </td><td class='p_t'>\n";
$arr=array('dir','fren','x');
echo "<img src='/albums/".$arr[$post['vid']].".png'> <a href='/albums/?im=album&user=$ank[id]&dir=$post[id]'>$post[name]</a> ".($post['pass']!=NULL?"<img src='/albums/key.png'/>":null)."</td> </tr><tr>";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>"; else echo "  <td class='p_m'>";
echo "Создан: ".vremja($post['time'])."<br />Автор: <a href='/info.php?id=$ank[id]'>$ank[nick]</a><br /></td></tr>";

}
echo "</table>";




if ($k_page>1)str('?',$k_page,$page); // Вывод страниц

if (isset($user))echo '<div class="foot">&raquo;<a href="/albums/?id='.$user['id'].'">Мои альбомы</a><br /></div>';
include_once '../../sys/inc/tfoot.php';
?>