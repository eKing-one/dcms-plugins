<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Фотоальбомы';
include_once '../sys/inc/thead.php';
title();
err();
aut();
?>
		<style>h2{font-size:13px; margin:15px 0 0 0;}</style>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});
				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>
<?

function rotate_img($src, $dest, $degrees){
if (!file_exists($src)) return false;
$size_img = getimagesize($src);
$format = strtolower(substr($size_img['mime'], strpos($size_img['mime'], '/')+1));
$icfunc = "imagecreatefrom" . $format;
if (!function_exists($icfunc)) return false;
$image = $icfunc($src);
$rotate = imagerotate($image, $degrees, 0);
imagejpeg($rotate, $dest, 90);
}	

$p = (isset($_GET['im'])) ? htmlspecialchars($_GET['im']) : null;
switch ($p){
default:
$id=intval($_GET['id']);
$ank=mysql_fetch_assoc(mysql_query("select `id` from `user` where `id`='".$id."' limit 1"));
If ($id==NULL OR mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]'"), 0)==0) {header('Location: /?');exit;}
$album=mysql_result(mysql_query("SELECT count(*) FROM `albums` WHERE `id_u`='$ank[id]' and `osn` = '0' "),0);
$picture=mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_u`='$ank[id]' AND `id_album`='0' "),0);
$k_page=k_page($picture,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($picture==0 && $album==0) echo '<div class = "err">Нет фотоальбомов</div>';
///////// Список папок фотоальбома
$albums=mysql_query("SELECT * FROM `albums` WHERE `id_u`='$ank[id]' and `osn` = '0' ORDER BY id ASC LIMIT ".(isset($_GET['page']) && $_GET['page']!=NULL && $_GET['page']!='1' ?$album:'0').", 100");
while($x=mysql_fetch_assoc($albums)){
$pics=mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_album`='$x[id]'"),0);
$ale=mysql_result(mysql_query("SELECT count(*) FROM `albums` WHERE `album`='$x[id]'"),0);
$arr=array('dir','fren','x');
echo '<div class = "p_m"> <img src="'.$arr[$x['vid']].'.png"> <a href="?im=album&user='.$ank['id'].'&dir='.$x['id'].'">'.$x['name'].'</a> ('.$pics.'/'.$ale.') '.($x['pass']!=NULL?'<img src="key.png">':null).' '.($ank['id']==$user['id']?'[<a href="?im=aledit&dir='.$x['id'].'">ред.</a>] [<a href="?im=delete&dir='.$x['id'].'">удал.</a>]':null).'</div>';
}
///////// Список фотографий в альбоме
$pictures=mysql_query("SELECT * FROM `albums_foto` WHERE `id_u`='$ank[id]' AND `id_album`='0' ORDER BY id ASC LIMIT $start, $set[p_str]");
echo '<table style="width:100%;">';
while($x=mysql_fetch_assoc($pictures))echo '<td class="p_m"><table cellpadding="0" cellspacing="0"> <tr> <td><img src="/albums/pictures/size50/'.$x['id'].'.jpg">&nbsp;</td><td><a href="?im=picture&user='.$ank['id'].'&dir=0&id='.$x['id'].'">'.$x['name'].'.jpg</a> ('.size_file(filesize(H.'albums/pictures/original/'.$x['id'].'.jpg')).')<br><img src="/albums/poloj.png"> '.$x['rating'].' | <img src="/albums/komm.png"> '.mysql_result(mysql_query("SELECT count(*) FROM `albums_foto_komm` WHERE `id_picture`='$x[id]'"),0).' | <img src="/albums/eye.png"> '.$x['eye'].'</tr></td></table></td></tr>';
echo '</table>';

if ($k_page>1)str("?id=".$ank['id']."&amp;",$k_page,$page);

if ($ank['id']==$user['id'])echo '  <div class="foot">&raquo; <a href="?im=alb_create">Создать</a></div>
<div class="foot">&raquo; <a href="?im=add_pic&dir=0">Выгрузить фото</a></div>';
echo '  <div class="foot">&laquo; <a href="/albums/all/">В фотоальбомы</a></div>';
echo'<div class="foot">&laquo; <a href="/info.php?id='.$ank['id'].'">В анкету</a></div>';
break;

case 'delete';
if (isset($_GET['dir']))$dir=intval($_GET['dir']); 
else $dir=0;
$ank=mysql_fetch_assoc(mysql_query("select `id` from `user` where `id`='".intval($_GET['user'])."' limit 1"));
$album=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '".intval($_GET['dir'])."' AND `id_u` = '$user[id]' LIMIT 1"));
if ($user['id']!=$album['id_u'] OR $album==NULL){header('Location: /?');exit;}
if (isset($_GET['ok'])){
if (isset($_GET['dir']) && esc($_GET['dir'])!=NULL)
{$l=ereg_replace("\.{2,}",NULL,esc(urldecode($album['put'])));
$l=ereg_replace("\./|/\.",NULL,$l);
$l=ereg_replace("(/){1,}","/",$l);
$l='/'.ereg_replace("(^(/){1,})|((/){1,}$)","",$l);}else{$l='/';}
$q=mysql_query("SELECT * FROM `albums` WHERE `put` like '$l/%'");
while ($post = mysql_fetch_assoc($q))
{
$q2=mysql_query("SELECT * FROM `albums_foto` WHERE `id_album` = '$post[id]'");
while ($post2 = mysql_fetch_assoc($q2))
{
unlink(H.'albums/pictures/size50/'.$post2['id'].'.jpg');
unlink(H.'albums/pictures/size128/'.$post2['id'].'.jpg');
unlink(H.'albums/pictures/original/'.$post2['id'].'.jpg');
mysql_query("DELETE FROM `albums_foto_komm` WHERE `id_picture` = '$post2[id]'");
mysql_query("DELETE FROM `albums_foto_rating` WHERE `id_picture` = '$post2[id]'");
}
mysql_query("DELETE FROM `albums_foto` WHERE `id_album` = '$post[id]'");
mysql_query("DELETE FROM `albums` WHERE `id` = '$post[id]' LIMIT 1");
}
msg('Папка успешно удалена');
echo '<div class="foot">&laquo; <a href="?id='.$user['id'].'">Вернуться</a></div>';
}
if (!Isset($_GET['ok']))echo '<div class="p_m">Вы уверены что хотите удалить фотоальбом ?<br>[<a href="?im=delete&'.($dir?'dir='.$dir.'&':"").'ok">Удалить</a>] [<a href="?im=picture&dir='.$dir.'&id='.$picture['id'].'">Отмена</a>]</div>';
break;


case 'aledit';
only_reg();
$album=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '".intval($_GET['dir'])."' AND `id_u` = '$user[id]' LIMIT 1"));
if ($album==NULL){header('Location: /?');exit;}
if (isset($_GET['edit']) && $_POST['name']!=NULL){
$name=my_esc($_POST['name']);
$pass=my_esc($_POST['pass']);
$vid=intval($_POST['vid']);
if (strlen2($name)>32)$err='Название не должно быть превышать 32 символа';
if (strlen2($pass)>20)$err='Пароль не может содержать более 20 символов';
if (!isset($err)){
mysql_query("UPDATE `albums` SET `name` = '$name', `pass` = '$pass', `vid` = '$vid' WHERE `id` = '$album[id]' LIMIT 1",$db);
$album=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '".intval($_GET['dir'])."'  LIMIT 1"));
msg('Сохранено');
}}
err();
echo '<form class="p_m" action="?im=aledit&dir='.intval($_GET['dir']).'&edit" method="post">
Название: 
<br><input type="text" name="name" value = "'.$album['name'].'"><br />Пароль (если хотите): 
<br><input type="text" name="pass" value = "'.$album['pass'].'"><br />
Приватность:<br /><select name="vid">
<option value="0" '.($album['vid']==0?" selected='selected'":null).'>Все</option>
<option value="1" '.($album['vid']==1?" selected='selected'":null).'>Друзья</option>
<option value="2" '.($album['vid']==2?" selected='selected'":null).'>Только мне</option></select><br>
<input class="submit" type="submit" value="Изменить"><br /></form>
<div class="foot">&laquo; <a href="?id='.$user['id'].'">Вернуться</a></div>';
break;


case 'alb_create';
only_reg();
if (isset($_GET['dir']))$dir=intval($_GET['dir']); 
else $dir=0;
$test=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '$dir' LIMIT 1"));
if (isset($_GET['ok']) && $_POST['name']!=NULL){
$name=stripcslashes(htmlspecialchars($_POST['name']));
$pass=stripcslashes(htmlspecialchars($_POST['pass']));
$vid=intval($_POST['vid']);
if (strlen2($name)>32)$err='Название не должно быть превышать 32 символа';
if (strlen2($pass)>20)$err='Пароль не может содержать более 20 символов';
$name=my_esc($name);
$pass=my_esc($pass);
err();
if (!isset($err)){
if(isset($_GET['dir']) && $_GET['dir']!=0){
mysql_query("INSERT INTO `albums` (`id_u`, `name`, `time`, `pass`, `vid`, `osn`, `album`, `put`, `put_os`) VALUES ('$user[id]', '$name','$time','$pass', '$vid', '1', '$dir', '$test[put]', '$test[put]');");
$id_al=mysql_insert_id();
mysql_query("UPDATE `albums` SET `put` = '$test[put]$id_al/' WHERE `id` = '$id_al' LIMIT 1",$db);
}
else if(isset($_GET['dir']) && $_GET['dir']==0){
mysql_query("INSERT INTO `albums` (`id_u`, `name`, `time`, `pass`, `vid`, `osn`, `put`, `put_os`) VALUES ('$user[id]', '$name','$time','$pass', '$vid', '0', '/', '/');");
$id_al=mysql_insert_id();
mysql_query("UPDATE `albums` SET `put` = '/$id_al/' WHERE `id` = '$id_al' LIMIT 1",$db);
}

msg('Альбом успешно создан');
}
}
echo '<form class="p_m" action="?im=alb_create&dir='.$dir.'&ok" method="post">
Название:<br /><input type="text" name="name"><br />
Доступ:<br /><select name="vid">
<option value="0">Всем</option>
<option value="1">Друзьям</option>
<option value="2">Только мне</option></select><br />
Пароль (если хотите):<br /><input type="text" name="pass"><br />
<input class="submit" type="submit" value="Создать"></form>';

echo '  <div class="foot">● <a href="?id='.$user['id'].'">В начало</a>';
$as=explode('/', $test['put']);
$ar=count($as);
for ($p = 0; $p < ($ar-1); $p++){
$test = mysql_fetch_array(mysql_query("SELECT * FROM `albums` WHERE `id` = '$as[$p]' LIMIT 1"));
echo' <a href="?im=album&user='.$user['id'].'&dir='.$test['id'].'">'.$test['name'].'</a> » ';}
echo '</div>';
break;

case 'add_pic';
only_reg();
if (isset($_GET['dir']))$dir=intval($_GET['dir']); 
else $dir=0;
$test=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '$dir' LIMIT 1"));
if (isset($_GET['ok']) && isset($_FILES['file']))
{
if ($imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'])))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])),1);
if ($name==null)$name=esc(stripcslashes(htmlspecialchars(preg_replace('#\.[^\.]*$#i', NULL, $_FILES['file']['name']))));
if (!preg_match("#^([A-zА-я0-9\-\_\ ])+$#ui",$name))$err='В названии фото присутствуют запрещенные символы';
if (strlen2($name)>32)$err='Название не должно превышать 32 символа';
$name=my_esc($name);
$msg=$_POST['opis'];
if (strlen2($msg)>1000)$err='Длина описания не должна превышать 1000 символов';
$msg=my_esc($msg);
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x>$set['max_upload_foto_x'] || $img_y>$set['max_upload_foto_y'])$err='Размер изображения превышает ограничения в '.$set['max_upload_foto_x'].'*'.$set['max_upload_foto_y'];
if (!isset($err)){
mysql_query("INSERT INTO `albums_foto` (`id_album`, `name`,`opis`, `id_u`, `time`) values ('".intval($_GET['dir'])."', '$name', '$msg', '$user[id]', '$time')");
$id_foto=mysql_insert_id();

$q = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `lenta_foto` = '1' AND `i` = '1'");
while ($f = mysql_fetch_array($q))
{
$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));
$ccc=mysql_result(mysql_query("SELECT count(*) FROM `lenta` WHERE `id_kont`='$a[id]' AND `id_user`='$user[id]'  AND `type`='foto' AND `read`='0' LIMIT 1"),0);
if ($ccc==0){
$msg_lenta = "Загрузил фото: [url=/albums/?im=picture&user=$user[id]&dir=".$dir."&id=$id_foto] $name [/url]";
mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`, `type`) values('$user[id]', '$a[id]', '$msg_lenta', '$time', 'foto')");}
else {
$g = mysql_fetch_array(mysql_query("SELECT * FROM `lenta` WHERE `id_kont` = '$a[id]' AND `type`='foto' AND `read`='0' AND `id_user`='$user[id]'  ORDER BY id DESC LIMIT 1"));
mysql_query("UPDATE `lenta` SET `time`='$time', `msg` = '$g[msg], [url=/albums/?im=picture&user=$user[id]&dir=".$dir."&id=$id_foto] $name [/url]' WHERE `id` = '$g[id]' AND `type`='foto' AND `read`='0' ORDER BY id DESC LIMIT 1");
}
}



if ($img_x==$img_y){$dstW=50; $dstH=50; }
elseif ($img_x>$img_y){$prop=$img_x/$img_y;$dstW=50;$dstH=ceil($dstW/$prop);}
else{$prop=$img_y/$img_x;$dstH=50;$dstW=ceil($dstH/$prop);}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagejpeg($screen,H."albums/pictures/size50/$id_foto.jpg",90);
@chmod(H."albums/pictures/size50/$id_foto.jpg",0777);
imagedestroy($screen);
if ($img_x==$img_y){$dstW=128; $dstH=128;}
elseif ($img_x>$img_y){$prop=$img_x/$img_y;$dstW=128;$dstH=ceil($dstW/$prop);}
else{$prop=$img_y/$img_x;$dstH=128;$dstW=ceil($dstH/$prop);}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
$screen=img_copyright($screen);
imagejpeg($screen,H."albums/pictures/size128/$id_foto.jpg",90);
@chmod(H."albums/pictures/size128/$id_foto.jpg",0777);
imagedestroy($screen);
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
$screen=img_copyright($screen);
imagedestroy($screen);
$imgc=img_copyright($imgc); 
imagejpeg($imgc,H."albums/pictures/original/$id_foto.jpg",90);
@chmod(H."albums/pictures/original/$id_foto.jpg",0777);
msg("Фотография успешно добавлена");

}
else{$imgc=img_copyright($imgc);imagejpeg($imgc,H."albums/pictures/original/$id_foto.jpg",90);@chmod(H."albums/pictures/original/$id_foto.jpg",0777);}
imagedestroy($imgc);
}else $err='Выбранный Вами формат изображения не поддерживается';

}
err();

echo '<form class="p_m" enctype="multipart/form-data" action="?im=add_pic&dir='.intval($_GET['dir']).'&ok" method="post">
Название:<br /><input name="name" type="text"><br />
Файл:<br /><input name="file" type="file" accept="image/*,image/jpeg" /><br />
Описание:<br /><textarea name="opis"></textarea><br />
<input class="submit" type="submit" value="Выгрузить" /></form>';

echo '  <div class="foot">● <a href="?id='.$user['id'].'">В начало</a>';
$as=explode('/', $test['put']);
$ar=count($as);
for ($p = 0; $p < ($ar-1); $p++){
$test = mysql_fetch_array(mysql_query("SELECT * FROM `albums` WHERE `id` = '$as[$p]' LIMIT 1"));
echo' <a href="?im=album&user='.$user['id'].'&dir='.$test['id'].'">'.$test['name'].'</a> » ';}
echo '</div>';

break;


case 'album';
$dir=intval($_GET['dir']);
$us=intval($_GET['user']);
$ank=mysql_fetch_assoc(mysql_query("select `id` from `user` where `id`='".$us."' limit 1"));
$albume=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '$dir' AND `id_u` = '$ank[id]' LIMIT 1"));
if ($dir==NULL || $albume==NULL){header('Location: /?');exit;}
$album=mysql_result(mysql_query("SELECT count(*) FROM `albums` WHERE `id_u`='$ank[id]' and `osn` = '1' and `album` = '$dir' "),0);
$picture=mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_u`='$ank[id]' AND `id_album`='$dir'"),0);

if (isset($_POST['passw'])){if ($_POST['passw']==$albume['pass'])$_SESSION['pass']=$albume['id'];else $err='Неверный пароль';}
$d2sql = mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$ank[id]' AND `frend` = '$user[id]') OR (`user` = '$user[id]' AND `frend` = '$ank[id]') LIMIT 1");
if($albume['vid']==1 && mysql_result($d2sql, 0)==0 && $user['level']!=10 && $user['id']!=$ank['id'])$err='Фотоальбом только для друзей';
if($albume['vid']==2 && $user['level']!=10 && $user['id']!=$ank['id'])$err='Фотоальбом был закрыт пользователем';
err();
if (!isset($err)){
if ($albume['pass']!=NULL && $user['id']!=$ank['id'] && $user['level']!=10 && $_SESSION['pass']!=$albume['id'] && !isset($_SESSION['pass'])){
err();
echo '<form method="post" action="?im=album&user='.$ank['id'].'&dir='.$dir.'">';
echo 'Альбом доступен только по паролю.<br> Введите пароль:<br /><input name="passw" maxlength="20"><br /><input value="Вход" type="submit"><br /></form>';
echo '<div class="foot">&laquo; <a href="?id='.$ank['id'].'">В начало</a></div>';
echo'<div class="foot">&laquo; <a href="/info.php?id='.$ank['id'].'">В анкету</a></div>';
include_once '../sys/inc/tfoot.php';
exit;}

$k_page=k_page($picture,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($picture==0 && $album==0) echo '<div class = "err">Нет фотоальбомов</div>';
$albums=mysql_query("SELECT * FROM `albums` WHERE `id_u`='$ank[id]' and `osn` = '1' and `album` = '$dir'  ORDER BY id ASC LIMIT ".(isset($_GET['page']) && $_GET['page']!=NULL && $_GET['page']!='1' ?$album:'0').", 100");
while($x=mysql_fetch_assoc($albums)){
$pics=mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_album`='$x[id]'"),0);
$ale=mysql_result(mysql_query("SELECT count(*) FROM `albums` WHERE `album`='$x[id]'"),0);
$arr=array('dir','fren','x');
echo '<div class = "p_m"> <img src="'.$arr[$x['vid']].'.png"> <a href="?im=album&user='.$ank['id'].'&dir='.$x['id'].'">'.$x['name'].'</a> ('.$pics.'/'.$ale.') '.($x['pass']!=NULL?'<img src="key.png">':null).' '.($ank['id']==$user['id']?'[<a href="?im=aledit&dir='.$x['id'].'">ред.</a>] [<a href="?im=delete&dir='.$x['id'].'">удал.</a>]':null).'</div>';}
$pictures=mysql_query("SELECT * FROM `albums_foto` WHERE `id_u`='$ank[id]' AND `id_album`='$dir' ORDER BY id ASC LIMIT $start, $set[p_str]");
echo '<table style="width:100%;">';
while($x=mysql_fetch_assoc($pictures))echo '<td class="p_m"><table cellpadding="0" cellspacing="0"> <tr> <td><img src="/albums/pictures/size50/'.$x['id'].'.jpg">&nbsp;</td><td><a href="?im=picture&user='.$ank['id'].'&dir='.$x['id_album'].'&id='.$x['id'].'">'.$x['name'].'.jpg</a> ('.size_file(filesize(H.'albums/pictures/original/'.$x['id'].'.jpg')).')<br><img src="/albums/poloj.png"> '.$x['rating'].' | <img src="/albums/komm.png"> '.mysql_result(mysql_query("SELECT count(*) FROM `albums_foto_komm` WHERE `id_picture`='$x[id]'"),0).' | <img src="/albums/eye.png"> '.$x['eye'].'</tr></td></table></td></tr>';
echo '</table>';

if ($k_page>1)str("?im=album&user=$ank[id]&dir=$dir&",$k_page,$page);
}
if ($ank['id']==$user['id'])echo '  <div class="foot">&raquo; <a href="?im=alb_create&dir='.$dir.'">Создать</a></div>
<div class="foot">&raquo; <a href="?im=add_pic&dir='.$dir.'">Выгрузить фото</a></div>';
echo '  <div class="foot">● <a href="?id='.$ank['id'].'">В начало</a>';

if ($albume['put_os']!=NULL){
$as=explode('/', $albume['put_os']);
$ar=count($as);
for ($p = 0; $p < ($ar-1); $p++){
$test = mysql_fetch_array(mysql_query("SELECT * FROM `albums` WHERE `id` = '$as[$p]' LIMIT 1"));
echo' <a href="?im=album&user='.$ank['id'].'&dir='.$test['id'].'">'.$test['name'].'</a> » ';}}

echo '</div><div class="foot">&laquo; <a href="/info.php?id='.$ank['id'].'">В анкету</a></div>';
break;


case 'picture';
if (isset($_GET['dir']))$dir=intval($_GET['dir']); 
else $dir=0;
$id=intval($_GET['id']);
$us=intval($_GET['user']);
$ank=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$us."' limit 1"));
$picture=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums_foto` WHERE `id` = '$id' AND `id_u` = '$ank[id]' LIMIT 1"));
if ($picture==NULL OR $picture['id_album']!=$dir){header('Location: /?');exit;}
$alb=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums` WHERE `id` = '$dir' AND `id_u` = '$ank[id]' LIMIT 1"));


if (isset($_POST['passw'])){if ($_POST['passw']==$alb['pass'])$_SESSION['pass']=$alb['id'];else $err[]='Неверный пароль';}
$d2sql = mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$ank[id]' AND `frend` = '$user[id]') OR (`user` = '$user[id]' AND `frend` = '$ank[id]') LIMIT 1");
if($alb['vid']==1 && mysql_result($d2sql, 0)==0 && $user['level']!=10 && $user['id']!=$ank['id'])$err='Фотоальбом только для друзей';
if($alb['vid']==2 && $user['level']!=10 && $user['id']!=$ank['id'])$err='Фотоальбом был закрыт пользователем';
err();
if (!isset($err)){

if ($alb['pass']!=NULL && $user['id']!=$ank['id'] && $user['level']!=10 && $_SESSION['pass']!=$alb['id'] && !isset($_SESSION['pass'])){
err();
echo '<form method="post" action="?im=album&user='.$ank['id'].'&dir='.$dir.'">
Альбом доступен только по паролю.<br> Введите пароль:<br /><input name="passw" maxlength="20"><br /><input value="Вход" type="submit"><br /></form>
<div class="foot">&laquo;<a href="?id='.$ank['id'].'">В начало</a></div>
<div class="foot">&laquo; <a href="/info.php?id='.$ank['id'].'">В анкету</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($_GET['whoR'])){
echo '<div class="menu_razd">Проголосовали за фотографию '.$picture['name'].'</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_rating` WHERE `id_picture` = '".$picture['id']."' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
if($k_post==0)echo '<div class="p_m">Никто не голосовал</div>';
$q=mysql_query("SELECT * FROM `albums_foto_rating` WHERE `id_picture` = '".$picture['id']."'  LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$tt=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$post['id_u']."' limit 1"));
echo '<div class="p_m"><a href="/info.php?id='.$tt['id'].'"><b>'.$tt['nick'].'</a>:</b> '.$post['cn'].'</a></div>';}
echo'</div><div class="foot">&laquo; <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'">К фотографии</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($_GET['frd'])){

if(isset($_GET['ok']) && isset($_POST['sel']))
{
$sel=intval($_POST['sel']);
if (mysql_result(mysql_query("SELECT count(*) FROM `albums_friend` WHERE `id_fr` = '".$sel."' AND `id_foto` = '".$picture['id']."' "),0)!=0)$err='Этот друг уже отмечался на данной фотографии';
if (mysql_result(mysql_query("SELECT count(*) FROM `frends` WHERE `frend` = '".$user['id']."' AND `user` = '".$sel."' "),0)==0)$err='Ошибка';
err();
if (!isset($err)){
$msg="$user[nick] отметил вас на фотографии [url=/albums/?im=picture&user=".$user['id']."&dir=".$dir."&id=".$picture['id']."]$picture[name][/url]";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$sel', '$msg', '$time')");
mysql_query("INSERT INTO `albums_friend` (`id_foto`, `id_fr`) values ('$picture[id]', '".$sel."' )");
msg('Сохранено');
}
}

if(isset($_GET['no']))
{
$no=intval($_GET['no']);
if (mysql_result(mysql_query("SELECT * FROM `albums_friend` WHERE `id_fr` = '".$no."' AND `id_foto`='".$picture['id']."' "),0)==0)$err='Ошибка';
err();
if (!isset($err)){
mysql_query("DELETE FROM `albums_friend` WHERE `id_fr` = '$no' AND `id_foto`='".$picture['id']."' LIMIT 1");
msg('Удалено');
}}


echo '<div class="menu_razd">Отмечаем друзей на фото '.$picture['name'].'</div>';

$q=mysql_query("SELECT * FROM `albums_friend` WHERE `id_foto` = '".$picture['id']."' LIMIT 100");
$qs=mysql_result(mysql_query("SELECT count(*) FROM `albums_friend` WHERE `id_foto` = '".$picture['id']."' "),0);
if ($qs>0){
$n=0;
echo '<div class="p_m">На фотографии отмечены: ';
while ($post = mysql_fetch_array($q)){
$n++;
$fr=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$post['id_fr']."' limit 1"));
echo $fr['nick'].' [<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&frd&no='.$post['id_fr'].'"><font color="red">X</font></a>]'.($qs==$n?null:', ');
}
echo '</div>';
}

echo '<form class="p_m" action="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&frd&ok" method="post">';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE `user` = '".$user['id']."' "),0);
if($k_post==0)echo '<div class="p_m">У вас нет друзей</div>';
$q=mysql_query("SELECT * FROM `frends` WHERE `user` = '".$user['id']."' LIMIT 100");
echo 'Выберите друга которого хотите отметить на фотографии: <br><select name="sel">';
while ($post = mysql_fetch_array($q)){
$fr=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$post['frend']."' limit 1"));
echo '<option value="'.$fr['id'].'">'.$fr['nick'].'</option>';}
echo '</select><br /><input name="submit" type="submit" value="Отметить" onClick="return validate1(this.form)"></form></div>';
echo'</div><div class="foot">&laquo; <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'">К фотографии</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($user) && !isset($_SESSION['tes'.$user['id'].'_'.$picture['id']])){
mysql_query("UPDATE `albums_foto` SET `eye` = '".($picture['eye']+1)."' WHERE `id` = '$picture[id]' LIMIT 1");
$_SESSION['tes'.$user['id'].'_'.$picture['id']]=1;
}


if (isset($_GET['avatar']) && isset($_GET['ok']) && $ank['id']==$user['id'])
{mysql_query("UPDATE `albums_foto` SET `avatar` = '0' WHERE `id_u` = '$user[id]'");
mysql_query("UPDATE `albums_foto` SET `avatar` = '1' WHERE `id` = '$picture[id]'");
msg('Фотография успешно установлена на аватар');}

if (isset($_GET['delete']) && isset($_GET['ok']) && $ank['id']==$user['id'])
{@unlink(H."albums/pictures/size50/$picture[id].jpg");
@unlink(H."albums/pictures/size128/$picture[id].jpg");
@unlink(H."albums/pictures/original/$picture[id].jpg");
mysql_query("DELETE FROM `albums_foto` WHERE `id` = '$id' LIMIT 1");
mysql_query("DELETE FROM `albums_foto_komm` WHERE `id_picture` = '$picture[id]'");
mysql_query("DELETE FROM `albums_foto_rating` WHERE `id_picture` = '$picture[id]'");
header('Location: ?'.($dir?'im=album&user='.$user['id'].'&dir='.$dir.'&'.$passgen:'id='.$user['id'].''));}

if (isset($_GET['edit']) && isset($_GET['ok']) && $_POST['name']!=NULL && $ank['id']==$user['id'])
{$name=my_esc($_POST['name']);$opis=my_esc($_POST['opis']);$pri=intval($_POST['pri']);
if (strlen2($name)>32)$err='Название не может быть более 32 символов';
if (strlen2($name)<2)$err='Название не может быть менее 2 символов';
if (strlen2($opis)>1000)$err='Описание не может быть более 1000 символов';
if (!isset($err)){
mysql_query("UPDATE `albums_foto` SET `name` = '$name' , `opis` = '$opis' , `pri` = '$pri'  WHERE `id` = '$id'");
msg('Фотография успешно изменена');}}

if (isset($_GET['out']) && isset($_GET['lg']) OR isset($_GET['rg'])){
if (isset($_GET['lg']))$z='-';
else $z='+';
rotate_img("pictures/size50/".$picture['id'].".jpg", "pictures/size50/".$picture['id'].".jpg", "".$z."90");
rotate_img("pictures/size128/".$picture['id'].".jpg", "pictures/size128/".$picture['id'].".jpg", "".$z."90");
rotate_img("pictures/original/".$picture['id'].".jpg", "pictures/original/".$picture['id'].".jpg", "".$z."90");
header('Location: ?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&out');
}



if (isset($_GET['del']) && ($user['level']>3 || $user['id'] == $picture['id_u'])){
mysql_query("DELETE FROM `albums_foto_komm` WHERE `id`='".intval($_GET['del'])."'");
msg('Сообщение успешно удалено');}

if (isset($_GET['reply'])) {
$reply=(int)$_GET['reply'];
$an=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$reply."' limit 1"));
$ts=mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_komm` WHERE `id_u` = '$an[id]' AND `id_picture`='$picture[id]' LIMIT 1"),0);
if ($an['id']==0 || $an['id']==null || $an['id']==$user['id'] || $ts==0 || !isset($user)){header('Location: /?');exit;}
include_once '../sys/inc/thead.php';
if (isset($_POST['msg'])) {
$msg=my_esc($_POST['msg']);
$dsql = mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$ank[id]' AND `frend` = '$user[id]') OR (`user` = '$user[id]' AND `frend` = '$ank[id]') LIMIT 1");
if (strlen2($msg)<2 OR strlen2($msg)>1000)$err='Ошибка! Слишком длинное или короткое сообщение!';
if ($user['id']!=$picture['id_u'] && $picture['pri']==2 && $user['level']==0)$err='Автор запретил добавлять комментарии к этой фотографии';
if ($user['id']!=$picture['id_u'] && mysql_result($dsql, 0)==0 && $picture['pri']==1 && $user['level']==0)$err='Автор разрешил добавлять комментарии только друзьям';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_komm` WHERE `id_u` = '$user[id]' AND `id_picture`='$picture[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}

err();
if (!isset($err))
{
$msgrat1="$user[nick] ответил вам в комментариях к фотографии [url=/albums/?im=picture&user=".$ank['id']."&dir=".$dir."&id=".$picture['id']."&page=end]$picture[name][/url]";
if ($an['id']!=$picture['id_u'])mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$an[id]', '$msgrat1', '$time')");
$msgrat2="$user[nick] оставил комментарий к вашей фотографии [url=/albums/?im=picture&user=".$ank['id']."&dir=".$dir."&id=".$picture['id']."&page=end]$picture[name][/url]";
if ($user['id']!=$picture['id_u'])mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$msgrat2', '$time')");
mysql_query("INSERT INTO `albums_foto_komm` (id_u, time, msg, id_picture, otv) values('$user[id]', '$time', '$msg', '$picture[id]', '$reply')");
header('Location: ?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&'.$passgen.'');
}}
echo '<form method="post" name="message" action="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&reply='.$an['id'].'&'.$passgen.'">';
echo 'Ответ: '.$an['nick'].'<br>Сообщение:<br /><textarea name="msg" id="markItUp"></textarea><br /><input name="post" value="Отправить" type="submit"><br /></form>
<div class="foot">&laquo; <a href="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'">Вернуться</a></div>';
include_once '../sys/inc/tfoot.php';
}

if (isset($_POST['msg']) && isset($user)){$msg=$_POST['msg'];
if (strlen2($msg)<2 OR strlen2($msg)>1000)$err='Ошибка! Слишком длинное или короткое сообщение!';
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_komm` WHERE `id_picture` = '$picture[id]' AND `id_u` = '$user[id]' AND `msg` = '".mysql_escape_string($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){
if ($ank['id']!=$user['id']){
$msgrat2="$user[nick] оставил комментарий к вашей фотографии [url=/albums/?im=picture&user=".$ank['id']."&dir=".$dir."&id=".$picture['id']."&page=end]$picture[name][/url]";
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$msgrat2', '$time')");
}
mysql_query("INSERT INTO `albums_foto_komm` (`id_picture`, `id_u`, `time`, `msg`) values('$picture[id]', '$user[id]', '$time', '".my_esc($msg)."')");
msg('Сообщение успешно добавлено');}}

err();

if (isset($user) && $ank['id'] != $user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_rating` WHERE `id_u` = '$user[id]' AND `id_picture` = '$picture[id]'"), 0)==0)
{if (isset($_GET['r']) && $_GET['r']=='minus'){
mysql_query("UPDATE `albums_foto` SET `rating` = '".($picture['rating']-1)."' WHERE `id` = '$picture[id]' LIMIT 1",$db);
mysql_query("INSERT INTO `albums_foto_rating` (`id_u`, `id_picture`,`cn`) values('$user[id]', '$picture[id]','-1')",$db);
msg ('Ваш отзыв принят');
}elseif(isset($_GET['r']) && $_GET['r']=='plus'){
mysql_query("UPDATE `albums_foto` SET `rating` = '".($picture['rating']+1)."' WHERE `id` = '$picture[id]' LIMIT 1",$db);
mysql_query("INSERT INTO `albums_foto_rating` (`id_u`, `id_picture`,`cn`) values('$user[id]', '$picture[id]','+1')",$db);
msg ('Ваш отзыв принят');}
$picture=mysql_fetch_assoc(mysql_query("SELECT * FROM `albums_foto` WHERE `id` = '$id' AND `id_u` = '$ank[id]' LIMIT 1"));}

			
echo '<b>'.$picture['name'].'.jpg</b><br>';

$src = imagecreatefromjpeg('pictures/original/'.$picture['id'].'.jpg');
if ($webbrowser){
echo '<center>';
$q=mysql_query("SELECT `id`,`name` FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' AND `id` > '$picture[id]' ");
$qq=mysql_query("SELECT `id`,`name` FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' AND `id` < '$picture[id]' ");
while ($post = mysql_fetch_array($qq))echo '<a class="group1" href="pictures/original/'.$post['id'].'.jpg" title="'.$post['name'].'"> </a>';
echo '<a class="group1"  href="pictures/original/'.$picture['id'].'.jpg" ><img src="/albums/pictures/size128/'.$picture['id'].'.jpg" title="'.$picture['name'].'" alt="'.$picture['name'].'"></a>';
while ($post = mysql_fetch_array($q))echo '<a class="group1" href="pictures/original/'.$post['id'].'.jpg" title="'.$post['name'].'"> </a>';
echo '<br/><font style="font-size: 11px;">('.imagesx($src).'x'.imagesy($src).'), '.size_file(filesize(H.'albums/pictures/original/'.$picture['id'].'.jpg')).'</font></center>';
}
else echo '<center><img src="/albums/pictures/size128/'.$picture['id'].'.jpg" title="'.$picture['name'].'" alt="'.$picture['name'].'"><br/><font style="font-size: 11px;">('.imagesx($src).'x'.imagesy($src).'), '.size_file(filesize(H.'albums/pictures/original/'.$picture['id'].'.jpg')).'</font></center>';

$listr = mysql_fetch_assoc(mysql_query("SELECT * FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' AND `id` > '$picture[id]' ORDER BY `id` ASC LIMIT 1"));
$list = mysql_fetch_assoc(mysql_query("SELECT * FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' AND `id` < '$picture[id]' ORDER BY `id` DESC LIMIT 1"));
$count = mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' "),0);
$count1 = mysql_result(mysql_query("SELECT count(*) FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' AND `id` < '$picture[id]' "),0);

echo '<div class="foot"><table class="gg" style="width: 100%">';
echo '<td style="width: 33%"><center><a href="/albums/?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.($list['id']?$list['id']:mysql_result(mysql_query("SELECT MAX(`id`) FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' "),0)).'"><<Пред.</a></center></td>';
echo '<td style="width: 33%"><center> ('.($count1+1).' из '.$count.') </center></td>';
echo '<td style="width: 33%"><center><a href="/albums/?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.($listr['id']?$listr['id']:mysql_result(mysql_query("SELECT MIN(`id`) FROM `albums_foto` WHERE `id_album` = '$dir' AND `id_u` = '$ank[id]' "),0)).'">След.>></a></center></td>';
echo '</td></tr></table></div>';

$q=mysql_query("SELECT * FROM `albums_friend` WHERE `id_foto` = '".$picture['id']."' LIMIT 100");
$qs=mysql_result(mysql_query("SELECT count(*) FROM `albums_friend` WHERE `id_foto` = '".$picture['id']."' "),0);
if ($qs>0){
$n=0;
echo '<div class="p_m">На фотографии отмечены: ';
while ($post = mysql_fetch_array($q)){
$n++;
$fr=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$post['id_fr']."' limit 1"));
echo '<a href="/info.php?id='.$fr['id'].'">'.$fr['nick'].'</a>'.($qs==$n?null:', ');
}
echo '</div>';
}

if ($picture['rating']>0)$m='poloj';
elseif ($picture['rating']<0)$m='otric';
elseif ($picture['rating']==0)$m='default';
echo '<div class = "p_m"> '.($picture['opis']!=NULL?'<b>Описание:</b> '.output_text($picture['opis']).' <br>' :null).'<img src=" '.$m.'.png "> Рейтинг: ';
if (isset($user) && $ank['id'] != $user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_rating` WHERE `id_u` = '$user[id]' AND `id_picture` = '$picture[id]'"), 0)==0)echo '[<a href="/albums/?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&r=minus" title="Минус">-</a>] ';
echo $picture['rating'];
if (isset($user) && $ank['id'] != $user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_rating` WHERE `id_u` = '$user[id]' AND `id_picture` = '$picture[id]'"), 0)==0)echo ' [<a href="/albums/?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&r=plus" title="Плюс">+</a>]';
echo ' [<a href="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&whoR">подр.</a>]
<br><img src="eye.png "> Просмотров: '.$picture['eye'].'
<br><img src="avtor.png "> Автор: <a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].'</a> ('.vremja($picture['time']).')
'.($ank['id']==$user['id']?'<br><img src="friends.png "> <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&frd">Отметить друзей</a>':null).'
</div>

<div class="p_m"><a href="/albums/pictures/original/'.$picture['id'].'.jpg" title="Скачать оригинал">Скачать оригинал</a> ('.size_file(filesize(H.'albums/pictures/original/'.$picture['id'].'.jpg')).')</div>
<div class="p_m"><a href="/albums/pictures/size128/'.$picture['id'].'.jpg" title="Скачать 128x128">Скачать 128x128</a> ('.size_file(filesize(H.'albums/pictures/size128/'.$picture['id'].'.jpg')).')</div>
<div class="p_m"><a href="/albums/pictures/size50/'.$picture['id'].'.jpg" title="Скачать 50x50">Скачать 50x50</a> ('.size_file(filesize(H.'albums/pictures/size50/'.$picture['id'].'.jpg')).')</div>';
if ($ank['id']==$user['id']){
echo '<div class="menu_razd">Меню</div>
<div class = "p_m"><img src="avatar.png"> <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&avatar">Заменить аватар</a></div>
<div class = "p_m"><img src="edit.png"> <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&edit">Редактировать</a></div>
<div class = "p_m"><img src="lg.png"> <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&out">Повернуть</a></div>
<div class = "p_m"><img src="delete.png"> <a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&delete">Удалить</a></div>';


if (Isset($_GET['avatar']) && !Isset($_GET['ok']))echo '<div class="p_m">Вы уверены что хотите заменить ваш аватар ?<br>[<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&avatar&ok">Заменить</a>] [<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'">Отмена</a>]</div>';
elseif (Isset($_GET['delete']))echo '<div class="p_m">Вы уверены что хотите удалить фотографию '.$picture['name'].' ?<br>[<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&delete&ok">Удалить</a>] [<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'">Отмена</a>]</div>';
elseif (Isset($_GET['out']))echo '<div class="p_m">Поворот фотографии: [<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&out&lg"><img src="lg.png"></a>] [<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&out&rg"><img src="rg.png"></a>]</div>';
elseif (Isset($_GET['edit']) && !Isset($_GET['ok']))echo '<form class="p_m" action="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'&edit&ok" method="post">
Название: 
<br><input type="text" name="name" value = "'.$picture['name'].'"> .jpg<br />
Описание: <br />
<textarea name="opis">'.$picture['opis'].'</textarea><br>
Комментируют:<br /><select name="pri">
<option value="0" '.($picture['pri']==0?" selected='selected'":null).'>Все</option>
<option value="1" '.($picture['pri']==1?" selected='selected'":null).'>Друзья</option>
<option value="2" '.($picture['pri']==2?" selected='selected'":null).'>Никто</option></select><br />
<input class="submit" type="submit" value="Изменить"><br />
[<a href="?im=picture&user='.$user['id'].'&dir='.$dir.'&id='.$picture['id'].'">Отмена</a>]</form>';
}

echo '<div class="menu_razd">Комментарии</div>';

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `albums_foto_komm` WHERE `id_picture` = '$picture[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo '<div class="p_m">Нет комментариев</div>';

$q=mysql_query("SELECT * FROM `albums_foto_komm` WHERE `id_picture` = '$picture[id]' ORDER BY `id` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank2=mysql_fetch_assoc(mysql_query("select `id`,`nick`,`pol` from `user` where `id`='".$post['id_u']."' limit 1"));
$otv=mysql_fetch_assoc(mysql_query("select `nick` from `user` where `id`='".$post['otv']."' limit 1"));
echo '<div class="p_m"><img src="/style/themes/'.$set['set_them'].'/user/'.$ank2['pol'].'.png"> 
<a href="/info.php?id='.$ank2['id'].'">'.$ank2['nick'].'</a> '.online($ank2['id']).' ('.vremja($post['time']).')<br />
'.($post['otv']!=0?'<small>Ответ <b>'.$otv['nick'].'</b>:</small><br />':null).' '.output_text($post['msg']).' <br/>
'.($ank2['id']!=$user['id']?'[<a href="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&reply='.$ank2['id'].'">Ответить</a>]':null).' 
 '.($user['level']>3 || $user['id'] == $picture['id_u']?'[<a href="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&del='.$post['id'].'">Удалить</a>]':null).'
';
echo '</div>';
}
if ($k_page>1)str('?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&',$k_page,$page);
if ($user['id']!=$ank['id'] && $picture['pri']==2 && $user['level']==0){echo 'Автор запретил добавлять комментарии к этой фотографии.';}
else{$dsql = mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$ank[id]' AND `frend` = '$user[id]') OR (`user` = '$user[id]' AND `frend` = '$ank[id]') LIMIT 1");
if ($user['id']!=$ank['id'] && mysql_result($dsql, 0)==0 && $picture['pri']==1 && $user['level']==0){echo 'Автор разрешил добавлять комментарии только друзьям.';}
else{echo '<form method="post" name="message" action="?im=picture&user='.$ank['id'].'&dir='.$dir.'&id='.$picture['id'].'&'.$passgen.'">';
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else echo 'Сообщение:<br /><textarea name="msg"></textarea><br />';
echo '<input value="Отправить" type="submit" /></form>';
}
}
}
echo '  <div class="foot">● <a href="?id='.$ank['id'].'">В начало</a>';

$as=explode('/', $alb['put']);
$ar=count($as);
for ($p = 0; $p < ($ar-1); $p++){
$test = mysql_fetch_array(mysql_query("SELECT * FROM `albums` WHERE `id` = '$as[$p]' LIMIT 1"));
echo' <a href="?im=album&user='.$ank['id'].'&dir='.$test['id'].'">'.$test['name'].'</a> » ';}
echo'</div><div class="foot">&laquo; <a href="/info.php?id='.$ank['id'].'">В анкету</a></div>';

break;
}
include_once '../sys/inc/tfoot.php';
?>