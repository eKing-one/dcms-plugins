<?
if (isset($_GET['act']) && $_GET['act']=='txt')
{
ob_clean();
ob_implicit_flush();
header('Content-Type: text/plain; charset=utf-8', true);

header('Content-Disposition: attachment; filename="'.retranslit($them['name']).'.txt";');
echo "Тема: $them[name] ($forum[name]/$razdel[name])\r\n";
$q=mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' ORDER BY `time` ASC");

//echo "\r\n";
while ($post = mysql_fetch_assoc($q))
{
echo "\r\n";
$ank=get_user($post['id_user']);
echo "$ank[nick] (".date("j M Y в H:i", $post['time']).")\r\n";



if ($post['cit']!=NULL && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '$post[cit]'"),0)==1)
{
$cit=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '$post[cit]' LIMIT 1"));
$ank_c=get_user($cit['id_user']);
echo "--Цитата--\r\n";
echo "$ank_c[nick] (".date("j M Y в H:i", $cit['time'])."):\r\n";
echo trim(br($cit['msg'],"\r\n"))."\r\n";
echo "----------\r\n";
}

echo trim(br($post['msg'],"\r\n"))."\r\n";





}
echo "\r\nИсточник: http://$_SERVER[SERVER_NAME]/forum/$forum[id]/$razdel[id]/$them[id]/\r\n";
exit;
}



if (isset($user) && isset($_GET['f_del']) && is_numeric($_GET['f_del']) && isset($_SESSION['file'][$_GET['f_del']]))
{
unlink($_SESSION['file'][$_GET['f_del']]['tmp_name']);
}


if (isset($user) && isset($_GET['zakl']) && $_GET['zakl']==1)
{
mysql_query("INSERT INTO `forum_zakl` (`id_user`, `time`,  `id_them`, `time_obn`) values('$user[id]', '$time', '$them[id]', '$time')");
//if (mysql_error()!=NULL)echo mysql_error();
msg('Тема добавлена в закладки');
}
elseif (isset($user) && isset($_GET['zakl']) && $_GET['zakl']==0)
{
mysql_query("DELETE FROM `forum_zakl` WHERE `id_user` = '$user[id]' AND `id_them` = '$them[id]'");
//if (mysql_error()!=NULL)echo mysql_error();
msg('Тема удалена из закладок');
}


if (isset($user) && isset($_GET['act']) && $_GET['act']=='new' && isset($_FILES['file_f']) && ereg('\.', $_FILES['file_f']['name']) && isset($_POST['file_s']))
{
copy($_FILES['file_f']['tmp_name'], H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp');
chmod(H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp', 0777);

if (isset($_SESSION['file']))$next_f=count($_SESSION['file']);else $next_f=0;


$file=esc(stripcslashes(htmlspecialchars($_FILES['file_f']['name'])));
$_SESSION['file'][$next_f]['name']=eregi_replace('\.[^\.]*$', NULL, $file); // имя файла без расширения
$_SESSION['file'][$next_f]['ras']=strtolower(eregi_replace('^.*\.', NULL, $file));
$_SESSION['file'][$next_f]['tmp_name']=H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp';
$_SESSION['file'][$next_f]['size']=filesize(H.'sys/tmp/'.$user['id'].'_'.md5_file($_FILES['file_f']['tmp_name']).'.forum.tmp');
$_SESSION['file'][$next_f]['type']=$_FILES['file_f']['type'];



}


if (isset($user) && ($them['close']==0  || $them['close']==1 && user_access('forum_post_close')) && isset($_GET['act']) && $_GET['act']=='new' && isset($_POST['msg']) && !isset($_POST['file_s']))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)<2)$err='Короткое сообщение';
if (strlen2($msg)>1024)$err='Длина сообщения превышает предел в 1024 символа';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0)$err='Ваше сообщение повторяет предыдущее';

if (!isset($err))
{

if (isset($_POST['cit']) && is_numeric($_POST['cit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '".intval($_POST['cit'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1)
$cit=intval($_POST['cit']); else $cit='null';
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
//// Рейтинг активности
include '../akt_rat.php';
$post_them=array('30','50','100','250', '500', '750', '1000', '1250');
for ($i=0; $i < count($post_them); $i++){

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0) >=$times[$i])
{
$ank_aut=get_user($them['id_user']);

mysql_query("UPDATE `user` SET `akt_rating` = '".($ank_aut['akt_rating']+0.003)."' WHERE `id` = '$ank_aut[id]' LIMIT 1");
}
}
/////////////////////
mysql_query("UPDATE `forum_zakl` SET `time_obn` = '$time' WHERE `id_them` = '$them[id]'");
mysql_query("INSERT INTO `forum_p` (`id_forum`, `id_razdel`, `id_them`, `id_user`, `msg`, `time`, `cit`) values('$forum[id]', '$razdel[id]', '$them[id]', '$user[id]', '".my_esc($msg)."', '$time', $cit)");

$post_id=mysql_insert_id();


if (isset($_SESSION['file']) && isset($user))
{
for ($i=0; $i<count($_SESSION['file']);$i++)
{
if (isset($_SESSION['file'][$i]) && is_file($_SESSION['file'][$i]['tmp_name']))
{
mysql_query("INSERT INTO `forum_files` (`id_post`, `name`, `ras`, `size`, `type`) values('$post_id', '".$_SESSION['file'][$i]['name']."', '".$_SESSION['file'][$i]['ras']."', '".$_SESSION['file'][$i]['size']."', '".$_SESSION['file'][$i]['type']."')");
$file_id=mysql_insert_id();
copy($_SESSION['file'][$i]['tmp_name'], H.'sys/forum/files/'.$file_id.'.frf');
unlink($_SESSION['file'][$i]['tmp_name']);
}
}
unset($_SESSION['file']);
}

unset($_SESSION['msg']);



mysql_query("UPDATE `forum_r` SET `time` = '$time' WHERE `id` = '$razdel[id]' LIMIT 1");
mysql_query("UPDATE `forum_t` SET `time` = '$time' WHERE `id` = '$them[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
 

aut();

header("Refresh: 1; url=/forum/$forum[id]/$razdel[id]/$them[id]/?page=end&".SID);
echo "<div class='menu'>\n";
echo "<a style='font-weight:bold;' href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=end\" title='Перейти в тему'>Перейти в тему</a><br />\n";
echo "<a href=\"/forum/$forum[id]/$razdel[id]/\" title='Вернуться в раздел'>$razdel[name]</a><br />\n";
echo "<a href=\"/forum/$forum[id]/\">$forum[name]</a><br />\n";
echo "<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';


}

}

if ($them['close']==1)
msg('Тема закрыта для обсуждения');






if (isset($user) &&  $user['balls']>=50 && $user['rating']>=0 && isset($_GET['id_file'])
&&
mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_files` WHERE `id` = '".intval($_GET['id_file'])."'"), 0)==1
&&
 mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_files_rating` WHERE `id_user` = '$user[id]' AND `id_file` = '".intval($_GET['id_file'])."'"), 0)==0)
{
if (isset($_GET['rating']) && $_GET['rating']=='down')
{
mysql_query("INSERT INTO `forum_files_rating` (`id_user`, `id_file`, `rating`) values('$user[id]', '".intval($_GET['id_file'])."', '-1')");
///рейтинг активности
mysql_query("UPDATE `user` SET `akt_rating` = '".($ank['akt_rating']-0.003)."' WHERE `id` = '$ank[id]' LIMIT 1");
msg ('Ваш отрицательный отзыв принят');
}
elseif(isset($_GET['rating']) && $_GET['rating']=='up')
{
mysql_query("INSERT INTO `forum_files_rating` (`id_user`, `id_file`, `rating`) values('$user[id]', '".intval($_GET['id_file'])."', '1')");
mysql_query("UPDATE `user` SET `akt_rating` = '".($ank['akt_rating']+0.001)."' WHERE `id` = $ank[id]' LIMIT 1");
msg ('Ваш положительный отзыв принят');
}
}






err();
aut();




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){$lim=NULL;}else $lim=" LIMIT $start, $set[p_str]";
$q=mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' ORDER BY `time` ASC$lim");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений в теме \"$them[name]\"\n";
echo "  </td>\n";
echo "   </tr>\n";
}
$post_k=$start;
while ($post = mysql_fetch_assoc($q))
{



$ank=get_user($post['id_user']);
$post_k++;

echo "   <tr>\n";




if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";

if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete')
{
echo "<input type='checkbox' name='post_$post[id]' value='1' />";
}
else
avatar($ank['id']);


echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete')
{
echo "<input type='checkbox' name='post_$post[id]' value='1' />";
}
else
echo "<img src='/style/themes/$set[set_them]/user/$ank[pol].png' alt='' />";
echo "  </td>\n";
}



if ($set['show_num_post']==1)$num_post=$post_k.') '; else $num_post=NULL;

echo "  <td class='p_t'>\n";
if (isset($user) && $them['close']==0)
echo "$num_post<a href='/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/msg' title='Ответить $ank[nick]'>$ank[nick]</a>".online($ank['id'])." (".vremja($post['time']).")\n";
else
echo "$num_post<a href='/info.php?id=$ank[id]' title='Анкета $ank[nick]'>$ank[nick]</a> ".online($ank['id'])." (".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
if ($post['cit']!=NULL && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '$post[cit]'"),0)==1)
{
$cit=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '$post[cit]' LIMIT 1"));
//$ank_c=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $cit[id_user] LIMIT 1"));
$ank_c=get_user($cit['id_user']);
echo "<div class='cit'>\n";
echo "<b>$ank_c[nick] (".vremja($cit['time'])."):</b><br />\n";
echo output_text($cit['msg'])."<br />\n";
echo "</div>\n";
}

echo output_text($post['msg'])."<br />\n";

include H.'/forum/inc/file.php';


if (user_access('forum_post_ed') && ($ank['level']<=$user['level'] || $ank['level']==$user['level'] && $ank['id']==$user['id']))
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/edit'>Редактировать</a>\n";
elseif (isset($user) && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post_k==$k_post)
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/edit'>Редактировать (".($post['time']+600-time())." сек)</a>\n";



if (isset($user) && $them['close']==0 && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post_k==$k_post)
echo ' | ';
elseif ($them['close']==0 && user_access('forum_post_ed') && ($ank['level']<$user['level'] || $ank['level']==$user['level'] && $ank['id']==$user['id']))
echo ' | ';



if ($them['close']==0 && isset($user))
echo "<a href='/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/cit' title='Цитировать $ank[nick]'>&ldquo;Цитировать&rdquo;</a><br />\n";






echo "  </td>\n";
echo "   </tr>\n";
}
echo "</table>\n";
if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){}
elseif ($k_page>1)str("/forum/$forum[id]/$razdel[id]/$them[id]/?",$k_page,$page); // Вывод страниц

if ((user_access('forum_post_ed') || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){}
elseif (isset($user) && ($them['close']==0 || $them['close']==1 && user_access('forum_post_close')))
{
if ($user['set_files']==1)
echo "<form method='post' name='message' enctype='multipart/form-data' action='/forum/$forum[id]/$razdel[id]/$them[id]/new?$passgen'>\n";
else
echo "<form method='post' name='message' action='/forum/$forum[id]/$razdel[id]/$them[id]/new?$passgen'>\n";
if (isset($_POST['msg']) && isset($_POST['file_s']))$msg2=output_text($_POST['msg'],false,true,false,false,false); else $msg2=NULL;


if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\">$msg2</textarea><br />\n";

if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";

if ($user['set_files']==1){
if (isset($_SESSION['file']))
{
echo "Прикрепленные файлы:<br />\n";
for ($i=0; $i<count($_SESSION['file']);$i++)
{
if (isset($_SESSION['file'][$i]) && is_file($_SESSION['file'][$i]['tmp_name']))
{
echo "<img src='/style/themes/$set[set_them]/forum/14/file.png' alt='' />\n";
echo $_SESSION['file'][$i]['name'].'.'.$_SESSION['file'][$i]['ras'].' (';
echo size_file($_SESSION['file'][$i]['size']);
echo ") <a href='/forum/$forum[id]/$razdel[id]/$them[id]/d_file$i' title='Удалить из списка'><img src='/style/themes/$set[set_them]/forum/14/del_file.png' alt='' /></a>\n";
echo "<br />\n";
}
}
}

echo "<input name='file_f' type='file' /><br />\n";
echo "<input name='file_s' value='Прикрепить файл' type='submit' /><br />\n";
}

echo "<input name='post' value='Отправить' type='submit' /><br />\n";
echo "</form>\n";
}
echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"/smiles.php\">Смайлы</a><br />\n";
echo "&raquo;<a href=\"/rules.php\">Правила</a><br />\n";
echo "</div>\n";
echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"txt\">Скачать тему в txt</a><br />\n";
echo "</div>\n";


?>