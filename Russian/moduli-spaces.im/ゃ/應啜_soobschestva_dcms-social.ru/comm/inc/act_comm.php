<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak



$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']); // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if($comm['id_user']!=0 && isset($user))
{
if(isset($_GET['in']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `activate` = '1'"),0)==0)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_blist` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]'"),0)!=0)$err[]="Вы не можете вступить в данное сообщество, так как находитесь в черном списке сообщества!";
else
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '1'"),0)!=0)
{
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$user[id]', `type` = 'in_comm', `time` = '$time'");
mysql_query("UPDATE `comm_users` SET `activate` = '1', `invite` = '0', `time` = '$time' WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '1'");
msg("Приглашение принято");
}
elseif($comm['join_rule']!=3)
{
if($comm['join_rule']==2)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '0' AND `activate` = '0'"),0)==0)
{
mysql_query("INSERT INTO `comm_users` (`id_comm`, `id_user`, `time`, `activate`) VALUES ('$comm[id]', '$user[id]', '".time()."', '0')");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) VALUES ('0', '$ank[id]', '$user[nick] хочет вступить в сообщество [url=/comm/?act=comm&id=$comm[id]]".htmlspecialchars($comm['name'])."[/url].', '$time')");
msg("Вы успешно подали заявку. Дождитесь пока создатель ее рассмотрит");
}
else
{
$err[]="Вы уже подали заявку";
}
}
else
{
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$user[id]', `type` = 'in_comm', `time` = '$time'");
mysql_query("INSERT INTO `comm_users` (`id_comm`, `id_user`, `time`, `activate`) VALUES ('$comm[id]', '$user[id]', '$time', '1')");
msg("Вы успешно вступили в сообщество");
}
}
else $err[]="Сообщество закрытого типа";
}
}
else $err[]="Вы уже являетесь участником сообщества";
}
elseif(isset($_GET['out']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `activate` = '1'"),0)!=0)
{
if($ank['id']==$user['id'] && isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1'"),0)>1)$err[]="Вы создатель сообщества! Для начала удалите всех участников сообщества";
else
{
if($ank['id']==$user['id'] && isset($user))
{
$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak
}

mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$user[id]', `type` = 'out_comm', `time` = '$time'");
mysql_query("DELETE FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `activate` = '1'");
msg("Вы успешно покинули сообщество");
}
}
else $err[]="Вы не являетесь участником сообщества";
}
}
elseif(isset($user) && isset($_GET['creator']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)
{
mysql_query("INSERT INTO `comm_journal` SET `id_comm` = '$comm[id]', `id_user` = '$user[id]', `id_ank` = '".mysql_result(mysql_query("SELECT `id_user` FROM `comm` WHERE `id` = '$comm[id]'"),0)."', `type` = 'access', `time` = '$time', `access` = 'creator'");
mysql_query("UPDATE `comm` SET `id_user` = '$user[id]' WHERE `id` = '$comm[id]'");
mysql_query("INSERT INTO `comm_users` (`id_comm`, `id_user`, `time`, `activate`, `access`) VALUES ('$comm[id]', '$user[id]', '".time()."', '1', 'creator')");
msg("Теперь вы создатель сообщества");
$ank=get_user($user['id']);
}

err();
echo "<div class='p_t'>\n";
echo "<b>".htmlspecialchars($comm['name'])."</b> ".($comm['adult']==1?" <span style='color: red;'>(+18)</span>":NULL)."<br/>\n";
echo "<img src='/comm/img/comm_".($comm['read_rule']==1?"open":"closed").".png' /> ".($comm['read_rule']==1?"Открыто":"Закрыто")." для чтения<br />\n";
echo "</div>\n";
echo "<div class='p_t'>\n";
if (is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))echo "<img src='/comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png'/><br/>\n";
else echo "<img src='/comm/screen_tmp/48-48_0screen.png'/><br/>\n";
if($comm['id_user']!=0)
{
echo "Создатель: ";
echo "<a href='/info.php?id=$ank[id]'>$ank[nick]</a> ".online($ank['id']);
echo "<br /><img src='/comm/img/information.png'/> <a href='/comm/?act=comm_info&id=$comm[id]'>Информация</a><br />\n";
}
echo "</div>\n";
if ($comm['forum']==1 || $comm['chat']==1 || $comm['files']==1)echo "<div class='p_t'>\n";
if ($comm['forum']==1)
{
$count_topics=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic'"),0);
$count_topics_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_forum` WHERE `id_comm` = '$comm[id]' AND `type` = 'topic' AND `time` > '".($time-(3600*24))."'"),0);
$count_topics_show=$count_topics.($count_topics_new>0?"/+$count_topics_new":NULL);
echo "<img src='/comm/img/forum.png' /> <a href='/comm/?act=forum&id=$comm[id]'>Форум ($count_topics_show)</a><br />\n";
}
if ($comm['chat']==1)
{
$count_people=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_comm_who` WHERE `id_comm` = '$comm[id]'"), 0);
echo "<img src='/comm/img/message.png' /> <a href='/comm/?act=chat&id=$comm[id]'>Чат ($count_people)</a><br />\n";
}
if ($comm['files']==1)
{
$count_files=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'file'"),0);
$count_files_new=mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_files` WHERE `id_comm` = '$comm[id]' AND `type` = 'file' AND `time` > '".($time-(3600*24))."'"),0);
$count_files_show=$count_files.($count_files_new>0?"/+$count_files_new":NULL);
echo "<img src='/comm/img/download.png' /> <a href='/comm/?act=files&id=$comm[id]'>Файлы ($count_files_show)</a><br />\n";
}
if ($comm['forum']==1 || $comm['chat']==1 || $comm['files']==1)echo "</div>\n";
echo "<div class='p_t'>\n";
echo "<img src='/comm/img/users.png'/> <a href='/comm/?act=comm_users&id=$comm[id]'>Участники (".mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0).")</a><br />\n";
echo "<img src='/comm/img/users_ban.png'/> <a href='/comm/?act=comm_users_ban&id=$comm[id]'>Нарушители</a><br />\n";
echo "<img src='/comm/img/journal_comm.png'/> <a href='/comm/?act=comm_journal&id=$comm[id]'>Журнал сообщества</a><br />\n";
if($ank['id']==$user['id'] && isset($user) || $uinc['access']=='adm')echo "<img src='/comm/img/blocked.png'/> <a href='/comm/?act=blist&id=$comm[id]'>Черный список</a><br />\n";
if($ank['id']==$user['id'] && isset($user))echo "<img src='/comm/img/settings.png'/> <a href='/comm/?act=comm_settings&id=$comm[id]'>Настройки</a><br />\n";
echo "</div>\n";
if(isset($user))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `activate` = '1'"),0)!=0)
{
echo "<div class='p_t'>\n";
echo "<img src='/comm/img/add.png'/> <a href='/comm/?act=invite&id=$comm[id]'>Пригласить в сообщество</a><br />\n";
echo "<img src='/comm/img/delete.png'/> <a href='/comm/?act=comm&id=$comm[id]&out=1'>Покинуть сообщество</a><br />\n";
echo "<br />\n";
}
else
{
if($comm['id_user']==0)
{
echo "<div class='p_t'>\n";
echo "Сообщество без создателя!<br/><img src='/comm/img/okey.png'/> <a href='/comm/?act=comm&id=$comm[id]&creator=1'>Стать создателем</a><br />\n";
echo "</div>\n";
}
else
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `id_user` = '$user[id]' AND `invite` = '1'"),0)!=0)echo "<div class='p_t'><img src='/comm/img/okey.png'/> <a href='/comm/?act=comm&id=$comm[id]&in=1'>Принять приглашение</a></div>\n";
elseif($comm['join_rule']!=3)echo "<div class='p_t'><img src='/comm/img/okey.png'/> <a href='/comm/?act=comm&id=$comm[id]&in=1'>Вступить в сообщество</a></div>\n";
}
}
}
?>
  <div class="p_t">
	<div>
	    Ссылка на сообщество (для отправки друзьям):<br />
	    <a href="http://<? echo $_SERVER['HTTP_HOST'].'/comm/'.$comm['id'];?>/"><span>http://<? echo $_SERVER['HTTP_HOST'].'/comm/'.$comm['id'];?>/</span></a>
	</div>
	
	
	    <div id="sharing_buttons">
		<div class='pluso pluso-theme-color pluso-small' style="padding-left:0;"><a class='pluso-vkontakte' style='margin-right:6px;'></a><a class='pluso-odnoklassniki' style='margin-right:6px;'></a><a class='pluso-facebook' style='margin-right:6px;'></a><a class='pluso-twitter' style='margin-right:6px;'></a><a class='pluso-moimir' style='margin-right:6px;'></a><a class='pluso-livejournal' style='margin-right:6px;'></a><a class='pluso-google' style='margin-right:6px;'></a><a class='pluso-email' style='margin-right:6px;'></a></div>
		<script type='text/javascript'>if(!window.pluso){pluso={version:'0.9.1',url:'http://share.pluso.ru/'};h=document.getElementsByTagName('head')[0];l=document.createElement('link');l.href=pluso.url+'pluso.css';l.type='text/css';l.rel='stylesheet';s=document.createElement('script');s.src=pluso.url+'pluso.js';s.charset='UTF-8';h.appendChild(l);h.appendChild(s)}</script>
	    </div><!-- #sharing_buttons -->
	
    </div>
    
<?
echo "<div class='foot'>&raquo; <a href='/comm/?act=cat&id=$cat[id]'>".htmlspecialchars($cat['name'])."</a> | <a href='/comm/'>Категории</a><br/></div>\n";
}
else{header("Location:/comm");exit;}
?>