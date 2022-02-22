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
$num=1;

if (isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_them']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_post']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1
)
{
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));
$post2=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_mess` WHERE `id_them` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' ORDER BY `id` DESC LIMIT 1"));
if (isset($user)){
$ank=get_user($post['id_user']);

if (isset($_GET['act']) && $_GET['act']=='edit' && isset($_POST['msg']) && isset($_POST['post']) &&
// редактирование поста
(
$user['id']==$gruppy['admid']
// права группы на редактирование
||
(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post['id_user']==$post2['id_user'])
// право на редактирование своего поста, если он поседний в теме
)
)
{


$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)<2)$err[]='Короткое сообщение';
if (strlen2($msg)>10000)$err[]='Длина сообщения превышает предел в 10000 символjd';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (!isset($err))mysql_query("UPDATE `gruppy_forum_mess` SET `mess` = '".my_esc($msg)."' WHERE `id` = '$post[id]' LIMIT 1");
}
elseif (isset($_GET['act']) && $_GET['act']=='edit' && isset($user) && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 && $post['id']==$post2['id'] && $post['id_user']==$user['id'] && $post['time']>time()-600)){

$set['title']=$gruppy['name'].' - Форум - редактирование поста'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();


echo "<form method='post' name='message' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&'>\n";
$msg2=output_text($post['mess'],false,true,false,false,false);
//echo auto_bb("message","msg");
echo "<div class='textmes'>\n";
echo "Сообщение:<br />\n<textarea name=\"msg\">".$msg2."</textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Изменить' type='submit' /><br />\n";
echo "</form>\n";
echo "</div>\n";
echo "&raquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&id_post=$post[id]&act=delete\" title='Удалить пост'>Удалить пост</a><br />\n";
echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='В раздел'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "&laquo;<a href=\"index.php?s=$gruppy[id]\" title='В сообщество'>$gruppy[name]</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['act']) && $_GET['act']=='delete' && isset($user) && $them['close']==0 && ($user['id']==$gruppy['admid'] || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 && $post['id']==$post2['id'] && $post['id_user']==$user['id'] && $post['time']>time()-600)){
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1");
header("location: ?s=$_GET[s]&id_forum=$_GET[id_forum]&id_them=$_GET[id_them]");
}
elseif (isset($_GET['act']) && $_GET['act']=='msg' && $them['close']==0 && isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
$ank=get_user($post['id_user']);
$set['title']=$gruppy['name'].' - Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();


echo "<form method='post' name='message' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=new'>\n";
echo "<div class='rekl'>\n";
echo "<font color='black'>Посмотреть:</font> <a href='/prof.php?act=see&id=$ank[id]'>анкету</a> | <a href='/info.php?id=$ank[id]'>страничку</a><br />\n";
$msg2=$ank['nick'].', ';
echo "</div>\n";
echo auto_bb("message","msg");
echo "<div class='textmes'>\n";
echo "Сообщение:<br />\n<textarea name=\"msg\">$ank[nick], </textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Отправить сообщение' type='submit' /><br />\n";
echo "</form>\n";
echo "</div>\n";
echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"/rules.php\">Правила</a><br />\n";
echo "</div>\n";

echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='В раздел'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "&laquo;<a href=\"index.php?s=$gruppy[id]\" title='В сообщество'>$gruppy[name]</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['act']) && $_GET['act']=='cit' && $them['close']==0 && isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
$ank=get_user($post['id_user']);
$set['title']='Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo "Будет процетировано сообщение:<br />\n";

echo "<div class='cit'>\n";
echo output_text($post['mess'])."<br />\n";
echo "</div>\n";
echo "<form method='post' name='message' action='?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&act=new'>\n";
echo "<input name='cit' value='$post[id]' type='hidden' />";
$msg2=$ank['nick'].', ';
//echo auto_bb("message","msg");
echo "<div class='textmes'>\n";
echo "Сообщение:<br />\n<textarea name=\"msg\">$ank[nick], </textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Отправить сообщение' type='submit' /><br />\n";
echo "</form>\n";
echo "</div>\n";

echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]&page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='В раздел'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "&laquo;<a href=\"index.php?s=$gruppy[id]\" title='В сообщество'>$gruppy[name]</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

}


}







elseif (isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_them']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1 )
{
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forum_thems` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));
$set['title']=$gruppy['name'].' - Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
$ank2=get_user($them['id_user']);


include 'inc/set_them_act.php';
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid'] || isset($user) && $user['level']>0)
{
include 'inc/them.php';
}
else
{
echo'Вы не можете просматривать темы форума данного сообщества<br/>';
}
include 'inc/set_them_form.php';

echo "<div class=\"foot\">\n";


echo "&laquo;<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='В раздел'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "&laquo;<a href=\"index.php?s=$gruppy[id]\" title='В сообщество'>В сообщество</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

elseif (isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."'"),0)==1
&& isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."'"),0)==1)
{
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '".intval($_GET['id_forum'])."' AND `id_gruppy` = '".intval($_GET['s'])."' LIMIT 1"));

if (isset($user) && isset($_GET['act']) && $_GET['act']=='new' && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 && (!isset($_SESSION['time_c_t_forum']) || $_SESSION['time_c_t_forum']<$time-600) || $user['id']==$gruppy['admid']))
include 'inc/new_t.php'; // создание новой темы
else
{
$set['title']=$gruppy['name'].' - Форум - '.$forum['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

if (isset($user) && $user['id']==$gruppy['admid'])include 'inc/set_razdel_act.php';
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid'] || isset($user) && $user['level']>0)
{
include 'inc/razdel.php';
}
else
{
echo'Вы не можете просматривать форумы данного сообщества<br/>';
}
if (isset($user) && $user['id']==$gruppy['admid'])include 'inc/set_razdel_form.php';
if (isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 && (!isset($_SESSION['time_c_t_forum']) || $_SESSION['time_c_t_forum']<$time-600) || $user['id']==$gruppy['admid']))
echo "<div class='nav1'><img src='img/5od.png' alt='' class='icon'/> <a href=\"?s=$gruppy[id]&id_forum=$forum[id]&act=new\" title='Создать новую тему'>Создать тему</a></div>\n";
echo "<div class='navi'>\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href=\"?s=$gruppy[id]&id_forum=$forum[id]\">$forum[name]</a><br />\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href=\"forum.php?s=$gruppy[id]\">Форум</a><br />\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br />\n";
echo "</div>\n";
}


include_once '../sys/inc/tfoot.php';
}

else
{
if (isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."'"),0)==1)
{
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"));
$set['title']=$gruppy['name'].' - Форум'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();


if (isset($user) && $user['id']==$gruppy['admid'] && isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='new' && isset($_POST['name']) && isset($_POST['opis']))
{

$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (preg_match("#\{|\}|\^|\%|\\$|#|@|!|\~|'|\"|`|<|>#",$name))$err='В названии форума присутствуют запрещенные символы';
if (strlen2($name)<3)$err='Слишком короткое название';
if (strlen2($name)>32)$err='Слишком днинное название';
$name=my_esc($name);


$opis=$_POST['opis'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$opis=translit($opis);
//if (strlen2($opis)<10)$err='Короткое описание';
if (strlen2($opis)>512)$err='Слишком длинное описание';
$opis=my_esc($opis);

if (!isset($err)){
mysql_query("INSERT INTO `gruppy_forums` (`desc`, `name`, `id_gruppy`) values('$opis', '$name', '$gruppy[id]')");
msg('Форум успешно создан');
}
}
err();


if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid'] || isset($user) && $user['level']>0)
{
//echo "<table class='post'>\n";
echo "<div class='mess'>";
echo "<img src='img/20od.png' alt='' class='icon'/> <a href='new_t.php?s=$gruppy[id]'>Новые темы</a> |";
echo " <img src='img/20od.png' alt='' class='icon'/> <a href='new_p.php?s=$gruppy[id]'>Новые сообщения</a>";
echo "</div>";
$q=mysql_query("SELECT * FROM `gruppy_forums` WHERE `id_gruppy`='$gruppy[id]' ORDER BY `id` ASC");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <div class='err'>\n";
echo "Нет подфорума\n";
echo "  </div>\n";
echo "   </tr>\n";
}

while ($forum = mysql_fetch_assoc($q))
{
if($num==1){
echo "<div class='nav2'>\n";
$num=0;
}else{
echo "<div class='nav1'>\n";
$num=1;}
echo "<a href='?s=$gruppy[id]&id_forum=$forum[id]'><b>$forum[name] (".mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_mess` WHERE `id_forum` = '$forum[id]' AND `id_gruppy`='$gruppy[id]'"),0).'/'.mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forum_thems` WHERE `id_forum` = '$forum[id]' AND `id_gruppy`='$gruppy[id]'"),0).")</b></a>\n";
if ($forum['desc']!=NULL){
echo "<br/>";
echo "<small>[";
echo output_text($forum['desc'])."]<br />\n";
echo "</small>";
}
echo "  </div>\n";

}
echo "</table>\n";
}
else
{
echo'Вы не можете просматривать форум данного сообщества<br/>';
}
if (isset($user) && $user['id']==$gruppy['admid'] && (isset($_GET['act']) && $_GET['act']=='new' || mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id_gruppy`='$gruppy[id]'"),0)==0))
{
echo "<form method=\"post\" action=\"?s=$gruppy[id]&act=new&amp;ok\">\n";
echo "Название подфорума:<br />\n";
echo "<input name=\"name\" type=\"text\" maxlength='32' value='' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit1\" value=\"1\" /> Транслит</label><br />\n";
echo "Описание:<br />\n";
echo "<textarea name=\"opis\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit2\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Создать\" type=\"submit\" /><br />\n";
echo "</form>\n";
}

echo "<div class=\"foot\">\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href=\"/gruppy/forum.php\">Отмена</a><br />\n";
echo "</div>\n";

if (isset($user) && $user['id']==$gruppy['admid'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_forums` WHERE `id_gruppy`='$gruppy[id]'"),0)>0){
echo "<div class=\"foot\">\n";
echo "<img src='img/20od.png' alt='' class='icon'/> <a href=\"forum.php?s=$gruppy[id]&act=new\">Новый подфорум</a><br />\n";
echo "</div>\n";
}

echo "<div class=\"foot\">\n";
echo "<img src='img/back.png' alt='' class='icon'/> <a href='index.php?s=$gruppy[id]'>В сообщество</a><br />\n";
echo "</div>\n";
}
elseif(!isset($_GET['s']) || isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."'"),0)==0)
{
header('Location:index.php');
}
}
include_once '../sys/inc/tfoot.php';
?>
