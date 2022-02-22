<?
// думаю для начала токого обменника должно хватить =)
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')$skp = NULL; else $skp = " `sk` = '0' AND";

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Файлы'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();

if ($comm['files']==0)
{
echo "Раздел \"Файлы\" сообщества <b>".htmlspecialchars($comm['name'])."</b> закрыт\n";
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['file']))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id` = '".intval($_GET['file'])."' AND `id_comm` = '$comm[id]' AND `type` = 'file'"),0)==0)
{
$err[] = "Файл не найден";
err();
include_once '../sys/inc/tfoot.php';
exit();
}
$file = mysql_fetch_array(mysql_query("SELECT * FROM `comm_files` WHERE `id` = '".intval($_GET['file'])."' AND `id_comm` = '$comm[id]' AND `type` = 'file'"));
$file['path']=H."comm/files/c$comm[id]/d$file[id_dir]/".$file['name'].".".$file['ras'].".dat";
$file['size']=filesize(H."comm/files/c$comm[id]/d$file[id_dir]/".$file['name'].".".$file['ras'].".dat");

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id` = '$file[id_dir]' AND `id_comm` = '$comm[id]' AND `type` = 'dir'"),0)!=0)
{
$dir = mysql_fetch_array(mysql_query("SELECT * FROM `comm_files` WHERE `id` = '$file[id_dir]' AND `id_comm` = '$comm[id]' AND `type` = 'dir'"));
}
else
{
$dir = array();
$dir['id'] = 0;
$dir['name'] = "Файлы";
$dir['counter'] = '/0/';
}
if (isset($_GET['download_file']))
{
include_once '../sys/inc/downloadfile.php';
DownloadFile($file['path'], $file['name'].".".$file['ras'], ras_to_mime($file['ras']));
exit;
}
$creator = get_user($file['id_user']); // гг
$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]'"),0);
if ($count_komm > 0)
{
$last_komm = mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' ORDER BY `time` DESC LIMIT 1"));
$creator_last_komm = get_user($last_komm['id_user']);
}
if(isset($_GET['mdelete']) && ($ank['id']==$user['id'] || $uinc['access']=='adm'))$mdelete=1;

if(isset($mdelete) && isset($_POST['m_d_okey']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^mdelelte_komm_([0-9]*)$#',$key,$kid) && $value='1')
{
if (mysql_result(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$kid[1]' LIMIT 1"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$kid[1]' LIMIT 1"));
mysql_query("DELETE FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$komm[id]'");
}
}
}
}

if (isset($_GET['delete_file']))
{
if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')
{
if(isset($_POST['submited']))
{
mysql_query("DELETE FROM `comm_files_komm` WHERE `id_file` = '$file[id]' AND `id_comm` = '$comm[id]'");
mysql_query("DELETE FROM `comm_files_rating` WHERE `id_file` = '$file[id]' AND `id_comm` = '$comm[id]'");
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$file[id]' AND `type` = 'file' AND `id_comm` = '$comm[id]'");
unlink(H."comm/files/c$comm[id]/d$dir[id]/".htmlspecialchars($file['name']).".".htmlspecialchars($file['ras']).".dat");
if (is_file(H."comm/screen_tmp/48-48_".$file['id']."screen.png"))unlink(H."comm/screen_tmp/48-48_".$file['id']."screen.png");
if (is_file(H."comm/screen_tmp/128-128_".$file['id']."screen.png"))unlink(H."comm/screen_tmp/128-128_".$file['id']."screen.png");
header("Location:/comm/?act=files&id=$comm[id]&dir=$file[id_dir]");
exit;
}
echo "<form method='POST'>\n";
echo "Подтвердите удаление файла<br/>\n";
echo "<input type='submit' name='submited' value='Удалить'> <a href='/comm/?act=files&id=$comm[id]&file=$file[id]'>Отмена</a>\n";
echo "</form>\n";
}
else
{
$err[] = "У Вас нет прав для удаления файлов в данном сообществе!";
err();
}
include_once '../sys/inc/tfoot.php';
exit();
}

if (isset($_GET['edit_file']))
{
if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm' || $creator['id']==$user['id'])
{
if(isset($_POST['submited']) && isset($_POST['name']) && isset($_POST['desc']))
{
$name=$_POST['name'];
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `name` = '$name' AND `id` != '$file[id]' AND `type` = 'file' AND `ras` = '$file[ras]' AND `id_comm` = '$comm[id]' AND `id_dir` = '$dir[id]'"),0)!=0)$err[]="Такая папка уже есть";
if(strlen2($name)>40 || strlen2($name)<1)$err[]="Название не должно быть пустым и не больше 40-ка символов";
if(strlen2($desc)>512)$err[]="Описание должно быть не больше 512-ти символов";
if(!preg_match("#^([A-zА-я0-9\-\_\(\)\ ])+$#ui", $name))$err[]='В названии присутствуют запрещенные символы';
$name=my_esc($name);
$desc=my_esc($desc);
if (!isset($err))
{
rename(H."comm/files/c$comm[id]/d$dir[id]/".htmlspecialchars($file['name']).".".htmlspecialchars($file['ras']).".dat", H."comm/files/c$comm[id]/d$dir[id]/$name.$file[ras].dat");
mysql_query("UPDATE `comm_files` SET `name` = '$name', `desc` = '$desc' WHERE `id` = '$file[id]' AND `type` = 'file' AND `id_comm` = '$comm[id]'");
header("Location:/comm/?act=files&id=$comm[id]&file=$file[id]");
exit;
}
}
err();

echo "<form method='POST'>\n";
echo "Название:<br/>\n";
if (is_file(H.'style/themes/'.$set['set_them'].'/loads/14/'.$file['ras'].'.png'))echo "<img src='/style/themes/$set[set_them]/loads/14/$file[ras].png' alt='$file[ras]' />\n";
else echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' />\n";
echo " <input type='text' style='width: 80%' name='name' value='".input_value_text($file['name'])."'>.".htmlspecialchars($file['ras'])."<br/>\n";
echo "Описание:<br />\n";
echo "<textarea name='desc'>".input_value_text($file['desc'])."</textarea>\n";
echo "<input type='submit' name='submited' value='Сохранить'> <a href='/comm/?act=files&id=$comm[id]&file=$file[id]'>Назад</a>\n";
echo "</form>\n";
}
else
{
$err[] = "У Вас нет прав для редактирования файлов в данном сообществе!";
err();
}
include_once '../sys/inc/tfoot.php';
exit();
}

if(isset($mdelete) && isset($_POST['m_sk_okey']))
{
foreach ($_POST as $key => $value)
{
if (preg_match('#^mdelelte_komm_([0-9]*)$#',$key,$kid) && $value='1')
{
if (mysql_result(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$kid[1]' LIMIT 1"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$kid[1]' LIMIT 1"));
mysql_query("UPDATE `comm_files_komm` SET `sk` = '".($komm['sk']==0?1:0)."', `sk_user` = '$user[id]' WHERE `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$komm[id]'");
}
}
}
}

if(isset($_GET['reply']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '".intval($_GET['reply'])."'"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '".intval($_GET['reply'])."'"));
$ank2=get_user($komm['id_user']);
echo "<table class='post'>\n";
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo avatar($ank2['id']);
echo "</td>\n";
echo "<td class='p_t'>";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a> ".online($ank['id']);
echo "<br />\n";
echo output_text($komm['msg'])."\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
if (isset($user))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете оставлять комментарии к файлам сообщества сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
echo "<form method='post' name='message' action='?act=files&id=$comm[id]&file=$file[id]'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите свой ответ...'></textarea><br />\n";
echo "<input type='hidden' name='reply' value='$ank2[id]'>";
echo "<input type='hidden' name='komm_reply' value='$komm[id]'>";
echo "<br/><input value=\"Отправить\" type=\"submit\" /> <a href='?act=files&id=$comm[id]&file=$file[id]'>Назад</a>\n";
echo "</form>\n";
}
}
include_once '../sys/inc/tfoot.php';
exit;
}

if(isset($_GET['edit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '".intval($_GET['edit'])."'"),0)!=0)
{
$komm=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '".intval($_GET['edit'])."'"));
$ank2=get_user($komm['id_user']);
if(isset($user) && ($user['id']==$ank2['id'] && $komm['time']>time()-600))
{

if(isset($_POST['msg']))
{
$msg=$_POST['msg'];

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($msg)<1){$err[]='Короткое сообщение';}
if(!isset($err))
{
mysql_query("UPDATE `comm_files_komm` SET `msg` = '".my_esc($msg)."' WHERE `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id` = '$komm[id]'");
header("Location: ?act=files&id=$comm[id]&file=$file[id]");
}
}

err();
echo "<form method='post' name='message' action='?act=files&id=$comm[id]&file=$file[id]&edit=$komm[id]'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите комментарий...'>".input_value_text($komm['msg'])."</textarea><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" /> <a href='?act=files&id=$comm[id]&file=$file[id]'>Назад</a>\n";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit;
}
}
if (isset($user) && $user['id']!=$creator['id'] && $user['balls']>=50 && $user['rating']>=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_rating` WHERE `id_user` = '$user[id]' AND `id_file` = '$file[id]' AND `id_comm` = '$comm[id]'"), 0)==0 && isset($_GET['rating']))
{
$rating=intval($_GET['rating']);
if (in_array($rating,array(1,2,3,4,5,15)))
{
mysql_query("UPDATE `comm_files` SET `rating` = '".($file['rating']+$rating)."' WHERE `id` = '$file[id]' LIMIT 1");
mysql_query("INSERT INTO `comm_files_rating` (`id_user`, `id_file`, `id_comm`) values('$user[id]', '$file[id]', '$comm[id]')");
msg ('Ваш отзыв принят');
$file['rating']=$file['rating']+$rating;
}
}
if (isset($_POST['msg']) && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)==0)
{
$msg=$_POST['msg'];
if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
if (strlen2($msg)<1){$err[]='Короткое сообщение';}
if ($creator_last_komm['id']==$user['id'] && my_esc($msg)==$last_komm['msg']){$err[]='Ваше сообщение повторяет предыдущее';}
if(!isset($err)){
if(isset($_POST['reply']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '".intval($_POST['reply'])."'"),0)!=0)
{
$reply_user=get_user(intval($_POST['reply']));
$komm_reply=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id_user` = '$reply_user[id]' AND `id` = '".intval($_POST['komm_reply'])."'"));
$reply=1;
}
$q3=NULL;$qq=mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]'");while($ppost=mysql_fetch_array($qq)){$a=get_user($ppost['id_user']);if($a){$array=explode(";", $q3);foreach ($array as $key => $value){if($value==$a['id'])$g=1;}if(!isset($g))$q3="".($q3!=NULL?"$q3;":null)."$a[id]";if(isset($g))unset($g);}}
$array=explode(";", $q3);foreach ($array as $key => $value){
$a=get_user($value);
if($value!=NULL && $a)
{
$k=mysql_fetch_array(mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' AND `id_user` = '$a[id]' ORDER BY `id` DESC LIMIT 1"));
if($a['id']!=$ank['id'] && $user['id']!=$a['id'])
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `msg` = '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$a[id]'"),0)==0)mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$a[id]', '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");else mysql_query("UPDATE `jurnal SET `time` = '$time' WHERE `msg` = '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$a[id]'");
}
}
}
if ($ank['id']!=$user['id'])if(mysql_result(mysql_query("SELECT COUNT(*) FROM `jurnal` WHERE `msg` = '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$ank[id]'"),0)==0)mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");else mysql_query("UPDATE `jurnal SET `time` = '$time' WHERE `msg` = '$user[nick] оставил [url=/comm/?act=files&id=$comm[id]&file=$file[id]]комментарий к этому файлу[/url] сообщества [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].' AND `id_kont` = '$ank[id]'");

mysql_query("INSERT INTO `comm_files_komm` (`id_comm`, `id_user`, `id_file`, `time`, `msg`".(isset($reply)?", `id_reply`, `reply_msg`":null).") values('$comm[id]', '$user[id]', '$file[id]', '$time', '".my_esc($msg)."'".(isset($reply)?", '$reply_user[id]', '$komm_reply[msg]'":null).")");
header("Location: ?act=files&id=$comm[id]&file=$file[id]");

}
}

if (($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user') && isset($_GET['delete']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' LIMIT 1"),0)!=0)
{
mysql_query("DELETE FROM `comm_files_komm` WHERE$skp `id` = '".intval($_GET['delete'])."' AND `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' LIMIT 1");
header("Location: ?act=files&id=$comm[id]&file=$file[id]");
}


echo "<div class='p_m'>\n";
if (is_file(H.'style/themes/'.$set['set_them'].'/loads/14/'.$file['ras'].'.png'))echo "<img src='/style/themes/$set[set_them]/loads/14/$file[ras].png' alt='$file[ras]' />\n";
else echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' />\n";
echo " <b>".htmlspecialchars($file['name'])."</b>.".htmlspecialchars($file['ras'])."\n";
if (isset($user) && ($ank['id']==$user['id'] || $uinc && $uinc['access']!='user' || $user['id']==$creator['id']))echo "<span style='float: right;'><a href='?act=files&id=$comm[id]&file=$file[id]&edit_file'><img src='/comm/img/edit.png'/></a>".($ank['id']==$user['id']  || $uinc['access']=='adm'?" <a href='?act=files&id=$comm[id]&file=$file[id]&delete_file'><img src='/comm/img/delete.png'/></a>":NULL)."</span>\n";
echo "<br />\n";
$screen = create_screen(H."comm/files/c$comm[id]/d$dir[id]/".htmlspecialchars($file['name']).".$file[ras].dat", H."comm/screen_tmp/", 128, 128, $file['id']);
if ($screen != NULL)
{
echo "<img src='/comm/screen_tmp/$screen' /><br />\n";
//delete_screen(H."comm/screen_tmp/$screen");
}
if ($file['desc']!=NULL)echo output_text($file['desc'])."<br />\n";

echo "<img src='/comm/img/download.png' /> <a href='/comm/files_get/download/c$comm[id]/d$dir[id]/f$file[id]/".htmlspecialchars($file['name']).".".htmlspecialchars($file['ras'])."'>Скачать</a> (".size_file($file['size']).")<br />\n";
echo "</div>\n";
echo "<div class='menu'>\n";
echo "<img src='/comm/img/avatar.png' /> Выгрузил: \n";
echo "<a href='/info.php?id=$creator[id]'>$creator[nick]</a> ".online($creator['id']);
echo "<br />\n";
if (isset($_GET['file_replace']) && ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'))
{
header("Location: ?act=files&id=$comm[id]&file_replace=$file[id]");
exit();
}
else echo "<img src='/style/themes/$set[set_them]/loads/14/dir.png' /> Папка: ".htmlspecialchars($dir['name'])." ".($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'?" <a href='?act=files&id=$comm[id]&file=$file[id]&file_replace'>[изменить]</a>":NULL)."<br />";
echo "<img src='/comm/img/rating.png' /> Рейтинг: $file[rating]<br />";
if (isset($user) && $user['id']!=$creator['id'] && $user['balls']>=50 && $user['rating']>=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_rating` WHERE `id_user` = '$user[id]' AND `id_file` = '$file[id]' AND `id_comm` = '$comm[id]'"), 0)==0)
{
$array=array(1,2,3,4,5,15);
foreach($array AS $key => $value)
{
echo "<a href='?act=files&id=$comm[id]&file=$file[id]&rating=$value'><img src='/comm/img/rating/".$value.".png'></a> \n";
}
echo "<br />\n";
}
echo "<img src='/comm/img/message.png' /> Комментариев: $count_komm\n";
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
  <script>
$(document).ready(function(){
	$("input[name='check_all']").click( function() {
		if($(this).is(':checked')){
			$("input[name^='mdelelte_komm']").each(function() { $(this).attr('checked', true); });
		} else {
			$("input[name^='mdelelte_komm']").each(function() { $(this).attr('checked', false); });
		}

	});
});
  </script>
<?
if(isset($mdelete))echo "<br />\n<input type='checkbox' name='check_all' value='1'> Отметить все\n";
echo "</div>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if(isset($mdelete))
{
echo "<form method='post'>\n";
}
if (!$k_post)
{
echo "<tr>\n";
echo "<td class='p_t'>\n";
echo "Нет комментариев\n";
echo "</td>\n";
echo "</tr>\n";
}
?>
<script>
	function toggle(id) {
		var quote = document.getElementById('quote-' + id);
		var state = quote.style.display;
			if(state == 'none') {
				quote.style.display = 'block';
			} else {
				quote.style.display = 'none';
			}
	}
</script>
<?
$q = mysql_query("SELECT * FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$file[id]' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank2=get_user($post['id_user']);
echo "<tr>\n";
echo "<td class='icon48'>\n";
avatar($ank2['id']);
if(isset($mdelete))echo "<br />\n<center><input type='checkbox' name='mdelelte_komm_$post[id]' value='1'></center>\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='/info.php?id=$ank2[id]'>$ank2[nick]</a>".online($ank2['id']);
echo " (".vremja($post['time']).")\n";
if ($ank2['id']==$creator['id'])echo "<span style='float: right;'>Автор</span>\n";
echo "<br />\n";
if($post['id_reply']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[id_reply]'"),0))echo "<div id='quote-$post[id]' style='display:none; margin:0; margin-bottom:7px; background-color: #EAEEF4; border: 1px solid #999; color: #666; padding: 6px 5px; -webkit-border-radius: 4px; border-radius: 4px;'>".output_text($post['reply_msg'])."</div>\n";
if($post['sk']==1 && $post['sk_user']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[sk_user]'"),0))
{
$sku=get_user($post['sk_user']);
echo "<font color='red'>Скрыл".($sku['pol']==0?'a':null)." $sku[nick]</font><br/>";
}
if($post['id_reply']!=0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$post[id_reply]'"),0))
{
$ru=get_user($post['id_reply']);
echo "<a href='?act=files&id=$comm[id]&file=$file[id]' onclick='javascript:toggle(\"$post[id]\"); return false;'>$ru[nick]</a>, ";
}
echo output_text($post['msg']);
echo "<br />\n";
if ($ank2['id']!=0)echo "<a href='?act=files&id=$comm[id]&file=$file[id]&reply=$post[id]'>Ответить</a>\n";
?>
<span style='float:right'>
<?
if ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user')echo " <a href='?act=files&id=$comm[id]&file=$file[id]&delete=$post[id]'>Удалить</a>\n";
if(isset($user) && $user['id']==$ank2['id'] && $post['time']>time()-600)
{
echo ($ank['id']==$user['id'] && isset($user) || $uinc && $uinc['access']!='user'?$rk:NULL)."<a href='?act=files&id=$comm[id]&file=$file[id]&edit=$post[id]' style='color:green;'>Ред</a>\n";
}
?>
</span>
<?
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
if(isset($mdelete))echo "Выбранные: <input type='submit' name='m_d_okey' value='Удалить'> <input type='submit' name='m_sk_okey' value='Скрыть/Показать'> <a href='?act=files&id=$comm[id]&file=$file[id]&page=$page'>Отмена</a>\n\n</form>\n";
if ($k_page>1)str("?act=files&id=$comm[id]&file=$file[id]".(isset($mdelete)?"&mdelete=1":null)."&",$k_page,$page); // Вывод страниц
err();
if (isset($user))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)!=0)
{
$max_time_ban = mysql_result(mysql_query("SELECT MAX(`time_ban`) FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' LIMIT 1"),0);
$ban = mysql_fetch_array(mysql_query("SELECT * FROM `comm_users_ban` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `time_ban` = '$max_time_ban' LIMIT 1"),0);
$time_ban = ($ban['time_ban']-$ban['time'])/3600;
$ban_give = get_user($ban['id_ank']);
echo "<div class='menu'>\n";
echo "Вы были забанены модератором \n";
echo "<a href='/info.php?id=$ban_give[id]'>$ban_give[nick]</a> ".online($ban_give['id']);
echo " на $time_ban ч.<br />\n";
echo "Во время действия бана Вы не сможете оставлять комментарии к файлам сообщества сообщества <b>".htmlspecialchars($comm['name'])."</b>\n";
echo "</div>\n";
}
else
{
echo "<form method='POST'>\n";
echo "<textarea name='msg' rows='5' cols='17' style='width: 95%' placeholder='Введите комментарий...'></textarea><br />\n";
echo "<input type='submit' name='submited' value='Добавить' />\n";
echo "</form>\n";
}
}
else echo "<div class='menu'><img src='/comm/img/add.png' /> <a href='/aut.php'>Добавить комментарий</a></div>\n";
if($ank['id']==$user['id'] || $uinc['access']=='adm')echo "<div class='foot'><img src='/comm/img/move.png' /> <a href='?act=files&id=$comm[id]&file=$file[id]&page=$page&mdelete=start'>Выбрать комментарии</a><br /></div>\n";
echo "<div class='foot'>\n";
echo "&raquo; <a href='?act=files&id=$comm[id]&dir=$dir[id]'>Список файлов</a> | <a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";

include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['dir']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id` = '".intval($_GET['dir'])."' AND `id_comm` = '$comm[id]' AND `type` = 'dir'"),0)!=0)
{
$dir = mysql_fetch_array(mysql_query("SELECT * FROM `comm_files` WHERE `id` = '".intval($_GET['dir'])."' AND `id_comm` = '$comm[id]' AND `type` = 'dir'"));
}
else
{
$dir = array();
$dir['id'] = 0;
$dir['name'] = "Файлы";
$dir['counter'] = '/0/';
}
if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')
{
if (isset($_GET['delete_dir']))
{
if ($dir['id']!=0)
{
if(isset($_POST['submited']))
{
$q = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `counter` like '%/$dir[id]/%' AND `type` = 'file'");
while ($post = mysql_fetch_array($q))
{
mysql_query("DELETE FROM `comm_files_komm` WHERE `id_file` = '$post[id]' AND `id_comm` = '$comm[id]'");
mysql_query("DELETE FROM `comm_files_rating` WHERE `id_file` = '$post[id]' AND `id_comm` = '$comm[id]'");
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$post[id]' AND `type` = 'file' AND `id_comm` = '$comm[id]'");
unlink(H."comm/files/c$comm[id]/d$post[id_dir]/$post[name].$post[ras].dat");
if (is_file(H."comm/screen_tmp/48-48_".$post['id']."screen.png"))unlink(H."comm/screen_tmp/48-48_".$post['id']."screen.png");
if (is_file(H."comm/screen_tmp/128-128_".$post['id']."screen.png"))unlink(H."comm/screen_tmp/128-128_".$post['id']."screen.png");
}
$q = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `counter` like '%/$dir[id]/%' AND `type` = 'dir'");
while ($post = mysql_fetch_array($q))
{
rmdir(H."comm/files/c$comm[id]/d$post[id]");
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$post[id]' AND `type` = 'dir' AND `id_comm` = '$comm[id]'");
}
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$dir[id]' AND `type` = 'dir' AND `id_comm` = '$comm[id]'");
rmdir(H."comm/files/c$comm[id]/d$dir[id]");
header("Location:/comm/?act=files&id=$comm[id]&dir=$dir[id_dir]");
exit;
}
echo "<form method='POST'>\n";
echo "Подтвердите удаление папки<br/>\n";
echo "<input type='submit' name='submited' value='Удалить'> <a href='/comm/?act=files&id=$comm[id]&dir=$dir[id]'>Отмена</a>\n";
echo "</form>\n";
}
else
{
$err[] = "Нельзя удалять корневую папку!";
err();
}
include_once '../sys/inc/tfoot.php';
exit();
}

if (isset($_GET['edit_dir']))
{
if ($dir['id']!=0)
{
if(isset($_POST['submited']) && isset($_POST['name']))
{
$name=$_POST['name'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `name` = '$name' AND `id` != '$dir[id]' AND `type` = 'dir' AND `id_comm` = '$comm[id]' AND `id_dir` = '$dir[id]'"),0)!=0)$err[]="Такая папка уже есть";
if(strlen2($name)>40 || strlen2($name)<1)$err[]="Название не должно быть пустым и не больше 40-ка символов";
$name=my_esc($name);
if (!isset($err))
{
mysql_query("UPDATE `comm_files` SET `name` = '$name' WHERE `id` = '$dir[id]' AND `type` = 'dir' AND `id_comm` = '$comm[id]'");
header("Location:/comm/?act=files&id=$comm[id]&dir=$dir[id]");
exit;
}
}
err();

echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value='".input_value_text($dir['name'])."'><br/>\n";
echo "<input type='submit' name='submited' value='Сохранить'> <a href='/comm/?act=files&id=$comm[id]&dir=$dir[id]'>Назад</a>\n";
echo "</form>\n";
}
else
{
$err[] = "Нельзя редактировать корневую папку!";
err();
}
include_once '../sys/inc/tfoot.php';
exit();
}

if (isset($_GET['add_dir']))
{
if(isset($_POST['submited']) && isset($_POST['name']))
{
$name=$_POST['name'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'dir' AND `name` = '$name' AND `id_dir` = '$dir[id]'"),0)!=0)$err[]="Такая папка уже есть";
if(strlen2($name)>40 || strlen2($name)<1)$err[]="Название не должно быть пустым и не больше 40-ка символов";
$name=my_esc($name);
if (!isset($err))
{
mysql_query("INSERT INTO `comm_files` (`id_comm`, `type`, `name`, `id_dir`, `counter`) VALUES ('$comm[id]', 'dir', '$name', '$dir[id]', '$dir[counter]$dir[id]/')");
header("Location:/comm/?act=files&id=$comm[id]&dir=$dir[id]");
exit;
}
}
err();
echo "<form method='POST'>\n";
echo "Название:<br/>\n";
echo "<input type='text' name='name' value=''><br/>\n";
echo "<input type='submit' name='submited' value='Добавить'> <a href='/comm/?act=files&id=$comm[id]'>Назад</a>\n";
echo "</form>\n";
include_once '../sys/inc/tfoot.php';
exit();
}

}
if (isset($_GET['add_file']))
{
if (isset($_POST['submited']))
{
$name=esc(stripcslashes(htmlspecialchars($_FILES['file']['name'])));
$name=ereg_replace('(#|\?)', NULL, $name);
$ras=strtolower(eregi_replace('^.*\.', NULL, $name));
$name=eregi_replace('\.[^\.]*$', NULL, $name); // имя файла без расширения
$size=filesize($_FILES['file']['tmp_name']);
$desc=$_POST['desc'];
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_dir` = '$dir[id]' AND `name` = '$name' AND `ras` = '$ras' AND `id_comm` = '$comm[id]' AND `type` = 'file'"),0)!=0)
$err[] = 'Файл с таким названием уже есть в этой папке';
if (strlen2($name)>40 || strlen2($name)<1)$err[]="Название не должно быть пустым и не больше 40-ка символов";
if(!preg_match("#^([A-zА-я0-9\-\_\(\)\ ])+$#ui", $name))$err[]='В названии присутствуют запрещенные символы';
if ($ras==NULL || $ras==$name || !preg_match("#^([A-z\-\_\(\)\ ])+$#ui", $ras))$err[] = "Неверное расширение";
if (!isset($err))
{
mysql_query("INSERT INTO `comm_files` SET `id_comm` = '$comm[id]', `name` = '".my_esc($name)."', `desc` = '".my_esc($desc)."', `time` = '$time', `id_dir` = '$dir[id]', `counter` = '$dir[counter]$dir[id]/', `type` = 'file', `id_user` = '$user[id]', `ras` = '$ras'");
$id_file=mysql_insert_id();
mkdir(H."comm/files/c$comm[id]", 0777);
mkdir(H."comm/files/c$comm[id]/d$dir[id]", 0777);
if (!@copy($_FILES['file']['tmp_name'], H."comm/files/c$comm[id]/d$dir[id]/".$name.".".$ras.".dat"))
{
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$id_file' LIMIT 1");
$err[]='Ошибка при выгрузке';
}
else {
header("Location:?act=files&id=$comm[id]&file=$id_file");
}
}
}

err();
echo "<div class='menu'>Папка: <a href='?act=files&id=$comm[id]&dir=$dir[id]'><b><img src='/style/themes/$set[set_them]/loads/14/dir.png' alt='dir' /> ".htmlspecialchars($dir['name'])."</b></a><br/></div>";
echo "<form method=\"post\" enctype=\"multipart/form-data\">";
echo "Выберите файл *<br />\n";
echo "<input name='file' type='file'/><br />\n";
//echo "<input type='checkbox' name='p18' value='1'> Только для взрослых <font color='red'>(+18)</font><br/>";
echo "Описание:<br />\n";
echo "<textarea name='desc'></textarea><br />\n";
echo "<input class='submit' type='submit' name='submited' value='Выгрузить' /><br />\n";
echo "</form>";
echo "<b>*Размер файла должен быть меньше 10 MB</b>";
?>
<div class="menu">
	<span style="font-size:small;color:blue">
		Загрузка может длиться несколько минут. Это зависит от размера файла и скорости передачи данных на вашем устройстве.
	</span>
		<br />
	<span style="color: #218094">
		Если у вас не видно выше кнопки выбора файла, значит ваш браузер не поддерживает загрузку файлов!
	</span>
</div>
<?
include_once '../sys/inc/tfoot.php';
exit();
}
if (isset($_GET['file_replace']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id` = '".intval($_GET['file_replace'])."' AND `id_comm` = '$comm[id]' AND `type` = 'file'"),0)!=0 && ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm'))
{
$file_replace = mysql_fetch_array(mysql_query("SELECT * FROM `comm_files` WHERE `id` = '".intval($_GET['file_replace'])."' AND `id_comm` = '$comm[id]' AND `type` = 'file'"));
if (isset($_GET['replace_there']))
{
mysql_query("UPDATE `comm_files` SET `id_dir` = '$dir[id]', `counter` = '$dir[counter]$dir[id]/' WHERE `id` = '$file_replace[id]'");
mkdir(H."comm/files/c$comm[id]", 0777);
mkdir(H."comm/files/c$comm[id]/d$dir[id]", 0777);
copy(H."comm/files/c$comm[id]/d$file_replace[id_dir]/".$file_replace['name'].".".$file_replace['ras'].".dat", H."comm/files/c$comm[id]/d$dir[id]/".$file_replace['name'].".".$file_replace['ras'].".dat");
unlink(H."comm/files/c$comm[id]/d$file_replace[id_dir]/".$file_replace['name'].".".$file_replace['ras'].".dat");
header("Location: ?act=files&id=$comm[id]&dir=$file_replace[id_dir]");
exit();
}
}

if (isset($_GET['new']) && $dir['id']==0)$new=1;
elseif (isset($_GET['top']) && $dir['id']==0)$top=1;
elseif (isset($_GET['search']) && $dir['id']==0)
{
$search=1;
$qsearch=NULL;
if (isset($_SESSION['qsearch']))$qsearch=$_SESSION['qsearch'];
if (isset($_POST['qsearch']))$qsearch=$_POST['qsearch'];
$_SESSION['qsearch']=$qsearch;

$qsearch=preg_replace("#( ){2,}#"," ",$qsearch);
$qsearch=preg_replace("#^( ){1,}|( ){1,}$#","",$qsearch);
$q_search=str_replace('%','',$qsearch);
$q_search=str_replace(' ','%',$q_search);
}
?>

<div class='menu'>
<?
if (!isset($new) && !isset($top) && !isset($search))
{
?>
	<?php echo "<img src='/style/themes/$set[set_them]/loads/14/dir.png' alt='dir' /> \n";?>Папка: <?php echo htmlspecialchars($dir['name'])."<br />\n";?>
<?
if (isset($file_replace) && !isset($new) && !isset($top) && !isset($search))
{
echo "Перемещение файла \"<a href='?act=files&id=$comm[id]&file=$file_replace[id]'>";
if (is_file(H.'style/themes/'.$set['set_them'].'/loads/14/'.$file_replace['ras'].'.png'))echo "<img src='/style/themes/$set[set_them]/loads/14/$file_replace[ras].png' alt='$file_replace[ras]' /> \n";
else echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' /> \n";
echo htmlspecialchars($file_replace['name']).".".htmlspecialchars($file_replace['ras'])."\" <a href='?act=files&id=$comm[id]&dir=$dir[id]'>[отмена]</a>\n<br />\n";
echo "<a href='?act=files&id=$comm[id]&dir=$dir[id]&file_replace=$file_replace[id]&replace_there=1'>Переместить сюда</a><br />\n";
}
if ($dir['id']==0)
{
echo "<img src='/comm/img/green_plus.png' /> <a href='?act=files&id=$comm[id]&top'>Популярные</a> | <a href='?act=files&id=$comm[id]&new'>Новые</a><br />\n";
echo "<img src='/comm/img/search.png' /> <a href='?act=files&id=$comm[id]&search=1'>Поиск файлов</a><br />\n";
}
}
else
{
if (isset($new))
{
echo "Новые файлы";
$qsort = " `id_comm` = '$comm[id]' AND `time` > '".($time-(3600*24))."' ORDER BY `time` DESC";
$qsortk = " `id_comm` = '$comm[id]' AND `time` > '".($time-(3600*24))."'";
}
elseif (isset($top))
{
echo "Популярные файлы";
$qsort = " `id_comm` = '$comm[id]' AND `rating` > '0' ORDER BY `rating` DESC";
$qsortk = " `id_comm` = '$comm[id]' AND `rating` > '0'";
}
elseif (isset($search))
{
echo "Поиск файлов";
$qsort = " (`name` like '%".mysql_escape_string($q_search)."%' OR `ras` like '%".mysql_escape_string($q_search)."%') AND `id_comm` = '$comm[id]' ORDER BY `time` DESC";
$qsortk = " `name` like '%".mysql_escape_string($q_search)."%'";
}
}
?>
</div>
<?

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE".(!isset($new) && !isset($top) && !isset($search)?" `id_comm` = '$comm[id]' AND `type` = 'file' AND `id_dir` = '$dir[id]'":$qsortk).""),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0 && (isset($new) || isset($search)))
{
echo "<table class='post'>\n";
echo "<tr>\n";
echo "<td class='p_t'>\n";
if (isset($new))echo "Нет новых файлов";
elseif (isset($search))echo "Ничего не найдено";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
}
if ($page==1 && !isset($new) && !isset($top) && !isset($search))
{
echo "<table class='post'>\n";
$q = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'dir' AND `id_dir` = '$dir[id]' ORDER BY `name` ASC");
while ($post = mysql_fetch_array($q))
{
$count_files=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'file' AND `counter` like '%/$post[id]/%'"),0);
$count_files_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'file' AND `counter` like '%/$post[id]/%' AND `time` > '".($time-(3600*24))."'"),0);
$count_files_show=$count_files.($count_files_new>0?" (+$count_files_new)":NULL);
echo "<tr>\n";
echo "<td class='icon48'>\n";
echo "<img src='/style/themes/$set[set_them]/loads/14/dir.png' alt='dir' />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=files&id=$comm[id]&dir=$post[id]".(isset($file_replace) && !isset($new) && !isset($top)?"&file_replace=$file_replace[id]":NULL)."'>".htmlspecialchars($post['name'])."</a>\n";
if ($ank['id']==$user['id'] && isset($user))
{
echo "<span style='float: right;'>\n";
echo " <a href='?act=files&id=$comm[id]&dir=$post[id]&edit_dir'><img src='/comm/img/edit.png'/></a> <a href='?act=files&id=$comm[id]&dir=$post[id]&delete_dir'><img src='/comm/img/delete.png'/></a>\n";
echo "</span>\n";
}
echo "<br />\n";
echo "Файлов: $count_files_show\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
}
if (!isset($file_replace))
{
echo "<table class='post'>\n";
$q = mysql_query("SELECT * FROM `comm_files` WHERE".(!isset($new) && !isset($top) && !isset($search)?" `id_comm` = '$comm[id]' AND `type` = 'file' AND `id_dir` = '$dir[id]' ORDER BY `name` ASC":$qsort)." LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$count_komm=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$post[id]'"),0);
$count_komm_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files_komm` WHERE$skp `id_comm` = '$comm[id]' AND `id_file` = '$post[id]' AND `time` > '".($time-(3600*24))."'"),0);
$count_komm_show=$count_komm.($count_komm_new>0?" (+$count_komm_new)":NULL);
echo "<tr>\n";
echo "<td class='icon48'>\n";
$screen = create_screen(H."comm/files/c$comm[id]/d$post[id_dir]/$post[name].$post[ras].dat", H."comm/screen_tmp/", 48, 48, $post['id']);
if ($screen != NULL)
{
echo "<img src='/comm/screen_tmp/$screen' /><br />\n";
}
else echo "<img src='/comm/screen_tmp/48-48_0screen.png' /><br />\n";
echo "</td>\n";
echo "<td class='p_t'>\n";
echo "<a href='?act=files&id=$comm[id]&file=$post[id]'>\n";
if (is_file(H.'style/themes/'.$set['set_them'].'/loads/14/'.$post['ras'].'.png'))echo "<img src='/style/themes/$set[set_them]/loads/14/$post[ras].png' alt='$post[ras]' /> \n";
else echo "<img src='/style/themes/$set[set_them]/loads/14/file.png' alt='file' /> \n";
echo htmlspecialchars($post['name']).".".htmlspecialchars($post['ras'])."</a> (".vremja($post['time']).")<br />\n";
if (isset($top))echo "Рейтинг: $post[rating]\n";
else echo "Комментариев: $count_komm_show\n";
echo "</td>\n";
echo "</tr>\n";
}
echo "</table>\n";
}
if ($k_page>1 && !isset($file_replace))str("?act=files&id=$comm[id]&dir=$dir[id]&".(isset($new) || isset($top)?(isset($new)?"new&":"top&"):(isset($search)?"search&":NULL)),$k_page,$page); // Вывод страниц
if ($uinc && !isset($new) && !isset($top) && !isset($search))
{
echo "<div class='foot'>\n";
echo "<img src='/comm/img/add.png' /> Добавить: <a href='?act=files&id=$comm[id]&dir=$dir[id]&add_file'>файл</a>\n";
if ($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')echo " | <a href='?act=files&id=$comm[id]&dir=$dir[id]&add_dir'>папку</a><br />\n";
echo "</div>\n";
}
elseif (isset($search))
{
echo "<form method='POST' class='foot' action='?act=files&id=$comm[id]&search=1'><input type='text' placeholder='Введите пару слов для поиска...' name='qsearch' value='".input_value_text($qsearch)."' style='width: 80%' /> <input type='submit' value='Поиск' /></form>\n";
}
echo "<div class='foot'>\n";
echo "&raquo; ".(isset($new) || isset($top) || isset($search)?"<a href='?act=files&id=$comm[id]'>Назад</a> | ":NULL)."".($dir['id']!=0?"<a href='?act=files&id=$comm[id]&dir=$dir[id_dir]".(isset($file_replace)?"&file_replace=$file_replace[id]":NULL)."'>Назад</a> | ":NULL)."<a href='?act=comm&id=$comm[id]'>В сообщество</a>\n";
echo "</div>\n";
}
else{header("Location:/comm");exit;}
?>