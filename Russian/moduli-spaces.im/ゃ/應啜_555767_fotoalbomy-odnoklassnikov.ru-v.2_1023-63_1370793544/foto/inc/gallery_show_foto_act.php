<?
//--------------------отмеченные---------------------//
if (isset($user) && $user['id']==$ank['id'])
{
if (isset($_POST['delete']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^post_([0-9]*)$#',$key,$postnum) && $value='1')
{
$delpost[]=$postnum[1];
}
}


if (isset($delpost) && is_array($delpost))
{
echo "<div class='mess'>Друзья: ";

for ($q=0; $q<=count($delpost)-1; $q++) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$delpost[$q]') OR (`user` = '$delpost[$q]' AND `frend` = '$user[id]') LIMIT 1"),0)==0)
$warn[]='Этого пользователя нет в вашем списке друзей';
else
{
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_frend` WHERE `id_avtor` = '$user[id]' AND `id_user` = '$delpost[$q]' AND `id_foto` = '$foto[id]'"),0)==1)
	{
	mysql_query("DELETE FROM `gallery_frend` WHERE `id_user` = '$delpost[$q]' AND `id_foto` = '$foto[id]'");
	}
}
$ank_del = get_user($delpost[$q]);
echo "<font color='#395aff'><b>$ank_del[nick]</b></font>, ";
}

echo " удален(ы) из списка отмеченных друзей на этом фото</div>";
}else{
$err[] = 'Не выделено ни одного контакта';
}

}
elseif (isset($_POST['metka']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^post_([0-9]*)$#',$key,$postnum) && $value='1')
{
$delpost[]=$postnum[1];
}
}


if (isset($delpost) && is_array($delpost))
{
echo "<div class='mess'>Друзья: ";

for ($q=0; $q<=count($delpost)-1; $q++) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$delpost[$q]') OR (`user` = '$delpost[$q]' AND `frend` = '$user[id]') LIMIT 1"),0)==0)
$warn[]='Этого пользователя нет в вашем списке друзей';
else
{
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_frend` WHERE `id_avtor` = '$user[id]' AND `id_user` = '$delpost[$q]' AND `id_foto` = '$foto[id]'"),0)==0)
	{
	mysql_query("INSERT INTO `gallery_frend` (`id_user`, `id_foto`, `time`, `id_avtor`) values('$delpost[$q]', '$foto[id]', '$time', $user[id])",$db);
	}
}
$ank_del = get_user($delpost[$q]);
echo "<font color='#395aff'><b>$ank_del[nick]</b></font>, ";
}

echo " отмечен(ы) на этом фото</div>";
}else{
$err[] = 'Не выделено ни одного друга';
}

}
}
//------------------------------------------------------//

if (isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['ok']))
{

if ($user['id']!=$ank['id'])
admin_log('Фотогалерея','Фотографии',"Удаление фото пользователя '[url=/info.php?id=$ank[id]]$ank[nick][/url]'");
@unlink(H."sys/gallery/48/$foto[id].jpg");
@unlink(H."sys/gallery/128/$foto[id].jpg");
@unlink(H."sys/gallery/640/$foto[id].jpg");
@unlink(H."sys/gallery/foto/$foto[id].jpg");

mysql_query("DELETE FROM `gallery_foto` WHERE `id` = '$foto[id]' LIMIT 1");


msg('Фотография успешно удалена');
aut();



echo "<div class=\"foot\">\n";
echo "&laquo;<a href='/foto/$ank[id]/$gallery[id]/'>К фотографиям</a><br />\n";
echo "&laquo;<a href='/foto/$ank[id]/'>К фотоальбомам</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
exit;
}
if ($gallery['my']=='1'){
if (isset($_GET['act']) && $_GET['act']=='ava' && isset($_GET['ok']))
{
mysql_query("UPDATE `gallery_foto` SET `avatar` = '0' WHERE `id_user` = '$user[id]'");
mysql_query("UPDATE `gallery_foto` SET `avatar` = '1' WHERE `id` = '$foto[id]' LIMIT 1");

$_SESSION['message'] = 'Фотография установлена на главной';
header("Location: /foto/$ank[id]/$gallery[id]/$foto[id]/");
exit;
}
}
if (isset($_GET['act']) && $_GET['act']=='rename' && isset($_GET['ok']) && isset($_POST['name']) && isset($_POST['opis']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])),1);
if (ereg("\{|\}|\^|\%|\\$|#|@|!|\~|'|\"|`|<|>",$name))$err='В названии фото присутствуют запрещенные символы';
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (strlen2($name)<3)$err='Короткое название';
if (strlen2($name)>32)$err='Название не должно быть длиннее 32-х символов';
$name=my_esc($name);

$msg=$_POST['opis'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$msg=translit($msg);
//if (strlen2($msg)<10)$err='Короткое описание';
if (strlen2($msg)>1024)$err='Длина описания превышает предел в 1024 символа';
$msg=my_esc($msg);



if (!isset($err))
{
if ($user['id']!=$ank['id'])
admin_log('Фотогалерея','Фотографии',"Переименование фото пользователя '[url=/info.php?id=$ank[id]]$ank[nick][/url]'");
mysql_query("UPDATE `gallery_foto` SET `name` = '$name', `opis` = '$msg' WHERE `id` = '$foto[id]' LIMIT 1");
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = '$foto[id]'  LIMIT 1"));
msg('Фотография успешно переименована');
}



}

?>