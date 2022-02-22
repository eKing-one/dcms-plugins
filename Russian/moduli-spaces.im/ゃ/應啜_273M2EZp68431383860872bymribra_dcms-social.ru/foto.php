<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
include_once 'config.php';
if (isset($_GET['act'])) {$act = altec($_GET['act']);} else {$act = 'index';} 
if (isset($_GET['id'])) {$id = intval($_GET['id']);} else {header("Location: index.php?");} 
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`='$id'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
switch ($act):
### Главная страница
case "index":
$set['title']='Фотоальбомы | '.truncate_utf8($data['title'], 15); // заголовок страницы
include_once '../sys/inc/thead.php';
title();




//echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a>';

$foto1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$data[id]'"),0);
$foto2 = ($foto1!=0) ? '<font color="red">'.$foto1.'</font>' : ''; 
echo"<div class='title'>Фотоальбомы</div>";





if((mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' AND `user`='$user[id]'"),0)==1 && $data['foto']==0) || $data['author']==$user['id']){

echo'<div class="mess"><a href="foto.php?act=creation&id='.$id.'"><img class="icn" alt="" src="/style/icons/pht2.png" />Создать фотоальбом</a></div>';
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto_alb` WHERE `group`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Фотоальбомы отсутствуют</div>';}
$q=mysql_query("SELECT * FROM `group_foto_alb` WHERE `group`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){


$foto1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$data[id]'"),0);
$foto2 = ($foto1!=0) ? '('.$foto1.')' : '';
echo'<a  href="foto.php?act=cat&alb='.$data['id'].'&id='.$id.'">';
echo'<div class="nav2">';
echo group_foto($data['defult'], 50);
echo''.$data['title'].'';
echo ' '.$foto2.'';
echo'</div>';


}

if ($k_page>1)str('foto.php?act=index&id='.$id.'&',$k_page,$page); // Вывод страниц

$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <a href="index.php"><span class="nav1">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Создание фотоальбома
case "creation":
$set['title']='Создание фотоальбома | '.truncate_utf8($data['title'], 15); // заголовок страницы
include_once '../sys/inc/thead.php';
title();
only_reg();

echo'<div id="content">';


if((mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' AND `user`='$user[id]'"),0)==1 && $data['foto']==0) || $data['author']==$user['id']){
if(isset($_POST['title'])){
$title = altec($_POST['title']);
if (utf_strlen($title) >= 5 && utf_strlen($title) < 50) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto_alb` WHERE `title` = '$title' AND `group` = '$id' LIMIT 1"),0)==0){
mysql_query("INSERT INTO `group_foto_alb` (`author`, `title`, `group`, `time`, `time_last`) values ('$user[id]', '$title', '$id', '$time', '$time')");
$foto = mysql_insert_id();
header("Location: foto.php?id=$id");
}else{echo'<div class="err">Ошибка! Фотоальбом  с таким названием уже существует!</div>';}
}else{echo'<div class="err">Ошибка! Название должно быть в пределах от 5 до 50 символов</div>';}
}




//echo'<a href="foto.php?act=cat&alb='.$data['id'].'&id='.$id.'" class="grp">'.$data['title'].'</a>';

echo'<div class="title">Создание фотоальбома</div>';


echo'<form action="foto.php?act=creation&id='.$id.'" method="post">';

echo'<input type="text" name="title"/><br/>';

echo'<div class="mt3 mb3">';
echo'<input value="Сохранить" type="submit" name="button_create" />&nbsp;&nbsp;<span class="act"><a class="ai alnk" href="foto.php?id='.$id.'"><span class="lnk">Отменить</span></a></span></div><span class="spinner"></span></form>';

echo'</div>';




}else{echo'<div class="err">Ошибка! Создавать альбомы может только администрация</div>';}

$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="foto.php?id='.$id.'">Фотоальбомы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';

break;
###Фотоальбом
case "cat":
$alb = intval($_GET['alb']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto_alb` WHERE `id` = '$alb'"),0)==0)header("Location: index.php");
$albom = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto_alb` WHERE `id` = '$alb' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$albom[author]' LIMIT 1"));
$set['title']='Фотоальбом | '.$albom['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

echo'<div id="content">';





//echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a>';

echo"<div class='title'>Фотоальбом</div>";
echo'<div class="mess"><a  href="foto.php?act=upload&alb='.$alb.'&id='.$id.'"> <img alt="" src="/style/icons/pht2.png" />Добавить фото в альбом</a></div>';


echo'<div class="nav2">';
echo group_foto($albom['defult'], 50);
echo ''.$albom['title'].'<br>';

echo'</div>';

echo '<div class="nav1">';
echo"</span>";
if($ank['nick']==NULL){$view_name_ank=$ank['id'];}else{$view_name_ank=htmlspecialchars($ank['nick']);}
echo 'Автор:</span> <a class="grp" href="/info.php?id='.$data['author'].'">'.$view_name_ank.'</a>'.online($ank['id']).'<br>';
echo 'Создан: '.times($data['time']).'';
echo'</div>';







$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$alb'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Фото отсутствуют</div>';}
$q=mysql_query("SELECT * FROM `group_foto` WHERE `albom`='$alb' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){


echo'<a href="foto.php?act=view&foto='.$data['id'].'&id='.$id.'">'.group_foto($data['id'], 50).'</a>';


}
if ($k_page>1)str('foto.php?act=index&id='.$id.'&',$k_page,$page); // Вывод страниц
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="foto.php?id='.$id.'">Фотоальбомы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Добавление фото
case "upload":
$alb = intval($_GET['alb']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto_alb` WHERE `id` = '$alb'"),0)==0)header("Location: index.php");
$albom = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto_alb` WHERE `id` = '$alb' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$albom[author]' LIMIT 1"));
$set['title']='Добавление фото | '.$albom['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';



//echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a>';

$foto1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$data[id]'"),0);
$foto2 = ($foto1!=0) ? '<span class="cgry">'.$foto1.'</span>' : ''; 

echo"<div class='title'>Добавление фотографии</div>";


echo'<div class="nav2">';
echo group_foto($albom['defult'], 50);
echo ''.$albom['title'].'<br>';

echo'</div>';

echo '<div class="nav1">';
echo"</span>";
if($ank['nick']==NULL){$view_name_ank=$ank['id'];}else{$view_name_ank=htmlspecialchars($ank['nick']);}
echo 'Автор:</span> <a class="grp" href="/info.php?id='.$data['author'].'">'.$view_name_ank.'</a>'.online($ank['id']).'<br>';
echo 'Создан: '.times($data['time']).'';
echo'</div>';
if(isset($_FILES['file']['tmp_name'])){




if($_FILES['file']['size']<500000){
if($_FILES['file']['type']=='image/jpg' OR $_FILES['file']['type']=='image/jpeg' OR $_FILES['file']['type']=='image/gif' OR $_FILES['file']['type']=='image/png'){
$file=esc(stripcslashes(htmlspecialchars($_FILES['file']['name'])));
$file=preg_replace('(\#|\?)', NULL, $file);
$ras=strtolower(preg_replace('#^.*\.#', NULL, $file));
mysql_query("INSERT INTO `group_foto` (`group`, `albom`, `author`, `desc`, `time`, `ras`) values ('$id', '$alb', '$user[id]', '', '$time', '$ras')");



$foto = mysql_insert_id();
$avatar_way = H."group/foto/".$foto; 
###Замена
$avatar_del=$foto.".gif";
@unlink(H."group/foto/$avatar_del");
$avatar_del=$foto.".jpg";
@unlink(H."group/foto/$avatar_del");
$avatar_del=$foto.".png";
@unlink(H."group/foto/$avatar_del");
###
switch($_FILES['file']['type']){
case 'image/jpg': $avatar_way.=".jpg"; break;
case 'image/jpeg': $avatar_way.=".jpg"; break;
case 'image/gif': $avatar_way.=".gif"; break;
case 'image/png': $avatar_way.=".png"; break;
}
copy($_FILES['file']['tmp_name'], $avatar_way); // сохраним файл на сервер
@chmod($avatar_way,0666);
header("Location: foto.php?act=view&foto=$foto&id=$id");

}else{$err[]=('Файл имеет неразрешенный тип!');}
}else{$err[]=('Фотография слишком велика!');}

$lenta = 'в группе добавлена фотография [url=/group/foto/][img]http://$_SERVER[HTTP_HOST]/group/foto/$foto[id].$foto[ras][/img][/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");

}
err();
echo '<form enctype="multipart/form-data" action="foto.php?act=upload&alb='.$alb.'&id='.$id.'" method="post">';

echo "<div class='stamp mb3'>Файловая система:</div>\n";
echo "<input name='file' type='file' accept='image/*,image/jpeg' />\n";


echo '<br><input class="submit" name="ok" type="submit" value="Загрузить" />&nbsp;&nbsp;<a  href="foto.php?act=cat&alb='.$alb.'&id='.$id.'">Отменить</a></form>';

$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="foto.php?id='.$id.'">Фотоальбомы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';

break;
###Просмотр фото
case "view":
$foto = intval($_GET['foto']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `id` = '$foto'"),0)==0)header("Location: index.php");
$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto` WHERE `id` = '$foto' LIMIT 1"));
$albom = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto_alb` WHERE `id` = '$foto[albom]' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$foto[author]' LIMIT 1"));
$set['title']='Фотография | '.$albom['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();


///echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a>';

$foto1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$data[id]'"),0);
$foto2 = ($foto1!=0) ? '<span class="cgry">'.$foto1.'</span>' : ''; 
echo"<div class='title'>Фотография</div>";


echo'<div class="nav2">';
echo '<a  href="/group/foto/'.$foto['id'].'.'.$foto['ras'].'">'.group_foto1($foto['id'], 150).'</a>';
echo'<div>';
$desc = ($foto['desc']>NULL) ? ''.$foto['desc'].'' : '';
echo $desc;

echo'<div class="nav1">Добавлена: '.times($foto['time']).'<br>';
echo'Автор:';
if($ank['nick']==NULL){$view_name_ank=$ank['id'];}else{$view_name_ank=htmlspecialchars($ank['nick']);}
echo '<a  href="/info.php?id='.$foto['author'].'"> '.$view_name_ank.'</a> '.online($ank['id']).'</div>';



echo "<div class='mess'>";
if($albom['author']==$user['id'] || $data['author']==$user['id']){
echo'  <span class="nav1"><a  href="foto.php?act=defult&foto='.$foto['id'].'&id='.$id.'"><img class="icn" alt="" src="/style/icons/pht2.png" />Сделать обложкой</a></span>';}
if($albom['author']==$user['id'] || $data['author']==$user['id'] || $foto['author']==$user['id']){
echo' <span class="nav1"><a href="foto.php?act=edit&foto='.$foto['id'].'&id='.$id.'"><img class="icn" alt="" src="/style/icons/pen2.png" />Изменить описание</a></span>';
echo' <span class="nav1"><a  href="foto.php?act=delete&foto='.$foto['id'].'&alb='.$foto['albom'].'&id='.$id.'"><img class="icn" alt="" src="/style/icons/crs2.png" />Удалить фото</a></span></div>';



}
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="foto.php?id='.$id.'">Фотоальбомы</a></span>';
echo' <span class="nav1"><a href="foto.php?act=cat&alb='.$foto['albom'].'&id='.$id.'">Фотоальбом</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Сделать обложкой
case "defult":
$foto = intval($_GET['foto']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `id` = '$foto'"),0)==0)header("Location: index.php");
$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto` WHERE `id` = '$foto' LIMIT 1"));
$albom = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto_alb` WHERE `id` = '$foto[albom]' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$foto[author]' LIMIT 1"));
if($albom['author']==$user['id'] || $data['author']==$user['id']){
mysql_query("UPDATE `group_foto_alb` SET `defult` = '$foto[id]' WHERE `id` = '$albom[id]' LIMIT 1");
header("Location: foto.php?act=cat&alb=$albom[id]&id=$id");
}else{echo'Ошибка! Попытка обмана!';}
break;
###Изменить описание
case "edit":
$foto = intval($_GET['foto']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `id` = '$foto'"),0)==0)header("Location: index.php");
$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto` WHERE `id` = '$foto' LIMIT 1"));
$albom = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto_alb` WHERE `id` = '$foto[albom]' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$foto[author]' LIMIT 1"));
$set['title']='Редактирование фотографии | '.$albom['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';


if(isset($_POST['desc'])){
if($albom['author']==$user['id'] || $data['author']==$user['id'] || $foto['author']==$user['id']){
$desc = altec($_POST['desc']);
if (utf_strlen($desc) >= 10 && utf_strlen($desc) < 200) {
mysql_query("UPDATE `group_foto` SET `desc` = '$desc' WHERE `id` = '$foto[id]' LIMIT 1");
header("Location: foto.php?act=view&foto=$foto[id]&id=$id");
}else{echo'<div class="err">Ошибка! Описание не может быть больше 200 символов и меньше 10 символов!</div>';}
}else{echo'<div class="err">Ошибка! Попытка обмана!</div>';}
}


//echo'<a href="index.php?act=view&id='.$data['id'].'" class="grp">'.$data['title'].'</a>';

$foto1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `albom`='$data[id]'"),0);
$foto2 = ($foto1!=0) ? '<span class="cgry">'.$foto1.'</span>' : ''; 
echo"<div class='title'>Описание фотографии</div>";


echo'<form action="foto.php?act=edit&foto='.$foto['id'].'&id='.$id.'" method="post">';


echo'Описание группы<br>';
echo'<textarea  cols="20" name="desc"  rows="1">'.$foto['desc'].'</textarea><br>';
echo'<input value="Сохранить" type="submit">&nbsp;&nbsp;<a  href="foto.php?act=view&foto='.$foto['id'].'&id='.$id.'">Отменить</form>';

echo'<div class="foot">';
echo' <a href="index.php"><span class="nav1">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="foto.php?id='.$id.'">Фотоальбомы</a></span>';
echo' <span class="nav1"><a href="foto.php?act=cat&alb='.$foto['albom'].'&id='.$id.'">Фотоальбом</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
#### Удаление фото
case "delete":
$foto = intval($_GET['foto']);
$alb = intval($_GET['alb']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto` WHERE `id` = '$foto'"),0)==0)header("Location: index.php");
$foto = mysql_fetch_assoc(mysql_query("SELECT * FROM `group_foto` WHERE `id` = '$foto' LIMIT 1"));
if($albom['author']==$user['id'] || $data['author']==$user['id'] || $foto['author']==$user['id']){
mysql_query("DELETE FROM `group_foto` WHERE `id`='".$foto['id']."'");
@unlink(H.'group/foto/'.$foto['id'].'.'.$foto['ras'].'');
header("Location: foto.php?act=cat&alb=$alb&id=$id");
}else{header("Location: index.php");}
break;
default:
header("location: index.php?");
endswitch;

?>