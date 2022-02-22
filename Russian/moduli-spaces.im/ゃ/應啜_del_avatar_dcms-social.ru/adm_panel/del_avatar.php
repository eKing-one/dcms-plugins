<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';

$set['title']='Админка - Удаление аваторов пользователей';
include_once '../sys/inc/thead.php';
title();
err();
aut();

switch($_GET['act']){

case 'del':

$user = $_POST['user'];

$result = mysql_query('SELECT id FROM user WHERE nick="'.$user.'"');
list($id) = mysql_fetch_array($result);
	echo "<div class='menu'>\n";
	echo "Текущий аватар пользователя ".$_POST['user'].":<br />";
	avatar($id);
	echo "<br />\n";
	echo "Вы действительно хотите удалить аватар пользователя?<br />";
	echo "<form action='?act=accept_del' method='post'>\n";
	echo "<input type='hidden' value='".$id."' name='id'/>\n";
	echo "<input type='hidden' value='".$user."' name='user'/>\n";
	echo "<input type='submit' value='Да'/> <a href='index.php'>Нет</a><br />";
	echo "</form>\n";
	echo "</div>\n";
break;

case 'accept_del':

$user = $_POST['user'];
$id = $_POST['id'];

@chmod(H."sys/avatar/".$id.".jpg",0777);
@chmod(H."sys/avatar/".$id.".gif",0777);
@chmod(H."sys/avatar/".$id.".png",0777);
@unlink(H."sys/avatar/".$id.".jpg");
@unlink(H."sys/avatar/".$id.".gif");
@unlink(H."sys/avatar/".$id.".png");

copy(H."sys/avatar/default.png", H."sys/avatar/$id.png");
	echo "<div class='menu'>\n";
	echo "Аватар пользователя ".$user." был успешно удален!";
	echo "</div>\n";
break;

default:
echo "<div class='menu'>\n";
echo "Введите ник пользователя у которого удаляем аватар:<br />";
echo "<form action='?act=del' method='post'>\n";
echo "<input type='text' name='user'/><br />";
echo "<input type='submit' value='Удалить'/><br />";
echo "</form>\n";
echo "</div>\n";
break;
}
echo"<a href='http://wmclass.lark.ru'>На благое дело<br/> Автор:Xokano </a>";
include_once '../sys/inc/tfoot.php';
?>