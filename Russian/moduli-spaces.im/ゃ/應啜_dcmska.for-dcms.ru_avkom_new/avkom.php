<?
	
	/*
	
	Добавляем комментарии к аватару
	запрос ниже
	выкладывать аватарпхп и инфопхп не стал т.к у всех они уже проредаченные
	если аватар незагружен комментить нельзя
	когда юзер изменяет аватар старые комменты очищаются
	
	
	
	
	CREATE TABLE IF NOT EXISTS `avkom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `msg` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_av` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;





1. В аватар.пхп добавляем после каждой строки можно перед каждой строки)
msg("Аватар успешно установлен");

вот это добавляем
mysql_query("DELETE FROM `avkom` WHERE `id_av`= '$user[id]' ");

там три раза надо добавить

2. в инфо добавляем

if (is_file(H."/sys/avatar/$ank[id].gif") || is_file(H."/sys/avatar/$ank[id].jpg") || is_file(H."/sys/avatar/$ank[id].png")){
echo '<a href="/avkom.php?id='.$ank['id'].'">Комммменннтариии к аватару</a>';
}

3. выполняем скул

4. почему не работает? 
возможно потому что ты не прочитал этот файл, 
или руки не там где надо, или у меня руки не там где надо, 
или у тебя не 6.6.4, 
или у тебя вообще не дцмс,




автор: Дикий

*/



include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

if (isset($user))$ank['id']=$user['id'];
if (isset($_GET['id']))$ank['id']=intval($_GET['id']);


if ($ank['id']==0)
{
$ank=get_user($ank['id']);
$set['title']=$ank['nick'].' - Комментарии аватара '; // заголовок страницы
include_once 'sys/inc/thead.php';
title();
aut();
echo "<span class=\"status\">$ank[group_name]</span><br />\n";

if ($ank['ank_o_sebe']!=NULL)echo "<span class=\"ank_n\">О себе:</span> <span class=\"ank_d\">$ank[ank_o_sebe]</span><br />\n";




if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "<div class='foot'>&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n</div>\n";

include_once 'sys/inc/tfoot.php';
exit;
}

$ank=get_user($ank['id']);
if(!$ank){header("Location: /index.php?".SID);exit;}








$ank['rating']=intval(@mysql_result(mysql_query("SELECT SUM(`rating`) FROM `user_voice2` WHERE `id_kont` = '$ank[id]'"),0));
$set['title']=$ank['nick'].' - Комментарии аватара '; // заголовок страницы
include_once 'sys/inc/thead.php';
title();


if ((!isset($_SESSION['refer']) || $_SESSION['refer']==NULL)
&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL &&
!ereg('info\.php',$_SERVER['HTTP_REFERER']))
$_SESSION['refer']=str_replace('&','&amp;',ereg_replace('^http://[^/]*/','/', $_SERVER['HTTP_REFERER']));


aut();


if (!is_file(H."/sys/avatar/$ank[id].gif") && !is_file(H."/sys/avatar/$ank[id].jpg") && !is_file(H."/sys/avatar/$ank[id].png")){

echo "<div class='err'>Аватар не загружен!</div>";

echo "<div class='foot'><a href='/info.php?id=$ank[id]'>Вернуться на страницу $ank[nick]</a></div>";
include_once 'sys/inc/tfoot.php';

exit;

}
//if ($ank['group_access']>1)echo "<span class='status'>$ank[group_name]</span><br />\n";




echo "<div class='menu'><center>";
avatar($ank['id']);

echo "</center></div>";
//include 'inc/admin_act.php';

if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);

$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (strlen2($msg)>1024){$err[]='Сообщение слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `avkom` WHERE `id_user` = '$user[id]' AND `id_av`='$ank[id]' AND `msg` = '".my_esc($msg)."' LIMIT 1"),0)!=0){$err='Ваше сообщение повторяет предыдущее';}
elseif(!isset($err)){



mysql_query("INSERT INTO `avkom` (id_user, time, msg, id_av) values('$user[id]', '$time', '".my_esc($msg)."', '$ank[id]')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$ank[id]', '[url=/info.php?id=$user[id]]$user[nick][/url] оставил комментарий вашему [url=/avkom.php?id=$ank[id]]Аватару[/url]', '$time')");
msg('Сообщение успешно добавлено');


}
}



err();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `avkom` WHERE `id_av`='$ank[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
echo "<table class='post'>\n";
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Комментариев нет\n";
echo "  </td>\n";
echo "   </tr>\n";

}

$q=mysql_query("SELECT * FROM `avkom` WHERE `id_av`='$ank[id]' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_assoc($q))
{

$ank12=get_user($post['id_user']);
//$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
echo "   <tr>\n";
if ($set['set_show_icon']==2){
echo "  <td class='icon48' rowspan='2'>\n";
avatar($ank12['id']);
echo "  </td>\n";
}
elseif ($set['set_show_icon']==1)
{
echo "  <td class='icon14'>\n";
echo "<img src='/style/themes/$set[set_them]/user/$ank12[pol].png' alt='' />";
echo "  </td>\n";
}



echo "  <td class='p_t'>\n";
echo "".online($ank12['id'])."<a href='/avdel.php?id=$ank12[id]'><span style=\"color:$ank[ncolor]\">$ank12[nick]</span></a> (".vremja($post['time']).")\n";
echo "  </td>\n";
echo "   </tr>\n";
echo "   <tr>\n";
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
echo output_text($post['msg'])."<br />\n";
if (user_access('guest_delete'))
echo "<a href='avdel.php?id=$post[id]'>Удалить</a><br />\n";
echo "  </td>\n";
echo "   </tr>\n";

}
echo "</table>\n";




if ($k_page>1)str('?id='.$ank['id'].'&amp;',$k_page,$page); // Вывод страниц

if (isset($user))
{
echo "<form method=\"post\" name='message' action=\"?id=$ank[id]&amp;$passgen\">\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Добавить коммент:<br />\n<textarea name=\"msg\"></textarea><br />\n";
echo "<label><input type=\"checkbox\" name=\"translit\" value=\"1\" /> Транслит</label><br />\n";
echo "<input value=\"Отправить\" type=\"submit\" />\n";
echo "</form>\n";
}




//include 'inc/admin_form.php';
echo "<div class='prod0'><a href='/info.php?id=$ank[id]'>Вернуться на страницу $ank[nick]</a></div>";
include_once 'sys/inc/tfoot.php';
?>
