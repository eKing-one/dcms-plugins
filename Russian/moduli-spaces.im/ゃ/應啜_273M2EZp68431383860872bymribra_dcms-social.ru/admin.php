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

only_reg();

$id = intval($_GET['id']);
$idx = intval($_GET['id_user']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`='$id' and `author`='$idx' "),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
if (isset($_GET['act'])) {$act = altec($_GET['act']);} else {$act = 'index';}
switch ($act):
### Главная страница

###Добавление фото
case "addFoto":
$set['title']='Загрузка фотографии | '.truncate_utf8($data['title'], 15); // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';
if (isset($_FILES['file']))
{
if ($imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'])))
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=50; // ширина
$dstH=50; // высота
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=50;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=50;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
//imagedestroy($imgc);
imagejpeg($screen,H."group/pic/$id.jpg",90);
@chmod(H."group/pic/$id.jpg",0777);
imagedestroy($screen);
}
else $err='<div class="err">Выбранный Вами формат изображения не поддерживается</div>';
}
echo'<div class="title">Установка главной фотографии</div>';
echo "<div class='nav2'>";
echo group_img($data['id']);
echo "</div>";

echo "<form enctype=\"multipart/form-data\" action='admin.php?act=addFoto&id=$id' method=\"post\">";
echo "<input name='file' type='file' accept='image/*,image/jpeg' />\n";
echo'<div class="mt3 mb3">';
echo "<input class=\"submit\" type=\"submit\" value=\"Заменить\" />\n";
echo'<a href="index.php?act=view&id='.$id.'">Отменить</a>';
echo "</form>";
echo'</div>';

break;
###Редактирование группы
case "edit":
$set['title']='Редактирование | '.truncate_utf8($data['title'], 15); // заголовок страницы
include_once '../sys/inc/thead.php';
title();

echo'<div id="content">';



if(isset($_POST['title']) && isset($_POST['desc'])){
$title = altec($_POST['title']);
$desc = altec($_POST['desc']);
$forum = (isset($_POST['forum'])) ? 1 : 0;
$foto = (isset($_POST['foto'])) ? 1 : 0;
if (utf_strlen($title) >= 2 && utf_strlen($title) < 50) {
if (utf_strlen($desc) >= 10 && utf_strlen($desc) < 300) {
mysql_query("UPDATE `group` SET `title` = '".$title."', `desc` = '".$desc."', `foto` = '".$foto."', `forum` = '".$forum."' WHERE `id` = '$data[id]' LIMIT 1");
msg("<div class='msg'>Настройки сохранены!</div>");
}else{echo'<div class="err">Ошибка! Описание должно быть в пределах от 10 до 300 символов</div>';}
}else{echo'<div class="err">Ошибка! Название должно быть в пределах от 2 до 50 символов</div';}
}

echo'<div class="title">Изменение настроек группы</div>';



echo'<form action="admin.php?act=edit&id='.$id.'" method="post">';
echo'Название';
echo'<input type="text" name="title" value="'.$data['title'].'"/>';
echo'<div class="">Описание</div>';
echo'<div class="smp"><textarea rows="10" name="desc">'.$data['desc'].'</textarea></div>';
$foto = ($data['foto'] == 1 ) ? 'checked="checked"' : '';
echo'<div class="stamp mb3"><input type="checkbox" name="foto" '.$foto.'/> Фотоальбомы создает только администрация<br/>';
$forum = ($data['forum'] == 1 ) ? 'checked="checked"' : '';
echo'<input type="checkbox" name="forum" '.$foto.'/> Темы создает только администрация</div>';
echo'<div class="mt3 mb3">';
echo'<input value="Сохранить" type="submit" name="button_create" /> <a  href="index.php?act=view&id='.$id.'">Отменить</a></form>';
echo '</div>';

break;
###Удаление группы
case "delete":
$set['title']='Удаление группы | '.truncate_utf8($data['title'], 15); // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';
if(isset($_POST['link_id'])){
mysql_query("DELETE FROM `group` WHERE `id`='".$data['id']."'");
mysql_query("DELETE FROM `group_news` WHERE `group`='".$data['id']."'");
mysql_query("DELETE FROM `group_forum` WHERE `group`='".$data['id']."'");
mysql_query("DELETE FROM `group_users` WHERE `group`='".$data['id']."'");
header("Location: index.php");
}

echo'<div class="title">';
echo'Удаление группы:<font color="white"> '.$data['title'].'</font>';
echo'</div>';


echo'<form action="admin.php?act=delete&id='.$id.'" method="post">';
echo'<div class="err">Вы действительно хотите удалить эту группу?<br/>';
echo'Группа удаляется без возможности восстановления.<br/>';
echo'Все темы, новости и фотоальбомы также удаляются вместе с группой.</div>';
echo'<div class="fbtns tac">';
echo'<input type="hidden" name="link_id" value="'.$id.'"/>';
echo'<center><input value="Удалить" type="submit" name="button_create" />';
echo'<a cl href="index.php?act=view&id='.$id.'">Отмена</a></center>';
echo '</div>';

echo'</form>';



break;
default:
header("location: admin.php?");
endswitch;
echo'<div class="foot">';
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
?>