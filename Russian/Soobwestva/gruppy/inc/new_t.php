<?
$set['title']=$gruppy['name'].' - Форум - '.$forum['name'].' - '.@$razdel['name'].' - Новая тема'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

if (isset($_POST['name']) && isset($_POST['msg']))
{

if (isset($_SESSION['time_c_t_forum']) && $_SESSION['time_c_t_forum']>$time-600 && $user['id']!=$gruppy['admid'] )$err='Нельзя так часто создавать темы';

$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
//if (ereg("\{|\}|\^|\%|\\$|#|@|!|\~|'|\"|`|<|>",$name))$err='В названии темы присутствуют запрещенные символы';
if (strlen2($name)<3)$err[]='Короткое название для темы';
if (strlen2($name)>32)$err[]='Название темы не должно быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В названии темы обнаружен мат: '.$mat;


$name=my_esc($name);


$msg=$_POST['msg'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$msg=translit($msg);
if (strlen2($msg)<3)$err[]='Короткое сообщение';
if (strlen2($msg)>10000)$err[]='Длина сообщения превышает предел в 10000 символов';

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;
$msg=my_esc($msg);
if (!isset($err))
{
$_SESSION['time_c_t_forum']=$time;
mysql_query("INSERT INTO `gruppy_forum_thems` (`id_forum`, `id_gruppy`, `time_create`, `id_user`, `name`, `time`) values('$forum[id]', '$gruppy[id]', '$time', '$user[id]', '$name', '$time')");
$them['id']=mysql_insert_id();
mysql_query("INSERT INTO `gruppy_forum_mess` (`id_forum`, `id_gruppy`, `id_them`, `id_user`, `mess`, `time`) values('$forum[id]', '$gruppy[id]', '$them[id]', '$user[id]', '$msg', '$time')");
msg('Тема успешно создана');

echo "<div class='foot'>\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]&id_them=$them[id]\" title='Перейти в тему'>Перейти в тему</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='Вернуться к списку тем'>Назад</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"forum.php?s=$gruppy[id]\">Форум</a><br />\n";
echo "</div>\n";
echo "<div class='foot'>\n";
echo "<a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}

}

err();
echo "<form method=\"post\" action=\"?s=$gruppy[id]&id_forum=$forum[id]&act=new\">";
echo "Название темы:<br />\n";
echo "<input name=\"name\" type=\"text\" maxlength='32' value='' /><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit1\" value=\"1\" /> Транслит</label><br />\n";
echo "Сообщение:<br />\n";
echo "<textarea name=\"msg\"></textarea><br />\n";
if ($user['set_translit']==1)echo "<label><input type=\"checkbox\" name=\"translit2\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Создать\" type=\"submit\" /><br />\n";
echo "</form>\n";

echo "<div class=\"foot\">\n";
echo "<a href=\"?s=$gruppy[id]&id_forum=$forum[id]\" title='Вернуться к списку тем'>Назад</a><br />\n";
echo "<a href=\"forum.php?s=$gruppy[id]\">Форум</a><br />\n";
echo "<a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br />\n";
echo "</div>\n";
?>
