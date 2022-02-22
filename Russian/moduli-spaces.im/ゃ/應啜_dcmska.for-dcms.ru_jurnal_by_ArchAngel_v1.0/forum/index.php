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





if (isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f` WHERE".((!isset($user) || $user['level']==0)?" `adm` = '0' AND":null)." `id` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_razdel']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_them']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_post']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1
)
{
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$post2=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_p` WHERE `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' ORDER BY `id` DESC LIMIT 1"));
if (isset($user)){
$ank=get_user($post['id_user']);

if (isset($_GET['act']) && $_GET['act']=='edit' && isset($_POST['msg']) && isset($_POST['post']) &&
 // редактирование поста
(
(user_access('forum_post_ed')) 
// права группы на редактирование
||
(isset($user) && $user['id']==$post['id_user'] && $post['time']>time()-600 && $post['id_user']==$post2['id_user']) 
// право на редактирование своего поста, если он поседний в теме
)
)
{


$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)<2)$err[]='Короткое сообщение';
if (strlen2($msg)>1024)$err[]='Длина сообщения превышает предел в 1024 символа';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (!isset($err))mysql_query("UPDATE `forum_p` SET `msg` = '".my_esc($msg)."' WHERE `id` = '$post[id]' LIMIT 1");
}
elseif (isset($_GET['act']) && $_GET['act']=='edit' && (user_access('forum_post_ed') && ($ank['level']<$user['level'] || $ank['level']==$user['level'] && $ank['id']==$user['id']) || isset($user) && $post['id']==$post2['id'] && $post['id_user']==$user['id'] && $post['time']>time()-600)){

$set['title']='Форум - редактирование поста'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();


echo "<form method='post' name='message' action='/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/edit'>\n";
$msg2=output_text($post['msg'],false,true,false,false,false);
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\">".$msg2."</textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Изменить' type='submit' /><br />\n";
echo "</form>\n";
echo "&raquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/$post[id]/delete\" title='Удалить пост'>Удалить пост</a><br />\n";
echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/\" title='В раздел'>$razdel[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/\" title='В подфорум'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['act']) && $_GET['act']=='delete' && isset($user) && $them['close']==0 && ((user_access('forum_post_ed') && ($ank['level']<=$user['level'] || $ank['level']==$user['level'] && $ank['id']==$user['id'])) || $post['id']==$post2['id'] && $post['id_user']==$user['id'] && $post['time']>time()-600)){
mysql_query("DELETE FROM `forum_p` WHERE `id` = '".intval($_GET['id_post'])."' AND `id_them` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1");
}
elseif (isset($_GET['act']) && $_GET['act']=='msg' && $them['close']==0 && isset($user)){
$ank=get_user($post['id_user']);
$set['title']='Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();


echo "<form method='post' name='message' action='/forum/$forum[id]/$razdel[id]/$them[id]/new?ud=$ank[id]&amp;otv=$user[id]'>\n";
echo "<a href='/info.php?id=$ank[id]'>Посмотреть анкету</a><br />\n";
$msg2=$ank['nick'].', ';
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\">$ank[nick], </textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Отправить сообщение' type='submit' /><br />\n";
echo "</form>\n";
echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"/smiles.php\">Смайлы</a><br />\n";
echo "&raquo;<a href=\"/rules.php\">Правила</a><br />\n";
echo "</div>\n";

echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/\" title='В раздел'>$razdel[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/\" title='В подфорум'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";

echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
elseif (isset($_GET['act']) && $_GET['act']=='cit' && $them['close']==0 && isset($user)){
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
$ank=get_user($post['id_user']);
$set['title']='Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
echo "Будет процетировано сообщение:<br />\n";

echo "<div class='cit'>\n";
echo output_text($post['msg'])."<br />\n";
echo "</div>\n";
echo "<form method='post' name='message' action='/forum/$forum[id]/$razdel[id]/$them[id]/new'>\n";
echo "<input name='cit' value='$post[id]' type='hidden' />";
$msg2=$ank['nick'].', ';
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name=\"msg\">$ank[nick], </textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input name='post' value='Отправить сообщение' type='submit' /><br />\n";
echo "</form>\n";


echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=end\" title='Вернуться в тему'>В тему</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/\" title='В раздел'>$razdel[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/\" title='В подфорум'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

}


}







if (isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f` WHERE".((!isset($user) || $user['level']==0)?" `adm` = '0' AND":null)." `id` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_razdel']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_them']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1 )
{
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$them=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_t` WHERE `id` = '".intval($_GET['id_them'])."' AND `id_razdel` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$set['title']='Форум - '.$them['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
$ank2=get_user($them['id_user']);


include 'inc/set_them_act.php';

include 'inc/them.php';


include 'inc/set_them_form.php';

echo "<div class=\"foot\">\n";



if (isset($user))
{
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_them` = '$them[id]' AND `id_user` = '$user[id]'"),0)==0)
echo "&raquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=$page&amp;zakl=1\" title='Добавить в закладки'>В закладки</a><br />\n";
else
{
mysql_query("UPDATE `forum_zakl` SET `time` = '".time()."' WHERE `id_them` = '$them[id]' AND `id_user` = '$user[id]'");
echo "&raquo;<a href=\"/forum/$forum[id]/$razdel[id]/$them[id]/?page=$page&amp;zakl=0\" title='Удалить из закладок'>Удалить из закладок</a><br />\n";
}
}
echo "&laquo;<a href=\"/forum/$forum[id]/$razdel[id]/\" title='В раздел'>$razdel[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/\" title='В подфорум'>$forum[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

if (isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f` WHERE".((!isset($user) || $user['level']==0)?" `adm` = '0' AND":null)." `id` = '".intval($_GET['id_forum'])."'"),0)==1
&& isset($_GET['id_razdel']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."'"),0)==1)
{
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '".intval($_GET['id_forum'])."' LIMIT 1"));
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_r` WHERE `id` = '".intval($_GET['id_razdel'])."' AND `id_forum` = '".intval($_GET['id_forum'])."' LIMIT 1"));

if (isset($user) && isset($_GET['act']) && $_GET['act']=='new' && (!isset($_SESSION['time_c_t_forum']) || $_SESSION['time_c_t_forum']<$time-600 || $user['level']>0))
include 'inc/new_t.php'; // создание новой темы
else
{
$set['title']='Форум - '.$razdel['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

if (user_access('forum_razd_edit'))include 'inc/set_razdel_act.php';

include 'inc/razdel.php';

if (user_access('forum_razd_edit'))include 'inc/set_razdel_form.php';

echo "<div class=\"foot\">\n";
if (isset($user) && (!isset($_SESSION['time_c_t_forum']) || $_SESSION['time_c_t_forum']<$time-600 || $user['level']>0))
echo "&raquo;<a href=\"/forum/$forum[id]/$razdel[id]/?act=new\" title='Создать новую тему'>Новая тема</a><br />\n";
echo "&laquo;<a href=\"/forum/$forum[id]/\">$forum[name]</a><br />\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
}


include_once '../sys/inc/tfoot.php';
}

if (isset($_GET['id_forum']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f` WHERE".((!isset($user) || $user['level']==0)?" `adm` = '0' AND":null)." `id` = '".intval($_GET['id_forum'])."'"),0)==1)
{
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `forum_f` WHERE `id` = '".intval($_GET['id_forum'])."' LIMIT 1"));

$set['title']='Форум - '.$forum['name']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

include 'inc/set_forum_act.php'; // действия над подфорумом

include 'inc/forum.php'; // содержимое

include 'inc/set_forum_form.php'; // формы действий над подфорумом


echo "<div class=\"foot\">\n";
echo "&laquo;<a href=\"/forum/\">Форум</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}


$set['title']='Форум'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();


if (user_access('forum_for_create') && isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='new' && isset($_POST['name']) && isset($_POST['opis']) && isset($_POST['pos']))
{

$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (ereg("\{|\}|\^|\%|\\$|#|@|!|\~|'|\"|`|<|>",$name))$err='В названии форума присутствуют запрещенные символы';
if (strlen2($name)<3)$err='Слишком короткое название';
if (strlen2($name)>32)$err='Слишком днинное название';
$name=my_esc($name);


$opis=$_POST['opis'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$opis=translit($opis);
//if (strlen2($opis)<10)$err='Короткое описание';
if (strlen2($opis)>512)$err='Слишком длинное описание';
$opis=my_esc($opis);

$pos=intval($_POST['pos']);
if (!isset($err)){
admin_log('Форум','Подфорумы',"Создание подфорума '$name'");
mysql_query("INSERT INTO `forum_f` (`opis`, `name`, `pos`) values('$opis', '$name', '$pos')");
msg('Подфорум успешно создан');
}
}



err();
aut(); // форма авторизации


echo "<table class='post'>\n";



$q=mysql_query("SELECT * FROM `forum_f`".((!isset($user) || $user['level']==0)?" WHERE `adm` = '0'":null)." ORDER BY `pos` ASC");
if (mysql_num_rows($q)==0) {
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет подфорумов\n";
echo "  </td>\n";
echo "   </tr>\n";
}
while ($forum = mysql_fetch_assoc($q))
{
echo "   <tr>\n";
if ($set['set_show_icon']==2)
{
echo "  <td class='icon48' rowspan='2'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/48/forum.png' />";
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/forum/14/forum.png' alt='' />";
echo "  </td>\n";
}


echo "  <td class='p_t'>\n";
echo "<a href='/forum/$forum[id]/'>$forum[name] (".mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_p` WHERE `id_forum` = '$forum[id]'"),0).'/'.mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_t` WHERE `id_forum` = '$forum[id]'"),0).")</a>\n";
echo "  </td>\n";
echo "   </tr>\n";

echo "   <tr>\n";

if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
if ($forum['opis']!=NULL)echo output_text($forum['opis'])."<br />\n"; 
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";

if (user_access('forum_for_create') && (isset($_GET['act']) && $_GET['act']=='new' || mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f`"),0)==0))
{
echo "<form method=\"post\" action=\"/forum/index.php?act=new&amp;ok\">\n";
echo "Название подфорума:<br />\n";
echo "<input name=\"name\" type=\"text\" maxlength='32' value='' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit1\" value=\"1\" /> Транслит</label><br />\n";
echo "Описание:<br />\n";
echo "<textarea name=\"opis\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit2\" value=\"1\" /> Транслит</label><br />\n";
echo "Позиция:<br />\n";
$pos=mysql_result(mysql_query("SELECT MAX(`pos`) FROM `forum_f`"), 0)+1;
echo "<input name=\"pos\" type=\"text\" maxlength='3' value='$pos' /><br />\n";
echo "<input value=\"Создать\" type=\"submit\" /><br />\n";
echo "&laquo;<a href=\"/forum/\">Отмена</a><br />\n";
echo "</form>\n";
}

if (user_access('forum_for_create') && mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_f`"),0)>0){
echo "<div class=\"foot\">\n";
echo "&raquo;<a href=\"/forum/?act=new\">Новый подфорум</a><br />\n";
echo "</div>\n";
}

echo "<div class=\"foot\">\n";
echo "&raquo;<a href='/forum/search.php'>Поиск</a><br />\n";
echo "&raquo;<a href='/forum/new_t.php'>Новые темы</a><br />\n";
echo "&raquo;<a href='/forum/new_p.php'>Темы с новыми сообщениями</a><br />\n";
echo "&raquo;<a href='/forum/files_list.php'>TOP 20 файлов</a><br />\n";
echo "&raquo;<a href='/rules.php'>Правила</a><br />\n";
echo "</div>\n";


include_once '../sys/inc/tfoot.php';
?>