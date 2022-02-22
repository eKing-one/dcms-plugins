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
$set['title']='Фотоконкурсы';
include_once '../sys/inc/thead.php';
title();
err();
aut();

 //////// сразу проверяем есть ли законченные конкурсы. Почему надо заходить сюда ? 
 //////// чтоб не нагружать каждый переход на сайт лишней проверкой. Все же так будет лучше))
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `do` < '$time' AND `end`='0' "), 0)>0){
$kon=mysql_fetch_assoc(mysql_query("SELECT `others`,`pob1`,`pob2`,`pob3`,`id`,`name` FROM `fotokr` where `do` < '$time' AND `end`='0' "));
$i=0;
//// победители
$q=mysql_query("SELECT `id_u`,`rating`,`id_kon` FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' ORDER by `rating` DESC LIMIT 3");
while ($post = mysql_fetch_assoc($q))
{$i++;
if ($post['id_u']!=NULL && $post['rating']>'0'){
if ($i=='1')mysql_query("INSERT INTO `fotokr_lider` (`id_u`, `time`,`id_kon`) values('$post[id_u]', '$time','$post[id_kon]')");
$ank=mysql_fetch_assoc(mysql_query("SELECT `balls` FROM `user` WHERE `id` = '".$post['id_u']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'Поздравляем !!! Вы заняли ".$i."-е место на фотоконкурсе ".$kon['name']." и получили ".$kon['pob'.$i]." баллов', '$time')");
mysql_query("UPDATE `user` SET `balls`='".($ank['balls']+$kon['pob'.$i.''])."' WHERE `id`='".$post['id_u']."'");}}
/////
//// поощрительные
if ($kon['others']!='0'){
$test = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'  "), 0);
$q=mysql_query("SELECT `id_u`,`rating` FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' ORDER by `rating` ASC LIMIT ".($test-3)." ");
while ($post = mysql_fetch_assoc($q))
{
if ($post['id_u']!=NULL && $post['rating']>'0'){
$ank=mysql_fetch_assoc(mysql_query("SELECT `balls` FROM `user` WHERE `id` = '".$post['id_u']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'К сожалению вы проиграли на фотоконкурсе ".$kon['name'].", но получили поощрительные ".$kon['others']." баллов', '$time')");
mysql_query("UPDATE `user` SET `balls`='".($ank['balls']+$kon['others'])."' WHERE `id`='".$post['id_u']."'");
}}}
/////
mysql_query("UPDATE `fotokr` SET `end`='1' WHERE `id`='".$kon['id']."'");
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id_kon` = '$kon[id]'");
}


switch ((isset($_GET['m'])) ? htmlspecialchars($_GET['m']) : null){
default:
if (isset($_SESSION['text'])){msg($_SESSION['text']);$_SESSION['text']=null;}
echo '<div class="menu_razd">Активные конкурсы</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `ot` <= '$time' and `do` > '$time' "), 0);
if ($k_post==0)echo '<div class="p_m">Активных конкурсов нет</div>';
$q = mysql_query("SELECT * FROM `fotokr` WHERE `ot` <= '$time' and  `do` > '$time' ORDER BY id DESC LIMIT 3");
while ($f = mysql_fetch_array($q))echo '<div class="p_m">&raquo; <a href="?m=info&amp;id='.$f['id'].' ">'.$f['name'].'</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$f[id]'"), 0).' чел.]</div>';
echo '<div class="menu_razd">Меню конкурсов</div>
'.($user['level']=='10'?'<div class="p_m"><img src="adm.png"> <a href="?m=add"><b>Добавить конкурс</b></a></div>':null).'
'.($user['level']>'0'?'<div class="p_m"><img src="end.png"> <a href="?m=whoz"><b>Заявки участников</b></a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users_a`"), 0).']</div>':null).'
<div class="p_m"><img src="p.png"> <a href="?m=active">Все активные конкурсы</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `ot` <= '$time' and `do` > '$time' "), 0).']</div>
<div class="p_m"><img src="k.png"> <a href="?m=end">Архив законченных</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `do` <= '$time'"), 0).']</div>
<div class="p_m"><img src="a.png"> <a href="?m=start">Ожидают начала</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `ot` > '$time' "), 0).'] </div>';
echo '<div class="menu_razd">Последние победители</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_lider`"), 0);
if ($k_post==0)echo '<div class="p_m">Победителей еще не было</div>';
$q = mysql_query("SELECT * FROM `fotokr_lider` ORDER BY id DESC LIMIT 3");
while ($f = mysql_fetch_array($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT `id`,`nick`,`pol` FROM `user` WHERE `id` = '".$f['id_u']."' LIMIT 1"));
echo '<div class="p_m"><img src="'.$ank['pol'].'.png"> <a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].'</a> ('.vremja($f['time']).')</div>';}
break;

case 'active':
echo '<div class="menu_razd">Активные конкурсы</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `ot` <= '$time' and `do` > '$time' "), 0);
if ($k_post==0)echo '<div class="p_m">Активных конкурсов нет</div>';
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `fotokr` WHERE `ot` <= '$time' and  `do` > '$time' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q))echo '<div class="p_m">&raquo; <a href="?m=info&amp;id='.$f['id'].' ">'.$f['name'].'</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$f[id]'"), 0).' чел.]</div>';
if ($k_page>1)str('?m=active&amp;',$k_page,$page);
echo '<div class="foot">&laquo; <a href="?">Конкурсы</a></div>';
break;

case 'end':
echo '<div class="menu_razd">Законченные конкурсы</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `do` <= '$time' "), 0);
if ($k_post==0)echo '<div class="p_m">Законченных конкурсов нет</div>';
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `fotokr` WHERE `do` <= '$time' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q))echo '<div class="p_m">&raquo; <a href="?m=info&amp;id='.$f['id'].' ">'.$f['name'].'</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$f[id]'"), 0).' чел.]</div>';
if ($k_page>1)str('?m=end&amp;',$k_page,$page);
echo '<div class="foot">&laquo; <a href="?">Конкурсы</a></div>';
break;

case 'start':
echo '<div class="menu_razd">Конкурсы ожидающие начала</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr` WHERE `ot` > '$time'  "), 0);
if ($k_post==0)echo '<div class="p_m">Конкурсов ожидающих начала нет</div>';
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `fotokr` WHERE `ot` > '$time'  ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q))echo '<div class="p_m">&raquo; <a href="?m=info&amp;id='.$f['id'].' ">'.$f['name'].'</a> ['.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$f[id]'"), 0).' чел.]</div>';
if ($k_page>1)str('?m=start&amp;',$k_page,$page);
echo '<div class="foot">&laquo; <a href="?">Конкурсы</a></div>';
break;

case 'whoz':
if ($user['level']==0)exit(header('location: ?'));
if (isset($_GET['yes'])){
$uc=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users_a` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if ($uc==NULL){exit(header('Location: ?'));}
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".$uc['id_kon']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$uc[id_u]', 'Вы успешно были приняты на фотоконкурс ".$kon['name'].". Удачи ;) ', '$time')");
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id` = '".intval($_GET['id'])."'");
mysql_query("INSERT INTO `fotokr_users` (`id_kon`, `id_u`, `id_foto`, `name_foto`, `time`) values ('".$uc['id_kon']."', '".$uc['id_u']."', '".$uc['id_foto']."', '".$uc['name_foto']."', '".$time."')");
msg('Участник успешно одобрен и уведомлен');
}

if (isset($_GET['no'])){
$uc=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users_a` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if ($uc==NULL){exit(header('Location: ?'));}
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".$uc['id_kon']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$uc[id_u]', 'К сожалению, Вы не прошли проверку на фотоконкурс ".$kon['name']."', '$time')");
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id` = '".intval($_GET['id'])."'");
unlink(H."fotokr/images/size100/$uc[id_foto].jpg");
unlink(H."fotokr/images/original/$uc[id_foto].jpg");
msg('Участник успешно удален и уведомлен');
}

echo '<div class="menu_razd">Заявки участников</div>';

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users_a`"), 0);
if ($k_post==0)echo '<div class="p_m">Нет заявок</div>';
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
echo '<table style="width:100%;">';
$q = mysql_query("SELECT * FROM `fotokr_users_a` ORDER BY id ASC LIMIT $start, $set[p_str]");
while ($f = mysql_fetch_array($q)){
$ank=mysql_fetch_assoc(mysql_query("SELECT `pol`,`id`,`nick` FROM `user` WHERE `id` = '".$f['id_u']."' LIMIT 1"));
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".$f['id_kon']."' LIMIT 1"));
echo '<td class="p_m"><table cellpadding="0" cellspacing="0"> <tr> <td style ="width:60px;"><img src="/fotokr/images/size100/'.$f['id_foto'].'.jpg" height="50" width="50">&nbsp;</td> <td> <b>Название:</b> '.$f['name_foto'].'<br/><b>Автор:</b> <a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].'</a><br/> <b>Время:</b> '.vremja($f['time']).'
<br/> <b>Заявка на конкурс:</b> '.$kon['name'].' <br/><a href="images/original/'.$f['id_foto'].'.jpg"><b>Открыть</b></a> | <a href="?m=whoz&amp;id='.$f['id'].'&amp;yes"><font color="green"><b>принять</b></font></a> | <a href="?m=whoz&amp;id='.$f['id'].'&amp;no"><font color="red"><b>отклонить</b></font></a>
</tr></td></table></td></tr>';
}
echo '</table>';
if ($k_page>1)str('?m=whoz&amp;',$k_page,$page);
echo '<div class="foot">&laquo; <a href="?">К конкурсам</a></div>';
break;

case 'add':
if ($user['level']!=10)exit(header('location: ?'));
if (isset($_POST['ok'], $_GET['ok'])){
$name=htmlspecialchars(mysql_real_escape_string($_POST['name']));
$msg=htmlspecialchars(mysql_real_escape_string($_POST['msg']));
if (!is_numeric($_POST['pob1']) OR !is_numeric($_POST['pob2']) OR !is_numeric($_POST['pob3']) OR !is_numeric($_POST['others']))$err[]='Ошибка в полях победителей';
if (strlen2($name)>25 OR strlen2($name)<4)$err[]='Ошибка количества символов в поле названия';
if (strlen2($msg)<10 OR strlen2($msg)>5000)$err[]='Ошибка количества символов в поле описания';
$timed=$time;
if ($_POST['do']=='min')$timed+=intval($_POST['dop'])*60;
elseif ($_POST['do']=='chas')$timed+=intval($_POST['dop'])*60*60;
elseif ($_POST['do']=='sut')$timed+=intval($_POST['dop'])*60*60*24;
if ($timed<$time)$err[]='Ошибка времени окончания';
$timep=$time;
if ($_POST['ot']=='min')$timep+=intval($_POST['otp'])*60;
elseif ($_POST['ot']=='chas')$timep+=intval($_POST['otp'])*60*60;
elseif ($_POST['ot']=='sut')$timep+=intval($_POST['otp'])*60*60*24;
if ($timep>$timed)$err[]='Ошибка времени начала';
err();
if (!isset($err)){
mysql_query("INSERT INTO `fotokr` (`name`, `msg`, `pob1`, `pob2`, `pob3`, `others`, `ot`, `do`, `time`,`votes`,`who`) values('$name', '$msg', '".intval($_POST['pob1'])."', '".intval($_POST['pob2'])."', '".intval($_POST['pob3'])."', '".intval($_POST['others'])."', '".$timep."', '".$timed."','".$time."','".intval($_POST['votes'])."','".intval($_POST['who'])."')");
msg('Конкурс успешно добавлен');
}
}
echo '<div class="menu_razd">Добавление конкурса</div>
<form method="post" action="?m=add&amp;ok">
Название конкурса<br/>
<input type="text" name="name"/><br/>
Описание конкурса<br/>
<textarea name="msg"></textarea><br/>
Начало через:<br/>
<input type="text" name="otp" value="0" size="3"/><select class="form" name="ot">
<option value="min">Минуты</option>
<option value="chas">Часы</option>
<option value="sut">Сутки</option>
</select><br />

Конец через:<br/>
<input type="text" name="dop" value="10" size="3"/><select class="form" name="do">
<option value="min">Минуты</option>
<option value="chas">Часы</option>
<option value="sut" selected="selected">Сутки</option>
</select><br />

Голосование:<br/>
<select class="form" name="votes">
<option value="0" selected="selected">Разрешено</option>
<option value="1">Запрещено</option>
</select><br />

Могут участвовать:<br/>
<select class="form" name="who">
<option value="2" selected="selected">Все</option>
<option value="1">Только парни</option>
<option value="0">Только девушки</option>
</select><br />

<div class="menu_razd">Победители</div>
1 место - <input type="text" value="300" name="pob1" size="5"/><br/>
2 место - <input type="text" value="200" name="pob2" size="5"/><br/>
3 место - <input type="text" value="100" name="pob3" size="5"/><br/>
* Поощрительные баллы - <input type="text" value="0" name="others" size="5"/><br/>
<b>** Поощрительные баллы - выдаются тем, кто проиграл. Если не хотите выдавать, просто оставьте 0.</b><br/>
<input type="submit" name="ok" value="Добавить конкурс" /></form>
<div class="foot">&laquo; <a href="?">К конкурсам</a></div>
';
break;

case 'info':
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if ($kon==NULL){exit(header('Location: ?'));}

if (isset($user, $_GET['Iam'], $_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users_a` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]' "), 0)!=0){
$uc=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users_a` WHERE `id_kon` = '".$kon['id']."' AND `id_u` = '".$user['id']."' "));
unlink(H."fotokr/images/size100/$uc[id_foto].jpg");
unlink(H."fotokr/images/original/$uc[id_foto].jpg");
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id_kon` = '".$kon['id']."' AND `id_u` = '".$user['id']."'");
msg('Заявка удалена');
}

if (isset($user, $_GET['Iam']) && ($kon['who']==$user['pol'] || $kon['who']=='2') && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]' "), 0)==0 && $kon['ot']<=$time && $kon['do']>$time)
{
if (isset($_GET['ok']) && isset($_FILES['file']))
{
if ($imgc=@imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'])))
{
$name=stripcslashes(htmlspecialchars($_POST['name_foto']));
if (strlen2($name)>30)$err='Название не должно превышать 30 символов';
if (strlen2($name)<2)$err='Название не должно содержать менее 2 символов';

$img_x=imagesx($imgc);
$img_y=imagesy($imgc);
$rand=rand(10,100);
if (!isset($err)){
if ($user['level']>'0')mysql_query("INSERT INTO `fotokr_users` (`id_kon`, `id_u`, `id_foto`, `name_foto`, `time`) values ('".$kon['id']."', '".$user['id']."', '".$kon['id']."_".$user['id']."_".$rand."', '".$name."', '".$time."')");
else mysql_query("INSERT INTO `fotokr_users_a` (`id_kon`, `id_u`, `id_foto`, `name_foto`, `time`) values ('".$kon['id']."', '".$user['id']."', '".$kon['id']."_".$user['id']."_".$rand."', '".$name."', '".$time."')");
$id_foto=mysql_insert_id();
if ($img_x==$img_y){$dstW=100; $dstH=100;}
elseif ($img_x>$img_y){$prop=$img_x/$img_y;$dstW=100;$dstH=ceil($dstW/$prop);}
else{$prop=$img_y/$img_x;$dstH=100;$dstW=ceil($dstH/$prop);}
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
$screen=img_copyright($screen);
imagejpeg($screen,H."fotokr/images/size100/".$kon['id']."_".$user['id']."_".$rand.".jpg",90);
@chmod(H."fotokr/images/size100/".$kon['id']."_".$user['id']."_".$rand.".jpg",0777);
imagedestroy($screen);
$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
$screen=img_copyright($screen);
imagedestroy($screen);
$imgc=img_copyright($imgc); 
imagejpeg($imgc,H."fotokr/images/original/".$kon['id']."_".$user['id']."_".$rand.".jpg",90);
@chmod(H."fotokr/images/original/".$kon['id']."_".$user['id']."_".$rand.".jpg",0777);
if ($user['level']>'0')$_SESSION['text']='Вы успешно приняли участие в конкурсе';
else $_SESSION['text']='Ваша фотография отправлена на модерацию';
exit(header('Location: ?m=info&id='.$kon['id'].''));
}
else{$imgc=img_copyright($imgc);imagejpeg($imgc,H."fotokr/images/original/".$kon['id']."_".$user['id']."_".$rand.".jpg",90);@chmod(H."fotokr/images/original/".$kon['id']."_".$user['id']."_".$rand.".jpg",0777);}
imagedestroy($imgc);
}else $err='Выбранный Вами формат изображения не поддерживается';

}
err();

echo '<form enctype="multipart/form-data" action="?m=info&amp;id='.$kon['id'].'&amp;Iam&amp;ok" method="post">
<div class="p_m"><center><b>Обязательно указывайте название фотографии</b></center></div>
'.(mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users_a` WHERE `id_u`='$user[id]' AND `id_kon` = '$kon[id]'"), 0)!=0?'<div class="p_m"><b><font color="red">Внимание!!!</font> Вы подали заявку, но она еще не рассмотрена.</b> [<a href="?m=info&amp;id='.$kon['id'].'&amp;Iam&amp;delete">удалить заявку</a>]</div>':null).'
Название:<br /><input name="name_foto" type="text"><br />
Фото:<br /><input name="file" type="file" accept="image/*,image/jpeg" /><br />
<input class="submit" type="submit" value="Выгрузить" /></form>';
echo '</div>
<div class="foot">&laquo; <a href="?m=info&amp;id='.$kon['id'].'">Вернуться</a></div>';

include_once '../sys/inc/tfoot.php';
exit;
}


if (isset($_GET['stop']) && $kon['ot']<=$time && $kon['do']>$time)
{

if ($_GET['stop']=='ok'){
$i=0;

//// победители
$q=mysql_query("SELECT `id_u`,`rating`,`id_kon` FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' ORDER by `rating` DESC LIMIT 3");
while ($post = mysql_fetch_assoc($q))
{$i++;
if ($post['id_u']!=NULL && $post['rating']>'0'){
if ($i=='1')mysql_query("INSERT INTO `fotokr_lider` (`id_u`, `time`,`id_kon`) values('$post[id_u]', '$time','$post[id_kon]')");
$ank=mysql_fetch_assoc(mysql_query("SELECT `balls` FROM `user` WHERE `id` = '".$post['id_u']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'Поздравляем !!! Вы заняли ".$i."-е место на фотоконкурсе ".$kon['name']." и получили ".$kon['pob'.$i]." баллов', '$time')");
mysql_query("UPDATE `user` SET `balls`='".($ank['balls']+$kon['pob'.$i.''])."' WHERE `id`='".$post['id_u']."'");}}
/////


//// поощрительные
if ($kon['others']!='0'){
$test = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'  "), 0);
$q=mysql_query("SELECT `id_u`,`rating` FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' ORDER by `rating` ASC LIMIT ".($test-3)." ");
while ($post = mysql_fetch_assoc($q))
{
if ($post['id_u']!=NULL && $post['rating']>'0'){
$ank=mysql_fetch_assoc(mysql_query("SELECT `balls` FROM `user` WHERE `id` = '".$post['id_u']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'К сожалению вы проиграле на фотоконкурсе ".$kon['name'].", но получили поощрительные ".$kon['others']." баллов', '$time')");
mysql_query("UPDATE `user` SET `balls`='".($ank['balls']+$kon['others'])."' WHERE `id`='".$post['id_u']."'");
}}}
/////
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id_kon` = '$kon[id]'");
mysql_query("UPDATE `fotokr` SET `do`='".$time."', `end`='1' WHERE `id`='".$kon['id']."'");
if ($kon['ot']>=$time)mysql_query("UPDATE `fotokr` SET `ot`='".$time."' WHERE `id`='".$kon['id']."'");
$_SESSION['text']='Конкурс завершен досрочно';
exit(header('Location: ?m=info&id='.$kon['id'].''));
}
echo '<div class="p_m"><center><font color="red">Вы действительно хотите закончить конкурс  '.$kon['name'].' досрочно ?</font><br/>
<a href="?m=info&amp;id='.$kon['id'].'&amp;stop=ok">Да</a> | <a href="?m=info&amp;id='.$kon['id'].'">Нет</a></center></center></div>
<div class="foot">&laquo; <a href="?m=info&amp;id='.$kon['id'].'">Вернуться</a></div>
<div class="foot">&laquo; <a href="/?">На главную</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}
if (isset($_SESSION['text'])){msg($_SESSION['text']);$_SESSION['text']=null;}

if (isset($_GET['who']))
{
if (isset($_GET['alld']) && $_GET['alld']=='yes')
{
$q=mysql_query("SELECT * FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'");
while ($post = mysql_fetch_assoc($q))
{
$uu=mysql_fetch_assoc(mysql_query("SELECT `id_u`,`id_foto` FROM `fotokr_users` WHERE `id_kon`='".$post['id_kon']."' LIMIT 1"));
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'К сожалению, все были сняты с конкурса ".$kon['name']." администрацией сайта', '$time')");
mysql_query("DELETE FROM `fotokr_users` WHERE `id_kon` = '$post[id_kon]'");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_u`='$post[id_u]'");
mysql_query("DELETE FROM `fotokr_komm` WHERE `id_kon`='$post[id_kon]'");
unlink(H."fotokr/images/size100/$uu[id_foto].jpg");
unlink(H."fotokr/images/original/$uu[id_foto].jpg");
}
msg('Все участники успешно отчислены от конкурса');
}

if (isset($_GET['allr']) && $_GET['allr']=='yes')
{
$q=mysql_query("SELECT * FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'");
while ($post = mysql_fetch_assoc($q))
{
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$post[id_u]', 'Рейтинг Всех участников конкурса ".$kon['name']." был обнулен администрацией сайта', '$time')");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_u`='$post[id_u]'");
mysql_query("UPDATE `fotokr_users` SET `rating`='0' WHERE `id_kon`='".$kon['id']."' ");
}
msg('Рейтинг всех участников обнулен');
}

echo '<div class="menu_razd">Участники конкурса '.$kon['name'].'</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'  "), 0);
if ($k_post==0)echo '<div class="p_m">Никого нет</div>';
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
$q = mysql_query("SELECT * FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' ORDER BY rating DESC LIMIT $start, $set[p_str]");
echo '<table style="width:100%;">';
while ($f = mysql_fetch_array($q))
{
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$f['id_u']."' LIMIT 1"));
echo '<td class="p_m"><table cellpadding="0" cellspacing="0"> <tr> <td style ="width:60px;">'.($f['block']=='0'?'<img src="/fotokr/images/size100/'.$f['id_foto'].'.jpg" height="50" width="50">':'<img src="/fotokr/images/block.png" height="50" width="50">').'&nbsp;</td><td> '.($f['block']=='0'?'<img src="ico.png"> <a href="?m=show&amp;id='.$f['id'].'">'.$f['name_foto'].'</a>':'<font color="red">[заблокирована]</font> '.($user['level']==10?'[<a href="?m=show&amp;id='.$f['id'].'">инфо</a>]':NULL).' ').'<br/><img src="'.$ank['pol'].'.png"> <b>Автор:</b> <a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].'</a><br/><img src="golos.png"> <b>Рейтинг:</b> '.$f['rating'].'</tr></td></table></td></tr>';
}
if ($k_page>1)str('?m=info&amp;id='.$kon['id'].'&amp;who&amp;',$k_page,$page);
echo '</table>'.($user['level']==10?'<div class="p_m">&raquo; <a href="?m=info&amp;id='.$kon['id'].'&amp;who&amp;alld"><b>Снять всех с конкурса</b></a>
'.(isset($_GET['alld']) && $_GET['alld']!='yes'?'<center><b>Вы уверены, что хотите снять всех с этого конкурса ?</b><br> <a href="?m=info&amp;id='.$kon['id'].'&amp;who&amp;alld=yes">Да</a> | <a href="?m=info&amp;id='.$kon['id'].'&amp;who">Нет</a></center>':null).'
</div>
<div class="p_m">&raquo; <a href="?m=info&amp;id='.$kon['id'].'&amp;who&amp;allr"><b>Обнулить у всех рейтинг</b></a>
'.(isset($_GET['allr']) && $_GET['allr']!='yes'?'<center><b>Вы уверены, что хотите обнулить рейтинг у всех участников конкурса ?</b><br> <a href="?m=info&amp;id='.$kon['id'].'&amp;who&amp;allr=yes">Да</a> | <a href="?m=info&amp;id='.$kon['id'].'&amp;who">Нет</a></center>':null).'
</div>':null).'
<div class="foot">&laquo; <a href="?">Конкурсы</a> &laquo; <a href="?m=info&amp;id='.$kon['id'].'">'.$kon['name'].'</a></div>
<div class="foot">&laquo; <a href="/?">На главную</a></div>';

include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($user, $_GET['not'], $_GET['ok']) && $kon['ot']<=$time && $kon['do']>$time && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]' "), 0)!=0)
{
$uu=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users` WHERE `id_u` = '".$user['id']."' and `id_kon`='".$kon['id']."' LIMIT 1"));
mysql_query("DELETE FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]'");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_foto`='$uu[id]'");
mysql_query("DELETE FROM `fotokr_komm` WHERE `id_foto`='$uu[id]'");
unlink(H."fotokr/images/size100/$uu[id_foto].jpg");
unlink(H."fotokr/images/original/$uu[id_foto].jpg");
$_SESSION['text']='Вы успешно вышли с конкурса';
exit(header('Location: ?m=info&id='.$kon['id'].''));
}

if (isset($_GET['delete'], $_GET['ok']) && $user['level']=='10')
{
$q=mysql_query("SELECT * FROM `fotokr_users` WHERE `id_kon`='".$kon['id']."'");
while ($f = mysql_fetch_array($q)){
unlink(H."fotokr/images/size100/$f[id_foto].jpg");
unlink(H."fotokr/images/original/$f[id_foto].jpg");
mysql_query("DELETE FROM `fotokr_users` WHERE `id_u` = '$f[id_u]'");
mysql_query("DELETE FROM `fotokr_users_a` WHERE `id_u` = '$f[id_u]'");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_foto`='$f[id]'");
mysql_query("DELETE FROM `fotokr_komm` WHERE `id_kon`='$kon[id]'");
}
mysql_query("DELETE FROM `fotokr` WHERE `id` = '$kon[id]'");
$_SESSION['text']='Конкурс успешно удален';
exit(header('Location: ?'));
}

echo '<div class="p_m">';
if ($kon['ot']<=$time && $kon['do']>$time)echo '<center><b><font color="green">Конкурс активен</font></b></center>';
else if ($kon['ot']>$time)echo '<center><b><font color="blue">Конкурс еще не начался</font></b></center>';
else if ($kon['do']<$time)echo '<center><b><font color="red">Конкурс закончился</font></b></center>';
echo '</div>';
$arr=array('девушки','парни','все');
echo '<div class="p_t">'.$kon['name'].'</div>
<div class="p_m"><b>Описание</b>: '.output_text($kon['msg']).'</div>
<div class="p_m"><b>Победители получат</b>:<br> 
<b>1 место</b> - '.($kon['pob1']).' баллов <br/>
<b>2 место</b> - '.($kon['pob2']).' баллов <br/>
<b>3 место</b> - '.($kon['pob3']).' баллов </div>
<div class="p_m"><b>Начало конкурса:</b>  '.vremja($kon['ot']).'</div>
<div class="p_m"><b>Окончание конкурса:</b>  '.vremja($kon['do']).' '.($user['level']==10 && $kon['ot']<=$time && $kon['do']>$time?'[<a href="?m=info&amp;id='.$kon['id'].'&amp;stop"><font color="red"><b>X</b> STOP</font></a>]':null).'</div>
<div class="p_m"><b>Голосование:</b>  '.($kon['votes']=='0'?"открыто":"закрыто").'</div>
<div class="p_m"><b>Участие принимают:</b>  '.$arr[$kon['who']].' </div>

<div class="p_m"><b>Участников:</b>  '.mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]'"), 0).' чел. [<a href="?m=info&amp;id='.$kon['id'].'&amp;who">открыть</a>]</div>
'.(isset($user) && ($kon['who']==$user['pol'] || $kon['who']=='2') && $kon['ot']<=$time && $kon['do']>$time && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]' "), 0)==0?'<div class="p_m"> <img src="yes.png"> <a href="?m=info&amp;id='.$kon['id'].'&amp;Iam"><b>Принять участие</b></a></div>':null);

if (isset($user) && $kon['ot']<=$time && $kon['do']>$time && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$user[id]' "), 0)!=0){
$uu=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users` WHERE `id_u` = '".$user['id']."' and `id_kon`='".$kon['id']."' LIMIT 1"));
echo '<div class="p_m"> <img src="yes.png"> <b>Вы участвуете!</b> [<a href="?m=show&amp;id='.$uu['id'].'"><b>Я</b></a>] [<a href="?m=info&amp;id='.$kon['id'].'&amp;not">выйти</a>] '.(isset($_GET['not'])?'<center><b>Вы уверены, что хотите выйти с этого конкурса ?</b><br> <a href="?m=info&amp;id='.$kon['id'].'&amp;not&amp;ok">Да</a> | <a href="?m=info&amp;id='.$kon['id'].'">Нет</a></center>':null).'</div>';
}

if ($user['level']=='10')echo '<div class="p_m"> <img src="adm.png"> <a href="?m=edit&amp;id='.$kon['id'].'"><b>Редактировать конкурс</b></a></div>
<div class="p_m"> <img src="end.png"> <a href="?m=info&amp;id='.$kon['id'].'&amp;delete"><b>Удалить конкурс</b></a> '.(isset($_GET['delete'])?'<center><b>Вы уверены, что хотите удалить конкурс '.$kon['name'].' ?</b><br> <a href="?m=info&amp;id='.$kon['id'].'&amp;delete&amp;ok">Да</a> | <a href="?m=info&amp;id='.$kon['id'].'">Нет</a></center>':null).' </div>';
echo '<div class="foot">&laquo; <a href="?">Конкурсы</a></div>';

break;


case 'edit':
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if ($kon==NULL){exit(header('Location: ?'));}
if ($user['level']!='10'){exit(header('Location: ?'));}

if (isset($_POST['ok']))
{
$name=htmlspecialchars(mysql_real_escape_string($_POST['name']));
$msg=htmlspecialchars(mysql_real_escape_string($_POST['msg']));
if (!is_numeric($_POST['pob1']) OR !is_numeric($_POST['pob2']) OR !is_numeric($_POST['pob3']) OR !is_numeric($_POST['others']))$err[]='Ошибка в полях победителей';
if (strlen2($name)>25 OR strlen2($name)<4)$err[]='Ошибка количества символов в поле названия';
if (strlen2($msg)<10 OR strlen2($msg)>5000)$err[]='Ошибка количества символов в поле описания';
err();
if (!isset($err)){
mysql_query("UPDATE `fotokr` SET `name`='".$name."', `msg`='".$msg."', `pob1`='".intval($_POST['pob1'])."', `pob2`='".intval($_POST['pob2'])."', `pob3`='".intval($_POST['pob3'])."', `others`='".intval($_POST['others'])."', `votes`='".intval($_POST['votes'])."', `who`='".intval($_POST['who'])."'   WHERE `id`='".$kon['id']."'");
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
msg('Изменения успешно приняты');
}}

echo '<div class="menu_razd">Редактирование конкурса '.$kon['name'].'</div>';
echo '<form method="post" action="?m=edit&amp;id='.$kon['id'].'&amp;ok">
Название конкурса<br/>
<input type="text" name="name" value="'.$kon['name'].'" /><br/>
Описание конкурса<br/>
<textarea name="msg">'.$kon['msg'].'</textarea><br/>
Голосование:<br/>
<select class="form" name="votes">
<option value="0" '.($kon['votes']==0?' selected="selected"':null).'>Разрешено</option>
<option value="1" '.($kon['votes']==1?' selected="selected"':null).'>Запрещено</option>
</select><br />
Могут участвовать:<br/>
<select class="form" name="who">
<option value="2" '.($kon['who']==2?' selected="selected"':null).'>Все</option>
<option value="1" '.($kon['who']==1?' selected="selected"':null).'>Только парни</option>
<option value="0" '.($kon['who']==0?' selected="selected"':null).'>Только девушки</option>
</select><br />
<div class="menu_razd">Победители</div>
1 место - <input type="text" value="'.$kon['pob1'].'" name="pob1" size="5"/><br/>
2 место - <input type="text" value="'.$kon['pob2'].'" name="pob2" size="5"/><br/>
3 место - <input type="text" value="'.$kon['pob3'].'" name="pob3" size="5"/><br/>
* Поощрительные баллы - <input type="text" value="'.$kon['others'].'" name="others" size="5"/><br/>
<b>** Поощрительные баллы - выдаются тем, кто проиграл. Если не хотите выдавать, просто оставьте 0.</b><br/>
<input type="submit" name="ok" value="Изменить конкурс" /></form>
<div class="foot">&laquo; <a href="?">Конкурсы</a> &laquo; <a href="?m=info&amp;id='.$kon['id'].'">'.$kon['name'].'</a></div>';
break;

case 'show':
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
if ($foto==NULL){exit(header('Location: ?'));}
$kon=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr` WHERE `id` = '".$foto['id_kon']."' LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '".$foto['id_u']."' LIMIT 1"));

if (isset($user))mysql_query("UPDATE `notification` SET `read` = '1' WHERE `type` = 'fotokr' AND `id_user` = '$user[id]'");


if ($user['level']!='10' && $foto['block']=='1')
{
echo '<div class="p_m"><center><font color="red">Фотография заблокирована администрацией сайта</font></center></div>
<div class="foot">&laquo; <a href="?m=info&amp;id='.$kon['id'].'&amp;who">К участникам</a></div>
<div class="foot">&laquo; <a href="/?">На главную</a></div>';
include_once '../sys/inc/tfoot.php';
}

if (isset($_GET['who'])){
echo '<div class="menu_razd">Проголосовали за фотографию '.$foto['name_foto'].'</div>';
$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_rating` WHERE `id_foto` = '".$foto['id']."' "),0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];
if($k_post==0)echo '<div class="p_m">Никто не голосовал</div>';
$q=mysql_query("SELECT * FROM `fotokr_rating` WHERE `id_foto` = '".$foto['id']."'  LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$tt=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$post['id_u']."' limit 1"));
echo '<div class="p_m"><a href="/info.php?id='.$tt['id'].'"><b>'.$tt['nick'].'</a>:</b> +'.$post['rating'].'</a></div>';}
if ($k_page>1)str('?m=show&amp;id='.$foto['id'].'&amp;who&amp;',$k_page,$page);
echo'</div><div class="foot">&laquo; <a href="?m=show&amp;id='.$foto['id'].'">Вернуться</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}

if (isset($_GET['reply'])) {
$reply=(int)$_GET['reply'];
$an=mysql_fetch_assoc(mysql_query("select `id`,`nick` from `user` where `id`='".$reply."' limit 1"));
$ts=mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_komm` WHERE `id_u` = '$an[id]' AND `id_foto`='$foto[id]' LIMIT 1"),0);
if ($an['id']==0 || $an['id']==null || $an['id']==$user['id'] || $ts==0 || !isset($user)){header('Location: /?');exit;}
include_once '../sys/inc/thead.php';
if (isset($_POST['msg'])) {
$msg=my_esc($_POST['msg']);
if (strlen2($msg)<2 OR strlen2($msg)>1000)$err='Ошибка! Слишком длинное или короткое сообщение!';
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_komm` WHERE `id_u` = '$user[id]' AND `id_foto`='$foto[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
err();
if (!isset($err))
{
$notifiacation=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$foto['id_u']."' LIMIT 1"));
$notifiacationn=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$an['id']."' LIMIT 1"));
if ($an['id']!=$foto['id_u'] && $notifiacationn['komm'] == 1)mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `type`, `time`,`msg`) values('$user[id]', '$an[id]', 'fotokr', '$time', '$user[nick] ответил вам в комментариях к фотографии [url=/fotokr/?m=show&id=".$foto['id']."&page=end]".$foto['name_foto']."[/url] на конкурсе $kon[name]')");
if ($user['id']!=$foto['id_u'] && $notifiacation['komm'] == 1)mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `type`, `time`,`msg`) VALUES ('$user[id]', '$ank[id]', 'fotokr', '$time', '$user[nick] оставил комментарий к вашей фотографии [url=/fotokr/?m=show&id=".$foto['id']."&page=end]".$foto['name_foto']."[/url] на конкурсе $kon[name]')");
mysql_query("INSERT INTO `fotokr_komm` (`id_u`, `time`, `msg`, `id_foto`, `otv`, `id_kon`) values('$user[id]', '$time', '$msg', '$foto[id]', '$reply', '$kon[id]')");
header('Location: ?m=show&id='.$foto['id'].'&page=end&'.$passgen.'');exit;
}}
echo '<form method="post" name="message" action="?m=show&amp;id='.$foto['id'].'&amp;reply='.$an['id'].'&amp;'.$passgen.'">';
echo 'Ответ: '.$an['nick'].'<br>Сообщение:<br /><textarea name="msg"></textarea><br />
<input name="post" value="Отправить" type="submit"><br /></form>
<div class="foot">&laquo; <a href="?m=show&amp;id='.$foto['id'].'">Вернуться</a></div>';
include_once '../sys/inc/tfoot.php';
}

if (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_komm` WHERE `id` = '".intval($_GET['del'])."'"),0)!=0 && ($user['level']>3 || $user['id'] == $foto['id_u'])){
mysql_query("DELETE FROM `fotokr_komm` WHERE `id`='".intval($_GET['del'])."'");
msg('Сообщение успешно удалено');}

if (isset($_POST['msg']) && isset($user)){
$msg=$_POST['msg'];
if (strlen2($msg)<2 OR strlen2($msg)>1000)$err='Ошибка! Слишком длинное или короткое сообщение!';
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_komm` WHERE `id_foto` = '$foto[id]' AND `id_u` = '$user[id]' AND `msg` = '".mysql_escape_string($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

$notifiacation=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$ank['id']."' LIMIT 1"));
if ($notifiacation['komm'] == 1 && $ank['id'] != $user['id'])
mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `type`, `time`,`msg`) VALUES ('$user[id]', '$ank[id]', 'fotokr', '$time', '$user[nick] оставил комментарий к вашей фотографии [url=/fotokr/?m=show&id=".$foto['id']."&page=end]".$foto['name_foto']."[/url] на конкурсе $kon[name]')");

mysql_query("INSERT INTO `fotokr_komm` (`id_foto`, `id_u`, `time`, `msg`, `id_kon`) values('$foto[id]', '$user[id]', '$time', '".my_esc($msg)."', '".$kon['id']."')");
msg('Сообщение успешно добавлено');}}

if (isset($user, $_GET['golos']) && $kon['votes']=='0' && $_GET['golos']<=5 && $_GET['golos']>0 && $ank['id'] != $user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_rating` WHERE `id_u` = '$user[id]' AND `id_foto` = '$foto[id]'"), 0)==0 && $kon['ot'] <= $time && $kon['do'] > $time)
{
mysql_query("INSERT INTO `fotokr_rating` (`id_u`, `id_foto`, `rating`) values('$user[id]', '$foto[id]', '".intval($_GET['golos'])."')");
mysql_query("UPDATE `fotokr_users` SET `rating`='".($foto['rating']+intval($_GET['golos']))."' WHERE `id`='".$foto['id']."' ");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$foto[id_u]', '".$user['nick']." проголосовал за вашу [url=/fotokr/?m=show&id=".$foto['id']."]фотографию[/url] (+".intval($_GET['golos']).") на конкурсе ".$kon['name']."', '$time')");
msg('Ваш голос успешно принят');
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
}

if (isset($_GET['act']) && $user['level']==10)
{
if ($_GET['act']=='block' && $foto['block']=='0'){
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$foto[id_u]', 'Ваша фотография [url=/fotokr/?m=show&id=".$foto['id']."]".$foto['name_foto']."[/url] была заблокирована администрацией', '$time')");
mysql_query("UPDATE `fotokr_users` SET `block`='1' WHERE `id`='".$foto['id']."' ");
msg('Фотография заблокирована');
}
elseif ($_GET['act']=='block' && $foto['block']=='1'){
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$foto[id_u]', 'Ваша фотография [url=/fotokr/?m=show&id=".$foto['id']."]".$foto['name_foto']."[/url] была разблокирована администрацией', '$time')");
mysql_query("UPDATE `fotokr_users` SET `block`='0' WHERE `id`='".$foto['id']."' ");
msg('Фотография разблокирована');
}
elseif ($_GET['act']=='del'){
mysql_query("DELETE FROM `fotokr_users` WHERE `id_kon` = '$kon[id]' and `id_u`='$foto[id_u]'");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_foto`='$foto[id]'");
mysql_query("DELETE FROM `fotokr_komm` WHERE `id_foto`='$foto[id]'");
unlink(H."fotokr/images/size100/$foto[id_foto].jpg");
unlink(H."fotokr/images/original/$foto[id_foto].jpg");
exit(header('Location: ?m=info&id='.$kon['id'].'&who'));
}
elseif ($_GET['act']=='null'){
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$foto[id_u]', 'Рейтинг вашей фотографии [url=/fotokr/?m=show&id=".$foto['id']."]".$foto['name_foto']."[/url] был обнулен', '$time')");
mysql_query("DELETE FROM `fotokr_rating` WHERE `id_foto`='$foto[id_u]'");
mysql_query("UPDATE `fotokr_users` SET `rating`='0' WHERE `id_u`='".$foto['id_u']."' AND `id_kon`='".$kon['id']."' ");
msg('Рейтинг успешно обнулен');
}
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `fotokr_users` WHERE `id` = '".intval($_GET['id'])."' LIMIT 1"));
}


echo '<div class="p_t">'.$foto['name_foto'].'</div><center><img src="images/size100/'.$foto['id_foto'].'.jpg"></center>
<div class="p_m">
<img src="ico.png"> <a href="images/original/'.$foto['id_foto'].'.jpg">Скачать оригинал</a> ['.size_file(filesize("images/original/".$foto['id_foto'].".jpg")).']<br/>
<img src="time.png"> <b>Время:</b> '.vremja($foto['time']).'<br/>
<img src="'.$ank['pol'].'.png"> <b>Автор:</b> <a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].'</a><br/>
<img src="golos.png"> <b>Рейтинг:</b> '.$foto['rating'].' '.(mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_rating` WHERE `id_u` = '$user[id]' AND `id_foto` = '$foto[id]'"), 0)!=0?'[<b>Вы:</b> +'.mysql_fetch_object(mysql_query("SELECT `rating` FROM `fotokr_rating` WHERE `id_u` = '$user[id]' AND `id_foto` = '$foto[id]'"))->rating.']':null).' [<a href="?m=show&amp;id='.$foto['id'].'&amp;who">подр.</a>]
'.($user['level']==10?'<br/><img src="end.png"> <b>Админка:</b> <a href="?m=show&amp;id='.$foto['id'].'&amp;act=block">'.($foto['block']=='0'?'блок':'разблок').'</a> | <a href="?m=show&amp;id='.$foto['id'].'&amp;act=del">удал.</a> | <a href="?m=show&amp;id='.$foto['id'].'&amp;act=null">обнулить</a>':null).'
</div>
'.((isset($user) && $ank['id'] != $user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_rating` WHERE `id_u` = '$user[id]' AND `id_foto` = '$foto[id]'"), 0)==0) && $kon['ot'] <= $time && $kon['do'] > $time?'<div class="p_m"><img src="golos.png"> 
'.($kon['votes']=='0'?'<b>Проголосовать:</b> 
<a href="?m=show&amp;id='.$foto['id'].'&amp;golos=1">+1</a> | 
<a href="?m=show&amp;id='.$foto['id'].'&amp;golos=2">+2</a> | 
<a href="?m=show&amp;id='.$foto['id'].'&amp;golos=3">+3</a> | 
<a href="?m=show&amp;id='.$foto['id'].'&amp;golos=4">+4</a> | 
<a href="?m=show&amp;id='.$foto['id'].'&amp;golos=5">+5</a>':'<font color="red">Голосование закрыто</font>').' </div> ':null);

echo '<div class="menu_razd">Комментарии</div>';
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `fotokr_komm` WHERE `id_foto` = '$foto[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo '<div class="p_m">Нет комментариев</div>';
$q=mysql_query("SELECT * FROM `fotokr_komm` WHERE `id_foto` = '$foto[id]' ORDER BY `id` ASC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{
$ank2=mysql_fetch_assoc(mysql_query("select `id`,`nick`,`pol` from `user` where `id`='".$post['id_u']."' limit 1"));
$otv=mysql_fetch_assoc(mysql_query("select `nick` from `user` where `id`='".$post['otv']."' limit 1"));
echo '<div class="p_m"><img src="'.$ank2['pol'].'.png"> 
<a href="/info.php?id='.$ank2['id'].'">'.$ank2['nick'].'</a> '.online($ank2['id']).' ('.vremja($post['time']).')<br />
'.($post['otv']!=0?'<small>Ответ <b>'.$otv['nick'].'</b>:</small><br />':null).' '.output_text($post['msg']).' <br/>
'.($ank2['id']!=$user['id']?'[<a href="?m=show&amp;id='.$foto['id'].'&amp;reply='.$ank2['id'].'">Ответить</a>]':null).' 
 '.($user['level']>3 || $user['id'] == $foto['id_u']?'[<a href="?m=show&amp;id='.$foto['id'].'&amp;del='.$post['id'].'">Удалить</a>]':null).'
';
echo '</div>';
}
if ($k_page>1)str('?m=show&amp;id='.$foto['id'].'&amp;',$k_page,$page);
if (isset($user)){
echo '<form method="post" name="message" action="?m=show&id='.$foto['id'].'">';
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else echo 'Сообщение:<br /><textarea name="msg"></textarea><br />';
echo '<input value="Отправить" type="submit" /></form>';
}
echo '<div class="foot">&laquo; <a href="?">Конкурсы</a> &laquo; <a href="?m=info&amp;id='.$kon['id'].'">'.$kon['name'].'</a> &laquo; <a href="?m=info&amp;id='.$kon['id'].'&amp;who">Участники</a></div>';
break;

}
echo '<div class="foot">&laquo; <a href="/?">На главную</a></div>';

include_once '../sys/inc/tfoot.php';
?>