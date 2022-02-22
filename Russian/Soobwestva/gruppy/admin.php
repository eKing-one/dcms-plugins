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
only_reg();
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
$set['title']=$gruppy['name'].' - Админка'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($user) && $user['id']==$gruppy['admid'])
{
if(isset($_GET['konf']))
{
if(isset($_POST['save']))
{
if(isset($_POST['name']))
{
if($_POST['name']==htmlspecialchars($gruppy['name'])){htmlspecialchars($name = $gruppy['name']);}
elseif(htmlspecialchars($_POST['name'])!=htmlspecialchars($gruppy['name']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `name` = '".htmlspecialchars($_POST['name'])."' LIMIT 1"),0)==0)
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (strlen2($name)<3)$err[]='Короткое название';
if (strlen2($name)>32)$err[]='Название не должно быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В названии обнаружен мат: '.$mat;
$name = htmlspecialchars($_POST['name']);
if(!isset($err))
mysql_query("UPDATE `gruppy` SET `name`='$name' WHERE `id`='$gruppy[id]' LIMIT 1");
}
else $err[]='Сообщество с таким названием уже есть';
}
if(isset($_POST['desc']))
{
$desc = esc(stripcslashes(htmlspecialchars($_POST['desc'])));
if (strlen2($desc)<3)$err[]='Короткое описание';
if (strlen2($desc)>100)$err[]='Описание не должно быть длиннее 100 символов';
$mat=antimat($desc);
if ($mat)$err[]='В описании обнаружен мат: '.$mat;
$desc=my_esc($desc);
if(!isset($err))
mysql_query("UPDATE `gruppy` SET `desc`='$desc' WHERE `id`='$gruppy[id]' LIMIT 1");
}
if(isset($_POST['konf_gruppy']) && ($_POST['konf_gruppy']==0 || $_POST['konf_gruppy']==1 || $_POST['konf_gruppy']==3) || isset($_POST['konf_gruppy']) && $_POST['konf_gruppy']==2 && isset($_POST['plata']) && is_numeric($_POST['plata']) && $_POST['plata']>0)
{
$konf_gruppy = intval($_POST['konf_gruppy']);
if(isset($_POST['plata']) && $_POST['plata']!=NULL)$plata=intval($_POST['plata']); else $plata=NULL;
mysql_query("UPDATE `gruppy` SET `konf_gruppy`='$konf_gruppy' WHERE `id`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `plata`='$plata' WHERE `id`='$gruppy[id]' LIMIT 1");
}
else $err[]='Ошибка типа сообщества или не заполнено поле баллов при типе сообщества Платное';
if(isset($_POST['rules']) && $_POST['rules']!=NULL)
{
$rules = esc(stripcslashes(htmlspecialchars($_POST['rules'])));
if (strlen2($rules)<3)$err[]='Короткие правила';
if (strlen2($rules)>3024)$err[]='Правила не должны быть длиннее 3024 символов';
$mat=antimat($rules);
if ($mat)$err[]='В правилах обнаружен мат: '.$mat;
$rules=my_esc($rules);
if(!isset($err))
mysql_query("UPDATE `gruppy` SET `rules`='$rules' WHERE `id`='$gruppy[id]' LIMIT 1");
}
else
{
mysql_query("UPDATE `gruppy` SET `rules`='' WHERE `id`='$gruppy[id]' LIMIT 1");
}
if (isset($_POST['konf_news']) && $_POST['konf_news']==1)
{
$konf_news=1;
mysql_query("UPDATE `gruppy` SET `konf_news`='$konf_news' WHERE `id`='$gruppy[id]' LIMIT 1");
}
else
{
$konf_news=0;
mysql_query("UPDATE `gruppy` SET `konf_news`='$konf_news' WHERE `id`='$gruppy[id]' LIMIT 1");
}

if (isset($_POST['conf_news']) && $_POST['conf_news']==1)
{
$conf_news=1;
mysql_query("UPDATE `gruppy` SET `conf_news`='$conf_news' WHERE `id`='$gruppy[id]' LIMIT 1");
}
else
{
$conf_news=0;
mysql_query("UPDATE `gruppy` SET `conf_news`='$conf_news' WHERE `id`='$gruppy[id]' LIMIT 1");
}

if(!isset($err))
{
msg('Настройки успешно сохранены');
}
}
err();
echo'<div class="nav1">';
echo'<b>Конфигурация группы</b><br/>';
echo'<form method="post" action="?s='.$gruppy['id'].'&konf&'.$passgen.'">';
echo'Название<br/>';
echo'<input type="text" name="name" value="'.$gruppy['name'].'"><br/>';
echo'Описание<br/>';
echo'<textarea name="desc">'.$gruppy['desc'].'</textarea><br/>';
echo'<b>Тип группы</b><br/>';
echo'Открытое - открыто для чтения и вступления<br/>';
echo'Закрытое для чтения-разделы соо закрыты, вступление свободное<br/>';
echo'Платное - закрыто для чтения, за вступление снимается указанное кол-во баллов и передается на Ваш счет<br/>';
echo'Закрытое </b>- закрыто для чтения, вступить можно по приглашению участника сообщества или же после активации заявки<br/>';
echo '<select name="konf_gruppy">';
echo '<option value="3"'.($gruppy['konf_gruppy']==3?' selected="selected"':null).'>Закрытое</option>';
echo '<option value="2"'.($gruppy['konf_gruppy']==2?' selected="selected"':null).'>Платное</option>';
echo '<option value="1"'.($gruppy['konf_gruppy']==1?' selected="selected"':null).'>Закрытое для чтения</option>';
echo '<option value="0"'.($gruppy['konf_gruppy']==0?' selected="selected"':null).'>Открытое</option>';
echo '</select><br />';
echo'Если тип сообщества <b>Платное</b>, укажите сумму баллов, снимаемую за вступление<br/>';
echo'<input type="text" name="plata" value="'.$gruppy['plata'].'"><br/>';
echo'Правила сообщества<br/>';
echo'<textarea name="rules">'.$gruppy['rules'].'</textarea><br/>';
echo '<label><input type="checkbox" name="conf_news"'.($gruppy['conf_news']?' checked="checked"':null).' value="1"/> Выводить последнюю новость на главную соо</label><br/>';
echo'<input name="save" type="submit" value="Сохранить"></form><br/>';
echo'</div>';
echo "<div class='navi'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">В админку</a><br/>';
}
elseif(isset($_GET['logo']))
{
if (isset($_FILES['file']))
{

if (preg_match('#\.jpe?g$#i',$_FILES['file']['name']) && $imgc=@imagecreatefromjpeg($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>80 || imagesy($imgc)>80)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=80; // ширина
$dstH=80; // высота
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=80;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=80;
$dstW=ceil($dstH/$prop);
}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
@chmod(H."gruppy/logo/$gruppy[id].jpg",0777);
@chmod(H."gruppy/logo/$gruppy[id].gif",0777);
@chmod(H."gruppy/logo/$gruppy[id].png",0777);
@unlink(H."gruppy/logo/$gruppy[id].jpg");
@unlink(H."gruppy/logo/$gruppy[id].gif");
@unlink(H."gruppy/logo/$gruppy[id].png");
imagejpeg($screen,H."gruppy/logo/$gruppy[id].jpg",100);
@chmod(H."gruppy/logo/$gruppy[id].jpg",0777);
imagedestroy($screen);
}
else
{
copy($_FILES['file']['tmp_name'], H."gruppy/logo/$gruppy[id].jpg");
}

msg("Аватар успешно установлен");
}
elseif (preg_match('#\.gif$#',$_FILES['file']['name']) && $imgc=@imagecreatefromgif($_FILES['file']['tmp_name']))
{
include_once '../sys/inc/gif_resize.php';
$screen=gif_resize(fread ( fopen ($_FILES['file']['tmp_name'], "rb" ), filesize ($_FILES['file']['tmp_name']) ),80,80);
@chmod(H."gruppy/logo/$gruppy[id].jpg",0777);
@chmod(H."gruppy/logo/$gruppy[id].gif",0777);
@chmod(H."gruppy/logo/$gruppy[id].png",0777);
@unlink(H."gruppy/logo/$gruppy[id].jpg");
@unlink(H."gruppy/logo/$gruppy[id].gif");
@unlink(H."gruppy/logo/$gruppy[id].png");

file_put_contents(H."gruppy/logo/$gruppy[id].gif", $screen);
@chmod(H."gruppy/logo/$gruppy[id].gif",0777);

msg("Аватар успешно установлен");
}
elseif (preg_match('#\.png$#',$_FILES['file']['name']) && $imgc=@imagecreatefrompng($_FILES['file']['tmp_name']))
{
if (imagesx($imgc)>80 || imagesy($imgc)>80)
{
$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
if ($img_x==$img_y)
{
$dstW=80; // ширина
$dstH=80; // высота
}
elseif ($img_x>$img_y)
{
$prop=$img_x/$img_y;
$dstW=80;
$dstH=ceil($dstW/$prop);
}
else
{
$prop=$img_y/$img_x;
$dstH=80;
$dstW=ceil($dstH/$prop);
}

$screen=ImageCreate($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);


@chmod(H."gruppy/logo/$gruppy[id].jpg",0777);
@chmod(H."gruppy/logo/$gruppy[id].gif",0777);
@chmod(H."gruppy/logo/$gruppy[id].png",0777);
@unlink(H."gruppy/logo/$gruppy[id].jpg");
@unlink(H."gruppy/logo/$gruppy[id].gif");
@unlink(H."gruppy/logo/$gruppy[id].png");
imagepng($screen,H."gruppy/logo/$gruppy[id].png");
@chmod(H."gruppy/logo/$gruppy[id].png",0777);
imagedestroy($screen);
}
else
{

copy($_FILES['file']['tmp_name'], H."gruppy/logo/$gruppy[id].png");
}

msg("Аватар успешно установлен");
}
else
{
$err='Неверный формат файла';
}
}
err();
echo '<form method="post" enctype="multipart/form-data" action="?s='.$gruppy['id'].'&logo">';
echo '<table class="post">';
echo '<tr>';
echo '<td class="icon48" rowspan="2">';
if (is_file(H."gruppy/logo/$gruppy[id].gif"))
echo '<img src="logo/'.$gruppy['id'].'.gif" alt="'.$gruppy['name'].'" />';
elseif (is_file(H."gruppy/logo/$gruppy[id].jpg"))
echo '<img src="logo/'.$gruppy['id'].'.jpg" alt="'.$gruppy['name'].'" />';
elseif (is_file(H."gruppy/logo/$gruppy[id].png"))
echo '<img src="logo/'.$gruppy['id'].'.png" alt="'.$gruppy['name'].'" />';
else
echo '<img src="/style/user/user.png" alt="No Logo" />';
echo '</td>';
echo '<td class="p_t">';
echo 'Текущее лого';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td class="p_m">';
echo 'Можно загружать картинки форматов: GIF, JPG, PNG<br />';
echo 'Качественное преобразование GIF-анимации не гарантируется<br />';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td colspan="2">';
echo '<input type="file" name="file" accept="image/*,image/gif,image/png,image/jpeg" />';
echo '<input value="Заменить" type="submit" />';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>';
echo "<div class='navi'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">В админку</a><br/>';
echo "</div>\n";
}
elseif(isset($_GET['users']))
{
$activ = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `activate`='1'"),0);
if(isset($_GET['activate']) && $_GET['activate']=='all')
{
$q=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1'");
while ($act = mysql_fetch_assoc($q))
{
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$act[id]', 'Ваша заявка на вступление в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url] активирована', '$time')");
}
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']+$activ)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy_users` SET `activate`='0' WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1' LIMIT $activ");
msg('Все заявки активированы');
}
elseif(isset($_GET['activate']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '".intval($_GET['activate'])."' AND `id_gruppy`='$gruppy[id]' AND `activate`='1' LIMIT 1"),0)==1)
{
$activate = intval($_GET['activate']);
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$activate', 'Ваша заявка на вступление в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url] активирована', '$time')");
mysql_query("UPDATE `gruppy_users` SET `activate`='0' WHERE `id_user`='$activate' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']+1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
msg('Заявка активирована');
}

if(isset($_GET['deactivate']) && $_GET['deactivate']=='all')
{
$q2=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1'");
while ($act2 = mysql_fetch_assoc($q2))
{
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$act2[id]', 'Ваша заявка на вступление в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url] отклонена', '$time')");
}
mysql_query("DELETE FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1' LIMIT $activ");
msg('Все заявки отклонены');
}
elseif(isset($_GET['deactivate']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '".intval($_GET['deactivate'])."' AND `id_gruppy`='$gruppy[id]' AND `activate`='1' LIMIT 1"),0)==1)
{
$deactivate = intval($_GET['deactivate']);
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$deactivate', 'Ваша заявка на вступление в сообщество [url=/gruppy/$gruppy[id]]$gruppy[name][/url] отклонена', '$time')");
mysql_query("DELETE FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='".intval($_GET['deactivate'])."' LIMIT 1");
msg('Заявка отклонена');
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="err">';
echo 'Ждущих активации нет';
echo '</div>';
echo '</tr>';
}

$q=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='1' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($ank = mysql_fetch_assoc($q))
{
$us=get_user($ank['id_user']);
echo '<tr>';
echo '<td class="icon14">';
if($num==1){
echo "<div class='p_t'>\n";
$num=0;
}else{
echo "<div class='p_t'>\n";
$num=1;}
echo '<img src="/style/themes/'.$set['set_them'].'/user/'.$us['pol'].'.png" alt="" /> ';
echo '<a href="/info.php?id='.$us['id'].'">'.$us['nick'].'</a> '.online($us['id']).'';
echo '</div>';
echo '</div>';
echo '</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';
echo '<span class="ank_n">Дата подачи:</span> <span class="ank_d">'.vremja($ank['time']).'</span><br/>';
echo'[<a href="?s='.$gruppy['id'].'&users&activate='.$us['id'].'">Активировать</a>|<a href="?s='.$gruppy['id'].'&users&deactivate='.$us['id'].'">Отклонить</a>]';
echo '</td>';
echo '</tr>';
}
echo'</table>';
if ($k_page>1)str("?s=$gruppy[id]&users&",$k_page,$page); // Вывод страниц
echo "<div class='foot'>\n";
echo'<img src="img/18od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&users&activate=all">Активировать всех</a><br/>';
echo '</div>';
echo "<div class='foot'>\n";
echo'<img src="img/19od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&users&deactivate=all">Отклонить всех</a><br/>';
echo '</div>';
echo "<div class='foot'>\n";
echo'<img src="img/ank.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">В админку</a><br/>';
echo '</div>';
}
elseif(isset($_GET['bl']))
{
if(isset($_GET['del']) && $_GET['del']=='all')
{
mysql_query("DELETE FROM `gruppy_bl` WHERE `id_gruppy`='$gruppy[id]' LIMIT 0");
msg('Блэк-лист очищен');
}
elseif(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='".intval($_GET['del'])."' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_bl` WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='".intval($_GET['del'])."' LIMIT 1");
msg('Юзер успешно удален');
}
if(isset($_GET['uz']) && $_GET['uz']!=NULL && $_GET['uz']!=$user['id'] && $_GET['uz']!=$user['nick'])
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_GET['uz'])."' OR `nick`='".my_esc($_GET['uz'])."' LIMIT 1"),0)==1)
{
$uz=mysql_fetch_array(mysql_query("SELECT `id` FROM `user` WHERE `nick`='".my_esc($_GET['uz'])."' OR `id` = '".intval($_GET['uz'])."'"));
$uzer=get_user($uz['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$uzer[id]' LIMIT 1"),0)==1)
{
echo'<div class="err">Юзер '.$uzer['nick'].' уже есть в блэк-листе</div>';
}
else
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_user` = '$uzer[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_users` WHERE `id_user`='$uzer[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
mysql_query("UPDATE `gruppy` SET `users` = '".($gruppy['users']-1)."' WHERE `id` = '$gruppy[id]' LIMIT 1");
}
mysql_query("INSERT INTO `gruppy_bl` (`id_gruppy`, `id_user`, `time`) values ('$gruppy[id]', '$uzer[id]', '$time')");
msg('Юзер успешно добавлен');
}
}
else
{
echo'<div class="err">Юзер с ID или ником '.my_esc($_GET['uz']).' не найден';
}
}

echo' BlackList - это список пользователей сайта, которые никогда не смогут вступить в Ваше сообщество или просмотреть его разделы<br/>';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy`='$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="p_t">';
echo 'В блэк-листе никого нет';
echo '</div>';
echo '</tr>';
}

$q=mysql_query("SELECT * FROM `gruppy_bl` WHERE `id_gruppy`='$gruppy[id]' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($ank = mysql_fetch_assoc($q))
{
$us=get_user($ank['id_user']);
echo '<tr>';
if($num==1){
echo "<div class='p_t'>\n";
$num=0;
}else{
echo "<div class='p_t'>\n";
$num=1;}
//echo '<td class="icon14">';
echo '<img src="/style/themes/'.$set['set_them'].'/user/'.$us['pol'].'.png" alt="" /> ';
echo '<a href="/info.php?id='.$us['id'].'">'.$us['nick'].'</a> '.online($us['id']).' [<a href="?s='.$gruppy['id'].'&bl&del='.$us['id'].'">x</a>]';
echo '</div>';
echo '</div>';
echo '</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';
echo '<span class="ank_n">Дата добавления:</span> <span class="ank_d">'.vremja($ank['time']).'</span>';
echo '</td>';
echo '</tr>';
}
echo '</table>';
echo'<form method="get" action="?s='.$gruppy['id'].'&bl">';
echo'Введите ник или ID юзера:<br/>';
echo'<input type="text" name="uz"><br/>';
echo'<input type="submit" value="Добавить"></form>';
if ($k_page>1)str("?s=$gruppy[id]&bl&",$k_page,$page); // Вывод страниц
echo'<a href="?s='.$gruppy['id'].'&bl&del=all">Очистить список</a><br/>';
echo'<a href="?s='.$gruppy['id'].'">В админку</a><br/>';
}
elseif(isset($_GET['readmin']))
{
echo'<b>Передать права на Группу</b><br/>';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="msg">';
echo 'Передать группу некому';
echo '</div>';
echo '</tr>';
}
else
{
if(isset($_POST['new_admin']) && is_numeric($_POST['admin']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user` = '".intval($_POST['new_admin'])."' AND `activate`='0' AND `invit`='0' LIMIT 1"),0)==1)
{
$new_admin=intval($_POST['new_admin']);
mysql_query("UPDATE `gruppy` SET `admid`='$new_admin' WHERE `id`='$gruppy[id]' LIMIT 1");
mysql_query("DELETE FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='$new_admin' LIMIT 1");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$new_admin', '$user[nick] передал вам права на группу [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
msg('Группа успешно передана');
}
echo'<div class="menu">';
echo'<form method="post" action="?s='.$gruppy['id'].'&readmin">';
$q=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' ORDER BY `time` DESC");
while ($ank1 = mysql_fetch_assoc($q))
{
$ank2=get_user($ank1['id_user']);
echo'<input type="radio" name="new_admin" value="'.$ank2['id'].'"/> '.$ank2['nick'].'<br/>';
}
echo'<input type="submit" value="Передать">';
echo'</form></div>';
}
echo'<a href="?s='.$gruppy['id'].'">Назад</a><br/>';
}
elseif(isset($_GET['mod']))
{
if(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='".intval($_GET['del'])."' AND `mod`='1' LIMIT 1"),0)==1)
{
$del_mod=intval($_GET['del']);
mysql_query("UPDATE `gruppy_users` SET `mod`='0' WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='$del_mod' LIMIT 1");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$del_mod', 'Вас сняли с должности модератора в сообществе [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
msg('Модератор успешно снят с должности');
}
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `mod`='1' LIMIT 1"),0)>0)
{
echo'<b>Текущие модераторы</b><br/>';
$q2=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `mod`='1' ORDER BY `time` DESC LIMIT 3");
while ($moders = mysql_fetch_assoc($q2))
{
$ank_m=get_user($moders['id_user']);
echo'<a href="/info.php?id='.$ank_m['id'].'"><span style="color:'.$ank_m['color'].'">'.$ank_m['nick'].'</span></a> (<a href="?s='.$gruppy['id'].'&mod&del='.$ank_m['id'].'">снять</a>)<br/>';
}
}
echo'<b>Назначить модератора</b><br/>';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="p_t">';
echo 'Назначить некого';
echo '</div>';
echo '</tr>';
}
else
{
if(isset($_POST['moder']) && is_numeric($_POST['moder']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user` = '".intval($_POST['moder'])."' AND `activate`='0' AND `invit`='0' AND `mod`='0' LIMIT 1"),0)==1)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `mod`='1' LIMIT 1"),0)>=3)
{
echo'<div class="err">Нельзя назначить больше 3 модераторов</div>';
}
else
{
$moder=intval($_POST['moder']);
mysql_query("UPDATE `gruppy_users` SET `mod`='1' WHERE `id_gruppy`='$gruppy[id]' AND `id_user`='$moder' LIMIT 1");
mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values ('0', '$moder', 'Вас назначили модератором в сообществе [url=/gruppy/$gruppy[id]]$gruppy[name][/url]', '$time')");
msg('Модератор успешно назначен');
}
}
echo'<div class="p_m">';
echo'<form method="post" action="?s='.$gruppy['id'].'&mod">';
$q=mysql_query("SELECT * FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `activate`='0' AND `invit`='0' AND `mod`='0' ORDER BY `time` DESC");
while ($ank1 = mysql_fetch_assoc($q))
{
$ank2=get_user($ank1['id_user']);
echo'<input type="radio" name="moder" value="'.$ank2['id'].'"/> '.$ank2['nick'].'<br/>';
}
echo'<input type="submit" value="Назначить">';
echo'</form></div>';
}
echo'<a href="?s='.$gruppy['id'].'">Назад</a><br/>';
}
else
{
echo'<div class="nav1">';
echo'<img src="img/ank.png" alt=""/> <a href="?s='.$gruppy['id'].'&konf">Конфигурация</a><br/>';
echo'</div>';
echo'<div class="nav2">';
echo'<img src="img/1od.png" alt=""/> <a href="?s='.$gruppy['id'].'&logo">Логотип</a><br/>';
echo'</div>';
echo'<div class="nav1">';
$cusers = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `activate`='1'"),0);
echo'<img src="img/15od.png" alt=""/> <a href="?s='.$gruppy['id'].'&users">Ждущие активации</a> ('.$cusers.')<br/>';
echo'</div>';
echo'<div class="nav2">';
$blusers = mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_bl` WHERE `id_gruppy` = '$gruppy[id]'"),0);
echo'<img src="img/6od.png" alt=""/> <a href="?s='.$gruppy['id'].'&bl">Черный список</a> ('.$blusers.')<br/>';
echo'</div>';
echo'<div class="nav1">';
echo'<img src="img/14od.png" alt=""/> <a href="?s='.$gruppy['id'].'&mod">Модераторы</a><br/>';
echo'</div>';

}
echo'<div class="foot"><img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a></div>';
}
else
{
header("Location:index.php?s=$gruppy[id]");
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>
