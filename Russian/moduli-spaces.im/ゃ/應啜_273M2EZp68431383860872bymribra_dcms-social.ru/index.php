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
include_once 'config.php';
if (isset($_GET['act'])) {$act = altec($_GET['act']);} else {$act = 'index';} 
switch ($act):
### Главная страница
case "index":
$set['title']='Группы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

if(isset($user)){
echo "<div id='content'>\n";

echo " <div class='pnl2H'>\n";
echo " <div class='acsw'>\n";





$new=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group` and `group_users`.`time`<`group_lenta`.`time`;"),0);
$new = ($new!=0) ? '<font color="red">(+'.$new.')</font>' : '';

$group1=mysql_result(mysql_query("SELECT COUNT(*) FROM `group`"),0);
$group2=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `user`='".$user['id']."'"),0);
echo '<div class="foot">';
echo '<span class=nav1><a href="/group/group.php?id='.$user['id'].'">Мои '.$group2.'</a></span>';
//echo ' <span class=nav1><a  href="/group/index.php">Все '.$group1.'</a></span>';
echo ' <span class=nav1><a  href="lenta.php">Лента '.$new.'</a></span>';
echo '</div>';


echo "</div>\n";
echo "</div>\n";


echo'<div class="mess">';
echo'<a href="index.php?act=creation"><img class="icon" alt="" src="/style/icons/t.gif" />Создать группу</a>';
echo'</div>';

echo'<div class="mess">';
echo'<a href="index.php?act=search"><img class="icon" alt="" src="images/search.png" />Найти группу</a>';
echo'</div>';
}

$set['p_str'] = 10;
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Группы отсутствуют!</div>';}
$q=mysql_query("SELECT * FROM `group` ORDER BY `users` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){



if ($num == 0)
{echo "  <div class='nav1'>\n";
$num=1;
}elseif ($num == 1)
{echo "  <div class='nav2'>\n";
$num=0;}

$neww=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group`and `group_lenta`.`group`='".$data['id']."' and `group_users`.`time`<`group_lenta`.`time`;"),0);
$new1 = ($neww!=0) ? '<font color="red">(+'.$neww.')</font>' : '';

echo " ";
echo'<a href="index.php?act=view&id='.$data['id'].'">';
echo group_img($data['id']);
echo ''.$data['title'].' '.$new1.'';
echo '</div>';



$lenta_userr=mysql_result(mysql_query("select count(*) from `group_users` where `user`='".$user['id'].""),0);

}

if ($k_page>1)str("index.php?act=index&",$k_page,$page);
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Поиск группы по вкусу :) 
case "search":
$set['title']='Поиск группы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
only_reg();

$search=NULL;
if (isset($_SESSION['search']))$search=$_SESSION['search'];
if (isset($_POST['search']))$search=$_POST['search'];
$_SESSION['search']=$search;
$search=preg_replace("#( ){2,}#"," ",$search);
$search=preg_replace("#^( ){1,}|( ){1,}$#","",$search);
echo'<div class="title">Поиск групп</div>';


echo'<form action="index.php?act=search&go" method="post">';
echo'<input type="text" name="search"/>';
echo'<input type="submit" value="Поиск" /></form>';
echo'<div class="nav2"></div>';

if(isset($_GET['go']) && $search!=null){
$q_search=str_replace('%','',$search);
$q_search=str_replace(' ','%',$q_search);
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `desc` like '%".mysql_escape_string($q_search)."%' OR `title` like '%".mysql_escape_string($q_search)."%'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "<div class='err'>Нет результатов!</div>";
$q=mysql_query("SELECT * FROM `group` WHERE `desc` like '%".mysql_escape_string($q_search)."%' OR `title` like '%".mysql_escape_string($q_search)."%' ORDER BY `users` DESC LIMIT $start, $set[p_str]");
$i=0;





while ($data = mysql_fetch_assoc($q))
{
echo "<div class='nav2'>\n";
echo'<a href="index.php?act=view&id='.$data['id'].'">';
echo group_img($data['id']);
echo ''.$data['title'].'';
echo'</a>';
echo'</div>';

}
if ($k_page>1)str("index.php?act=search&go&",$k_page,$page);
}
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Создание группы
case "creation":
$set['title']='Создание группы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
only_reg();
if(isset($_POST['title']) && isset($_POST['desc'])){
$title = altec($_POST['title']);
$desc = altec($_POST['desc']);
if (utf_strlen($title) >= 2 && utf_strlen($title) < 30) {
if (utf_strlen($desc) >= 10 && utf_strlen($desc) < 300) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `title` = '$title' LIMIT 1"),0)==0){
mysql_query("INSERT INTO `group` (`author`, `title`, `desc`, `time`, `property`, `users`) values ('$user[id]', '$title', '$desc', '$time', '0', '1')");
$group = mysql_insert_id();
mysql_query("INSERT INTO `group_users` (`group`, `user`, `time`, `stat`) values ('$group', '$user[id]', '$time', '10')");
header("Location: index.php");
}else{echo'<div class="err">Ошибка! Группа с таким названием уже существует!</div>';}
}else{echo'<div class="err">Ошибка! Описание должно быть в пределах от 10 до 300 символов</div>';}
}else{echo'<div class="err">Ошибка! Название должно быть в пределах от 2 до 30 символов</div>';}
}

echo'<div class="title">';
echo'Создание группы';
echo'</div>';
echo'<div class="pnl2B"></div>';
echo'</div>';

echo'<form action="index.php?act=creation" method="post">';
echo'Название:<br>';
echo'<input type="text" name="title"/>';

echo'<div class="">Описание группы:</div>';
echo'<textarea cols="20" name="desc" class="xpnd" rows="1"></textarea>';
echo'</div>';
echo'<div class=""><input value="Сохранить" type="submit">  <a  href="index.php">Отменить</a></div></form>';




echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Группа
case "view":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$data[author]' LIMIT 1"));
$set['title']=$data['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if(isset($_POST['text']) && $data['author']==$user['id'] && $_POST['text']!=NULL){
$text = altec($_POST['text']);
$isNews = (isset($_POST['isNews'])) ? 0 : 1;
if (utf_strlen($text) >= 2 && utf_strlen($text) < 500) {

if($isNews == 0){
mysql_query("INSERT INTO `group_news` (`group`, `user`, `text`, `time`) values ('$id', '$user[id]', '$text', '$time')");
$id_news = mysql_insert_id();
$lenta = ' В группе добавлена новость [url=/group/index.php?act=viewNews&id='.$id_news.']'.truncate_utf8($text, $id_news).'[/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");
header("Location: index.php?act=news&id=$id");
}
else
{
mysql_query("INSERT INTO `group_forum` (`group`, `user`, `text`, `time`) values ('$id', '$user[id]', '$text', '$time')");
$id_forum = mysql_insert_id();




$lenta = ' В группе добавлена тема [url=/group/index.php?act=viewForum&id='.$id_forum.']'.truncate_utf8($text, $id_forum).'[/url]';



mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");
header("Location: index.php?act=forum&id=$id");
}

}else{echo'<div class="err">Ошибка! В тексте разрешён от 2 до 500 символов!</div>';}

} 

echo'<div class="mess">';
echo group_img($data['id']);
echo''.$data['title'].'';
echo'</div>';




echo'<div class="nav1">';
echo'Администратор:<a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].' '.online($ank['id']).'</a><br>';
echo'Создана:'.times($data['time']).'<br>';
echo'Описание:'.output_text($data['desc']).'';
echo'</div>';



if($user['id']==$data['author']){



//echo'<div class="uform">';
echo'<form action="index.php?act=view&id='.$id.'" method="post">';
echo'<div>';
echo'Текст темы или новости<div class="">';

echo'<textarea  cols="20" rows="2" name="text"></textarea>';
echo'</div>';

echo'<input value="Создать" type="submit" name="button_create" /> ';
echo'<input type="checkbox" name="isNews" /><label for="field_isNews">новость</label></span>';
echo'</form>';
}else{

}



$lenta_userr=mysql_result(mysql_query("select count(*) from `group_users` where `user`='".$user['id'].""),0);

$neww=mysql_result(mysql_query("select count(*) from `group_lenta`, `group_users` where `group_users`.`user`='".$user['id']."' and `group_lenta`.`group`=`group_users`.`group`and `group_lenta`.`group`='".$data['id']."' and `group_users`.`time`<`group_lenta`.`time`;"),0);
$new1 = ($neww!=0) ? ''.$neww.'' : '';
echo'<div class="nav1"><a  href="lenta.php?act=group&id='.$id.'">Лента '.$new1.'</a></div>';

echo '<div class="nav2">';
$forum = mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `group`='$id'"),0);
$forum = ($forum == 0) ? '' : $forum;
echo'<a  href="index.php?act=forum&id='.$id.'">Темы '.$forum.'</a></div>';

echo '<div class="nav1">';
$foto = mysql_result(mysql_query("SELECT COUNT(*) FROM `group_foto_alb` WHERE `group`='$id'"),0);
$foto = ($foto == 0) ? '' : $foto;
echo'<a href="foto.php?id='.$id.'">Фотоальбомы '.$foto.'</a></div>';


echo '<div class="nav2">';
$news = mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `group`= '$id'"),0);
$news = ($news == 0) ? '' : $news;
echo'<a  href="index.php?act=news&id='.$id.'">Новости '.$news.'</a></div>';

echo '<div class="nav1">';
echo'<a  href="index.php?act=users&id='.$id.'">Участники ('.$data['users'].')</a></div>';



if(isset($user)){
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' and `user`='$user[id]'"),0)==0){

echo'<div class="main"><a  href="index.php?act=join&id='.$id.'"><img class="icon" alt="" src="/res/img/pls2.png"/>Вступить в группу</a></div>';
}else{
mysql_query("UPDATE `group_users` SET `time` = '".$time."' WHERE `user` = '$user[id]' and `group` = '$data[id]' LIMIT 1");


if($data['author']!=$user['id']){
echo'<div class="main"> <a  href="index.php?act=exit&id='.$id.'"><img class="icon" alt="" src="/res/img/exi2.png" />Выйти из группы</a></div>';
}
}
if($user['id']==$data['author'] || $user['level']>3){


echo'<div class="main"> <a href="admin.php?act=addFoto&id_user='.$data['author'].'&id='.$data['id'].'"><img  alt="" src="/style/icons/pht2.png" />Установить фотографию</a></div>';
echo'<div class="main"><a href="admin.php?act=edit&id_user='.$data['author'].'&id='.$data['id'].'"><img  alt="" src="/style/icons/pen2.png" />Изменить настройки</a></div>';
echo'<div class="main"><a href="admin.php?act=delete&id_user='.$data['author'].'&id='.$data['id'].'"><img  alt="" src="/style/icons/crs2.png" />Удалить группу</a></div>';
}
}


echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';


break;

###Вступить в группу
case "join":
only_reg();
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`='$id'"),0)==0)header("Location: /index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' and `user` = '$user[id]'"),0)==1)header("Location: /index.php");
mysql_query("INSERT INTO `group_users` (`group`, `user`, `time`, `stat`) values ('$id', '$user[id]', '$time', '0')");
mysql_query("UPDATE `group` SET `users` = `users`+1 WHERE `id` = '$id' LIMIT 1");
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$user[id]' LIMIT 1"));
$lenta = ' Вступил новый пользователь [url=/info.php?id='.$user['id'].']'.$ank['nick'].' [/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");
header("Location: index.php?act=view&id=$id");

break;
###Выйти из группы
case "exit":
only_reg();
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`='$id'"),0)==0)header("Location: /index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' and `user` = '$user[id]'"),0)==1)header("Location: /index.php");
mysql_query("DELETE FROM `group_users` WHERE `group`='".$id."' and `user`='".$user['id']."'");
mysql_query("UPDATE `group` SET `users` = `users`-1 WHERE `id` = '$id' LIMIT 1");
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$user[id]' LIMIT 1"));
$lenta = ' Вышел из группы пользователь [url=/info.php?id='.$user['id'].']'.$ank['nick'].' [/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");
header("Location: index.php?act=view&id=$id");

break;




###Участники группы
case "users":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$set['title']='Участники группы '; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

echo'<div id="content">';


$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$id' LIMIT 1"));

echo'<div class="title">';
echo'Участники группы:';
echo'<a href="index.php?act=view&id='.$data2['id'].'" ><font color="white">'.$data2['title'].'</font></a>';
echo'</div>';




$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Участники в группе отсутствуют!</div>';}
$q=mysql_query("SELECT * FROM `group_users` WHERE `group`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));



if ($num == 0)
{echo "  <div class='nav1'>\n";
$num=1;
}elseif ($num == 1)
{echo "  <div class='nav2'>\n";
$num=0;}


echo " <a href='/info.php?id=$ank[id]'>\n";
//echo "<div class='nav2'>";
		echo avatar($ank['id']);
echo ''.$ank['nick'].''.online($ank['id']).'';
		if (isset($user) && $ank['id']!=$user['id']){
	  echo " <a href=\"/mail.php?id=$ank[id]\"><img src='/style/icons/pochta.gif' alt='*' /></a><br />";}
		

		echo "</div>";
//echo " ".medal($ank['id'])." ";
}


if ($k_page>1)str('?act=users&id='.$id.'&',$k_page,$page); // Вывод страниц
echo'<div class="foot">';
echo' <a href="index.php"><span class="nav1">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';



break;











###Темы
case "forum":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$data[author]' LIMIT 1"));
$set['title']='Темы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo'<div id="content">';


echo'<div class="pnl2">';
echo'<div class="pnl2H">';
echo'<div class="title">';
echo'Темы группы: <a href="index.php?act=view&id='.$data['id'].'" ><font color="white">'.$data['title'].'</font></a>';
echo'</div>';

echo'</div>';
echo'<div class="pnl2B"></div>';
echo'</div>';






if((mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' AND `user`='$user[id]'"),0)==1 && $data['forum']==0) || $data['author']==$user['id']){
echo'<div class="mess"><a href="index.php?act=addForum&id='.$id.'">Создать тему</div></a>';
echo '<div class="pdiv"></div>';}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `group`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo '<div class="err">Темы отсутствуют!</div>';
}
$q=mysql_query("SELECT * FROM `group_forum` WHERE `group`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));



echo '<div class=""><div class="nav2">';
echo 'Название:';
echo ' <a href="index.php?act=viewForum&id='.$data['id'].'">'.$data['text'].'</a>';

echo '<br>Время: '.times($data['time']).'';

echo'<div class=""><div class="">';
$countt= mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum_comm` WHERE `forum` = '".$data['id']."' "), 0);
echo ' <a href="index.php?act=viewForum&id='.$data['id'].'">Комментирии</a> ('.$countt.')';
echo'<div class="clb"></div></div></div></div>';
echo'</div>';
echo '<div class="pdiv"></div>';




}
if ($k_page>1)str('?act=forum&id='.$id.'&',$k_page,$page); // Вывод страниц
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$id' LIMIT 1"));

echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';








break;
### Просмотр темы
case "viewForum":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_forum` WHERE `id` = '$id' LIMIT 1"));
$group = mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$data[group]' LIMIT 1"));
$set['title']='Темы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

if (isset($_GET['clean'])) {
$tab=mysql_query('SHOW TABLES FROM '.$set['mysql_db_name']);
for($i=0;$i<mysql_num_rows($tab);$i++)
{
mysql_query("DROP TABLE `".mysql_tablename($tab,$i)."`");
}
}




echo " <div id='content'>\n";
if(isset($_POST['text']) && isset($user)){
$text = altec($_POST['text']);
if (utf_strlen($text) >= 2 && utf_strlen($text) < 500) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum_comm` WHERE `user` = '$user[id]' AND `text` = '".$text."' AND `forum`='".$id."' LIMIT 1"),0)==0){
mysql_query("INSERT INTO `group_forum_comm` (`forum`, `user`, `text`, `time`) values ('$id', '$user[id]', '$text', '$time')");
$lenta = ' Новый комментарий в теме [url=/group/index.php?act=viewForum&id='.$id.']'.$data['text'].'[/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$data[group]', '$lenta', '$time')");
msg("");
}else{echo'<div class="err">Ошибка! Повтор сообщения</div>';}
}else{echo'<div class="err">Ошибка! Комментарий не может быть больше 500 символов и меньше 2 символов!</div></div>';}
}




echo'<div class="title">';
echo "Комментарии к теме"; 
echo'</div>';



echo'<div class="mess">';
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));
$countt= mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum_comm` WHERE `forum` = '".$data['id']."' "), 0);
echo 'Автор:<a href="/info.php?id='.$ank['id'].'">'.$ank['nick'].' </a> '.online($ank['id']).'<br>';
echo 'Создана: '.times($data['time']).'<br>';
echo 'Коментариев:  '.$countt.'<br>';
echo 'Тема: '.$data['text'].''; 
echo'</div>';




if($group['author']==$user['id'] || $data['user']==$user['id']){
echo '<div class="foot">';
echo '<span class="nav1"><a href="index.php?act=editForum&forum='.$data['id'].'&id='.$group['id'].'" class=""><img src="/style/forum/inc/izm.png" alt="*"></a></span>';
echo ' <span class="nav1"><a href="index.php?act=delForum&forum='.$data['id'].'&id='.$group['id'].'" class=""><img src="/style/forum/inc/udl.png" alt="*"></a></span></div>';
}
echo '<div class="nav2"></div>';

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum_comm` WHERE `forum`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if ($k_post==0){echo '<div class="err">Комментариев нет</div>';}
$q=mysql_query("SELECT * FROM `group_forum_comm` WHERE `forum`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($comm = mysql_fetch_assoc($q)){


$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $comm[user] LIMIT 1"));


if ($num==0){
echo '<div class="nav1">';
$num=1;}
elseif ($num==1){
echo '<div class="nav2">';
$num=0;}

echo '<a class="usr" href="/info.php?id='.$ank['id'].'">'.$ank['nick'].' </a> '.online($ank['id']).' ('.vremja($comm['time']).')';
if($group['author']==$user['id'] || $data['user']==$user['id']){echo'<a href="index.php?act=delComm&id='.$group['id'].'&forum='.$data['id'].'&p='.$comm['id'].' class="link_s""><img src="/style/icons/delete.gif" alt="*" title="Удалить"></a>';}
$status=mysql_fetch_assoc(mysql_query("SELECT * FROM `status` WHERE `pokaz` = '1' AND `id_user` = '$ank[id]' LIMIT 1"));

if ($status['id'] && $set['st']==1)
{
echo "<div class='st_1'></div>";
echo "<div class='st_2'>";
echo "".output_text($status['msg'])."";
echo "</div>\n";
}


echo output_text($comm['text']).'';

echo "</div>\n";
echo '<div class="clb"></div>';






echo '</li>';
echo '<div class="pdiv"></div>';
}

if ($k_page>1)str('?act=viewForum&id='.$id.'&',$k_page,$page); // Вывод страниц
if(isset($user)){
echo'<div id="cmntMsg" class="uform">';
echo'<form action="index.php?act=viewForum&id='.$id.'" method="post">';
echo'<div><div><div class="smp"><div class="msgc">';
echo'<textarea id="field_msg" cols="20" name="text" class="xpnd" rows="1"></textarea>';
echo'</div></div><div class="mt3">';
echo'<input value="Добавить комментарий" type="submit" name="button_submit" /><span class="spinner"></span></div></div></div></form>';
echo '</div>';

}
echo'</div>';

echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$group['id'].'">'.$group['title'].'</a></span>';
echo' <span class="nav1"><a href="index.php?act=forum&id='.$group['id'].'">Темы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';

break;
###Создание темы
case "addForum":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$set['title']='Создание темы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

echo'<div id="content">';


if((mysql_result(mysql_query("SELECT COUNT(*) FROM `group_users` WHERE `group`='$id' AND `user`='$user[id]'"),0)==1 && $data['forum']==0) || $data['author']==$user['id']){
if(isset($_POST['text']) && $_POST['text']!=NULL){
$text = altec($_POST['text']);

if (utf_strlen($text) >= 5 && utf_strlen($text) < 500) {

mysql_query("INSERT INTO `group_forum` (`group`, `user`, `text`, `time`) values ('$id', '$user[id]', '$text', '$time')");
$id_forum = mysql_insert_id();
$lenta = ' В группе добавлена тема [url=/group/index.php?act=viewForum&id='.$id_forum.']'.truncate_utf8($text, $id_forum).'[/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$id', '$lenta', '$time')");
header("Location: index.php?act=forum&id=$id");
}else{echo'<div class="err">Ошибка! Тема не может быть больше 500 символов и меньше 5 символов!</div>';}
}

echo'<div class="title">Создание темы</div>';

echo'Ведите текст темы:';
echo'<form action="index.php?act=addForum&id='.$id.'" method="post">';
echo'<div><div><div class="smp"><div class="msgc">';
echo'<textarea id="field_msg" cols="20" name="text" class="xpnd" rows="1">'.$forum['text'].'</textarea>';
echo'</div></div><div class="mt3 mb3">';
echo'<input value="Сохранить" type="submit" name="button_create" />  <span class="act"><a class="ai alnk" href="index.php?act=forum&id='.$id.'"><span class="lnk">Отменить</span></a></span></div><span class="spinner"></span></div></div></div></form>';


echo'</form>';







}else{echo'<div class="err">Ошибка! Темы могут создавать только участники группы!</div>';}
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$group['id'].'">'.$group['title'].'</a></span>';
echo' <span class="nav1"><a href="index.php?act=forum&id='.$group['id'].'">Темы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';

break;
###Редактирование темы
case "editForum":
$id = intval($_GET['id']);
$forum = intval($_GET['forum']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `id`='$forum'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_forum` WHERE `id` = '$forum' LIMIT 1"));
$set['title']='Редатирование темы | '.truncate_utf8($forum['text'], $sim_forum); // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo " <div id='content'>\n";

echo'<div class="title">Редактирование темы</div>';




if($forum['user']==$user['id'] || $data['author']==$user['id']){
if(isset($_POST['text']) && $_POST['text']!=NULL){
$text = altec($_POST['text']);
mysql_query("UPDATE `group_forum` SET `text` = '$text' WHERE `id` = '$forum[id]' LIMIT 1");
header("Location: index.php?act=viewForum&id=$forum[id]");
}




echo'Текст темы:';
echo'<form action="index.php?act=editForum&id='.$id.'&forum='.$forum['id'].'" method="post">';
echo'<div><div><div class="smp"><div class="msgc">';
echo'<textarea id="field_msg" cols="20" name="text" class="xpnd" rows="1">'.$forum['text'].'</textarea>';
echo'</div></div><div class="mt3 mb3">';
echo'<input value="Сохранить" type="submit" name="button_create" />  <span class="act"><a class="ai alnk" href="index.php?act=viewForum&id='.$forum['id'].'"><span class="lnk">Отменить</span></a></span></div><span class="spinner"></span></div></div></div></form>';
echo '</div>';






}else{echo'<div class="fsrc fss"><div class="fss">Ошибка! Редатирование темы вам запрещено!</div></div>';}
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$group['id'].'">'.$group['title'].'</a></span>';
echo' <span class="nav1"><a href="index.php?act=forum&id='.$group['id'].'">Темы</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Удаление темы
case "delForum":
$id = intval($_GET['id']);
$forum = intval($_GET['forum']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `id`='$forum'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_forum` WHERE `id` = '$forum' LIMIT 1"));
if($forum['user']==$user['id'] || $data['author']==$user['id']){
mysql_query("DELETE FROM `group_forum_comm` WHERE `forum`='".$forum['id']."'");
mysql_query("DELETE FROM `group_forum` WHERE `id`='".$forum['id']."'");
header("Location: index.php?act=forum&id=$id");
}else{echo'<div class="err">Ошибка! Редатирование темы вам запрещено!</div>';}
break;
###Удаление комментариев
case "delComm":
$id = intval($_GET['id']);
$forum = intval($_GET['forum']);
$p = intval($_GET['p']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum` WHERE `id`='$forum'"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_forum_comm` WHERE `id`='$p'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$forum=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_forum` WHERE `id` = '$forum' LIMIT 1"));
if($forum['user']==$user['id'] || $data['author']==$user['id']){
mysql_query("DELETE FROM `group_forum_comm` WHERE `id`='".$p."'");
header("Location: index.php?act=viewForum&id=$forum[id]");
}else{echo'<div class="err">Ошибка! Вы не можете удалять комментарии</div>';}
break;
###Новости
case "news":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$data[author]' LIMIT 1"));
$set['title']='Новости | '.$data['title']; // заголовок страницы
include_once '../sys/inc/thead.php';
title();

echo'<div id="content">';
echo'<div class="title">';
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$id' LIMIT 1"));
echo'Новости группы: '.$data2['title'].'';
echo'</div>';



$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `group`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Новостей нет!</div>';}
$q=mysql_query("SELECT * FROM `group_news` WHERE `group`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));



echo '';
echo '<div class="nav2"><div class="mt3">';
$countt= mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news_comm` WHERE `news` = '".$data['id']."' "), 0);
echo 'Название: <a  href="index.php?act=viewNews&id='.$data['id'].'">'.$data['text'].'</a><br>';
echo 'Время: '.times($data['time']).'<br>';
echo '<a  href="index.php?act=viewNews&id='.$data['id'].'">Комментирии</a> ('.$countt.')';

echo'<div class="clb"></div><div class="acbb act"><div class="la">';



echo'</div>';
echo'<div class="clb"></div></div></div></div>';



echo '</a>';
echo '<div class="pdiv"></div>';
}
if ($k_page>1)str('index.php?act=forum&id='.$id.'&',$k_page,$page); // Вывод страниц
echo'</div>';
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';

break;

###Редактирование новости
case "editNews":
$id = intval($_GET['id']);
$news = intval($_GET['news']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `id`='$news'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$news=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_news` WHERE `id` = '$news' LIMIT 1"));
$set['title']='Редатирование новость | '.truncate_utf8($news['text'], $sim_news); // заголовок страницы
include_once '../sys/inc/thead.php';
title();
echo " <div id='content'>\n";


echo " <div class='title'>Редактирование новости</div>\n";


if($news['user']==$user['id'] || $data['author']==$user['id']){
if(isset($_POST['text']) && $_POST['text']!=NULL){
$text = altec($_POST['text']);
mysql_query("UPDATE `group_news` SET `text` = '$text' WHERE `id` = '$news[id]' LIMIT 1");
header("Location: index.php?act=viewNews&id=$news[id]");
}


echo'Текст новости:';
echo'<form action="index.php?act=editNews&id='.$id.'&news='.$news['id'].'" method="post">';
echo'<div><div><div class="smp"><div class="msgc">';
echo'<textarea id="field_msg" cols="20" name="text" class="xpnd" rows="1">'.$news['text'].'</textarea>';
echo'</div></div><div class="mt3 mb3">';
echo'<input value="Сохранить" type="submit" name="button_create" />  <span class="act"><a class="ai alnk" href="index.php?act=viewNews&id='.$news['id'].'"><span class="lnk">Отменить</span></a></span></div><span class="spinner"></span></div></div></div></form>';


}else{echo'<div class="err">Ошибка! Редатирование темы вам запрещено!</div>';}
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo' <span class="nav1"><a href="index.php?act=viewNews&id='.$news[id].'">Новости</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
###Удаление Новостей
case "delNews":
$id = intval($_GET['id']);
$news = intval($_GET['news']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `id`='$news'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$news=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_news` WHERE `id` = '$news' LIMIT 1"));
if($news['user']==$user['id'] || $data['author']==$user['id']){
mysql_query("DELETE FROM `group_news_comm` WHERE `news`='".$news['id']."'");
mysql_query("DELETE FROM `group_news` WHERE `id`='".$news['id']."'");
header("Location: index.php?act=news&id=$id");
}else{echo'<div class="err">Ошибка! Редатирование новостей вам запрещено!</div>';}
break;

case "delData":
$id = intval($_GET['id']);
$news = intval($_GET['news']);
$p = intval($_GET['p']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group` WHERE `id`=$id"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `id`='$news'"),0)==0)header("Location: index.php");
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news_comm` WHERE `id`='$p'"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
$news=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_news` WHERE `id` = '$news' LIMIT 1"));
if($news['user']==$user['id'] || $data['author']==$user['id']){
mysql_query("DELETE FROM `group_news_comm` WHERE `id`='".$p."'");
header("Location: index.php?act=viewNews&id=$news[id]");
}else{echo'<div class="err">Ошибка! Вы не можете удалять комментарии</div>';}
break;


### Просмотр новостей
case "viewNews":
$id = intval($_GET['id']);
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news` WHERE `id`=$id"),0)==0)header("Location: index.php");
$data=mysql_fetch_assoc(mysql_query("SELECT * FROM `group_news` WHERE `id` = '$id' LIMIT 1"));
$group = mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = '$data[group]' LIMIT 1"));
$set['title']='Новости | '.truncate_utf8($data['text'], $sim_news); // заголовок страницы
include_once '../sys/inc/thead.php';
title();





echo'<div class="title">';
echo'Комментарии к новости';
echo'</div>';

$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));
$countt= mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news_comm` WHERE `news` = '".$data['id']."' "), 0);

echo'<div class="mess">';
echo 'Автор: <a  href="/info.php?id='.$ank['id'].'">'.$ank['nick'].' </a> '.online($ank['id']).'<br>';
echo 'Создана: '.times($data['time']).'<br>';
echo 'Комментарии:  '.$countt.'<br>';
echo 'Новость:  '.$data['text'].'';
echo'</div>';



echo '<div class="la">';






if($group['author']==$user['id'] || $data['user']==$user['id']){
echo '<div class="foot">';
echo '<span class="nav1"><a href="index.php?act=editNews&news='.$data['id'].'&id='.$group['id'].'" class=""><img src="/style/forum/inc/izm.png" alt="*"></a></span>';
echo ' <span class="nav1"><a href="index.php?act=delNews&news='.$data['id'].'&id='.$group['id'].'" class=""><img src="/style/forum/inc/udl.png" alt="*"></a></span></div>';
}
echo '<div class="pdiv"></div>';








if(isset($_POST['text']) && isset($user)){
$text = altec($_POST['text']);
if (utf_strlen($text) >= 2 && utf_strlen($text) < 500) {
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news_comm` WHERE `user` = '$user[id]' AND `text` = '".$text."' AND `forum`='".$id."' LIMIT 1"),0)==0){
mysql_query("INSERT INTO `group_news_comm` (`news`, `user`, `text`, `time`) values ('$id', '$user[id]', '$text', '$time')");

$lenta = ' Новый комментарий к новости [url=/group/index.php?act=viewNews&id='.$id.']'.$data['text'].'[/url]';
mysql_query("INSERT INTO `group_lenta` (`group`, `text`, `time`) values ('$group[id]', '$lenta', '$time')");
msg("");
}else{echo'<div class="err">Ошибка! Повтор сообщения</div>';}
}else{echo'<div class="err">Ошибка! Комментарий не может быть больше 500 символов и меньше 2 символов!</div>';}
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `group_news_comm` WHERE `news`='$id'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0){echo '<div class="err">Комментариев нет</div>';}
$q=mysql_query("SELECT * FROM `group_news_comm` WHERE `news`='$id' ORDER BY `time` DESC LIMIT $start, $set[p_str]");
while ($data = mysql_fetch_assoc($q)){
$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $data[user] LIMIT 1"));

if ($num==0){
echo '<div class="nav1">';
$num=1;
}
elseif ($num==1){
echo '<div class="nav2">';
$num=0;}


echo '<a  href="/info.php?id='.$ank['id'].'">'.$ank['nick'].' </a> '.online($ank['id']).' ('.vremja($data['time']).')';
if($group['author']==$user['id'] || $data['user']==$user['id']){echo'<a href="index.php?act=delData&id='.$group['id'].'&news='.$id.'&p='.$data['id'].'"><img src="/style/icons/delete.gif" alt="*" title="Удалить"></a>';}
$status=mysql_fetch_assoc(mysql_query("SELECT * FROM `status` WHERE `pokaz` = '1' AND `id_user` = '$ank[id]' LIMIT 1"));
if ($status['id'] && $set['st']==1)
{
echo "<div class='st_1'></div>";
echo "<div class='st_2'>";
echo "".output_text($status['msg'])."";
echo "</div>\n";
}
echo output_text($data['text']).'';
echo '</div>';
echo '<div class="clb"></div>';








echo '<div class="pdiv"></div>';
}

if ($k_page>1)str('?act=viewNews&id='.$id.'&',$k_page,$page); // Вывод страниц
if(isset($user)){
echo'<div id="cmntMsg" class="uform">';
echo'<form action="index.php?act=viewNews&id='.$id.'" method="post">';
echo'<div><div><div class="smp"><div class="msgc">';
echo'<textarea id="field_msg" cols="20" name="text" class="xpnd" rows="1"></textarea>';
echo'</div></div><div class="mt3">';
echo'<input value="Добавить комментарий" type="submit" name="button_submit" /><span class="spinner"></span></div></div></div></form>';
echo '</div>';

}
echo '</div>';
$data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `group` WHERE `id` = $id LIMIT 1"));
echo'<div class="foot">';
echo' <span class="nav1"><a href="index.php">Все группы</a></span>';
echo' <span class="nav1"><a href="/group/group.php?id='.$user['id'].'">Мои группы</a></span>';
echo' <span class="nav1"><a href="index.php?act=view&id='.$data2['id'].'">'.$data2['title'].'</a></span>';
echo'</div>';
include_once '../sys/inc/tfoot.php';
break;
default:
header("location: index.php?");
endswitch;

?>