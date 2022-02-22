<?

if (isset($user)  && ($them['close']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']) && isset($_GET['act']) && $_GET['act']=='new' && isset($_POST['msg']))
{
$msg=htmlspecialchars($_POST['msg']);
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)<2)$err='Короткое сообщение';
if (strlen2($msg)>10000)$err='Длина сообщения превышает предел в 10000 символов';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' AND `id_user` = '$user[id]' AND `mess` = '".my_esc($msg)."' LIMIT 1"),0)!=0)$err='Ваше сообщение повторяет предыдущее';

if (!isset($err))
{

if (isset($_POST['cit']) && is_numeric($_POST['cit']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id` = '".intval($_POST['cit'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_gruppy` = '".intval($_GET['s'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1)
$cit=intval($_POST['cit']); else $cit='null';
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `gruppy_forum_mess` (`id_forum`, `id_gruppy`, `id_them`, `id_user`, `mess`, `time`, `cit`) values('$forum[id]', '$gruppy[id]', '$them[id]', '$user[id]', '".my_esc($msg)."', '$time', '$cit')");

$post_id=mysql_insert_id();

unset($_SESSION['msg']);

mysql_query("UPDATE `gruppy_forum_thems` SET `time` = '$time' WHERE `id` = '$them[id]' LIMIT 1");
msg('Сообщение успешно добавлено');


header("Refresh: 1; url=?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end&".SID);
echo "<div class='foot'>\n";
echo "<a style='font-weight:bold;' href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end\" title='Перейти в тему'>Перейти в тему</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='Вернуться в раздел'>$forum[name]</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';


}

}
if ($them['close']==1)
msg('Тема закрыта для обсуждения');

err();
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo "<table class='post'>\n";
if ((isset($user) && $user['id']==$gruppy['admid'] || isset($user) && $ank2['id']==$user['id']) && isset($_GET['act']) && $_GET['act']=='post_delete'){$lim=NULL;}else $lim=" LIMIT $start, $set[p_str]";
$q=mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '$them[id]' AND `id_forum` = '$forum[id]' AND `id_gruppy` = '$gruppy[id]' ORDER BY `time` ASC$lim");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <div class='err'>\n";
echo "Нет сообщений в теме \"$them[name]\"\n";
echo "  </div>\n";
echo "   </tr>\n";
}
$post_k=$start;
while ($post = mysql_fetch_assoc($q))
{
$ank=get_user($post['id_user']);
$post_k++;

echo "   <tr>\n";
if ((isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1) && isset($_GET['act']) && $_GET['act']=='post_delete')
{
echo "<input type='checkbox' name='post_$post[id]' value='1' />";
}
if ($set['show_num_post']==1)$num_post=$post_k.') '; else $num_post=NULL;

echo "<div class='nav2'>\n";
if (isset($user) && $them['close']==0 && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
echo "$num_post<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&id_post=$post[id]&act=msg' title='Ответить $ank[nick]'>$ank[nick]</a>".online($ank['id'])." (".vremja($post['time']).")\n";
else
echo "$num_post<a href='/info.php?id=$ank[id]' title='Анкета $ank[nick]'>$ank[nick]</a> ".online($ank['id'])." (".vremja($post['time']).")\n";
echo "</div>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
if ($post['cit']!=NULL && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id` = '$post[cit]'"),0)==1)
{
$cit=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id` = '$post[cit]' LIMIT 1"));
//$ank_c=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $cit[id_user] LIMIT 1"));
$ank_c=get_user($cit['id_user']);
echo "<div class='cit'>\n";
echo "<b>$ank_c[nick] (".vremja($cit['time'])."):</b><br />\n";
echo output_text($cit['mess'])."<br />\n";
echo "</div>\n";
}

echo output_text($post['mess'])."<br />\n";


if (isset($user) && $user['id']==$gruppy['admid'])
echo "<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&id_post=$post[id]&act=edit'> ред.</a>\n";
elseif (isset($user) && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post_k==$k_post)
echo "<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&id_post=$post[id]&act=edit'> <img src='img/reply.png' alt='' class='icon'/> (".($post['time']+600-time())." сек)</a>\n";



if (isset($user) && $them['close']==0 && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post_k==$k_post)
echo ' | ';
elseif ($them['close']==0 && isset($user) && $user['id']==$gruppy['admid'])
echo ' | ';



if ($them['close']==0 && isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
echo "<a href='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&id_post=$post[id]&act=cit' title='Цитировать $ank[nick]'>[<b>*</b>]</a><br />\n";

echo "   </tr>\n";
}
echo "</table>\n";
if ((isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1) && isset($_GET['act']) && $_GET['act']=='post_delete'){}
elseif ($k_page>1)str("?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&",$k_page,$page); // Вывод страниц

if ((isset($user) && $user['id']==$gruppy['admid'] || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `mod`='1' LIMIT 1"),0)==1) && isset($_GET['act']) && $_GET['act']=='post_delete'){}
elseif (isset($user) && ($them['close']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid']))
{
echo "<form method='post' name='message' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=new&$passgen'>\n";
if (isset($_POST['msg']))$msg2=output_text($_POST['msg'],false,true,false,false,false); else $msg2=NULL;
echo "<div class='textmes'>\n";
echo "Сообщение:<br />\n<textarea name=\"msg\">$msg2</textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";

echo "<input name='post' value='Отправить' type='submit' /><br />\n";
echo "</form>\n";
echo "</div>\n";
}
?>
