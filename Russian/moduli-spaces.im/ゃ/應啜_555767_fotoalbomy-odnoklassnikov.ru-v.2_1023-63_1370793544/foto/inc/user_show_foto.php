<?
if (!isset($user) && !isset($_GET['id_user'])){header("Location: /foto/?".SID);exit;}
if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id_user']))$ank['id']=$_GET['id_user'];

$ank=get_user($ank['id']);
if (!$ank){header("Location: /foto/?".SID);exit;}


$gallery['id']=intval($_GET['id_gallery']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /foto/$ank[id]/?".SID);exit;}
$gallery=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery` WHERE `id` = '$gallery[id]' AND `id_user` = '$ank[id]' LIMIT 1"));

$foto['id']=intval($_GET['id_foto']);
if (isset($user) && isset($_GET['save']) && $user['id']==$ank['id'])
{
crop(H."sys/gallery/avatar/$foto[id].jpg", H."sys/gallery/50/$foto[id].tmp.jpg");
resize(H."sys/gallery/50/$foto[id].tmp.jpg", H."sys/gallery/50/$foto[id].jpg", 50, 50);

@chmod(H."sys/gallery/50/$foto[id].jpg",0777);
@unlink(H."sys/gallery/50/$foto[id].tmp.jpg");
$_SESSION['message'] = 'Изображение успешно сохранено';
header("Location: ?".SID);
exit;
}
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id` = '$foto[id]' LIMIT 1"),0)==0){header("Location: /foto/$ank[id]/$gallery[id]/?".SID);exit;}
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = '$foto[id]'  LIMIT 1"));

$set['title']=$ank['nick'].' - '.$gallery['name'].' - '.$foto['name']; // заголовок страницы

include_once '../sys/inc/thead.php';
title();
if (user_access('foto_foto_edit') && $ank['level']>$user['level'] || isset($user) && $ank['id']==$user['id'])
include 'inc/gallery_show_foto_act.php';



if (isset($user) && $user['id']!=$ank['id'] && $user['rating']>=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `id_user` = '$user[id]' AND `id_foto` = '$foto[id]'"), 0)==0)
{
if (isset($_GET['rating']) && $_GET['rating']=='1'){

mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '1', '$time', $foto[id_user])",$db);

$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));

}
elseif(isset($_GET['rating']) && $_GET['rating']=='2')
{

mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '2', '$time', $foto[id_user])",$db);

$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));
}
elseif(isset($_GET['rating']) && $_GET['rating']=='3')
{

mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '3', '$time', $foto[id_user])",$db);

$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));
}
elseif(isset($_GET['rating']) && $_GET['rating']=='4')
{

mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '4', '$time', $foto[id_user])",$db);

$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));
}
elseif(isset($_GET['rating']) && $_GET['rating']=='5')
{

mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '5', '$time', $foto[id_user])",$db);

$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));
}
elseif(isset($_GET['rating']) && $_GET['rating']=='5i')
{
$c=mysql_result(mysql_query("SELECT COUNT(*) FROM `ocenky` WHERE `id_user` = '$user[id]' AND `time` > '$time'"), 0);
if ($c==1){
mysql_query("INSERT INTO `gallery_rating` (`id_user`, `id_foto`, `like`, `time`, `avtor`) values('$user[id]', '$foto[id]', '6', '$time', $foto[id_user])",$db);
mysql_query("UPDATE `gallery_foto` SET `rating` = '".($foto['rating']+10)."' WHERE `id` = '$foto[id]' LIMIT 1",$db);
}
else
{
$_SESSION['url'] = "/foto/$ank[id]/$gallery[id]/$foto[id]/";
header("Location: /user/money/plus5.php");
}
}

}
/*-------------Главные переменные----------------*/
if ($set['web']){
$di='640';
$width='350';
}else{
$width='';
$di='128';
}
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = $foto[id] LIMIT 1"));
$rat=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `id_foto` = $foto[id] AND `like` = '6'"), 0);
if (isset($_SESSION['message']))
{
echo "<div class='msg'>$_SESSION[message]</div>";
$_SESSION['message'] = null;
}
/*------------------------------------------------*/
err();
aut();
/*----------------------листинг-------------------*/
$r=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$gallery[id]' AND `id` < '$foto[id]' ORDER BY `id` DESC LIMIT 1"));
$l=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id_gallery` = '$gallery[id]' AND `id` > '$foto[id]' ORDER BY `id`  ASC LIMIT 1"));
if ($r['id'] || $l['id'])
{
echo "<div class=\"foot\">\n";
if ($l['id'])echo "<a href='/foto/$ank[id]/$gallery[id]/$l[id]/'>&laquo;Пред.</a>";
if ($r['id'] && $l['id'])echo " • ";
if ($r['id'])echo "<a href='/foto/$ank[id]/$gallery[id]/$r[id]/'>След.&raquo;</a>";
echo "</div>\n";
}
/*----------------------alex-borisi---------------*/

echo "<a href='/foto/$ank[id]/$gallery[id]/komm/$foto[id]/'>Комментарии (".mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_komm` WHERE `id_foto` = '$foto[id]'"),0).")</a><br />\n";

echo "<div class='content'>";
echo "<div style='display:inline;'><a href='/foto/foto0/$foto[id].$foto[ras]' title='Скачать оригинал'><img style='max-width:240px;' src='/foto/foto$di/$foto[id].$foto[ras]'/></a></div>";
if ($rat>0)echo "<div style='display:inline;margin-left:-25px;vertical-align:top;'><img style='padding-top:10px;' src='/style/icons/6.png'/></div>";
echo "</div>";

if ($foto['opis']!=null)
echo esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($foto['opis']))))))))."<br />\n";

$k_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_frend` where `id_foto` = '$foto[id]'"),0);
if ($k_p > 0)
{
echo "<div class='mess'>";
echo "На этом фото отмечен(ы) ";
$q=mysql_query("SELECT * FROM `gallery_frend` where `id_foto` = '$foto[id]'");
$n = 0;
while ($post = mysql_fetch_assoc($q))
{
$n++;
$f=get_user($post['id_user']);
if ($n)echo " | ";
if ($f['id'] != $user['id'])
echo " <a href='/info.php?id=$f[id]'>$f[nick]</a>\n";
else
echo "<b>Вы</b>";

}
echo "</div>";
}

if (isset($user) && $user['id']!=$ank['id'] &&  mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_rating` WHERE `id_user` = '$user[id]' AND `id_foto` = '$foto[id]'"), 0)==0){
echo "<a href=\"?id=$foto[id]&amp;rating=5i\" title=\"5+\"><img src='/style/icons/6.png' alt=''/></a>";
echo "<a href=\"?id=$foto[id]&amp;rating=5\" title=\"5\"><img src='/style/icons/5.png' alt=''/></a>";
echo "<a href=\"?id=$foto[id]&amp;rating=4\" title=\"4\"><img src='/style/icons/4.png' alt=''/></a>";
echo "<a href=\"?id=$foto[id]&amp;rating=3\" title=\"3\"><img src='/style/icons/3.png' alt=''/></a>";
echo "<a href=\"?id=$foto[id]&amp;rating=2\" title=\"2\"><img src='/style/icons/2.png' alt=''/></a>";
echo "<a href=\"?id=$foto[id]&amp;rating=1\" title=\"1\"><img src='/style/icons/1.png' alt=''/></a>";
}else{
$rate=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_rating` WHERE `id_foto` = $foto[id] AND `id_user` = '$user[id]' LIMIT 1"));
if (isset($user) && $user['id']!=$ank['id'])
echo "Ваша оценка <img style='vertical-align:middle;' src='/style/icons/$rate[like].png' alt=''/></a>";
}
echo "</div>\n";


if (user_access('foto_foto_edit') && $ank['level']>$user['level'] || isset($user) && $ank['id']==$user['id'])
include 'inc/gallery_show_foto_form.php';



echo "<div class=\"foot\">\n";
echo "<a href='/foto/$ank[id]/$gallery[id]/'>К фотографиям</a><br />\n";
echo "<a href='/foto/$ank[id]/'>К фотоальбомам</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit;
?>