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


/* Бан пользователя */ 
if (isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE `razdel` = 'guest' AND `id_user` = '$user[id]' AND (`time` > '$time' OR `view` = '0')"), 0)!=0)
{
header('Location: /ban.php?'.SID);exit;
}

$set['title']='Гостевая книга'; // заголовок страницы

include_once '../sys/inc/thead.php';
title();

if (isset($user))
mysql_query("UPDATE `notification` SET `read` = '1' WHERE `type` = 'guest' AND `id_user` = '$user[id]'");

/*
================================
Модуль жалобы на пользователя
и его сообщение либо контент
в зависимости от раздела
================================
*/
if (isset($_GET['spam'])  && isset($user))
{
$mess = mysql_fetch_assoc(mysql_query("SELECT * FROM `guest` WHERE `id` = '".intval($_GET['spam'])."' limit 1"));
$spamer = get_user($mess['id_user']);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `spamus` WHERE `id_user` = '$user[id]' AND `id_spam` = '$spamer[id]' AND `razdel` = 'guest' AND `spam` = '".$mess['msg']."'"),0)==0)
{
if (isset($_POST['msg']))
{
if ($mess['id_user']!=$user['id'])
{
$msg=mysql_real_escape_string($_POST['msg']);

if (strlen2($msg)<3)$err='Укажите подробнее причину жалобы';
if (strlen2($msg)>1512)$err='Длина текста превышает предел в 512 символов';

if(isset($_POST['types'])) $types=intval($_POST['types']);
else $types='0'; 
if (!isset($err))
{
mysql_query("INSERT INTO `spamus` (`id_user`, `msg`, `id_spam`, `time`, `types`, `razdel`, `spam`) values('$user[id]', '$msg', '$spamer[id]', '$time', '$types', 'guest', '".my_esc($mess['msg'])."')");
$_SESSION['message'] = 'Заявка на рассмотрение отправлена'; 
header("Location: ?spam=$mess[id]&page=".intval($_GET['page'])."");
exit;
}
}
}
}
aut();
err();

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `spamus` WHERE `id_user` = '$user[id]' AND `id_spam` = '$spamer[id]' AND `razdel` = 'guest'"),0)==0)
{
echo "<div class='mess'>Ложная информация может привести к блокировке ника. 
Если вас постоянно достает один человек - пишет всякие гадости, вы можете добавить его в черный список.</div>";
echo "<form class='nav1' method='post' action='?spam=$mess[id]&amp;page=".intval($_GET['page'])."'>\n";
echo "<b>Пользователь:</b> ";
echo " ".status($spamer['id'])."  ".group($spamer['id'])." <a href=\"/info.php?id=$spamer[id]\">$spamer[nick]</a>\n";
echo "".medal($spamer['id'])." ".online($spamer['id'])." (".vremja($mess['time']).")<br />";
echo "<b>Нарушение:</b> <font color='green'>".output_text($mess['msg'])."</font><br />";
echo "Причина:<br />\n<select name='types'>\n";
echo "<option value='1' selected='selected'>Спам/Реклама</option>\n";
echo "<option value='2' selected='selected'>Мошенничество</option>\n";
echo "<option value='3' selected='selected'>Оскорбление</option>\n";
echo "<option value='0' selected='selected'>Другое</option>\n";
echo "</select><br />\n";
echo "Комментарий:";
echo $tPanel."<textarea name=\"msg\"></textarea><br />";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";
}else{
echo "<div class='mess'>Жалоба на <font color='green'>$spamer[nick]</font> будет рассмотрена в ближайшее время.</div>";
}

echo "<div class='foot'>\n";
echo "<img src='/style/icons/str2.gif' alt='*'> <a href='?page=".intval($_GET['page'])."'>Назад</a><br />\n";
echo "</div>\n";
include_once '../sys/inc/tfoot.php';
}
/*
==================================
The End
==================================
*/

include 'inc/admin_act.php';

if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){

		/*
		==========================
		Уведомления об ответах
		==========================
		*/
		if (isset($user) && $respons==TRUE){
		$notifiacation=mysql_fetch_assoc(mysql_query("SELECT * FROM `notification_set` WHERE `id_user` = '".$ank_otv['id']."' LIMIT 1"));
			
			if ($notifiacation['komm'] == 1 && $ank_otv['id'] != $user['id'])
			mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `type`, `time`) VALUES ('$user[id]', '$ank_otv[id]', 'guest', '$time')");
		
		}



mysql_query("INSERT INTO `guest` (id_user, time, msg) values('$user[id]', '$time', '".my_esc($msg)."')");// ---------------- Запись в ленту сайта ----------------- //$set_lenta = mysql_fetch_assoc(mysql_query("SELECT `guest` FROM `lenta_site_set`"));if($set_lenta['guest'] == 1) mysql_query("INSERT INTO `lenta_site` SET `id_user` = '$user[id]', `opis` = ' оставил сообщение в', `title` = 'гостевой', `link` = 'guest/', `time` = '$time'");// ------------------------ Конец ----------------------- //
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."',  `rating_tmp` = '".($user['rating_tmp']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['message']='Сообщение успешно добавлено';
header ("Location: ?");
}
}


err();
aut(); // форма авторизации

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `guest`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];


if (isset($user) && (!isset($_SESSION['antiflood']) || $_SESSION['antiflood']<$time-300))
{
echo "<form method=\"post\" name='message' action=\"index.php?$passgen&amp;".$go_otv."\">";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
{
echo $tPanel . '<textarea name="msg">'.$respons_msg.'</textarea><br />';

}
echo '<input value="Отправить" type="submit" />';
echo "</form>";
}



echo "<table class='post'>";

if ($k_post==0)
{
echo "  <div class='mess'>\n";
echo "Нет сообщений\n";
echo "  </div>\n";
}

$q=mysql_query("SELECT * FROM `guest` ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{

$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));

/*-----------зебра-----------*/
if ($num==0)
{echo '<div class="nav1">';
$num=1;
}elseif ($num==1)
{echo '<div class="nav2">';
$num=0;}
/*---------------------------*/

if ($set['set_show_icon']==2){
avatar($ank['id']);
}
elseif ($set['set_show_icon']==1)
{
//echo status($ank['id']);
}


echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a>";
if (isset($user))echo " <a href='?response=$ank[id]'>[*]</a> ";
echo medal($ank['id']) . online($ank['id']).' ('.vremja($post['time']).')<br />';

$postBan = mysql_result(mysql_query("SELECT COUNT(*) FROM `ban` WHERE (`razdel` = 'all' OR `razdel` = 'guest') AND `post` = '1' AND `id_user` = '$ank[id]' AND (`time` > '$time' OR `navsegda` = '1')"), 0);
if ($postBan == 0) // Блок сообщения
{	
/*------------Вывод статуса-------------*/
$status=mysql_fetch_assoc(mysql_query("SELECT * FROM `status` WHERE `pokaz` = '1' AND `id_user` = '$ank[id]' LIMIT 1"));
if ($status['msg'] && $set['st']==1)
{
echo '<div class="st_1"></div>';
echo '<div class="st_2">';
echo output_text($status['msg']);
echo '</div>';
}
/*---------------------------------------*/


echo output_text($post['msg']).'<br />';
}else{
	echo output_text($banMess).'<br />';
}

if (isset($user))
{
echo "<div style='text-align:right;'>";
if ($ank['id']!=$user['id'])echo "<a href=\"?spam=$post[id]&amp;page=$page\"><img src='/style/icons/blicon.gif' alt='*' title='Это спам'></a> "; 

if (user_access('guest_delete'))
echo " <a href='delete.php?id=$post[id]'><img src='/style/icons/delete.gif' alt='*'></a>";

echo '</div>';
}

echo '</div>';
}
echo '</table>';



if ($k_page>1)str('index.php?',$k_page,$page); // Вывод страниц



echo '<div class="foot">';
echo '<img src="/style/icons/str.gif" alt="*"> <a href="who.php">Кто здесь?</a><br />';
echo '</div>';
include 'inc/admin_form.php';
include_once '../sys/inc/tfoot.php';
?>
